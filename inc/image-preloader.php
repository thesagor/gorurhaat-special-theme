<?php
/**
 * Product Image Preloader
 * 
 * Adds a loading animation to product images while they load
 * Uses preloader.png as the loading indicator
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add preloader to product images
 */
function gorurhaat_add_image_preloader() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Preloader image URL
        const preloaderUrl = '<?php echo get_stylesheet_directory_uri(); ?>/preloader.png';
        
        // Function to add preloader to images
        function addImagePreloader(selector) {
            $(selector).each(function() {
                const $img = $(this);
                const $wrapper = $img.parent();
                
                // Skip if already processed
                if ($wrapper.hasClass('has-preloader')) {
                    return;
                }
                
                // Add wrapper class
                $wrapper.addClass('has-preloader loading');
                
                // Add preloader background
                $wrapper.css({
                    'background-image': 'url(' + preloaderUrl + ')',
                    'background-repeat': 'no-repeat',
                    'background-position': 'center',
                    'background-size': '50px 50px'
                });
                
                // When image loads
                $img.on('load', function() {
                    $wrapper.removeClass('loading').addClass('loaded');
                    setTimeout(function() {
                        $wrapper.css('background-image', 'none');
                    }, 300);
                });
                
                // If image is already loaded (cached)
                if ($img[0].complete) {
                    $img.trigger('load');
                }
            });
        }
        
        // Apply to different image types
        
        // 1. Product gallery images
        addImagePreloader('.woocommerce-product-gallery__image img');
        
        // 2. Product thumbnails
        addImagePreloader('.woocommerce-product-gallery__wrapper img');
        
        // 3. Product grid/archive images
        addImagePreloader('.woocommerce-loop-product__link img');
        addImagePreloader('.product-card .product-image img');
        addImagePreloader('.product-slide-image img');
        
        // 4. Related products
        addImagePreloader('.related-product-image img');
        
        // 5. Cart images
        addImagePreloader('.cart-item-image img');
        addImagePreloader('.product-thumbnail img');
        
        // 6. Single product images
        addImagePreloader('.product-images-section img');
        
        // Handle lazy-loaded images
        $(document).on('lazyloaded', function(e) {
            const $img = $(e.target);
            const $wrapper = $img.parent();
            $wrapper.removeClass('loading').addClass('loaded');
            setTimeout(function() {
                $wrapper.css('background-image', 'none');
            }, 300);
        });
        
        // Re-apply on AJAX events (for cart updates, etc.)
        $(document.body).on('updated_cart_totals updated_checkout', function() {
            setTimeout(function() {
                addImagePreloader('.cart-item-image img');
                addImagePreloader('.product-thumbnail img');
            }, 100);
        });
        
        // Re-apply when WooCommerce gallery initializes
        $(document).on('wc-product-gallery-after-init', function() {
            addImagePreloader('.woocommerce-product-gallery__image img');
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'gorurhaat_add_image_preloader' );

/**
 * Add preloader CSS
 */
function gorurhaat_preloader_styles() {
    ?>
    <style>
    /* Image Preloader Styles */
    .has-preloader {
        position: relative;
        min-height: 100px;
        background-color: #f8f9fa;
    }
    
    .has-preloader.loading {
        animation: preloader-pulse 1.5s ease-in-out infinite;
    }
    
    .has-preloader.loading img {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .has-preloader.loaded img {
        opacity: 1;
    }
    
    @keyframes preloader-pulse {
        0%, 100% {
            background-color: #f8f9fa;
        }
        50% {
            background-color: #e9ecef;
        }
    }
    
    /* Specific adjustments for different image containers */
    .woocommerce-product-gallery__image.has-preloader,
    .product-image.has-preloader,
    .product-slide-image.has-preloader,
    .related-product-image.has-preloader {
        display: block;
        overflow: hidden;
    }
    
    /* Cart drawer images */
    .cart-item-image.has-preloader {
        min-height: 80px;
        background-size: 30px 30px;
    }
    
    /* Product grid images */
    .woocommerce-loop-product__link.has-preloader {
        display: block;
        aspect-ratio: 1 / 1;
    }
    
    /* Ensure smooth transitions */
    .has-preloader img {
        display: block;
        width: 100%;
        height: auto;
    }
    </style>
    <?php
}
add_action( 'wp_head', 'gorurhaat_preloader_styles' );

/**
 * Add loading attribute to product images for better performance
 */
function gorurhaat_add_loading_attribute( $html, $post_id ) {
    // Add loading="lazy" to improve performance
    $html = str_replace( '<img', '<img loading="lazy"', $html );
    return $html;
}
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'gorurhaat_add_loading_attribute', 10, 2 );
add_filter( 'post_thumbnail_html', 'gorurhaat_add_loading_attribute', 10, 2 );
