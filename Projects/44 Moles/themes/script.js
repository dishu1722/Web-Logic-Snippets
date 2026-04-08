// Scrolling logo Carousel

document.addEventListener('DOMContentLoaded', function() {
    const swiperWrapper = document.querySelector('.miro-marquee .swiper-wrapper');
    
    if (swiperWrapper) {
        // 1. Force the container to be a single, non-wrapping line
        swiperWrapper.style.display = 'flex';
        swiperWrapper.style.flexWrap = 'nowrap';
        swiperWrapper.style.width = 'max-content';
        
        // 2. Measure the width of your original 4 logos
        const originalWidth = swiperWrapper.scrollWidth;

        // 3. Buffer the track heavily (6 sets total)
        // This ensures the "tail" of the animation is always visible on the right
        const logoSet = swiperWrapper.innerHTML;
        swiperWrapper.innerHTML = logoSet + logoSet + logoSet + logoSet + logoSet + logoSet;

        // 4. Set the exact pixel distance for one full cycle
        swiperWrapper.style.setProperty('--scroll-distance', `-${originalWidth}px`);
        
        // 5. Kill any Elementor JS that tries to move the slides
        swiperWrapper.style.transitionProperty = 'none';
        swiperWrapper.style.transform = 'translateX(0)';
    }
});
