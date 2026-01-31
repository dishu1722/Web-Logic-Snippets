# 🌲 Waldholz - Luxury Forest & Real Estate Platform

**Live Project:** [waldholz.de](https://waldholz.de/)  
**Design Prototype:** [Figma Project File](https://www.figma.com/design/djIaNnsS4Oy37vJRJOjG3c/New-Website-project?node-id=73-2&p=f)

## 📖 Project Overview
Waldholz is a premium real estate agency specializing in high-value forest land, private hunting estates (*Eigenjagden*), and agricultural properties across Europe and North America. The objective was to create a digital presence that reflects the exclusivity and stability of land investment while providing a high-tech interactive experience for potential investors.

## 🚀 The "Figma-to-Code" Workflow
I managed the complete lifecycle of this project, ensuring a 1:1 translation from the high-fidelity design to the live production environment.

### **Phase 1: UI/UX Architecture (Figma)**
* **Brand Strategy:** Developed a sophisticated visual identity using a palette of **Deep Forest Green** and **Earth Gold**.
* **Typography:** Utilized **Cormorant Garamond** for headers to evoke a classic, high-trust "Old Money" feel, paired with **Inter** for clean, modern readability.
* **Component Library:** Designed reusable cards, buttons, and navigation patterns to ensure consistency across the international property listings.

### **Phase 2: Advanced Technical Engineering (WordPress/PHP)**
* **Interactive SVG Map Engine:** I engineered a custom JavaScript solution for the region-based map. Standard plugins could not distinguish between regions like Scotland and England within a single path; I solved this by calculating SVG bounding box coordinates to trigger dynamic, region-specific tooltips.
* **Property CPT & Querying:** Developed a **Custom Post Type (CPT)** for property listings. I wrote custom PHP functions to handle hectare data, tree species categories, and price-on-request logic.
* **Bespoke Owl Carousel:** Built a custom shortcode to initialize an **Owl Carousel** with "Stage Padding." This creates the modern "peek-a-boo" offset effect seen in the featured properties section, which is highly performant compared to bulky page-builder widgets.



## 🛠️ Technical Toolkit
* **Design:** Figma, Adobe Photoshop
* **Frontend:** JavaScript (jQuery/ES6), CSS3 (Custom Polygon Clip-paths)
* **Backend:** PHP (WordPress Hooks & Shortcodes)
* **Architecture:** Custom Child Theme + Elementor + MapGeo Logic

## ✨ Key Features
* **Dynamic Regional Tooltips:** Real-time data overlay on the global map based on mouse-hover coordinates.
* **Custom Geometric UI:** "Zig-Zag" section dividers created with CSS `clip-path` math for a unique, non-standard web layout.
* **Lead Generation Logic:** Custom-styled contact funnels integrated with **WPForms** to capture high-intent investor leads.
* **Performance Focused:** Designed with a "Clean DOM" philosophy to ensure fast loading times on German high-speed networks.

## 💡 Results
The final build for Waldholz is a world-class real estate platform that stands out in a traditional industry. It balances heavy data requirements (international listings) with a lightweight, premium user experience that functions flawlessly across all modern browsers and devices.

---

## 📸 Screenshots

Home:  
![Home](Screenshots/home.png)

About:  
![About](Screenshots/about.png)

For Buyers:  
![For Buyers](Screenshots/for-buyers.png)

For Sellers:  
![For Sellers](Screenshots/for-sellers.png)

References:  
![References](Screenshots/References.png)

Contact:  
![Contact](Screenshots/Contact.png)
