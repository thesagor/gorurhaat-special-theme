<?php
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
 * Create WooCommerce Product Categories with Subcategories
 * This ensures categories are available in Elementor widget dropdowns
 */
function hello_elementor_child_create_woocommerce_categories() {
    // Check if we've already created the WooCommerce categories
    $wc_cats_created = get_option( 'hello_elementor_child_wc_categories_created', false );
    
    if ( $wc_cats_created ) {
        return;
    }

    // Define parent categories with their subcategories
    $category_structure = array(
        'Livestock' => array(
            'slug' => 'livestock',
            'description' => 'Live animals including cows, oxen, and other cattle',
            'subcategories' => array(
                'Ox' => array(
                    'slug' => 'ox',
                    'description' => 'Ox products'
                ),
                'Dairy Cow' => array(
                    'slug' => 'dairy-cow',
                    'description' => 'Dairy cow products'
                ),
                'Bokna' => array(
                    'slug' => 'bokna',
                    'description' => 'Bokna cattle products'
                ),
            )
        ),
        'Dairy Products' => array(
            'slug' => 'dairy-products',
            'description' => 'Milk-based products like butter, cheese, ghee, and yogurt',
            'subcategories' => array(
                'Milk' => array(
                    'slug' => 'milk',
                    'description' => 'Fresh milk products'
                ),
                'Butter' => array(
                    'slug' => 'butter',
                    'description' => 'Butter products'
                ),
                'Cheese' => array(
                    'slug' => 'cheese',
                    'description' => 'Cheese products'
                ),
                'Yogurt' => array(
                    'slug' => 'yogurt',
                    'description' => 'Yogurt products'
                ),
                'Cream' => array(
                    'slug' => 'cream',
                    'description' => 'Cream products'
                ),
                'Ghee' => array(
                    'slug' => 'ghee',
                    'description' => 'Ghee products'
                ),
            )
        ),
        'Animal Feed' => array(
            'slug' => 'animal-feed',
            'description' => 'Feed and nutrition products for cattle and livestock',
            'subcategories' => array(
                'Cattle Feed' => array(
                    'slug' => 'cattle-feed',
                    'description' => 'General cattle feed'
                ),
                'Dairy Cow Feed' => array(
                    'slug' => 'dairy-cow-feed',
                    'description' => 'Specialized feed for dairy cows'
                ),
                'Calf Feed' => array(
                    'slug' => 'calf-feed',
                    'description' => 'Feed for calves'
                ),
                'Hay' => array(
                    'slug' => 'hay',
                    'description' => 'Hay products'
                ),
                'Silage' => array(
                    'slug' => 'silage',
                    'description' => 'Silage products'
                ),
                'Concentrate' => array(
                    'slug' => 'concentrate',
                    'description' => 'Concentrated feed products'
                ),
            )
        ),
    );

    // Create parent categories and their subcategories
    foreach ( $category_structure as $parent_name => $parent_data ) {
        // Check if parent category exists
        $parent_term = get_term_by( 'slug', $parent_data['slug'], 'product_cat' );
        
        if ( ! $parent_term ) {
            // Create parent category
            $parent_result = wp_insert_term(
                $parent_name,
                'product_cat',
                array(
                    'description' => $parent_data['description'],
                    'slug' => $parent_data['slug']
                )
            );
            
            if ( ! is_wp_error( $parent_result ) ) {
                $parent_id = $parent_result['term_id'];
            } else {
                continue; // Skip if parent creation failed
            }
        } else {
            $parent_id = $parent_term->term_id;
        }
        
        // Create subcategories under parent
        if ( isset( $parent_data['subcategories'] ) && ! empty( $parent_data['subcategories'] ) ) {
            foreach ( $parent_data['subcategories'] as $sub_name => $sub_data ) {
                $sub_term = get_term_by( 'slug', $sub_data['slug'], 'product_cat' );
                
                if ( ! $sub_term ) {
                    wp_insert_term(
                        $sub_name,
                        'product_cat',
                        array(
                            'description' => $sub_data['description'],
                            'slug' => $sub_data['slug'],
                            'parent' => $parent_id
                        )
                    );
                }
            }
        }
    }

    // Mark that we've created the WooCommerce categories
    update_option( 'hello_elementor_child_wc_categories_created', true );
}
add_action( 'init', 'hello_elementor_child_create_woocommerce_categories' );

