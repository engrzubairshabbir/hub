<?php
/**
 * ConsultWays Hub - Core System Configuration
 * Subdomain: hub.consultways.com
 */

// Helper to safely load key-value pairs from .env files
function load_env_file($filePath) {
    if (!file_exists($filePath) || !is_readable($filePath)) return;
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0 || strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// 1. Look for .env one level above webroot (/home/consultw/.env - SAFEST)
load_env_file(dirname(__DIR__) . '/.env');

// 2. Look for .env in current directory (/home/consultw/hub.consultways.com/.env)
load_env_file(__DIR__ . '/.env');

// 3. Look for config.local.php (ignored by Git)
$localConfig = __DIR__ . '/config.local.php';
if (file_exists($localConfig)) {
    require_once $localConfig;
}

define('APP_NAME', 'ConsultWays Hub');
define('APP_TAGLINE', 'Global Software Studio & Digital Commerce Hub');
define('APP_DOMAIN', 'hub.consultways.com');
define('APP_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'hub.consultways.com'));
define('ROOT_DOMAIN', 'https://consultways.com');
define('SUPPORT_EMAIL', 'support@consultways.com');

// Google AdSense Configuration
define('ADSENSE_CLIENT_ID', getenv('ADSENSE_CLIENT_ID') ?: 'ca-pub-8661538987218500');
define('ADSENSE_ENABLED', true);

// AI Verification (Gemini API / OpenAI API)
// Loaded SECURELY from .env or server environment (NEVER committed to Git)
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
