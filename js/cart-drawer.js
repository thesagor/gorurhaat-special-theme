/**
 * Cart Drawer - Lightweight Version
 */
(function($) {
    'use strict';

    // Add to cart
    $(document).on('click', '.ajax-add-to-cart', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var productId = $btn.data('product-id');
        var originalText = $btn.text();
        
        $btn.prop('disabled', true).text('Adding...');
        
        $.post(cartDrawerAjax.ajax_url, {
            action: 'add_to_cart',
            product_id: productId,
            quantity: 1,
            nonce: cartDrawerAjax.nonce
        }, function(res) {
            if (res.success) {
                $('.cart-drawer-body').html(res.data.cart_html);
                openDrawer();
                showNotice('Added to cart!', 'success');
            } else {
                showNotice('Failed to add', 'error');
            }
            $btn.prop('disabled', false).text(originalText);
        });
    });

    // Remove item
    $(document).on('click', '.cart-item-remove', function(e) {
        e.preventDefault();
        
        $.post(cartDrawerAjax.ajax_url, {
            action: 'remove_cart_item',
            cart_item_key: $(this).data('cart-item-key'),
            nonce: cartDrawerAjax.nonce
        }, function(res) {
            if (res.success) {
                $('.cart-drawer-body').html(res.data.cart_html);
                showNotice('Item removed', 'success');
            }
        });
    });

    // Update quantity
    $(document).on('click', '.qty-btn', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.cart-drawer-item');
        var $input = $item.find('.qty-input');
        var qty = parseInt($input.val());
        var newQty = $(this).data('action') === 'increase' ? qty + 1 : Math.max(1, qty - 1);
        
        $input.val(newQty);
        
        $.post(cartDrawerAjax.ajax_url, {
            action: 'update_cart_quantity',
            cart_item_key: $item.data('cart-item-key'),
            quantity: newQty,
            nonce: cartDrawerAjax.nonce
        }, function(res) {
            if (res.success) {
                $('.cart-drawer-body').html(res.data.cart_html);
            }
        });
    });

    // Close drawer
    $(document).on('click', '.cart-drawer-close, .cart-drawer-overlay', closeDrawer);

    // Prevent close when clicking inside drawer
    $(document).on('click', '.cart-drawer', function(e) {
        e.stopPropagation();
    });

    // Open drawer
    function openDrawer() {
        $('body').addClass('cart-drawer-open');
        $('.cart-drawer').addClass('open');
        $('.cart-drawer-overlay').addClass('active');
    }

    // Close drawer
    function closeDrawer() {
        $('body').removeClass('cart-drawer-open');
        $('.cart-drawer').removeClass('open');
        $('.cart-drawer-overlay').removeClass('active');
    }

    // Show notification
    function showNotice(msg, type) {
        var $notice = $('<div class="cart-notice cart-notice-' + type + '">' + msg + '</div>');
        $('body').append($notice);
        setTimeout(function() { $notice.addClass('show'); }, 10);
        setTimeout(function() { $notice.removeClass('show'); setTimeout(function() { $notice.remove(); }, 300); }, 2000);
    }

})(jQuery);
