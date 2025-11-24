<?php
/**
 * Force create WooCommerce categories
 * Run this file once, then delete it
 */

// Load WordPress
require_once('../../../../../wp-load.php');

// Delete the option to force recreation
delete_option('hello_elementor_child_wc_categories_created');

// Run the function
hello_elementor_child_create_woocommerce_categories();

echo "✅ WooCommerce categories created successfully!\n\n";
echo "Categories created:\n";
echo "- Livestock\n";
echo "  — Ox\n";
echo "  — Dairy Cow\n";
echo "  — Bokna\n\n";
echo "- Dairy Products\n";
echo "  — Milk\n";
echo "  — Butter\n";
echo "  — Cheese\n";
echo "  — Yogurt\n";
echo "  — Cream\n";
echo "  — Ghee\n\n";
echo "- Animal Feed\n";
echo "  — Cattle Feed\n";
echo "  — Dairy Cow Feed\n";
echo "  — Calf Feed\n";
echo "  — Hay\n";
echo "  — Silage\n";
echo "  — Concentrate\n\n";
echo "Now go to your Elementor Product Slider widget and you'll see all categories in the dropdown!\n";
echo "\n⚠️ You can delete this file now.\n";
