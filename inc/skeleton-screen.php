<?php
/**
 * Skeleton Screen Templates
 * Provides loading placeholders for various content types
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Product Grid Skeleton
 * 
 * @param int $count Number of skeleton items to display
 */
function render_skeleton_product_grid( $count = 8 ) {
    ?>
    <div class="skeleton-container skeleton-product-grid" data-skeleton="product-grid">
        <?php for ( $i = 0; $i < $count; $i++ ) : ?>
            <div class="skeleton-product-card">
                <div class="skeleton skeleton-product-image"></div>
                <div class="skeleton skeleton-product-title"></div>
                <div class="skeleton skeleton-product-price"></div>
                <div class="skeleton skeleton-product-rating"></div>
                <div class="skeleton skeleton-product-button"></div>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

/**
 * Single Product Skeleton
 */
function render_skeleton_single_product() {
    ?>
    <div class="skeleton-container skeleton-single-product" data-skeleton="single-product">
        <div class="skeleton-product-gallery">
            <div class="skeleton skeleton-main-image"></div>
            <div class="skeleton-thumbnail-grid">
                <?php for ( $i = 0; $i < 4; $i++ ) : ?>
                    <div class="skeleton skeleton-thumbnail"></div>
                <?php endfor; ?>
            </div>
        </div>
        <div class="skeleton-product-details">
            <div class="skeleton skeleton-product-category"></div>
            <div class="skeleton skeleton-product-name"></div>
            <div class="skeleton skeleton-product-price"></div>
            <div class="skeleton skeleton-product-meta"></div>
            <div class="skeleton-product-description">
                <div class="skeleton"></div>
                <div class="skeleton"></div>
                <div class="skeleton"></div>
                <div class="skeleton"></div>
            </div>
            <div class="skeleton skeleton-add-to-cart"></div>
        </div>
    </div>
    <?php
}

/**
 * Blog Post List Skeleton
 * 
 * @param int $count Number of skeleton posts to display
 */
function render_skeleton_post_list( $count = 5 ) {
    ?>
    <div class="skeleton-container skeleton-post-list" data-skeleton="post-list">
        <?php for ( $i = 0; $i < $count; $i++ ) : ?>
            <div class="skeleton-post-item">
                <div class="skeleton skeleton-post-featured-image"></div>
                <div class="skeleton skeleton-post-title"></div>
                <div class="skeleton skeleton-post-meta"></div>
                <div class="skeleton-post-excerpt">
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                    <div class="skeleton"></div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

/**
 * Cart Items Skeleton
 * 
 * @param int $count Number of skeleton cart items to display
 */
function render_skeleton_cart( $count = 3 ) {
    ?>
    <div class="skeleton-container skeleton-cart" data-skeleton="cart">
        <?php for ( $i = 0; $i < $count; $i++ ) : ?>
            <div class="skeleton-cart-item">
                <div class="skeleton skeleton-cart-item-image"></div>
                <div class="skeleton-cart-item-details">
                    <div class="skeleton skeleton-cart-item-name"></div>
                    <div class="skeleton skeleton-cart-item-meta"></div>
                </div>
                <div class="skeleton skeleton-cart-item-price"></div>
                <div class="skeleton skeleton-cart-item-remove"></div>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

/**
 * Category Grid Skeleton
 * 
 * @param int $count Number of skeleton categories to display
 */
function render_skeleton_category_grid( $count = 6 ) {
    ?>
    <div class="skeleton-container skeleton-category-grid" data-skeleton="category-grid">
        <?php for ( $i = 0; $i < $count; $i++ ) : ?>
            <div class="skeleton-category-card">
                <div class="skeleton skeleton-category-image"></div>
                <div class="skeleton skeleton-category-name"></div>
                <div class="skeleton skeleton-category-count"></div>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

/**
 * Product Slider Skeleton
 * 
 * @param int $count Number of skeleton slider items to display
 */
function render_skeleton_slider( $count = 5 ) {
    ?>
    <div class="skeleton-container skeleton-slider" data-skeleton="slider">
        <div class="skeleton-slider-track">
            <?php for ( $i = 0; $i < $count; $i++ ) : ?>
                <div class="skeleton-slider-item">
                    <div class="skeleton skeleton-slider-image"></div>
                    <div class="skeleton skeleton-slider-title"></div>
                    <div class="skeleton skeleton-slider-subtitle"></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php
}

/**
 * Widget Skeleton
 * 
 * @param int $count Number of skeleton widget items to display
 */
function render_skeleton_widget( $count = 4 ) {
    ?>
    <div class="skeleton-container skeleton-widget" data-skeleton="widget">
        <div class="skeleton skeleton-widget-title"></div>
        <?php for ( $i = 0; $i < $count; $i++ ) : ?>
            <div class="skeleton-widget-item">
                <div class="skeleton skeleton-widget-item-image"></div>
                <div class="skeleton-widget-item-content">
                    <div class="skeleton skeleton-widget-item-title"></div>
                    <div class="skeleton skeleton-widget-item-meta"></div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

/**
 * Generic Content Skeleton
 * For custom use cases
 */
function render_skeleton_content() {
    ?>
    <div class="skeleton-container" data-skeleton="content">
        <div class="skeleton skeleton-text-xl" style="width: 60%; margin-bottom: 20px;"></div>
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 80%;"></div>
        <div class="skeleton skeleton-text" style="width: 100%; margin-top: 20px;"></div>
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 70%;"></div>
    </div>
    <?php
}

/**
 * Add skeleton screen to WooCommerce shop page
 */
function add_skeleton_to_shop_page() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        echo '<div id="skeleton-shop-wrapper">';
        render_skeleton_product_grid( 12 );
        echo '</div>';
    }
}
add_action( 'woocommerce_before_shop_loop', 'add_skeleton_to_shop_page', 5 );

/**
 * Add skeleton screen to single product page
 */
function add_skeleton_to_single_product() {
    if ( is_product() ) {
        echo '<div id="skeleton-product-wrapper">';
        render_skeleton_single_product();
        echo '</div>';
    }
}
add_action( 'woocommerce_before_single_product', 'add_skeleton_to_single_product', 5 );

/**
 * Add skeleton screen to cart page
 */
function add_skeleton_to_cart_page() {
    if ( is_cart() ) {
        echo '<div id="skeleton-cart-wrapper">';
        render_skeleton_cart( 4 );
        echo '</div>';
    }
}
add_action( 'woocommerce_before_cart', 'add_skeleton_to_cart_page', 5 );

/**
 * Add skeleton screen to blog/archive pages
 */
function add_skeleton_to_blog() {
    if ( is_home() || is_archive() ) {
        echo '<div id="skeleton-blog-wrapper">';
        render_skeleton_post_list( 6 );
        echo '</div>';
    }
}
add_action( 'loop_start', 'add_skeleton_to_blog', 5 );

/**
 * AJAX handler for loading products with skeleton
 */
function ajax_load_products_with_skeleton() {
    check_ajax_referer( 'skeleton_nonce', 'nonce' );
    
    $paged = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
    $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'paged' => $paged,
    );
    
    if ( ! empty( $category ) ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        ob_start();
        woocommerce_product_loop_start();
        
        while ( $query->have_posts() ) {
            $query->the_post();
            wc_get_template_part( 'content', 'product' );
        }
        
        woocommerce_product_loop_end();
        $html = ob_get_clean();
        
        wp_send_json_success( array(
            'html' => $html,
            'has_more' => $query->max_num_pages > $paged,
        ) );
    } else {
        wp_send_json_error( array( 'message' => 'No products found' ) );
    }
    
    wp_reset_postdata();
    wp_die();
}
add_action( 'wp_ajax_load_products_skeleton', 'ajax_load_products_with_skeleton' );
add_action( 'wp_ajax_nopriv_load_products_skeleton', 'ajax_load_products_with_skeleton' );

/**
 * Shortcode for skeleton screens
 * Usage: [skeleton type="product-grid" count="8"]
 */
function skeleton_screen_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'type' => 'product-grid',
        'count' => 8,
    ), $atts );
    
    ob_start();
    
    switch ( $atts['type'] ) {
        case 'product-grid':
            render_skeleton_product_grid( intval( $atts['count'] ) );
            break;
        case 'single-product':
            render_skeleton_single_product();
            break;
        case 'post-list':
            render_skeleton_post_list( intval( $atts['count'] ) );
            break;
        case 'cart':
            render_skeleton_cart( intval( $atts['count'] ) );
            break;
        case 'category-grid':
            render_skeleton_category_grid( intval( $atts['count'] ) );
            break;
        case 'slider':
            render_skeleton_slider( intval( $atts['count'] ) );
            break;
        case 'widget':
            render_skeleton_widget( intval( $atts['count'] ) );
            break;
        default:
            render_skeleton_content();
            break;
    }
    
    return ob_get_clean();
}
add_shortcode( 'skeleton', 'skeleton_screen_shortcode' );
