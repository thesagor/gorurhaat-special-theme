<?php
/**
 * Single Product short description
 *
 * This template displays product information based on product type
 *
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

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
if( !empty( $product_info_type ) || !empty( $cow_type ) || !empty( $dairy_product_type ) || !empty( $feed_type ) ) : ?>

<div class="product-info-fields-display">
    <div class="product-fields-grid">

        <?php if( $product_info_type === 'livestock' ) : ?>
            <!-- LIVESTOCK INFORMATION DISPLAY -->
            <?php if( !empty( $cow_type ) ) :
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

            <?php if( !empty( $cow_breed ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Breed:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $cow_breed ); ?></span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $cow_age ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Age:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $cow_age ); ?></span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $cow_weight ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Weight:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $cow_weight ); ?> kg</span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $cow_color ) ) :
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

            <?php if( !empty( $cow_health_status ) ) :
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

            <?php if( $cow_vaccinated === 'yes' ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Vaccination:', 'hello-elementor-child'); ?></span>
                    <span class="field-value vaccinated-status"><?php esc_html_e('Up to date', 'hello-elementor-child'); ?></span>
                </div>
            <?php endif; ?>

        <?php elseif( $product_info_type === 'dairy_product' ) : ?>
            <!-- DAIRY PRODUCT INFORMATION DISPLAY -->
            <?php if( !empty( $dairy_product_type ) ) :
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

            <?php if( !empty( $dairy_unit_size ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Unit Size:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $dairy_unit_size ); ?></span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $dairy_fat_content ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Fat Content:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $dairy_fat_content ); ?>%</span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $dairy_shelf_life ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Shelf Life:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $dairy_shelf_life ); ?></span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $dairy_storage ) ) :
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

            <?php if( !empty( $dairy_origin ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Origin:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $dairy_origin ); ?></span>
                </div>
            <?php endif; ?>

        <?php elseif( $product_info_type === 'feed' ) : ?>
            <!-- ANIMAL FEED INFORMATION DISPLAY -->
            <?php if( !empty( $feed_type ) ) :
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

            <?php if( !empty( $feed_package_size ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Package Size:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $feed_package_size ); ?></span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $feed_protein_content ) ) : ?>
                <div class="product-field-item">
                    <span class="field-label"><?php esc_html_e('Protein Content:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $feed_protein_content ); ?>%</span>
                </div>
            <?php endif; ?>

            <?php if( !empty( $feed_suitable_for ) ) :
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

            <?php if( !empty( $feed_nutrition_info ) ) : ?>
                <div class="product-field-item nutrition-info">
                    <span class="field-label"><?php esc_html_e('Nutrition Info:', 'hello-elementor-child'); ?></span>
                    <span class="field-value"><?php echo esc_html( $feed_nutrition_info ); ?></span>
                </div>
            <?php endif; ?>

        <?php endif; ?>

        <?php // Display additional notes for all product types ?>
        <?php if( !empty( $product_notes ) ) : ?>
            <div class="product-field-item product-notes">
                <span class="field-label"><?php esc_html_e('Additional Notes:', 'hello-elementor-child'); ?></span>
                <span class="field-value"><?php echo esc_html( $product_notes ); ?></span>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php else : ?>
    <?php // Fallback to regular excerpt if no product info ?>
    <?php if ( $product->get_short_description() ) : ?>
        <div class="woocommerce-product-details__short-description">
            <?php echo $product->get_short_description(); // WPCS: XSS ok. ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
