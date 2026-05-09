/**
 * 1. Robust check for the category
 */
function is_care_hamper_layout() {
    // Add every category slug that should use this special layout
    $hamper_categories = array(
        'care-packs', 
        'diabetic-food-hamper', 
        'elderly-food-hamper', 
        'immunity-food-hamper',
		'recovery-food-hamper',
    );

    if ( is_product() && has_term( $hamper_categories, 'product_cat' ) ) {
        return true;
    }
    
    return false;
}

/**
 * 2. Clean up tabs - wrapped in a check to prevent errors
 */
add_filter( 'woocommerce_product_tabs', 'clean_hamper_tabs', 98 );
function clean_hamper_tabs( $tabs ) {
    if ( is_care_hamper_layout() ) {
        unset( $tabs['description'] ); // Full description moved to popup
        unset( $tabs['reviews'] );
        unset( $tabs['additional_information'] );
    }
    return $tabs;
}


/**
 * 3. Rearrange Product Summary: Title -> Reviews -> Price -> Short Description
 */
add_action( 'wp_footer', 'reorder_summary_elements_js' );
function reorder_summary_elements_js() {
    if ( !is_product() || !has_term( array('diabetic-food-hamper', 'care-packs'), 'product_cat' ) ) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Define our target container and elements
        var titleRow  = $('.c-product__share-row');
        var price     = $('p.price');
        var shortDesc = $('.c-product__short-description');
        var reviews   = $('.woocommerce-product-rating');
        var squareTag = $('square-placement'); // Targets the installment badge

        // 1. Handle Reviews (Use existing or create placeholder)
        if (reviews.length === 0) {
            reviews = $('<div class="woocommerce-product-rating" style="margin-bottom:10px; margin-top: 15px;">⭐⭐⭐⭐⭐ (No reviews yet)</div>');
        }

        // 2. Perform the Move in sequence:
        
        // Move Reviews after Title
        titleRow.after(reviews);
        
        // Move Price after Reviews
        reviews.after(price);
        
        // Move Square Badge right after Price
        if (squareTag.length > 0) {
            price.after(squareTag);
            // Move Short Desc after Square Badge
            squareTag.after(shortDesc);
        } else {
            // Fallback if Square isn't present: Move Short Desc after Price
            price.after(shortDesc);
        }
    });
    </script>
    <?php
}


/**
 * 4. Add Delivery Information Section
 */
add_action( 'woocommerce_single_product_summary', 'add_hamper_delivery_section', 25 );
function add_hamper_delivery_section() {
    if ( !is_care_hamper_layout() ) return;
    ?>

<!-- Healthy Choices / Trust Badges Block -->
		
<div class="hamper-trust-badges">
        <div style="display: flex; align-items: center; gap: 6px; color: color(srgb 0.0216 0.6648 0.3909); font-size: 14px; font-weight: 500;">
			<svg width="20" height="20" viewBox="0 0 24 24" style="fill: none !important; stroke: color(srgb 0.0216 0.6648 0.3909); stroke-width: 2px; stroke-linecap: round; stroke-linejoin: round; display: inline-block;">
				<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
				<polyline points="22 4 12 14.01 9 11.01"></polyline>
        	</svg>
            Healthy choices
       </div>
       <div style="display: flex; align-items: center; gap: 6px; color: color(srgb 0.0216 0.6648 0.3909); font-size: 14px; font-weight: 500;">
			<svg width="20" height="20" viewBox="0 0 24 24" style="fill: none !important; stroke: color(srgb 0.0216 0.6648 0.3909); stroke-width: 2px; stroke-linecap: round; stroke-linejoin: round; display: inline-block;">
            	<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        	</svg>
            Trusted quality
       </div>
       <div style="display: flex; align-items: center; gap: 6px; color: color(srgb 0.0216 0.6648 0.3909); font-size: 14px; font-weight: 500;">
            <svg width="20" height="20" viewBox="0 0 24 24" style="fill: none !important; stroke: color(srgb 0.0216 0.6648 0.3909); stroke-width: 2px; stroke-linecap: round; stroke-linejoin: round; display: inline-block;">
            	<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
        	</svg>
 			Made with care
       </div>
</div>


<!-- Delivery Info Block  -->
		
    <div class="hamper-delivery-info">
        <h4>Delivery</h4>
		<div class="del-outer">
			<div class="del-row">
				<span class="elementor-icon elementor-animation-rotate">
				<i aria-hidden="true" class="fibd21- fi-bd21-delivery-2"></i>				
				</span> 
				<div><strong>We deliver across Zimbabwe</strong><br>Reliable, safe & on time.					</div>		
			</div>
			<div class="del-row"><span class="elementor-icon">
					<i aria-hidden="true" class="fibd21- fi-bd21-time"></i>				</span> <div>Same or next-day delivery (Mon-Sat)</div></div>
			<div class="del-row"><span class="elementor-icon elementor-animation-rotate">
					<i aria-hidden="true" class="fib154- fi-b154-heart-circled"></i>				</span> <div>Delivered with care, just like you would</div></div>
		</div>
   </div>
    <?php
}



