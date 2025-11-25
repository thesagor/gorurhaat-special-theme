<?php
/**
 * Add QR Codes to WooCommerce Order Emails
 * 
 * Generates QR codes for each product in the order so admin and customers
 * can scan and view product details
 */

/**
 * Add QR code column to order items in emails
 */
add_filter( 'woocommerce_email_order_items_args', 'hello_elementor_child_add_qr_code_to_email_items' );
function hello_elementor_child_add_qr_code_to_email_items( $args ) {
    $args['show_image'] = true;
    return $args;
}

/**
 * Display QR code for each product in order emails
 */
add_action( 'woocommerce_order_item_meta_end', 'hello_elementor_child_display_qr_code_in_email', 10, 4 );
function hello_elementor_child_display_qr_code_in_email( $item_id, $item, $order, $plain_text ) {
    // Only add QR code in emails, not on order pages
    if ( ! is_email() && ! did_action( 'woocommerce_email_order_details' ) ) {
        return;
    }
    
    // Get product from order item
    $product = $item->get_product();
    
    if ( ! $product ) {
        return;
    }
    
    // Get product URL (use shortlink if available)
    $product_url = wp_get_shortlink( $product->get_id() );
    if ( empty( $product_url ) ) {
        $product_url = get_permalink( $product->get_id() );
    }
    
    // Generate QR code using Google Charts API (free, no dependencies needed)
    $qr_code_url = hello_elementor_child_generate_qr_code_url( $product_url );
    
    // Don't show QR code in plain text emails
    if ( $plain_text ) {
        echo "\n" . __( 'Product Link:', 'hello-elementor-child' ) . ' ' . $product_url . "\n";
        return;
    }
    
    // Display QR code in HTML emails
    ?>
    <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 4px; text-align: center;">
        <img src="<?php echo esc_url( $qr_code_url ); ?>" 
             alt="<?php esc_attr_e( 'QR Code for product', 'hello-elementor-child' ); ?>" 
             style="width: 100px; height: 100px; margin: 5px auto; display: block;" />
        <small style="color: #666; font-size: 11px;">
            <?php esc_html_e( 'Scan to view product', 'hello-elementor-child' ); ?>
        </small>
    </div>
    <?php
}

/**
 * Generate QR code URL using Google Charts API
 * 
 * @param string $data The data to encode in QR code
 * @param int $size Size of QR code in pixels
 * @return string QR code image URL
 */
function hello_elementor_child_generate_qr_code_url( $data, $size = 150 ) {
    // Use Google Charts API to generate QR code
    // Format: https://chart.googleapis.com/chart?chs=SIZExSIZE&cht=qr&chl=DATA
    $qr_url = add_query_arg(
        array(
            'chs' => $size . 'x' . $size,
            'cht' => 'qr',
            'chl' => urlencode( $data ),
            'choe' => 'UTF-8'
        ),
        'https://chart.googleapis.com/chart'
    );
    
    return $qr_url;
}

/**
 * Alternative: Add QR codes in a separate section after order items
 * This adds all product QR codes in one section at the bottom
 */
add_action( 'woocommerce_email_after_order_table', 'hello_elementor_child_add_qr_codes_section', 10, 4 );
function hello_elementor_child_add_qr_codes_section( $order, $sent_to_admin, $plain_text, $email ) {
    // Skip for plain text emails
    if ( $plain_text ) {
        return;
    }
    
    // Get order items
    $items = $order->get_items();
    
    if ( empty( $items ) ) {
        return;
    }
    
    ?>
    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
        <h2 style="margin-top: 0; font-size: 18px; color: #333;">
            <?php esc_html_e( 'Product QR Codes', 'hello-elementor-child' ); ?>
        </h2>
        <p style="color: #666; font-size: 13px; margin-bottom: 20px;">
            <?php esc_html_e( 'Scan these QR codes to view product details on your mobile device', 'hello-elementor-child' ); ?>
        </p>
        
        <table cellspacing="0" cellpadding="0" style="width: 100%; border-collapse: collapse;">
            <?php
            foreach ( $items as $item_id => $item ) {
                $product = $item->get_product();
                
                if ( ! $product ) {
                    continue;
                }
                
                // Get product URL
                $product_url = wp_get_shortlink( $product->get_id() );
                if ( empty( $product_url ) ) {
                    $product_url = get_permalink( $product->get_id() );
                }
                
                // Generate QR code
                $qr_code_url = hello_elementor_child_generate_qr_code_url( $product_url, 120 );
                ?>
                <tr>
                    <td style="padding: 15px; border-bottom: 1px solid #e0e0e0; vertical-align: middle;">
                        <strong style="font-size: 14px; color: #333;">
                            <?php echo esc_html( $product->get_name() ); ?>
                        </strong>
                        <?php if ( $item->get_quantity() > 1 ) : ?>
                            <span style="color: #666; font-size: 12px;">
                                (<?php echo esc_html( sprintf( __( 'Qty: %d', 'hello-elementor-child' ), $item->get_quantity() ) ); ?>)
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 15px; border-bottom: 1px solid #e0e0e0; text-align: center; width: 140px;">
                        <img src="<?php echo esc_url( $qr_code_url ); ?>" 
                             alt="<?php echo esc_attr( sprintf( __( 'QR Code for %s', 'hello-elementor-child' ), $product->get_name() ) ); ?>" 
                             style="width: 120px; height: 120px; display: block; margin: 0 auto;" />
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}

/**
 * Add custom CSS to order emails for better QR code display
 */
add_action( 'woocommerce_email_header', 'hello_elementor_child_email_qr_styles' );
function hello_elementor_child_email_qr_styles( $email_heading ) {
    ?>
    <style type="text/css">
        .qr-code-container {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }
        .qr-code-container img {
            max-width: 150px;
            height: auto;
            margin: 10px auto;
            display: block;
        }
        @media only screen and (max-width: 600px) {
            .qr-code-container img {
                max-width: 120px;
            }
        }
    </style>
    <?php
}
