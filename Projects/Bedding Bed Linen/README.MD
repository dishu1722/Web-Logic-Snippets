# 🧺 Bedding & Bed Linen – Website Development (WordPress + WooCommerce + Custom Calculator)

**Live Project:** [https://beddingbedlinen.com.au](https://beddingbedlinen.com.au)  

This is a complete WordPress website developed using Elementor + Hello Elementor Child Theme, featuring a fully custom WooCommerce-based Linen Hire Calculator, a fully customized checkout experience, and complete site design across multiple pages.

---

## 🚀 Project Overview
This project was built for a linen hire business in Australia.  
The goal was to design a modern, clean website with a **custom hire calculator** that dynamically calculates cost based on:

- Quantity
- Pickup Price
- Daily Hire Rate
- Days to Hire

The system integrates directly with WooCommerce so users can add selected items to the cart and complete checkout with **custom pickup/delivery date fields**.

---

## 🛠️ Tech Stack
| Technology | Usage |
|------------|-------|
| WordPress | CMS |
| Hello Elementor Child Theme | Theme customization |
| Elementor (Pro + widgets) | Page design |
| WooCommerce | eCommerce platform |
| Custom PHP | functions.php for calculator & hooks |
| JavaScript / jQuery | Calculator logic, AJAX |
| CSS | Custom styling |
| MetForm | Contact forms |
| SiteGround SMTP | Email delivery |
| GitHub | Version control |

---

## 🎨 Website Pages Designed
| Page | Built Using | Notes |
|------|------------|-------|
| Home | Elementor | Full layout, hero section, services, CTAs |
| FAQ | Elementor | Styled FAQ accordion blocks |
| About | Elementor | Branding, business information |
| Cost Calculator | Custom + Elementor | Entire calculator built manually |
| Contact | Elementor + MetForm | SMTP configured, email working |

---

## 🧮 Custom Linen Hire Calculator (Major Feature)
- Fully custom UI (HTML/CSS/JS)
- Supports 100+ potential products
- Dynamic calculations per row:
  - Add/remove quantity
  - Change days to hire
  - Auto-updating total cost
- Mobile-responsive
- Floating "Add to Cart" footer bar
- Custom AJAX call to add selected items to WooCommerce cart
- **Security & Integrity:** Implemented WordPress Nonces for AJAX request verification to prevent CSRF attacks.
- **Error Handling:** Robust front-end and back-end validation to ensure accurate pricing calculations and data sanitization.
- Products created in WooCommerce and connected to calculator

---

## 🛒 WooCommerce Customizations
### Checkout Page
- Added Delivery Date, Pickup Date, Time fields
- Pickup date auto-calculates based on selected days
- Disabled manual editing for Pickup Date
- “Optional” removed from fields
- Custom validation & styling

### Cart System
- Synced calculator with WooCommerce
- Custom cart icon with live count in header
- Error handling for invalid items
- Removed default purchase rules blocking calculator items

### Order Page
- Custom fields (delivery/pickup date & time) visible in order admin
- Passed all custom checkout data into WooCommerce order meta

---

## 📧 Contact Form + SMTP (MetForm)
- Built complete contact form
- Created SMTP email account
- Connected SMTP via SiteGround Email Tools
- Integrated MetForm for sending emails through SMTP
- Verified entry storage and email delivery

---

## ⌨️ Code Highlight: AJAX Add-to-Cart Logic
To bridge the gap between the custom JS calculator and the WooCommerce core, I developed a custom AJAX handler:

```javascript
// Example of the logic used to sync calculator selections with the cart
$.ajax({
    url: wc_add_to_cart_params.ajax_url,
    type: 'POST',
    data: {
        action: 'linen_hire_add_to_cart',
        product_data: selectedItems,
        security: custom_nonce
    },
    success: function(response) {
        // Handle UI update and floating cart bar refresh
    }
}); 
```

## 📸 Screenshots
Homepage:  
![Homepage](/screenshot-previews/Homepage.png)

About:
![About](/screenshot-previews/About.png)

Contact:
![Contact](/screenshot-previews/Contact.png)

Cost Calculator:  
![Cost Calculator](/screenshot-previews/Cost-Calculator.png)

Mobile Cost Calculator View:  
![Mobile View](/screenshot-previews/Cost-Calculator-mobile.png)

FAQ:  
![FAQ](/screenshot-previews/FAQ.png)

Checkout Page with Custom Fields:  
![Checkout](/screenshot-previews/Checkout.png)

Cart Page + Cart Icon with Counter in Header:  
![Cart](/screenshot-previews/Cart.png)

---

## 🧑‍💻 Key Technical Contributions
- Designed all main pages using Elementor
- Built entire Linen Hire Calculator system (PHP, JS, CSS)
- Developed custom PHP hooks, AJAX calls, and JS logic
- Setup WooCommerce products for calculator (pickup/daily hire)
- Customized Cart, Checkout, and Orders
- Created contact form and configured SMTP
- Performed testing, debugging, and optimization
- Made website responsive and mobile-friendly

---

## 📦 Setup Instructions (for reuse)
1. Install WordPress
2. Install the following plugins:
   - Hello Elementor theme
   - Elementor / Elementor Pro
   - WooCommerce
   - MetForm
3. Upload the child theme from this repo
4. Add WooCommerce products
5. Import all calculator JS/CSS
6. Add custom date/time fields to checkout
7. Configure SMTP for forms
8. Test cart/checkout flow

---

## 📄 License
This is a client project – code included for **portfolio purposes only**.
