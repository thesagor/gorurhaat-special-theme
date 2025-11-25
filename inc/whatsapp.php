<?php
/**
 * Add WhatsApp button to single product page
 */
function hello_elementor_child_add_whatsapp_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    // Get WhatsApp number from settings or use default
    $whatsapp_number = get_option( 'whatsapp_contact_number', '+8801780884888' );
    
    // Clean phone number (remove spaces, dashes, etc.)
    $whatsapp_number = preg_replace( '/[^0-9+]/', '', $whatsapp_number );

    // Validate phone number
    if ( empty( $whatsapp_number ) ) {
        return;
    }

    // Get product details
    $product_name = $product->get_name();
    $product_id = $product->get_id();
    
    // Use WordPress shortlink instead of full permalink
    $product_url = wp_get_shortlink( $product_id );
    
    // If shortlink is not available, use regular permalink
    if ( empty( $product_url ) ) {
        $product_url = get_permalink( $product_id );
    }

    // Create WhatsApp message with proper formatting
    $message = sprintf(
        'Assalamu alaikum, I want to talk about "%s" which link is %s',
        $product_name,
        $product_url
    );

    // URL encode the message
    $encoded_message = rawurlencode( $message );

    // Generate WhatsApp URL
    $whatsapp_url = 'https://wa.me/' . $whatsapp_number . '?text=' . $encoded_message;

    // Output the WhatsApp button with improved styling
    ?>
    <div class="whatsapp-floating-button">
        <a href="<?php echo esc_url( $whatsapp_url ); ?>" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="whatsapp-button-circle"
           aria-label="<?php esc_attr_e( 'Contact us on WhatsApp', 'hello-elementor-child' ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.785"/>
            </svg>
        </a>
    </div>
    <?php
}

// Add WhatsApp button after the add to cart button
add_action( 'woocommerce_single_product_summary', 'hello_elementor_child_add_whatsapp_button', 35 );

/**
 * Add WhatsApp settings to admin
 */
function hello_elementor_child_add_whatsapp_settings() {
    add_settings_section(
        'whatsapp_settings_section',
        'WhatsApp Settings',
        'hello_elementor_child_whatsapp_settings_callback',
        'general'
    );

    add_settings_field(
        'whatsapp_contact_number',
        'Default WhatsApp Number',
        'hello_elementor_child_whatsapp_number_callback',
        'general',
        'whatsapp_settings_section'
    );

    register_setting( 'general', 'whatsapp_contact_number' );
}
add_action( 'admin_init', 'hello_elementor_child_add_whatsapp_settings' );

function hello_elementor_child_whatsapp_settings_callback() {
    echo '<p>Configure WhatsApp integration settings for product inquiries.</p>';
}

function hello_elementor_child_whatsapp_number_callback() {
    $value = get_option( 'whatsapp_contact_number', '' );
    echo '<input type="text" id="whatsapp_contact_number" name="whatsapp_contact_number" value="' . esc_attr( $value ) . '" placeholder="e.g., +1234567890" />';
    echo '<p class="description">Enter the default WhatsApp number (with country code) for product inquiries. If not set, the product\'s contact number will be used.</p>';
}
