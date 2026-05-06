<?php
/**
 * Shop Archive Template — Gorurhaat Custom Design
 * Hero banner + left filter sidebar + right product grid
 *
 * @package HelloElementorChild
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// ── Collect active filter values from URL ────────────────────────────────────
$active_cat    = isset( $_GET['product_cat'] )                    ? sanitize_text_field( wp_unslash( $_GET['product_cat'] ) )                    : '';
$active_type   = isset( $_GET['filter_product_type_category'] )   ? sanitize_text_field( wp_unslash( $_GET['filter_product_type_category'] ) )   : '';
$min_price     = isset( $_GET['min_price'] ) && $_GET['min_price'] !== '' ? absint( $_GET['min_price'] ) : '';
$max_price     = isset( $_GET['max_price'] ) && $_GET['max_price'] !== '' ? absint( $_GET['max_price'] ) : '';
$active_stock  = ! empty( $_GET['instock'] ) ? '1' : '';
$current_sort  = isset( $_GET['orderby'] ) ? sanitize_text_field( wp_unslash( $_GET['orderby'] ) ) : get_option( 'woocommerce_default_catalog_orderby', 'menu_order' );

$shop_url = function_exists( 'wc_get_page_id' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );

// Active filter count for mobile badge
$filter_count = 0;
if ( $active_cat )               $filter_count++;
if ( $active_type )              $filter_count++;
if ( $min_price || $max_price )  $filter_count++;
if ( $active_stock )             $filter_count++;

// Price boundaries from all products
global $wpdb;
$price_row  = $wpdb->get_row( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    "SELECT MIN(CAST(meta_value AS UNSIGNED)) AS mn, MAX(CAST(meta_value AS UNSIGNED)) AS mx
     FROM {$wpdb->postmeta}
     WHERE meta_key = '_price' AND meta_value != '' AND meta_value > 0"
);
$global_min  = max( 0,       (int) ( $price_row->mn ?? 0 ) );
$global_max  = max( 500000,  (int) ( $price_row->mx ?? 500000 ) );
$cur_min     = $min_price !== '' ? (int) $min_price : $global_min;
$cur_max     = $max_price !== '' ? (int) $max_price : $global_max;

$total_products = (int) wp_count_posts( 'product' )->publish;
?>

<?php /* ================================================================
       HERO BANNER
       ================================================================ */ ?>
<section class="gorurhaat-shop-hero" aria-label="<?php esc_attr_e( 'Shop banner', 'hello-elementor-child' ); ?>">
    <div class="shop-hero-overlay"></div>
    <div class="shop-hero-inner">

        <span class="shop-hero-badge">
            <?php esc_html_e( '🐄 বাংলাদেশের #১ অনলাইন গরুর হাট', 'hello-elementor-child' ); ?>
        </span>

        <h1 class="shop-hero-title">
            <?php
            if ( is_product_category() ) {
                $queried_term = get_queried_object();
                echo esc_html( $queried_term->name );
            } else {
                esc_html_e( 'গরুর হাট — কোরবানি, ডেইরি ও পশু খাদ্য', 'hello-elementor-child' );
            }
            ?>
        </h1>

        <p class="shop-hero-sub">
            <?php
            if ( is_product_category() && ! empty( get_queried_object()->description ) ) {
                echo wp_kses_post( get_queried_object()->description );
            } else {
                printf(
                    /* translators: %d: product count */
                    esc_html__( 'সেরা মানের গরু ও পশু পণ্য সরাসরি আপনার দরজায়। %d+ পণ্য উপলব্ধ।', 'hello-elementor-child' ),
                    $total_products
                );
            }
            ?>
        </p>

        <form method="get" class="shop-hero-search" action="<?php echo esc_url( $shop_url ); ?>" role="search">
            <?php if ( $active_cat )  : ?><input type="hidden" name="product_cat" value="<?php echo esc_attr( $active_cat ); ?>"><?php endif; ?>
            <input
                type="search"
                name="s"
                value="<?php echo ! empty( $_GET['s'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) : ''; ?>"
                placeholder="<?php esc_attr_e( 'গরু খুঁজুন… (Qurbani cow, দেশি গরু)', 'hello-elementor-child' ); ?>"
                class="shop-hero-search-input"
                aria-label="<?php esc_attr_e( 'Search products', 'hello-elementor-child' ); ?>"
            >
            <input type="hidden" name="post_type" value="product">
            <button type="submit" class="shop-hero-search-btn" aria-label="<?php esc_attr_e( 'Search', 'hello-elementor-child' ); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </button>
        </form>

        <div class="shop-hero-stats" role="list">
            <div class="shop-stat" role="listitem"><strong><?php echo esc_html( $total_products ); ?>+</strong><span><?php esc_html_e( 'Products', 'hello-elementor-child' ); ?></span></div>
            <div class="shop-stat" role="listitem"><strong>৬৪</strong><span><?php esc_html_e( 'Districts', 'hello-elementor-child' ); ?></span></div>
            <div class="shop-stat" role="listitem"><strong>24/7</strong><span><?php esc_html_e( 'Support', 'hello-elementor-child' ); ?></span></div>
            <div class="shop-stat" role="listitem"><strong>100%</strong><span><?php esc_html_e( 'Trusted', 'hello-elementor-child' ); ?></span></div>
        </div>

    </div>
