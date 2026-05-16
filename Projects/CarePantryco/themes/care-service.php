<?php 

// CARE SERVICE functions.php snippet


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

/**
 * 2. Clean up tabs - wrapped in a check to prevent errors
 */
add_filter( 'woocommerce_product_tabs', 'clean_service_tabs', 98 );
function clean_service_tabs( $tabs ) {
    if ( is_care_service_layout() ) {
        unset( $tabs['description'] ); // Full description moved to popup
        unset( $tabs['reviews'] );
        unset( $tabs['additional_information'] );
    }
    return $tabs;
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
var trustBar = $('#servicecare-trust-divider');
var summaryContainer = $('.summary.entry-summary'); // The first section
var whatsIncluded = $('#servicewhats-included-section');

if (trustBar.length && summaryContainer.length) {
    // This moves it OUTSIDE the summary container to create a separate section
    summaryContainer.after(trustBar);
    trustBar.show().css('display', 'flex'); 
}
if (whatsIncluded.length && trustBar.length) {
    // Places it after the trust badges bar
    trustBar.after(whatsIncluded);
 whatsIncluded.show();
}



// Add this logic right underneath your 'whatsIncluded' placement inside reorder_elements_js
var interactionSection = $('#service-dynamic-interaction-section');
var essentialsSection = $('.essentials-section'); // Grid wrapper created by your essentials function

if (interactionSection.length) {
    if (essentialsSection.length) {
        // Place it beautifully right under the grocery/essentials slider section
        essentialsSection.after(interactionSection);
    } else {
        // Fallback: Place it inside the main summary container if slider elements are missing
        $('.summary.entry-summary').append(interactionSection);
    }
    interactionSection.show();
}

// Interactivity handlers for interaction elements
$(document).on('change', '#rx_upload', function() {
    var fileName = $(this).val().split('\\').pop();
    $('#file-name-display').text(fileName ? "Selected: " + fileName : "");
});

$(document).on('input', '#service-dynamic-interaction-section textarea', function() {
    var len = $(this).val().length;
    $(this).closest('.interaction-card').find('.char-count').text(len + '/300');
});


$(document).on('click', '#service-submit-cart-btn', function(e) {
    e.preventDefault();
    
    var $btn = $(this);
    if ($btn.hasClass('processing')) return;
    
    var originalHtml = $btn.html();
    $btn.addClass('processing').html('Processing... <span class="spinner-loader"></span>').prop('disabled', true);
    
    var productId = <?php echo get_the_ID(); ?>;
    var notesText = $('#service-dynamic-interaction-section textarea').val();
    var fileInput = document.getElementById('rx_upload');

    // Use native FormData to safely transport files and text fields
    var formData = new FormData();
    formData.append('action', 'add_care_service_to_cart');
    formData.append('product_id', productId);
    formData.append('notes', notesText);

    if (fileInput && fileInput.files.length > 0) {
        formData.append('rx_file', fileInput.files[0]);
    }
    
    $.ajax({
        url: "<?php echo admin_url('admin-ajax.php'); ?>",
        type: 'POST',
        data: formData,
        contentType: false, // Required for FormData
        processData: false, // Required for FormData
        success: function(response) {
            if (response.success) {
                $btn.html('Added Successfully!').css({
    'background': '#06aa64',
    'white-space': 'nowrap',
    'width': 'max-content'
});
                $(document.body).trigger('wc_fragment_refresh');
                
                setTimeout(function() {
                    window.location.href = response.data.redirect_url;
                }, 800);
            } else {
                alert(response.data || 'Could not add service to cart. Please try again.');
                resetBtn();
            }
        },
        error: function(xhr, status, error) {
            // Detailed console logging to see exactly why your server is complaining
            console.error('Status:', status);
            console.error('Error Details:', error);
            console.error('Response Text:', xhr.responseText);
            alert('Server or connection error. Check console for details.');
            resetBtn();
        }
    });

    function resetBtn() {
        $btn.removeClass('processing').html(originalHtml).prop('disabled', false);
    }
});

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
max-width: 780px;
	margin: 40px auto;
	padding: 20px 20px;
}
.whats-included-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.whats-included-grid {
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 20px 20px;
    margin: 10px auto;
display: grid;
grid-template-columns: 1fr 1fr;
gap: 10px 20px;
}
.is-care-service .included-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-weight: 600;
}

