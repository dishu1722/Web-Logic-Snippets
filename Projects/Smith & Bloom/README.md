# 🌸 Smith & Bloom - Bespoke E-commerce Floral Store

A high-end WooCommerce storefront developed for an Australian boutique specializing in flowers, fruit platters, and organic gift boxes. This project involved a complete custom build, focusing on elegant UI design and specialized functional logic for local delivery logistics.

## 🛠️ Key Technical Features

### **1. Custom Business Logic (PHP & Hooks)**
* **WooCommerce Layout Engineering:** Re-engineered the single product template using PHP hooks to move product data tabs directly below the "Add to Cart" summary, optimizing the mobile conversion funnel.
* **Smart Pricing Display:** Implemented a custom filter (`woocommerce_variable_price_html`) to hide confusing price ranges on variable products, displaying only the specific price once a selection is made.
* **Dynamic Menu Architecture:** Developed a custom theme setup to support multi-location navigation menus (Primary & Footer) within a bespoke child theme environment.

### **2. Frontend Interactivity & Validation (JS/jQuery)**
* **Regional Delivery Checker:** Built a custom JavaScript validation engine that checks user postcodes against a defined array of delivery zones, providing real-time feedback on service availability before the user reaches checkout.
* **Checkout Field Transformation:** Utilized the `MutationObserver` API to dynamically rename and re-purpose the standard "Order Notes" field into a "Custom Card Message" field, improving the user experience for gift-buyers.
* **Automated Data Updates:** Implemented jQuery scripts to handle dynamic year updates for copyright statements and UI state changes for floral variation selectors.

### **3. Advanced CSS Architecture**
* **Brand-Aligned Typography:** Integrated the "Cinzel" and "Roboto" font families through custom CSS to maintain a balance between floral elegance and modern readability.
* **Custom UI Components:** Developed CSS-only solutions for rotating interactive icons and styling the "Stripe Gateway" checkout components to match the brand's gold-and-white aesthetic.
* **Responsive Grid Logic:** Crafted custom media queries to standardize product image aspect ratios and grid spacing across mobile, tablet, and desktop views.

## 💡 The "Smith & Bloom" Solution
E-commerce for florists requires specific "gifting" features. I addressed these by:
* **Gift Personalization:** Ensuring the "Card Message" field was prominent and mandatory.
* **Delivery Accuracy:** Preventing out-of-area orders through the frontend postcode checker.
* **Visual Storytelling:** Using a video-hero banner and clean product galleries to highlight the freshness of the inventory.

## 💻 Tech Stack & Tools
* **Platform:** WordPress (Hello Elementor Child Theme)
* **E-commerce:** WooCommerce + Stripe Gateway
* **Languages:** PHP, JavaScript (ES6), jQuery, CSS3
* **Tools:** Elementor, WPForms, Generate Child Theme
