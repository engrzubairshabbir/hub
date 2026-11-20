<?php
/**
 * ConsultWays Hub - Main Hybrid Application Router
 * Subdomain: hub.consultways.com
 */

require_once __DIR__ . '/config.php';

// Determine View from query parameter or request URI
$view = $_GET['view'] ?? 'home';
$slug = $_GET['slug'] ?? '';

// Sanitize view
$allowedViews = [
    'home' => ['title' => 'ConsultWays Hub — Apps, Tools & Viral Free Marketplace', 'desc' => 'Discover official Play Store Android apps, desktop SaaS tools, and viral free listings from verified sellers. Prices $20 to $200 USD.'],
    'apps' => ['title' => 'Official Play Store Apps & SaaS Tools — ConsultWays Hub', 'desc' => 'Explore 21+ mobile and desktop applications from ConsultWays Limited and Twinkling Bytes Innovations.'],
    'marketplace' => ['title' => 'Free Multi-Vendor Marketplace — ConsultWays Hub', 'desc' => 'Alibaba/Temu style free listings for physical products, software downloads, and freelance services.'],
    'post-listing' => ['title' => 'Post Free Listing & Open Your Store — ConsultWays Hub', 'desc' => 'List your products or services in 10 seconds. Auto-WebP image compression and real-time AI moderation.'],
    'dashboard' => ['title' => 'User Command Center & Live Tracking — ConsultWays Hub', 'desc' => 'Track orders, access digital downloads vault, manage free listings, and communicate with verified sellers.'],
    'privacy' => ['title' => 'Privacy Policy — ConsultWays Hub', 'desc' => 'ConsultWays Limited privacy policy and data protection standards.'],
    'terms' => ['title' => 'Terms of Service — ConsultWays Hub', 'desc' => 'Terms of service and merchant listing rules on hub.consultways.com.'],
    'refund' => ['title' => 'Refund & Return Policy — ConsultWays Hub', 'desc' => 'Buyer protection, dispute handling and refund guidelines on ConsultWays Hub.'],
    'shipping' => ['title' => 'Shipping & Digital Delivery Policy — ConsultWays Hub', 'desc' => 'Global shipping timelines, carrier information, and instant digital download delivery.']
];

if (!array_key_exists($view, $allowedViews)) {
    $view = 'home';
}

$pageInfo = $allowedViews[$view];
$pageTitle = $pageInfo['title'];
$pageDesc = $pageInfo['desc'];
$ogImage = 'https://consultways.com/wp-content/uploads/2025/01/Discover-the-Future-of-Online-Consultancy-with-Consultways.com-in-UK-USA-and-EU.webp';
$currentView = $view;

// If viewing a specific item slug, customize SEO tags dynamically
if ($view === 'marketplace' && !empty($slug)) {
    $listingsFile = DATA_PATH . '/listings.json';
    if (file_exists($listingsFile)) {
        $items = json_decode(file_get_contents($listingsFile), true) ?: [];
        foreach ($items as $item) {
            if (($item['slug'] ?? '') === $slug || ($item['id'] ?? '') === $slug) {
                $pageTitle = e($item['title']) . ' (' . e($item['price_display']) . ') — ConsultWays Hub';
                $pageDesc = substr(strip_tags($item['description'] ?? ''), 0, 160);
                if (!empty($item['image_url'])) {
                    $ogImage = $item['image_url'];
                }
                break;
            }
        }
    }
}

// Render Header
require_once VIEWS_PATH . '/header.php';

// Render Target View
$viewFile = VIEWS_PATH . '/' . $view . '.php';
if (file_exists($viewFile)) {
    require_once $viewFile;
} else {
    require_once VIEWS_PATH . '/home.php';
}

// Render Footer
require_once VIEWS_PATH . '/footer.php';
