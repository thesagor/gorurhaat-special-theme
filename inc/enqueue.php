<?php
/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

/**
 * Enqueue Swiper library for slider widget
 */
function hello_elementor_child_enqueue_swiper() {
    // Enqueue Swiper CSS
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11.0.0'
    );

    // Enqueue Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [ 'jquery' ],
        '11.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_swiper' );
add_action( 'elementor/frontend/after_enqueue_scripts', 'hello_elementor_child_enqueue_swiper' );

/**
 * Enqueue custom CSS file
 */
function hello_elementor_child_enqueue_custom_styles() {
    wp_enqueue_style(
        'hello-elementor-child-custom-styles',
        get_stylesheet_directory_uri() . '/inc/custom-styles.css',
        [],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
    
    // Enqueue single product styles
    if ( is_product() ) {
        wp_enqueue_style(
            'hello-elementor-child-single-product-styles',
            get_stylesheet_directory_uri() . '/inc/single-product-styles.css',
            [],
            HELLO_ELEMENTOR_CHILD_VERSION
        );
    }
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_custom_styles' );
add_action( 'admin_enqueue_scripts', 'hello_elementor_child_enqueue_custom_styles' );

/**
 * Enqueue Cart Drawer Scripts and Styles
 */
function hello_elementor_child_enqueue_cart_drawer_assets() {
    // Enqueue cart drawer JavaScript
    wp_enqueue_script(
        'cart-drawer-js',
        get_stylesheet_directory_uri() . '/js/cart-drawer.js',
        [ 'jquery' ],
        HELLO_ELEMENTOR_CHILD_VERSION,
        true
    );

    // Localize script for AJAX
    wp_localize_script( 'cart-drawer-js', 'cartDrawerAjax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'cart_drawer_nonce' ),
        'checkout_url' => wc_get_checkout_url(),
        'cart_url' => wc_get_cart_url(),
    ));
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_cart_drawer_assets' );