add_action( 'woocommerce_after_single_product_summary', 'render_whats_inside_groups', 5 );
function render_whats_inside_groups() {
    if ( !is_care_hamper_layout() ) return;

    $group_names = array('pack_item_first', 'pack_item_sec', 'pack_item_three', 'pack_item_four', 'pack_item_five'); 
    $first_group = get_field('pack_item_first');
    
    if ( $first_group ) {
        echo '<div class="whats-inside-section" style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 30px; padding-bottom: 15px; clear: both;">';
            echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">';
                echo '<h2 style="margin: 0; font-weight: 700;">What\'s inside</h2>';
                
                // UPDATED: Changed span to a clickable anchor tag
                echo '<a href="javascript:void(0);" id="openFullList" style="color: #06aa64; font-size: 14px; font-weight: 700; cursor: pointer; letter-spacing: 0;">View full list &rarr;</a>';
            
            echo '</div>';
            echo '<div class="inside-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px;">';
                
                foreach ( $group_names as $name ) {
                    $group = get_field($name);
                    if ( $group && (!empty($group['inside_item_image']) || !empty($group['inside_item_name'])) ) {
                        
                        $img_data = $group['inside_item_image'];
                        $img_url = is_array($img_data) ? $img_data['url'] : $img_data;
                        $item_n  = !empty($group['inside_item_name']) ? esc_html($group['inside_item_name']) : 'Item';
                        $item_w  = !empty($group['inside_item_weight']) ? esc_html($group['inside_item_weight']) : '';
                        
                        echo '<div class="inside-card" style="background: #ffffff; padding: 15px; text-align: center;">';
                            echo '<div style="height: 150px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; border: 1px solid #e9e9e9; border-radius: 10px;">';
                                if($img_url) {
                                    echo '<img src="'.esc_url($img_url).'" alt="'.$item_n.'" style="max-width: 100px; height: auto; object-fit: contain;">';
                                }
                            echo '</div>';
                            echo '<h4 style="font-size: 14px; margin: 5px 0; font-weight: 700;">'.$item_n.'</h4>';
                            echo '<p style="color: #888; margin: 0;">'.$item_w.'</p>';
                        echo '</div>';
                    }
                }
                
            echo '</div>';
        echo '</div>';

        // NEW: Modal HTML structure
        ?>
        <div id="fullListModal" style="display:none; position: fixed; z-index: 99999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: center; justify-content: center; max-width: 100%;">
            <div style="background-color: #fff; padding: 40px; border-radius: 15px; width: 90%; max-width: 650px; max-height: 85vh; overflow-y: auto; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <span id="closeModal" style="position: absolute; right: 20px; top: 28px; font-size: 45px; cursor: pointer; color: #000; line-height: 1;">&times;</span>
                <!-- <h2 style="margin-top: 0; color: #000000; padding-bottom: 15px; text-align: left;">What's inside</h2> -->
                <div style="line-height: 1.7; font-size: 15px;">
                    <?php the_content(); ?>
                </div>
				<div class="modal-disclaimer" style="background-color: #f0f7f4; border-radius: 10px; padding: 12px 15px; display: flex; align-items: center; gap: 10px;">
                    <div style="background: #004d2c; color: #fff; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">i</div>
                    <span style="color: #004d2c; font-size: 13px; font-weight: 500;">Items may vary slightly based on availability.</span>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            var modal = $('#fullListModal');
            
            // Open modal
            $('#openFullList').on('click', function() {
                modal.css('display', 'flex').hide().fadeIn(200);
                $('body').css('overflow', 'hidden'); // Stop page scroll
            });

            // Close modal via X button or clicking outside the box
            $('#closeModal, #fullListModal').on('click', function(e) {
                if (e.target !== this && e.target.id !== 'closeModal') return;
                modal.fadeOut(200);
                $('body').css('overflow', 'auto'); // Restore page scroll
            });
        });
        </script>
        <?php
    }
}


