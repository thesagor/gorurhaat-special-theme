<?php
/**
 * Add WooCommerce support
 */
function hello_elementor_child_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'hello_elementor_child_add_woocommerce_support' );

/**
 * Set products per page on shop page
 * 
 * @param int $per_page Products per page
 * @return int Modified products per page
 */
function hello_elementor_child_products_per_page( $per_page ) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        return 12; // Display 12 products per page
    }
    return $per_page;
}
add_filter( 'loop_shop_per_page', 'hello_elementor_child_products_per_page' );
