# Product Image Preloader - Documentation

## Overview

The image preloader system displays a loading animation (using `preloader.png`) while product images are loading. This improves user experience by providing visual feedback and preventing layout shifts.

---

## Features

### ✅ **What It Does:**

1. **Shows Loading Animation**: Displays preloader.png while images load
2. **Smooth Transitions**: Fade-in effect when images are ready
3. **Multiple Effects**: Spinner, shimmer, blur, and skeleton loading
4. **Universal Coverage**: Works on all product images throughout the site
5. **Performance Optimized**: Uses lazy loading for better performance
6. **Accessibility**: Respects user's motion preferences

---

## Where It Works

### Product Images Covered:

✅ **Single Product Page**
- Main product gallery images
- Product thumbnails
- Zoom images

✅ **Product Grid/Archive**
- Shop page product images
- Category page images
- Search results

✅ **Product Widgets**
- Product slider images
- Related products
- Upsell products
- Cross-sell products

✅ **Cart & Checkout**
- Cart page thumbnails
- Cart drawer images
- Checkout page images
- Mini cart images

✅ **Custom Widgets**
- Elementor product widgets
- Product grid widget
- Product slider widget

---

## Visual Effects

### 1. **Preloader Image** (preloader.png)
- Displays as background while loading
- Centered in the image container
- 50px × 50px size

### 2. **Spinning Loader**
- Blue circular spinner overlay
- Rotates continuously
- Positioned at center

### 3. **Shimmer Effect**
- Subtle light sweep across image
- Creates premium loading feel
- Infinite loop animation

### 4. **Blur Effect**
- Image starts blurred while loading
- Gradually becomes sharp
- Smooth transition

### 5. **Fade In**
- Image fades in when loaded
- Slight scale animation
- 0.5s duration

### 6. **Skeleton Loading**
- Gradient background pulse
- Modern loading pattern
- Smooth animation

---

## Technical Implementation

### Files Created:

**`/inc/image-preloader.php`**
- Main preloader functionality
- jQuery-based image detection
- Event handlers for AJAX updates
- Lazy loading support

**Enhanced CSS in `/inc/custom-styles.css`**
- Preloader animations
- Visual effects
- Responsive adjustments
- Accessibility features

---

## How It Works

### Loading Sequence:

1. **Image Container Detected**
   - jQuery finds all product images
   - Adds `has-preloader` class to parent

2. **Loading State**
   - Adds `loading` class
   - Shows preloader.png background
   - Displays spinner overlay
   - Hides actual image (opacity: 0)

3. **Image Loads**
   - Detects image load event
   - Removes `loading` class
   - Adds `loaded` class
   - Fades in image

4. **Cleanup**
   - Removes background image
   - Removes spinner
   - Image fully visible

---

## CSS Classes

### Main Classes:

**`.has-preloader`**
- Applied to image parent container
- Sets up preloader environment

**`.loading`**
- Active while image is loading
- Shows all loading effects

**`.loaded`**
- Applied when image is ready
- Triggers fade-in animation

**`.skeleton-loading`**
- Optional skeleton effect
- Can be added manually

---

## Customization

### Change Preloader Image:

Replace `preloader.png` in theme root with your own image.

### Adjust Spinner Color:

In `/inc/custom-styles.css`, find:
```css
.has-preloader.loading::after {
    border-top: 3px solid #3498db; /* Change this color */
}
```

### Modify Animation Speed:

```css
/* Spinner speed */
animation: spin 1s linear infinite; /* Change 1s */

/* Shimmer speed */
animation: shimmer 2s infinite; /* Change 2s */

/* Fade in speed */
animation: fadeIn 0.5s ease-in; /* Change 0.5s */
```

### Disable Specific Effects:

Comment out unwanted effects in `/inc/custom-styles.css`:

```css
/* Disable blur effect */
/*
.has-preloader.loading img {
    filter: blur(5px);
}
*/

/* Disable spinner */
/*
.has-preloader.loading::after {
    ...
}
*/
```

---

## Performance Features

### 1. **Lazy Loading**
```php
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'gorurhaat_add_loading_attribute' );
```
- Images load only when needed
- Reduces initial page load
- Better performance

### 2. **Cached Image Detection**
```javascript
if ($img[0].complete) {
    $img.trigger('load');
}
```
- Instantly shows cached images
- No unnecessary loading state

