<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

/**
 * Add WooCommerce support
 */
function hello_elementor_child_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'hello_elementor_child_add_woocommerce_support' );

/**
 * Register Custom Elementor Widgets
 */
function hello_elementor_child_register_elementor_widgets( $widgets_manager ) {
    // Include widget files
    require_once( get_stylesheet_directory() . '/widgets/product-grid-widget.php' );
    require_once( get_stylesheet_directory() . '/widgets/product-slider-widget.php' );

    // Register widgets
    $widgets_manager->register( new \Hello_Elementor_Product_Grid_Widget() );
    $widgets_manager->register( new \Hello_Elementor_Product_Slider_Widget() );
}
add_action( 'elementor/widgets/register', 'hello_elementor_child_register_elementor_widgets' );

/**
 * Enqueue Swiper library for slider widget
 */
function hello_elementor_child_enqueue_swiper() {
    // Enqueue Swiper CSS
    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        [],
        '11.0.0'
    );

    // Enqueue Swiper JS
    wp_enqueue_script(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [ 'jquery' ],
        '11.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_swiper' );
add_action( 'elementor/frontend/after_enqueue_scripts', 'hello_elementor_child_enqueue_swiper' );


/**
 * Add custom widget categories
 */
function hello_elementor_child_add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'woocommerce-elements',
        [
            'title' => __( 'WooCommerce Elements', 'hello-elementor-child' ),
            'icon' => 'fa fa-shopping-cart',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'hello_elementor_child_add_elementor_widget_categories' );


/**
 * Create custom taxonomies for product organization
 */
function hello_elementor_child_create_product_taxonomies() {
    // Product Type Category (Main Categories)
    register_taxonomy( 'product_type_category', 'product', array(
        'labels' => array(
            'name'              => __( 'Product Types', 'hello-elementor-child' ),
            'singular_name'     => __( 'Product Type', 'hello-elementor-child' ),
            'search_items'      => __( 'Search Product Types', 'hello-elementor-child' ),
            'all_items'         => __( 'All Product Types', 'hello-elementor-child' ),
            'edit_item'         => __( 'Edit Product Type', 'hello-elementor-child' ),
            'update_item'       => __( 'Update Product Type', 'hello-elementor-child' ),
            'add_new_item'      => __( 'Add New Product Type', 'hello-elementor-child' ),
            'new_item_name'     => __( 'New Product Type Name', 'hello-elementor-child' ),
            'menu_name'         => __( 'Product Types', 'hello-elementor-child' ),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'product-type' ),
        'show_in_rest'      => true,
        'meta_box_cb'       => false, // Hide metabox since we'll auto-assign
    ));

    // Product Brand/Sub-type (Tags/Brands)
    register_taxonomy( 'product_brand', 'product', array(
        'labels' => array(
            'name'              => __( 'Product Brands', 'hello-elementor-child' ),
            'singular_name'     => __( 'Product Brand', 'hello-elementor-child' ),
            'search_items'      => __( 'Search Brands', 'hello-elementor-child' ),
            'all_items'         => __( 'All Brands', 'hello-elementor-child' ),
            'edit_item'         => __( 'Edit Brand', 'hello-elementor-child' ),
            'update_item'       => __( 'Update Brand', 'hello-elementor-child' ),
            'add_new_item'      => __( 'Add New Brand', 'hello-elementor-child' ),
            'new_item_name'     => __( 'New Brand Name', 'hello-elementor-child' ),
            'menu_name'         => __( 'Brands', 'hello-elementor-child' ),
        ),
        'hierarchical'      => false, // Like tags
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'brand' ),
        'show_in_rest'      => true,
        'meta_box_cb'       => false, // Hide metabox since we'll auto-assign
    ));
}
add_action( 'init', 'hello_elementor_child_create_product_taxonomies' );

/**
 * Create default taxonomy terms
 * Only runs once to avoid unnecessary database queries
 */
