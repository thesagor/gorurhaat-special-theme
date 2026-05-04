<?php
/**
 * Index Template (Blog Homepage)
 * 
 * Displays the blog homepage with all posts
 * Only shows blog archive layout on blog/home pages
 * 
 * @package HelloElementorChild
 */

// Only show blog archive on home/blog pages, not on single posts
if ( is_home() || is_front_page() ) {
    get_header();
    include get_stylesheet_directory() . '/card/blog-archive.php';
    get_footer();
} else {
    // Use parent theme's index template for other cases
    $parent_template = get_template_directory() . '/index.php';
    if ( file_exists( $parent_template ) ) {
        require $parent_template;
    }
}
