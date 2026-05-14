<?php
function is_care_service_layout() {
    if ( ! is_product() ) {
        return false;
    }
    return has_term( 'care-services', 'product_cat' );
}

/**
 * Add "is-care-service" class to the body tag for products in the care-services category
 */
add_filter( 'body_class', 'add_care_service_body_class' );
function add_care_service_body_class( $classes ) {
    // Check if we are on a single product page and if it has the 'care-services' category
    if ( is_product() && has_term( 'care-services', 'product_cat' ) ) {
        $classes[] = 'is-care-service';
    }
    return $classes;
}

add_action( 'wp_footer', 'reorder_elements_js', 99 );
function reorder_elements_js() {
    if ( !is_care_service_layout() ) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // 1. Identify current context more reliably
        var titleElement = $('.product_title');
        var titleString  = titleElement.text().toLowerCase();
        var currentUrl   = window.location.href.toLowerCase();
        
        // Use standard selectors as fallback if custom ones aren't found
        var titleRow  = $('.c-product__share-row').length ? $('.c-product__share-row') : titleElement;
        var reviews   = $('.woocommerce-product-rating');
        var shortDesc = $('.c-product__short-description').length ? $('.c-product__short-description') : $('.woocommerce-product-details__short-description');
        var price     = $('p.price');


$('.summary.entry-summary form.cart .c-product__atc-wrap').remove();
        // 2. Strict Prescription Logic
        // Checks if 'prescription' is in the Title OR the URL
        var isPrescription = titleString.includes('prescription') || currentUrl.includes('prescription');

        // 3. Handle Reviews Placeholder
        if (reviews.length === 0) {
            reviews = $('<div class="woocommerce-product-rating" style="margin-bottom:10px; margin-top: 15px;">⭐⭐⭐⭐⭐ (No reviews yet)</div>');
        }

       titleRow.after(reviews);
        reviews.after(shortDesc);
        shortDesc.after(price);
        
if (price.length > 0) {
    // Remove any existing subtext first to prevent duplicates
    $('.price-subtext').remove();

    var subtext = isPrescription ? 'Per pickup & delivery' : 'One-time visit';
    
    price.after('<div class="price-subtext" style="margin-bottom: 15px;">' + subtext + '</div>');
   
}


// Add this inside your existing reorder_elements_js jQuery block
var trustBar = $('#care-trust-divider');
var summaryContainer = $('.summary.entry-summary'); // The first section

if (trustBar.length && summaryContainer.length) {
    // This moves it OUTSIDE the summary container to create a separate section
    summaryContainer.after(trustBar);
    trustBar.show().css('display', 'flex'); 
}


    });
    </script>
<style>
.is-care-service .c-product-features, .is-care-service .c-product__custom-html {
    display: none;
}
body.is-care-service {
	background-color: #fff;
}
body.is-care-service .c-product__section{
	border: none;
}
body.is-care-service .hamper-trust-badges {
    justify-content: center;
    gap: 45px;
max-width: 1060px;
	margin: 20px auto;
	padding: 20px 20px;
}

</style>
    <?php
}

add_action( 'wp_footer', 'render_care_trust_section_html' );
function render_care_trust_section_html() {
    if ( !is_care_service_layout() ) return;
    ?>
    <div id="care-trust-divider" class="hamper-trust-badges">
		<div style="display: flex; align-items: center; gap: 8px; font-size: 16px; font-weight: 600;">
			<svg width="40" height="40" viewBox="0 0 24 24" style="fill: none !important; stroke: color(srgb 0.0216 0.6648 0.3909); stroke-width: 2px; stroke-linecap: round; stroke-linejoin: round; display: inline-block;">
            	<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        	</svg>
            Trusted
       </div>
        <div style="display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 600;">
<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="outline-icon" style="color: color(srgb 0.0216 0.6648 0.3909);"><circle cx="12" cy="12" r="10" fill="none"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            On-Time
       </div>
       <div style="display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 600;">
           <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="outline-icon" style="color: color(srgb 0.0216 0.6648 0.3909);">
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" fill="none"></rect>
        <path d="M7 11V7a5 5 0 0 1 10 0v4" fill="none"></path>
    </svg>
 			Private
       </div>
<div style="display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 600;">
           <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="outline-icon" style="color: color(srgb 0.0216 0.6648 0.3909);">
        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" fill="none"></path>
    </svg>
 			Updates
       </div>
    </div>
    <?php
}