function hello_elementor_child_create_default_taxonomy_terms() {
    // Check if we've already created the terms
    $terms_created = get_option( 'hello_elementor_child_taxonomy_terms_created', false );
    
    if ( $terms_created ) {
        return;
    }

    // Create main product type categories
    $product_types = array(
        'livestock' => array(
            'name' => 'Livestock',
            'description' => 'Live animals including cows, oxen, and other cattle'
        ),
        'dairy-product' => array(
            'name' => 'Dairy Products',
            'description' => 'Milk-based products like butter, cheese, ghee, and yogurt'
        ),
        'animal-feed' => array(
            'name' => 'Animal Feed',
            'description' => 'Feed and nutrition products for cattle and livestock'
        )
    );

    foreach ( $product_types as $slug => $data ) {
        if ( ! term_exists( $data['name'], 'product_type_category' ) ) {
            wp_insert_term(
                $data['name'],
                'product_type_category',
                array(
                    'description' => $data['description'],
                    'slug' => $slug
                )
            );
        }
    }

    // Create livestock sub-brands
    $livestock_brands = array(
        'ox' => 'Ox',
        'dairy-cow' => 'Dairy Cow',
        'bokna' => 'Bokna',
        'holstein' => 'Holstein',
        'jersey' => 'Jersey'
    );

    // Create dairy product sub-brands
    $dairy_brands = array(
        'milk' => 'Milk',
        'butter' => 'Butter',
        'cheese' => 'Cheese',
        'cream' => 'Cream',
        'ghee' => 'Ghee'
    );

    // Create feed sub-brands
    $feed_brands = array(
        'cattle-feed' => 'Cattle Feed',
        'dairy-feed' => 'Dairy Feed',
        'calf-feed' => 'Calf Feed',
        'mixed-feed' => 'Ready Mixed',
        'silage' => 'Silage'
    );

    $all_brands = array_merge( $livestock_brands, $dairy_brands, $feed_brands );

    foreach ( $all_brands as $slug => $name ) {
        if ( ! term_exists( $name, 'product_brand' ) ) {
            wp_insert_term(
                $name,
                'product_brand',
                array(
                    'slug' => $slug
                )
            );
        }
    }

    // Mark that we've created the terms
    update_option( 'hello_elementor_child_taxonomy_terms_created', true );
}
add_action( 'init', 'hello_elementor_child_create_default_taxonomy_terms' );

/**
 * Add custom "Product Info" tab to product data (replaces Cow Info tab)
 */
function hello_elementor_child_add_product_info_tab( $tabs ) {
    // Remove the old cow info tab
    unset($tabs['cow_info']);

    $tabs['product_info'] = array(
        'label'    => __( 'Product Info', 'hello-elementor-child' ),
        'target'   => 'product_info_product_data',
        'class'    => array( 'show_if_simple', 'show_if_variable' ),
        'priority' => 21,
    );
    return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'hello_elementor_child_add_product_info_tab' );

/**
 * Add fields to the Product Info tab
 */
