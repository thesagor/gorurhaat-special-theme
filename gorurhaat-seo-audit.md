# SEO Audit Report — gorurhaat.com

**Site:** https://gorurhaat.com
**Audit date:** 2026-05-04
**Data sources:** Google Search Console (Overview, Insights, Search Results screenshots, last 3 months) + live crawl of homepage, robots.txt, sitemap.xml
**Platform:** WordPress + WooCommerce

---

## 1. Executive Summary

Gorurhaat is gaining real organic traction — **373 clicks / 2,420 impressions in 3 months**, with a healthy **CTR of 15.4%** and an average position of **5.4**. The site already ranks for high-intent commercial queries like *"cow price in bangladesh 2026"*, *"online gorur haat"*, and *"qurbani cow price in bangladesh"*.

But the data shows three serious problems that are **directly capping sales and visitors**:

| # | Problem | Impact |
|---|---------|--------|
| 1 | **Only 19 pages indexed** out of **587,191 discovered** by Google | 99.99% of the site is invisible in search |
| 2 | **4 URLs with Poor Core Web Vitals on mobile**, 0 "Good" | Mobile users (most of your audience) bounce before buying |
| 3 | **Zero structured data / enhancements** | No rich snippets → lower CTR, no product/price/rating in results |

Add to that a **broken robots.txt** (`Disalow` typo + a junk sitemap URL), a **missing meta description**, **no Open Graph tags**, and **weak image alt text**, and the picture is clear: the site has demand but is leaking visibility, trust, and conversions at every step.

If the fixes below are implemented, a realistic 3-month outlook is **2–4× clicks** and a meaningful lift in conversion rate from the same traffic.

---

## 2. What Search Console Tells Us

### 2.1 Performance (last 3 months)
- **Clicks:** 373 (trending up — 75 in the last week)
- **Impressions:** 2,420
- **Average CTR:** 15.4% (well above the ~3–5% e‑commerce average — your titles work)
- **Average position:** 5.4 (page 1 — close to the top 3)

**Top queries by clicks:**

| Query | Clicks | Impressions | CTR (calc.) |
|---|---:|---:|---:|
| cow price in bangladesh 2026 | 36 | 198 | 18.2% |
| online gorur haat | 23 | 41 | 56.1% |
| gorur haat | 7 | 88 | 8.0% |
| qurbani cow price in bangladesh | 7 | 30 | 23.3% |
| gorur hat | 5 | 75 | 6.7% |
| online cow hut bd price list | 5 | 21 | 23.8% |
| qurbani online | 5 | 12 | 41.7% |
| online cow | 4 | 33 | 12.1% |
| online cattle market | 4 | 13 | 30.8% |
| buy cow online | 4 | 10 | 40.0% |

**Branded vs non-branded:** ~9% branded, **91% non-branded** → growth is coming from generic search, not just people searching your name. This is excellent — there is real upside.

**Top countries:** Bangladesh (≈90%), Saudi Arabia, Canada, UK, Australia (Bangladeshi diaspora — Qurbani buyers).

### 2.2 Indexing — the biggest single problem
- **Indexed pages:** 19
- **Not indexed pages:** **587,191**

A WooCommerce store with 19 indexed pages cannot scale sales. The 587K number almost certainly comes from one of these (very common WordPress/WooCommerce issues):
- Faceted/filter URLs (`?orderby=`, `?filter_*=`, `?min_price=…`) generating near-infinite combinations
- Search result URLs (`/?s=…`)
- Tag/attribute archives without value
- Pagination loops
- Session or `add-to-cart=` URLs

Google has classified them as *"Discovered – currently not indexed"* or *"Crawled – currently not indexed"*, both of which mean **Google doesn't think the URLs are worth indexing**. This wastes crawl budget and starves your real product pages of attention.

### 2.3 Experience (Core Web Vitals)
- Mobile: **0 Good · 0 Needs Improvement · 4 Poor**
- Desktop: **No data** (not enough traffic samples)
- HTTPS: 4 valid, 0 issues ✅

Mobile is where 90%+ of your buyers are. Four URLs flagged Poor likely represent URL *patterns* (e.g. product page template, category template) — fixing one template fixes many pages.

### 2.4 Enhancements
- **No structured data of any kind detected.** No Product, Breadcrumb, Organization, FAQ, Review, or LocalBusiness schema → you cannot win rich results (price, stock, ratings, breadcrumbs in SERPs).

---

## 3. On-Site / Technical Findings

### 3.1 robots.txt (broken)
Current file:
```
User-agent:*
Disalow:/wp-admin/
Sitemap:https://gorurhaat.com/sitemap.xml
Sitemap:https://gorurhaat.com/?sitemap.xml
```
Problems:
1. **`Disalow:` is a typo** — Google ignores unknown directives, so `/wp-admin/` is **not** actually blocked.
2. **`?sitemap.xml`** is not a valid sitemap URL — it should be removed.
3. Missing space after `User-agent:` and `Disalow:` is tolerated but sloppy.
4. No rules to control faceted/filter URLs, search results, or `add-to-cart` links — directly contributing to the 587K junk URLs.

