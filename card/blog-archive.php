<?php
/**
 * Blog Archive Template
 * 
 * Displays blog posts in a beautiful grid layout with filtering and pagination
 * Based on blog-archieve.html design
 * 
 * @package HelloElementorChild
 */

// Get blog archive data
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$posts_per_page = 6;
$search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

// Query arguments
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

// Add search if present
if (!empty($search_query)) {
    $args['s'] = $search_query;
}

$blog_query = new WP_Query($args);
?>

<div class="blog-archive-wrapper">
    <!-- BACKGROUND SHAPES -->
    <div class="bg-shapes">
        <div class="shape-1"></div>
        <div class="shape-2"></div>
    </div>

    <!-- HERO SECTION -->
    <section class="blog-hero">
        <span class="badge"><?php _e('Our blog', 'hello-theme-child'); ?></span>
        <h1><?php _e('Resources and insights', 'hello-theme-child'); ?></h1>
        <p><?php _e('The latest blog post about dairy and cattle sector', 'hello-theme-child'); ?></p>
        
        <form method="get" class="blog-search" action="<?php echo esc_url(home_url('/')); ?>">
            <input 
                type="text" 
                name="s" 
                placeholder="<?php _e('Search posts...', 'hello-theme-child'); ?>"
                value="<?php echo esc_attr($search_query); ?>"
                class="blog-search-input"
            />
        </form>
    </section>

    <!-- BLOG GRID SECTION -->
    <section class="blog-section">
        <div class="blog-container">
            <?php if ($blog_query->have_posts()): ?>
                <div class="blog-grid">
                    <?php 
                    $post_count = 0;
                    while ($blog_query->have_posts()): 
                        $blog_query->the_post();
                        $post_count++;
                        
                        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                        $featured_image = $featured_image ?: 'https://picsum.photos/600/440?random=' . $post_count;
                        
                        $author_id = get_the_author_meta('ID');
                        $author_avatar = get_avatar_url($author_id, array('size' => 44));
                        $author_name = get_the_author_meta('display_name');
                        $post_date = get_the_date('j M Y');
                        $categories = get_the_category();
                        $category_name = !empty($categories) ? $categories[0]->name : __('Uncategorized', 'hello-theme-child');
                    ?>
                        <article class="blog-card" style="animation-delay: <?php echo ($post_count - 1) * 0.1; ?>s">
                            <div class="blog-card-image">
                                <img src="<?php echo esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>">
                            </div>
                            <div class="blog-card-content">
                                <span class="blog-category"><?php echo esc_html($category_name); ?></span>
                                <h3 class="blog-card-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <p class="blog-card-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                </p>
                                <div class="blog-meta">
                                    <img 
                                        src="<?php echo esc_url($author_avatar); ?>" 
                                        alt="<?php echo esc_attr($author_name); ?>"
                                        class="blog-author-avatar"
                                    >
                                    <div class="blog-meta-info">
                                        <strong><?php echo esc_html($author_name); ?></strong>
                                        <span class="blog-date"><?php echo esc_html($post_date); ?></span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php 
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <!-- PAGINATION -->
                <div class="blog-pagination">
                    <?php
                    $total_pages = $blog_query->max_num_pages;
                    
                    if ($total_pages > 1) {
                        echo paginate_links(array(
                            'base'      => add_query_arg('paged', '%#%'),
                            'format'    => '',
                            'current'   => max(1, $paged),
                            'total'     => $total_pages,
                            'prev_text' => '← ' . __('Previous', 'hello-theme-child'),
                            'next_text' => __('Next', 'hello-theme-child') . ' →',
                            'type'      => 'plain',
                        ));
                    }
                    ?>
                </div>
            <?php else: ?>
                <div class="blog-no-posts">
                    <p><?php _e('No posts found. Try adjusting your search.', 'hello-theme-child'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
