<?php
/**
 * WooCommerce UX Enhancements for Gorurhaat
 *
 * Goal-focused improvements for cart, checkout, empty cart, and order confirmation
 * to maximise conversions for a Bangladesh livestock marketplace.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function gorurhaat_ux_whatsapp_url( $message_bn = '' ) {
    $number  = preg_replace( '/[^0-9+]/', '', get_option( 'whatsapp_contact_number', '+8801780884888' ) );
    $message = $message_bn ?: 'আমি একটি অর্ডার করতে চাই। দয়া করে সাহায্য করুন।';
    return 'https://wa.me/' . $number . '?text=' . rawurlencode( $message );
}

function gorurhaat_ux_whatsapp_svg() {
    return '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.785"/></svg>';
}

// ---------------------------------------------------------------------------
// Product page — trust bar (delivery, secure pay, verified, support)
// ---------------------------------------------------------------------------

function gorurhaat_product_trust_bar() {
    global $product;
    if ( ! $product ) {
        return;
    }
    ?>
    <div class="gorurhaat-product-trust" role="list" aria-label="<?php esc_attr_e( 'Purchase guarantees', 'hello-elementor-child' ); ?>">
        <div class="product-trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span><strong>সারা বাংলাদেশে</strong> ডেলিভারি</span>
        </div>
        <div class="product-trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span><strong>নিরাপদ</strong> পেমেন্ট</span>
        </div>
        <div class="product-trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><strong>যাচাইকৃত</strong> পণ্য</span>
        </div>
        <div class="product-trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.69 15.1 19.79 19.79 0 011.62 6.44 2 2 0 013.6 4.34h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.91 11a16 16 0 006 6l.92-.92a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 18.14z"/></svg>
            <span><strong>24/7</strong> সাপোর্ট</span>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_single_product_summary', 'gorurhaat_product_trust_bar', 25 );

// ---------------------------------------------------------------------------
// Cart page — trust strip at top
// ---------------------------------------------------------------------------

function gorurhaat_cart_trust_strip() {
    if ( ! is_cart() ) {
        return;
    }
    ?>
    <div class="gorurhaat-trust-strip" role="list">
        <div class="trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span><?php esc_html_e( 'নিরাপদ পেমেন্ট', 'hello-elementor-child' ); ?></span>
        </div>
        <div class="trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <span><?php esc_html_e( 'সারা বাংলাদেশে ডেলিভারি', 'hello-elementor-child' ); ?></span>
        </div>
        <div class="trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.69 15.1 19.79 19.79 0 011.62 6.44 2 2 0 013.6 4.34h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.91 11a16 16 0 006 6l.92-.92a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 18.14z"/></svg>
            <span><?php esc_html_e( 'WhatsApp সাপোর্ট', 'hello-elementor-child' ); ?></span>
        </div>
        <div class="trust-item" role="listitem">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?php esc_html_e( '১০০% বিশ্বস্ত', 'hello-elementor-child' ); ?></span>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_before_cart', 'gorurhaat_cart_trust_strip' );

// ---------------------------------------------------------------------------
// Cart page — free delivery progress notice
// ---------------------------------------------------------------------------

function gorurhaat_cart_delivery_notice() {
    if ( ! is_cart() || WC()->cart->is_empty() ) {
        return;
    }

    $threshold  = apply_filters( 'gorurhaat_free_delivery_threshold', 50000 );
    $cart_total = WC()->cart->get_cart_contents_total();

    if ( $cart_total >= $threshold ) {
        echo '<div class="gorurhaat-delivery-notice delivery-free" role="status">'
            . '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
            . ' ' . esc_html__( 'আপনি বিনামূল্যে ডেলিভারি পাচ্ছেন!', 'hello-elementor-child' )
            . '</div>';
    } else {
        $remaining = wc_price( $threshold - $cart_total );
        echo '<div class="gorurhaat-delivery-notice delivery-pending" role="status">'
            . '<svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>'
            . ' ' . sprintf(
                wp_kses(
                    /* translators: %s: remaining amount */
                    __( 'বিনামূল্যে ডেলিভারির জন্য আরও <strong>%s</strong> এর পণ্য যোগ করুন।', 'hello-elementor-child' ),
                    array( 'strong' => array() )
                ),
                $remaining
            )
            . '</div>';
    }
}
add_action( 'woocommerce_before_cart_table', 'gorurhaat_cart_delivery_notice' );

// ---------------------------------------------------------------------------
// Cart page — WhatsApp help link below cart table
// ---------------------------------------------------------------------------

function gorurhaat_cart_whatsapp_help() {
    if ( ! is_cart() || WC()->cart->is_empty() ) {
        return;
    }
    $url = gorurhaat_ux_whatsapp_url( 'আমি একটি অর্ডার করতে চাই। দয়া করে সাহায্য করুন।' );
    ?>
    <div class="gorurhaat-cart-whatsapp">
        <p><?php esc_html_e( 'অর্ডার করতে সমস্যা হচ্ছে?', 'hello-elementor-child' ); ?></p>
        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="gorurhaat-whatsapp-cta-btn">
            <?php echo gorurhaat_ux_whatsapp_svg(); ?>
            <?php esc_html_e( 'WhatsApp-এ অর্ডার করুন', 'hello-elementor-child' ); ?>
        </a>
    </div>
    <?php
}
add_action( 'woocommerce_after_cart_table', 'gorurhaat_cart_whatsapp_help' );

// ---------------------------------------------------------------------------
// Checkout page — trust badges above form
// ---------------------------------------------------------------------------

