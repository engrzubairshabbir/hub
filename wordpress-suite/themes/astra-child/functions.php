<?php
/**
 * Astra Child Theme Functions - ConsultWays Hub
 */

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('astra-parent-theme-css', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('astra-child-theme-css', get_stylesheet_uri(), ['astra-parent-theme-css']);
});

// Enforce Price Range in WooCommerce ($20 to $200 USD)
add_filter('woocommerce_add_to_cart_validation', function($passed, $product_id, $quantity) {
    $product = wc_get_product($product_id);
    if ($product) {
        $price = (float)$product->get_price();
        if ($price < 20.00 || $price > 200.00) {
            wc_add_notice(__('This listing price does not comply with the ConsultWays Hub standard ($20 - $200 USD).', 'consultways-hub'), 'error');
            return false;
        }
    }
    return $passed;
}, 10, 3);