/**
 * MAIN RENDER FUNCTION: Automated Essentials + Draggable Slider + Popup
 */
add_action( 'woocommerce_after_single_product_summary', 'render_essentials_system_v2', 10 );
function render_essentials_system_v2() {
     if ( !is_care_hamper_layout() ) return;

    $parent_cat = get_term_by('slug', 'all-groceries', 'product_cat');
    if ( !$parent_cat ) return;

    $sub_categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'parent'     => $parent_cat->term_id,
        'hide_empty' => true,
    ) );

    $essentials = get_field('essentials_list'); 

    ?>
    <style>
body.single-product, 
        #main, 
        #content,
        .site-content { 
            background-color: #ffffff !important; 
        }
	.is-hamper-product .c-product__section {
    border: none;
}
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
        .ess-card-mini { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; border: 1px solid #f2f2f2; padding: 12px; border-radius: 15px; background: #fff; }
    </style>

    <div class="essentials-section" style="background-color: #fbfbfb;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <h2 style="margin: 0; font-weight: 700; text-align: left;">Add Essentials <span style="width: 30px; display: inline-block;">💚</span></h2>
            <a href="javascript:void(0);" id="openEssentialsPopup" style="color: #06aa64; font-size: 14px; font-weight: 700; cursor: pointer; letter-spacing: 0;">View more &rarr;</a>
        </div>
        <p style="margin-top: 0; margin-bottom: 15px;">Include everyday items for everyone at home.</p>

        <div class="scroll-container-wrapper">
            <div class="nav-arrow arrow-left" onclick="document.getElementById('ess-drag-grid').scrollBy({left: -250, behavior: 'smooth'})">&larr;</div>
            
            <div class="essentials-grid" id="ess-drag-grid">
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

            <div class="nav-arrow arrow-right" onclick="document.getElementById('ess-drag-grid').scrollBy({left: 250, behavior: 'smooth'})">&rarr;</div>
        </div>
    </div>

    <div id="essentialsModal" style="display:none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: flex-end; justify-content: center; max-width: 100%;">
        <div style="background: #fff; width: 100%; max-width: 600px; height: 85vh; border-radius: 25px 25px 0 0; position: relative; display: flex; flex-direction: column; animation: slideUpMobile 0.3s ease-out;">
            <div style="padding: 20px 20px 10px;">
                <div style="width: 45px; height: 5px; background: #ddd; border-radius: 10px; margin: 0 auto 15px;"></div>
                <span id="closeEssModal" style="position: absolute; right: 20px; top: 20px; font-size: 37px; cursor: pointer; color: #000;">&times;</span>
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
            <div id="essentialsContent" style="flex: 1; overflow-y: auto; padding: 10px 20px 120px;">
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
                <button id="doneEss" style="background: #182d21; color: #fff; border: none; padding: 12px 50px; border-radius: 8px; font-weight: 600; cursor: pointer;">Done</button>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Modal toggles
        $('#openEssentialsPopup').on('click', function() { $('#essentialsModal').css('display', 'flex'); $('body').css('overflow', 'hidden'); });
        $('#closeEssModal, #doneEss').on('click', function() { $('#essentialsModal').hide(); $('body').css('overflow', 'auto'); });
        
        // Tab switching
        $('.tab-item').on('click', function() {
            $('.tab-item').removeClass('active'); $(this).addClass('active');
            $('.cat-panel').hide(); $('#panel-' + $(this).data('cat')).css('display', 'grid');
        });

// 3. AJAX Add to Cart (The code you need to add)
    $(document).on('click', '.ajax_add_to_cart_essentials', function(e) {
        e.preventDefault(); 
        
        var $button = $(this);
        var product_id = $button.data('product_id');
        var originalText = $button.html();

        $button.text('Adding...').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: wc_add_to_cart_params.ajax_url, 
            data: {
                'action': 'woocommerce_add_to_cart',
                'product_id': product_id,
                'quantity': 1
            },
            success: function(response) {
                if (response.error && response.product_url) {
                    window.location = response.product_url;
                    return;
                }

                // Updates the header cart count/total automatically
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                
                // Visual feedback: button turns green
                $button.text('Added!').css({
                    'background-color': '#06aa64', 
                    'color': '#fff',
                    'border-color': '#06aa64'
                });
                
                // Reset button after 2 seconds
                setTimeout(function() {
                    $button.html(originalText).prop('disabled', false).css({
                        'background-color': '#fff', 
                        'color': '#06aa64',
                        'border-color': '#06aa64'
                    });
                }, 2000);
            }
        });
    });



        // Mouse Drag-to-Scroll
        const slider = document.querySelector('#ess-drag-grid');
        let isDown = false; let startX; let scrollLeft;
        slider.addEventListener('mousedown', (e) => { isDown = true; startX = e.pageX - slider.offsetLeft; scrollLeft = slider.scrollLeft; });
        slider.addEventListener('mouseleave', () => { isDown = false; });
        slider.addEventListener('mouseup', () => { isDown = false; });
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    });

