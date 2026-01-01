# Cattle Slider Widget - Documentation

## Overview
The **Cattle Slider Widget** is a premium Elementor widget designed specifically for displaying livestock/cattle products with beautiful card designs. It's based on the premium card.html design and includes:

- **Product Title** - The name of the cattle/livestock
- **Breed Information** - Type/breed of the animal
- **Price** - Product price with WooCommerce integration
- **Live Weight** - Weight of the animal
- **Premium Badge** - Customizable badge (Premium, Featured, etc.)
- **Product Image** - High-quality product images
- **View Details Button** - Links to product page

## Widget Location
After activation, you'll find the widget in Elementor under:
**WooCommerce Elements → Cattle Slider**

## Required Custom Fields

To use this widget effectively, you need to add the following custom fields to your WooCommerce products:

### 1. Cattle Breed
- **Meta Key:** `_cattle_breed`
- **Type:** Text
- **Example:** "Holstein Friesian", "Jersey Cow", "Angus Bull"
- **Default:** "Premium Breed" (if not set)

### 2. Live Weight
- **Meta Key:** `_live_weight`
- **Type:** Text
- **Example:** "450 kg", "380 kg", "550 kg"
- **Default:** "450 kg" (if not set)

### 3. Badge Text
- **Meta Key:** `_badge_text`
- **Type:** Text
- **Example:** "Premium", "Featured", "Best Seller"
- **Default:** "Premium" (if not set)

## How to Add Custom Fields to Products

### Method 1: Using Code (Add to functions.php or a plugin)

```php
/**
 * Add custom fields to WooCommerce product
 */
add_action( 'woocommerce_product_options_general_product_data', 'add_cattle_custom_fields' );
function add_cattle_custom_fields() {
    global $post;
    
    echo '<div class="options_group">';
    
    // Cattle Breed
    woocommerce_wp_text_input( array(
        'id' => '_cattle_breed',
        'label' => __( 'Cattle Breed', 'hello-elementor-child' ),
        'placeholder' => 'e.g., Holstein Friesian',
        'desc_tip' => true,
        'description' => __( 'Enter the breed of the cattle', 'hello-elementor-child' ),
    ) );
    
    // Live Weight
    woocommerce_wp_text_input( array(
        'id' => '_live_weight',
        'label' => __( 'Live Weight', 'hello-elementor-child' ),
        'placeholder' => 'e.g., 450 kg',
        'desc_tip' => true,
        'description' => __( 'Enter the live weight of the cattle', 'hello-elementor-child' ),
    ) );
    
    // Badge Text
    woocommerce_wp_text_input( array(
        'id' => '_badge_text',
        'label' => __( 'Badge Text', 'hello-elementor-child' ),
        'placeholder' => 'e.g., Premium',
        'desc_tip' => true,
        'description' => __( 'Enter the badge text (Premium, Featured, etc.)', 'hello-elementor-child' ),
    ) );
    
    echo '</div>';
}

/**
 * Save custom fields
 */
add_action( 'woocommerce_process_product_meta', 'save_cattle_custom_fields' );
function save_cattle_custom_fields( $post_id ) {
    $cattle_breed = isset( $_POST['_cattle_breed'] ) ? sanitize_text_field( $_POST['_cattle_breed'] ) : '';
    update_post_meta( $post_id, '_cattle_breed', $cattle_breed );
    
    $live_weight = isset( $_POST['_live_weight'] ) ? sanitize_text_field( $_POST['_live_weight'] ) : '';
    update_post_meta( $post_id, '_live_weight', $live_weight );
    
    $badge_text = isset( $_POST['_badge_text'] ) ? sanitize_text_field( $_POST['_badge_text'] ) : '';
    update_post_meta( $post_id, '_badge_text', $badge_text );
}
```

### Method 2: Using Advanced Custom Fields (ACF) Plugin

1. Install ACF plugin
2. Create a new Field Group
3. Add these fields:
   - Field Label: "Cattle Breed" | Field Name: `_cattle_breed` | Field Type: Text
   - Field Label: "Live Weight" | Field Name: `_live_weight` | Field Type: Text
   - Field Label: "Badge Text" | Field Name: `_badge_text` | Field Type: Text
4. Set Location Rules: Post Type is equal to Product

## Widget Settings

### Query Settings
- **Product Category** - Filter products by category (multiple selection)
- **Number of Products** - How many products to display (1-100)
- **Order By** - Sort by Date, Title, Price, or Random
- **Order** - Ascending or Descending

### Slider Settings
- **Slides to Show** - Number of slides visible (Desktop: 4, Tablet: 2, Mobile: 1)
- **Space Between Slides** - Gap between cards (default: 30px)
- **Autoplay** - Enable/disable automatic sliding
- **Autoplay Speed** - Delay between slides (default: 4000ms)
- **Infinite Loop** - Enable continuous loop
- **Pause on Hover** - Pause autoplay when hovering
- **Animation Speed** - Transition speed (default: 600ms)

### Navigation
- **Show Arrows** - Display previous/next navigation arrows
- **Show Pagination** - Display pagination dots

### Style Settings

#### Card Style
- **Background Color** - Card background (default: #ffffff)
- **Border Radius** - Card corner radius (default: 20px)

#### Badge Style
- **Background Color** - Badge background (default: #10b981 green)

#### Typography
- **Title Color** - Product title color (default: #1a1a2e)
- **Breed Color** - Breed text color (default: #64748b)
- **Price Color** - Price color (default: #dc2626 red)
- **Weight Color** - Weight color (default: #10b981 green)

#### Button Style
- **Normal Background** - Button background (default: #2563eb blue)
- **Hover Background** - Button hover background (default: #1d4ed8)

## Design Features

### Premium Card Design
- ✅ Smooth hover animations with lift effect
- ✅ Gradient top border on hover
- ✅ Image zoom effect on hover
- ✅ Premium badge with shadow
- ✅ Info grid with hover effects
- ✅ Responsive design for all devices

### Navigation
- ✅ Circular navigation buttons with hover effects
- ✅ Animated pagination bullets
- ✅ Touch/swipe support on mobile

### Responsive Breakpoints
- **Mobile (0-639px):** 1 slide
- **Tablet (640-1023px):** 2 slides
- **Desktop (1024-1399px):** 2-3 slides
- **Large Desktop (1400px+):** 4 slides

## Usage Example

1. **Add the widget** to your Elementor page
2. **Configure settings:**
   - Select product categories (e.g., "Livestock", "Cattle")
   - Set number of products to 8
   - Enable autoplay with 4000ms delay
   - Show arrows and pagination
3. **Customize colors** to match your brand
4. **Publish** and enjoy!

## Browser Compatibility
- ✅ Chrome, Firefox, Safari, Edge (latest versions)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)
- ✅ Supports all modern devices

## Dependencies
- **Swiper.js v11** (automatically loaded from local files)
- **WooCommerce** (required for product data)
- **Elementor** (required for widget functionality)

## File Locations
- Widget File: `/widgets/cattle-slider-widget.php`
- Swiper CSS: `/lib/swiper-bundle.min.css`
- Swiper JS: `/lib/swiper-bundle.min.js`
- Registration: `/inc/elementor-widgets.php`

## Support
For issues or customization requests, please contact your theme developer.

---

**Version:** 1.0.0  
**Last Updated:** 2025-12-31  
**Compatibility:** WordPress 5.8+, WooCommerce 5.0+, Elementor 3.0+
