# 🐄 Cattle Slider Widget - Complete Setup Guide

## ✅ What Was Created

A premium Elementor widget for displaying cattle/livestock products with beautiful card designs based on your card.html file.

---

## 📦 Files Created/Modified

### New Files Created:
1. ✅ **`/widgets/cattle-slider-widget.php`** - Main widget file
2. ✅ **`/inc/cattle-custom-fields.php`** - Custom fields for products
3. ✅ **`/widgets/CATTLE-SLIDER-WIDGET-DOCS.md`** - Full documentation
4. ✅ **`/lib/swiper-bundle.min.css`** - Swiper CSS (offline)
5. ✅ **`/lib/swiper-bundle.min.js`** - Swiper JS (offline)

### Files Modified:
1. ✅ **`/inc/elementor-widgets.php`** - Registered new widget
2. ✅ **`/inc/enqueue.php`** - Updated to use local Swiper files
3. ✅ **`/functions.php`** - Added cattle custom fields include
4. ✅ **`/card/card.html`** - Updated to use local Swiper files

---

## 🎨 Widget Features

### Display Fields:
- ✅ **Product Title** - Cattle/livestock name
- ✅ **Breed** - Type/breed information (custom field)
- ✅ **Price** - WooCommerce product price
- ✅ **Live Weight** - Animal weight (custom field)
- ✅ **Badge** - Premium/Featured badge (custom field)
- ✅ **Product Image** - High-quality images with zoom effect
- ✅ **View Details Button** - Links to product page

### Premium Design Features:
- ✅ Smooth hover animations with lift effect
- ✅ Gradient top border on hover
- ✅ Image zoom effect on hover
- ✅ Premium badge with shadow
- ✅ Info grid with hover effects
- ✅ Circular navigation buttons
- ✅ Animated pagination bullets
- ✅ Fully responsive design

---

## 🚀 How to Use

### Step 1: Access the Widget
1. Open any page in **Elementor**
2. Search for **"Cattle Slider"** in the widget panel
3. Drag it to your page
4. You'll find it under **WooCommerce Elements** category

### Step 2: Configure Settings

#### Query Settings:
- Select product categories (optional)
- Set number of products to display
- Choose order by (Date, Title, Price, Random)
- Set order direction (ASC/DESC)

#### Slider Settings:
- **Slides to Show:** Desktop: 4, Tablet: 2, Mobile: 1
- **Space Between:** 30px (adjustable)
- **Autoplay:** Yes (4000ms delay)
- **Infinite Loop:** Yes
- **Pause on Hover:** Yes
- **Animation Speed:** 600ms

#### Navigation:
- **Show Arrows:** Yes
- **Show Pagination:** Yes

#### Style Customization:
- Card background color
- Border radius
- Badge color
- Title, breed, price, weight colors
- Button colors (normal & hover)

### Step 3: Add Custom Fields to Products

The widget requires 3 custom fields for each product:

#### In WooCommerce Product Editor:
1. Edit any product
2. Scroll to **"Product Data"** section
3. Go to **"General"** tab
4. You'll see a new section: **"Cattle Information"**
5. Fill in:
   - **Cattle Breed:** e.g., "Holstein Friesian"
   - **Live Weight:** e.g., "450 kg"
   - **Badge Text:** e.g., "Premium"

#### Custom Fields Reference:
| Field Name | Meta Key | Example | Default |
|------------|----------|---------|---------|
| Cattle Breed | `_cattle_breed` | Holstein Friesian | Premium Breed |
| Live Weight | `_live_weight` | 450 kg | 450 kg |
| Badge Text | `_badge_text` | Premium | Premium |

---

## 📊 Admin Features

The custom fields also add columns to your product list in WordPress admin:

- **Breed Column** - Shows cattle breed
- **Weight Column** - Shows live weight
- **Quick Edit Support** - Edit fields without opening full product editor
- **Sortable Columns** - Sort products by breed or weight

---

## 🎯 Responsive Breakpoints

| Screen Size | Slides Shown | Space Between |
|-------------|--------------|---------------|
| Mobile (0-639px) | 1 | 20px |
| Tablet (640-1023px) | 2 | 20px |
| Desktop (1024-1399px) | 2-3 | 24px |
| Large Desktop (1400px+) | 4 | 30px |

---

## 🎨 Color Scheme (Defaults)