/* Matching the green filled checkmark from image_cb6e7d.jpg */
.is-care-service .custom-check {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.is-care-service .custom-check circle {
    fill: #05a963; /* Brand green */
}

.is-care-service .custom-check path {
    fill: none;
    stroke: white; /* White checkmark inside */
    stroke-width: 2.5;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.service-interaction-wrapper {
    width: 100%;
    margin: 30px auto;
    clear: both;
	background-color: color(srgb 0.9353 0.96 0.9312 / 0.89) !important;
}
.interaction-title {
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 16px 0 !important;
    color: #111;
}
.interaction-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 20px;
}
.upload-box {
    background: #fff;
    border: 1px dashed #d1d1d1;
    border-radius: 12px;
    padding: 25px 15px;
    text-align: center;
    cursor: pointer;
    transition: background 0.2s ease, border-color 0.2s ease;
}
.upload-box:hover {
    border-color: #06aa64;
    background: #f7fdf9;
}
.upload-icon {
    width: 32px;
    height: 32px;
    color: #06aa64;
    margin-bottom: 8px;
}
.upload-content strong {
    display: block;
    font-size: 14px;
    color: #222;
}
.upload-content p {
    font-size: 11px;
    color: #888;
    margin: 4px 0 0;
    line-height: 1.4;
}
.notes-box label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    color: #222;
}
	.interaction-card {
    background: #fdfdfd;
    border: 1px solid #f2f2f2;
    border-radius: 16px;
    padding: 24px;
}
.interaction-card textarea {
    width: 100%;
    border: 1px solid #e2e2e2;
    border-radius: 10px;
    padding: 14px;
    font-size: 14px;
    min-height: 115px;
    resize: none;
    background: #fff;
    color: #333;
    line-height: 1.5;
}
.interaction-card textarea:focus {
    border-color: #06aa64;
    outline: none;
}
.char-count {
    font-size: 12px;
    color: #aaa;
    margin-top: 6px;
    text-align: left;
}
.security-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 16px;
    font-size: 12px;
    color: #777;
    border-top: 1px solid #f5f5f5;
    padding-top: 14px;
}
#service-submit-cart-btn:hover {
    background: #05a963 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 169, 99, 0.2);
}
#service-submit-cart-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
@media (max-width: 768px) {
    .interaction-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

</style>
    <?php
}

