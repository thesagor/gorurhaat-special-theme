<?php
/**
 * Archive Template
 * 
 * Displays archives (category, tag, author, etc.) in the blog layout
 * Only shows blog archive for post archives, not for other post types
 * 
 * @package HelloElementorChild
 */

// Only show blog archive for post category/tag/author archives
if ( is_category() || is_tag() || is_author() ) {
    get_header();
    include get_stylesheet_directory() . '/card/blog-archive.php';
    get_footer();
} else {
    // Use parent theme's archive template for other post types
    $parent_template = get_template_directory() . '/archive.php';
    if ( file_exists( $parent_template ) ) {
        require $parent_template;
    }
}
