<?php
/**
 * ConsultWays Hub - Apps & Ecosystem Catalog API
 * Subdomain: hub.consultways.com
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config.php';

$appsFile = DATA_PATH . '/apps.json';
if (!file_exists($appsFile)) {
    echo json_encode(['success' => false, 'error' => 'Apps data file not found']);
    exit;
}

$apps = json_decode(file_get_contents($appsFile), true) ?: [];

$developer = $_GET['developer'] ?? 'All';

if ($developer !== 'All') {
    $apps = array_values(array_filter($apps, function($a) use ($developer) {
        return stripos($a['developer'] ?? '', $developer) !== false;
    }));
}

echo json_encode([
    'success' => true,
    'total' => count($apps),
    'apps' => $apps
]);