add_action( 'wp_footer', 'service_care_trust_section_html' );
function service_care_trust_section_html() {
    if ( !is_care_service_layout() ) return;
    ?>
    <div id="servicecare-trust-divider" class="hamper-trust-badges">
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

add_action( 'wp_footer', 'service_whats_included_section_html' );
function service_whats_included_section_html() {
    if ( !is_care_service_layout() ) return;

    // Fetch the Group Field
    $highlights = get_field('whats_include_display_points');

$text_info = strtolower( trim( get_the_title() ) );
$is_rx = (
    strpos($text_info, 'prescription') !== false
);
    
    // If the group exists, grab the individual points
    if( $highlights ) :
        $p1 = $highlights['list_first'];
        $p2 = $highlights['list_sec'];
        $p3 = $highlights['list_third'];
        $p4 = $highlights['list_four'];
    ?>
    <div id="servicewhats-included-section" class="whats-included-wrapper" style="display: none; padding-left: 30px; padding-right: 30px;">
        <div class="whats-included-header">
            <h2 style="margin: 0; font-weight: 700; text-align: left; font-size: 28px; line-height: normal;">What's included <span style="width: 30px; display: inline-block;">💚</span></h2>
            <a href="javascript:void(0);" id="serviceopenFullList" class="view-full-desc-trigger" style="color: #06aa64; font-size: 14px; font-weight: 700; cursor: pointer; letter-spacing: 0; text-align: right;">View full service description &rarr; </a>
        </div>
        
        <div class="whats-included-grid">
            <?php if($p1): ?><div class="included-item"><svg class="custom-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
<span><?php echo esc_html($p1); ?></span></div><?php endif; ?>
            <?php if($p2): ?><div class="included-item"><svg class="custom-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg><span><?php echo esc_html($p2); ?></span></div><?php endif; ?>
            <?php if($p3): ?><div class="included-item"><svg class="custom-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg><span><?php echo esc_html($p3); ?></span></div><?php endif; ?>
            <?php if($p4): ?><div class="included-item"><svg class="custom-check" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg><span><?php echo esc_html($p4); ?></span></div><?php endif; ?>
        </div>

        <div id="servicedynamic-disclaimer" class="modal-disclaimer" style="background-color: #f0f7f4; border-radius: 10px; padding: 12px 15px; display: flex; align-items: center; gap: 10px; width: fit-content; margin-top: 15px; margin-bottom: 30px;">
           <div style="background: #004d2c; color: #fff; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">i</div>
              <span class="disclaimer-text" style="color: #004d2c; font-size: 13px; font-weight: 500;"><?php echo $is_rx ? 'Medication costs are paid by the customer (we coordinate purchase).' : 'This service does not include medical treatment and emergency care.'; ?></span>
          </div>
       </div>

<!-- 2. The Pop-up Modal -->
    <div id="servicefullListModal" style="display:none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: center; justify-content: center;">
        <div style="background-color: #fff; padding: 40px; border-radius: 15px; width: 90%; max-width: 650px; max-height: 85vh; overflow-y: auto; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <span id="servicecloseModal" style="position: absolute; right: 20px; top: 20px; font-size: 30px; cursor: pointer; color: #000; font-weight: bold;">&times;</span>
            
            <div class="modal-body-content" style="line-height: 1.7; font-size: 15px; color: #243f2f;">
<h2 style="margin: 0; font-weight: 700; text-align: left; font-size: 28px; line-height: normal;">Full Service Description</h2>
<div>Everything you need to know about this service.</div>
<hr style="width: 100%; margin-top: 10px !important; color: rgb(226, 226, 226);">
                <?php the_content(); ?>
            </div>

           <div id="servicedynamic-disclaimer" class="modal-disclaimer" style="background-color: #f0f7f4; border-radius: 10px; padding: 12px 15px; display: flex; align-items: center; gap: 10px;">
           <div style="background: #004d2c; color: #fff; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">i</div>
              <span class="disclaimer-text" style="color: #004d2c; font-size: 13px; font-weight: 500;"><?php echo $is_rx ? 'Medication costs are paid by the customer (we coordinate purchase).' : 'Items may vary slightly based on availability.'; ?></span>
          </div>
        </div>
    </div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var modal = $('#servicefullListModal');
        var whatsIncluded = $('#servicewhats-included-section');
        var trustBar = $('#servicecare-trust-divider');

        // Position the section on the page
        if (whatsIncluded.length && trustBar.length) {
            trustBar.after(whatsIncluded);
            whatsIncluded.show();
        }

        // Open modal
        $('#serviceopenFullList').on('click', function(e) {
            e.preventDefault();
            modal.css('display', 'flex').hide().fadeIn(200);
            $('body').css('overflow', 'hidden'); 
        });

        // Close modal
        $('#servicecloseModal, #servicefullListModal').on('click', function(e) {
            if (e.target !== this && e.target.id !== 'servicecloseModal') return;
            modal.fadeOut(200);
            $('body').css('overflow', 'auto'); 
        });
    });
    </script>
    <?php
    endif;
}

/**
 * MAIN RENDER FUNCTION: Automated Essentials + Draggable Slider + Popup
 */
