<?php
/**
 * ConsultWays Hub - Listings API
 * Subdomain: hub.consultways.com
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config.php';

$listingsFile = DATA_PATH . '/listings.json';
if (!file_exists($listingsFile)) {
    echo json_encode(['success' => false, 'error' => 'Listings data file not found']);
    exit;
}

$listings = json_decode(file_get_contents($listingsFile), true) ?: [];

// Query Filters
$category = $_GET['category'] ?? 'All';
$search = strtolower(trim($_GET['q'] ?? ''));
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : 200.0;
$slug = $_GET['slug'] ?? '';

if ($slug) {
    foreach ($listings as $item) {
        if (($item['slug'] ?? '') === $slug || ($item['id'] ?? '') === $slug) {
            echo json_encode(['success' => true, 'item' => $item]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'error' => 'Listing not found']);
    exit;
}

$filtered = array_values(array_filter($listings, function($item) use ($category, $search, $maxPrice) {
    if ($category !== 'All' && ($item['type'] ?? '') !== $category) {
        return false;
    }
    if ($maxPrice && ($item['price_usd'] ?? 0) > $maxPrice) {
        return false;
    }
    if ($search) {
        $title = strtolower($item['title'] ?? '');
        $desc = strtolower($item['description'] ?? '');
        $seller = strtolower($item['seller_name'] ?? '');
        if (strpos($title, $search) === false && strpos($desc, $search) === false && strpos($seller, $search) === false) {
            return false;
        }
    }
    return true;
}));

echo json_encode([
    'success' => true,
    'total' => count($filtered),
    'items' => $filtered
]);