</section>

<?php /* ================================================================
       SHOP LAYOUT  (sidebar + grid)
       ================================================================ */ ?>
<div class="gorurhaat-shop-wrap">

    <?php /* -- Mobile filter toggle button -- */ ?>
    <button
        class="shop-filter-toggle"
        id="shopFilterToggle"
        aria-expanded="false"
        aria-controls="shopSidebar"
    >
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <line x1="4" y1="6" x2="20" y2="6"/>
            <line x1="8" y1="12" x2="20" y2="12"/>
            <line x1="12" y1="18" x2="20" y2="18"/>
        </svg>
        <?php esc_html_e( 'Filters', 'hello-elementor-child' ); ?>
        <?php if ( $filter_count > 0 ) : ?>
            <span class="filter-badge" aria-label="<?php echo esc_attr( $filter_count ); ?> active filters"><?php echo esc_html( $filter_count ); ?></span>
        <?php endif; ?>
    </button>

    <?php /* ============================================================
           SIDEBAR
           ============================================================ */ ?>
    <aside class="gorurhaat-shop-sidebar" id="shopSidebar" role="complementary" aria-label="<?php esc_attr_e( 'Product filters', 'hello-elementor-child' ); ?>">

        <div class="sidebar-inner">

            <div class="sidebar-head">
                <h2 class="sidebar-title">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    <?php esc_html_e( 'Filters', 'hello-elementor-child' ); ?>
                </h2>
                <button class="sidebar-close" id="sidebarClose" aria-label="<?php esc_attr_e( 'Close filters', 'hello-elementor-child' ); ?>">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <form method="get" action="<?php echo esc_url( $shop_url ); ?>" id="shopFilterForm">

                <?php if ( $current_sort && $current_sort !== get_option( 'woocommerce_default_catalog_orderby' ) ) : ?>
                    <input type="hidden" name="orderby" value="<?php echo esc_attr( $current_sort ); ?>">
                <?php endif; ?>

                <?php /* ---- Categories ---- */ ?>
                <?php
                $cats = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'parent'     => 0,
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'number'     => 20,
                    'exclude'    => array( get_option( 'default_product_cat', 0 ) ),
                ) );
                if ( $cats && ! is_wp_error( $cats ) ) :
                ?>
                <div class="sf-section" id="sfCats">
                    <button type="button" class="sf-toggle" aria-expanded="true" aria-controls="sfCatsBody">
                        <span><?php esc_html_e( 'Categories', 'hello-elementor-child' ); ?></span>
                        <svg class="sf-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sf-body" id="sfCatsBody">
                        <label class="sf-radio<?php echo ! $active_cat ? ' is-active' : ''; ?>">
                            <input type="radio" name="product_cat" value="" <?php checked( $active_cat, '' ); ?>>
                            <span><?php esc_html_e( 'All Categories', 'hello-elementor-child' ); ?></span>
                        </label>
                        <?php foreach ( $cats as $cat ) :
                            $is_active = ( $active_cat === $cat->slug );
                            ?>
                            <label class="sf-radio<?php echo $is_active ? ' is-active' : ''; ?>">
                                <input type="radio" name="product_cat" value="<?php echo esc_attr( $cat->slug ); ?>" <?php checked( $active_cat, $cat->slug ); ?>>
                                <span>
                                    <?php echo esc_html( $cat->name ); ?>
                                    <em>(<?php echo esc_html( $cat->count ); ?>)</em>
                                </span>
                            </label>
                            <?php
                            $sub_cats = get_terms( array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => $cat->term_id,
                            ) );
                            if ( $sub_cats && ! is_wp_error( $sub_cats ) ) :
                            ?>
                            <div class="sf-subcats">
                                <?php foreach ( $sub_cats as $sub ) : ?>
                                    <label class="sf-radio sf-radio-sub<?php echo ( $active_cat === $sub->slug ) ? ' is-active' : ''; ?>">
                                        <input type="radio" name="product_cat" value="<?php echo esc_attr( $sub->slug ); ?>" <?php checked( $active_cat, $sub->slug ); ?>>
                                        <span>
                                            <?php echo esc_html( $sub->name ); ?>
                                            <em>(<?php echo esc_html( $sub->count ); ?>)</em>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php /* ---- Product Type (custom taxonomy) ---- */ ?>
                <?php
                $prod_types = get_terms( array(
                    'taxonomy'   => 'product_type_category',
                    'hide_empty' => true,
                ) );
                if ( $prod_types && ! is_wp_error( $prod_types ) ) :
                ?>
                <div class="sf-section" id="sfType">
                    <button type="button" class="sf-toggle" aria-expanded="true" aria-controls="sfTypeBody">
                        <span><?php esc_html_e( 'Product Type', 'hello-elementor-child' ); ?></span>
                        <svg class="sf-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sf-body" id="sfTypeBody">
                        <label class="sf-radio<?php echo ! $active_type ? ' is-active' : ''; ?>">
                            <input type="radio" name="filter_product_type_category" value="" <?php checked( $active_type, '' ); ?>>
                            <span><?php esc_html_e( 'All Types', 'hello-elementor-child' ); ?></span>
                        </label>
                        <?php foreach ( $prod_types as $type ) :
                            $is_active = ( $active_type === $type->slug );
                            ?>
                            <label class="sf-radio<?php echo $is_active ? ' is-active' : ''; ?>">
                                <input type="radio" name="filter_product_type_category" value="<?php echo esc_attr( $type->slug ); ?>" <?php checked( $active_type, $type->slug ); ?>>
                                <span>
                                    <?php echo esc_html( $type->name ); ?>
                                    <em>(<?php echo esc_html( $type->count ); ?>)</em>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php /* ---- Price Range ---- */ ?>
                <div class="sf-section" id="sfPrice">
                    <button type="button" class="sf-toggle" aria-expanded="true" aria-controls="sfPriceBody">
                        <span><?php esc_html_e( 'Price', 'hello-elementor-child' ); ?></span>
                        <svg class="sf-chevron" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sf-body" id="sfPriceBody">
                        <div class="sf-price-display">
                            <span class="sf-price-label"><?php esc_html_e( 'Price Range:', 'hello-elementor-child' ); ?></span>
                            <span class="sf-price-value">
                                <span id="sfPriceMin">৳<?php echo number_format( $cur_min ); ?></span>
                                &nbsp;–&nbsp;
                                <span id="sfPriceMax">৳<?php echo number_format( $cur_max ); ?></span>
                            </span>
                        </div>
                        <div class="sf-range-wrap"
                             data-min="<?php echo esc_attr( $global_min ); ?>"
                             data-max="<?php echo esc_attr( $global_max ); ?>"
                             id="sfRangeWrap">
                            <div class="sf-range-track">
                                <div class="sf-range-fill" id="sfRangeFill"></div>
                            </div>
                            <input type="range"
                                   id="sfRangeMinInput"
                                   name="min_price"
                                   min="<?php echo esc_attr( $global_min ); ?>"
                                   max="<?php echo esc_attr( $global_max ); ?>"
                                   value="<?php echo esc_attr( $cur_min ); ?>"
                                   step="500"
                                   class="sf-range-input sf-range-low"
                                   aria-label="<?php esc_attr_e( 'Minimum price', 'hello-elementor-child' ); ?>">
                            <input type="range"
                                   id="sfRangeMaxInput"
                                   name="max_price"
                                   min="<?php echo esc_attr( $global_min ); ?>"
                                   max="<?php echo esc_attr( $global_max ); ?>"
                                   value="<?php echo esc_attr( $cur_max ); ?>"
                                   step="500"
                                   class="sf-range-input sf-range-high"
                                   aria-label="<?php esc_attr_e( 'Maximum price', 'hello-elementor-child' ); ?>">
                        </div>
                    </div>
                </div>

                <?php /* ---- In Stock toggle ---- */ ?>
                <div class="sf-section sf-section-inline">
                    <label class="sf-switch">
                        <input type="checkbox" name="instock" value="1" id="sfInStock" <?php checked( $active_stock, '1' ); ?>>
                        <span class="sf-switch-track"></span>
                        <span class="sf-switch-label"><?php esc_html_e( 'In Stock Only', 'hello-elementor-child' ); ?></span>
                    </label>
                </div>

                <?php /* ---- Actions ---- */ ?>
                <div class="sf-actions">
                    <button type="submit" class="sf-apply-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php esc_html_e( 'Apply Filters', 'hello-elementor-child' ); ?>
                    </button>
                    <a href="<?php echo esc_url( $shop_url ); ?>" class="sf-reset-btn">
                        <?php esc_html_e( 'Reset', 'hello-elementor-child' ); ?>
                    </a>
                </div>

            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

    <?php /* ============================================================
           MAIN PRODUCT AREA
           ============================================================ */ ?>
    <main class="gorurhaat-shop-main" id="shopMain">

        <?php /* ---- Active filter chips ---- */ ?>
        <?php if ( $filter_count > 0 ) : ?>
        <div class="active-filters" role="status" aria-live="polite" aria-label="<?php esc_attr_e( 'Active filters', 'hello-elementor-child' ); ?>">
            <span class="active-filters-label"><?php esc_html_e( 'Active:', 'hello-elementor-child' ); ?></span>

            <?php if ( $active_cat ) :
                $cat_term = get_term_by( 'slug', $active_cat, 'product_cat' );
                if ( $cat_term ) :
                    $clear = remove_query_arg( 'product_cat' );
                    ?>
                    <a href="<?php echo esc_url( $clear ); ?>" class="filter-chip">
                        <?php echo esc_html( $cat_term->name ); ?>
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </a>
                <?php endif;
            endif; ?>

            <?php if ( $active_type ) :
                $type_term = get_term_by( 'slug', $active_type, 'product_type_category' );
                $type_label = $type_term ? $type_term->name : $active_type;
                $clear = remove_query_arg( 'filter_product_type_category' );
                ?>
                <a href="<?php echo esc_url( $clear ); ?>" class="filter-chip">
                    <?php echo esc_html( $type_label ); ?>
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
            <?php endif; ?>

            <?php if ( $min_price !== '' || $max_price !== '' ) :
                $clear = remove_query_arg( array( 'min_price', 'max_price' ) );
                ?>
                <a href="<?php echo esc_url( $clear ); ?>" class="filter-chip">
                    ৳<?php echo number_format( $cur_min ); ?> – ৳<?php echo number_format( $cur_max ); ?>
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
            <?php endif; ?>

            <?php if ( $active_stock ) :
                $clear = remove_query_arg( 'instock' );
                ?>
                <a href="<?php echo esc_url( $clear ); ?>" class="filter-chip">
                    <?php esc_html_e( 'In Stock', 'hello-elementor-child' ); ?>
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url( $shop_url ); ?>" class="filter-chip filter-chip-clear">
                <?php esc_html_e( 'Clear All', 'hello-elementor-child' ); ?>
            </a>
        </div>
        <?php endif; ?>

        <?php if ( woocommerce_product_loop() ) : ?>

            <?php /* ---- Sort / result bar ---- */ ?>
            <?php
            // Remove default result count + ordering fired by woocommerce_before_shop_loop
            // so they don't duplicate our custom sort bar below.
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
            ?>
            <div class="shop-sort-bar">
                <div class="shop-result-count"><?php woocommerce_result_count(); ?></div>
                <div class="shop-ordering"><?php woocommerce_catalog_ordering(); ?></div>
            </div>

            <?php
            do_action( 'woocommerce_before_shop_loop' );
            woocommerce_product_loop_start();
            if ( wc_get_loop_prop( 'total' ) ) {
                while ( have_posts() ) {
                    the_post();
                    do_action( 'woocommerce_shop_loop' );
                    wc_get_template_part( 'content', 'product' );
                }
            }
            woocommerce_product_loop_end();
            do_action( 'woocommerce_after_shop_loop' );
            ?>

        <?php else : ?>

            <?php do_action( 'woocommerce_no_products_found' ); ?>

        <?php endif; ?>

    </main>
</div>

<?php get_footer( 'shop' ); ?>
