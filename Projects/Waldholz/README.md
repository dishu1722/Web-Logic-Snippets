# 🌲 Exclusive Forest & Land - Premium Real Estate UI/UX

A high-fidelity real estate platform designed for luxury land and forest acquisitions. This project bridge the gap between high-end Figma prototyping and custom WordPress development, featuring bespoke interactive components and a data-driven property engine.

## 🛠️ Technical Implementation & Innovation

### **1. Advanced Map Interactivity (JavaScript/SVG)**
* **Geometric Region Detection:** Engineered a custom JavaScript solution for the MapGeo plugin to subdivide a single SVG path (United Kingdom) into distinct interactive regions (Scotland vs. England). 
* **Coordinate-Based Tooltips:** Implemented logic to calculate mouse positions relative to SVG bounding boxes, dynamically updating tooltip content based on the vertical and horizontal percentage of the path being hovered.
* **UX Micro-interactions:** Developed a custom-styled, persistent tooltip system with CSS transitions and arrow-anchoring for precise region identification.

### **2. Dynamic Content Architecture (PHP)**
* **Custom Post Type (CPT) Engine:** Architected a `property` post type from scratch, including custom support for thumbnails, excerpts, and REST API integration for future scalability.
* **Bespoke Owl Carousel Shortcode:** Developed a custom PHP shortcode that queries the Property CPT and initializes an **Owl Carousel** with "Stage Padding" to create a modern, offset "peek-a-boo" effect.
* **Query Optimization:** Utilized `WP_Query` with proper data resetting to ensure the carousel remains lightweight and doesn't interfere with global page performance.

### **3. UI/UX Design & Frontend Artistry**
* **Figma to WordPress Pipeline:** Designed a premium aesthetic in Figma using the **Cormorant Garamond** and **Inter** font families to evoke trust and elegance, then translated it into Elementor-ready components.
* **Complex CSS Geometry:** Created a unique "Zig-Zag" brand element using advanced `clip-path` polygon math and `aspect-ratio` properties to ensure the design maintains its visual edge across all resolutions.
* **Sticky Header Logic:** Scripted a scroll-aware header that transitions styles dynamically, improving navigation accessibility on long-scrolling land listing pages.

## 💡 The "Exclusive" Solution
Luxury real estate requires a unique balance of data and atmosphere. I achieved this by:
* **Interactive Exploration:** Allowing users to explore properties via a region-sensitive map rather than just a list.
* **Visual Momentum:** Using an infinite offset slider to showcase the "Scale" of the land offerings.
* **Feasibility First:** Designing in Figma with a deep understanding of Elementor's capabilities, ensuring the final build was 100% faithful to the design.

## 💻 Tech Stack & Tools
* **Design:** Figma, Adobe Photoshop
* **Development:** PHP (CPT & Shortcodes), JavaScript (jQuery), CSS3 (Clip-path/Flex)
* **WordPress Tools:** Elementor, MapGeo Plugin, Owl Carousel 2