function gorurhaat_checkout_trust_badges() {
    if ( ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
        return;
    }
    ?>
    <div class="gorurhaat-checkout-trust" role="list">
        <div class="checkout-trust-item" role="listitem">
            <svg width="22" height="22" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <div>
                <strong><?php esc_html_e( 'নিরাপদ পেমেন্ট', 'hello-elementor-child' ); ?></strong>
                <span><?php esc_html_e( 'SSL সুরক্ষিত', 'hello-elementor-child' ); ?></span>
            </div>
        </div>
        <div class="checkout-trust-item" role="listitem">
            <svg width="22" height="22" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            <div>
                <strong><?php esc_html_e( 'দ্রুত ডেলিভারি', 'hello-elementor-child' ); ?></strong>
                <span><?php esc_html_e( 'সারা বাংলাদেশ', 'hello-elementor-child' ); ?></span>
            </div>
        </div>
        <div class="checkout-trust-item" role="listitem">
            <svg width="22" height="22" fill="none" stroke="#27ae60" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong><?php esc_html_e( '১০০% বিশ্বস্ত', 'hello-elementor-child' ); ?></strong>
                <span><?php esc_html_e( 'যাচাইকৃত বিক্রেতা', 'hello-elementor-child' ); ?></span>
            </div>
        </div>
    </div>
    <?php
}
add_action( 'woocommerce_before_checkout_form', 'gorurhaat_checkout_trust_badges', 5 );

// ---------------------------------------------------------------------------
// Checkout page — WhatsApp order alternative
// ---------------------------------------------------------------------------

function gorurhaat_checkout_whatsapp_option() {
    if ( ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
        return;
    }
    $url = gorurhaat_ux_whatsapp_url( 'আমি অর্ডার করতে চাই। দয়া করে সাহায্য করুন।' );
    ?>
    <div class="gorurhaat-checkout-whatsapp">
        <span class="checkout-whatsapp-divider"><?php esc_html_e( 'অথবা সরাসরি', 'hello-elementor-child' ); ?></span>
        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="gorurhaat-whatsapp-cta-btn">
            <?php echo gorurhaat_ux_whatsapp_svg(); ?>
            <?php esc_html_e( 'WhatsApp-এ অর্ডার করুন', 'hello-elementor-child' ); ?>
        </a>
    </div>
    <?php
}
add_action( 'woocommerce_before_checkout_form', 'gorurhaat_checkout_whatsapp_option', 20 );

// ---------------------------------------------------------------------------
// Empty cart page — custom CTA with shop link + WhatsApp
// ---------------------------------------------------------------------------

function gorurhaat_empty_cart_page() {
    $shop_url    = get_permalink( wc_get_page_id( 'shop' ) );
    $wa_url      = gorurhaat_ux_whatsapp_url( 'আমি একটি গরু কিনতে চাই। দয়া করে সাহায্য করুন।' );
    ?>
    <div class="gorurhaat-empty-cart">
        <div class="empty-cart-icon" aria-hidden="true">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#cccccc" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
        <h2><?php esc_html_e( 'আপনার কার্ট খালি!', 'hello-elementor-child' ); ?></h2>
        <p><?php esc_html_e( 'পছন্দের পণ্য বেছে নিন অথবা WhatsApp-এ সরাসরি অর্ডার করুন।', 'hello-elementor-child' ); ?></p>
        <div class="empty-cart-actions">
            <a href="<?php echo esc_url( $shop_url ); ?>" class="gorurhaat-btn-primary">
                <?php esc_html_e( 'কেনাকাটা শুরু করুন', 'hello-elementor-child' ); ?>
            </a>
            <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="gorurhaat-whatsapp-cta-btn">
                <?php echo gorurhaat_ux_whatsapp_svg(); ?>
                <?php esc_html_e( 'WhatsApp-এ অর্ডার করুন', 'hello-elementor-child' ); ?>
            </a>
        </div>
    </div>
    <?php
}
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'gorurhaat_empty_cart_page', 10 );

// ---------------------------------------------------------------------------
// Order received page — next-steps + WhatsApp CTA
// ---------------------------------------------------------------------------

function gorurhaat_order_received_section( $order ) {
    if ( ! $order ) {
        return;
    }
    $order_id = $order->get_id();
    $wa_url   = gorurhaat_ux_whatsapp_url( 'আমার অর্ডার #' . $order_id . ' সম্পর্কে জানতে চাই।' );
    ?>
    <div class="gorurhaat-order-success">
        <div class="order-next-steps">
            <h3><?php esc_html_e( 'পরবর্তী পদক্ষেপ', 'hello-elementor-child' ); ?></h3>
            <ul>
                <li>
                    <svg width="16" height="16" fill="none" stroke="#27ae60" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।', 'hello-elementor-child' ); ?>
                </li>
                <li>
                    <svg width="16" height="16" fill="none" stroke="#27ae60" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'ডেলিভারির আগে পণ্যের ভিডিও পাঠানো হবে।', 'hello-elementor-child' ); ?>
                </li>
                <li>
                    <svg width="16" height="16" fill="none" stroke="#27ae60" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e( 'যেকোনো প্রশ্নে WhatsApp করুন।', 'hello-elementor-child' ); ?>
                </li>
            </ul>
        </div>
        <a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="gorurhaat-whatsapp-cta-btn">
            <?php echo gorurhaat_ux_whatsapp_svg(); ?>
            <?php esc_html_e( 'WhatsApp-এ যোগাযোগ করুন', 'hello-elementor-child' ); ?>
        </a>
    </div>
    <?php
}
add_action( 'woocommerce_thankyou', 'gorurhaat_order_received_section', 5 );
