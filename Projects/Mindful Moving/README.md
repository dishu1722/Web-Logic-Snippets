# ⚡ Mindful Moving - Performance Optimization & Core Web Vitals

A high-stakes performance optimization project for a New Jersey-based moving service. The focus was on drastically improving **Core Web Vitals**, specifically targeting **Total Blocking Time (TBT)** and **Cumulative Layout Shift (CLS)** to enhance user retention and search engine rankings.

## 📈 Performance Results (Verified via PageSpeed Insights)

| Metric | Desktop (Post-Optimization) | Mobile (Post-Optimization) |
| :--- | :--- | :--- |
| **Performance Score** | **98 / 100** | **65 / 100** (Up from ~40) |
| **Total Blocking Time** | **0 ms** | **0 ms** |
| **Largest Contentful Paint** | **1.0s** | **6.3s** (Significant Improvement) |
| **Cumulative Layout Shift** | **0.021** | **0.172** |

## 🛠️ Optimization Strategy

### **1. Eliminating Layout Shift (CLS)**
* **Stabilization:** Identified and fixed heavy layout shifts on the desktop header and hero sections by defining explicit dimensions for media and optimizing CSS delivery.
* **Visual Integrity:** Reduced Desktop CLS to a negligible **0.021**, ensuring a smooth, "jitter-free" loading experience for users.

### **2. Reducing Execution Time (TBT)**
* **Script Management:** Leveraged **WP-Rocket** and custom JavaScript deferral techniques to eliminate render-blocking resources.
* **Zero Latency:** Successfully reduced **Total Blocking Time to 0 ms** on both mobile and desktop, ensuring the main thread stays free for user interactions.

### **3. Asset & Image Optimization**
* **Next-Gen Delivery:** Converted legacy image formats to **WebP** and implemented aggressive lazy loading.
* **Font Loading:** Optimized web font delivery (FOUT/FOIT) to prevent text-flash and improve the **First Contentful Paint (FCP)** to 2.9s on mobile.

## 💡 The "Mindful" Solution
Working with the **Divi** builder often presents performance challenges due to heavy CSS/JS output. My role was to "trim the fat" by:
* **Minifying and Combining:** Drastically reducing the number of server requests.
* **Critical CSS:** Prioritizing the loading of "above-the-fold" styles.
* **Server-Side Tweaks:** Fine-tuning caching layers to deliver pre-rendered content to the browser instantly.

## 💻 Tech Stack & Tools
* **Analysis:** Google PageSpeed Insights, Lighthouse
* **Framework:** WordPress + Divi
* **Optimization:** WP-Rocket, Custom CSS, JavaScript Optimization
* **Assets:** Image Compression, Next-gen format conversion
