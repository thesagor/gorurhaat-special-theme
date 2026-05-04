/**
 * Skeleton Screen JavaScript
 * Handles skeleton loading states and content transitions
 */

(function ($) {
  "use strict";

  // Skeleton Screen Manager
  const SkeletonScreen = {
    /**
     * Initialize skeleton screens
     */
    init: function () {
      this.handlePageLoad();
      this.handleAjaxRequests();
      // Only enable infinite scroll on blog/archive pages, not on shop
      if (
        !document.body.classList.contains("woocommerce-page") &&
        !document.body.classList.contains("shop")
      ) {
        this.handleInfiniteScroll();
      }
      this.handleImageLoading();
      this.handleProductFilters();
    },

    /**
     * Handle initial page load
     */
    handlePageLoad: function () {
      const self = this;
      let maxAttempts = 0;
      const maxCheckAttempts = 20; // Max 10 seconds of checking

      // Hide skeleton as soon as DOM is ready
      $(document).ready(function () {
        self.checkAndHideSkeleton();
      });

      // Also check on window load
      $(window).on("load", function () {
        self.checkAndHideSkeleton();
      });

      // Check periodically for content (fallback)
      const checkInterval = setInterval(function () {
        maxAttempts++;
        self.checkAndHideSkeleton();

        // Stop checking after 10 seconds OR if no skeleton containers exist
        if (
          maxAttempts >= maxCheckAttempts ||
          $(".skeleton-container").length === 0
        ) {
          clearInterval(checkInterval);

          // Force hide any remaining skeletons after max timeout
          if (
            maxAttempts >= maxCheckAttempts &&
            $(".skeleton-container").length > 0
          ) {
            $(".skeleton-container").fadeOut(300, function () {
              $(this).remove();
            });
          }
        }
      }, 500);
    },

    /**
     * Check if content has loaded and hide skeleton
     */
    checkAndHideSkeleton: function () {
      $(".skeleton-container").each(function () {
        const $skeleton = $(this);
        const skeletonType = $skeleton.data("skeleton");
        let hasContent = false;

        // Check for content based on skeleton type
        if (skeletonType === "single-product") {
          // Check if product images or content exists
          hasContent =
            $(".woocommerce-product-gallery").length > 0 ||
            $(".product_title").length > 0 ||
            $(".summary").length > 0 ||
            $("img.wp-post-image").length > 0 ||
            $(".woocommerce-product-details__short-description").length > 0;
        } else if (skeletonType === "product-grid") {
          // Check if products exist - more flexible selectors
          hasContent =
            $(".products li.product").length > 0 ||
            $(".products .product").length > 0 ||
            $(".product-item").length > 0 ||
            $(".woocommerce-product-list li").length > 0;
        } else if (skeletonType === "post-list") {
          // Check if posts exist
          hasContent =
            $(".post").length > 0 ||
            $("article").length > 0 ||
            $(".post-item").length > 0;
        } else if (skeletonType === "cart") {
          // Check if cart items exist
          hasContent =
            $(".cart_item").length > 0 ||
            $(".woocommerce-cart-form").length > 0;
        } else if (skeletonType === "hero-slider") {
          // Check if hero slider has loaded (by checking if hero-slider section has loaded with images)
          hasContent =
            $skeleton.next(".hero-slider").length > 0 &&
            $skeleton.next(".hero-slider").find(".as-slider-background img")
              .length > 0;
        } else {
          // Generic check - if any meaningful content exists after skeleton
          const $nextContent = $skeleton.next();
          hasContent =
            $nextContent.length > 0 &&
            $nextContent.children().length > 0 &&
            $nextContent.html().trim().length > 0;
        }

        // If content exists, hide skeleton
        if (hasContent) {
          $skeleton.fadeOut(200, function () {
            $(this).remove();
          });
        }
      });
    },

    /**
     * Handle AJAX requests with skeleton screens
     */
    handleAjaxRequests: function () {
      const self = this;

      // Listen for AJAX start
      $(document).on("ajaxStart", function () {
        // You can add global AJAX skeleton handling here
      });

      // Listen for AJAX complete
      $(document).on("ajaxComplete", function () {
        self.hideSkeleton();
      });
    },

    /**
     * Handle infinite scroll with skeleton
     */
    handleInfiniteScroll: function () {
      const self = this;
      let isLoading = false;
      let currentPage = 1;
      let hasMorePages = true;
      let scrollTimeout = null;

      $(window).on("scroll", function () {
        // Clear previous timeout to debounce scroll events
        if (scrollTimeout) {
          clearTimeout(scrollTimeout);
        }

        // Only check after scrolling has stopped for 200ms
        scrollTimeout = setTimeout(function () {
          if (isLoading || !hasMorePages) return;

          const $loadMoreTrigger = $(".products, .posts");
          if (!$loadMoreTrigger.length) return;

          const scrollPosition = $(window).scrollTop() + $(window).height();
          const triggerPosition =
            $loadMoreTrigger.offset().top + $loadMoreTrigger.height() - 500;

          if (scrollPosition > triggerPosition) {
            isLoading = true;
            currentPage++;

            // Show skeleton before loading
            self.showSkeleton("product-grid", $loadMoreTrigger, 4);

            // Load more products
            self.loadMoreProducts(currentPage, function (hasMore) {
              isLoading = false;
              hasMorePages = hasMore;
            });
          }
        }, 200);
      });
    },

    /**
     * Load more products via AJAX
     */
    loadMoreProducts: function (page, callback) {
      const self = this;

      $.ajax({
        url: skeletonScreenData.ajax_url,
        type: "POST",
        data: {
          action: "load_products_skeleton",
          nonce: skeletonScreenData.nonce,
          page: page,
        },
        success: function (response) {
          if (response.success) {
            // Hide skeleton
            self.hideSkeleton();

            // Append new products
            $(".products").append(response.data.html);

            // Trigger fade-in animation
            $(".products > li:last-child").addClass("skeleton-fade-in");

            // Call callback with has_more status
            if (callback) callback(response.data.has_more);
          } else {
            self.hideSkeleton();
            if (callback) callback(false);
          }
        },
        error: function () {
          self.hideSkeleton();
          if (callback) callback(false);
        },
      });
    },

    /**
     * Handle product filter changes
     */
    handleProductFilters: function () {
      const self = this;

      // Listen for filter changes
      $(document).on(
        "change",
        ".woocommerce-ordering select, .product-filters select",
        function () {
          const $productsContainer = $(".products").parent();

          // Show skeleton
          $(".products").fadeOut(200, function () {
            $(this).remove();
            self.showSkeleton("product-grid", $productsContainer, 12);
          });
        }
      );

      // Listen for category filter clicks
      $(document).on("click", ".product-categories a", function (e) {
        const $productsContainer = $(".products").parent();

        // Show skeleton
        $(".products").fadeOut(200, function () {
          $(this).remove();
          self.showSkeleton("product-grid", $productsContainer, 12);
        });
      });
    },

    /**
     * Handle image loading with skeleton
     */
    handleImageLoading: function () {
      // Add skeleton to images that haven't loaded yet
      $('img[data-skeleton="true"]').each(function () {
        const $img = $(this);
        const $skeleton = $(
          '<div class="skeleton" style="width: 100%; height: ' +
            $img.height() +
            'px;"></div>'
        );

        $img.before($skeleton).hide();

        $img.on("load", function () {
          $skeleton.fadeOut(200, function () {
            $(this).remove();
          });
          $img.fadeIn(200).addClass("skeleton-fade-in");
        });
      });
    },

    /**
     * Show skeleton screen
     */
    showSkeleton: function (type, $container, count) {
      count = count || 8;
      let skeletonHTML = "";

      switch (type) {
        case "product-grid":
          skeletonHTML = this.getProductGridSkeleton(count);
          break;
        case "single-product":
          skeletonHTML = this.getSingleProductSkeleton();
          break;
        case "post-list":
          skeletonHTML = this.getPostListSkeleton(count);
          break;
        case "cart":
          skeletonHTML = this.getCartSkeleton(count);
          break;
        default:
          skeletonHTML = this.getGenericSkeleton();
      }

      const $skeleton = $(
        '<div class="skeleton-container" data-skeleton="' +
          type +
          '">' +
          skeletonHTML +
          "</div>"
      );
      $container.append($skeleton);

      return $skeleton;
    },

    /**
     * Hide skeleton screen
     */
    hideSkeleton: function ($skeleton) {
      if (!$skeleton) {
        $skeleton = $(".skeleton-container");
      }

      $skeleton.fadeOut(300, function () {
        $(this).remove();
      });
    },

    /**
     * Get product grid skeleton HTML
     */
    getProductGridSkeleton: function (count) {
      let html = '<div class="skeleton-product-grid">';

      for (let i = 0; i < count; i++) {
        html += `
                    <div class="skeleton-product-card">
                        <div class="skeleton skeleton-product-image"></div>
                        <div class="skeleton skeleton-product-title"></div>
                        <div class="skeleton skeleton-product-price"></div>
                        <div class="skeleton skeleton-product-rating"></div>
                        <div class="skeleton skeleton-product-button"></div>
                    </div>
                `;
      }

      html += "</div>";
      return html;
    },

    /**
     * Get single product skeleton HTML
     */
    getSingleProductSkeleton: function () {
      return `
                <div class="skeleton-single-product">
                    <div class="skeleton-product-gallery">
                        <div class="skeleton skeleton-main-image"></div>
                        <div class="skeleton-thumbnail-grid">
                            <div class="skeleton skeleton-thumbnail"></div>
                            <div class="skeleton skeleton-thumbnail"></div>
                            <div class="skeleton skeleton-thumbnail"></div>
                            <div class="skeleton skeleton-thumbnail"></div>
                        </div>
                    </div>
                    <div class="skeleton-product-details">
                        <div class="skeleton skeleton-product-category"></div>
                        <div class="skeleton skeleton-product-name"></div>
                        <div class="skeleton skeleton-product-price"></div>
                        <div class="skeleton skeleton-product-meta"></div>
                        <div class="skeleton-product-description">
                            <div class="skeleton"></div>
                            <div class="skeleton"></div>
                            <div class="skeleton"></div>
                            <div class="skeleton"></div>
                        </div>
                        <div class="skeleton skeleton-add-to-cart"></div>
                    </div>
                </div>
            `;
    },

    /**
     * Get post list skeleton HTML
     */
    getPostListSkeleton: function (count) {
      let html = '<div class="skeleton-post-list">';

      for (let i = 0; i < count; i++) {
        html += `
                    <div class="skeleton-post-item">
                        <div class="skeleton skeleton-post-featured-image"></div>
                        <div class="skeleton skeleton-post-title"></div>
                        <div class="skeleton skeleton-post-meta"></div>
                        <div class="skeleton-post-excerpt">
                            <div class="skeleton"></div>
                            <div class="skeleton"></div>
                            <div class="skeleton"></div>
                        </div>
                    </div>
                `;
      }

      html += "</div>";
      return html;
    },

    /**
     * Get cart skeleton HTML
     */
    getCartSkeleton: function (count) {
      let html = '<div class="skeleton-cart">';

      for (let i = 0; i < count; i++) {
        html += `
                    <div class="skeleton-cart-item">
                        <div class="skeleton skeleton-cart-item-image"></div>
                        <div class="skeleton-cart-item-details">
                            <div class="skeleton skeleton-cart-item-name"></div>
                            <div class="skeleton skeleton-cart-item-meta"></div>
                        </div>
                        <div class="skeleton skeleton-cart-item-price"></div>
                        <div class="skeleton skeleton-cart-item-remove"></div>
                    </div>
                `;
      }

      html += "</div>";
      return html;
    },

    /**
     * Get generic skeleton HTML
     */
    getGenericSkeleton: function () {
      return `
                <div class="skeleton skeleton-text-xl" style="width: 60%; margin-bottom: 20px;"></div>
                <div class="skeleton skeleton-text" style="width: 100%;"></div>
                <div class="skeleton skeleton-text" style="width: 100%;"></div>
                <div class="skeleton skeleton-text" style="width: 100%;"></div>
                <div class="skeleton skeleton-text" style="width: 80%;"></div>
            `;
    },
  };

  // Initialize when document is ready
  $(document).ready(function () {
    SkeletonScreen.init();
  });

  // Expose to global scope for external use
  window.SkeletonScreen = SkeletonScreen;
})(jQuery);