add_action( 'woocommerce_after_single_product_summary', 'service_essentials_system_v3', 10 );
function service_essentials_system_v3() {
    if ( !is_care_service_layout() ) return;

    // ... [Rest of your sub-category fetching logic here] ...
    $parent_cat = get_term_by('slug', 'all-groceries', 'product_cat');
    $sub_categories = ($parent_cat) ? get_terms(array('taxonomy' => 'product_cat', 'parent' => $parent_cat->term_id, 'hide_empty' => true)) : [];
    $essentials = get_field('essentials_list'); 

    ?>

  <div class="essentials-section" style="background-color: #fbfbfb;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h2 style="margin: 0; font-weight: 700; text-align: left;">Add essentials <span style="width: 30px; display: inline-block;">💚</span></h2>
            <a href="javascript:void(0);" id="serviceopenEssentialsPopup" style="color: #06aa64; font-size: 14px; font-weight: 700; cursor: pointer; letter-spacing: 0;">View all &rarr;</a>
        </div>
        <p style="margin-top: 0; margin-bottom: 15px;">Include everyday items to suppprt your loved one.</p>

        <div class="scroll-container-wrapper">
            <div class="nav-arrow arrow-left" onclick="document.getElementById('serviceess-drag-grid').scrollBy({left: -250, behavior: 'smooth'})">&larr;</div>
            
            <div class="essentials-grid" id="serviceess-drag-grid">
                <?php 
                if( $essentials ) {
                    $items_to_show = $essentials;
                } else {
                    $items_to_show = wc_get_products(array('status' => 'publish', 'limit' => 8, 'category' => array('all-groceries')));
                }

                if($items_to_show):
                    foreach( $items_to_show as $item ):
                        $prod = (is_object($item) && isset($item->ID)) ? wc_get_product($item->ID) : $item;
                        if($prod) render_single_essential_card($prod);
                    endforeach;
                endif; 
                ?>
            </div>

            <div class="nav-arrow arrow-right" onclick="document.getElementById('serviceess-drag-grid').scrollBy({left: 250, behavior: 'smooth'})">&rarr;</div>
        </div>
    </div>


<div id="serviceessentialsModal" style="display:none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: flex-end; justify-content: center; max-width: 100%;">
        <div style="background: #fff; width: 100%; max-width: 600px; height: 85vh; border-radius: 25px 25px 0 0; position: relative; display: flex; flex-direction: column; animation: slideUpMobile 0.3s ease-out;">
            <div style="padding: 20px 20px 10px;">
                <div style="width: 45px; height: 5px; background: #ddd; border-radius: 10px; margin: 0 auto 15px;"></div>
                <span id="servicecloseEssModal" style="position: absolute; right: 20px; top: 20px; font-size: 37px; cursor: pointer; color: #000;">&times;</span>
                <h2 style="margin: 0; font-weight: 700; text-align: left;">Add more essentials <span style="width: 30px; display: inline-block;">💚</span></h2>
                <p style="margin: 5px 0 20px;">Include more items for the whole family.</p>
                <div class="category-tabs" style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none;">
                    <?php if ( $sub_categories ) : 
                        foreach ( $sub_categories as $index => $category ) : ?>
                            <div class="tab-item <?php echo $index === 0 ? 'active' : ''; ?>" data-cat="<?php echo esc_attr($category->slug); ?>" style="padding: 8px 18px; border: 1px solid #eee; border-radius: 20px; white-space: nowrap; cursor: pointer; font-size: 14px; font-weight: 600; letter-spacing: 0;">
                                <?php echo esc_html($category->name); ?>
                            </div>
                        <?php endforeach; 
                    endif; ?>
                </div>
            </div>
            <div id="serviceessentialsContent" style="flex: 1; overflow-y: auto; padding: 10px 20px 120px;">
                <?php if ( $sub_categories ) : 
                    foreach ( $sub_categories as $index => $category ) : ?>
                        <div class="cat-panel" id="panel-<?php echo esc_attr($category->slug); ?>" style="display: <?php echo $index === 0 ? 'grid' : 'none'; ?>; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <?php 
                            $cat_prods = wc_get_products(array('status' => 'publish', 'limit' => 12, 'category' => array($category->slug)));
                            if($cat_prods) foreach($cat_prods as $cp) render_single_essential_card($cp, true);
                            ?>
                        </div>
                    <?php endforeach; 
                endif; ?>
            </div>
            <div style="padding: 12px 15px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fff; position: absolute; bottom: 0; width: 100%; border-radius: 15px; box-shadow: 0 -5px 15px rgba(0,0,0,0.05);">
                <a href="<?php echo wc_get_cart_url(); ?>" style="text-decoration: none; color: #333; font-weight: 500;">View cart &rarr;</a>
                <button id="servicedoneEss" style="background: #182d21; color: #fff; border: none; padding: 12px 50px; border-radius: 8px; font-weight: 600; cursor: pointer;">Done</button>
            </div>
        </div>
    </div>

<script>
jQuery(document).ready(function($) {
    // 1. USE DELEGATION TO OPEN MODAL
    // This works even if the button is moved or reordered by your other JS
    $(document).on('click', '#serviceopenEssentialsPopup', function(e) {
        e.preventDefault();
        console.log('Essentials button clicked'); // Debugging check
        $('#serviceessentialsModal').css('display', 'flex').hide().fadeIn(200);
        $('body').css('overflow', 'hidden'); 
    });

    // 2. CLOSE MODAL
    $(document).on('click', '#servicecloseEssModal, #servicedoneEss', function() {
        $('#serviceessentialsModal').fadeOut(200);
        $('body').css('overflow', 'auto'); 
    });

    // 3. TAB SWITCHING
    $(document).on('click', '.tab-item', function() {
        $('.tab-item').removeClass('active'); 
        $(this).addClass('active');
        $('.cat-panel').hide(); 
        $('#panel-' + $(this).data('cat')).css('display', 'grid');
    });

    // 4. AJAX ADD TO CART
    $(document).on('click', '.ajax_add_to_cart_essentials', function(e) {
        e.preventDefault(); 
        var $button = $(this);
        if ($button.hasClass('is-added')) return;

        var product_id = $button.data('product_id');
        var originalText = $button.html();

        $button.text('Adding...').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: typeof wc_add_to_cart_params !== 'undefined' ? wc_add_to_cart_params.ajax_url : '/wp-admin/admin-ajax.php', 
            data: {
                'action': 'woocommerce_add_to_cart',
                'product_id': product_id,
                'quantity': 1
            }
        }).always(function(response) {
            if (response && response.fragments) {
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
            }
            // Transition to "Added" state
            $button.addClass('is-added')
                   .text('Added')
                   .prop('disabled', true)
                   .css({
                       'background-color': '#06aa64', 
                       'color': '#fff',
                       'border-color': '#06aa64',
                       'cursor': 'default',
                       'opacity': '1'
                   });
        });
    });
});

