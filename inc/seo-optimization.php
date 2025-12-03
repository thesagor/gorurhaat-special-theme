<?php
/**
 * SEO Optimization for Gorurhaat
 * 
 * This file adds comprehensive SEO features with "Gorurhaat" as the main keyword
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add SEO Meta Tags to Head
 */
function gorurhaat_seo_meta_tags() {
    // Get current page info
    $site_name = 'Gorurhaat';
    $site_description = get_bloginfo( 'description' );
    
    // Default meta tags
    $meta_title = $site_name . ' - ' . $site_description;
    $meta_description = 'Gorurhaat is your trusted online marketplace for livestock, dairy products, and animal feed in Bangladesh. Buy and sell cattle, cows, oxen, dairy products, and quality animal feed at Gorurhaat.';
    $meta_keywords = 'Gorurhaat, গরুর হাট, livestock market, cattle market Bangladesh, buy cows online, dairy products Bangladesh, animal feed, ox for sale, dairy cow, bokna, গরু কিনুন, গরু বিক্রয়';
    $og_image = get_template_directory_uri() . '/screenshot.png';
    
    // Page-specific meta
    if ( is_singular( 'product' ) ) {
        global $post, $product;
        
        $meta_title = get_the_title() . ' - Gorurhaat';
        $meta_description = wp_trim_words( get_the_excerpt(), 30, '...' ) . ' Available at Gorurhaat, Bangladesh\'s trusted livestock and agricultural marketplace.';
        
        // Add product-specific keywords
        $product_type = get_post_meta( get_the_ID(), '_product_info_type', true );
        $categories = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
        
        $product_keywords = array( 'Gorurhaat', get_the_title() );
        if ( ! empty( $categories ) ) {
            $product_keywords = array_merge( $product_keywords, $categories );
        }
        
        if ( $product_type === 'livestock' ) {
            $product_keywords[] = 'livestock';
            $product_keywords[] = 'cattle';
            $product_keywords[] = 'cow for sale';
            $product_keywords[] = 'গরু';
        } elseif ( $product_type === 'dairy_product' ) {
            $product_keywords[] = 'dairy products';
            $product_keywords[] = 'milk products';
            $product_keywords[] = 'দুগ্ধজাত পণ্য';
        } elseif ( $product_type === 'feed' ) {
            $product_keywords[] = 'animal feed';
            $product_keywords[] = 'cattle feed';
            $product_keywords[] = 'গরুর খাদ্য';
        }
        
        $meta_keywords = implode( ', ', $product_keywords );
        
        // Get product image
        if ( has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        }
    } elseif ( is_product_category() ) {
        $term = get_queried_object();
        $meta_title = $term->name . ' - Gorurhaat';
        $meta_description = $term->description ? $term->description : 'Browse ' . $term->name . ' at Gorurhaat - Bangladesh\'s premier online marketplace for livestock and agricultural products.';
        $meta_keywords = 'Gorurhaat, ' . $term->name . ', livestock, cattle, dairy products, animal feed, Bangladesh';
    } elseif ( is_shop() ) {
        $meta_title = 'Shop - Gorurhaat | Livestock, Dairy & Animal Feed Marketplace';
        $meta_description = 'Shop at Gorurhaat for premium livestock, dairy products, and animal feed. Bangladesh\'s most trusted online agricultural marketplace. Quality products, competitive prices.';
    } elseif ( is_front_page() ) {
        $meta_title = 'Gorurhaat - Bangladesh\'s Premier Livestock & Agricultural Marketplace';
        $meta_description = 'Welcome to Gorurhaat (গরুর হাট) - Buy and sell livestock, dairy products, and animal feed online. Trusted marketplace for cattle, cows, oxen, dairy products, and quality feed in Bangladesh.';
    }
    
    // Output meta tags
    ?>
    <!-- SEO Meta Tags by Gorurhaat -->
    <meta name="description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="keywords" content="<?php echo esc_attr( $meta_keywords ); ?>">
    <meta name="author" content="Gorurhaat">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:locale" content="en_US">
    <meta property="og:locale:alternate" content="bn_BD">
    <meta property="og:type" content="<?php echo is_singular( 'product' ) ? 'product' : 'website'; ?>">
    <meta property="og:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta property="og:url" content="<?php echo esc_url( get_permalink() ); ?>">
    <meta property="og:site_name" content="Gorurhaat">
    <meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
    
    <?php if ( is_singular( 'product' ) && $product ) : ?>
    <!-- Product-Specific Meta Tags -->
    <meta property="product:price:amount" content="<?php echo esc_attr( $product->get_price() ); ?>">
    <meta property="product:price:currency" content="BDT">
    <meta property="product:availability" content="<?php echo $product->is_in_stock() ? 'in stock' : 'out of stock'; ?>">
    <meta property="product:brand" content="Gorurhaat">
    <?php endif; ?>
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url( get_permalink() ); ?>">
    
    <!-- Alternate Language Links -->
    <link rel="alternate" hreflang="en" href="<?php echo esc_url( get_permalink() ); ?>">
    <link rel="alternate" hreflang="bn" href="<?php echo esc_url( get_permalink() ); ?>">
    <?php
}
add_action( 'wp_head', 'gorurhaat_seo_meta_tags', 1 );

