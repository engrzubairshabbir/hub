<?php
/**
 * ConsultWays Hub - Orders & Live Tracking API
 * Subdomain: hub.consultways.com
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config.php';

$ordersFile = DATA_PATH . '/orders.json';
$orders = file_exists($ordersFile) ? (json_decode(file_get_contents($ordersFile), true) ?: []) : [];

$orderId = $_GET['order_id'] ?? '';

if ($orderId) {
    foreach ($orders as $o) {
        if (($o['order_id'] ?? '') === $orderId) {
            echo json_encode(['success' => true, 'order' => $o]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'error' => 'Order not found']);
    exit;
}

echo json_encode([
    'success' => true,
    'total' => count($orders),
    'orders' => $orders
]);
