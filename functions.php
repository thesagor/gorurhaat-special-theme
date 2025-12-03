<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Include required files
 */
require_once get_stylesheet_directory() . '/inc/enqueue.php';
require_once get_stylesheet_directory() . '/inc/woocommerce-support.php';
require_once get_stylesheet_directory() . '/inc/elementor-widgets.php';
require_once get_stylesheet_directory() . '/inc/taxonomies.php';
require_once get_stylesheet_directory() . '/inc/product-info.php';
require_once get_stylesheet_directory() . '/inc/product-info-display.php'; // Hook-based product info display
require_once get_stylesheet_directory() . '/inc/product-display.php';
require_once get_stylesheet_directory() . '/inc/whatsapp.php';
require_once get_stylesheet_directory() . '/inc/cart-drawer.php';
require_once get_stylesheet_directory() . '/inc/single-product.php';
require_once get_stylesheet_directory() . '/inc/order-qrcode.php';
require_once get_stylesheet_directory() . '/inc/seo-optimization.php'; // SEO with Gorurhaat keyword

