# 🪑 Spinergo - Advanced WooCommerce Product Engineering

A technical deep-dive into the customization of a high-end ergonomic seating store built with **Bricks Builder**. This project focused on creating a bespoke "Product Configurator" experience, integrating dynamic metadata into swatches and engineering a custom product add-on system from the ground up.

**Live Project:** [https://spinergo.kinsta.cloud/produkt/ergonomicka-stolicka-business/](https://spinergo.kinsta.cloud/produkt/ergonomicka-stolicka-business/)

## 🛠️ Key Technical Features

### **1. Dynamic Swatch Metadata Engine (PHP & JS)**
* **Term Description Injection:** Developed a custom PHP bridge in `functions.php` that extracts WooCommerce attribute descriptions and injects them into the frontend as a JSON object.
* **UI Enhancement:** Engineered a JavaScript solution to intercept swatch rendering, dynamically appending rich text descriptions and high-resolution images to variation selections, replacing the limited default functionality.
* **Real-time Label Updating:** Implemented dynamic UI feedback that updates category headers (e.g., "Seat Color: [Selected Name]") instantly upon user interaction.

### **2. Custom Product Add-on System (Full-Stack)**
* **In-Form Injection:** Used JavaScript to dynamically inject custom table rows (Add-ons like "Silicone Wheels") directly into the WooCommerce variations table without modifying core template files.
* **Cart Logic & Price Calculation:** * Hooked into `woocommerce_add_cart_item_data` to ensure add-on selections persist through the session.
    * Engineered a custom calculation engine in `woocommerce_before_calculate_totals` to adjust product prices dynamically.
    * **Tax Logic:** Implemented a complex fix to handle "Price including Tax" calculations for add-ons, ensuring a 0ms blocking time and perfect accounting accuracy.
* **Checkout Transparency:** Filtered the cart and checkout displays to ensure custom add-ons are clearly itemized for the customer.

### **3. Bricks Builder Optimization**
* **Child Theme Architecture:** Built on a customized Bricks Child Theme to ensure all programmatic changes remain update-proof.
* **CSS Component Styling:** Authored specialized CSS to transform standard list-style swatches into a professional, modern "Flexbox" layout with custom borders, active states, and refined typography.
* **Image Optimization:** Implemented a JS-based "lazy-swap" to replace thumbnail-sized swatch images with full-size versions only when necessary, balancing speed and visual quality.

## 💡 The "Spinergo" Solution
Standard WooCommerce setups struggle with complex product options. I bridged this gap by:
* **Rich Information:** Allowing customers to see the *benefits* of a fabric or color (via descriptions) directly inside the selector.
* **Upsell Integration:** Making add-ons feel like a native part of the product selection process rather than an afterthought.
* **Performance:** Writing lightweight, native code that avoids the bloat of heavy "Product Options" plugins.

## 💻 Tech Stack & Tools
* **Builder:** Bricks Builder (WordPress)
* **Backend:** PHP (WooCommerce Hooks & Filters)
* **Frontend:** Vanilla JavaScript (ES6), jQuery, CSS3 (BEM-inspired)
* **Logic:** JSON Data Handling, DOM Mutation, Cart Object Manipulation

## 📸 Screenshots

Single Product:  
![Single Product](screenshot-previews/Single%20Product.png)

Cart:  
![Cart](Screenshots/Cart.png)

