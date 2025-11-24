<?php
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

    // Array to store all category IDs to assign
    $category_ids = array();

    // Assign WooCommerce product category (parent)
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
            $category_ids[] = $wc_category_id;
        }
    }

    // Create and assign subcategories based on product type
    $subcategory_name = '';
    $subcategory_slug = '';
    
    switch ( $product_info_type ) {
        case 'livestock':
            $cow_type = get_post_meta( $post_id, '_cow_type', true );
            if ( ! empty( $cow_type ) ) {
                $cow_type_names = array(
                    'ox'        => 'Ox',
                    'dairy_cow' => 'Dairy Cow',
                    'bokna'     => 'Bokna'
                );
                if ( isset( $cow_type_names[ $cow_type ] ) ) {
                    $subcategory_name = $cow_type_names[ $cow_type ];
                    $subcategory_slug = sanitize_title( $subcategory_name );
                }
            }
            break;

        case 'dairy_product':
            $dairy_type = get_post_meta( $post_id, '_dairy_product_type', true );
            if ( ! empty( $dairy_type ) ) {
                $dairy_type_names = array(
                    'milk'   => 'Milk',
                    'butter' => 'Butter',
                    'cheese' => 'Cheese',
                    'yogurt' => 'Yogurt',
                    'cream'  => 'Cream',
                    'ghee'   => 'Ghee'
                );
                if ( isset( $dairy_type_names[ $dairy_type ] ) ) {
                    $subcategory_name = $dairy_type_names[ $dairy_type ];
                    $subcategory_slug = sanitize_title( $subcategory_name );
                }
            }
            break;

        case 'feed':
            $feed_type = get_post_meta( $post_id, '_feed_type', true );
            if ( ! empty( $feed_type ) ) {
                $feed_type_names = array(
                    'cattle_feed' => 'Cattle Feed',
                    'dairy_feed'  => 'Dairy Cow Feed',
                    'calf_feed'   => 'Calf Feed',
                    'hay'         => 'Hay',
                    'silage'      => 'Silage',
                    'concentrate' => 'Concentrate'
                );
                if ( isset( $feed_type_names[ $feed_type ] ) ) {
                    $subcategory_name = $feed_type_names[ $feed_type ];
                    $subcategory_slug = sanitize_title( $subcategory_name );
                }
            }
            break;
    }

    // Create subcategory if we have one
    if ( ! empty( $subcategory_name ) && isset( $wc_category_id ) ) {
        $subcategory = get_term_by( 'slug', $subcategory_slug, 'product_cat' );
        
        if ( ! $subcategory ) {
            // Create subcategory under parent
            $subcategory_data = wp_insert_term(
                $subcategory_name,
                'product_cat',
                array(
                    'description' => sprintf( __( '%s products', 'hello-elementor-child' ), $subcategory_name ),
                    'slug'        => $subcategory_slug,
                    'parent'      => $wc_category_id
                )
            );

            if ( ! is_wp_error( $subcategory_data ) ) {
                $category_ids[] = $subcategory_data['term_id'];
            }
        } else {
            $category_ids[] = $subcategory->term_id;
        }
    }

    // Assign all categories to the product
    if ( ! empty( $category_ids ) ) {
        wp_set_object_terms( $post_id, $category_ids, 'product_cat', false );
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
