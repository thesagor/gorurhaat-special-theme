<?php
/**
 * Single Product Meta
 *
 * This template displays product meta information including cow-specific data
 *
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;
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
    if( !empty( $cow_notes ) ) : ?>
        <div class="cow-additional-notes">
            <strong><?php esc_html_e('Additional Notes:', 'hello-elementor-child'); ?></strong>
            <div class="cow-notes-content"><?php echo esc_html( $cow_notes ); ?></div>
        </div>
    <?php endif; ?>

    <?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
