🍽️ Buffet Hire - Custom Rental & Booking System
A specialized WooCommerce implementation for an Australian event equipment rental business. This project involved re-engineering the standard e-commerce purchase flow into a high-performance rental and booking engine.

🛠️ Key Technical Features
1. Dynamic UI & Frontend Interactivity (JS/jQuery)

Asynchronous Cart Management: Developed a dynamic cart fragment and counter that refreshes via AJAX. This allows users to see their rental count update instantly without page reloads.

Adaptive Header Injection: Implemented JavaScript logic to dynamically move the cart UI between the desktop navigation and the mobile header based on the user's viewport, ensuring a clean responsive layout.

Interactive Navigation: Built jQuery smooth-scroll handlers to improve the user journey through large product grids and category filters.

2. WooCommerce Logic & Custom Hooks (PHP)

Rental-Specific Price Modeling: Utilized woocommerce_get_price_html to transform standard pricing into a "Per Day" rental format across the shop and single product pages.

Custom Layout Control: Used action hooks (woocommerce_before_main_content) to restructure the single product page, moving titles and metadata into a bespoke wrapper for better UI hierarchy.

Automated Cross-Selling: Developed a custom shortcode system that queries and displays related products based on specific category slugs, significantly improving internal linking and user engagement.

3. Backend & Search Optimization

Search Result Customization: Filtered the WordPress search engine to replace standard post excerpts with dynamic product pricing and rental costs, ensuring users find critical info directly from the search bar.

Image Standardization: Implemented backend filters to force consistent 1:1 aspect ratios for product thumbnails in search results, maintaining a professional and uniform UI.

💡 The "Rental" Solution
To meet the client's business needs, I modified the standard checkout flow to handle:

Payment Pre-authorization: Integrated Stripe to support damage deposit holds rather than immediate charges.

Privacy-First Logistics: Configured the system to reveal the full pickup address only after a booking is confirmed, protecting business warehouse locations.

B2B Quoting: Implemented custom redirects that bypass standard checkout for specific high-volume rental packages, sending users to a specialized quote request form.

💻 Tech Stack
Languages: PHP, JavaScript (ES6), jQuery, CSS3

Platform: WordPress & WooCommerce

Tools: Elementor (Custom Child Theme), Bookings for WooCommerce, Stripe Gateway, Git