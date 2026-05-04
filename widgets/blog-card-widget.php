<?php
/**
 * Blog Card Widget for Elementor
 * 
 * Displays blog posts in a card grid layout with filtering and pagination
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Hello_Elementor_Blog_Card_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'blog_card';
    }

    public function get_title() {
        return __( 'Blog Cards', 'hello-elementor-child' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'gorurhaat' ];
    }

    public function get_keywords() {
        return [ 'blog', 'posts', 'card', 'grid', 'archive' ];
    }

    public function get_style_depends() {
        return [ 'blog-archive-styles' ];
    }

    protected function register_controls() {

        // Query Settings Section
        $this->start_controls_section(
            'query_section',
            [
                'label' => __( 'Query Settings', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Posts Per Page', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'DESC' => __( 'Newest First', 'hello-elementor-child' ),
                    'ASC' => __( 'Oldest First', 'hello-elementor-child' ),
                ],
                'default' => 'DESC',
            ]
        );

        $this->add_control(
            'category_filter',
            [
                'label' => __( 'Category Filter', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_categories_list(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __( 'Show Pagination', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label' => __( 'Show Search Box', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Layout Settings Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __( 'Layout Settings', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __( 'Columns', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => __( '1 Column', 'hello-elementor-child' ),
                    '2' => __( '2 Columns', 'hello-elementor-child' ),
                    '3' => __( '3 Columns', 'hello-elementor-child' ),
                    '4' => __( '4 Columns', 'hello-elementor-child' ),
                ],
                'default' => '3',
                'selectors' => [
                    '{{WRAPPER}} .blog-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_control(
            'gap',
            [
                'label' => __( 'Gap Between Cards', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .blog-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Section Background Settings
        $this->start_controls_section(
            'section_bg_style',
            [
                'label' => __( 'Section Background', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'section_bg_color',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .blog-archive-wrapper' => 'background: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Settings Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __( 'Card Styling', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg_color',
            [
                'label' => __( 'Card Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .blog-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => __( 'Border Radius', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .blog-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_shadow',
            [
                'label' => __( 'Box Shadow', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::BOX_SHADOW,
                'selectors' => [
                    '{{WRAPPER}} .blog-card' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Styling
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __( 'Title Styling', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1d1d1f',
                'selectors' => [
                    '{{WRAPPER}} .blog-card-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Title Typography', 'hello-elementor-child' ),
                'selector' => '{{WRAPPER}} .blog-card-title',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get categories list for dropdown
     */
    private function get_categories_list() {
        $categories = get_categories( [ 'hide_empty' => false ] );
        $options = [];

        foreach ( $categories as $category ) {
            $options[ $category->term_id ] = $category->name;
        }

        return $options;
    }

    protected function render() {
        $settings       = $this->get_settings_for_display();
        $paged          = max( 1, get_query_var( 'paged' ) );
        $posts_per_page = intval( $settings['posts_per_page'] );
        $order          = $settings['order'];
        $show_search    = $settings['show_search'];
        $category_ids   = $settings['category_filter'];

        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $posts_per_page,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => $order,
        );

        if ( ! empty( $category_ids ) ) {
            $args['cat'] = implode( ',', array_map( 'absint', $category_ids ) );
        }

        if ( ! empty( $_GET['s'] ) ) {
            $args['s'] = sanitize_text_field( wp_unslash( $_GET['s'] ) );
        }

        $blog_query = new WP_Query( $args );
        ?>
        <div class="blog-archive-wrapper elementor-blog-widget">

            <?php if ( $show_search ) : ?>
                <section class="blog-hero">
                    <h2><?php esc_html_e( 'Blog Articles', 'hello-elementor-child' ); ?></h2>
                    <form method="get" class="blog-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
                        <input
                            type="search"
                            name="s"
                            placeholder="<?php esc_attr_e( 'Search posts...', 'hello-elementor-child' ); ?>"
                            value="<?php echo ! empty( $_GET['s'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) : ''; ?>"
                            class="blog-search-input"
                            aria-label="<?php esc_attr_e( 'Search blog posts', 'hello-elementor-child' ); ?>"
                        >
                        <button type="submit" class="blog-search-submit" aria-label="<?php esc_attr_e( 'Search', 'hello-elementor-child' ); ?>">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </button>
                    </form>
                </section>
            <?php endif; ?>

            <section class="blog-section">
                <div class="blog-container">
                    <?php if ( $blog_query->have_posts() ) : ?>
                        <div class="blog-grid">
                            <?php
                            $post_count = 0;
                            while ( $blog_query->have_posts() ) :
                                $blog_query->the_post();
                                $post_count++;

                                $has_thumb     = has_post_thumbnail();
                                $thumb_url     = $has_thumb ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : '';
                                $author_id     = (int) get_the_author_meta( 'ID' );
                                $author_avatar = get_avatar_url( $author_id, array( 'size' => 44 ) );
                                $author_name   = get_the_author_meta( 'display_name' );
                                $post_date     = get_the_date( 'j M Y' );
                                $categories    = get_the_category();
                                $cat_name      = ! empty( $categories ) ? $categories[0]->name : __( 'General', 'hello-elementor-child' );
                                $cat_url       = ! empty( $categories ) ? get_category_link( $categories[0] ) : '';

                                $content      = get_post_field( 'post_content', get_the_ID() );
                                $word_count   = str_word_count( wp_strip_all_tags( $content ) );
                                $reading_mins = max( 1, (int) ceil( $word_count / 200 ) );
                                ?>
                                <article class="blog-card" style="animation-delay:<?php echo ( $post_count - 1 ) * 0.1; ?>s">
                                    <?php if ( $thumb_url ) : ?>
                                    <div class="blog-card-image">
                                        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                            <img
                                                src="<?php echo esc_url( $thumb_url ); ?>"
                                                alt="<?php the_title_attribute(); ?>"
                                                loading="lazy"
                                                width="600"
                                                height="440"
                                            >
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <div class="blog-card-content">
                                        <?php if ( $cat_url ) : ?>
                                            <a href="<?php echo esc_url( $cat_url ); ?>" class="blog-category"><?php echo esc_html( $cat_name ); ?></a>
                                        <?php else : ?>
                                            <span class="blog-category"><?php echo esc_html( $cat_name ); ?></span>
                                        <?php endif; ?>

                                        <h3 class="blog-card-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>

                                        <p class="blog-card-excerpt">
                                            <?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
                                        </p>

                                        <div class="blog-meta">
                                            <img
                                                src="<?php echo esc_url( $author_avatar ); ?>"
                                                alt="<?php echo esc_attr( $author_name ); ?>"
                                                class="blog-author-avatar"
                                                loading="lazy"
                                                width="44"
                                                height="44"
                                            >
                                            <div class="blog-meta-info">
                                                <strong><?php echo esc_html( $author_name ); ?></strong>
                                                <span class="blog-date">
                                                    <?php echo esc_html( $post_date ); ?> &middot;
                                                    <?php
                                                    printf(
                                                        /* translators: %d: minutes */
                                                        esc_html( _n( '%d min read', '%d min read', $reading_mins, 'hello-elementor-child' ) ),
                                                        $reading_mins
                                                    );
                                                    ?>
                                                </span>
                                            </div>
                                        </div>

                                        <a href="<?php the_permalink(); ?>" class="blog-read-more" aria-label="<?php echo esc_attr( sprintf( __( 'Read more: %s', 'hello-elementor-child' ), get_the_title() ) ); ?>">
                                            <?php esc_html_e( 'Read More', 'hello-elementor-child' ); ?>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                        </a>
                                    </div>
                                </article>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>

                        <?php if ( $settings['show_pagination'] && $blog_query->max_num_pages > 1 ) : ?>
                            <nav class="blog-pagination" aria-label="<?php esc_attr_e( 'Blog pagination', 'hello-elementor-child' ); ?>">
                                <?php
                                echo paginate_links( array(
                                    'total'   => $blog_query->max_num_pages,
                                    'current' => $paged,
                                    'type'    => 'list',
                                ) );
                                ?>
                            </nav>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="blog-no-posts" role="status">
                            <p><?php esc_html_e( 'No posts found.', 'hello-elementor-child' ); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <?php
    }
}
