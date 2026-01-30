# 🍽️ Buffet Hire - Custom Rental & Booking System

A specialized WooCommerce implementation for an Australian event equipment rental business. This project involved re-engineering the standard e-commerce purchase flow into a high-performance rental and booking engine.

## 🛠️ Key Technical Features

### **1. Dynamic UI & Frontend Interactivity (JS/jQuery)**
* **Asynchronous Cart Management:** Developed a dynamic cart fragment and counter that refreshes via AJAX. This allows users to see their rental count update instantly without page reloads.
* **Adaptive Header Injection:** Implemented JavaScript logic to dynamically move the cart UI between the desktop navigation and the mobile header based on the user's viewport.
* **Interactive Navigation:** Built jQuery smooth-scroll handlers to improve the user journey through large product grids and category filters.

### **2. WooCommerce Logic & Custom Hooks (PHP)**
* **Rental-Specific Price Modeling:** Utilized `woocommerce_get_price_html` to transform standard pricing into a "Per Day" rental format.
* **Custom Layout Control:** Used action hooks (`woocommerce_before_main_content`) to restructure the single product page for better UI hierarchy.
* **Automated Cross-Selling:** Developed a custom shortcode system that queries and displays related products based on specific category slugs.

### **3. Backend & Search Optimization**
* **Search Result Customization:** Filtered the WordPress search engine to replace standard post excerpts with dynamic product pricing.
* **Image Standardization:** Implemented backend filters to force consistent 1:1 aspect ratios for product thumbnails in search results.

## 💡 The "Rental" Solution
To meet the client's business needs, I modified the standard checkout flow to handle:
* **Payment Pre-authorization:** Integrated Stripe to support damage deposit holds.
* **Privacy-First Logistics:** Configured the system to reveal the full pickup address only after a booking is confirmed.
* **B2B Quoting:** Implemented custom redirects for high-volume rental packages.

## 💻 Tech Stack
* **Languages:** PHP, JavaScript (ES6), jQuery, CSS3
* **Platform:** WordPress & WooCommerce
* **Tools:** Elementor, Bookings for WooCommerce, Stripe Gateway

## 📸 Screenshots
Home v1:  
![Home v1](Screenshots/Home%20v1.png)

Home v2:  
![Home v2](Screenshots/Home%20v2.png)

Home Mob:  
![Home Mob](Screenshots/Home-mob.png)

Packages v1:  
![Packages v1](Screenshots/Packages.png)

Packages:  
![Packages v2](Screenshots/Packages-v2.png)

Category:  
![Category](Screenshots/Category.png)

Category Mob:  
![Category Mob](Screenshots/Category-mob.png)

Single Product v1:  
![Single Product v1](Screenshots/Single-Product.png)

Single Product v2:  
![Single Product v2](Screenshots/Single-Product-v2.png)

My Account:  
![My Account](Screenshots/My-Account.png)

My Account Mob:  
![My Account Mob](Screenshots/My-Account-mob.png)

Contact:  
![Contact](Screenshots/Contact-Us.png)

