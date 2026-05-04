<?php
/**
 * SEO Optimization for Gorurhaat
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add SEO meta tags, Open Graph, Twitter Card, canonical, and hreflang to <head>
 */
function gorurhaat_seo_meta_tags() {
    $og_image = get_template_directory_uri() . '/screenshot.png';
    $og_type  = 'website';

    // Defaults (overridden per page type below)
    $meta_title       = 'Online Gorur Haat – Cow Price in Bangladesh 2026 | Qurbani & Dairy Cow | Gorurhaat';
    $meta_description = 'বাংলাদেশের প্রথম অনলাইন গরুর হাট – কোরবানির গরু, ডেইরি গরু ও খামারের সমাধান। নিরাপদ ডেলিভারি, সঙ্গত মূল্য। Cow price in Bangladesh 2026.';
    $meta_keywords    = 'Gorurhaat, গরুর হাট, cow price in bangladesh 2026, online gorur haat, qurbani cow price in bangladesh, buy cow online, online cattle market bangladesh, qurbani online, কোরবানির গরু, gorur hat';
    $canonical_url    = home_url( '/' );

    if ( is_front_page() || is_home() ) {
        $canonical_url = home_url( '/' );

    } elseif ( is_singular( 'product' ) ) {
        $product = wc_get_product( get_the_ID() );

        $meta_title    = get_the_title() . ' – Gorurhaat | Online Gorur Haat Bangladesh';
        $excerpt       = wp_strip_all_tags( get_the_excerpt() );
        $meta_description = $excerpt
            ? wp_trim_words( $excerpt, 30, '...' )
            : get_the_title() . ' পাওয়া যাচ্ছে Gorurhaat-এ। বাংলাদেশের বিশ্বস্ত অনলাইন গরুর হাট। নিরাপদ ডেলিভারি ও সঙ্গত মূল্য। Cow price in Bangladesh 2026.';

        $cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'names' ) );
        $cat_str = ! empty( $cats ) ? implode( ', ', $cats ) . ', ' : '';
        $meta_keywords = 'Gorurhaat, ' . get_the_title() . ', ' . $cat_str . 'cow price in bangladesh 2026, online gorur haat, qurbani cow bangladesh';

        if ( has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        }
        $og_type       = 'product';
        $canonical_url = get_permalink();

    } elseif ( is_product_category() ) {
        $term          = get_queried_object();
        $meta_title    = $term->name . ' – Gorurhaat | Online Gorur Haat Bangladesh';
        $meta_description = $term->description
            ? wp_strip_all_tags( $term->description )
            : $term->name . ' পাওয়া যাচ্ছে Gorurhaat-এ। সেরা মূল্যে কিনুন। Cow price in Bangladesh 2026.';
        $canonical_url = get_term_link( $term );

    } elseif ( is_shop() ) {
        $meta_title       = 'Shop – Online Gorur Haat | Cow Price in Bangladesh 2026 | Gorurhaat';
        $meta_description = 'Shop at Gorurhaat for premium Qurbani cows, dairy cows, and animal feed. Cow price in Bangladesh 2026 – updated regularly. Safe delivery across Bangladesh.';
        $canonical_url    = get_permalink( wc_get_page_id( 'shop' ) );

    } elseif ( is_single() && get_post_type() === 'post' ) {
        $meta_title    = get_the_title() . ' – Gorurhaat Blog';
        $excerpt       = wp_strip_all_tags( get_the_excerpt() );
        $meta_description = $excerpt ? wp_trim_words( $excerpt, 30, '...' ) : $meta_description;
        if ( has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        }
        $canonical_url = get_permalink();
    }

    // Pagination-aware canonical
    $paged = get_query_var( 'paged' );
    if ( $paged > 1 ) {
        $canonical_url = get_pagenum_link( $paged );
    }

    ?>
    <meta name="description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="keywords" content="<?php echo esc_attr( $meta_keywords ); ?>">
    <meta name="author" content="Gorurhaat">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- Open Graph -->
    <meta property="og:locale" content="bn_BD">
    <meta property="og:locale:alternate" content="en_US">
    <meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>">
    <meta property="og:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta property="og:url" content="<?php echo esc_url( $canonical_url ); ?>">
    <meta property="og:site_name" content="Gorurhaat">
    <meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="<?php echo esc_attr( $meta_title ); ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@gorurhaat">
    <meta name="twitter:title" content="<?php echo esc_attr( $meta_title ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $meta_description ); ?>">
    <meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">

    <?php if ( is_singular( 'product' ) && isset( $product ) && is_object( $product ) ) : ?>
    <meta property="product:price:amount" content="<?php echo esc_attr( $product->get_price() ); ?>">
    <meta property="product:price:currency" content="BDT">
    <meta property="product:availability" content="<?php echo $product->is_in_stock() ? 'in stock' : 'out of stock'; ?>">
    <?php endif; ?>

    <link rel="canonical" href="<?php echo esc_url( $canonical_url ); ?>">

    <!-- hreflang for Bangladesh + diaspora -->
    <link rel="alternate" hreflang="bn-BD" href="<?php echo esc_url( $canonical_url ); ?>">
    <link rel="alternate" hreflang="en-BD" href="<?php echo esc_url( $canonical_url ); ?>">
    <link rel="alternate" hreflang="en" href="<?php echo esc_url( $canonical_url ); ?>">
    <link rel="alternate" hreflang="x-default" href="<?php echo esc_url( $canonical_url ); ?>">
    <?php
}
add_action( 'wp_head', 'gorurhaat_seo_meta_tags', 1 );