jQuery(document).ready(function($) {
    var canLoadMore = true;
    var currentPage = 1;
    var $scrollContainer = $('#essentialsContent'); 

    // Handle Tab Swapping: Reset pagination when user clicks a new category
    $('.tab-item').on('click', function() {
        currentPage = 1;
        canLoadMore = true;
        $scrollContainer.scrollTop(0); // Scroll back to top for new category
    });

    $scrollContainer.on('scroll', function() {
        var scrollTop = $scrollContainer.scrollTop();
        var innerHeight = $scrollContainer.innerHeight();
        var scrollHeight = $scrollContainer[0].scrollHeight;

        // Trigger when 150px from bottom (slightly earlier for smoother mini-card loading)
        if (scrollTop + innerHeight >= scrollHeight - 150) {
            if (canLoadMore) {
                canLoadMore = false; // Lock loading
                currentPage++;
                
                var activeCat = $('.tab-item.active').data('cat');

                // Add loader at the bottom of the active panel
                $('.cat-panel:visible').append('<div id="ess-loader" style="width:100%; text-align:center; padding:15px; color:#06aa64; font-weight:700;">Loading...</div>');

                $.ajax({
                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                    type: 'POST',
                    data: {
                        action: 'load_more_essentials',
                        paged: currentPage,
                        category: activeCat
                    },
                    success: function(data) {
                        $('#ess-loader').remove();
                        
                        if (data.trim() !== "") {
                            // Append the new mini-cards to the visible category panel
                            $('.cat-panel:visible').append(data);
                            canLoadMore = true; // Unlock for next page
                        } else {
                            // No more products left in this category
                            canLoadMore = false;
                        }
                    },
                    error: function() {
                        $('#ess-loader').remove();
                        canLoadMore = true;
                    }
                });
            }
        }
    });
});


    </script>
    <?php
    wp_reset_postdata();
}

function render_single_essential_card($product, $is_mini = false) {
    if (!$product) return;
    $id = $product->get_id();
    $img = get_the_post_thumbnail_url($id, 'woocommerce_thumbnail');
    $price = $product->get_price_html();
    $title = $product->get_name();

    if(!$is_mini): ?>
        <div class="essential-card" style="min-width: 200px; border: 1px solid #f0f0f0; border-radius: 15px; padding: 15px 15px 25px; text-align: center; background: #fff;">
            <img src="<?php echo $img; ?>" style="max-width: 100px; margin-bottom: 10px; height: auto;">
            <h4 style="font-size: 14px; margin: 5px 0; height: 40px; overflow: hidden; line-height: 1.3; font-weight: 700;"><?php echo $title; ?></h4>
            <p style="font-weight: 700; margin-top: 5px; margin-bottom: 15px; color: #333;"><?php echo $price; ?></p>
      
<button type="button" class="ajax_add_to_cart_essentials" data-product_id="<?php echo $id; ?>" style="color: #06aa64; font-size: 12px; font-weight: 700; border: 1px solid #06aa64; padding: 4px 12px; border-radius: 6px; margin: 4px 0; display: inline-block; background-color: #fff; cursor: pointer;">
    + Add
</button>

        </div>
    <?php else: ?>
        <div class="ess-card-mini">
            <img src="<?php echo $img; ?>" style="width: 55px; height: auto; border-radius: 8px;">
            <div style="flex: 1;">
                <h4 style="font-size: 13px; margin: 0; font-weight: 500; color: #333;"><?php echo $title; ?></h4>
                <p style="font-weight: 700; margin: 3px 0; font-size: 14px; color: #000;"><?php echo $price; ?></p>
<button type="button" class="ajax_add_to_cart_essentials" data-product_id="<?php echo $id; ?>" style="color: #06aa64; font-size: 12px; font-weight: 700; border: 1px solid #06aa64; padding: 4px 12px; border-radius: 6px; margin: 4px 0; display: inline-block; background-color: #fff; cursor: pointer;">
    + Add
</button>
            </div>
        </div>
    <?php endif;
}