### 3. **AJAX Support**
```javascript
$(document.body).on('updated_cart_totals', function() {
    // Re-apply preloader
});
```
- Works with cart updates
- Handles dynamic content

---

## Browser Compatibility

✅ **Modern Browsers**
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Opera (latest)

✅ **Mobile Browsers**
- iOS Safari
- Chrome Mobile
- Samsung Internet

✅ **Fallback**
- Gracefully degrades in older browsers
- Images still load normally

---

## Accessibility

### Motion Preferences:

```css
@media (prefers-reduced-motion: reduce) {
    /* Disables animations */
    /* Shows simple opacity transition */
}
```

Users who prefer reduced motion get:
- No spinning animations
- No shimmer effects
- Simple fade transition
- Better accessibility

---

## Troubleshooting

### Preloader Not Showing?

1. **Check if preloader.png exists**
   ```
   /wp-content/themes/hello-theme-child-master/preloader.png
   ```

2. **Clear cache**
   - Browser cache
   - WordPress cache
   - CDN cache

3. **Check jQuery**
   - Ensure jQuery is loaded
   - Check browser console for errors

### Images Loading Too Fast?

This is actually good! It means:
- Images are cached
- Server is fast
- Optimization is working

The preloader will still show briefly for new images.

### Preloader Stays Forever?

Check:
1. Image URL is correct
2. Image file exists
3. No JavaScript errors in console
4. Image isn't blocked by ad blocker

---

## Advanced Usage

### Manual Preloader Application:

```javascript
// Apply to custom images
jQuery('.my-custom-image img').each(function() {
    addImagePreloader(this);
});
```

### Custom Preloader Image Per Section:

```css
/* Different preloader for cart */
.cart-item-image.has-preloader {
    background-image: url('custom-preloader.png');
}
```

### Add Preloader to Non-Product Images:

In `/inc/image-preloader.php`, add:
```javascript
addImagePreloader('.my-custom-selector img');
```

---

## Performance Metrics

### Before Preloader:
- ❌ Layout shifts during image load
- ❌ Blank spaces while loading
- ❌ Poor user experience

### After Preloader:
- ✅ Stable layout
- ✅ Visual feedback
- ✅ Professional appearance
- ✅ Better perceived performance

---

## Best Practices

### 1. **Optimize preloader.png**
- Keep file size small (< 10KB)
- Use PNG with transparency
- Optimize with tools like TinyPNG

### 2. **Optimize Product Images**
- Compress images before upload
- Use appropriate dimensions
- Consider WebP format

### 3. **Use CDN**
- Serve images from CDN
- Faster loading times
- Better global performance

### 4. **Enable Caching**
- Browser caching
- Server-side caching
- Image caching

---

## Examples

### Product Grid:
```
┌─────────────────┐
│   [Preloader]   │  ← Shows while loading
│   [Spinner]     │
└─────────────────┘
        ↓
┌─────────────────┐
│  Product Image  │  ← Fades in when ready
│   (Loaded)      │
└─────────────────┘
```

### Single Product:
```
┌───────────────────────┐
│    [Preloader.png]    │  ← Background image
│    [Spinning Circle]  │  ← Animated spinner
│    [Shimmer Effect]   │  ← Light sweep
└───────────────────────┘
           ↓
┌───────────────────────┐
│   Product Gallery     │  ← Smooth fade-in
│   (Fully Loaded)      │
└───────────────────────┘
```

---

## Future Enhancements

Possible additions:
- [ ] Progress bar for large images
- [ ] Percentage loaded indicator
- [ ] Different preloader styles
- [ ] Custom preloader per product category
- [ ] Animated SVG preloaders

---

## Support

### If You Need Help:

1. Check browser console for errors
2. Verify preloader.png exists
3. Clear all caches
4. Test in incognito mode
5. Check jQuery is loaded

### Common Issues:

**Q: Preloader shows but image never loads**
A: Check image URL and file permissions

**Q: Preloader doesn't show on some images**
A: Add the selector to the JavaScript in image-preloader.php

**Q: Animation is too slow/fast**
A: Adjust animation duration in custom-styles.css

---

## Summary

The image preloader system provides:
- ✅ Professional loading experience
- ✅ Better user experience
- ✅ Stable page layout
- ✅ Visual feedback
- ✅ Performance optimization
- ✅ Accessibility support

All product images across your Gorurhaat marketplace now have smooth, professional loading animations!

---

*Last Updated: December 3, 2025*
*Feature: Product Image Preloader*
*Version: 1.0.0*
