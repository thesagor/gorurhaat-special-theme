# 🎨 Skeleton Screen - Quick Reference

## Files Created
✅ `inc/skeleton-screen.css` - Styles & animations  
✅ `inc/skeleton-screen.php` - PHP templates & functions  
✅ `js/skeleton-screen.js` - JavaScript handlers  
✅ `SKELETON-SCREEN-GUIDE.md` - Full documentation  
✅ `skeleton-demo.html` - Interactive demo  

## Quick Usage

### PHP Functions
```php
render_skeleton_product_grid( 8 );      // Product grid
render_skeleton_single_product();        // Single product
render_skeleton_post_list( 5 );         // Blog posts
render_skeleton_cart( 3 );              // Cart items
render_skeleton_category_grid( 6 );     // Categories
render_skeleton_slider( 5 );            // Sliders
render_skeleton_widget( 4 );            // Widgets
```

### Shortcodes
```
[skeleton type="product-grid" count="8"]
[skeleton type="single-product"]
[skeleton type="post-list" count="5"]
[skeleton type="cart" count="3"]
```

### JavaScript
```javascript
// Show skeleton
SkeletonScreen.showSkeleton('product-grid', $('#container'), 8);

// Hide skeleton
SkeletonScreen.hideSkeleton();
```

## Auto-Integration
✅ Shop pages  
✅ Product pages  
✅ Cart page  
✅ Blog/archive pages  

## View Demo
Open: `skeleton-demo.html` in browser

## Full Guide
Read: `SKELETON-SCREEN-GUIDE.md`
