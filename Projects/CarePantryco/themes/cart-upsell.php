<?php

/**
 * CarePantry Cart Left Column Cross-Sells (Fixed Layout, Slider, Modal, Infinite Scroll, Full CSS & View Cart link)
 */
add_action( 'wp_footer', 'inject_carepantry_cart_left_cross_sells' );
function inject_carepantry_cart_left_cross_sells() {
    if ( ! function_exists( 'is_cart' ) || ! is_cart() ) return;

    // Gather categories cleanly for our modal view
    $parent_cat = get_term_by('slug', 'all-groceries', 'product_cat');
    $sub_categories = array();
    if ( $parent_cat ) {
        $sub_categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'parent'     => $parent_cat->term_id,
            'hide_empty' => true,
        ) );
    }

    // Capture products in cart for setting disabled/active states
    $cart_item_ids = array();
    if ( function_exists('WC') && WC()->cart ) {
        $cart = WC()->cart->get_cart();
        if ( is_array( $cart ) ) {
            foreach ( $cart as $cart_item ) {
                $cart_item_ids[] = $cart_item['product_id'];
            }
        }
    }

    $has_visible_content = false;
    $cross_sells_html = '<div id="custom-cart-left-crosssells" style="margin-bottom: 20px; clear: both; width: 100%; box-sizing: border-box; display: block; font-family: inherit;">';

    // --- 1. ESSENTIALS SLIDER BLOCK ---
    $ess_args = array(
        'post_type'      => 'product',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'tax_query'      => array(array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'all-groceries')),
    );
    $ess_query = new WP_Query( $ess_args );

    if ( $ess_query->have_posts() ) {
        $has_visible_content = true;
        $cross_sells_html .= '
        <div class="cross-sell-section essentials-section" style="margin-bottom: 30px; background-color: #fbfbfb; padding: 20px; border-radius: 15px; position: relative; box-sizing: border-box; width: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                <h2 style="margin: 0; font-size: 1.5em; font-weight: 700; color: #243f2f;">Add Essentials <span style="font-size:18px;">💚</span></h2>
                <a href="javascript:void(0);" id="openCartEssentialsPopup" style="color: #06aa64; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none;">View more &rarr;</a>
            </div>
            <p style="margin-top: 0; margin-bottom: 15px; font-size: 14px; color: #555;">Include everyday items for everyone at home.</p>

            <div class="scroll-container-wrapper" style="position: relative; width: 100%; box-sizing: border-box;">
                <div class="nav-arrow arrow-left" id="cart-slide-left" style="position: absolute; left: -12px; top: 50%; transform: translateY(-50%); background: #fff; border: 1px solid #eee; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 2px 6px rgba(0,0,0,0.08); font-weight: bold; color: #333;">&larr;</div>
                
                <div class="essentials-grid" id="cart-ess-drag-grid" style="display: flex; gap: 15px; overflow-x: auto; padding: 5px 2px; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; width: 100%;">__SLIDER_ITEMS__</div>
                
                <div class="nav-arrow arrow-right" id="cart-slide-right" style="position: absolute; right: -12px; top: 50%; transform: translateY(-50%); background: #fff; border: 1px solid #eee; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 2px 6px rgba(0,0,0,0.08); font-weight: bold; color: #333;">&rarr;</div>
            </div>
        </div>';

        $slider_items_html = '';
        while ( $ess_query->have_posts() ) {
            $ess_query->the_post();
            global $product;
            $p_id = get_the_ID();
            $img = wp_get_attachment_image_src( get_post_thumbnail_id( $p_id ), 'woocommerce_thumbnail' );
            $img_url = $img ? $img[0] : wc_placeholder_img_src();
            
            $is_in_cart = (!empty($cart_item_ids) && in_array($p_id, $cart_item_ids));
            $btn_text   = $is_in_cart ? 'Added' : '+ Add';
            $btn_style  = $is_in_cart ? 'color: #fff; background-color: #06aa64; border-color: #06aa64; cursor: default; pointer-events: none;' : 'color: #06aa64; background-color: #fff; border: 1px solid #06aa64;';

            $slider_items_html .= '
            <div class="essential-card cross-sell-item" style="flex: 0 0 calc(33.333% - 10px); min-width: 165px; max-width: 200px; border: 1px solid #f0f0f0; border-radius: 15px; padding: 15px; text-align: center; background: #fff; display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
                <div>
                    <img src="' . esc_url($img_url) . '" style="max-width: 85px; height: auto; margin: 0 auto 10px; display: block; object-fit: contain;">
                    <h4 style="font-size: 13px; margin: 5px 0; height: 34px; overflow: hidden; line-height: 1.3; font-weight: 700; color: #333; text-align: center;">' . esc_html(get_the_title()) . '</h4>
                    <p style="font-weight: 700; margin-top: 5px; margin-bottom: 12px; color: #333; font-size: 14px; text-align: center;">' . $product->get_price_html() . '</p>
                </div>
                <button type="button" class="cart-grid-ajax-btn" data-product_id="' . $p_id . '" style="' . $btn_style . ' font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 6px; display: inline-block; cursor: pointer; transition: all 0.2s ease; width: auto; margin: 0 auto;">
                    ' . $btn_text . '
                </button>
            </div>';
        }
        $cross_sells_html = str_replace('__SLIDER_ITEMS__', $slider_items_html, $cross_sells_html);
    }
    wp_reset_postdata();

    // --- 2. SUPPORT ROWS BLOCK ---
    $supp_args = array(
        'post_type'      => 'product',
        'posts_per_page' => 2,
        'post_status'    => 'publish',
        'tax_query'      => array(array('taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => 'care-services')),
    );
    $supp_query = new WP_Query( $supp_args );

    if ( $supp_query->have_posts() ) {
        $has_visible_content = true;
        $cross_sells_html .= '
        <div class="cross-sell-section extra-support-section" style="margin-bottom: 20px; background-color: #fbfbfb; padding: 20px; border-radius: 15px; box-sizing: border-box; width: 100%;">
            <h2 style="margin: 0 0 4px 0; font-size: 1.5em; font-weight: 700; color: #243f2f; text-align: left;">Add Support <span style="font-size:18px;">💚</span></h2>
            <p style="margin: 0 0 15px 0; font-size: 14px; color: #555; text-align: left;">Choose care services to support your loved one beyond groceries.</p>
            <div class="support-list" style="display: flex; flex-direction: column; gap: 10px; width: 100%;">';
                
                while ( $supp_query->have_posts() ) {
                    $supp_query->the_post();
                    global $product;
                    $p_id = get_the_ID();
                    $img = wp_get_attachment_image_src( get_post_thumbnail_id( $p_id ), 'thumbnail' );
                    $img_url = $img ? $img[0] : wc_placeholder_img_src();
                    
                    $is_checked = (!empty($cart_item_ids) && in_array( $p_id, $cart_item_ids )) ? 'checked disabled' : '';

                    $cross_sells_html .= '
                    <label class="support-item-row cart-support-ajax-row" style="display: flex; align-items: center; gap: 11px; padding: 12px; border: 1px solid #f2f2f2; border-radius: 10px; cursor: pointer; background: #ffffff; box-sizing: border-box; width: 100%; margin: 0;">
                        <input type="checkbox" class="support-checkbox cart-support-checkbox" value="' . $p_id . '" ' . $is_checked . ' style="width: 18px; height: 18px; accent-color: #004d2c; cursor: pointer; flex-shrink: 0; margin: 0;">
                        <img src="' . esc_url( $img_url ) . '" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                        <div style="flex: 1; text-align: left;">
                            <h4 style="margin: 0; font-size: 16px; font-weight: 700; color: #333;">' . esc_html( get_the_title() ) . '</h4>
                            <div style="font-size: 12px; color: #666; line-height: 1.3;">' . wp_strip_all_tags(get_the_excerpt()) . '</div>
                        </div>
                        <div style="font-weight: 700; color: #333; font-size: 14px; white-space: nowrap;">
                            +' . $product->get_price_html() . '
                        </div>
                    </label>';
                }
        $cross_sells_html .= '</div></div>';
    }
    wp_reset_postdata();
    $cross_sells_html .= '</div>'; // Safely closes the left column container block

    // --- 3. THE ISOLATED MODAL MARKUP ---
    ob_start();
    ?>
    <div id="cartEssentialsModal" style="display:none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: flex-end; justify-content: center;">
        <div style="background: #fff; width: 100%; max-width: 600px; height: 85vh; border-radius: 25px 25px 0 0; position: relative; display: flex; flex-direction: column; font-family: inherit; box-sizing: border-box;">
            <div style="padding: 20px 20px 10px; box-sizing: border-box;">
                <div style="width: 45px; height: 5px; background: #ddd; border-radius: 10px; margin: 0 auto 15px;"></div>
                <span id="closeCartEssModal" style="position: absolute; right: 20px; top: 20px; font-size: 35px; cursor: pointer; color: #000; line-height: 1;">&times;</span>
                <h2 style="margin: 0; font-weight: 700; text-align: left; color: #243f2f; font-size: 28px; text-transform: capitalize;">Add more essentials 💚</h2>
                <p style="margin: 5px 0 20px; font-size: 14px; color: #666;">Include more items for the whole family.</p>
                <div class="category-tabs" style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none;">
                    <?php if ( !empty($sub_categories) ) : 
                        foreach ( $sub_categories as $index => $category ) : ?>
                            <div class="tab-item <?php echo $index === 0 ? 'active' : ''; ?>" data-cat="<?php echo esc_attr($category->slug); ?>" style="padding: 8px 18px; border: 1px solid #eee; border-radius: 20px; white-space: nowrap; cursor: pointer; font-size: 14px; font-weight: 600;">
                                <?php echo esc_html($category->name); ?>
                            </div>
                        <?php endforeach; 
                    endif; ?>
                </div>
            </div>
            
            <div id="cartEssentialsContent" style="flex: 1; overflow-y: auto; padding: 10px 20px 120px; box-sizing: border-box;">
                <?php if ( !empty($sub_categories) ) : 
                    foreach ( $sub_categories as $index => $category ) : ?>
                        <div class="cat-panel" id="cart-panel-<?php echo esc_attr($category->slug); ?>" style="display: <?php echo $index === 0 ? 'grid' : 'none'; ?>; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <?php 
                            $cat_prods = wc_get_products(array('status' => 'publish', 'limit' => 12, 'category' => array($category->slug)));
                            if($cat_prods) {
                                foreach($cat_prods as $cp) {
                                    $mini_id = $cp->get_id();
                                    $mini_img = get_the_post_thumbnail_url($mini_id, 'woocommerce_thumbnail') ?: wc_placeholder_img_src();
                                    
                                    $mini_in_cart = (!empty($cart_item_ids) && in_array($mini_id, $cart_item_ids));
                                    $m_btn_text   = $mini_in_cart ? 'Added' : '+ Add';
                                    $m_btn_style  = $mini_in_cart ? 'color: #fff; background-color: #06aa64; border-color: #06aa64; cursor: default; pointer-events: none;' : 'color: #06aa64; background-color: #fff; border: 1px solid #06aa64;';
                                    ?>
                                    <div class="ess-card-mini" style="display: flex; align-items: center; gap: 10px; border: 1px solid #f2f2f2; padding: 12px; border-radius: 15px; background: #fff; box-sizing: border-box;">
                                        <img src="<?php echo esc_url($mini_img); ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: contain; flex-shrink: 0;">
                                        <div style="flex: 1; text-align: left;">
                                            <h4 style="font-size: 13px; margin: 0; font-weight: 700; color: #333; line-height: 1.2; max-height: 32px; overflow: hidden;"><?php echo esc_html($cp->get_name()); ?></h4>
                                            <p style="font-weight: 700; margin: 3px 0; font-size: 14px; color: #333;"><?php echo $cp->get_price_html(); ?></p>
                                            <button type="button" class="cart-grid-ajax-btn" data-product_id="<?php echo $mini_id; ?>" style="<?php echo $m_btn_style; ?> font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 6px; display: inline-block; cursor: pointer; width: auto;">
                                                <?php echo $m_btn_text; ?>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    <?php endforeach; 
                endif; ?>
            </div>
            
            <div style="padding: 15px 25px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fff; position: absolute; bottom: 0; width: 100%; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); box-sizing: border-box;">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="modal-view-cart-link" style="text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    View Cart &rarr;
                </a>
                <button id="cartDoneEss" style="background: #182d21; color: #fff; border: none; padding: 12px 50px; border-radius: 8px; font-weight: 600; cursor: pointer;">Done</button>
            </div>
        </div>
    </div>
    <?php
    $modal_html = ob_get_clean();
    ?>

    <style>
        #cart-ess-drag-grid::-webkit-scrollbar { display: none; }
        #cart-ess-drag-grid { scrollbar-width: none; }
        .nav-arrow:hover { background: #06aa64 !important; color: #fff !important; border-color: #06aa64 !important; }
        @media (max-width: 768px) { .nav-arrow { display: none !important; } }
        .tab-item.active { background: #f0f5f2 !important; color: #06aa64 !important; border-color: #f0f5f2 !important; }
        @keyframes slideUpCartMod { from { transform: translateY(100%); } to { transform: translateY(0); } }
        #cartEssentialsModal > div { animation: slideUpCartMod 0.25s ease-out; }
        

        
        /* --- EXPLICIT CSS FOR MODAL MINI CARDS --- */
        .ess-card-mini {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            border: 1px solid #f2f2f2 !important;
            padding: 12px !important;
            border-radius: 15px !important;
            background: #fff !important;
            box-sizing: border-box !important;
        }
        .ess-card-mini img {
            width: 55px !important;
            height: 55px !important;
            border-radius: 8px !important;
            object-fit: contain !important;
            flex-shrink: 0 !important;
            margin: 0 !important;
        }
        .ess-card-mini h4 {
            font-size: 13px !important;
            margin: 0 0 2px 0 !important;
            font-weight: 700 !important;
            color: #333 !important;
            line-height: 1.3 !important;
            max-height: 34px !important;
            overflow: hidden !important;
            text-align: left !important;
        }
        .ess-card-mini p {
            font-weight: 700 !important;
            margin: 0 0 6px 0 !important;
            font-size: 14px !important;
            color: #333 !important;
            text-align: left !important;
        }
        .ess-card-mini button.cart-grid-ajax-btn {
            font-size: 11px !important;
            font-weight: 700 !important;
            padding: 4px 14px !important;
            border-radius: 6px !important;
            display: inline-block !important;
            cursor: pointer !important;
            width: auto !important;
            text-align: center !important;
            line-height: 1.2 !important;
            margin: 0 !important;
        }
        @media (max-width: 480px) {
            #cartEssentialsModal > div { height: 90vh !important; }
            .cat-panel { grid-template-columns: 1fr !important; gap: 10px !important; }
        }
    </style>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        
        var canLoadMore = true;
        var currentPage = 1;

        function positionLeftColumnCrossSells() {
            var hasContent = <?php echo $has_visible_content ? 'true' : 'false'; ?>;
            if (!hasContent) return;

            // Render core cross-sells container cleanly under the cart items form
            if ($('#custom-cart-left-crosssells').length === 0) {
                var rawHtml = <?php echo json_encode( $cross_sells_html ); ?>;
                var $cartForm = $('form.woocommerce-cart-form');
                if ($cartForm.length) {
                    $cartForm.after(rawHtml);
                }
            }

            // Append modal directly to document body context so it can never conflict with layout grid styling
            if ($('#cartEssentialsModal').length === 0) {
                var modalContainer = <?php echo json_encode( $modal_html ); ?>;
                $('body').append(modalContainer);
                
                // Bind scroll event immediately upon DOM insertion
                bindModalScrollEvent();
            }
        }

        positionLeftColumnCrossSells();

        // Arrow controls functionality
        $(document).on('click', '#cart-slide-left', function() {
            var $grid = $('#cart-ess-drag-grid');
            $grid.animate({ scrollLeft: $grid.scrollLeft() - 240 }, 200);
        });
        $(document).on('click', '#cart-slide-right', function() {
            var $grid = $('#cart-ess-drag-grid');
            $grid.animate({ scrollLeft: $grid.scrollLeft() + 240 }, 200);
        });

        // Triggering standard views and adjustments
        $(document).on('click', '#openCartEssentialsPopup', function() { $('#cartEssentialsModal').css('display', 'flex'); $('body').css('overflow', 'hidden'); });
        $(document).on('click', '#closeCartEssModal, #cartDoneEss', function() { $('#cartEssentialsModal').hide(); $('body').css('overflow', 'auto'); });
        
        // Modal subcategory switches
        $(document).on('click', '.tab-item', function() {
            $('.tab-item').removeClass('active'); $(this).addClass('active');
            $('.cat-panel').hide(); $('#cart-panel-' + $(this).data('cat')).css('display', 'grid');
            
            // Reset infinite scroll engine tracker positions on tab change
            currentPage = 1; 
            canLoadMore = true;
            $('.ess-no-more').remove(); 
            $('#ess-loader').remove();
            $('#cartEssentialsContent').scrollTop(0);
        });

        // --- DIRECT ELEMENT SCROLL INITIALIZATION ---
        function bindModalScrollEvent() {
            $('#cartEssentialsContent').on('scroll', function() {
                var container = $(this);
                var scrollTop = container.scrollTop();
                var innerHeight = container.innerHeight();
                var scrollHeight = container[0].scrollHeight;

                if (scrollTop + innerHeight >= scrollHeight - 150) {
                    if (canLoadMore) {
                        canLoadMore = false; 
                        currentPage++;
                        var activeCat = $('.tab-item.active').data('cat');
                        $('.cat-panel:visible').append('<div id="ess-loader" style="grid-column: 1 / -1; text-align:center; padding:15px; color:#06aa64; font-weight:700;">Loading...</div>');

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
                                    $('.cat-panel:visible').append(data);
                                    canLoadMore = true; 
                                } else {
                                    canLoadMore = false;
                                    if ($('.cat-panel:visible .ess-no-more').length === 0) {
                                        $('.cat-panel:visible').append('<div class="ess-no-more" style="grid-column: 1 / -1; text-align:center; padding:10px; font-size:12px; color:#999;">No more products in this category.</div>');
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }

        // Dynamic AJAX operations engine
        $(document).on('click', '.cart-grid-ajax-btn', function(e) {
            e.preventDefault();
            var $button = $(this);
            if ($button.hasClass('is-added')) return;
            
            var productId = $button.data('product_id');
            $button.text('Adding...').prop('disabled', true);

            executeCartAddAjax(productId, function() {
                $button.addClass('is-added').text('Added').css({
                    'background-color': '#06aa64', 'color': '#fff', 'border-color': '#06aa64'
                });
            });
        });

        $(document).on('change', '.cart-support-checkbox', function(e) {
            var $checkbox = $(this);
            if (!$checkbox.is(':checked') || $checkbox.prop('disabled')) return;

            var productId = $checkbox.val();
            var $row = $checkbox.closest('.cart-support-ajax-row');
            
            $checkbox.prop('disabled', true);
            $row.css('opacity', '0.6');

            executeCartAddAjax(productId, function() { $row.css('opacity', '1'); });
        });

        function executeCartAddAjax(productId, successCallback) {
            if (typeof wc_add_to_cart_params === 'undefined') return;
            $.ajax({
                type: 'POST',
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                data: { product_id: productId, quantity: 1 },
                success: function(response) {
                    if (response.error && response.product_url) { window.location.href = response.product_url; return; }
                    if (typeof successCallback === "function") successCallback();
                    $(document.body).trigger('removed_from_cart_fragments_refreshed');
                    location.reload();
                }
            });
        }

        $(document.body).on('updated_cart_totals wc_fragments_refreshed wc_fragments_loaded updated_wc_div', function() {
            positionLeftColumnCrossSells();
        });
    });
    </script>
    <?php
}

/**
 * --- BACKEND AJAX ROUTINE ---
 * Handles returning structured columns cleanly for the infinite scroll query inside the modal
 */
add_action('wp_ajax_load_more_essentials', 'cart_modal_infinite_scroll_handler');
add_action('wp_ajax_nopriv_load_more_essentials', 'cart_modal_infinite_scroll_handler');
function cart_modal_infinite_scroll_handler() {
    $paged    = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    if (empty($category)) {
        wp_die();
    }

    // Checking matching items in cart to handle state changes
    $cart_item_ids = array();
    if ( function_exists('WC') && WC()->cart ) {
        $cart = WC()->cart->get_cart();
        if ( is_array( $cart ) ) {
            foreach ( $cart as $cart_item ) {
                $cart_item_ids[] = $cart_item['product_id'];
            }
        }
    }

    $cat_prods = wc_get_products(array(
        'status'   => 'publish',
        'limit'    => 12,
        'paged'    => $paged,
        'category' => array($category)
    ));

    if ($cat_prods) {
        foreach ($cat_prods as $cp) {
            $mini_id  = $cp->get_id();
            $mini_img = get_the_post_thumbnail_url($mini_id, 'woocommerce_thumbnail') ?: wc_placeholder_img_src();
            
            $mini_in_cart = (!empty($cart_item_ids) && in_array($mini_id, $cart_item_ids));
            $m_btn_text   = $mini_in_cart ? 'Added' : '+ Add';
            $m_btn_style  = $mini_in_cart ? 'color: #fff; background-color: #06aa64; border-color: #06aa64; cursor: default; pointer-events: none;' : 'color: #06aa64; background-color: #fff; border: 1px solid #06aa64;';
            ?>
            <div class="ess-card-mini" style="display: flex; align-items: center; gap: 10px; border: 1px solid #f2f2f2; padding: 12px; border-radius: 15px; background: #fff; box-sizing: border-box;">
                <img src="<?php echo esc_url($mini_img); ?>" style="width: 50px; height: 50px; border-radius: 8px; object-fit: contain; flex-shrink: 0;">
                <div style="flex: 1; text-align: left;">
                    <h4 style="font-size: 13px; margin: 0; font-weight: 700; color: #333; line-height: 1.2; max-height: 32px; overflow: hidden;"><?php echo esc_html($cp->get_name()); ?></h4>
                    <p style="font-weight: 700; margin: 3px 0; font-size: 14px; color: #333;"><?php echo $cp->get_price_html(); ?></p>
                    <button type="button" class="cart-grid-ajax-btn" data-product_id="<?php echo $mini_id; ?>" style="<?php echo $m_btn_style; ?> font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 6px; display: inline-block; cursor: pointer; width: auto;">
                        <?php echo $m_btn_text; ?>
                    </button>
                </div>
            </div>
            <?php
        }
    }
    wp_die();
}
