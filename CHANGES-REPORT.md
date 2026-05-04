# Gorurhaat Theme — Change Report
**Date:** 2026-05-04  
**Based on:** SEO Audit + professional code & UX review

---

## Files Changed

| File | Type | Summary |
|------|------|---------|
| `inc/seo-optimization.php` | Rewrite | SEO, schema, robots.txt, lazy load, breadcrumbs |
| `inc/woocommerce-ux.php` | New | Cart/checkout/blog UX improvements |
| `inc/single-product.php` | Update | Reviews re-enabled, related products improved |
| `widgets/blog-card-widget.php` | Update | Read More, lazy load, category link, search submit |
| `css/custom-styles.css` | Append | Trust strip, delivery notice, WhatsApp CTA, cart/checkout styles |
| `css/single-product-styles.css` | Append | Product trust bar, WhatsApp CTA button upgrade |
| `functions.php` | Update | Added `woocommerce-ux.php` include |

---

## 1. SEO Fixes (`inc/seo-optimization.php` — full rewrite)

### Homepage title — fixed to target #1 search query
**Before:**  
`Gorurhaat - Bangladesh's Premier Livestock & Agricultural Marketplace`

**After:**  
`Online Gorur Haat – Cow Price in Bangladesh 2026 | Qurbani & Dairy Cow | Gorurhaat`

**Why:** The audit showed "cow price in bangladesh 2026" drives 36 clicks/198 impressions — the highest of any query. The old title didn't contain it at all.

### Homepage meta description — added Bengali + key query
**Before:** English-only, didn't include target queries.

**After:**  
`বাংলাদেশের প্রথম অনলাইন গরুর হাট – কোরবানির গরু, ডেইরি গরু ও খামারের সমাধান। নিরাপদ ডেলিভারি, সঙ্গত মূল্য। Cow price in Bangladesh 2026.`

**Why:** Buyers are Bengali-speaking. A Bengali meta description matches their language and signals relevance to Google's language detection.

### Schema moved from `wp_footer` → `wp_head`
JSON-LD structured data is now output in `<head>`. Google processes head-based schema before footer, reducing the risk of crawl budget waste.

### New structured data added
- **LocalBusiness** schema on homepage with telephone, address, payment methods
- **BreadcrumbList** on product pages, category pages, and blog posts
- **BlogPosting** schema on single blog posts
- **AggregateRating** on product pages (when reviews exist — reviews are now re-enabled)
- **WebSite** with SearchAction sitewide

### robots.txt — fixed via WordPress filter
The `robots_txt` WordPress filter now generates a clean robots.txt that:
- Fixes the `Disalow` typo (was blocking nothing)
- Blocks `/cart/`, `/checkout/`, `/my-account/`
- Blocks `?add-to-cart=`, `?orderby=`, `?filter_*`, `?min_price=`, `?max_price=`, `?s=` (the source of 587,191 junk URLs)
- Removes the bogus `?sitemap.xml` line
- Points to a single clean sitemap

**Action required:** Delete any physical `robots.txt` file from your server root for this to take effect.

### Image lazy loading added
All WordPress attachment images now get `loading="lazy"` automatically via `wp_get_attachment_image_attributes`. WooCommerce product images also get lazy loading + descriptive alt text if missing.

**Why:** Missing lazy loading is a direct cause of Poor Core Web Vitals on mobile.

### Removed bad product title filter
The old `gorurhaat_optimize_product_title` filter was appending `" - Livestock at Gorurhaat"` to every product title in *all* contexts — navigation menus, breadcrumbs, related product names, etc. This has been removed.

### Breadcrumbs — now actually hooked
The `gorurhaat_breadcrumbs()` function existed but was never hooked. It is now hooked to `woocommerce_before_main_content` and fires on all WooCommerce pages.

### Open Graph locale corrected
`og:locale` changed from `en_US` to `bn_BD` (primary audience is Bangladeshi). English remains as `og:locale:alternate`.

### hreflang — diaspora targeting
Added proper hreflang tags: `bn-BD`, `en-BD`, `en`, `x-default`. This helps capture Bangladeshi diaspora traffic from Saudi Arabia, UK, Canada, and Australia.

### HTML lang attribute
`<html lang="bn">` is now added when the WordPress language attribute is not set. This signals to Google that the content is in Bengali.

### Canonical URL — pagination-aware
Canonical URLs now correctly reflect the paginated page (e.g. `/shop/page/2/`) instead of always pointing to page 1.

---

## 2. WooCommerce UX (`inc/woocommerce-ux.php` — new file)

### Product page — trust bar
Four trust signals shown below the price on every product page:
- সারা বাংলাদেশে ডেলিভারি
- নিরাপদ পেমেন্ট
- যাচাইকৃত পণ্য
- 24/7 সাপোর্ট

### Cart page — trust strip
A green trust strip at the top of the cart showing four guarantees. Reduces cart abandonment.

### Cart page — free delivery progress notice
Shows a contextual message:
- Below ৳50,000: "Add ৳X more for free delivery"
- Above ৳50,000: "You qualify for free delivery!"

