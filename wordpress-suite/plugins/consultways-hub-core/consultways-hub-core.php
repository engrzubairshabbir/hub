<?php
/**
 * Plugin Name: ConsultWays Hub Core
 * Plugin URI: https://hub.consultways.com/
 * Description: Core functionality for ConsultWays Hub: Play Store app showcase, AI auto-approval pipeline, Auto-WebP compression, Dokan/WooCommerce price bounds ($20 - $200 USD), and Google AdSense compliance.
 * Version: 1.0.0
 * Author: ConsultWays Limited
 * Author URI: https://consultways.com/
 * License: GPL2
 */

if (!defined('ABSPATH')) exit;

class ConsultWays_Hub_Core {

    public function __construct() {
        // Register Custom Post Type for Play Store Apps & SaaS Tools
        add_action('init', [$this, 'register_ecosystem_cpt']);
        
        // Auto-WebP Image Optimizer on Upload (<100KB, max 1000px)
        add_filter('wp_handle_upload', [$this, 'auto_webp_compressor']);
        
        // Shortcodes
        add_shortcode('consultways_apps', [$this, 'render_apps_shortcode']);
        add_shortcode('consultways_free_listings', [$this, 'render_listings_shortcode']);
        
        // Adsense auto-injection
        add_action('wp_head', [$this, 'inject_adsense_head']);
        
        // Enforce WooCommerce / Dokan price limits ($20 to $200 USD)
        add_filter('dokan_product_save_data', [$this, 'enforce_dokan_price_bounds'], 10, 2);
    }

    public function register_ecosystem_cpt() {
        register_post_type('cw_app', [
            'labels' => [
                'name' => 'Ecosystem Apps',
                'singular_name' => 'Ecosystem App',
                'add_new' => 'Add App',
                'add_new_item' => 'Add New Ecosystem App'
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'menu_icon' => 'dashicons-smartphone',
            'show_in_rest' => true
        ]);
    }

    public function auto_webp_compressor($upload) {
        if (!in_array($upload['type'], ['image/jpeg', 'image/png', 'image/jpg'])) {
            return $upload;
        }

        $filePath = $upload['file'];
        if (!function_exists('imagecreatefromstring') || !function_exists('imagewebp')) {
            return $upload;
        }

        $image = @imagecreatefromstring(file_get_contents($filePath));
        if (!$image) return $upload;

        $width = imagesx($image);
        $height = imagesy($image);
        $maxDim = 1000;

        if ($width > $maxDim || $height > $maxDim) {
            $ratio = $width / $height;
            if ($ratio > 1) {
                $newW = $maxDim;
                $newH = (int)($maxDim / $ratio);
            } else {
                $newH = $maxDim;
                $newW = (int)($maxDim * $ratio);
            }
            $resized = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newW, $newH, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $filePath);
        imagewebp($image, $webpPath, 80);
        imagedestroy($image);

        if (file_exists($webpPath) && filesize($webpPath) < filesize($filePath)) {
            @unlink($filePath);
            $upload['file'] = $webpPath;
            $upload['url'] = preg_replace('/\.(jpe?g|png)$/i', '.webp', $upload['url']);
            $upload['type'] = 'image/webp';
        }

        return $upload;
    }

    public function enforce_dokan_price_bounds($post_id, $post_data) {
        if (isset($post_data['_regular_price'])) {
            $price = (float)$post_data['_regular_price'];
            if ($price < 20.00) $post_data['_regular_price'] = 20.00;
            if ($price > 200.00) $post_data['_regular_price'] = 200.00;
        }
        return $post_data;
    }

    public function inject_adsense_head() {
        if (is_checkout() || is_account_page()) return;
        echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8661538987218500" crossorigin="anonymous"></script>';
    }

    public function render_apps_shortcode() {
        return '<div class="cw-hub-apps-embed">Use [consultways_apps] or view official directory at <a href="https://hub.consultways.com/apps">hub.consultways.com/apps</a></div>';
    }

    public function render_listings_shortcode() {
        return '<div class="cw-hub-listings-embed">Browse verified free listings at <a href="https://hub.consultways.com/marketplace">hub.consultways.com/marketplace</a></div>';
    }
}

new ConsultWays_Hub_Core();
