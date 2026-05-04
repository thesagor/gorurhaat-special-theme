<?php
/**
 * Single Product Page Customizations
 *
 * @package HelloElementorChild
 */

// Re-enable reviews tab (needed for AggregateRating rich snippets and trust signals)
// If you want to hide reviews again, comment out the line below and uncomment the filter.
// add_filter( 'woocommerce_product_tabs', 'hello_elementor_child_remove_reviews_tab', 98 );
function hello_elementor_child_remove_reviews_tab( $tabs ) {
    unset( $tabs['reviews'] );
    return $tabs;
}

// ---------------------------------------------------------------------------
// Related products slider
// ---------------------------------------------------------------------------

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'hello_elementor_child_related_products_slider', 20 );

function hello_elementor_child_related_products_slider() {
    global $product;

    if ( ! $product ) {
        return;
    }

    $related_ids = wc_get_related_products( $product->get_id(), 8 );

    if ( empty( $related_ids ) ) {
        return;
    }
    ?>
    <div class="related-products-section">
        <h2 class="section-title"><?php esc_html_e( 'Related Products', 'hello-elementor-child' ); ?></h2>
        <div class="swiper related-products-slider" role="region" aria-label="<?php esc_attr_e( 'Related Products', 'hello-elementor-child' ); ?>">
            <div class="swiper-wrapper">
                <?php
                foreach ( $related_ids as $related_id ) :
                    $related_product = wc_get_product( $related_id );
                    if ( ! $related_product ) {
                        continue;
                    }
                    $r_title = esc_html( $related_product->get_name() );
                    $r_url   = esc_url( get_permalink( $related_id ) );
                    ?>
                    <div class="swiper-slide">
                        <div class="related-product-card">
                            <div class="related-product-image">
                                <a href="<?php echo $r_url; ?>" aria-label="<?php echo $r_title; ?>">
                                    <?php echo $related_product->get_image( 'medium', array( 'loading' => 'lazy', 'alt' => $r_title . ' – Gorurhaat' ) ); ?>
                                </a>
                            </div>
                            <div class="related-product-content">
                                <h3 class="related-product-title">
                                    <a href="<?php echo $r_url; ?>"><?php echo $r_title; ?></a>
                                </h3>
                                <div class="related-product-price">
                                    <?php echo $related_product->get_price_html(); ?>
                                </div>
                                <a href="<?php echo $r_url; ?>" class="related-product-button">
                                    <?php esc_html_e( 'View Details', 'hello-elementor-child' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-prev" aria-label="<?php esc_attr_e( 'Previous', 'hello-elementor-child' ); ?>"></div>
            <div class="swiper-button-next" aria-label="<?php esc_attr_e( 'Next', 'hello-elementor-child' ); ?>"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <script>
    (function() {
        function initRelatedSlider() {
            if ( typeof Swiper === 'undefined' ) return;
            new Swiper( '.related-products-slider', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                autoplay: { delay: 4000, disableOnInteraction: false },
                navigation: {
                    nextEl: '.related-products-slider .swiper-button-next',
                    prevEl: '.related-products-slider .swiper-button-prev',
                },
                pagination: { el: '.related-products-slider .swiper-pagination', clickable: true },
                breakpoints: {
                    640:  { slidesPerView: 2 },
                    768:  { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                },
                a11y: { prevSlideMessage: 'Previous slide', nextSlideMessage: 'Next slide' },
            });
        }
        if ( document.readyState === 'loading' ) {
            document.addEventListener( 'DOMContentLoaded', initRelatedSlider );
        } else {
            initRelatedSlider();
        }
    })();
    </script>
    <?php
}

// ---------------------------------------------------------------------------
// Social share buttons
// ---------------------------------------------------------------------------

add_action( 'woocommerce_single_product_summary', 'hello_elementor_child_social_share', 45 );

function hello_elementor_child_social_share() {
    $url   = rawurlencode( get_permalink() );
    $title = rawurlencode( get_the_title() );
    $image = rawurlencode( (string) get_the_post_thumbnail_url() );
    ?>
    <div class="product-social-share">
        <span class="share-label"><?php esc_html_e( 'Share:', 'hello-elementor-child' ); ?></span>
        <div class="share-buttons" role="list">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn facebook" aria-label="<?php esc_attr_e( 'Share on Facebook', 'hello-elementor-child' ); ?>" role="listitem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://wa.me/?text=<?php echo $title; ?>%20-%20<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" class="share-btn whatsapp" aria-label="<?php esc_attr_e( 'Share on WhatsApp', 'hello-elementor-child' ); ?>" role="listitem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.785"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" class="share-btn twitter" aria-label="<?php esc_attr_e( 'Share on Twitter', 'hello-elementor-child' ); ?>" role="listitem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&media=<?php echo $image; ?>&description=<?php echo $title; ?>" target="_blank" rel="noopener noreferrer" class="share-btn pinterest" aria-label="<?php esc_attr_e( 'Share on Pinterest', 'hello-elementor-child' ); ?>" role="listitem">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
            </a>
        </div>
    </div>
    <?php
}
