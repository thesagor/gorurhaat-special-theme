<?php
/**
 * Custom Elementor Product Grid Widget
 * 
 * Displays WooCommerce products in a grid layout with category filtering
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Product Grid Widget Class
 */
class Hello_Elementor_Product_Grid_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'product_grid';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Product Grid', 'hello-elementor-child' );
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-products';
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
        return [ 'product', 'grid', 'woocommerce', 'shop', 'category' ];
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
                'label' => __( 'Products Per Page', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
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

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __( 'Layout Settings', 'hello-elementor-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Columns
        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .product-grid-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        // Gap
        $this->add_responsive_control(
            'gap',
            [
                'label' => __( 'Gap', 'hello-elementor-child' ),
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
                'selectors' => [
                    '{{WRAPPER}} .product-grid-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .product-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Card Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .product-card',
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
                    '{{WRAPPER}} .product-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Card Box Shadow
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .product-card',
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
                    '{{WRAPPER}} .product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        // Title Color
        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .product-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Title Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .product-title',
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

        // Price Color
        $this->add_control(
            'price_color',
            [
                'label' => __( 'Color', 'hello-elementor-child' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2c3e50',
                'selectors' => [
                    '{{WRAPPER}} .product-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Price Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .product-price',
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

        // Button Typography
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .product-button',
            ]
        );

        // Button Tabs
        $this->start_controls_tabs( 'button_tabs' );

        // Normal Tab
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
                    '{{WRAPPER}} .product-button' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover Tab
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
                    '{{WRAPPER}} .product-button:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .product-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Button Border Radius
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
                    '{{WRAPPER}} .product-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Button Padding
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
                    '{{WRAPPER}} .product-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

        // Add product type filter (custom meta)
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
            ?>
            <div class="product-grid-container">
                <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                    <?php
                    global $product;
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                <?php echo $product->get_image( 'medium' ); ?>
                            </a>
                        </div>
                        <div class="product-content">
                            <h3 class="product-title">
                                <a href="<?php echo esc_url( get_permalink() ); ?>">
                                    <?php echo esc_html( get_the_title() ); ?>
                                </a>
                            </h3>
                            <div class="product-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="product-button">
                                <?php esc_html_e( 'View Details', 'hello-elementor-child' ); ?>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
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

    /**
     * Render widget output in the editor.
     */
    protected function content_template() {
        ?>
        <#
        view.addRenderAttribute( 'wrapper', 'class', 'product-grid-container' );
        #>
        <div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
            <div class="product-card">
                <div class="product-image">
                    <img src="https://via.placeholder.com/300x300" alt="Product">
                </div>
                <div class="product-content">
                    <h3 class="product-title">Sample Product</h3>
                    <div class="product-price">$99.00</div>
                    <a href="#" class="product-button">View Details</a>
                </div>
            </div>
        </div>
        <?php
    }
}