function hello_elementor_child_product_info_tab_content() {
    echo '<div id="product_info_product_data" class="panel woocommerce_options_panel">';

    echo '<div class="options_group">';

    // Product Type Field (Primary Selection)
    woocommerce_wp_select(
        array(
            'id' => '_product_info_type',
            'label' => __('Product Type', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select the type of product. This will show relevant fields below.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose product type', 'hello-elementor-child'),
                'livestock' => __('Livestock (Cows)', 'hello-elementor-child'),
                'dairy_product' => __('Dairy Product', 'hello-elementor-child'),
                'feed' => __('Animal Feed', 'hello-elementor-child')
            ),
            'custom_attributes' => array(
                'onchange' => 'toggleProductTypeFields(this.value)'
            )
        )
    );

    echo '</div>';

    // LIVESTOCK SECTION (Cows)
    echo '<div class="options_group livestock-fields" style="display:none;">';
    echo '<h4>' . __('Livestock Information', 'hello-elementor-child') . '</h4>';

    // Cow Type Field
    woocommerce_wp_select(
        array(
            'id' => '_cow_type',
            'label' => __('Cow Type', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select the type of cow.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose cow type', 'hello-elementor-child'),
                'ox' => __('Ox', 'hello-elementor-child'),
                'dairy_cow' => __('Dairy Cow', 'hello-elementor-child'),
                'bokna' => __('Bokna', 'hello-elementor-child')
            ),
            'custom_attributes' => array(
                'onchange' => 'toggleCowTypeFields(this.value)'
            )
        )
    );

    // Cow Breed Field
    woocommerce_wp_text_input(
        array(
            'id' => '_cow_breed',
            'label' => __('Cow Breed', 'hello-elementor-child'),
            'placeholder' => 'e.g., Holstein, Jersey, Angus',
            'desc_tip' => 'true',
            'description' => __('Enter the breed of the cow.', 'hello-elementor-child')
        )
    );

    // Cow Age Field
    woocommerce_wp_text_input(
        array(
            'id' => '_cow_age',
            'label' => __('Age', 'hello-elementor-child'),
            'placeholder' => 'e.g., 3 years, 18 months',
            'desc_tip' => 'true',
            'description' => __('Enter the age of the cow.', 'hello-elementor-child')
        )
    );

    // Cow Weight Field
    woocommerce_wp_text_input(
        array(
            'id' => '_cow_weight',
            'label' => __('Weight (kg)', 'hello-elementor-child'),
            'placeholder' => 'e.g., 500',
            'type' => 'number',
            'desc_tip' => 'true',
            'description' => __('Enter the weight of the cow in kilograms.', 'hello-elementor-child')
        )
    );

    // Cow Color Field
    woocommerce_wp_select(
        array(
            'id' => '_cow_color',
            'label' => __('Color', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select the color of the cow.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose color', 'hello-elementor-child'),
                'black' => __('Black', 'hello-elementor-child'),
                'brown' => __('Brown', 'hello-elementor-child'),
                'white' => __('White', 'hello-elementor-child'),
                'black_white' => __('Black & White', 'hello-elementor-child'),
                'brown_white' => __('Brown & White', 'hello-elementor-child'),
                'mixed' => __('Mixed Colors', 'hello-elementor-child')
            )
        )
    );

    echo '</div>';

    // DAIRY PRODUCT SECTION is now
    echo '<div class="options_group dairy-product-fields" style="display:none;">';
    echo '<h4>' . __('Dairy Product Information', 'hello-elementor-child') . '</h4>';

    // Dairy Product Type
    woocommerce_wp_select(
        array(
            'id' => '_dairy_product_type',
            'label' => __('Dairy Product Type', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select the type of dairy product.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose dairy product', 'hello-elementor-child'),
                'milk' => __('Milk', 'hello-elementor-child'),
                'butter' => __('Butter', 'hello-elementor-child'),
                'cheese' => __('Cheese', 'hello-elementor-child'),
                'yogurt' => __('Yogurt', 'hello-elementor-child'),
                'cream' => __('Cream', 'hello-elementor-child'),
                'ghee' => __('Ghee', 'hello-elementor-child')
            )
        )
    );

    // Fat Content
    woocommerce_wp_text_input(
        array(
            'id' => '_dairy_fat_content',
            'label' => __('Fat Content (%)', 'hello-elementor-child'),
            'placeholder' => 'e.g., 3.5',
            'type' => 'number',
            'custom_attributes' => array('step' => '0.1'),
            'desc_tip' => 'true',
            'description' => __('Enter the fat content percentage.', 'hello-elementor-child')
        )
    );

    // Volume/Weight per unit
    woocommerce_wp_text_input(
        array(
            'id' => '_dairy_unit_size',
            'label' => __('Unit Size', 'hello-elementor-child'),
            'placeholder' => 'e.g., 1L, 500g, 250ml',
            'desc_tip' => 'true',
            'description' => __('Enter the size per unit (L, ml, g, kg).', 'hello-elementor-child')
        )
    );

    // Expiry/Shelf Life
    woocommerce_wp_text_input(
        array(
            'id' => '_dairy_shelf_life',
            'label' => __('Shelf Life', 'hello-elementor-child'),
            'placeholder' => 'e.g., 7 days, 2 weeks, 6 months',
            'desc_tip' => 'true',
            'description' => __('Enter the shelf life duration.', 'hello-elementor-child')
        )
    );

    // Storage Requirements
    woocommerce_wp_select(
        array(
            'id' => '_dairy_storage',
            'label' => __('Storage Requirements', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select storage requirements.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose storage type', 'hello-elementor-child'),
                'refrigerated' => __('Refrigerated (2-4°C)', 'hello-elementor-child'),
                'frozen' => __('Frozen (-18°C)', 'hello-elementor-child'),
                'room_temperature' => __('Room Temperature', 'hello-elementor-child'),
                'cool_dry_place' => __('Cool Dry Place', 'hello-elementor-child')
            )
        )
    );

    // Origin/Source
    woocommerce_wp_text_input(
        array(
            'id' => '_dairy_origin',
            'label' => __('Origin/Source', 'hello-elementor-child'),
            'placeholder' => 'e.g., Local farm, Imported from...',
            'desc_tip' => 'true',
            'description' => __('Enter the origin or source of the dairy product.', 'hello-elementor-child')
        )
    );

    echo '</div>';

    // ANIMAL FEED SECTION
    echo '<div class="options_group feed-fields" style="display:none;">';
    echo '<h4>' . __('Animal Feed Information', 'hello-elementor-child') . '</h4>';

    // Feed Type
    woocommerce_wp_select(
        array(
            'id' => '_feed_type',
            'label' => __('Feed Type', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select the type of animal feed.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose feed type', 'hello-elementor-child'),
                'cattle_feed' => __('Cattle Feed', 'hello-elementor-child'),
                'dairy_feed' => __('Dairy Cow Feed', 'hello-elementor-child'),
                'calf_feed' => __('Calf Feed', 'hello-elementor-child'),
                'hay' => __('Hay', 'hello-elementor-child'),
                'silage' => __('Silage', 'hello-elementor-child'),
                'concentrate' => __('Concentrate', 'hello-elementor-child')
            )
        )
    );

    // Package Size
    woocommerce_wp_text_input(
        array(
            'id' => '_feed_package_size',
            'label' => __('Package Size', 'hello-elementor-child'),
            'placeholder' => 'e.g., 25kg, 50kg, 1 ton',
            'desc_tip' => 'true',
            'description' => __('Enter the package size.', 'hello-elementor-child')
        )
    );

    // Protein Content
    woocommerce_wp_text_input(
        array(
            'id' => '_feed_protein_content',
            'label' => __('Protein Content (%)', 'hello-elementor-child'),
            'placeholder' => 'e.g., 18',
            'type' => 'number',
            'custom_attributes' => array('step' => '0.1'),
            'desc_tip' => 'true',
            'description' => __('Enter the protein content percentage.', 'hello-elementor-child')
        )
    );

    // Suitable For
    woocommerce_wp_select(
        array(
            'id' => '_feed_suitable_for',
            'label' => __('Suitable For', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select what animals this feed is suitable for.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose animal type', 'hello-elementor-child'),
                'all_cattle' => __('All Cattle', 'hello-elementor-child'),
                'dairy_cows' => __('Dairy Cows', 'hello-elementor-child'),
                'beef_cattle' => __('Beef Cattle', 'hello-elementor-child'),
                'calves' => __('Calves', 'hello-elementor-child'),
                'oxen' => __('Oxen', 'hello-elementor-child')
            )
        )
    );

    // Nutritional Information
    woocommerce_wp_textarea_input(
        array(
            'id' => '_feed_nutrition_info',
            'label' => __('Nutritional Information', 'hello-elementor-child'),
            'placeholder' => 'e.g., Crude Protein: 18%, Crude Fat: 3%, Fiber: 12%...',
            'desc_tip' => 'true',
            'description' => __('Enter detailed nutritional information.', 'hello-elementor-child')
        )
    );

    echo '</div>';

    // Health Information for Livestock Only
    echo '<div class="options_group health-fields livestock-only" style="display:none;">';
    echo '<h4>' . __('Health Information (Livestock Only)', 'hello-elementor-child') . '</h4>';

    // Health Status Field
    woocommerce_wp_select(
        array(
            'id' => '_cow_health_status',
            'label' => __('Health Status', 'hello-elementor-child'),
            'desc_tip' => 'true',
            'description' => __('Select the health status of the cow.', 'hello-elementor-child'),
            'options' => array(
                '' => __('Choose status', 'hello-elementor-child'),
                'excellent' => __('Excellent', 'hello-elementor-child'),
                'good' => __('Good', 'hello-elementor-child'),
                'fair' => __('Fair', 'hello-elementor-child'),
                'needs_attention' => __('Needs Attention', 'hello-elementor-child')
            )
        )
    );

    // Vaccination Status Field
    woocommerce_wp_checkbox(
        array(
            'id' => '_cow_vaccinated',
            'label' => __('Vaccinated', 'hello-elementor-child'),
            'description' => __('Check if the cow is up to date with vaccinations.', 'hello-elementor-child')
        )
    );

    echo '</div>';

    // Common Notes Field
    echo '<div class="options_group">';
    woocommerce_wp_textarea_input(
        array(
            'id' => '_product_notes',
            'label' => __('Additional Notes', 'hello-elementor-child'),
            'placeholder' => 'Any additional information about the product...',
            'desc_tip' => 'true',
            'description' => __('Enter any additional notes or special information.', 'hello-elementor-child')
        )
    );
    echo '</div>';

    echo '</div>';

    // Add JavaScript for dynamic field showing/hiding
    ?>
    <script type="text/javascript">
    function toggleProductTypeFields(productType) {
        // Hide all type-specific fields
        jQuery('.livestock-fields, .dairy-product-fields, .feed-fields, .livestock-only').hide();

        // Show fields based on selection
        if (productType === 'livestock') {
            jQuery('.livestock-fields, .livestock-only').show();
        } else if (productType === 'dairy_product') {
            jQuery('.dairy-product-fields').show();
        } else if (productType === 'feed') {
            jQuery('.feed-fields').show();
        }
    }

    function toggleCowTypeFields(cowType) {
        // This function can be expanded later for cow-specific fields
        console.log('Cow type selected: ' + cowType);
    }

    // Initialize on page load
    jQuery(document).ready(function($) {
        var selectedType = $('#_product_info_type').val();
        if (selectedType) {
            toggleProductTypeFields(selectedType);
        }
    });
    </script>
    <?php
}
add_action( 'woocommerce_product_data_panels', 'hello_elementor_child_product_info_tab_content' );

