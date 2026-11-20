<?php
/**
 * ConsultWays Hub - Core System Configuration
 * Subdomain: hub.consultways.com
 */

define('APP_NAME', 'ConsultWays Hub');
define('APP_TAGLINE', 'Global Software Studio & Digital Commerce Hub');
define('APP_DOMAIN', 'hub.consultways.com');
define('APP_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'hub.consultways.com'));
define('ROOT_DOMAIN', 'https://consultways.com');
define('SUPPORT_EMAIL', 'support@consultways.com');

// Google AdSense Configuration
define('ADSENSE_CLIENT_ID', 'ca-pub-8661538987218500');
define('ADSENSE_ENABLED', true);

// AI Verification (Gemini API / OpenAI API)
// Configurable via Admin Settings or Server Environment Variable
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: '');
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: '');
define('AI_AUTO_APPROVE_SAFE', true);

// Play Store Developer IDs
define('PLAYSTORE_DEV_CONSULTWAYS', '8553158602497140879');
define('PLAYSTORE_DEV_TWINKLINGBYTES', '7550803575516852992');

// Storage Paths
define('DATA_PATH', __DIR__ . '/data');
define('UPLOADS_PATH', __DIR__ . '/uploads');
define('VIEWS_PATH', __DIR__ . '/views');

// Helper to sanitize output
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

// Helper to format prices
function format_usd($amount) {
    return '$' . number_format((float)$amount, 2);
}