// This "watcher" stays active at all times
$(document).on('click', '.btn-continue-essentials', function(e) {
    e.preventDefault();
    
    // Look for your specific View More link and click it
    var modalTrigger = document.getElementById('serviceopenEssentialsPopup');
    
    if (modalTrigger) {
        modalTrigger.click(); 
    } else {
        // Backup: in case ID is slightly different
        $('#serviceopenEssentialsPopup').click();
    }
});
</script>

<style>
  .essentials-section { margin-top: 50px; padding-bottom: 40px; clear: both; width: 100%; }
        .scroll-container-wrapper { position: relative; width: 100%; }
        
        .essentials-grid { 
            display: flex; 
            gap: 15px; 
            overflow-x: auto; 
            padding: 10px 5px; 
            -webkit-overflow-scrolling: touch; 
            scrollbar-width: none; 
            cursor: grab;
            user-select: none;
        }
        .essentials-grid::-webkit-scrollbar { display: none; }
        .essentials-grid:active { cursor: grabbing; }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #fff;
            border: 1px solid #eee;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
            color: #333;
            font-weight: bold;
        }
        .nav-arrow:hover { background: #06aa64; color: #fff; border-color: #06aa64; }
        .arrow-left { left: -37px; }
        .arrow-right { right: -37px; }

        @media (max-width: 768px) {
            .nav-arrow { display: none;}
			.sticky-bar-actions {
			width: 100%;
			justify-content: center;
		}
		.hamper-trust-badges{
			display:none;
		}	
		.hamper-delivery-info .elementor-icon {
		padding-right: 7px;
		}
		.hamper-delivery-info{
			margin-top: 10px;
		}

		.sticky-qty-selector {
		overflow: visible;
		}
			.sticky-submit-button {
		width: fit-content;
		padding: 10px 12px;
		}
		.inside-grid {
				display: flex !important;
				overflow-x: auto !important;
				flex-wrap: nowrap !important;
				gap: 10px !important;
				padding-bottom: 15px;
				-webkit-overflow-scrolling: touch;
				scrollbar-width: none; /* Hides scrollbar on Firefox */
	 }
    .inside-grid::-webkit-scrollbar {
        display: none; /* Hides scrollbar on Chrome/Safari */
    }
    .inside-card {
        flex: 0 0 40% !important; /* Shows roughly 2.5 items to hint at swiping */
        min-width: 130px;
    }
  }

        @keyframes slideUpMobile { from { transform: translateY(100%); } to { transform: translateY(0); } }
        .tab-item.active { background: color(srgb 0.9353 0.96 0.9312 / 0.89)!important; color: color(srgb 0.0216 0.6648 0.3909) !important; border-color: color(srgb 0.9353 0.96 0.9312 / 0.89) !important; }
        .ess-card-mini { 
display: flex; flex-wrap: wrap; align-items: center; gap: 7px; 
border: 1px solid #f2f2f2; 
padding: 12px; 
border-radius: 15px; 
background: #fff; 
}
</style>
<?php 
 

// Register the AJAX actions
add_action('wp_ajax_load_more_essentials', 'load_more_service_essentials_handler');
add_action('wp_ajax_nopriv_load_more_essentials', 'load_more_service_essentials_handler');

function load_more_service_essentials_handler() {
    // 1. Get current page and category slug from the AJAX request
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $category_slug = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : 'all-groceries';
    
    $args = array(
        'post_type'        => 'product',
        'post_status'      => 'publish',
        'posts_per_page'   => 12,
        'paged'            => $paged,
        'orderby'          => 'menu_order', 
        'order'            => 'ASC',
        'suppress_filters' => false, 
        'tax_query'        => array(
            array(
                'taxonomy'         => 'product_cat',
                'field'            => 'slug',
                'terms'            => $category_slug, 
                'operator'         => 'IN',
                'include_children' => ($category_slug === 'all-groceries') ? true : false, 
            ),
        ),
    );

    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            
            $id      = get_the_ID();
            $img_id  = $product->get_image_id();
            $img     = $img_id ? wp_get_attachment_image_url($img_id, 'thumbnail') : wc_placeholder_img_src();
            $title   = get_the_title();
            $price   = $product->get_price_html();

            // --- CHECK IF IN CART ---
           // --- DEEP SCAN CART CHECK ---
$is_in_cart = false;

if ( function_exists('WC') && !empty(WC()->cart) ) {
    // Get all product IDs currently in the cart
    $cart_ids = wp_list_pluck( WC()->cart->get_cart(), 'product_id' );
    
    // Check if current ID or its Variation ID is in that list
    if ( in_array($id, $cart_ids) ) {
        $is_in_cart = true;
    }
}
            ?>
            <div class="ess-card-mini">
                <img src="<?php echo esc_url($img); ?>" style="width: 55px; height: auto; border-radius: 8px;" loading="eager">
                <div style="flex: 1;">
                    <h4 style="font-size: 13px; margin: 0; font-weight: 500; color: #333;"><?php echo esc_html($title); ?></h4>
                    <p style="font-weight: 700; margin: 3px 0; font-size: 14px; color: #000;"><?php echo $price; ?></p>
                    
                    <?php if ( $is_in_cart ) : ?>
                        <button type="button" class="ajax_add_to_cart_essentials is-added" 
                                data-product_id="<?php echo $id; ?>" 
                                disabled 
                                style="background-color: #06aa64; color: #fff; font-size: 12px; font-weight: 700; border: 1px solid #06aa64; padding: 4px 12px; border-radius: 6px; margin: 4px 0; display: inline-block; cursor: default; opacity: 1;">
                            Added
                        </button>
                    <?php else : ?>
                        <button type="button" class="ajax_add_to_cart_essentials" 
                                data-product_id="<?php echo $id; ?>" 
                                style="color: #06aa64; font-size: 12px; font-weight: 700; border: 1px solid #06aa64; padding: 4px 12px; border-radius: 6px; margin: 4px 0; display: inline-block; background-color: #fff; cursor: pointer;">
                            + Add
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    }
    wp_die(); 
		}
		 ?>

			<script>

