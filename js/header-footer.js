(function () {
	'use strict';

	/* ---- Elements ---- */
	var header     = document.getElementById('gh-header');
	var hamburger  = document.getElementById('gh-hamburger');
	var mobileMenu = document.getElementById('gh-mobile-menu');
	var mobileClose = document.getElementById('gh-mobile-close');
	var overlay    = document.getElementById('gh-overlay');
	var backToTop  = document.getElementById('gh-back-to-top');

	/* ==========================================
	   STICKY HEADER (add scroll class)
	   ========================================== */
	if (header) {
		window.addEventListener('scroll', function () {
			if (window.scrollY > 10) {
				header.classList.add('gh-scrolled');
			} else {
				header.classList.remove('gh-scrolled');
			}
		}, { passive: true });
	}

	/* ==========================================
	   MOBILE MENU
	   ========================================== */
	function openMobileMenu() {
		if (!mobileMenu || !overlay || !hamburger) return;
		mobileMenu.classList.add('is-open');
		mobileMenu.setAttribute('aria-hidden', 'false');
		overlay.classList.add('is-active');
		overlay.setAttribute('aria-hidden', 'false');
		hamburger.classList.add('is-active');
		hamburger.setAttribute('aria-expanded', 'true');
		hamburger.setAttribute('aria-label', 'মেনু বন্ধ করুন');
		document.body.style.overflow = 'hidden';
	}

	function closeMobileMenu() {
		if (!mobileMenu || !overlay || !hamburger) return;
		mobileMenu.classList.remove('is-open');
		mobileMenu.setAttribute('aria-hidden', 'true');
		overlay.classList.remove('is-active');
		overlay.setAttribute('aria-hidden', 'true');
		hamburger.classList.remove('is-active');
		hamburger.setAttribute('aria-expanded', 'false');
		hamburger.setAttribute('aria-label', 'মেনু খুলুন');
		document.body.style.overflow = '';
	}

	if (hamburger)   hamburger.addEventListener('click', openMobileMenu);
	if (mobileClose) mobileClose.addEventListener('click', closeMobileMenu);
	if (overlay)     overlay.addEventListener('click', closeMobileMenu);

	/* Close on Escape key */
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('is-open')) {
			closeMobileMenu();
			if (hamburger) hamburger.focus();
		}
	});

	/* Close mobile menu when a link inside it is clicked */
	if (mobileMenu) {
		mobileMenu.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				if (window.innerWidth < 993) closeMobileMenu();
			});
		});
	}

	/* ==========================================
	   NAV DROPDOWN — keyboard + toggle button
	   ========================================== */
	document.querySelectorAll('.gh-dropdown-toggle').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			var expanded = btn.getAttribute('aria-expanded') === 'true';
			var dropdown = btn.closest('.gh-nav-item').querySelector('.gh-dropdown');

			// Close all others
			document.querySelectorAll('.gh-dropdown-toggle[aria-expanded="true"]').forEach(function (b) {
				if (b !== btn) {
					b.setAttribute('aria-expanded', 'false');
					var d = b.closest('.gh-nav-item').querySelector('.gh-dropdown');
					if (d) d.setAttribute('aria-hidden', 'true');
				}
			});

			btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
			if (dropdown) dropdown.setAttribute('aria-hidden', expanded ? 'true' : 'false');
		});
	});

	/* Close dropdowns when clicking outside */
	document.addEventListener('click', function (e) {
		if (!e.target.closest('.gh-nav-item')) {
			document.querySelectorAll('.gh-dropdown-toggle[aria-expanded="true"]').forEach(function (btn) {
				btn.setAttribute('aria-expanded', 'false');
				var d = btn.closest('.gh-nav-item').querySelector('.gh-dropdown');
				if (d) d.setAttribute('aria-hidden', 'true');
			});
		}
	});

	/* ==========================================
	   BACK TO TOP
	   ========================================== */
	if (backToTop) {
		window.addEventListener('scroll', function () {
			if (window.scrollY > 400) {
				backToTop.classList.add('is-visible');
			} else {
				backToTop.classList.remove('is-visible');
			}
		}, { passive: true });

		backToTop.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	/* ==========================================
	   CART BADGE — sync with WooCommerce events
	   ========================================== */
	document.body.addEventListener('wc_fragments_refreshed', syncCartBadge);
	document.body.addEventListener('wc_fragments_loaded', syncCartBadge);
	document.body.addEventListener('added_to_cart', syncCartBadge);
	document.body.addEventListener('removed_from_cart', syncCartBadge);

	function syncCartBadge() {
		var badge = document.getElementById('gh-cart-badge');
		if (!badge) return;
		// WooCommerce updates .cart-contents-count spans; read from there
		var wcCount = document.querySelector('.cart-contents-count, .woocommerce-cart-link__count');
		if (wcCount) {
			var count = wcCount.textContent.replace(/\D/g, '') || '0';
			badge.textContent = count;
		}
	}

	/* ==========================================
	   CART DRAWER — trigger existing cart drawer
	   ========================================== */
	var cartTrigger = document.getElementById('gh-cart-trigger');
	if (cartTrigger) {
		cartTrigger.addEventListener('click', function () {
			// Open the existing cart drawer (from inc/cart-drawer.php)
			var existingDrawer = document.querySelector('.cart-drawer, #cart-drawer, [data-cart-drawer]');
			if (existingDrawer) {
				existingDrawer.classList.toggle('is-open');
				existingDrawer.classList.toggle('active');
				document.body.classList.toggle('cart-drawer-open');
			} else {
				// Fallback: navigate to cart page
				var cartLink = document.querySelector('a.cart-contents, a[href*="cart"]');
				if (cartLink) window.location.href = cartLink.href;
			}
		});
	}

})();
