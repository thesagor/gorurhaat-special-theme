<?php
/**
 * Replace short description with product info on product pages
 */
function hello_elementor_child_replace_short_description_with_product_info() {
    // Remove the default short description
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

    // Add product info in place of short description using template
    add_action( 'woocommerce_single_product_summary', 'hello_elementor_child_display_product_info_template', 20 );
}
add_action( 'init', 'hello_elementor_child_replace_short_description_with_product_info' );

/**
 * Load product info template instead of excerpt
 */
function hello_elementor_child_display_product_info_template() {
    wc_get_template( 'single-product/short-description.php' );
}

/**
 * Hide category and brand metaboxes in admin
 */
function hello_elementor_child_remove_metaboxes() {
    // Remove category metabox
    remove_meta_box( 'product_catdiv', 'product', 'side' );

    // Remove brand metabox if it exists
    remove_meta_box( 'product_branddiv', 'product', 'side' );
}
add_action( 'add_meta_boxes', 'hello_elementor_child_remove_metaboxes', 20 );

/**
 * Add custom column to products admin list
 * 
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function hello_elementor_child_add_product_columns( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        
        // Add our column after the product name
        if ( $key === 'name' ) {
            $new_columns['product_info_type'] = __( 'Product Type', 'hello-elementor-child' );
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_edit-product_columns', 'hello_elementor_child_add_product_columns', 20 );

/**
 * Display custom column content in products admin list
 * 
 * @param string $column  Column name
 * @param int    $post_id Post ID
 */
function hello_elementor_child_display_product_column( $column, $post_id ) {
    if ( $column === 'product_info_type' ) {
        $product_type = get_post_meta( $post_id, '_product_info_type', true );
        
        if ( ! empty( $product_type ) ) {
            $type_labels = array(
                'livestock'     => __( 'Livestock', 'hello-elementor-child' ),
                'dairy_product' => __( 'Dairy Product', 'hello-elementor-child' ),
                'feed'          => __( 'Animal Feed', 'hello-elementor-child' )
            );
            
            $label = isset( $type_labels[ $product_type ] ) ? $type_labels[ $product_type ] : ucfirst( $product_type );
            
            // Add color-coded badge
            $colors = array(
                'livestock'     => '#4CAF50',
                'dairy_product' => '#2196F3',
                'feed'          => '#FF9800'
            );
            
            $color = isset( $colors[ $product_type ] ) ? $colors[ $product_type ] : '#999';
            
            echo '<span style="display:inline-block;padding:3px 8px;border-radius:3px;background:' . esc_attr( $color ) . ';color:#fff;font-size:11px;font-weight:600;">' . esc_html( $label ) . '</span>';
        } else {
            echo '<span style="color:#999;">—</span>';
        }
    }
}
add_action( 'manage_product_posts_custom_column', 'hello_elementor_child_display_product_column', 10, 2 );

/**
 * Make product type column sortable
 * 
 * @param array $columns Sortable columns
 * @return array Modified sortable columns
 */
function hello_elementor_child_sortable_product_columns( $columns ) {
    $columns['product_info_type'] = 'product_info_type';
    return $columns;
}
add_filter( 'manage_edit-product_sortable_columns', 'hello_elementor_child_sortable_product_columns' );

/**
 * Handle sorting by product type
 * 
 * @param WP_Query $query The WordPress query object
 */
function hello_elementor_child_product_type_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $orderby = $query->get( 'orderby' );

    if ( 'product_info_type' === $orderby ) {
        $query->set( 'meta_key', '_product_info_type' );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'hello_elementor_child_product_type_orderby' );
