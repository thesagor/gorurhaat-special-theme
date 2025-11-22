# Product Grid Widget - User Guide

## 🎨 Overview

The **Product Grid Widget** is a custom Elementor widget that displays your WooCommerce products in a beautiful, responsive grid layout. It matches the design from your reference image with professional styling and extensive customization options.

---

## 📍 How to Use the Widget

### Step 1: Open Elementor Editor
1. Go to any page or post
2. Click "Edit with Elementor"

### Step 2: Add the Widget
1. In the left sidebar, search for **"Product Grid"**
2. Or find it under **"WooCommerce Elements"** category
3. Drag and drop it onto your page

### Step 3: Configure Settings
The widget will appear with default settings. Customize it using the options below.

---

## ⚙️ Widget Settings

### 📊 Query Settings Tab

#### **Product Category**
- **Type**: Multi-select dropdown
- **Purpose**: Filter products by WooCommerce categories
- **Options**: All your product categories
- **Usage**: 
  - Leave empty to show all products
  - Select one or more categories to filter
  - Example: Select "Livestock" to show only livestock products

#### **Product Type**
- **Type**: Single select dropdown
- **Purpose**: Filter by custom product type (from Product Info tab)
- **Options**:
  - All Types (default)
  - Livestock
  - Dairy Products
  - Animal Feed
- **Usage**: Select a specific type to show only those products

#### **Products Per Page**
- **Type**: Number input
- **Default**: 6
- **Range**: 1-100
- **Purpose**: Control how many products to display

#### **Order By**
- **Type**: Dropdown
- **Options**:
  - Date (newest/oldest first)
  - Title (alphabetical)
  - Price (low to high / high to low)
  - Popularity (most viewed)
  - Rating (highest rated)
  - Random (random order)
- **Default**: Date

#### **Order**
- **Type**: Dropdown
- **Options**:
  - Ascending (A-Z, low-high, old-new)
  - Descending (Z-A, high-low, new-old)
- **Default**: Descending

---

### 🎨 Layout Settings Tab

#### **Columns**
- **Type**: Responsive control
- **Desktop Default**: 3 columns
- **Tablet Default**: 2 columns
- **Mobile Default**: 1 column
- **Options**: 1-6 columns
- **Purpose**: Control grid layout on different devices

#### **Gap**
- **Type**: Slider
- **Default**: 20px
- **Range**: 0-100px
- **Purpose**: Space between product cards

---

### 🎨 Style Settings

#### **Card Style Tab**

