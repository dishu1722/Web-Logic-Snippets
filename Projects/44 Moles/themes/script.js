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

// Changing the logo and Background on scroll
document.addEventListener('DOMContentLoaded', function() {
  const header = document.querySelector('.header-scroll');
  const logoImg = document.querySelector('.header-scroll .elementor-widget-theme-site-logo img');
	
  const logoLight = 'https://new.44moles.com/wp-content/uploads/2026/02/Logo-light-gradient-scaled.png';
  const logoDark = 'https://new.44moles.com/wp-content/uploads/2026/04/Logo-dark-gradient-scaled-1.png';
  
  if (header) {
    const bgLayer = document.createElement('div');
    bgLayer.className = 'header-bg-layer';
    
    Object.assign(bgLayer.style, {
      position: 'absolute',
      top: '0',
      left: '50%',              
      transform: 'translateX(-50%)', 
      width: '730px',             
      height: '100%',
      backgroundColor: '#ffffff', 
      opacity: '0',
      transition: 'opacity 0.3s ease',
      zIndex: '-1',
      borderRadius: '6.25rem'   // Creates the rounded "pill" ends
    });

    // Parent header adjustments
    header.style.position = 'fixed'; 
    header.style.width = '100%';
    header.style.left = '0';
    header.style.zIndex = '999';

    header.prepend(bgLayer);

    const updateWidth = () => {
      if (window.innerWidth < 1025) {
        bgLayer.style.width = '630px'; // Wider on tablet (less than 1025px)
      } 
	  if (window.innerWidth < 768) {
        bgLayer.style.width = '96%'; // Wider on mobile (less than 768px)
      } 
    };

    // Initialize width on load
    updateWidth();

    window.addEventListener('scroll', function() {
  const isScrolled = window.scrollY > 10;

  bgLayer.style.opacity = isScrolled ? '1' : '0';
  header.classList.toggle('hdr-change', isScrolled);

  if (logoImg) {
    if (isScrolled) {
      logoImg.src = logoDark;
      logoImg.srcset = logoDark; 
    } else {
      logoImg.src = logoLight;
      logoImg.srcset = logoLight; 
    }
  }
});

    // Update width if window is resized
    window.addEventListener('resize', updateWidth);
  }
});

// Hiding the logo when scrolled up and show when scrolled down in Mobile
let lastScrollTop = 0;
const header = document.querySelector(".header-move");

window.addEventListener("scroll", function() {
  // Only run if the screen width is 1024px or less
  if (window.innerWidth <= 1024) {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop && scrollTop > 100) {
      // User is scrolling down
      header.classList.add("header-up");
    } else {
      // User is scrolling up
      header.classList.remove("header-up");
    }

    // Update lastScrollTop, catching the "bounce" on mobile browsers
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  } else {
    // Safety: ensure header is visible if user resizes window to desktop
    header.classList.remove("header-up");
  }
}, false);