/**
 * 3. ADD EXTRA SUPPORT SECTION (Checkbox List)
 * Specifically targeting Wellness Check and Support Visits
 */
add_action( 'woocommerce_after_single_product_summary', 'render_extra_support_system', 15 );
function render_extra_support_system() {
    if ( !is_care_hamper_layout() ) return;

    // Pulling specific services as requested: Wellness Check and Companionship/Support Visit
    $args = array(
        'status'   => 'publish',
        'category' => array('care-services'),
        'limit'    => 2, // Limit to two as per the mockup
        'orderby'  => 'date',
        'order'    => 'ASC'
    );
    $support_services = wc_get_products($args);

    if ( empty($support_services) ) return;

    ?>
    <div class="extra-support-section" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <h2 style="margin: 0 0 5px; font-weight: 700; text-align: left;">Add Support <span style="width: 30px; display: inline-block;">💚</span></h2>
        <p style="margin-top: 0; margin-bottom: 20px;">Choose care services to support your loved one beyond groceries.</p>

        <div class="support-list" style="display: flex; flex-direction: column; gap: 10px;">
            <?php foreach( $support_services as $service ) : 
                $id = $service->get_id();
                $title = $service->get_name();
                $short_desc = $service->get_short_description();
                $price = $service->get_price();
                // Custom Icon mapping or use featured image
                $icon = get_the_post_thumbnail_url($id, 'thumbnail'); 
            ?>
                <label class="support-item-row" style="display: flex; align-items: center; gap: 11px; padding: 12px; border: 1px solid #f2f2f2; border-radius: 10px; cursor: pointer;">
                    <input type="checkbox" class="support-checkbox" value="<?php echo $id; ?>" style="width: 18px; height: 18px; accent-color: #004d2c;">
                    
                    <?php if($icon): ?>
                        <img src="<?php echo $icon; ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    <?php endif; ?>

                    <div style="flex: 1;">
                        <h4 style="margin: 0; font-size: 16px; font-weight: 700;"><?php echo $title; ?></h4>
                        <div style="font-size: 12px;">
                            <?php echo wp_strip_all_tags($short_desc); ?>
                        </div>
                    </div>

                    <div style="font-weight: 700; color: #333; font-size: 14px;">
                        +<?php echo wc_price($price); ?>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.support-checkbox').on('change', function() {
            const productId = $(this).val();
            if($(this).is(':checked')) {
                // AJAX add to cart or redirect
                window.location.href = '?add-to-cart=' + productId;
            }
        });
    });
    </script>
    <?php
}


/**
 * Redirect specific Hamper categories directly to their product pages
 */
add_action( 'template_redirect', 'redirect_hamper_categories_to_products' );
function redirect_hamper_categories_to_products() {
    // 1. Check if we are on a product category page
    if ( is_product_category() ) {
        $queried_object = get_queried_object();
        $slug = $queried_object->slug;

        // 2. Map the category slug to your specific product URL
        // Replace 'diabetic-food-hamper' with your actual category slug
        // Replace '/product/diabetic-support-pack/' with your actual product URL
        if ( $slug === 'diabetic-food-hamper' ) {
            wp_redirect( home_url( '/shop/diabetic-care-pack/' ) );
            exit;
        }
        
        if ( $slug === 'elderly-food-hamper' ) {
            wp_redirect( home_url( '/shop/elderly-food-hamper/' ) );
            exit;
        }
 		if ( $slug === 'recovery-food-hamper' ) {
            wp_redirect( home_url( '/shop/recovery-support-hamper/' ) );
            exit;
        }
		if ( $slug === 'immunity-food-hamper' ) {
            wp_redirect( home_url( '/shop/immunity-support-hamper/' ) );
            exit;
        }
    }
}


