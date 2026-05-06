<?php
/**
 * Shop Page — Custom Filter Query Hooks
 *
 * Handles GET parameters that WooCommerce does not process natively:
 *   ?filter_product_type_category=livestock
 *   ?instock=1
 *
 * Standard WooCommerce parameters (product_cat, min_price, max_price, orderby)
 * are handled by WooCommerce itself and need no extra code.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Inject custom taxonomy + in-stock filters into the main WooCommerce query
 */
function gorurhaat_shop_custom_filters( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $is_shop_page = $query->is_post_type_archive( 'product' )
                 || $query->is_tax( array( 'product_cat', 'product_tag', 'product_type_category' ) );

    if ( ! $is_shop_page ) {
        return;
    }

    $tax_query  = (array) $query->get( 'tax_query' );
    $meta_query = (array) $query->get( 'meta_query' );

    // ── Product type category (custom taxonomy) ──────────────────────────────
    if ( ! empty( $_GET['filter_product_type_category'] ) ) {
        $type_slug = sanitize_title( wp_unslash( $_GET['filter_product_type_category'] ) );
        $tax_query[] = array(
            'taxonomy' => 'product_type_category',
            'field'    => 'slug',
            'terms'    => $type_slug,
            'operator' => 'IN',
        );
        $query->set( 'tax_query', $tax_query );
    }

    // ── In-stock filter ───────────────────────────────────────────────────────
    if ( ! empty( $_GET['instock'] ) ) {
        $meta_query[] = array(
            'key'     => '_stock_status',
            'value'   => 'instock',
            'compare' => '=',
        );
        $query->set( 'meta_query', $meta_query );
    }
}
add_action( 'pre_get_posts', 'gorurhaat_shop_custom_filters' );

/**
 * Remove WooCommerce breadcrumb on shop pages (we output our own via seo-optimization.php)
 */
add_filter( 'woocommerce_before_main_content', function() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}, 1 );

/**
 * Remove default WooCommerce sidebar on shop page
 * (our template handles layout itself)
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Set column count for the product loop to 3
 */
function gorurhaat_shop_loop_columns() {
    if ( is_shop() || is_product_category() ) {
        return 3;
    }
    return 4;
}
add_filter( 'loop_shop_columns', 'gorurhaat_shop_loop_columns' );

/**
 * Ensure 12 products per page on shop/category pages
 */
function gorurhaat_shop_products_per_page( $per_page ) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        return 12;
    }
    return $per_page;
}
add_filter( 'loop_shop_per_page', 'gorurhaat_shop_products_per_page', 20 );