/**
 * Output JSON-LD structured data in <head> (not footer — head is preferred by Google)
 */
function gorurhaat_schema_markup() {
    $schemas = array();

    if ( is_singular( 'product' ) ) {
        $product = wc_get_product( get_the_ID() );
        if ( ! $product ) {
            return;
        }

        $product_schema = array(
            '@context' => 'https://schema.org/',
            '@type'    => 'Product',
            'name'     => get_the_title(),
            'description' => wp_strip_all_tags( wp_trim_words( get_the_content(), 50, '...' ) ),
            'url'      => get_permalink(),
            'brand'    => array( '@type' => 'Brand', 'name' => 'Gorurhaat' ),
            'offers'   => array(
                '@type'          => 'Offer',
                'url'            => get_permalink(),
                'priceCurrency'  => 'BDT',
                'price'          => $product->get_price() ?: '0',
                'priceValidUntil' => gmdate( 'Y-12-31' ),
                'availability'   => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller'         => array( '@type' => 'Organization', 'name' => 'Gorurhaat', 'url' => home_url() ),
            ),
        );

        if ( has_post_thumbnail() ) {
            $product_schema['image'] = array(
                '@type' => 'ImageObject',
                'url'   => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
            );
        }

        $cats = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $cats && ! is_wp_error( $cats ) ) {
            $product_schema['category'] = $cats[0]->name;
        }

        if ( $product->get_average_rating() > 0 ) {
            $product_schema['aggregateRating'] = array(
                '@type'       => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => max( 1, $product->get_review_count() ),
                'bestRating'  => '5',
                'worstRating' => '1',
            );
        }

        $schemas[] = $product_schema;

        // BreadcrumbList for product page
        $breadcrumb = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(
                array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url() ),
                array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => get_permalink( wc_get_page_id( 'shop' ) ) ),
            ),
        );
        if ( $cats && ! is_wp_error( $cats ) ) {
            $breadcrumb['itemListElement'][] = array( '@type' => 'ListItem', 'position' => 3, 'name' => $cats[0]->name, 'item' => get_term_link( $cats[0] ) );
            $breadcrumb['itemListElement'][] = array( '@type' => 'ListItem', 'position' => 4, 'name' => get_the_title() );
        } else {
            $breadcrumb['itemListElement'][] = array( '@type' => 'ListItem', 'position' => 3, 'name' => get_the_title() );
        }
        $schemas[] = $breadcrumb;

    } elseif ( is_product_category() ) {
        $term      = get_queried_object();
        $schemas[] = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => array(
                array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url() ),
                array( '@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => get_permalink( wc_get_page_id( 'shop' ) ) ),
                array( '@type' => 'ListItem', 'position' => 3, 'name' => $term->name, 'item' => get_term_link( $term ) ),
            ),
        );

    } elseif ( is_front_page() || is_home() ) {
        $schemas[] = array(
            '@context'       => 'https://schema.org',
            '@type'          => 'WebSite',
            'name'           => 'Gorurhaat',
            'alternateName'  => 'গরুর হাট',
            'url'            => home_url(),
            'description'    => 'Bangladesh\'s premier online livestock marketplace. Cow price in Bangladesh 2026.',
            'inLanguage'     => array( 'bn-BD', 'en-US' ),
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => array( '@type' => 'EntryPoint', 'urlTemplate' => home_url( '/?s={search_term_string}' ) ),
                'query-input' => 'required name=search_term_string',
            ),
        );

        $schemas[] = array(
            '@context'    => 'https://schema.org',
            '@type'       => array( 'Organization', 'LocalBusiness' ),
            'name'        => 'Gorurhaat',
            'alternateName' => 'গরুর হাট',
            'url'         => home_url(),
            'logo'        => array(
                '@type'  => 'ImageObject',
                'url'    => get_template_directory_uri() . '/screenshot.png',
                'width'  => 512,
                'height' => 512,
            ),
            'image'       => get_template_directory_uri() . '/screenshot.png',
            'description' => 'Bangladesh\'s first online gorur haat. Buy Qurbani cows, dairy cows, and animal feed at the best price. Safe delivery across Bangladesh.',
            'telephone'   => get_option( 'whatsapp_contact_number', '+8801780884888' ),
            'address'     => array(
                '@type'          => 'PostalAddress',
                'addressCountry' => 'BD',
                'addressLocality' => 'Dhaka',
            ),
            'areaServed'        => 'Bangladesh',
            'currenciesAccepted' => 'BDT',
            'paymentAccepted'   => 'Cash, bKash, Nagad, Bank Transfer',
            'priceRange'        => '৳৳৳',
            'sameAs'            => array( 'https://www.facebook.com/gorurhaat' ),
        );

    } elseif ( is_shop() ) {
        $schemas[] = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            'name'        => 'Online Gorur Haat – Shop | Gorurhaat',
            'description' => 'Shop for Qurbani cows, dairy cows, and animal feed at Gorurhaat. Cow price in Bangladesh 2026.',
            'url'         => get_permalink( wc_get_page_id( 'shop' ) ),
        );

    } elseif ( is_single() && get_post_type() === 'post' ) {
        $schemas[] = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BlogPosting',
            'headline'        => get_the_title(),
            'description'     => wp_strip_all_tags( wp_trim_words( get_the_excerpt(), 30 ) ),
            'url'             => get_permalink(),
            'datePublished'   => get_the_date( 'c' ),
            'dateModified'    => get_the_modified_date( 'c' ),
            'author'          => array( '@type' => 'Person', 'name' => get_the_author() ),
            'publisher'       => array(
                '@type' => 'Organization',
                'name'  => 'Gorurhaat',
                'logo'  => array( '@type' => 'ImageObject', 'url' => get_template_directory_uri() . '/screenshot.png' ),
            ),
            'image'           => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : get_template_directory_uri() . '/screenshot.png',
            'mainEntityOfPage' => array( '@type' => 'WebPage', '@id' => get_permalink() ),
        );
    }

    foreach ( $schemas as $schema ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'gorurhaat_schema_markup', 5 );

