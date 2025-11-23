/**
 * Cart Drawer JavaScript
 * Handles AJAX add-to-cart, cart drawer interactions, and quantity updates
 */

(function($) {
    'use strict';

    const CartDrawer = {
        init: function() {
            this.bindEvents();
        },

        bindEvents: function() {
            // Add to cart button click
            $(document).on('click', '.ajax-add-to-cart', this.addToCart);
            
            // Open/close cart drawer
            $(document).on('click', '.cart-drawer-close, .cart-drawer-overlay', this.closeDrawer);
            
            // Remove item from cart
            $(document).on('click', '.cart-item-remove', this.removeItem);
            
            // Quantity buttons
            $(document).on('click', '.qty-btn', this.updateQuantity);
            
            // Prevent drawer close when clicking inside
            $(document).on('click', '.cart-drawer', function(e) {
                e.stopPropagation();
            });
        },

        addToCart: function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productId = $button.data('product-id');
            const originalText = $button.text();
            
            // Disable button and show loading
            $button.prop('disabled', true).text('Adding...');
            
            $.ajax({
                url: cartDrawerAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'add_to_cart',
                    product_id: productId,
                    quantity: 1,
                    nonce: cartDrawerAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count if you have a cart icon
                        CartDrawer.updateCartCount(response.data.cart_count);
                        
                        // Update cart drawer content
                        $('.cart-drawer-body').html(response.data.cart_html);
                        
                        // Open cart drawer
                        CartDrawer.openDrawer();
                        
                        // Show success message
                        CartDrawer.showNotification('Product added to cart!', 'success');
                    } else {
                        CartDrawer.showNotification(response.data.message || 'Failed to add product', 'error');
                    }
                },
                error: function() {
                    CartDrawer.showNotification('An error occurred. Please try again.', 'error');
                },
                complete: function() {
                    // Re-enable button
                    $button.prop('disabled', false).text(originalText);
                }
            });
        },

        removeItem: function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const cartItemKey = $button.data('cart-item-key');
            const $item = $button.closest('.cart-drawer-item');
            
            // Add removing class for animation
            $item.addClass('removing');
            
            $.ajax({
                url: cartDrawerAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'remove_cart_item',
                    cart_item_key: cartItemKey,
                    nonce: cartDrawerAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count
                        CartDrawer.updateCartCount(response.data.cart_count);
                        
                        // Update cart drawer content
                        $('.cart-drawer-body').html(response.data.cart_html);
                        
                        // Show notification
                        CartDrawer.showNotification('Item removed from cart', 'success');
                    } else {
                        $item.removeClass('removing');
                        CartDrawer.showNotification(response.data.message || 'Failed to remove item', 'error');
                    }
                },
                error: function() {
                    $item.removeClass('removing');
                    CartDrawer.showNotification('An error occurred. Please try again.', 'error');
                }
            });
        },

        updateQuantity: function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const action = $button.data('action');
            const $item = $button.closest('.cart-drawer-item');
            const cartItemKey = $item.data('cart-item-key');
            const $input = $item.find('.qty-input');
            let currentQty = parseInt($input.val());
            
            // Calculate new quantity
            let newQty = action === 'increase' ? currentQty + 1 : currentQty - 1;
            
            // Prevent negative quantities
            if (newQty < 1) {
                newQty = 1;
            }
            
            // Update input value immediately for better UX
            $input.val(newQty);
            
            $.ajax({
                url: cartDrawerAjax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_cart_quantity',
                    cart_item_key: cartItemKey,
                    quantity: newQty,
                    nonce: cartDrawerAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update cart count
                        CartDrawer.updateCartCount(response.data.cart_count);
                        
                        // Update cart drawer content
                        $('.cart-drawer-body').html(response.data.cart_html);
                    } else {
                        // Revert to original quantity
                        $input.val(currentQty);
                        CartDrawer.showNotification('Failed to update quantity', 'error');
                    }
                },
                error: function() {
                    // Revert to original quantity
                    $input.val(currentQty);
                    CartDrawer.showNotification('An error occurred. Please try again.', 'error');
                }
            });
        },

        openDrawer: function() {
            $('body').addClass('cart-drawer-open');
            $('.cart-drawer').addClass('open');
            $('.cart-drawer-overlay').addClass('active');
        },

        closeDrawer: function() {
            $('body').removeClass('cart-drawer-open');
            $('.cart-drawer').removeClass('open');
            $('.cart-drawer-overlay').removeClass('active');
        },

        updateCartCount: function(count) {
            // Update cart count in header if you have one
            $('.cart-count, .cart-icon-count').text(count);
            
            // Update cart badge
            if (count > 0) {
                $('.cart-count, .cart-icon-count').show();
            } else {
                $('.cart-count, .cart-icon-count').hide();
            }
        },

        showNotification: function(message, type) {
            // Remove existing notifications
            $('.cart-notification').remove();
            
            // Create notification element
            const $notification = $('<div class="cart-notification cart-notification-' + type + '">' + message + '</div>');
            
            // Append to body
            $('body').append($notification);
            
            // Trigger animation
            setTimeout(function() {
                $notification.addClass('show');
            }, 10);
            
            // Auto remove after 3 seconds
            setTimeout(function() {
                $notification.removeClass('show');
                setTimeout(function() {
                    $notification.remove();
                }, 300);
            }, 3000);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        CartDrawer.init();
    });

})(jQuery);
