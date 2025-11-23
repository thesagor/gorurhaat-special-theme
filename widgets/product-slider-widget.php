<?php
/**
 * Custom Elementor Product Slider Widget
 * 
 * Displays WooCommerce products in a carousel/slider with category filtering
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Product Slider Widget Class
 */
class Hello_Elementor_Product_Slider_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'product_slider';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Product Slider', 'hello-elementor-child' );
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-slider-push';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'woocommerce-elements' ];
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'product', 'slider', 'carousel', 'woocommerce', 'shop', 'category' ];
    }

    /**
     * Get script dependencies.
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return [ 'swiper' ];
    }

    /**
     * Get style dependencies.
     *
     * @return array Widget styles dependencies.
     */
    public function get_style_depends() {
        return [ 'swiper' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Query Settings', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Product Category Selection
        $this->add_control(
            'product_category',
            [
                'label' => __( 'Product Category', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_product_categories(),
                'default' => [],
                'label_block' => true,
                'description' => __( 'Select categories to filter products. Leave empty to show all.', 'hello-elementor-child' ),
            ]
        );

        // Product Type Selection (Custom Taxonomy)
        $this->add_control(
            'product_type',
            [
                'label' => __( 'Product Type', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'All Types', 'hello-elementor-child' ),
                    'livestock' => __( 'Livestock', 'hello-elementor-child' ),
                    'dairy_product' => __( 'Dairy Products', 'hello-elementor-child' ),
                    'feed' => __( 'Animal Feed', 'hello-elementor-child' ),
                ],
                'default' => '',
            ]
        );

        // Number of Products
        $this->add_control(
            'posts_per_page',
            [
                'label' => __( 'Number of Products', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 100,
            ]
        );

        // Order By
        $this->add_control(
            'orderby',
            [
                'label' => __( 'Order By', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => __( 'Date', 'hello-elementor-child' ),
                    'title' => __( 'Title', 'hello-elementor-child' ),
                    'price' => __( 'Price', 'hello-elementor-child' ),
                    'popularity' => __( 'Popularity', 'hello-elementor-child' ),
                    'rating' => __( 'Rating', 'hello-elementor-child' ),
                    'rand' => __( 'Random', 'hello-elementor-child' ),
                ],
                'default' => 'date',
            ]
        );

        // Order
        $this->add_control(
            'order',
            [
                'label' => __( 'Order', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'ASC' => __( 'Ascending', 'hello-elementor-child' ),
                    'DESC' => __( 'Descending', 'hello-elementor-child' ),
                ],
                'default' => 'DESC',
            ]
        );

        $this->end_controls_section();

        // Slider Settings Section
        $this->start_controls_section(
            'slider_settings_section',
            [
                'label' => __( 'Slider Settings', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Slides to Show
        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __( 'Slides to Show', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'min' => 1,
                'max' => 6,
            ]
        );

        // Slides to Scroll
        $this->add_control(
            'slides_to_scroll',
            [
                'label' => __( 'Slides to Scroll', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'max' => 6,
            ]
        );

        // Autoplay
        $this->add_control(
            'autoplay',
            [
                'label' => __( 'Autoplay', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Autoplay Speed
        $this->add_control(
            'autoplay_speed',
            [
                'label' => __( 'Autoplay Speed (ms)', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'min' => 1000,
                'max' => 10000,
                'step' => 100,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        // Infinite Loop
        $this->add_control(
            'infinite',
            [
                'label' => __( 'Infinite Loop', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Pause on Hover
        $this->add_control(
            'pause_on_hover',
            [
                'label' => __( 'Pause on Hover', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        // Speed
        $this->add_control(
            'speed',
            [
                'label' => __( 'Animation Speed (ms)', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 500,
                'min' => 100,
                'max' => 3000,
                'step' => 100,
            ]
        );

        // Space Between
        $this->add_responsive_control(
            'space_between',
            [
                'label' => __( 'Space Between Slides', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 20,
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Section
        $this->start_controls_section(
            'navigation_section',
            [
                'label' => __( 'Navigation', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Show Arrows
        $this->add_control(
            'show_arrows',
            [
                'label' => __( 'Show Arrows', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Show Dots
        $this->add_control(
            'show_dots',
            [
                'label' => __( 'Show Dots', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'hello-elementor-child' ),
                'label_off' => __( 'No', 'hello-elementor-child' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section - Card
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => __( 'Card Style', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Card Background
        $this->add_control(
            'card_background',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Card Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .product-slide-card',
            ]
        );

        // Card Border Radius
        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __( 'Border Radius', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 8,
                    'right' => 8,
                    'bottom' => 8,
                    'left' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-slide-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Card Box Shadow
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .product-slide-card',
            ]
        );

        // Card Padding
        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __( 'Padding', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 15,
                    'right' => 15,
                    'bottom' => 15,
                    'left' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-slide-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Title
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __( 'Title Style', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .product-slide-title',
            ]
        );

        $this->end_controls_section();

        // Style Section - Price
        $this->start_controls_section(
            'price_style_section',
            [
                'label' => __( 'Price Style', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __( 'Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2c3e50',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .product-slide-price',
            ]
        );

        $this->end_controls_section();

        // Style Section - Button
        $this->start_controls_section(
            'button_style_section',
            [
                'label' => __( 'Button Style', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .product-slide-button',
            ]
        );

        $this->start_controls_tabs( 'button_tabs' );

        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => __( 'Normal', 'hello-elementor-child' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3498db',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover_tab',
            [
                'label' => __( 'Hover', 'hello-elementor-child' ),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2980b9',
                'selectors' => [
                    '{{WRAPPER}} .product-slide-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __( 'Border Radius', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __( 'Padding', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 10,
                    'right' => 20,
                    'bottom' => 10,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Navigation
        $this->start_controls_section(
            'navigation_style_section',
            [
                'label' => __( 'Navigation Style', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Arrow Color
        $this->add_control(
            'arrow_color',
            [
                'label' => __( 'Arrow Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        // Dot Color
        $this->add_control(
            'dot_color',
            [
                'label' => __( 'Dot Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#3498db',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Get product categories for dropdown
     *
     * @return array
     */
    protected function get_product_categories() {
        $categories = get_terms( [
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ] );

        $options = [];
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $category ) {
                $options[ $category->term_id ] = $category->name;
            }
        }

        return $options;
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Build query args
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        ];

        // Add category filter
        if ( ! empty( $settings['product_category'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $settings['product_category'],
            ];
        }

        // Add product type filter
        if ( ! empty( $settings['product_type'] ) ) {
            $args['meta_query'][] = [
                'key' => '_product_info_type',
                'value' => $settings['product_type'],
                'compare' => '=',
            ];
        }

        // Run query
        $products = new \WP_Query( $args );

        if ( $products->have_posts() ) :
            $slider_id = 'product-slider-' . $this->get_id();
            ?>
            <div class="product-slider-wrapper">
                <div class="swiper <?php echo esc_attr( $slider_id ); ?>">
                    <div class="swiper-wrapper">
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <?php 
                            global $product; 
                            $product_id = $product->get_id();
                            ?>
                            <div class="swiper-slide">
                                <div class="product-slide-card">
                                    <div class="product-slide-image">
                                        <a href="<?php echo esc_url( get_permalink() ); ?>">
                                            <?php echo $product->get_image( 'medium' ); ?>
                                        </a>
                                    </div>
                                    <div class="product-slide-content">
                                        <h3 class="product-slide-title">
                                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                                <?php echo esc_html( get_the_title() ); ?>
                                            </a>
                                        </h3>
                                        <div class="product-slide-price">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                        <button class="product-slide-button ajax-add-to-cart" data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                            <?php esc_html_e( 'Add to Cart', 'hello-elementor-child' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    <?php endif; ?>
                    
                    <?php if ( $settings['show_dots'] === 'yes' ) : ?>
                        <div class="swiper-pagination"></div>
                    <?php endif; ?>
                </div>
            </div>

            <script>
            jQuery(document).ready(function($) {
                new Swiper('.<?php echo esc_js( $slider_id ); ?>', {
                    slidesPerView: <?php echo esc_js( $settings['slides_to_show_mobile'] ?? 1 ); ?>,
                    slidesPerGroup: <?php echo esc_js( $settings['slides_to_scroll'] ); ?>,
                    spaceBetween: <?php echo esc_js( $settings['space_between']['size'] ?? 20 ); ?>,
                    speed: <?php echo esc_js( $settings['speed'] ); ?>,
                    loop: <?php echo $settings['infinite'] === 'yes' ? 'true' : 'false'; ?>,
                    <?php if ( $settings['autoplay'] === 'yes' ) : ?>
                    autoplay: {
                        delay: <?php echo esc_js( $settings['autoplay_speed'] ); ?>,
                        disableOnInteraction: false,
                        <?php if ( $settings['pause_on_hover'] === 'yes' ) : ?>
                        pauseOnMouseEnter: true,
                        <?php endif; ?>
                    },
                    <?php endif; ?>
                    <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                    navigation: {
                        nextEl: '.<?php echo esc_js( $slider_id ); ?> .swiper-button-next',
                        prevEl: '.<?php echo esc_js( $slider_id ); ?> .swiper-button-prev',
                    },
                    <?php endif; ?>
                    <?php if ( $settings['show_dots'] === 'yes' ) : ?>
                    pagination: {
                        el: '.<?php echo esc_js( $slider_id ); ?> .swiper-pagination',
                        clickable: true,
                    },
                    <?php endif; ?>
                    breakpoints: {
                        768: {
                            slidesPerView: <?php echo esc_js( $settings['slides_to_show_tablet'] ?? 2 ); ?>,
                            spaceBetween: <?php echo esc_js( $settings['space_between_tablet']['size'] ?? $settings['space_between']['size'] ?? 20 ); ?>,
                        },
                        1024: {
                            slidesPerView: <?php echo esc_js( $settings['slides_to_show'] ?? 3 ); ?>,
                            spaceBetween: <?php echo esc_js( $settings['space_between']['size'] ?? 20 ); ?>,
                        }
                    }
                });
            });
            </script>
            <?php
            wp_reset_postdata();
        else :
            ?>
            <div class="no-products-found">
                <p><?php esc_html_e( 'No products found.', 'hello-elementor-child' ); ?></p>
            </div>
            <?php
        endif;
    }
}
