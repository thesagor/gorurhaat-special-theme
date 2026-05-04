<?php
/**
 * Theme and Plugin Security
 * 
 * Blocks detection of WordPress theme and plugins by tools like WPThemeDetector
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Remove WordPress version from front-end
 * Prevents version detection
 */
function hello_elementor_child_remove_wp_version() {
    return '';
}
add_filter( 'the_generator', 'hello_elementor_child_remove_wp_version' );

/**
 * Remove generator meta tag that shows WordPress version
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Remove theme and plugin information from REST API
 * Prevents API-based detection
 */
add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_cannot_access', 'REST API access restricted.', [ 'status' => 403 ] );
    }
    return $result;
}, 5 );

/**
 * Disable REST API for non-authenticated users
 */
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( ! is_user_logged_in() ) {
        // Remove theme endpoints
        unset( $endpoints['/wp/v2/themes'] );
        // Remove plugin endpoints  
        unset( $endpoints['/wp/v2/plugins'] );
    }
    return $endpoints;
} );

/**
 * Block direct access to wp-json endpoints for unauthenticated users
 */
add_filter( 'json_enabled', function( $enabled ) {
    if ( ! is_user_logged_in() && ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/wp-json/' ) !== false ) ) {
        wp_die( 'Access denied', 'Access Denied', [ 'response' => 403 ] );
    }
    return $enabled;
} );

/**
 * Hide admin-ajax from being publicly accessible for sensitive endpoints
 */
add_action( 'wp_loaded', function() {
    if ( ! is_user_logged_in() && isset( $_REQUEST['action'] ) ) {
        $blocked_actions = [
            'get_themes',
            'get_plugins',
            'heartbeat',
        ];
        
        if ( in_array( $_REQUEST['action'], $blocked_actions, true ) ) {
            wp_die( 'Access denied', 'Access Denied', [ 'response' => 403 ] );
        }
    }
} );

/**
 * Disable file listing in theme and plugin directories
 * Create .htaccess rules to prevent directory listing
 */
function hello_elementor_child_disable_directory_listing() {
    $dirs = [
        WP_CONTENT_DIR . '/themes',
        WP_CONTENT_DIR . '/plugins',
        WP_CONTENT_DIR . '/uploads',
    ];
    
    foreach ( $dirs as $dir ) {
        $htaccess_file = trailingslashit( $dir ) . '.htaccess';
        
        if ( ! file_exists( $htaccess_file ) ) {
            $htaccess_content = "Options -Indexes\n";
            
            if ( is_writable( $dir ) ) {
                file_put_contents( $htaccess_file, $htaccess_content );
            }
        }
    }
}
add_action( 'wp_loaded', 'hello_elementor_child_disable_directory_listing' );

/**
 * Remove WordPress version from HTTP headers
 */
function hello_elementor_child_remove_x_powered_by() {
    header_remove( 'X-Powered-By' );
    header_remove( 'X-Generator' );
}
add_action( 'send_headers', 'hello_elementor_child_remove_x_powered_by' );

/**
 * Remove link rel tags that expose theme info
 */
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

/**
 * Disable user enumeration
 * Prevents discovering usernames through /?author=1
 */
if ( ! is_admin() ) {
    // Redirect author archives to homepage
    if ( isset( $_GET['author'] ) ) {
        wp_safe_remote_get( home_url() );
        exit;
    }
}

/**
 * Hide WordPress version and admin info from error messages
 */
function hello_elementor_child_hide_wp_errors() {
    define( 'WP_DEBUG_DISPLAY', false );
    error_reporting( E_ALL ^ E_NOTICE );
}
add_action( 'wp_loaded', 'hello_elementor_child_hide_wp_errors' );

/**
 * Block access to readme.html and other expose files
 */
add_action( 'wp_loaded', function() {
    $exposed_files = [
        '/readme.html',
        '/wp-config.php',
        '/wp-settings.php',
        '/.git',
        '/.gitignore',
    ];
    
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '';
    
    foreach ( $exposed_files as $file ) {
        if ( strpos( $request_uri, $file ) !== false ) {
            wp_die( 'Access denied', 'Access Denied', [ 'response' => 404 ] );
        }
    }
} );

/**
 * Disable XMLRPC (additional security)
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove XMLRPC header
 */
add_filter( 'wp_headers', function( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

/**
 * Hide WordPress admin from robots
 */
add_filter( 'template_include', function( $template ) {
    if ( is_admin() && ! is_user_logged_in() ) {
        status_header( 404 );
        nocache_headers();
        return 0;
    }
    return $template;
} );
