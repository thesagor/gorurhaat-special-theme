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
 */
function hello_elementor_child_create_default_taxonomy_terms() {
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

    foreach( $product_types as $slug => $data ) {
        if( !term_exists( $data['name'], 'product_type_category' ) ) {
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
        'jersey' => 'Jersey',
        'angus' => 'Angus'
    );

    // Create dairy product sub-brands
    $dairy_brands = array(
        'milk' => 'Milk',
        'butter' => 'Butter',
        'cheese' => 'Cheese',
        'yogurt' => 'Yogurt',
        'cream' => 'Cream',
        'ghee' => 'Ghee'
    );

    // Create feed sub-brands
    $feed_brands = array(
        'cattle-feed' => 'Cattle Feed',
        'dairy-feed' => 'Dairy Feed',
        'calf-feed' => 'Calf Feed',
        'hay' => 'Hay',
        'silage' => 'Silage',
        'concentrate' => 'Concentrate'
    );

    $all_brands = array_merge( $livestock_brands, $dairy_brands, $feed_brands );

    foreach( $all_brands as $slug => $name ) {
        if( !term_exists( $name, 'product_brand' ) ) {
            wp_insert_term(
                $name,
                'product_brand',
                array(
                    'slug' => $slug
                )
            );
        }
    }
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

    // DAIRY PRODUCT SECTION
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
 */
function hello_elementor_child_save_product_info_fields( $post_id ) {
    // Product Type
    $product_info_type = $_POST['_product_info_type'];
    if( !empty( $product_info_type ) ) {
        update_post_meta( $post_id, '_product_info_type', esc_attr( $product_info_type ) );
    }

    // Cow Type
    $cow_type = $_POST['_cow_type'];
    if( !empty( $cow_type ) ) {
        update_post_meta( $post_id, '_cow_type', esc_attr( $cow_type ) );
    }

    // Cow Breed
    $cow_breed = $_POST['_cow_breed'];
    if( !empty( $cow_breed ) ) {
        update_post_meta( $post_id, '_cow_breed', esc_attr( $cow_breed ) );
    }

    // Cow Age
    $cow_age = $_POST['_cow_age'];
    if( !empty( $cow_age ) ) {
        update_post_meta( $post_id, '_cow_age', esc_attr( $cow_age ) );
    }

    // Cow Weight
    $cow_weight = $_POST['_cow_weight'];
    if( !empty( $cow_weight ) ) {
        update_post_meta( $post_id, '_cow_weight', esc_attr( $cow_weight ) );
    }

    // Cow Color
    $cow_color = $_POST['_cow_color'];
    if( !empty( $cow_color ) ) {
        update_post_meta( $post_id, '_cow_color', esc_attr( $cow_color ) );
    }

    // Dairy Product Type
    $dairy_product_type = $_POST['_dairy_product_type'];
    if( !empty( $dairy_product_type ) ) {
        update_post_meta( $post_id, '_dairy_product_type', esc_attr( $dairy_product_type ) );
    }

    // Fat Content
    $dairy_fat_content = $_POST['_dairy_fat_content'];
    if( !empty( $dairy_fat_content ) ) {
        update_post_meta( $post_id, '_dairy_fat_content', esc_attr( $dairy_fat_content ) );
    }

    // Unit Size
    $dairy_unit_size = $_POST['_dairy_unit_size'];
    if( !empty( $dairy_unit_size ) ) {
        update_post_meta( $post_id, '_dairy_unit_size', esc_attr( $dairy_unit_size ) );
    }

    // Shelf Life
    $dairy_shelf_life = $_POST['_dairy_shelf_life'];
    if( !empty( $dairy_shelf_life ) ) {
        update_post_meta( $post_id, '_dairy_shelf_life', esc_attr( $dairy_shelf_life ) );
    }

    // Storage Requirements
    $dairy_storage = $_POST['_dairy_storage'];
    if( !empty( $dairy_storage ) ) {
        update_post_meta( $post_id, '_dairy_storage', esc_attr( $dairy_storage ) );
    }

    // Origin/Source
    $dairy_origin = $_POST['_dairy_origin'];
    if( !empty( $dairy_origin ) ) {
        update_post_meta( $post_id, '_dairy_origin', esc_attr( $dairy_origin ) );
    }

    // Feed Type
    $feed_type = $_POST['_feed_type'];
    if( !empty( $feed_type ) ) {
        update_post_meta( $post_id, '_feed_type', esc_attr( $feed_type ) );
    }

    // Package Size
    $feed_package_size = $_POST['_feed_package_size'];
    if( !empty( $feed_package_size ) ) {
        update_post_meta( $post_id, '_feed_package_size', esc_attr( $feed_package_size ) );
    }

    // Protein Content
    $feed_protein_content = $_POST['_feed_protein_content'];
    if( !empty( $feed_protein_content ) ) {
        update_post_meta( $post_id, '_feed_protein_content', esc_attr( $feed_protein_content ) );
    }

    // Suitable For
    $feed_suitable_for = $_POST['_feed_suitable_for'];
    if( !empty( $feed_suitable_for ) ) {
        update_post_meta( $post_id, '_feed_suitable_for', esc_attr( $feed_suitable_for ) );
    }

    // Nutritional Information
    $feed_nutrition_info = $_POST['_feed_nutrition_info'];
    if( !empty( $feed_nutrition_info ) ) {
        update_post_meta( $post_id, '_feed_nutrition_info', esc_attr( $feed_nutrition_info ) );
    }

    // Health Status
    $cow_health_status = $_POST['_cow_health_status'];
    if( !empty( $cow_health_status ) ) {
        update_post_meta( $post_id, '_cow_health_status', esc_attr( $cow_health_status ) );
    }

    // Vaccination Status
    $cow_vaccinated = isset( $_POST['_cow_vaccinated'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_cow_vaccinated', $cow_vaccinated );

    // Additional Notes
    $product_notes = $_POST['_product_notes'];
    if( !empty( $product_notes ) ) {
        update_post_meta( $post_id, '_product_notes', esc_attr( $product_notes ) );
    }

    // Auto-assign taxonomies based on product info
    hello_elementor_child_auto_assign_taxonomies( $post_id );
}
add_action( 'woocommerce_process_product_meta', 'hello_elementor_child_save_product_info_fields' );

/**
 * Automatically assign taxonomies based on product info fields
 */
function hello_elementor_child_auto_assign_taxonomies( $post_id ) {
    $product_info_type = get_post_meta( $post_id, '_product_info_type', true );

    // Assign main product type category to custom taxonomy
    if( !empty( $product_info_type ) ) {
        $category_mapping = array(
            'livestock' => 'livestock',
            'dairy_product' => 'dairy-product',
            'feed' => 'animal-feed'
        );

        if( isset( $category_mapping[ $product_info_type ] ) ) {
            $category_slug = $category_mapping[ $product_info_type ];
            $category_term = get_term_by( 'slug', $category_slug, 'product_type_category' );

            if( $category_term ) {
                wp_set_object_terms( $post_id, $category_term->term_id, 'product_type_category', false );
            }
        }
    }

    // Also assign to standard WooCommerce product categories
    if( !empty( $product_info_type ) ) {
        $wc_category_mapping = array(
            'livestock' => 'Livestock',
            'dairy_product' => 'Dairy Products',
            'feed' => 'Animal Feed'
        );

        if( isset( $wc_category_mapping[ $product_info_type ] ) ) {
            $category_name = $wc_category_mapping[ $product_info_type ];

            // Check if WooCommerce category exists, if not create it
            $wc_category = get_term_by( 'name', $category_name, 'product_cat' );

            if( !$wc_category ) {
                $category_data = wp_insert_term(
                    $category_name,
                    'product_cat',
                    array(
                        'description' => sprintf( __('Products in the %s category', 'hello-elementor-child'), $category_name ),
                        'slug' => sanitize_title( $category_name )
                    )
                );

                if( !is_wp_error( $category_data ) ) {
                    $wc_category_id = $category_data['term_id'];
                }
            } else {
                $wc_category_id = $wc_category->term_id;
            }

            // Assign to WooCommerce product category
            if( isset( $wc_category_id ) ) {
                wp_set_object_terms( $post_id, $wc_category_id, 'product_cat', false );
            }
        }
    }

    // Assign brands based on specific product types
    $brands_to_assign = array();

    if( $product_info_type === 'livestock' ) {
        // Get cow type and breed for livestock
        $cow_type = get_post_meta( $post_id, '_cow_type', true );
        $cow_breed = get_post_meta( $post_id, '_cow_breed', true );

        if( !empty( $cow_type ) ) {
            $cow_type_mapping = array(
                'ox' => 'ox',
                'dairy_cow' => 'dairy-cow',
                'bokna' => 'bokna'
            );

            if( isset( $cow_type_mapping[ $cow_type ] ) ) {
                $brands_to_assign[] = $cow_type_mapping[ $cow_type ];
            }
        }

        if( !empty( $cow_breed ) ) {
            $breed_slug = sanitize_title( strtolower( $cow_breed ) );
            $brands_to_assign[] = $breed_slug;
        }

    } elseif( $product_info_type === 'dairy_product' ) {
        // Get dairy product type
        $dairy_product_type = get_post_meta( $post_id, '_dairy_product_type', true );

        if( !empty( $dairy_product_type ) ) {
            $dairy_mapping = array(
                'milk' => 'milk',
                'butter' => 'butter',
                'cheese' => 'cheese',
                'yogurt' => 'yogurt',
                'cream' => 'cream',
                'ghee' => 'ghee'
            );

            if( isset( $dairy_mapping[ $dairy_product_type ] ) ) {
                $brands_to_assign[] = $dairy_mapping[ $dairy_product_type ];
            }
        }

    } elseif( $product_info_type === 'feed' ) {
        // Get feed type
        $feed_type = get_post_meta( $post_id, '_feed_type', true );

        if( !empty( $feed_type ) ) {
            $feed_mapping = array(
                'cattle_feed' => 'cattle-feed',
                'dairy_feed' => 'dairy-feed',
                'calf_feed' => 'calf-feed',
                'hay' => 'hay',
                'silage' => 'silage',
                'concentrate' => 'concentrate'
            );

            if( isset( $feed_mapping[ $feed_type ] ) ) {
                $brands_to_assign[] = $feed_mapping[ $feed_type ];
            }
        }
    }

    // Assign brands/sub-types
    if( !empty( $brands_to_assign ) ) {
        $brand_term_ids = array();

        foreach( $brands_to_assign as $brand_slug ) {
            $brand_term = get_term_by( 'slug', $brand_slug, 'product_brand' );
            if( $brand_term ) {
                $brand_term_ids[] = $brand_term->term_id;
            } else {
                // Create the brand if it doesn't exist
                $brand_name = ucwords( str_replace( '-', ' ', $brand_slug ) );
                $new_term = wp_insert_term( $brand_name, 'product_brand', array( 'slug' => $brand_slug ) );
                if( !is_wp_error( $new_term ) ) {
                    $brand_term_ids[] = $new_term['term_id'];
                }
            }
        }

        if( !empty( $brand_term_ids ) ) {
            wp_set_object_terms( $post_id, $brand_term_ids, 'product_brand', false );
        }
    }
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
 * Add WhatsApp button to single product page
 */
function hello_elementor_child_add_whatsapp_button() {
    global $product;

    if ( ! $product ) {
        return;
    }

    // Get product details
    $product_name = $product->get_name();
    $product_url = get_permalink( $product->get_id() );

    // Create WhatsApp message with your specified format
    $message = 'Assalamu alaikum, i want to talk about "' . $product_name . '" which link is ' . $product_url;

    // URL encode the message
    $encoded_message = urlencode( $message );

    // Use your specific WhatsApp number
    $whatsapp_number = "+8801780884888";

    // Clean phone number (remove spaces, dashes, etc.)
    $whatsapp_number = preg_replace( '/[^0-9+]/', '', $whatsapp_number );

    // Generate WhatsApp URL
    $whatsapp_url = "https://wa.me/" . $whatsapp_number . "?text=" . $encoded_message;

    // Output the WhatsApp button
    echo '<div class="whatsapp-floating-button">';
    echo '<a href="' . esc_url( $whatsapp_url ) . '" target="_blank" class="whatsapp-button-circle">';
    echo '<svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">';
    echo '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.785"/>';
    echo '</svg>';
    echo '</a>';
    echo '</div>';
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