/**
 * Save Product Info tab fields
 * 
 * @param int $post_id The product post ID
 */
function hello_elementor_child_save_product_info_fields( $post_id ) {
    // Security checks
    if ( ! current_user_can( 'edit_product', $post_id ) ) {
        return;
    }

    // Verify nonce if available (WooCommerce handles this, but good practice)
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Define all fields with their sanitization callbacks
    $fields = array(
        // Product Type
        '_product_info_type' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'livestock', 'dairy_product', 'feed' )
        ),
        
        // Livestock fields
        '_cow_type' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'ox', 'dairy_cow', 'bokna' )
        ),
        '_cow_breed' => 'sanitize_text_field',
        '_cow_age' => 'sanitize_text_field',
        '_cow_weight' => 'absint',
        '_cow_color' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'black', 'brown', 'white', 'black_white', 'brown_white', 'mixed' )
        ),
        '_cow_health_status' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'excellent', 'good', 'fair', 'needs_attention' )
        ),
        
        // Dairy product fields
        '_dairy_product_type' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'milk', 'butter', 'cheese', 'yogurt', 'cream', 'ghee' )
        ),
        '_dairy_fat_content' => 'sanitize_text_field',
        '_dairy_unit_size' => 'sanitize_text_field',
        '_dairy_shelf_life' => 'sanitize_text_field',
        '_dairy_storage' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'refrigerated', 'frozen', 'room_temperature', 'cool_dry_place' )
        ),
        '_dairy_origin' => 'sanitize_text_field',
        
        // Feed fields
        '_feed_type' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'cattle_feed', 'dairy_feed', 'calf_feed', 'hay', 'silage', 'concentrate' )
        ),
        '_feed_package_size' => 'sanitize_text_field',
        '_feed_protein_content' => 'sanitize_text_field',
        '_feed_suitable_for' => array(
            'sanitize' => 'sanitize_text_field',
            'validate' => array( '', 'all_cattle', 'dairy_cows', 'beef_cattle', 'calves', 'oxen' )
        ),
        '_feed_nutrition_info' => 'sanitize_textarea_field',
        
        // Common fields
        '_product_notes' => 'sanitize_textarea_field',
    );

    // Process each field
    foreach ( $fields as $field_key => $sanitize_config ) {
        if ( ! isset( $_POST[ $field_key ] ) ) {
            continue;
        }

        $value = $_POST[ $field_key ];
        
        // Handle array configuration (with validation)
        if ( is_array( $sanitize_config ) ) {
            $sanitize_callback = $sanitize_config['sanitize'];
            $sanitized_value = call_user_func( $sanitize_callback, $value );
            
            // Validate against allowed values if specified
            if ( isset( $sanitize_config['validate'] ) && ! in_array( $sanitized_value, $sanitize_config['validate'], true ) ) {
                continue; // Skip invalid values
            }
        } else {
            // Simple sanitization callback
            $sanitized_value = call_user_func( $sanitize_config, $value );
        }

        // Only update if value is not empty
        if ( ! empty( $sanitized_value ) || $sanitized_value === '0' ) {
            update_post_meta( $post_id, $field_key, $sanitized_value );
        } else {
            delete_post_meta( $post_id, $field_key );
        }
    }

    // Handle checkbox field separately
    $cow_vaccinated = isset( $_POST['_cow_vaccinated'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_cow_vaccinated', $cow_vaccinated );

    // Auto-assign taxonomies based on product info
    hello_elementor_child_auto_assign_taxonomies( $post_id );
}
add_action( 'woocommerce_process_product_meta', 'hello_elementor_child_save_product_info_fields' );

/**
 * Automatically assign taxonomies based on product info fields
 * 
 * @param int $post_id The product post ID
 */
function hello_elementor_child_auto_assign_taxonomies( $post_id ) {
    $product_info_type = get_post_meta( $post_id, '_product_info_type', true );

    if ( empty( $product_info_type ) ) {
        return;
    }

    // Category mapping configuration
    $category_mapping = array(
        'livestock'     => 'livestock',
        'dairy_product' => 'dairy-product',
        'feed'          => 'animal-feed'
    );

    $wc_category_mapping = array(
        'livestock'     => 'Livestock',
        'dairy_product' => 'Dairy Products',
        'feed'          => 'Animal Feed'
    );

    // Assign custom product type category
    if ( isset( $category_mapping[ $product_info_type ] ) ) {
        $category_slug = $category_mapping[ $product_info_type ];
        $category_term = get_term_by( 'slug', $category_slug, 'product_type_category' );

        if ( $category_term ) {
            wp_set_object_terms( $post_id, $category_term->term_id, 'product_type_category', false );
        }
    }

    // Assign WooCommerce product category
    if ( isset( $wc_category_mapping[ $product_info_type ] ) ) {
        $category_name = $wc_category_mapping[ $product_info_type ];
        $wc_category = get_term_by( 'name', $category_name, 'product_cat' );

        if ( ! $wc_category ) {
            // Create category if it doesn't exist
            $category_data = wp_insert_term(
                $category_name,
                'product_cat',
                array(
                    'description' => sprintf( __( 'Products in the %s category', 'hello-elementor-child' ), $category_name ),
                    'slug'        => sanitize_title( $category_name )
                )
            );

            if ( ! is_wp_error( $category_data ) ) {
                $wc_category_id = $category_data['term_id'];
            }
        } else {
            $wc_category_id = $wc_category->term_id;
        }

        if ( isset( $wc_category_id ) ) {
            wp_set_object_terms( $post_id, $wc_category_id, 'product_cat', false );
        }
    }

    // Assign brands based on product type
    $brands_to_assign = hello_elementor_child_get_brands_for_product( $post_id, $product_info_type );

    if ( ! empty( $brands_to_assign ) ) {
        $brand_term_ids = array();

        foreach ( $brands_to_assign as $brand_slug ) {
            $brand_term = get_term_by( 'slug', $brand_slug, 'product_brand' );
            
            if ( $brand_term ) {
                $brand_term_ids[] = $brand_term->term_id;
            } else {
                // Create the brand if it doesn't exist
                $brand_name = ucwords( str_replace( array( '-', '_' ), ' ', $brand_slug ) );
                $new_term = wp_insert_term( 
                    $brand_name, 
                    'product_brand', 
                    array( 'slug' => $brand_slug ) 
                );
                
                if ( ! is_wp_error( $new_term ) ) {
                    $brand_term_ids[] = $new_term['term_id'];
                }
            }
        }

        if ( ! empty( $brand_term_ids ) ) {
            wp_set_object_terms( $post_id, $brand_term_ids, 'product_brand', false );
        }
    }
}

/**
 * Get brands to assign based on product type and meta data
 * 
 * @param int    $post_id           The product post ID
 * @param string $product_info_type The product type
 * @return array Array of brand slugs to assign
 */
function hello_elementor_child_get_brands_for_product( $post_id, $product_info_type ) {
    $brands = array();

    switch ( $product_info_type ) {
        case 'livestock':
            $cow_type = get_post_meta( $post_id, '_cow_type', true );
            $cow_breed = get_post_meta( $post_id, '_cow_breed', true );

            if ( ! empty( $cow_type ) ) {
                $cow_type_mapping = array(
                    'ox'        => 'ox',
                    'dairy_cow' => 'dairy-cow',
                    'bokna'     => 'bokna'
                );

                if ( isset( $cow_type_mapping[ $cow_type ] ) ) {
                    $brands[] = $cow_type_mapping[ $cow_type ];
                }
            }

            if ( ! empty( $cow_breed ) ) {
                $brands[] = sanitize_title( strtolower( $cow_breed ) );
            }
            break;

        case 'dairy_product':
            $dairy_product_type = get_post_meta( $post_id, '_dairy_product_type', true );

            if ( ! empty( $dairy_product_type ) ) {
                $dairy_mapping = array(
                    'milk'   => 'milk',
                    'butter' => 'butter',
                    'cheese' => 'cheese',
                    'cream'  => 'cream',
                    'ghee'   => 'ghee'
                );

                if ( isset( $dairy_mapping[ $dairy_product_type ] ) ) {
                    $brands[] = $dairy_mapping[ $dairy_product_type ];
                }
            }
            break;

        case 'feed':
            $feed_type = get_post_meta( $post_id, '_feed_type', true );

            if ( ! empty( $feed_type ) ) {
                $feed_mapping = array(
                    'cattle_feed' => 'cattle-feed',
                    'dairy_feed'  => 'dairy-feed',
                    'calf_feed'   => 'calf-feed',
                    'silage'      => 'silage'
                );

                if ( isset( $feed_mapping[ $feed_type ] ) ) {
                    $brands[] = $feed_mapping[ $feed_type ];
                }
            }
            break;
    }

    return $brands;
}

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
    $product_url = get_permalink( $product->get_id() );

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

/**
 * Get formatted product information for display
 * 
 * @param int $product_id Product ID (optional, uses current product if not provided)
 * @return array Array of formatted product information
 */
function hello_elementor_child_get_product_info( $product_id = null ) {
    if ( ! $product_id ) {
        global $product;
        if ( ! $product ) {
            return array();
        }
        $product_id = $product->get_id();
    }

    $product_type = get_post_meta( $product_id, '_product_info_type', true );
    $info = array(
        'type' => $product_type,
        'fields' => array()
    );

    if ( empty( $product_type ) ) {
        return $info;
    }

    // Get type-specific fields
    switch ( $product_type ) {
        case 'livestock':
            $fields = array(
                'cow_type' => __( 'Cow Type', 'hello-elementor-child' ),
                'cow_breed' => __( 'Breed', 'hello-elementor-child' ),
                'cow_age' => __( 'Age', 'hello-elementor-child' ),
                'cow_weight' => __( 'Weight', 'hello-elementor-child' ),
                'cow_color' => __( 'Color', 'hello-elementor-child' ),
                'cow_health_status' => __( 'Health Status', 'hello-elementor-child' ),
                'cow_vaccinated' => __( 'Vaccinated', 'hello-elementor-child' ),
            );
            break;

        case 'dairy_product':
            $fields = array(
                'dairy_product_type' => __( 'Product Type', 'hello-elementor-child' ),
                'dairy_fat_content' => __( 'Fat Content', 'hello-elementor-child' ),
                'dairy_unit_size' => __( 'Unit Size', 'hello-elementor-child' ),
                'dairy_shelf_life' => __( 'Shelf Life', 'hello-elementor-child' ),
                'dairy_storage' => __( 'Storage', 'hello-elementor-child' ),
                'dairy_origin' => __( 'Origin', 'hello-elementor-child' ),
            );
            break;

        case 'feed':
            $fields = array(
                'feed_type' => __( 'Feed Type', 'hello-elementor-child' ),
                'feed_package_size' => __( 'Package Size', 'hello-elementor-child' ),
                'feed_protein_content' => __( 'Protein Content', 'hello-elementor-child' ),
                'feed_suitable_for' => __( 'Suitable For', 'hello-elementor-child' ),
                'feed_nutrition_info' => __( 'Nutritional Info', 'hello-elementor-child' ),
            );
            break;

        default:
            $fields = array();
    }

    // Get field values
    foreach ( $fields as $key => $label ) {
        $value = get_post_meta( $product_id, '_' . $key, true );
        if ( ! empty( $value ) ) {
            // Format vaccinated field
            if ( $key === 'cow_vaccinated' ) {
                $value = ( $value === 'yes' ) ? __( 'Yes', 'hello-elementor-child' ) : __( 'No', 'hello-elementor-child' );
            }
            $info['fields'][ $label ] = $value;
        }
    }

    // Add notes if available
    $notes = get_post_meta( $product_id, '_product_notes', true );
    if ( ! empty( $notes ) ) {
        $info['notes'] = $notes;
    }

    return $info;
}

/**
 * Enqueue custom CSS file
 */
function hello_elementor_child_enqueue_custom_styles() {
    wp_enqueue_style(
        'hello-elementor-child-custom-styles',
        get_stylesheet_directory_uri() . '/inc/custom-styles.css',
        [],
        HELLO_ELEMENTOR_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_custom_styles' );
add_action( 'admin_enqueue_scripts', 'hello_elementor_child_enqueue_custom_styles' );

/**
 * Enqueue Cart Drawer Scripts and Styles
 */
function hello_elementor_child_enqueue_cart_drawer_assets() {
    // Enqueue cart drawer JavaScript
    wp_enqueue_script(
        'cart-drawer-js',
        get_stylesheet_directory_uri() . '/js/cart-drawer.js',
        [ 'jquery' ],
        HELLO_ELEMENTOR_CHILD_VERSION,
        true
    );

    // Localize script for AJAX
    wp_localize_script( 'cart-drawer-js', 'cartDrawerAjax', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'cart_drawer_nonce' ),
        'checkout_url' => wc_get_checkout_url(),
        'cart_url' => wc_get_cart_url(),
    ));
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_cart_drawer_assets' );

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