/**
 * Breadcrumb navigation with Schema markup
 * Hooked on woocommerce_before_main_content and singular posts
 */
function gorurhaat_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    $sep = ' <span aria-hidden="true">›</span> ';

    echo '<nav class="gorurhaat-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'hello-elementor-child' ) . '">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';

    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url( home_url() ) . '"><span itemprop="name">' . esc_html__( 'Home', 'hello-elementor-child' ) . '</span></a>';
    echo '<meta itemprop="position" content="1">';
    echo '</li>';

    $pos = 2;

    if ( is_shop() ) {
        echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . esc_html__( 'Shop', 'hello-elementor-child' ) . '</span><meta itemprop="position" content="' . $pos . '"></li>';

    } elseif ( is_product_category() ) {
        $term = get_queried_object();
        echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '"><span itemprop="name">' . esc_html__( 'Shop', 'hello-elementor-child' ) . '</span></a><meta itemprop="position" content="' . $pos . '"></li>';
        $pos++;
        echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . esc_html( $term->name ) . '</span><meta itemprop="position" content="' . $pos . '"></li>';

    } elseif ( is_singular( 'product' ) ) {
        echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '"><span itemprop="name">' . esc_html__( 'Shop', 'hello-elementor-child' ) . '</span></a><meta itemprop="position" content="' . $pos . '"></li>';
        $pos++;
        $cats = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $cats && ! is_wp_error( $cats ) ) {
            $cat = array_shift( $cats );
            echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_term_link( $cat ) ) . '"><span itemprop="name">' . esc_html( $cat->name ) . '</span></a><meta itemprop="position" content="' . $pos . '"></li>';
            $pos++;
        }
        echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . esc_html( get_the_title() ) . '</span><meta itemprop="position" content="' . $pos . '"></li>';

    } elseif ( is_single() ) {
        $cats = get_the_category();
        if ( $cats ) {
            echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="' . esc_url( get_category_link( $cats[0] ) ) . '"><span itemprop="name">' . esc_html( $cats[0]->name ) . '</span></a><meta itemprop="position" content="' . $pos . '"></li>';
            $pos++;
        }
        echo $sep . '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . esc_html( get_the_title() ) . '</span><meta itemprop="position" content="' . $pos . '"></li>';
    }

    echo '</ol></nav>';
}
add_action( 'woocommerce_before_main_content', 'gorurhaat_breadcrumbs', 5 );
add_action( 'gorurhaat_breadcrumbs', 'gorurhaat_breadcrumbs' );