jQuery(document).ready(function($) {
    var canLoadMore = true;
    var currentPage = 1;
    var $scrollContainer = $('#serviceessentialsContent'); 

    // Handle Tab Swapping
    $('.tab-item').on('click', function() {
        // 1. Reset variables for the new category
        currentPage = 1;
        canLoadMore = true;
        
        // 2. Clear any "No more products" messages or loaders from panels
        $('.ess-no-more').remove();
        $('#service-ess-loader').remove();
        
        // 3. Reset scroll position
        $scrollContainer.scrollTop(0); 
    });

    $scrollContainer.on('scroll', function() {
        var scrollTop = $scrollContainer.scrollTop();
        var innerHeight = $scrollContainer.innerHeight();
        var scrollHeight = $scrollContainer[0].scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 150) {
            if (canLoadMore) {
                canLoadMore = false; 
                currentPage++;
                
                var activeCat = $('.tab-item.active').data('cat');

                // Loader with grid-column fix
                $('.cat-panel:visible').append('<div id="service-ess-loader" style="grid-column: 1 / -1; text-align:center; padding:15px; color:#06aa64; font-weight:700;">Loading...</div>');

                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: 'POST',
                    data: {
                        action: 'load_more_essentials',
                        paged: currentPage,
                        category: activeCat
                    },
                    success: function(data) {
    $('#service-ess-loader').remove();
    
    if (data.trim() !== "") {
        // 1. Convert the response string into a jQuery object
        var $newData = $(data);
        
        // 2. FORCE IMAGES TO LOAD: Strip lazy attributes and set eager loading
        $newData.find('img').each(function() {
            var $img = $(this);
            
            // Remove native lazy loading
            $img.attr('loading', 'eager'); 
            
            // Handle common plugin lazy load attributes (LiteSpeed, Smush, etc.)
            var lazySrc = $img.attr('data-src') || $img.attr('data-lazy-src') || $img.attr('data-orig-src');
            if (lazySrc) {
                $img.attr('src', lazySrc);
                $img.removeAttr('data-src data-lazy-src data-orig-src');
            }

            // Remove any hidden/opacity classes some plugins add
            $img.removeClass('lazyload lazyloading').css('opacity', '1');
        });

        // 3. Append the "cleaned" data to the visible panel
        $('.cat-panel:visible').append($newData);
        
        // 4. Trigger universal "new content" events for other plugins
        $(document.body).trigger('post-load');
        $(window).trigger('resize'); // Sometimes triggers lazy loaders to re-check
        
        canLoadMore = true; 
    } else {
        canLoadMore = false;
        if ($('.cat-panel:visible .ess-no-more').length === 0) {
            $('.cat-panel:visible').append('<div class="ess-no-more" style="grid-column: 1 / -1; text-align:center; padding:10px; font-size:12px; color:#999;">No more products in this category.</div>');
        }
    }
},
                    error: function() {
                        $('#service-ess-loader').remove();
                        canLoadMore = true;
                    }
                });
            }
        }
    });
});
</script>
    <?php					 }