### 3.2 Meta data
- **Title tag:** Present — *"Gorurhaat – First online gorurhaat in Bangladesh"* (OK, but generic; doesn't include top query *"cow price in Bangladesh"*).
- **Meta description:** **Missing on homepage.**
- **Open Graph / Twitter Card tags:** Missing → poor link previews on Facebook, WhatsApp, Messenger (the channels Bangladeshi buyers actually share on).
- **Canonical tag:** Not detected on homepage — risk of duplicate content (HTTP/HTTPS, www/non-www, trailing slash, query strings).

### 3.3 Heading structure
Multiple H1‑level headings on the homepage. There should be **exactly one H1** per page describing the page's purpose; everything else should step down (H2 → H3).

### 3.4 Images
- Product/hero images largely **missing descriptive alt text** (e.g. *"Deshi cow"* with no alt, instead of *"Deshi gorur — 350kg qurbani cow, price ৳1,20,000"*).
- No evidence of `loading="lazy"`, `width`/`height` attributes, or modern formats (WebP/AVIF) → contributes to Poor Core Web Vitals.

### 3.5 Internal linking
Links exist (categories, "View Details", blog), but the structure is shallow. Important product/category pages are not linked from the homepage in a way that signals priority to Google.

### 3.6 Language / international
- No `<html lang="bn">` tag confirmed.
- No `hreflang` (you have UK/Saudi/Canada traffic — Bengali diaspora — worth targeting).

---

## 4. Why Sales & Visitors Are Capped

Putting the data together, the funnel breaks like this:

1. **Discovery limit** — Google has only 19 of your URLs in its index, so 99% of your products cannot be found via search at all.
2. **Crawl waste** — 587K low-value URLs are eating Google's crawl budget instead of your product pages.
3. **CTR ceiling** — With no rich snippets (price, stock, breadcrumb), your listings look plainer than competitors who do have them, even when you outrank them.
4. **Mobile drop-off** — Poor Core Web Vitals on mobile = slow pages = users leave before "Order Now". Google also demotes slow mobile pages.
5. **Social leakage** — Missing Open Graph tags means shared product links on WhatsApp/Facebook show as ugly text, killing click-through from your strongest informal channels.
6. **Trust gap** — No Organization / LocalBusiness schema, no reviews schema, no clear brand entity in Google's eyes.

---

## 5. Action Plan — Prioritized

### Priority 1 — Fix this week (biggest impact, lowest effort)

**1.1 Fix robots.txt**
Replace the file at `https://gorurhaat.com/robots.txt` with:
```
User-agent: *
Disallow: /wp-admin/
Disallow: /cart/
Disallow: /checkout/
Disallow: /my-account/
Disallow: /*?add-to-cart=
Disallow: /*?orderby=
Disallow: /*?filter_*
Disallow: /*?min_price=
Disallow: /*?max_price=
Disallow: /?s=
Allow: /wp-admin/admin-ajax.php

Sitemap: https://gorurhaat.com/sitemap.xml
```
This single change should drastically shrink the 587K "not indexed" number over a few weeks.

**1.2 Add a homepage meta description**
Example (≤155 chars, includes top query):
> বাংলাদেশের প্রথম অনলাইন গরুর হাট — কোরবানির গরু, ডেইরি গরু ও খামারের সমাধান। নিরাপদ ডেলিভারি, স্বচ্ছ মূল্য। Cow price in Bangladesh 2026.

**1.3 Improve the homepage title**
Current title doesn't carry your #1 query. Suggested:
> Online Gorur Haat — Cow Price in Bangladesh 2026 | Qurbani & Dairy Cow

**1.4 Add Open Graph + Twitter Card tags** (every page) using a plugin like Yoast SEO or RankMath — both are free and handle this automatically once configured.

**1.5 Submit the corrected sitemap in GSC** and request re-indexing of your top 20 product/category URLs via URL Inspection.

### Priority 2 — Within 2 weeks (sales & CTR levers)

**2.1 Install structured data** (Yoast/RankMath does most of this automatically; verify with [Rich Results Test](https://search.google.com/test/rich-results)):
- `Product` schema on every product page (name, image, price, availability, SKU)
- `BreadcrumbList` schema sitewide
- `Organization` + `LocalBusiness` schema in the footer (you already have address + phone — use them)
- `Review` / `AggregateRating` schema if you collect reviews (start collecting them — see 2.4)

**2.2 Fix Core Web Vitals on mobile**
- Convert hero/product images to **WebP**
- Add `loading="lazy"` to all below-the-fold images
- Always specify `width` and `height` to stop layout shift (CLS)
- Enable a caching plugin (LiteSpeed Cache, WP Rocket, or W3 Total Cache)
- Use a CDN (Cloudflare free tier is enough)
- Test with [PageSpeed Insights](https://pagespeed.web.dev/) — target LCP < 2.5s on 4G

**2.3 Rewrite product page titles + meta descriptions** to include intent keywords:
- *"Deshi Qurbani Cow — 350kg | Price ৳1,20,000 | Free Dhaka Delivery"*

**2.4 Start collecting reviews** (Google Reviews + on-site WooCommerce reviews). Reviews → AggregateRating in SERPs → higher CTR + trust.

### Priority 3 — Within 1 month (growth)

**3.1 Build content around proven queries.** Your data already shows what people search for. Create dedicated pages/blog posts for:
- *Cow price in Bangladesh 2026* (a definitive, updated price list page — currently your #1 query)
- *Qurbani cow price in Bangladesh* (with weight ranges and breeds)
- *Online cow hut BD price list*
- *Buy cow online — how it works* (delivery, payment, guarantees)
- Breed guides: Deshi, Brahma, Sahiwal, Frisian, etc.

These map directly to the queries already driving impressions — small ranking improvements there will compound.

**3.2 Diaspora targeting (Saudi/UK/Canada).** You already have organic traffic from these countries — Bangladeshi expats sending Qurbani cows home. Add:
- An English landing page: *"Qurbani Cow Delivery in Bangladesh from Abroad"*
- WhatsApp + international payment options visible
- `hreflang="en"` and `hreflang="bn"` tags

**3.3 Internal linking pass.** From the homepage, link to:
- Top 5 product categories
- "Cow price 2026" hub page
- Blog hub
And from every product page, link to 3 related products + the parent category.

**3.4 Backlinks.** You're at position 5.4 — backlinks from Bangladeshi news sites, livestock blogs, Qurbani guides, and Facebook community pages will push you to position 1–3, where CTR roughly **doubles**.

### Priority 4 — Ongoing
- Re-check GSC weekly for new "Not indexed" reasons.
- Monitor Core Web Vitals monthly.
- Refresh the "Cow price 2026" page with current weekly prices — freshness signals + a reason to come back.
- Build a basic email/SMS list from buyers — direct sales without paying Google.

---

## 6. Expected Outcome (3 months, if Priority 1 + 2 are done)

| Metric | Today | Realistic target |
|---|---:|---:|
| Indexed pages | 19 | 200–500 (real product/category pages) |
| Not-indexed junk URLs | 587,191 | < 50,000 (and shrinking) |
| Mobile Core Web Vitals "Good" | 0 | ≥ 75% of URLs |
| Avg. position | 5.4 | 3.0–4.0 |
| Clicks / month (current run-rate ~125) | ~125 | **300–500** |
| CTR on rich-snippet pages | 15.4% | 18–22% |
| Conversion rate | (unknown — install GA4 e-commerce events) | +20–40% from speed + trust signals |

---

## 7. Things to Set Up so You Can Measure This

1. **Confirm GA4 is installed** with e-commerce events (`view_item`, `add_to_cart`, `begin_checkout`, `purchase`) — without this you can't tell which queries drive sales.
2. **Link GA4 ↔ Search Console** in GA4 Admin → Product Links.
3. **Install Yoast SEO or RankMath** (free) to automate titles, meta, OG, schema, sitemap, breadcrumbs.
4. **Install a caching + image plugin** (LiteSpeed Cache + ShortPixel, or WP Rocket + Imagify).
5. **Add Cloudflare** (free) in front of the domain.

---

## 8. Quick Checklist (printable)

- [ ] Fix `Disalow` → `Disallow` in robots.txt and add filter/cart disallows
- [ ] Remove the bogus `?sitemap.xml` line
- [ ] Add homepage meta description
- [ ] Rewrite homepage title to include "cow price in Bangladesh 2026"
- [ ] Install Yoast or RankMath; enable OG + schema
- [ ] Add Product, Breadcrumb, Organization, LocalBusiness schema
- [ ] Convert product images to WebP, lazy-load, set width/height
- [ ] Install caching plugin + Cloudflare
- [ ] Re-test mobile in PageSpeed Insights — fix until "Good"
- [ ] Submit clean sitemap in GSC, request indexing for top 20 URLs
- [ ] Build "Cow price in Bangladesh 2026" landing page
- [ ] Start collecting Google + on-site reviews
- [ ] Add English landing page for diaspora buyers
- [ ] Confirm GA4 + e-commerce events installed and linked to GSC

---

*Prepared from Google Search Console screenshots (Overview, Insights, Search Results — 3-month window) and a live crawl of gorurhaat.com on 2026-05-04.*