/**
 * Fix robots.txt — remove junk URLs, block faceted/filter URLs
 * IMPORTANT: Delete any physical robots.txt from your server root for this filter to work.
 */
function gorurhaat_robots_txt( $output, $public ) {
    if ( '0' === $public ) {
        return "User-agent: *\nDisallow: /\n";
    }

    $sitemap_url = home_url( '/sitemap.xml' );

    $output  = "User-agent: *\n";
    $output .= "Disallow: /wp-admin/\n";
    $output .= "Disallow: /cart/\n";
    $output .= "Disallow: /checkout/\n";
    $output .= "Disallow: /my-account/\n";
    $output .= "Disallow: /*?add-to-cart=\n";
    $output .= "Disallow: /*?orderby=\n";
    $output .= "Disallow: /*?filter_*\n";
    $output .= "Disallow: /*?min_price=\n";
    $output .= "Disallow: /*?max_price=\n";
    $output .= "Disallow: /*?s=\n";
    $output .= "Allow: /wp-admin/admin-ajax.php\n\n";
    $output .= "Sitemap: " . esc_url( $sitemap_url ) . "\n";

    return $output;
}
add_filter( 'robots_txt', 'gorurhaat_robots_txt', 10, 2 );

/**
 * Add loading="lazy" to all WordPress attachment images
 */
function gorurhaat_image_lazy_loading( $attr, $attachment, $size ) {
    if ( ! isset( $attr['loading'] ) ) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'gorurhaat_image_lazy_loading', 10, 3 );

/**
 * Add lazy loading and descriptive alt text to WooCommerce single product images
 */
function gorurhaat_wc_product_image_lazy_load( $html, $attachment_id ) {
    if ( strpos( $html, 'loading=' ) === false ) {
        $html = str_replace( '<img ', '<img loading="lazy" ', $html );
    }
    if ( strpos( $html, 'alt=""' ) !== false ) {
        $alt  = esc_attr( get_the_title() . ' – Gorurhaat | Online Gorur Haat Bangladesh' );
        $html = str_replace( 'alt=""', 'alt="' . $alt . '"', $html );
    }
    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'gorurhaat_wc_product_image_lazy_load', 10, 2 );

/**
 * Add descriptive alt text to product loop images
 */
function gorurhaat_product_loop_image_alt( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if ( strpos( $html, 'alt=""' ) !== false ) {
        $alt  = esc_attr( get_the_title( $post_id ) . ' – Gorurhaat' );
        $html = str_replace( 'alt=""', 'alt="' . $alt . '"', $html );
    }
    return $html;
}
add_filter( 'post_thumbnail_html', 'gorurhaat_product_loop_image_alt', 10, 5 );

/**
 * Boost sitemap priority for products
 */
function gorurhaat_sitemap_priority( $entry, $post ) {
    if ( isset( $post->post_type ) && $post->post_type === 'product' ) {
        $entry['priority'] = 0.9;
    }
    return $entry;
}
add_filter( 'wp_sitemaps_posts_entry', 'gorurhaat_sitemap_priority', 10, 2 );

/**
 * Add lang="bn" to <html> tag for correct language signal
 */
function gorurhaat_language_attributes( $output ) {
    if ( strpos( $output, 'lang=' ) === false ) {
        $output .= ' lang="bn"';
    }
    return $output;
}
add_filter( 'language_attributes', 'gorurhaat_language_attributes' );
