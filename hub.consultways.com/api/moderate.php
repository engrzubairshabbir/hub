<?php
/**
 * ConsultWays Hub - AI Auto-Approval Pipeline & Moderation Engine
 * Subdomain: hub.consultways.com
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true) ?: [];

$title = trim($data['title'] ?? '');
$type = trim($data['type'] ?? 'Physical Products');
$priceUsd = (float)($data['price_usd'] ?? 0);
$sellerName = trim($data['seller_name'] ?? 'Merchant');
$sellerEmail = trim($data['seller_email'] ?? '');
$description = trim($data['description'] ?? '');
$imageBase64 = $data['image_base64'] ?? '';

if (!$title || !$description || !$sellerEmail) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields (title, description, or seller email).'
    ]);
    exit;
}

// Strict pricing validation: $20 to $200 USD
if ($priceUsd < 20.0 || $priceUsd > 200.0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Price must be strictly between $20.00 and $200.00 USD.'
    ]);
    exit;
}

// 1. Process & Save Image with Auto-WebP Compression
$imageUrl = 'https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp';
if (!empty($imageBase64) && strpos($imageBase64, 'base64,') !== false) {
    $parts = explode('base64,', $imageBase64);
    $decoded = base64_decode($parts[1]);
    if ($decoded) {
        $filename = 'listing_' . time() . '_' . substr(md5(uniqid()), 0, 8) . '.webp';
        $savePath = UPLOADS_PATH . '/' . $filename;

        // Try GD optimization if available
        if (function_exists('imagecreatefromstring') && function_exists('imagewebp')) {
            $img = @imagecreatefromstring($decoded);
            if ($img !== false) {
                $w = imagesx($img);
                $h = imagesy($img);
                $maxDim = 1000;
                if ($w > $maxDim || $h > $maxDim) {
                    $ratio = $w / $h;
                    if ($ratio > 1) {
                        $newW = $maxDim;
                        $newH = (int)($maxDim / $ratio);
                    } else {
                        $newH = $maxDim;
                        $newW = (int)($maxDim * $ratio);
                    }
                    $thumb = imagecreatetruecolor($newW, $newH);
                    imagecopyresampled($thumb, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
                    imagewebp($thumb, $savePath, 80);
                    imagedestroy($thumb);
                } else {
                    imagewebp($img, $savePath, 80);
                }
                imagedestroy($img);
                $imageUrl = '/uploads/' . $filename;
            } else {
                file_put_contents($savePath, $decoded);
                $imageUrl = '/uploads/' . $filename;
            }
        } else {
            file_put_contents($savePath, $decoded);
            $imageUrl = '/uploads/' . $filename;
        }
    }
}

// 2. AI Content Moderation Check
$aiScore = 100;
$flaggedReasons = [];
$geminiKey = GEMINI_API_KEY;

// Prohibited heuristic keywords
$bannedKeywords = [
    'counterfeit', 'replica', 'fake brand', 'stolen', 'hack', 'crack', 'warez',
    'weapon', 'firearm', 'narcotics', 'drug', 'adult 18+', 'viagra', 'casino', 'betting'
];

foreach ($bannedKeywords as $bad) {
    if (stripos($title, $bad) !== false || stripos($description, $bad) !== false) {
        $aiScore -= 50;
        $flaggedReasons[] = "Matches prohibited category policy keyword: '{$bad}'";
    }
}

// Call Gemini API if key is present
if (!empty($geminiKey) && function_exists('curl_init')) {
    $prompt = "You are a content safety moderator for ConsultWays Global Marketplace. Check if this product listing is safe, authentic, and legal to publish. Title: '{$title}', Description: '{$description}', Price: \${$priceUsd}. Reply in valid JSON only: {\"safe\": true/false, \"reason\": \"summary\"}";
    
    $ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . urlencode($geminiKey));
    $payload = json_encode([
        'contents' => [
            ['parts' => [['text' => $prompt]]]
        ]
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $response) {
        $resJson = json_decode($response, true);
        $aiText = $resJson['candidates'][0]['content']['parts'][0]['text'] ?? '';
        if (preg_match('/\{.*\}/s', $aiText, $matches)) {
            $parsed = json_decode($matches[0], true);
            if ($parsed && isset($parsed['safe']) && $parsed['safe'] === false) {
                $aiScore = 40;
                $flaggedReasons[] = "Gemini AI Flag: " . ($parsed['reason'] ?? 'Potentially misleading or prohibited content');
            }
        }
    }
}

$isSafe = ($aiScore >= 80 && count($flaggedReasons) === 0);

$listingsFile = DATA_PATH . '/listings.json';
$queueFile = DATA_PATH . '/moderation_queue.json';

if ($isSafe && AI_AUTO_APPROVE_SAFE) {
    // 3. Auto-Publish Safe Listing
    $listings = json_decode(file_get_contents($listingsFile), true) ?: [];
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    $newId = 'cw-user-' . time();

    $newListing = [
        'id' => $newId,
        'source' => 'hub.consultways.com',
        'type' => $type,
        'title' => $title,
        'slug' => $slug . '-' . substr(md5(uniqid()), 0, 4),
        'price_usd' => $priceUsd,
        'price_display' => '$' . number_format($priceUsd, 2),
        'original_price_raw' => $priceUsd,
        'image_url' => $imageUrl,
        'description' => $description,
        'seller_name' => $sellerName,
        'seller_verified' => false,
        'rating' => '5.0',
        'reviews_count' => 1,
        'status' => 'approved',
        'featured' => false,
        'tags' => [$type, 'Verified Clean', 'New Listing'],
        'created_at' => date('Y-m-d H:i')
    ];

    array_unshift($listings, $newListing);
    file_put_contents($listingsFile, json_encode($listings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode([
        'status' => 'approved',
        'message' => 'Listing automatically verified by AI and published!',
        'listing' => $newListing
    ]);
    exit;

} else {
    // 4. Hold in Admin Moderation Queue
    $queue = json_decode(file_get_contents($queueFile), true) ?: [];
    $queueItem = [
        'queue_id' => 'MOD-Q-' . time(),
        'title' => $title,
        'type' => $type,
        'price' => $priceUsd,
        'seller_name' => $sellerName,
        'seller_email' => $sellerEmail,
        'submitted_at' => date('Y-m-d H:i'),
        'ai_status' => 'flagged_for_review',
        'ai_score' => $aiScore,
        'ai_feedback' => implode('; ', $flaggedReasons) ?: 'Requires routine merchant verification',
        'image_url' => $imageUrl
    ];

    array_unshift($queue, $queueItem);
    file_put_contents($queueFile, json_encode($queue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode([
        'status' => 'queued',
        'message' => 'Your listing has been submitted and is currently in the Admin Moderation Queue for review.',
        'queue_id' => $queueItem['queue_id'],
        'reasons' => $flaggedReasons
    ]);
    exit;
}
