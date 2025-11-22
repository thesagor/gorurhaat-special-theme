# Quick Reference Guide - Enhanced Theme Features

## New Admin Features

### 1. Product Type Column
**Location**: Products → All Products

The products list now displays a color-coded "Product Type" badge for each product:
- 🟢 **Green** = Livestock
- 🔵 **Blue** = Dairy Product  
- 🟠 **Orange** = Animal Feed

**Features**:
- Click the column header to sort by product type
- Quickly identify product categories at a glance
- Color-coded for easy visual scanning

### 2. WhatsApp Settings
**Location**: Settings → General → WhatsApp Settings

Configure your default WhatsApp contact number:
1. Go to Settings → General
2. Scroll to "WhatsApp Settings" section
3. Enter your WhatsApp number with country code (e.g., +8801780884888)
4. Click "Save Changes"

The WhatsApp button on product pages will use this number.

## Using the Utility Function

### Get Product Information in Templates

You can now easily retrieve product information in your templates:

```php
<?php
// Get current product info
$product_info = hello_elementor_child_get_product_info();

// Or get specific product info
$product_info = hello_elementor_child_get_product_info( 123 ); // Product ID

// Display the information
if ( ! empty( $product_info['fields'] ) ) {
    echo '<div class="product-specifications">';
    echo '<h3>Product Details</h3>';
    echo '<dl>';
    foreach ( $product_info['fields'] as $label => $value ) {
        echo '<dt>' . esc_html( $label ) . '</dt>';
        echo '<dd>' . esc_html( $value ) . '</dd>';
    }
    echo '</dl>';
    echo '</div>';
}

// Display notes if available
if ( ! empty( $product_info['notes'] ) ) {
    echo '<div class="product-notes">';
    echo '<h4>Additional Notes</h4>';
    echo '<p>' . esc_html( $product_info['notes'] ) . '</p>';
    echo '</div>';
}
?>
```

### Example Output Structure

```php
array(
    'type' => 'livestock',
    'fields' => array(
        'Cow Type' => 'Dairy Cow',
        'Breed' => 'Holstein',
        'Age' => '3 years',
        'Weight' => '500',
        'Color' => 'Black & White',
        'Health Status' => 'Excellent',
        'Vaccinated' => 'Yes'
    ),
    'notes' => 'High milk production, well-maintained'
)
```

## Product Management Workflow

### Adding a New Livestock Product

1. Go to Products → Add New
2. Enter product name and description
3. Scroll to "Product Info" tab
4. Select "Livestock (Cows)" from Product Type dropdown
5. Fill in livestock-specific fields:
   - Cow Type
   - Breed
   - Age
   - Weight
   - Color
   - Health Status
   - Vaccination status
6. Add any additional notes
7. Click "Publish"

**Automatic Actions**:
- Product is automatically assigned to "Livestock" category
- Brand/sub-type is auto-assigned based on cow type
- Custom taxonomies are updated

### Adding a Dairy Product

1. Follow steps 1-3 above
2. Select "Dairy Product" from Product Type dropdown
3. Fill in dairy-specific fields:
   - Dairy Product Type
   - Fat Content
   - Unit Size
   - Shelf Life
   - Storage Requirements
   - Origin/Source
4. Add notes and publish

### Adding Animal Feed

1. Follow steps 1-3 above
2. Select "Animal Feed" from Product Type dropdown
3. Fill in feed-specific fields:
   - Feed Type
   - Package Size
   - Protein Content
   - Suitable For
   - Nutritional Information
4. Add notes and publish

## Frontend Features

### WhatsApp Contact Button

Automatically appears on all product pages below the "Add to Cart" button.

**Features**:
- Pre-filled message with product name and link
- Opens WhatsApp in new tab
- Uses number from admin settings
- Professional styling with hover effects

**Message Format**:
```
Assalamu alaikum, I want to talk about "[Product Name]" which link is [Product URL]
```

## Custom Taxonomies

### Product Type Category
**Slug**: `product_type_category`

Available terms:
- Livestock
- Dairy Products
- Animal Feed

### Product Brand
**Slug**: `product_brand`

Pre-created brands include:
- **Livestock**: Ox, Dairy Cow, Bokna, Holstein, Jersey
- **Dairy**: Milk, Butter, Cheese, Cream, Ghee
- **Feed**: Cattle Feed, Dairy Feed, Calf Feed, Ready Mixed, Silage

## Tips & Best Practices

### 1. Always Select Product Type
Make sure to select a product type for every product. This ensures:
- Proper categorization
- Correct brand assignment
- Better organization
- Improved searchability

### 2. Fill in Relevant Fields
Complete as many fields as possible for better product information:
- Helps customers make informed decisions
- Improves SEO
- Better product filtering
- Professional appearance

### 3. Use Additional Notes
The notes field is great for:
- Special offers
- Unique selling points
- Care instructions
- Warranty information
- Delivery details

### 4. Keep WhatsApp Number Updated
Regularly check that your WhatsApp number in settings is:
- Active and monitored
- Has correct country code
- Belongs to a business account (recommended)

## Troubleshooting

### Product Type Not Showing
**Solution**: Make sure you've selected a product type in the "Product Info" tab and saved the product.

### WhatsApp Button Not Appearing
**Possible causes**:
1. WhatsApp number not set in settings
2. Theme cache needs clearing
3. Check if WooCommerce is active

**Solution**: 
- Go to Settings → General → WhatsApp Settings
- Enter a valid phone number
- Clear cache if using a caching plugin

### Taxonomies Not Auto-Assigning
**Solution**: 
1. Edit the product
2. Make sure Product Type is selected
3. Save the product again
4. Check Products → Product Types and Products → Brands

### Admin Column Not Showing
**Solution**:
1. Go to Products → All Products
2. Click "Screen Options" at the top right
3. Make sure "Product Type" is checked
4. Refresh the page

## Advanced Customization

### Modify WhatsApp Message Template

Edit the message in `functions.php` around line 880:

```php
$message = sprintf(
    'Your custom message here: "%s" - %s',
    $product_name,
    $product_url
);
```

### Add Custom Product Types

To add a new product type, update these sections in `functions.php`:

1. Line ~207: Add to dropdown options
2. Line ~547: Add to validation array
3. Line ~195: Add new field section
4. Line ~681: Add to category mapping
5. Line ~1070: Add to utility function

### Customize Admin Column Colors

Edit the colors array around line 895:

```php
$colors = array(
    'livestock'     => '#4CAF50',  // Green
    'dairy_product' => '#2196F3',  // Blue
    'feed'          => '#FF9800',  // Orange
    'your_type'     => '#9C27B0'   // Purple
);
```

## Support & Documentation

For more information about WordPress theme development:
- [WordPress Developer Documentation](https://developer.wordpress.org/)
- [WooCommerce Developer Documentation](https://woocommerce.com/documentation/plugins/woocommerce/)
- [Elementor Developer Documentation](https://developers.elementor.com/)

---

**Version**: 2.0.0  
**Last Updated**: 2025-11-22  
**Compatibility**: WordPress 5.0+, WooCommerce 3.0+
