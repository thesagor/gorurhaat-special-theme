<?php
/**
 * Register Custom Elementor Widgets
 */
function hello_elementor_child_register_elementor_widgets( $widgets_manager ) {
    // Include widget files
    require_once( get_stylesheet_directory() . '/widgets/product-grid-widget.php' );
    require_once( get_stylesheet_directory() . '/widgets/product-slider-widget.php' );
    require_once( get_stylesheet_directory() . '/widgets/cattle-slider-widget.php' );
    require_once( get_stylesheet_directory() . '/widgets/hero-slider-widget.php' );



    // Register widgets
    $widgets_manager->register( new \Hello_Elementor_Product_Grid_Widget() );
    $widgets_manager->register( new \Hello_Elementor_Product_Slider_Widget() );
    $widgets_manager->register( new \Hello_Elementor_Cattle_Slider_Widget() );
    $widgets_manager->register( new \Hello_Elementor_Hero_Slider_Widget() );


}
add_action( 'elementor/widgets/register', 'hello_elementor_child_register_elementor_widgets' );

/**
 * Add custom widget categories
 */
function hello_elementor_child_add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'woocommerce-elements',
        [
            'title' => __( 'WooCommerce Elements', 'hello-elementor-child' ),
            'icon' => 'fa fa-shopping-cart',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'hello_elementor_child_add_elementor_widget_categories' );
