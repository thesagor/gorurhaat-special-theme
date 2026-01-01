<?php
/**
 * Cattle Product Custom Fields
 * 
 * Adds custom fields for cattle/livestock products:
 * - Cattle Breed
 * - Live Weight
 * - Badge Text
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add custom fields to WooCommerce product general tab
 */
function hello_elementor_child_add_cattle_custom_fields() {
    global $post;
    
    echo '<div class="options_group">';
    
    echo '<h4 style="padding: 10px 12px; margin: 0; border-bottom: 1px solid #eee;">' . __( 'Cattle Information', 'hello-elementor-child' ) . '</h4>';
    
    // Cattle Breed
    woocommerce_wp_text_input( array(
        'id' => '_cattle_breed',
        'label' => __( 'Cattle Breed', 'hello-elementor-child' ),
        'placeholder' => __( 'e.g., Holstein Friesian, Jersey Cow, Angus Bull', 'hello-elementor-child' ),
        'desc_tip' => true,
        'description' => __( 'Enter the breed of the cattle/livestock', 'hello-elementor-child' ),
        'type' => 'text',
    ) );
    
    // Live Weight
    woocommerce_wp_text_input( array(
        'id' => '_live_weight',
        'label' => __( 'Live Weight', 'hello-elementor-child' ),
        'placeholder' => __( 'e.g., 450 kg, 380 kg, 550 kg', 'hello-elementor-child' ),
        'desc_tip' => true,
        'description' => __( 'Enter the live weight of the cattle (include unit)', 'hello-elementor-child' ),
        'type' => 'text',
    ) );
    
    // Badge Text
    woocommerce_wp_text_input( array(
        'id' => '_badge_text',
        'label' => __( 'Badge Text', 'hello-elementor-child' ),
        'placeholder' => __( 'e.g., Premium, Featured, Best Seller', 'hello-elementor-child' ),
        'desc_tip' => true,
        'description' => __( 'Enter the badge text to display on the product card', 'hello-elementor-child' ),
        'type' => 'text',
        'value' => get_post_meta( $post->ID, '_badge_text', true ) ?: 'Premium',
    ) );
    
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'hello_elementor_child_add_cattle_custom_fields' );

/**
 * Save custom fields when product is saved
 */
function hello_elementor_child_save_cattle_custom_fields( $post_id ) {
    // Cattle Breed
    $cattle_breed = isset( $_POST['_cattle_breed'] ) ? sanitize_text_field( $_POST['_cattle_breed'] ) : '';
    update_post_meta( $post_id, '_cattle_breed', $cattle_breed );
    
    // Live Weight
    $live_weight = isset( $_POST['_live_weight'] ) ? sanitize_text_field( $_POST['_live_weight'] ) : '';
    update_post_meta( $post_id, '_live_weight', $live_weight );
    
    // Badge Text
    $badge_text = isset( $_POST['_badge_text'] ) ? sanitize_text_field( $_POST['_badge_text'] ) : 'Premium';
    update_post_meta( $post_id, '_badge_text', $badge_text );
}
add_action( 'woocommerce_process_product_meta', 'hello_elementor_child_save_cattle_custom_fields' );

/**
 * Display custom fields in product admin columns (optional)
 */
function hello_elementor_child_add_cattle_product_columns( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $key => $column ) {
        $new_columns[ $key ] = $column;
        
        // Add custom columns after the product title
        if ( $key === 'name' ) {
            $new_columns['cattle_breed'] = __( 'Breed', 'hello-elementor-child' );
            $new_columns['live_weight'] = __( 'Weight', 'hello-elementor-child' );
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_edit-product_columns', 'hello_elementor_child_add_cattle_product_columns' );

/**
 * Populate custom columns with data
 */
function hello_elementor_child_populate_cattle_product_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'cattle_breed':
            $breed = get_post_meta( $post_id, '_cattle_breed', true );
            echo $breed ? esc_html( $breed ) : '—';
            break;
            
        case 'live_weight':
            $weight = get_post_meta( $post_id, '_live_weight', true );
            echo $weight ? esc_html( $weight ) : '—';
            break;
    }
}
add_action( 'manage_product_posts_custom_column', 'hello_elementor_child_populate_cattle_product_columns', 10, 2 );

/**
 * Make custom columns sortable (optional)
 */
function hello_elementor_child_make_cattle_columns_sortable( $columns ) {
    $columns['cattle_breed'] = 'cattle_breed';
    $columns['live_weight'] = 'live_weight';
    return $columns;
}
add_filter( 'manage_edit-product_sortable_columns', 'hello_elementor_child_make_cattle_columns_sortable' );

/**
 * Add custom fields to quick edit (optional)
 */
function hello_elementor_child_add_cattle_quick_edit_fields( $column_name, $post_type ) {
    if ( $post_type !== 'product' ) {
        return;
    }
    
    switch ( $column_name ) {
        case 'cattle_breed':
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <label>
                        <span class="title"><?php _e( 'Cattle Breed', 'hello-elementor-child' ); ?></span>
                        <span class="input-text-wrap">
                            <input type="text" name="_cattle_breed" class="text" value="">
                        </span>
                    </label>
                    <label>
                        <span class="title"><?php _e( 'Live Weight', 'hello-elementor-child' ); ?></span>
                        <span class="input-text-wrap">
                            <input type="text" name="_live_weight" class="text" value="">
                        </span>
                    </label>
                    <label>
                        <span class="title"><?php _e( 'Badge Text', 'hello-elementor-child' ); ?></span>
                        <span class="input-text-wrap">
                            <input type="text" name="_badge_text" class="text" value="">
                        </span>
                    </label>
                </div>
            </fieldset>
            <?php
            break;
    }
}
add_action( 'quick_edit_custom_box', 'hello_elementor_child_add_cattle_quick_edit_fields', 10, 2 );

/**
 * Save quick edit custom fields
 */
function hello_elementor_child_save_cattle_quick_edit_fields( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['_cattle_breed'] ) ) {
        update_post_meta( $post_id, '_cattle_breed', sanitize_text_field( $_POST['_cattle_breed'] ) );
    }
    
    if ( isset( $_POST['_live_weight'] ) ) {
        update_post_meta( $post_id, '_live_weight', sanitize_text_field( $_POST['_live_weight'] ) );
    }
    
    if ( isset( $_POST['_badge_text'] ) ) {
        update_post_meta( $post_id, '_badge_text', sanitize_text_field( $_POST['_badge_text'] ) );
    }
}
add_action( 'save_post', 'hello_elementor_child_save_cattle_quick_edit_fields' );
