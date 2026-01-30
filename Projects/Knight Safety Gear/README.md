# 🛡️ Knight Safety Gear - UI/UX & Component Optimization

A series of custom frontend enhancements for an industrial safety equipment retailer. This project focused on improving the clarity of product data, automating category organization, and creating interactive product components within the WooCommerce ecosystem.

## 🛠️ Key Technical Features

### **1. Dynamic Data Scraping & Discount Updates (Vanilla JS)**
* **Real-time Price Logic:** Engineered a JavaScript listener that scrapes a discount percentage from a comparison table and dynamically updates the product variation description.
* **Conditional UI States:** Implemented logic to ensure discount labels only appear when a specific "Case" variation is selected, improving the accuracy of the customer's pricing view.

### **2. Frontend DOM Manipulation & Sorting (jQuery)**
* **Client-Side Category Sorting:** Developed a custom jQuery sorting algorithm to reorganize product categories alphabetically (A-Z) on the fly, bypassing the limitations of the existing layout engine.
* **Automated Data Cleaning:** Used Regular Expressions (RegEx) to strip redundant unit pricing text ("$/Unit") from product labels across the site, resulting in a cleaner, more professional UI.

### **3. Custom WordPress Components (PHP)**
* **Accordion Shortcode:** Developed a custom PHP shortcode to wrap WooCommerce product descriptions into a responsive accordion. This optimized the mobile viewing experience by reducing "page bloat" and improving scrollability.
* **Hook Integration:** Seamlessly integrated the description shortcode into the product template while maintaining compatibility with the core WooCommerce engine.

## 💡 Problem Solving: The "Industrial" Challenge
Safety gear websites often deal with complex bulk-pricing data. My contributions focused on:
* **Visibility Control:** Managed the "Flash of Unstyled Content" (FOUC) by using CSS visibility toggles synchronized with JavaScript execution.
* **UI Consistency:** Standardized the appearance of variation descriptions across hundreds of safety products.

## 💻 Tech Stack
* **Languages:** PHP, JavaScript (ES6), jQuery
* **Platform:** WordPress & WooCommerce
* **Techniques:** DOM Manipulation, RegEx, Shortcode API