### Card Elements:
- **Background:** #ffffff (White)
- **Border:** #f1f5f9 (Light Gray)
- **Hover Border:** #e0e7ff (Light Blue)

### Typography:
- **Title:** #1a1a2e (Dark Blue)
- **Breed:** #64748b (Gray)
- **Price:** #dc2626 (Red)
- **Weight:** #10b981 (Green)

### Badge:
- **Background:** #10b981 (Green Gradient)
- **Text:** #ffffff (White)

### Button:
- **Normal:** #2563eb (Blue Gradient)
- **Hover:** #1d4ed8 (Darker Blue)

### Navigation:
- **Arrows:** #1a1a2e (Dark)
- **Arrows Hover:** #2563eb (Blue)
- **Pagination Active:** #2563eb (Blue)

---

## 💡 Usage Tips

### Best Practices:
1. **Use high-quality images** - Recommended size: 800x600px or larger
2. **Keep titles concise** - 2-4 words work best
3. **Use consistent weight units** - Always use "kg" or "lbs"
4. **Badge text should be short** - 1-2 words maximum
5. **Test on mobile** - Preview on different devices

### Performance:
- Widget uses **local Swiper files** (no CDN dependency)
- **Lazy loading** for images recommended
- **Optimal number of products:** 8-12 for best performance

### Customization:
- All colors are customizable via Elementor
- CSS can be overridden in your theme
- Widget supports custom CSS classes

---

## 🔧 Technical Details

### Dependencies:
- **WordPress:** 5.8+
- **WooCommerce:** 5.0+
- **Elementor:** 3.0+
- **Swiper.js:** v11 (included locally)

### File Structure:
```
hello-theme-child-master/
├── widgets/
│   ├── cattle-slider-widget.php (NEW)
│   ├── product-slider-widget.php
│   └── CATTLE-SLIDER-WIDGET-DOCS.md (NEW)
├── inc/
│   ├── cattle-custom-fields.php (NEW)
│   ├── elementor-widgets.php (MODIFIED)
│   └── enqueue.php (MODIFIED)
├── lib/
│   ├── swiper-bundle.min.css (NEW)
│   └── swiper-bundle.min.js (NEW)
└── functions.php (MODIFIED)
```

### Widget Registration:
```php
// In inc/elementor-widgets.php
require_once( get_stylesheet_directory() . '/widgets/cattle-slider-widget.php' );
$widgets_manager->register( new \Hello_Elementor_Cattle_Slider_Widget() );
```

### Custom Fields Registration:
```php
// In functions.php
require_once get_stylesheet_directory() . '/inc/cattle-custom-fields.php';
```

---

## 🐛 Troubleshooting

### Widget Not Showing?
1. Clear Elementor cache: **Elementor → Tools → Regenerate CSS**
2. Clear WordPress cache
3. Check if WooCommerce is active

### Custom Fields Not Appearing?
1. Make sure WooCommerce is installed and active
2. Check if `cattle-custom-fields.php` is loaded in functions.php
3. Try editing a product and refresh the page

### Slider Not Working?
1. Check browser console for JavaScript errors
2. Verify Swiper files are loaded (check Network tab)
3. Make sure jQuery is loaded

### Images Not Displaying?
1. Regenerate thumbnails: Use plugin like "Regenerate Thumbnails"
2. Check image permissions
3. Verify product has featured image set

---

## 📝 Example Product Setup

### Product: "Holstein Friesian Bull"
- **Title:** Holstein Friesian Bull
- **Price:** $2,500
- **Cattle Breed:** Holstein Friesian
- **Live Weight:** 450 kg
- **Badge Text:** Premium
- **Category:** Livestock → Cattle
- **Featured Image:** High-quality cattle image

---

## 🎉 You're All Set!

Your Cattle Slider Widget is now ready to use! 

### Quick Start:
1. ✅ Widget is registered in Elementor
2. ✅ Custom fields are available in product editor
3. ✅ Swiper library is loaded (offline)
4. ✅ All styles are included

### Next Steps:
1. Add custom field values to your products
2. Create a new page in Elementor
3. Add the Cattle Slider widget
4. Configure settings to your liking
5. Publish and enjoy! 🎊

---

**Need Help?** Check the full documentation in `CATTLE-SLIDER-WIDGET-DOCS.md`

**Version:** 1.0.0  
**Created:** 2025-12-31  
**Status:** ✅ Ready to Use