add_action( 'wp_footer', 'service_interaction_section_html', 100 );
function service_interaction_section_html() {
    // Rely on your existing layout helper function
    if ( ! function_exists('is_care_service_layout') || ! is_care_service_layout() ) return;

    $is_rx = strpos(strtolower(get_the_title()), 'prescription') !== false;
    ?>
    <div id="service-dynamic-interaction-section" class="service-interaction-wrapper" style="display: none;">
        <?php if ($is_rx) : ?>
            <div class="interaction-card rx-layout">
                <h3 class="interaction-title" style="text-align: left;">Prescription & Notes (optional) <span style="width: 30px; display: inline-block;">💚</span></h3>
                <div class="interaction-grid">
                    <div class="upload-box" onclick="document.getElementById('rx_upload').click();">
                        <input type="file" id="rx_upload" style="display:none;" accept=".pdf,image/*">
                        <div class="upload-content">
                            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="12" y1="18" x2="12" y2="12"></line>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <strong>Upload Prescription / Script</strong>
                            <p>PDF, image or photo<br>Max 10MB</p>
                            <span id="file-name-display" style="color: #06aa64; font-weight: 600; font-size: 12px; margin-top: 5px; display: block;"></span>
                        </div>
                    </div>
                    <div class="notes-box">
                        <label>Additional Notes</label>
                        <textarea placeholder="Add pharmacy details, medication name, or any special instructions..." maxlength="300"></textarea>
                        <div class="char-count">0/300</div>
                    </div>
                </div>
                <div class="security-info">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Your files are secure and only used for this order.
                </div>
            </div>
        <?php else : ?>
            <div class="interaction-card standard-layout">
                <h3 class="interaction-title" style="text-align: left;">Additional notes (optional) <span style="width: 30px; display: inline-block;">💚</span></h3>
                <div class="notes-box-full">
                    <div class="textarea-wrapper" style="position: relative;">
                        <svg class="edit-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2" style="position: absolute; left: 15px; top: 15px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <textarea style="padding-left: 45px;" placeholder="Add any special instructions or requests for this service..." maxlength="300"></textarea>
                        <div class="char-count">0/300</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
<div class="service-action-footer" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f0f0f0; display: flex; justify-content: center; align-items: center; gap: 20px;">
    <button type="button" id="service-submit-cart-btn" class="single_add_to_cart_button button alt continuous-cart-btn" style="background: #243f2f; color: #fff; border: none; padding: 12px 40px; border-radius: 10px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 10px;">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; fill: none !important;">
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" style="fill: none !important;"></rect>
        <line x1="16" y1="2" x2="16" y2="6"></line>
        <line x1="8" y1="2" x2="8" y2="6"></line>
        <line x1="3" y1="10" x2="21" y2="10"></line>
    </svg>
        <span style="text-transform: uppercase;">Book <?php echo $is_rx ? 'Prescription' : 'Care'; ?> Service <span style="width: 18px; display: inline-block;"> 💚</span></span>
    </button>
</div>
    </div>
    <?php



// 1. Render data values clearly inside the Cart/Checkout layout loops
add_filter( 'woocommerce_get_item_data', 'render_care_service_meta_in_cart', 10, 2 );
function render_care_service_meta_in_cart( $item_data, $cart_item ) {
    if ( isset( $cart_item['care_service_notes'] ) ) {
        $item_data[] = array('name' => 'Notes', 'value' => $cart_item['care_service_notes']);
    }
    if ( isset( $cart_item['care_service_rx_name'] ) ) {
        $item_data[] = array('name' => 'Prescription', 'value' => '📄 ' . $cart_item['care_service_rx_name']);
    }
    return $item_data;
}

// 2. Lock the custom values down to final database records upon checkout submission
add_action( 'woocommerce_checkout_create_order_line_item', 'persist_care_service_meta_to_order', 10, 4 );
function persist_care_service_meta_to_order( $item, $cart_item_key, $values, $order ) {
    if ( isset( $values['care_service_notes'] ) ) {
        $item->add_meta_data( 'Customer Notes', $values['care_service_notes'] );
    }
    if ( isset( $values['care_service_rx_url'] ) ) {
        $item->add_meta_data( 'Prescription Script Attachment', $values['care_service_rx_url'] );
    }
}
}

