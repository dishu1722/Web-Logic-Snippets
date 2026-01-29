/* ============================================================
   1. SUCCESS STORIES TABS
   ============================================================ */
jQuery(document).ready(function($) {
    // Tab switching logic for Success Stories
    $('.tabs2 ul li').on('click', function() {
        // 1. Get the index of the clicked tab
        var tabIndex = $(this).index();

        // 2. Manage tab active states
        $('.tabs2 ul li').removeClass('current');
        $(this).addClass('current');

        // 3. Manage content box visibility
        $('.box-content .box').hide().removeClass('current'); // Hide all
        $('.box-content .box').eq(tabIndex).fadeIn().addClass('current'); // Show clicked index
    });

/* ============================================================
   2. VIDEO POPUP CAROUSEL (OWL CAROUSEL)
   ============================================================ */
  
   // [Your Owl Carousel Code Here]
  // Initialize Video Slider
    $('.care-edu-video-slider').owlCarousel({
        loop: true,
        margin: 21,
        nav: true,
        dots: true,
        navText: [
            "<img src='.../arrow-white.png'>", 
            "<img src='.../arrow-white.png'>"
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });

    // Reconstructed logic for Video Popups (using a typical Lightbox)
    $('.videobox-block a').on('click', function(e) {
        e.preventDefault();
        var videoUrl = $(this).attr('href');
        // Logic to trigger a modal/lightbox would go here
        console.log("Opening video popup for: " + videoUrl);
    });

  
  /* ============================================================
   3. Resource Filter Logic
   ============================================================ */
  
  $('#my-filter li').on('click', function() {
        var filterIndex = $(this).index();

        // Update active tab styling
        $('#my-filter li').removeClass('current');
        $(this).addClass('current');

        // Toggle visibility of the resource blocks
        $('.filteration-blogs .box').hide();
        $('.filteration-blogs .box').eq(filterIndex).stop().fadeIn(500);
    });


 /* ============================================================
   4. Customizing Search & Filter Pro Radio Buttons to look like UI Tabs
   ============================================================ */

   $('.sf-field-category input[type="radio"]').on('change', function() {
        $('.sf-label-radio').removeClass('active-tab');
        if($(this).is(':checked')) {
            $(this).parent().find('label').addClass('active-tab');
        }
    });


/* ============================================================
   5. Job Flip Logic
   ============================================================ */

   // Open Job Description (Flip to Back)
    $('.job-desc-btn').on('click', function() {
        var $parent = $(this).closest('.job-single-box');
        
        $parent.find('.job-front').fadeOut(300, function() {
            $parent.find('.job-back').fadeIn(300);
        });
    });

    // Close Job Description (Return to Front)
    $('.job-close').on('click', function() {
        var $parent = $(this).closest('.job-single-box');
        
        $parent.find('.job-back').fadeOut(300, function() {
            $parent.find('.job-front').fadeIn(300);
        });
    });
});

