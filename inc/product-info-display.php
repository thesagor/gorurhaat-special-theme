<?php
/**
 * Product Information Display using Hooks
 * 
 * This file replaces the outdated WooCommerce template overrides
 * with hook-based customizations for better compatibility.
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Display product-specific information fields
 * Replaces the short-description.php template
 */
function hello_elementor_child_display_product_info_fields() {
    global $product;
    
    if ( ! $product ) {
        return;
    }
    
    // Get the main product type first
    $product_info_type = get_post_meta( $product->get_id(), '_product_info_type', true );
    
    // Get all possible product information
    // Livestock fields
    $cow_type = get_post_meta( $product->get_id(), '_cow_type', true );
    $cow_breed = get_post_meta( $product->get_id(), '_cow_breed', true );
    $cow_age = get_post_meta( $product->get_id(), '_cow_age', true );
    $cow_weight = get_post_meta( $product->get_id(), '_cow_weight', true );
    $cow_color = get_post_meta( $product->get_id(), '_cow_color', true );
    $cow_health_status = get_post_meta( $product->get_id(), '_cow_health_status', true );
    $cow_vaccinated = get_post_meta( $product->get_id(), '_cow_vaccinated', true );
    
    // Dairy product fields
    $dairy_product_type = get_post_meta( $product->get_id(), '_dairy_product_type', true );
    $dairy_fat_content = get_post_meta( $product->get_id(), '_dairy_fat_content', true );
    $dairy_unit_size = get_post_meta( $product->get_id(), '_dairy_unit_size', true );
    $dairy_shelf_life = get_post_meta( $product->get_id(), '_dairy_shelf_life', true );
    $dairy_storage = get_post_meta( $product->get_id(), '_dairy_storage', true );
    $dairy_origin = get_post_meta( $product->get_id(), '_dairy_origin', true );
    
    // Animal feed fields
    $feed_type = get_post_meta( $product->get_id(), '_feed_type', true );
    $feed_package_size = get_post_meta( $product->get_id(), '_feed_package_size', true );
    $feed_protein_content = get_post_meta( $product->get_id(), '_feed_protein_content', true );
    $feed_suitable_for = get_post_meta( $product->get_id(), '_feed_suitable_for', true );
    $feed_nutrition_info = get_post_meta( $product->get_id(), '_feed_nutrition_info', true );
    
    // Common fields
    $product_notes = get_post_meta( $product->get_id(), '_product_notes', true );
    
    // Check if any product info exists
    if ( empty( $product_info_type ) && empty( $cow_type ) && empty( $dairy_product_type ) && empty( $feed_type ) ) {
        // Fallback to regular excerpt if no product info
        if ( $product->get_short_description() ) {
            echo '<div class="woocommerce-product-details__short-description">';
            echo $product->get_short_description(); // WPCS: XSS ok.
            echo '</div>';
        }
        return;
    }
    ?>
    
    <div class="product-info-fields-display">
        <div class="product-fields-grid">
            
            <?php if ( $product_info_type === 'livestock' ) : ?>
                <!-- LIVESTOCK INFORMATION DISPLAY -->
                <?php if ( ! empty( $cow_type ) ) :
                    $type_labels = array(
                        'ox' => __('Ox', 'hello-elementor-child'),
                        'dairy_cow' => __('Dairy Cow', 'hello-elementor-child'),
                        'bokna' => __('Bokna', 'hello-elementor-child')
                    );
                    $type_display = isset($type_labels[$cow_type]) ? $type_labels[$cow_type] : $cow_type;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Type:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $type_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $cow_breed ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Breed:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $cow_breed ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $cow_age ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Age:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $cow_age ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $cow_weight ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Weight:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $cow_weight ); ?> kg</span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $cow_color ) ) :
                    $color_labels = array(
                        'black' => __('Black', 'hello-elementor-child'),
                        'brown' => __('Brown', 'hello-elementor-child'),
                        'white' => __('White', 'hello-elementor-child'),
                        'black_white' => __('Black & White', 'hello-elementor-child'),
                        'brown_white' => __('Brown & White', 'hello-elementor-child'),
                        'mixed' => __('Mixed Colors', 'hello-elementor-child')
                    );
                    $color_display = isset($color_labels[$cow_color]) ? $color_labels[$cow_color] : $cow_color;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Color:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $color_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $cow_health_status ) ) :
                    $health_labels = array(
                        'excellent' => __('Excellent', 'hello-elementor-child'),
                        'good' => __('Good', 'hello-elementor-child'),
                        'fair' => __('Fair', 'hello-elementor-child'),
                        'needs_attention' => __('Needs Attention', 'hello-elementor-child')
                    );
                    $health_display = isset($health_labels[$cow_health_status]) ? $health_labels[$cow_health_status] : $cow_health_status;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Health Status:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $health_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( $cow_vaccinated === 'yes' ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Vaccination:', 'hello-elementor-child'); ?></span>
                        <span class="field-value vaccinated-status"><?php esc_html_e('Up to date', 'hello-elementor-child'); ?></span>
                    </div>
                <?php endif; ?>
                
            <?php elseif ( $product_info_type === 'dairy_product' ) : ?>
                <!-- DAIRY PRODUCT INFORMATION DISPLAY -->
                <?php if ( ! empty( $dairy_product_type ) ) :
                    $dairy_type_labels = array(
                        'milk' => __('Milk', 'hello-elementor-child'),
                        'butter' => __('Butter', 'hello-elementor-child'),
                        'cheese' => __('Cheese', 'hello-elementor-child'),
                        'yogurt' => __('Yogurt', 'hello-elementor-child'),
                        'cream' => __('Cream', 'hello-elementor-child'),
                        'ghee' => __('Ghee', 'hello-elementor-child')
                    );
                    $dairy_type_display = isset($dairy_type_labels[$dairy_product_type]) ? $dairy_type_labels[$dairy_product_type] : $dairy_product_type;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Product Type:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $dairy_type_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $dairy_unit_size ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Unit Size:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $dairy_unit_size ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $dairy_fat_content ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Fat Content:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $dairy_fat_content ); ?>%</span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $dairy_shelf_life ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Shelf Life:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $dairy_shelf_life ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $dairy_storage ) ) :
                    $storage_labels = array(
                        'refrigerated' => __('Refrigerated (2-4°C)', 'hello-elementor-child'),
                        'frozen' => __('Frozen (-18°C)', 'hello-elementor-child'),
                        'room_temperature' => __('Room Temperature', 'hello-elementor-child'),
                        'cool_dry_place' => __('Cool Dry Place', 'hello-elementor-child')
                    );
                    $storage_display = isset($storage_labels[$dairy_storage]) ? $storage_labels[$dairy_storage] : $dairy_storage;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Storage:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $storage_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $dairy_origin ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Origin:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $dairy_origin ); ?></span>
                    </div>
                <?php endif; ?>
                
            <?php elseif ( $product_info_type === 'feed' ) : ?>
                <!-- ANIMAL FEED INFORMATION DISPLAY -->
                <?php if ( ! empty( $feed_type ) ) :
                    $feed_type_labels = array(
                        'cattle_feed' => __('Cattle Feed', 'hello-elementor-child'),
                        'dairy_feed' => __('Dairy Cow Feed', 'hello-elementor-child'),
                        'calf_feed' => __('Calf Feed', 'hello-elementor-child'),
                        'hay' => __('Hay', 'hello-elementor-child'),
                        'silage' => __('Silage', 'hello-elementor-child'),
                        'concentrate' => __('Concentrate', 'hello-elementor-child')
                    );
                    $feed_type_display = isset($feed_type_labels[$feed_type]) ? $feed_type_labels[$feed_type] : $feed_type;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Feed Type:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $feed_type_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $feed_package_size ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Package Size:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $feed_package_size ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $feed_protein_content ) ) : ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Protein Content:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $feed_protein_content ); ?>%</span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $feed_suitable_for ) ) :
                    $suitable_labels = array(
                        'all_cattle' => __('All Cattle', 'hello-elementor-child'),
                        'dairy_cows' => __('Dairy Cows', 'hello-elementor-child'),
                        'beef_cattle' => __('Beef Cattle', 'hello-elementor-child'),
                        'calves' => __('Calves', 'hello-elementor-child'),
                        'oxen' => __('Oxen', 'hello-elementor-child')
                    );
                    $suitable_display = isset($suitable_labels[$feed_suitable_for]) ? $suitable_labels[$feed_suitable_for] : $feed_suitable_for;
                ?>
                    <div class="product-field-item">
                        <span class="field-label"><?php esc_html_e('Suitable For:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $suitable_display ); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $feed_nutrition_info ) ) : ?>
                    <div class="product-field-item nutrition-info">
                        <span class="field-label"><?php esc_html_e('Nutrition Info:', 'hello-elementor-child'); ?></span>
                        <span class="field-value"><?php echo esc_html( $feed_nutrition_info ); ?></span>
                    </div>
                <?php endif; ?>
                
            <?php endif; ?>
            
            <?php // Display additional notes for all product types ?>
            <?php if ( ! empty( $product_notes ) ) : ?>
                <div class="product-field-item product-notes">
                    <span class="field-label"><?php esc_html_e('Additional Notes:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $product_notes ); ?></span>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    <?php
}

/**
 * Display custom product meta (replaces meta.php template)
 */
function hello_elementor_child_display_custom_product_meta() {
    global $product;
    
    if ( ! $product ) {
        return;
    }
    ?>
    <div class="product_meta">
        
        <?php do_action( 'woocommerce_product_meta_start' ); ?>
        
        <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>
        <?php endif; ?>
        
        <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
        
        <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
        
        <?php
        // Get cow notes for additional information display
        $cow_notes = get_post_meta( $product->get_id(), '_cow_notes', true );
        if ( ! empty( $cow_notes ) ) : ?>
            <div class="cow-additional-notes">
                <strong><?php esc_html_e('Additional Notes:', 'hello-elementor-child'); ?></strong>
                <div class="cow-notes-content"><?php echo esc_html( $cow_notes ); ?></div>
            </div>
        <?php endif; ?>
        
        <?php do_action( 'woocommerce_product_meta_end' ); ?>
        
    </div>
    <?php
}

/**
 * Add custom wrapper for single product layout
 */
function hello_elementor_child_before_single_product_summary_wrapper() {
    echo '<div class="product-layout-wrapper"><div class="product-images-section">';
}
add_action( 'woocommerce_before_single_product_summary', 'hello_elementor_child_before_single_product_summary_wrapper', 5 );

function hello_elementor_child_after_single_product_summary_wrapper() {
    echo '</div><div class="summary entry-summary product-details-section"><div class="product-header">';
}
add_action( 'woocommerce_before_single_product_summary', 'hello_elementor_child_after_single_product_summary_wrapper', 25 );

function hello_elementor_child_close_product_header() {
    echo '</div></div></div>';
}
add_action( 'woocommerce_after_single_product_summary', 'hello_elementor_child_close_product_header', 5 );