add_action('wp_ajax_add_care_service_to_cart', 'handle_care_service_cart_upload');
add_action('wp_ajax_nopriv_add_care_service_to_cart', 'handle_care_service_cart_upload');

function handle_care_service_cart_upload() {
    if ( ! function_exists('WC') ) wp_send_json_error('WooCommerce not loaded');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $notes      = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';

    if (!$product_id) wp_send_json_error('Invalid Product ID');

    $custom_cart_data = array();
    
    if ( !empty($notes) ) {
        $custom_cart_data['care_service_notes'] = $notes;
    }

    // Handle native $_FILES array upload safely via WordPress core files
    if ( !empty($_FILES['rx_file']) && $_FILES['rx_file']['error'] === UPLOAD_ERR_OK ) {
        
        // Include core files if running inside an isolated AJAX scope
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        // Let WordPress validate and process the upload securely
        $attachment_id = media_handle_upload('rx_file', 0);

        if ( ! is_wp_error($attachment_id) ) {
            $file_url  = wp_get_attachment_url($attachment_id);
            $file_name = basename($file_url);

            $custom_cart_data['care_service_rx_url']  = $file_url;
            $custom_cart_data['care_service_rx_name'] = $file_name;
        } else {
            wp_send_json_error('File upload error: ' . $attachment_id->get_error_message());
        }
    }

    // Add item with bundled array data to active WooCommerce session cart
    $cart_item_key = WC()->cart->add_to_cart($product_id, 1, 0, array(), $custom_cart_data);

    if ( $cart_item_key ) {
        wp_send_json_success(array(
            'redirect_url' => wc_get_cart_url()
        ));
    } else {
        wp_send_json_error('Could not register item with WooCommerce session.');
    }

    wp_die();
}
add_filter('woocommerce_order_item_display_meta_value', function($value, $meta){
    return wp_kses_post($value);
}, 10, 2);

/**
 * Redirect specific Care service categories directly to their product pages
 */
add_action( 'template_redirect', 'redirect_service_categories_to_products' );
function redirect_service_categories_to_products() {
    // 1. Check if we are on a product category page
    if ( is_product_category() ) {
        $queried_object = get_queried_object();
        $slug = $queried_object->slug;

        // 2. Map the category slug to your specific product URL
        // Replace 'diabetic-food-hamper' with your actual category slug
        // Replace '/product/diabetic-support-pack/' with your actual product URL
        if ( $slug === 'medical-appointment-support-reporting' ) {
            wp_redirect( home_url( '/shop/medical-appointment-support/' ) );
            exit;
        }
        
        if ( $slug === 'wellness-check' ) {
            wp_redirect( home_url( '/shop/wellness-check/' ) );
            exit;
        }
 		if ( $slug === 'prescription-pick-up' ) {
            wp_redirect( home_url( '/shop/prescription-pick-up/' ) );
            exit;
        }
		if ( $slug === 'meal-preparation' ) {
            wp_redirect( home_url( '/shop/meal-preparation/' ) );
            exit;
        }
        if ( $slug === 'light-housekeeping' ) {
            wp_redirect( home_url( '/shop/light-housekeeping/' ) );
            exit;
        }
        if ( $slug === 'companionship' ) {
            wp_redirect( home_url( '/shop/companionship/' ) );
            exit;
        }
        if ( $slug === 'overnight-care-support' ) {
            wp_redirect( home_url( '/shop/overnight-care-support/' ) );
            exit;
        }
    }
}
