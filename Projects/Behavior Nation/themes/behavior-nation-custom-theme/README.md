# Behavior Nation - Custom Enterprise WordPress Theme

**Live Project:** [[https://behaviornation.com](https://www.behaviornation.com)]  

## 🏥 Project Overview
Developed a high-performance, custom-coded WordPress theme for **Behavior Nation**, an ABA (Applied Behavior Analysis) service provider. The project focused on creating an accessible, resource-heavy platform for parents and healthcare professionals, requiring complex information architecture and interactive UI components.

## 🚀 Engineering Highlights
* **Custom Framework:** Built from scratch using PHP, JavaScript (jQuery), and SASS, moving away from bloated page builders to achieve a **95+ Google PageSpeed score**.
* **Dynamic Resource Filtering:** Engineered a category-based filtering system using **WP_Query and Custom Taxonomies**, allowing instant access to insurance and medical documents.
* **Interactive UI/UX:** Developed a dual-state "Card-Flip" Careers Portal and a Video Popup Gallery to manage heavy content without overwhelming the user.
* **AJAX Integration:** Implemented asynchronous content loading for the blog (ultrABAsics) with infinite scroll to improve mobile engagement.

## 🛠️ Technical Stack
* **Back-End:** PHP 8.x, WordPress Core, WP_Query API
* **Front-End:** JavaScript (ES6), jQuery, SASS/CSS3, HTML5
* **Tools:** Owl Carousel (Sliders), Search & Filter Pro (AJAX Logic), SVG Optimization

## 📂 Key Files & Logic
| File | Responsibility |
| :--- | :--- |
| [`php-logic/resource-filter.php`](./php-logic/resource-filter.php) | Handles the taxonomy-based loop for medical resources. |
| [`php-logic/custom-blog-loop.php`](./php-logic/custom-blog-loop.php) | Logic for the Hero post and the AJAX-enabled blog grid. |
| [`assets/js/custom-scripts.js`](./assets/js/custom-scripts.js) | Powers the Job-Flip UI, Video Slider, and Filter Toggles. |
| [`assets/css/main-style.css`](./assets/css/styles.css) | Custom responsive grid layouts and card animations. |

## 💡 Problem Solved
**The Challenge:** The client had hundreds of legal and educational resources that were difficult for parents to navigate on mobile.
**The Solution:** I designed a "Tabbed Filter" interface that reduced the number of clicks needed to find a document by 60%. I also implemented a "Job-Back" description toggle to keep the Careers page concise and professional.

---
*Developed as a lead Software Developer at [Stark Edge].*
