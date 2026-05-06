<?php
/**
 * Product Card — Gorurhaat Custom Design
 * Fully explicit template: NO woocommerce_before/after_shop_loop_item hooks
 * (those hooks fire duplicate add-to-cart and open/close link wrappers that
 *  conflict with our custom card structure).
 *
 * @package HelloElementorChild
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$url      = $product->get_permalink();
$on_sale  = $product->is_on_sale();
$in_stock = $product->is_in_stock();
?>
<li <?php wc_product_class( 'gh-product-card', $product ); ?>>

    <?php /* ── Image ── */ ?>
    <a href="<?php echo esc_url( $url ); ?>" class="gh-card-img-link" tabindex="-1" aria-hidden="true">
        <?php
        echo $product->get_image(
            'woocommerce_thumbnail',
            array( 'class' => 'gh-card-img', 'loading' => 'lazy', 'decoding' => 'async' )
        ); // phpcs:ignore WordPress.Security.EscapeOutput
        ?>
        <?php if ( $on_sale ) : ?>
            <span class="onsale"><?php esc_html_e( 'Sale!', 'hello-elementor-child' ); ?></span>
        <?php endif; ?>
        <?php if ( ! $in_stock ) : ?>
            <span class="gh-out-of-stock"><?php esc_html_e( 'Out of Stock', 'hello-elementor-child' ); ?></span>
        <?php endif; ?>
    </a>

    <?php /* ── Body ── */ ?>
    <div class="gh-card-body">
        <h2 class="gh-card-title">
            <a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
        </h2>
        <div class="gh-card-price">
            <?php echo wp_kses_post( $product->get_price_html() ); ?>
        </div>
    </div>

    <?php /* ── Actions ── */ ?>
    <div class="gh-card-actions">
        <?php
        // woocommerce_template_loop_add_to_cart() handles variable, grouped,
        // external, and simple products correctly with full AJAX support.
        woocommerce_template_loop_add_to_cart( array( 'class' => 'gh-atc-btn' ) );
        ?>
        <a href="<?php echo esc_url( $url ); ?>" class="gh-view-btn">
            <?php esc_html_e( 'View Details', 'hello-elementor-child' ); ?>
        </a>
    </div>

</li>
