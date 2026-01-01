<?php
/**
 * Enqueue Scripts and Styles
 * 
 * Optimized asset loading for performance
 * 
 * @package HelloElementorChild
 */

/**
 * Load child theme main stylesheet
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
 * Enqueue Font Awesome for icons
 */
function hello_elementor_child_enqueue_fontawesome() {
    wp_enqueue_style(
        'font-awesome-6',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_fontawesome', 5 );
add_action( 'elementor/frontend/after_enqueue_styles', 'hello_elementor_child_enqueue_fontawesome', 5 );

/**
 * Enqueue Swiper library for slider widgets (Offline Version)
 * Only loads when needed by widgets
 */
function hello_elementor_child_enqueue_swiper() {
    // Enqueue Swiper CSS (Local)
    wp_enqueue_style(
        'swiper',
        get_stylesheet_directory_uri() . '/lib/swiper-bundle.min.css',
        [],
        '11.0.0'
    );

    // Enqueue Swiper JS (Local)
    wp_enqueue_script(
        'swiper',
        get_stylesheet_directory_uri() . '/lib/swiper-bundle.min.js',
        [ 'jquery' ],
        '11.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_swiper' );
add_action( 'elementor/frontend/after_enqueue_scripts', 'hello_elementor_child_enqueue_swiper' );

/**
 * Enqueue Cattle Slider Widget Styles
 * Only loads when widget is used
 */
function hello_elementor_child_enqueue_cattle_slider() {
    wp_enqueue_style(
        'cattle-slider',
        get_stylesheet_directory_uri() . '/css/cattle-slider.css',
        [ 'swiper' ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_cattle_slider' );
add_action( 'elementor/frontend/after_enqueue_styles', 'hello_elementor_child_enqueue_cattle_slider' );

/**
 * Enqueue Advanced Slider Widget Styles
 * Only loads when widget is used
 */
function hello_elementor_child_enqueue_advanced_slider() {
    wp_enqueue_style(
        'advanced-slider',
        get_stylesheet_directory_uri() . '/css/advanced-slider.css',
        [ 'swiper' ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_advanced_slider' );
add_action( 'elementor/frontend/after_enqueue_styles', 'hello_elementor_child_enqueue_advanced_slider' );

/**
 * Enqueue Hero Slider Widget Assets
 * Only loads when widget is used
 */
function hello_elementor_child_register_hero_slider_assets() {
    // Register CSS
    wp_register_style(
        'hero-slider-styles',
        get_stylesheet_directory_uri() . '/css/hero-slider.css',
        [ 'swiper' ],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
    
    // Register JS
    wp_register_script(
        'hero-slider-script',
        get_stylesheet_directory_uri() . '/js/hero-slider.js',
        [ 'jquery', 'swiper' ],
        HELLO_ELEMENTOR_CHILD_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_register_hero_slider_assets' );
add_action( 'elementor/frontend/after_register_scripts', 'hello_elementor_child_register_hero_slider_assets' );

/**
 * Enqueue custom CSS files
 * Organized in /css folder for better structure
 */
function hello_elementor_child_enqueue_custom_styles() {
    // Main custom styles
    wp_enqueue_style(
        'hello-elementor-child-custom-styles',
        get_stylesheet_directory_uri() . '/css/custom-styles.css',
        [],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
    
    // Single product styles (conditional loading for performance)
    if ( is_product() ) {
        wp_enqueue_style(
            'hello-elementor-child-single-product-styles',
            get_stylesheet_directory_uri() . '/css/single-product-styles.css',
            [],
            HELLO_ELEMENTOR_CHILD_VERSION
        );
    }
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_custom_styles' );
add_action( 'admin_enqueue_scripts', 'hello_elementor_child_enqueue_custom_styles' );

/**
 * Enqueue Cart Drawer Assets
 * Only loads on frontend for better performance
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

/**
 * Enqueue Skeleton Screen Assets
 * Improves perceived loading performance
 */
function hello_elementor_child_enqueue_skeleton_screen() {
    // Enqueue skeleton screen CSS
    wp_enqueue_style(
        'skeleton-screen-css',
        get_stylesheet_directory_uri() . '/css/skeleton-screen.css',
        [],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
    
    // Enqueue skeleton screen JavaScript
    wp_enqueue_script(
        'skeleton-screen-js',
        get_stylesheet_directory_uri() . '/js/skeleton-screen.js',
        [ 'jquery' ],
        HELLO_ELEMENTOR_CHILD_VERSION,
        true
    );
    
    // Localize script for AJAX
    wp_localize_script( 'skeleton-screen-js', 'skeletonScreenData', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'skeleton_nonce' ),
    ));
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_skeleton_screen' );

/**
 * Performance Optimizations
 */

// Remove jQuery Migrate for better performance (only if not needed)
function hello_elementor_child_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_action( 'wp_default_scripts', 'hello_elementor_child_remove_jquery_migrate' );

// Defer JavaScript loading for better performance
function hello_elementor_child_defer_scripts( $tag, $handle, $src ) {
    // List of scripts to defer
    $defer_scripts = array(
        'swiper',
        'skeleton-screen-js',
    );
    
    if ( in_array( $handle, $defer_scripts ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'hello_elementor_child_defer_scripts', 10, 3 );

// Disable WordPress emoji scripts for better performance
function hello_elementor_child_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'hello_elementor_child_disable_emojis' );

// Remove WordPress version from head for security
remove_action( 'wp_head', 'wp_generator' );

// Disable embeds for better performance
function hello_elementor_child_disable_embeds() {
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'hello_elementor_child_disable_embeds' );