**Background Color**
- Default: White (#ffffff)
- Controls the card background

**Border**
- Type, width, and color controls
- Default: 1px solid #e0e0e0

**Border Radius**
- Default: 8px on all corners
- Makes cards rounded

**Box Shadow**
- Add shadow effects to cards
- Enhances depth and elevation

**Padding**
- Default: 15px on all sides
- Space inside the card

---

#### **Title Style Tab**

**Color**
- Default: #333333
- Controls product title color

**Typography**
- Font family
- Font size
- Font weight
- Line height
- Letter spacing
- Text transform

---

#### **Price Style Tab**

**Color**
- Default: #2c3e50
- Controls price text color

**Typography**
- Full typography controls
- Same as title

---

#### **Button Style Tab**

**Normal State**
- Text Color: White (#ffffff)
- Background: Blue (#3498db)

**Hover State**
- Text Color: White (#ffffff)
- Background: Darker Blue (#2980b9)

**Border Radius**
- Default: 4px
- Rounded button corners

**Padding**
- Default: 10px top/bottom, 20px left/right

---

## 🎯 Common Use Cases

### Example 1: Show All Livestock Products
```
Query Settings:
- Product Category: [Leave empty or select specific category]
- Product Type: Livestock
- Products Per Page: 9
- Order By: Date
- Order: Descending
```

### Example 2: Show Dairy Products from Specific Category
```
Query Settings:
- Product Category: Dairy Products
- Product Type: Dairy Products
- Products Per Page: 6
- Order By: Price
- Order: Ascending
```

### Example 3: Featured Products Grid
```
Query Settings:
- Product Category: Featured
- Product Type: All Types
- Products Per Page: 8
- Order By: Popularity
- Order: Descending

Layout Settings:
- Columns: 4 (Desktop), 2 (Tablet), 1 (Mobile)
- Gap: 30px
```

---

## 🎨 Styling Tips

### Professional Look
1. **Card Style**:
   - Background: White
   - Border: 1px solid #e0e0e0
   - Border Radius: 8px
   - Box Shadow: Subtle (0 2px 8px rgba(0,0,0,0.1))

2. **Button Style**:
   - Use brand colors
   - Add hover effects
   - Keep padding consistent

3. **Typography**:
   - Title: 16-18px, Semi-bold
   - Price: 18-20px, Bold
   - Button: 14-16px, Semi-bold

### Modern Minimalist
1. **Card Style**:
   - Background: #f8f9fa
   - Border: None
   - Border Radius: 12px
   - Box Shadow: None

2. **Colors**:
   - Title: #1a1a1a
   - Price: #000000
   - Button: Accent color

### E-commerce Classic
1. **Layout**:
   - 4 columns on desktop
   - 20px gap
   - Equal height cards

2. **Button**:
   - Full width
   - Prominent color
   - Clear hover state

---

## 📱 Responsive Design

The widget is fully responsive with these breakpoints:

### Desktop (> 1024px)
- Default column count (usually 3-4)
- Full gap spacing
- All features visible

### Tablet (768px - 1024px)
- Automatically switches to 2 columns
- Adjusted spacing
- Optimized for touch

### Mobile (< 768px)
- Single column layout
- Reduced gap (15px)
- Smaller font sizes
- Touch-friendly buttons

---

## 🔧 Advanced Customization

### Custom CSS Classes
You can add custom CSS to further style the widget:

```css
/* Target specific widget instance */
.elementor-widget-product_grid {
    /* Your custom styles */
}

/* Customize product cards */
.product-card {
    /* Card customization */
}

/* Customize buttons */
.product-button {
    /* Button customization */
}
```

### Modify Widget Template
For advanced users, you can modify the widget template in:
`/widgets/product-grid-widget.php`

Look for the `render()` method around line 400.

---

## 🐛 Troubleshooting

### Products Not Showing
**Possible Causes**:
1. No products match the selected filters
2. Products are not published
3. WooCommerce is not active

**Solutions**:
1. Check your filter settings
2. Verify products are published
3. Ensure WooCommerce plugin is active

### Styling Issues
**Possible Causes**:
1. Theme CSS conflicts
2. Cache not cleared
3. Custom CSS overriding

**Solutions**:
1. Check browser console for errors
2. Clear all caches (browser, plugin, server)
3. Use more specific CSS selectors

### Layout Breaking on Mobile
**Possible Causes**:
1. Responsive settings not configured
2. Theme CSS conflicts

**Solutions**:
1. Set mobile columns to 1
2. Reduce gap size for mobile
3. Check theme responsive CSS

### Images Not Displaying Correctly
**Possible Causes**:
1. Missing product images
2. Image size issues

**Solutions**:
1. Ensure all products have featured images
2. Regenerate thumbnails (use plugin)
3. Check image aspect ratio settings

---

## 💡 Pro Tips

### 1. Optimize Performance
- Don't show too many products (6-12 is ideal)
- Use product categories to filter
- Enable caching plugins

### 2. Better User Experience
- Use clear, descriptive product titles
- Add high-quality product images
- Keep "View Details" button visible
- Use consistent pricing format

### 3. SEO Benefits
- Products link to individual pages
- Proper HTML structure
- Image alt tags included
- Semantic markup

### 4. Conversion Optimization
- Use contrasting button colors
- Add urgency with "Order By: Date"
- Show sale prices prominently
- Use professional product images

---

## 🔄 Widget Updates

When the widget is updated:
1. Clear Elementor cache (Elementor → Tools → Regenerate CSS)
2. Clear site cache
3. Test on different devices
4. Check for any style conflicts

---

## 📞 Support

For issues or questions:
1. Check this documentation first
2. Review the troubleshooting section
3. Check WordPress/Elementor forums
4. Contact theme developer

---

## 🎓 Learning Resources

### Elementor Documentation
- [Elementor Widget Development](https://developers.elementor.com/)
- [Elementor Controls](https://developers.elementor.com/elementor-controls/)

### WooCommerce Documentation
- [WooCommerce Product Query](https://woocommerce.com/document/woocommerce-shortcodes/)
- [WooCommerce Templates](https://woocommerce.com/document/template-structure/)

---

## ✅ Checklist for Perfect Product Grid

- [ ] Widget added to page
- [ ] Product category selected (if needed)
- [ ] Product type filtered (if needed)
- [ ] Column count set for all devices
- [ ] Gap spacing adjusted
- [ ] Card styling customized
- [ ] Button colors match brand
- [ ] Typography set correctly
- [ ] Tested on mobile devices
- [ ] Page published

---

**Version**: 1.0.0  
**Last Updated**: 2025-11-22  
**Compatible With**: Elementor 3.0+, WooCommerce 3.0+

---

## 🎉 Enjoy Your New Product Grid Widget!

This widget gives you complete control over how your products are displayed, with professional styling and extensive customization options. Experiment with different settings to find the perfect look for your site!