// Register the AJAX actions
add_action('wp_ajax_load_more_essentials', 'load_more_essentials_handler');
add_action('wp_ajax_nopriv_load_more_essentials', 'load_more_essentials_handler');

function load_more_essentials_handler() {
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    
    $args = array(
        'status'   => 'publish',
        'category' => array( 'all-groceries' ), 
        'limit'    => 12, 
        'page'     => $paged,
        'paginate' => true,
    );

    $products_data = wc_get_products($args);
    
    if (!empty($products_data->products)) {
        foreach ($products_data->products as $product) {
            // Get product details
            $id = $product->get_id();
            $image = wp_get_attachment_image_url($product->get_image_id(), 'thumbnail');
            $title = $product->get_name();
            $price = $product->get_price_html();

            // NEW POPUP-SPECIFIC HTML STRUCTURE
            ?>
            <div class="ess-card-mini">
                <img src="<?php echo esc_url($image); ?>" style="width: 55px; height: auto; border-radius: 8px;">
 <div style="flex: 1;">
               <h4 style="font-size: 13px; margin: 0; font-weight: 500; color: #333;"><?php echo esc_html($title); ?></h4>
                 <p style="font-weight: 700; margin: 3px 0; font-size: 14px; color: #000;"><?php echo $price; ?></p>
               <button type="button" class="ajax_add_to_cart_essentials" data-product_id="<?php echo $id; ?>" style="color: #06aa64; font-size: 12px; font-weight: 700; border: 1px solid #06aa64; padding: 4px 12px; border-radius: 6px; margin: 4px 0; display: inline-block; background-color: #fff; cursor: pointer;">
                     + Add
</button>
            </div>
</div>
            <?php
        }
    }
    wp_die(); 
}


add_action( 'wp_head', 'hide_hamper_features_instantly' );
function hide_hamper_features_instantly() {
    // Only target the specific hamper categories
     if ( !is_care_hamper_layout() ) return; {
        ?>
        <style>
            /* 1. Hides the old feature list instantly */
            .c-product-features__list, 
            .c-product-features { 
                display: none !important; 
            }

            /* 2. Hides the original Quantity and Add to Cart form in the summary */
            .single-product .summary form.cart,
            .c-product__atc-wrap { 
                display: none !important; 
            }
        </style>
        <?php
    }
}

add_filter( 'body_class', 'add_hamper_body_class' );
function add_hamper_body_class( $classes ) {
    
    // Reuse our master switch function
    if ( is_care_hamper_layout() ) {
        $classes[] = 'is-hamper-product';
    }
    
    return $classes;
}


add_action( 'wp_footer', 'render_care_pantry_sticky_atc' );
function render_care_pantry_sticky_atc() {
    // Only show for the specific Care Hampers categories
    if ( !is_care_hamper_layout() ) return;

    global $product;
    ?>
    <div class="care-sticky-atc-bar">
        <div class="sticky-bar-content">
            <!-- Left Side: Price and Title -->
            <div class="sticky-bar-info">
                <span class="sticky-bar-price"><?php echo $product->get_price_html(); ?></span>
                <span class="sticky-bar-label">One-time purchase</span>
            </div>

            <!-- Right Side: Qty and Button -->
            <div class="sticky-bar-actions">
                <div class="sticky-qty-selector">
                    <button type="button" class="sticky-qty-btn minus">–</button>
                    <input type="number" class="sticky-qty-input" value="1" min="1">
                    <button type="button" class="sticky-qty-btn plus">+</button>
                </div>
                <button type="button" class="sticky-submit-button">
                    <span>SEND THIS CARE PACKAGE</span> 🤍
                </button>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Sync Sticky Qty buttons
        $('.sticky-qty-btn.plus').on('click', function() {
            let input = $('.sticky-qty-input');
            input.val(parseInt(input.val()) + 1);
        });
        $('.sticky-qty-btn.minus').on('click', function() {
            let input = $('.sticky-qty-input');
            if (parseInt(input.val()) > 1) input.val(parseInt(input.val()) - 1);
        });

        // Trigger the real WooCommerce Add to Cart button
        $('.sticky-submit-button').on('click', function() {
            let qty = $('.sticky-qty-input').val();
            $('form.cart input.qty').val(qty); // Update main qty input
            $('form.cart .single_add_to_cart_button').click(); // Click main ATC
        });
    });
    </script>
    <?php
}
