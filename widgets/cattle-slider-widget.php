<?php
/**
 * Custom Elementor Cattle Slider Widget
 * 
 * Premium cattle/livestock product slider with price, live weight, title, and breed
 * Based on the premium card design from card.html
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Cattle Slider Widget Class
 */
class Hello_Elementor_Cattle_Slider_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'cattle_slider';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Cattle Slider', 'hello-elementor-child' );
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-carousel';
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
        return [ 'cattle', 'livestock', 'slider', 'carousel', 'woocommerce', 'product', 'animal' ];
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
        return [ 'swiper', 'cattle-slider' ];
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
                'description' => __( 'Select categories to filter products. Leave empty to show all products.', 'hello-elementor-child' ),
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
                'default' => 4,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'min' => 1,
                'max' => 6,
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
                    'size' => 15,
                ],
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
                'default' => 4000,
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
                'default' => 600,
                'min' => 100,
                'max' => 3000,
                'step' => 100,
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
                'label' => __( 'Show Pagination', 'hello-elementor-child' ),
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
                    '{{WRAPPER}} .cattle-card' => 'background-color: {{VALUE}};',
                ],
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
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .cattle-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Badge
        $this->start_controls_section(
            'badge_style_section',
            [
                'label' => __( 'Badge Style', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'badge_background',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .cattle-badge' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}} 100%);',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Typography
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __( 'Typography', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1a1a2e',
                'selectors' => [
                    '{{WRAPPER}} .cattle-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breed_color',
            [
                'label' => __( 'Breed Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#64748b',
                'selectors' => [
                    '{{WRAPPER}} .cattle-breed' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => __( 'Price Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#dc2626',
                'selectors' => [
                    '{{WRAPPER}} .price-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'weight_color',
            [
                'label' => __( 'Weight Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#10b981',
                'selectors' => [
                    '{{WRAPPER}} .weight-value' => 'color: {{VALUE}};',
                ],
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

        $this->start_controls_tabs( 'button_tabs' );

        $this->start_controls_tab(
            'button_normal_tab',
            [
                'label' => __( 'Normal', 'hello-elementor-child' ),
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .details-btn' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}} 100%);',
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
            'button_hover_background',
            [
                'label' => __( 'Background Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1d4ed8',
                'selectors' => [
                    '{{WRAPPER}} .details-btn:hover' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}} 100%);',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Get product categories for dropdown (hierarchical)
     *
     * @return array
     */
    protected function get_product_categories() {
        $categories = get_terms( [
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'orderby' => 'parent',
        ] );

        $options = [];
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            foreach ( $categories as $category ) {
                $prefix = $category->parent != 0 ? '— ' : '';
                $options[ $category->term_id ] = $prefix . $category->name;
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

        // Run query
        $products = new \WP_Query( $args );

        if ( $products->have_posts() ) :
            $slider_id = 'cattle-slider-' . $this->get_id();
            ?>
            <div class="cattle-slider-wrapper">
                <div class="swiper <?php echo esc_attr( $slider_id ); ?>">
                    <div class="swiper-wrapper">
                        <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                            <?php 
                            global $product; 
                            $product_id = $product->get_id();
                            
                            // Get custom fields (try new product-info keys first, then fallbacks)
                            $breed = get_post_meta( $product_id, '_cow_breed', true );
                            if ( empty( $breed ) ) {
                                $breed = get_post_meta( $product_id, '_cattle_breed', true );
                            }

                            $live_weight = get_post_meta( $product_id, '_cow_weight', true );
                            if ( empty( $live_weight ) ) {
                                $live_weight = get_post_meta( $product_id, '_live_weight', true );
                            } else {
                                // Add 'kg' unit if it's just a number
                                if ( is_numeric( $live_weight ) ) {
                                    $live_weight .= ' kg';
                                }
                            }

                            $badge_text = get_post_meta( $product_id, '_badge_text', true );
                            
                            // Fallback values
                            if ( empty( $breed ) ) {
                                $breed = __( 'Premium Breed', 'hello-elementor-child' );
                            }
                            if ( empty( $live_weight ) ) {
                                $live_weight = '450 kg';
                            }
                            if ( empty( $badge_text ) ) {
                                $badge_text = __( 'Premium', 'hello-elementor-child' );
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="cattle-card">
                                    <div class="card-image-wrapper">
                                        <span class="cattle-badge"><?php echo esc_html( $badge_text ); ?></span>
                                        <a href="<?php echo esc_url( get_permalink() ); ?>">
                                            <?php echo $product->get_image( 'medium' ); ?>
                                        </a>
                                    </div>
                                    <div class="card-content">
                                        <h3 class="cattle-name">
                                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                                <?php echo esc_html( get_the_title() ); ?>
                                            </a>
                                        </h3>
                                        <p class="cattle-breed"><?php echo esc_html( $breed ); ?></p>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <div class="info-value price-value">
                                                    <?php echo $product->get_price_html(); ?>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-value weight-value">
                                                    <?php echo esc_html( $live_weight ); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="details-btn">
                                            <?php esc_html_e( 'View Details', 'hello-elementor-child' ); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php if ( $settings['show_arrows'] === 'yes' ) : ?>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    <?php endif; ?>
                    
                </div>
                <?php if ( $settings['show_dots'] === 'yes' ) : ?>
                    <div class="swiper-pagination <?php echo esc_attr( $slider_id ); ?>-pagination"></div>
                <?php endif; ?>
            </div>

            <script>
            jQuery(document).ready(function($) {
                new Swiper('.<?php echo esc_js( $slider_id ); ?>', {
                    slidesPerView: <?php echo esc_js( $settings['slides_to_show_mobile'] ?? 1 ); ?>,
                    spaceBetween: <?php echo esc_js( $settings['space_between']['size'] ?? 15 ); ?>,
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
                        el: '.<?php echo esc_js( $slider_id ); ?>-pagination',
                        clickable: true,
                    },
                    <?php endif; ?>
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 10,
                        },
                        1024: {
                            slidesPerView: <?php echo esc_js( $settings['slides_to_show_tablet'] ?? 2 ); ?>,
                            spaceBetween: 12,
                        },
                        1400: {
                            slidesPerView: <?php echo esc_js( $settings['slides_to_show'] ?? 4 ); ?>,
                            spaceBetween: <?php echo esc_js( $settings['space_between']['size'] ?? 15 ); ?>,
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
