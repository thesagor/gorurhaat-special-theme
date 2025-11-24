<?php
/**
 * AJAX: Add product to cart
 */
function hello_elementor_child_ajax_add_to_cart() {
    check_ajax_referer( 'cart_drawer_nonce', 'nonce' );

    $product_id = absint( $_POST['product_id'] );
    $quantity = absint( $_POST['quantity'] ?? 1 );

    if ( $product_id <= 0 ) {
        wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'hello-elementor-child' ) ) );
    }

    // Add to cart
    $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( $cart_item_key ) {
        wp_send_json_success( array(
            'message' => __( 'Product added to cart!', 'hello-elementor-child' ),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_html' => hello_elementor_child_get_cart_drawer_html(),
        ));
    } else {
        wp_send_json_error( array( 'message' => __( 'Failed to add product to cart', 'hello-elementor-child' ) ) );
    }
}
add_action( 'wp_ajax_add_to_cart', 'hello_elementor_child_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_add_to_cart', 'hello_elementor_child_ajax_add_to_cart' );

/**
 * AJAX: Remove item from cart
 */
function hello_elementor_child_ajax_remove_cart_item() {
    check_ajax_referer( 'cart_drawer_nonce', 'nonce' );

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );

    if ( WC()->cart->remove_cart_item( $cart_item_key ) ) {
        wp_send_json_success( array(
            'message' => __( 'Item removed from cart', 'hello-elementor-child' ),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_html' => hello_elementor_child_get_cart_drawer_html(),
        ));
    } else {
        wp_send_json_error( array( 'message' => __( 'Failed to remove item', 'hello-elementor-child' ) ) );
    }
}
add_action( 'wp_ajax_remove_cart_item', 'hello_elementor_child_ajax_remove_cart_item' );
add_action( 'wp_ajax_nopriv_remove_cart_item', 'hello_elementor_child_ajax_remove_cart_item' );

/**
 * AJAX: Update cart item quantity
 */
function hello_elementor_child_ajax_update_cart_quantity() {
    check_ajax_referer( 'cart_drawer_nonce', 'nonce' );

    $cart_item_key = sanitize_text_field( $_POST['cart_item_key'] );
    $quantity = absint( $_POST['quantity'] );

    if ( $quantity <= 0 ) {
        WC()->cart->remove_cart_item( $cart_item_key );
    } else {
        WC()->cart->set_quantity( $cart_item_key, $quantity );
    }

    wp_send_json_success( array(
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_html' => hello_elementor_child_get_cart_drawer_html(),
    ));
}
add_action( 'wp_ajax_update_cart_quantity', 'hello_elementor_child_ajax_update_cart_quantity' );
add_action( 'wp_ajax_nopriv_update_cart_quantity', 'hello_elementor_child_ajax_update_cart_quantity' );

/**
 * AJAX: Get cart drawer HTML
 */
function hello_elementor_child_ajax_get_cart_drawer() {
    check_ajax_referer( 'cart_drawer_nonce', 'nonce' );

    wp_send_json_success( array(
        'cart_html' => hello_elementor_child_get_cart_drawer_html(),
        'cart_count' => WC()->cart->get_cart_contents_count(),
    ));
}
add_action( 'wp_ajax_get_cart_drawer', 'hello_elementor_child_ajax_get_cart_drawer' );
add_action( 'wp_ajax_nopriv_get_cart_drawer', 'hello_elementor_child_ajax_get_cart_drawer' );

/**
 * Generate cart drawer HTML
 */
function hello_elementor_child_get_cart_drawer_html() {
    // Check if WooCommerce is available
    if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
        return '<div class="cart-drawer-empty"><p>' . esc_html__( 'Cart is not available', 'hello-elementor-child' ) . '</p></div>';
    }
    
    ob_start();
    
    if ( WC()->cart->is_empty() ) {
        ?>
        <div class="cart-drawer-empty">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <p><?php esc_html_e( 'Your cart is empty', 'hello-elementor-child' ); ?></p>
        </div>
        <?php
    } else {
        ?>
        <div class="cart-drawer-items">
            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>
                    <div class="cart-drawer-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                        <div class="cart-item-image">
                            <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                            if ( ! $product_permalink ) {
                                echo $thumbnail;
                            } else {
                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                            }
                            ?>
                        </div>
                        <div class="cart-item-details">
                            <h4 class="cart-item-title">
                                <?php
                                if ( ! $product_permalink ) {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                                } else {
                                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                }
                                ?>
                            </h4>
                            <div class="cart-item-price">
                                <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                            </div>
                            <div class="cart-item-quantity">
                                <button class="qty-btn qty-minus" data-action="decrease">-</button>
                                <input type="number" class="qty-input" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="1" readonly>
                                <button class="qty-btn qty-plus" data-action="increase">+</button>
                            </div>
                        </div>
                        <button class="cart-item-remove" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        
        <div class="cart-drawer-footer">
            <div class="cart-drawer-subtotal">
                <span><?php esc_html_e( 'Subtotal:', 'hello-elementor-child' ); ?></span>
                <strong><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
            </div>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="cart-drawer-checkout-btn">
                <?php esc_html_e( 'Proceed to Checkout', 'hello-elementor-child' ); ?>
            </a>
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-drawer-view-cart">
                <?php esc_html_e( 'View Cart', 'hello-elementor-child' ); ?>
            </a>
        </div>
        <?php
    }
    
    return ob_get_clean();
}

/**
 * Add cart drawer HTML to footer
 */
function hello_elementor_child_add_cart_drawer_html() {
    // Only add cart drawer if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    ?>
    <div class="cart-drawer-overlay"></div>
    <div class="cart-drawer">
        <div class="cart-drawer-header">
            <h3><?php esc_html_e( 'Shopping Cart', 'hello-elementor-child' ); ?></h3>
            <button class="cart-drawer-close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="cart-drawer-body">
            <?php echo hello_elementor_child_get_cart_drawer_html(); ?>
        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'hello_elementor_child_add_cart_drawer_html' );