/**
 * Add Schema.org Structured Data
 */
function gorurhaat_schema_markup() {
    if ( is_singular( 'product' ) ) {
        global $product;
        
        $schema = array(
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => get_the_title(),
            'description' => wp_trim_words( get_the_excerpt(), 50, '...' ),
            'brand' => array(
                '@type' => 'Brand',
                'name' => 'Gorurhaat'
            ),
            'offers' => array(
                '@type' => 'Offer',
                'url' => get_permalink(),
                'priceCurrency' => 'BDT',
                'price' => $product->get_price(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => array(
                    '@type' => 'Organization',
                    'name' => 'Gorurhaat'
                )
            )
        );
        
        if ( has_post_thumbnail() ) {
            $schema['image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        }
        
        // Add rating if available
        if ( $product->get_average_rating() ) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count()
            );
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
        
    } elseif ( is_front_page() || is_shop() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Gorurhaat',
            'url' => home_url(),
            'description' => 'Bangladesh\'s Premier Livestock & Agricultural Marketplace',
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string'
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
        
        // Organization Schema
        $org_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Gorurhaat',
            'url' => home_url(),
            'logo' => get_template_directory_uri() . '/screenshot.png',
            'description' => 'Gorurhaat is Bangladesh\'s trusted online marketplace for livestock, dairy products, and animal feed.',
            'sameAs' => array(
                // Add your social media URLs here
                'https://www.facebook.com/gorurhaat',
                'https://twitter.com/gorurhaat'
            )
        );
        
        echo '<script type="application/ld+json">' . wp_json_encode( $org_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
    }
}
add_action( 'wp_footer', 'gorurhaat_schema_markup' );

/**
 * Optimize Product Titles for SEO
 */
function gorurhaat_optimize_product_title( $title, $id = null ) {
    if ( is_singular( 'product' ) && in_the_loop() && $id === get_the_ID() ) {
        $product_type = get_post_meta( $id, '_product_info_type', true );
        
        // Add product type context to title
        if ( $product_type === 'livestock' ) {
            $title .= ' - Livestock at Gorurhaat';
        } elseif ( $product_type === 'dairy_product' ) {
            $title .= ' - Dairy Product at Gorurhaat';
        } elseif ( $product_type === 'feed' ) {
            $title .= ' - Animal Feed at Gorurhaat';
        } else {
            $title .= ' - Gorurhaat';
        }
    }
    
    return $title;
}
add_filter( 'the_title', 'gorurhaat_optimize_product_title', 10, 2 );

/**
 * Add Breadcrumbs for Better SEO
 */
function gorurhaat_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }
    
    $separator = ' › ';
    $home_title = 'Gorurhaat Home';
    
    echo '<nav class="gorurhaat-breadcrumbs" aria-label="Breadcrumb">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // Home link
    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url( home_url() ) . '">';
    echo '<span itemprop="name">' . esc_html( $home_title ) . '</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';
    
    $position = 2;
    
    if ( is_shop() ) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">Shop</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    } elseif ( is_product_category() ) {
        $term = get_queried_object();
        echo $separator;
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html( $term->name ) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    } elseif ( is_singular( 'product' ) ) {
        echo $separator;
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<a itemprop="item" href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">';
        echo '<span itemprop="name">Shop</span></a>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
        
        $position++;
        $categories = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $categories && ! is_wp_error( $categories ) ) {
            $category = array_shift( $categories );
            echo $separator;
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a itemprop="item" href="' . esc_url( get_term_link( $category ) ) . '">';
            echo '<span itemprop="name">' . esc_html( $category->name ) . '</span></a>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            $position++;
        }
        
        echo $separator;
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html( get_the_title() ) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Add Gorurhaat branding to product descriptions
 */
function gorurhaat_enhance_product_description( $description ) {
    if ( is_singular( 'product' ) && ! empty( $description ) ) {
        $description .= '<p><em>Available exclusively at Gorurhaat - Bangladesh\'s trusted livestock and agricultural marketplace.</em></p>';
    }
    return $description;
}
add_filter( 'woocommerce_short_description', 'gorurhaat_enhance_product_description' );

/**
 * Add ALT tags to product images for SEO
 */
function gorurhaat_product_image_alt( $html, $attachment_id ) {
    $product_title = get_the_title();
    $alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    
    if ( empty( $alt_text ) ) {
        $alt_text = $product_title . ' - Gorurhaat';
        update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt_text );
    }
    
    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'gorurhaat_product_image_alt', 10, 2 );

/**
 * Optimize sitemap for Gorurhaat
 */
function gorurhaat_sitemap_priority( $priority, $type, $object ) {
    if ( $type === 'post_type' && $object->post_type === 'product' ) {
        return 0.9; // High priority for products
    }
    return $priority;
}
add_filter( 'wp_sitemaps_posts_entry', 'gorurhaat_sitemap_priority', 10, 3 );