The threshold can be changed via the `gorurhaat_free_delivery_threshold` filter.

### Cart page — WhatsApp help link
"অর্ডার করতে সমস্যা হচ্ছে?" with a WhatsApp button below the cart table. Many Bangladeshi buyers prefer WhatsApp ordering.

### Empty cart page — custom CTA
Replaced the plain "Your cart is empty" text with:
- Friendly Bengali message
- "কেনাকাটা শুরু করুন" button → shop page
- "WhatsApp-এ অর্ডার করুন" button → direct WhatsApp

### Checkout page — trust badges
Three trust badges above the checkout form: Secure Payment, Fast Delivery, Verified Sellers.

### Checkout page — WhatsApp alternative
"অথবা সরাসরি — WhatsApp-এ অর্ডার করুন" shown above the form. Many customers in Bangladesh prefer WhatsApp for large purchases like cattle.

### Order received page — next steps + WhatsApp follow-up
After order confirmation, customers see:
1. We will contact you soon
2. Video of the product will be sent before delivery
3. WhatsApp for any questions
Plus a direct WhatsApp link pre-filled with the order number.

---

## 3. Single Product Page (`inc/single-product.php`)

### Reviews tab re-enabled
The `woocommerce_product_tabs` filter that hid reviews has been commented out. Reviews are now visible.

**Why:** The SEO audit recommends collecting reviews to enable `AggregateRating` rich snippets in Google Search, which can increase CTR by 15–20%. Google cannot show star ratings in search results without reviews.

### Related products — accessibility improved
- `role="region"` and `aria-label` added to the slider
- Navigation buttons have `aria-label`
- Product images get descriptive `alt` text with `– Gorurhaat` suffix
- Inline `<script>` replaced with an IIFE that waits for `DOMContentLoaded`

### Social share buttons
- WhatsApp share moved to first position (most used channel in Bangladesh)
- All buttons get `aria-label` for accessibility
- `rel="noopener noreferrer"` added to all external links

---

## 4. Blog Card Widget (`widgets/blog-card-widget.php`)

### Image lazy loading
`loading="lazy"` + `width="600" height="440"` added to card images. This prevents layout shift (CLS) and defers off-screen images.

### "Read More" link
Each card now has a "Read More →" link at the bottom with an accessible `aria-label` including the post title.

### Category as clickable link
Category label is now a proper `<a href>` linking to the category archive page — better for both UX and internal linking.

### Reading time estimate
Calculates and shows estimated reading time (e.g. "5 min read") based on 200 words/minute.

### Search form improvements
- `type="search"` (correct semantic type)
- Added a visible submit button with a magnifier icon
- Input values are properly sanitized with `wp_unslash` before `sanitize_text_field`
- `role="search"` on the form element
- Removed `https://picsum.photos` placeholder (external request, bad for performance/SEO)

---

## 5. CSS Additions

### `css/custom-styles.css` (appended)
- `.gorurhaat-btn-primary` — reusable primary action button
- `.gorurhaat-whatsapp-cta-btn` — full-width green WhatsApp CTA button
- `.gorurhaat-trust-strip` — cart page trust strip (green bar)
- `.gorurhaat-delivery-notice` — cart delivery progress bar (green/yellow states)
- `.gorurhaat-cart-whatsapp` — WhatsApp help section in cart
- `.gorurhaat-checkout-trust` — checkout trust badges grid
- `.gorurhaat-checkout-whatsapp` — checkout WhatsApp alternative
- `.gorurhaat-empty-cart` — full-page empty cart design
- `.gorurhaat-order-success` — order received next-steps card
- `.blog-read-more` — animated "Read More →" link
- `.blog-search-submit` — search submit button
- Full mobile responsive styles for all of the above

### `css/single-product-styles.css` (appended)
- `.gorurhaat-product-trust` — 4-item trust bar below price
- `.product-trust-item` — individual trust item with icon
- WhatsApp button upgrade: on product pages the small circle (`whatsapp-button-circle`) is overridden to a full-width CTA with Bengali label text "অর্ডার করুন WhatsApp-এ"

---

## What Still Needs to Be Done (not theme code)

| Action | Priority | Owner |
|--------|----------|-------|
| Delete physical `robots.txt` from server root | P1 | Server/hosting |
| Submit clean sitemap in Google Search Console | P1 | You |
| Request re-indexing of top 20 URLs in GSC URL Inspection | P1 | You |
| Install Cloudflare free tier in front of domain | P2 | Hosting |
| Install LiteSpeed Cache or W3 Total Cache | P2 | WordPress admin |
| Convert product/hero images to WebP format | P2 | Media library |
| Build "Cow price in Bangladesh 2026" hub page | P2 | Content |
| Set up Google Reviews widget on site | P3 | Marketing |
| Add English landing page for diaspora buyers | P3 | Content |
| Install GA4 with e-commerce events | P3 | Analytics |

---

*Generated by Claude Code on 2026-05-04*
