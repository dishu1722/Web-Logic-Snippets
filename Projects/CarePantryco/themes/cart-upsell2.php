<?php
/**
 * CarePantry Cart Left Column Cross-Sells & Modal Quick-Add Architecture
 * Robust fix for dynamic loaded add-to-cart buttons and cross-contamination.
 */

add_action( 'wp_footer', 'inject_carepantry_cart_left_cross_sells_modal' );
function inject_carepantry_cart_left_cross_sells_modal() {
    if ( ! function_exists( 'is_cart' ) || ! is_cart() ) return;
    
    $parent_cat = get_term_by( 'slug', 'all-groceries', 'product_cat' );
    $sub_categories = array();
    if ( $parent_cat ) {
        $sub_categories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'parent'     => $parent_cat->term_id,
            'hide_empty' => true,
        ) );
    }

    $cart_item_ids = array();
    if ( function_exists( 'WC' ) && WC()->cart ) {
        $cart = WC()->cart->get_cart();
        if ( is_array( $cart ) ) {
            foreach ( $cart as $cart_item ) {
                $cart_item_ids[] = (int) $cart_item['product_id'];
            }
        }
    }
    ?>
    <div id="cartEssentialsModal" style="display:none; position: fixed; z-index: 999999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: flex-end; justify-content: center;">
        <div style="background: #fff; width: 100%; max-width: 600px; height: 85vh; border-radius: 25px 25px 0 0; position: relative; display: flex; flex-direction: column; font-family: inherit; box-sizing: border-box;">
            <div style="padding: 20px 20px 10px; box-sizing: border-box;">
                <div style="width: 45px; height: 5px; background: #ddd; border-radius: 10px; margin: 0 auto 15px;"></div>
                <span id="closeCartEssModal" style="position: absolute; right: 20px; top: 20px; font-size: 35px; cursor: pointer; color: #000; line-height: 1;">&times;</span>
                <h2 style="margin: 0; font-weight: 700; text-align: left; color: #243f2f; font-size: 28px; text-transform: capitalize;">Add more groceries 💚</h2>
                <p style="margin: 5px 0 20px; font-size: 14px; color: #666;">Include more items for the whole family.</p>
                
                <div class="category-tabs" style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none;">
                    <?php if ( ! empty( $sub_categories ) ) : ?>
                        <?php foreach ( $sub_categories as $index => $category ) : ?>
                            <div class="tab-item <?php echo $index === 0 ? 'active' : ''; ?>" data-cat="<?php echo esc_attr( $category->slug ); ?>" style="padding: 8px 18px; border: 1px solid #eee; border-radius: 20px; white-space: nowrap; cursor: pointer; font-size: 14px; font-weight: 600;">
                                <?php echo esc_html( $category->name ); ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div id="cartEssentialsContent" style="flex: 1; overflow-y: auto; padding: 10px 20px 120px; box-sizing: border-box;">
                <?php if ( ! empty( $sub_categories ) ) : ?>
                    <?php foreach ( $sub_categories as $index => $category ) : ?>
                        <div class="cat-panel" id="cart-panel-<?php echo esc_attr( $category->slug ); ?>" data-loaded-page="1" style="display: <?php echo $index === 0 ? 'grid' : 'none'; ?>; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <?php
                            $cat_prods = wc_get_products( array(
                                'status'       => 'publish',
                                'limit'        => 12,
                                'category'     => array( $category->slug ),
                                'type'         => array( 'simple' ),
                                'stock_status' => 'instock',
                            ) );
                            if ( $cat_prods ) {
                                foreach ( $cat_prods as $cp ) {
                                    $mini_id = $cp->get_id();
                                    $mini_img_id = $cp->get_image_id();
                                    $mini_img_src = $mini_img_id ? wp_get_attachment_image_url( $mini_img_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src();
                                    $mini_in_cart = in_array( (int) $mini_id, $cart_item_ids, true );
                                    $m_btn_text = $mini_in_cart ? 'Added' : '+ Add';
                                    $m_btn_style = $mini_in_cart
                                        ? 'color:#fff;background-color:#06aa64;border-color:#06aa64;cursor:default;pointer-events:none;'
                                        : 'color:#06aa64;background-color:#fff;border:1px solid #06aa64;';
                                    ?>
                                    <div class="ess-card-mini" data-pid="<?php echo esc_attr( $mini_id ); ?>">
                                        <img src="<?php echo esc_url( $mini_img_src ); ?>" alt="">
                                        <div class="card-body-content">
                                            <h4><?php echo esc_html( $cp->get_name() ); ?></h4>
                                            <div class="prod-price-wrapper"><?php echo wp_kses_post( $cp->get_price_html() ); ?></div>
                                            <button type="button" class="cart-grid-ajax-btn" data-product_id="<?php echo esc_attr( $mini_id ); ?>" style="<?php echo esc_attr( $m_btn_style ); ?>">
                                                <?php echo esc_html( $m_btn_text ); ?>
                                            </button>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="padding: 15px 25px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; background: #fff; position: absolute; bottom: 0; width: 100%; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); box-shadow: 0 -5px 15px rgba(0,0,0,0.05); box-sizing: border-box;">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="modal-view-cart-link" style="text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                    View Cart &rarr;
                </a>
                <button id="cartDoneEss" type="button" style="background: #182d21; color: #fff; border: none; padding: 12px 50px; border-radius: 8px; font-weight: 600; cursor: pointer;">Done</button>
            </div>
        </div>
    </div>

    <style>
    #cart-ess-drag-grid::-webkit-scrollbar { display:none; }
    #cart-ess-drag-grid { scrollbar-width:none; }
    .nav-arrow:hover { background:#06aa64 !important; color:#fff !important; border-color:#06aa64 !important; }
    @media (max-width:768px) { .nav-arrow { display:none !important; } }
    .tab-item.active { background:#f0f5f2 !important; color:#06aa64 !important; border-color:#f0f5f2 !important; }
    @keyframes slideUpCartMod { from { transform:translateY(100%); } to { transform:translateY(0); } }
    #cartEssentialsModal > div { animation: slideUpCartMod 0.25s ease-out; }
    .ess-card-mini {
        display:flex !important;
        align-items:center !important;
        gap:12px !important;
        border:1px solid #f2f2f2 !important;
        padding:12px !important;
        border-radius:15px !important;
        background:#fff !important;
        box-sizing:border-box !important;
        width:100% !important;
        position:relative !important;
    }
    .ess-card-mini img {
        width:55px !important;
        height:55px !important;
        border-radius:8px !important;
        object-fit:contain !important;
        flex-shrink:0 !important;
        margin:0 !important;
    }
    .ess-card-mini .card-body-content {
        flex:1 !important;
        text-align:left !important;
        display:block !important;
    }
    .ess-card-mini h4 {
        font-size:13px !important;
        margin:0 0 2px 0 !important;
        font-weight:700 !important;
        color:#333 !important;
        line-height:1.3 !important;
        max-height:34px !important;
        overflow:hidden !important;
        text-align:left !important;
    }
    .ess-card-mini .prod-price-wrapper {
        font-weight:700 !important;
        margin:0 0 6px 0 !important;
        font-size:14px !important;
        color:#333 !important;
        text-align:left !important;
    }
    .ess-card-mini button.cart-grid-ajax-btn {
        font-size:11px !important;
        font-weight:700 !important;
        padding:4px 14px !important;
        border-radius:6px !important;
        display:inline-block !important;
        cursor:pointer !important;
        width:auto !important;
        text-align:center !important;
        line-height:1.2 !important;
        margin:0 !important;
    }
    @media (max-width:480px) {
        #cartEssentialsModal > div { height:90vh !important; }
        .cat-panel { grid-template-columns:1fr !important; gap:10px !important; }
    }
    </style>
    <?php
}

add_action( 'wp_footer', 'inject_carepantry_cart_left_cross_sells_layout' );
function inject_carepantry_cart_left_cross_sells_layout() {
    if ( ! function_exists( 'is_cart' ) || ! is_cart() ) return;

    $cart_item_ids = array();
    if ( function_exists( 'WC' ) && WC()->cart ) {
        $cart = WC()->cart->get_cart();
        if ( is_array( $cart ) ) {
            foreach ( $cart as $cart_item ) {
                $cart_item_ids[] = (int) $cart_item['product_id'];
            }
        }
    }

    $has_visible_content = false;
    $cross_sells_html = '<div id="custom-cart-left-crosssells" style="margin-bottom: 20px; clear: both; width: 100%; box-sizing: border-box; display: block; font-family: inherit;">';

    // 1. ALL GROCERIES SLIDER SECTION
    $ess_args = array(
        'post_type'      => 'product',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'all-groceries',
            )
        ),
        'meta_query'     => array(
            array(
                'key'     => '_price',
                'compare' => 'EXISTS',
            )
        ),
    );

    $ess_query = new WP_Query( $ess_args );
    if ( $ess_query->have_posts() ) {
        $has_visible_content = true;
        $cross_sells_html .= '
        <div class="cross-sell-section essentials-section" style="margin-bottom: 30px; background-color: #fbfbfb; padding: 20px; border-radius: 15px; position: relative; box-sizing: border-box; width: 100%;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                <h2 style="margin:0; font-size:1.5em; font-weight:700; color:#243f2f;">Add More Groceries <span style="font-size:18px;">💚</span></h2>
                <a href="javascript:void(0);" id="openCartEssentialsPopup" style="color:#06aa64; font-size:14px; font-weight:700; cursor:pointer; text-decoration:none;">View more &rarr;</a>
            </div>
            <p style="margin-top:0; margin-bottom:15px; font-size:14px; color:#555;">Include everyday items for everyone at home.</p>
            <div class="scroll-container-wrapper" style="position:relative; width:100%; box-sizing:border-box;">
                <div class="nav-arrow arrow-left" id="cart-slide-left" style="position:absolute; left:-12px; top:50%; transform:translateY(-50%); background:#fff; border:1px solid #eee; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:10; box-shadow:0 2px 6px rgba(0,0,0,0.08); font-weight:bold; color:#333;">&larr;</div>
                <div class="essentials-grid" id="cart-ess-drag-grid" style="display:flex; gap:15px; overflow-x:auto; padding:5px 2px; scroll-behavior:smooth; -webkit-overflow-scrolling:touch; width:100%;">__SLIDER_ITEMS__</div>
                <div class="nav-arrow arrow-right" id="cart-slide-right" style="position:absolute; right:-12px; top:50%; transform:translateY(-50%); background:#fff; border:1px solid #eee; border-radius:50%; width:32px; height:32px; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:10; box-shadow:0 2px 6px rgba(0,0,0,0.08); font-weight:bold; color:#333;">&rarr;</div>
            </div>
        </div>';

        $slider_items_html = '';
        while ( $ess_query->have_posts() ) {
            $ess_query->the_post();
            global $product;
            $p_id = get_the_ID();
            $prod = wc_get_product( $p_id );

            if ( ! $prod || ! $prod->is_type( 'simple' ) || ! $prod->is_purchasable() ) {
                continue;
            }

            $img = wp_get_attachment_image_src( get_post_thumbnail_id( $p_id ), 'woocommerce_thumbnail' );
            $img_url = $img ? $img[0] : wc_placeholder_img_src();
            $is_in_cart = in_array( (int) $p_id, $cart_item_ids, true );
            $btn_text = $is_in_cart ? 'Added' : '+ Add';
            $btn_style = $is_in_cart
                ? 'color:#fff;background-color:#06aa64;border-color:#06aa64;cursor:default;pointer-events:none;'
                : 'color:#06aa64;background-color:#fff;border:1px solid #06aa64;';

            $slider_items_html .= '
            <div class="essential-card cross-sell-item" data-pid="' . esc_attr( $p_id ) . '" style="flex:0 0 calc(33.333% - 10px); min-width:165px; max-width:200px; border:1px solid #f0f0f0; border-radius:15px; padding:15px; text-align:center; background:#fff; display:flex; flex-direction:column; justify-content:space-between; box-sizing:border-box;">
                <div>
                    <img src="' . esc_url( $img_url ) . '" style="max-width:85px; height:auto; margin:0 auto 10px; display:block; object-fit:contain;" alt="">
                    <h4 style="font-size:13px; margin:5px 0; height:34px; overflow:hidden; line-height:1.3; font-weight:700; color:#333; text-align:center;">' . esc_html( get_the_title() ) . '</h4>
                    <div class="prod-price-wrapper" style="font-weight:700; margin-top:5px; margin-bottom:12px; color:#333; font-size:14px; text-align:center;">' . wp_kses_post( $prod->get_price_html() ) . '</div>
                </div>
                <button type="button" class="cart-grid-ajax-btn" data-product_id="' . esc_attr( $p_id ) . '" style="' . esc_attr( $btn_style ) . ' font-size:12px; font-weight:700; padding:5px 12px; border-radius:6px; display:inline-block; cursor:pointer; transition:all 0.2s ease; width:auto; margin:0 auto;">
                    ' . esc_html( $btn_text ) . '
                </button>
            </div>';
        }
        wp_reset_postdata();
        $cross_sells_html = str_replace( '__SLIDER_ITEMS__', $slider_items_html, $cross_sells_html );
    }
    wp_reset_postdata();

    // 2. CARE SERVICES SECTION (WITH DYNAMIC HIERARCHY & TOGGLE)
    $supp_args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'meta_value_num',
        'meta_key'       => '_price',
        'order'          => 'ASC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => 'care-services',
            )
        ),
    );

    $supp_query = new WP_Query( $supp_args );
    if ( $supp_query->have_posts() ) {
        $has_visible_content = true;
        
        $initial_items_html = '';
        $hidden_items_html  = '';
        $visible_count      = 0;

        // Force lower-cost options to show up instantly first
        $preferred_slugs = array( 'wellness-check', 'companionship-support' );

        while ( $supp_query->have_posts() ) {
            $supp_query->the_post();
            global $product;
            $p_id = get_the_ID();
            $prod = wc_get_product( $p_id );

            if ( ! $prod || ! $prod->is_type( 'simple' ) || ! $prod->is_purchasable() ) {
                continue;
            }

            $img = wp_get_attachment_image_src( get_post_thumbnail_id( $p_id ), 'thumbnail' );
            $img_url = $img ? $img[0] : wc_placeholder_img_src();
            $is_in_cart = in_array( (int) $p_id, $cart_item_ids, true );
            
            $btn_text = $is_in_cart ? 'Added' : '+ Add';
            $btn_style = $is_in_cart
                ? 'color:#fff;background-color:#06aa64;border-color:#06aa64;cursor:default;pointer-events:none;'
                : 'color:#06aa64;background-color:#fff;border:1px solid #06aa64;';

            // Clean, scannable layout row
            $item_html = '
            <div class="support-item-row" style="display:flex; align-items:center; gap:12px; padding:12px; border:1px solid #f2f2f2; border-radius:12px; background:#ffffff; box-sizing:border-box; width:100%; margin:0;">
                <img src="' . esc_url( $img_url ) . '" style="width:50px; height:50px; border-radius:50%; object-fit:cover; flex-shrink:0;" alt="">
                <div style="flex:1; text-align:left;">
                    <h4 style="margin:0 0 2px 0; font-size:15px; font-weight:700; color:#333;">' . esc_html( get_the_title() ) . '</h4>
                    <div style="font-size:12px; color:#666; line-height:1.3;">' . esc_html( wp_strip_all_tags( get_the_excerpt() ) ) . '</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:6px; flex-shrink:0;">
                    <div style="font-weight:700; color:#333; font-size:14px; white-space:nowrap;">+' . wp_kses_post( $prod->get_price_html() ) . '</div>
                    <button type="button" class="cart-grid-ajax-btn" data-product_id="' . esc_attr( $p_id ) . '" style="' . esc_attr( $btn_style ) . ' font-size:11px; font-weight:700; padding:4px 14px; border-radius:6px; cursor:pointer; line-height:1.2; margin:0;">
                        ' . esc_html( $btn_text ) . '
                    </button>
                </div>
            </div>';

            // Partition logic based on matching slugs or fallback counter bounds
            if ( in_array( $prod->get_slug(), $preferred_slugs ) || ( empty( $initial_items_html ) && $visible_count < 2 ) ) {
                $initial_items_html .= $item_html;
                $visible_count++;
            } else {
                $hidden_items_html .= $item_html;
            }
        }
        wp_reset_postdata();

        // Assemble markup elements inside container framework
        $cross_sells_html .= '
        <div class="cross-sell-section extra-support-section" style="margin-bottom:20px; background-color:#fbfbfb; padding:20px; border-radius:15px; box-sizing:border-box; width:100%;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; width:100%;">
                <h2 style="margin:0; font-size:1.5em; font-weight:700; color:#243f2f; text-align:left;">Add Care Support <span style="font-size:18px;">💚</span></h2>
                ' . ( ! empty( $hidden_items_html ) ? '<a href="javascript:void(0);" id="toggle-care-services-trigger" style="color:#06aa64; font-size:14px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">View more <span class="arrow-indicator" style="font-size:10px; display:inline-block; transition:transform 0.2s;">↓</span></a>' : '' ) . '
            </div>
            <p style="margin:0 0 15px 0; font-size:14px; color:#555; text-align:left;">Choose care services to support your loved one beyond groceries.</p>
            
            <div class="support-list" style="display:flex; flex-direction:column; gap:12px; width:100%;">
                ' . $initial_items_html . '
                
                ' . ( ! empty( $hidden_items_html ) ? '
                <div id="extended-care-services-wrapper" style="display:none; flex-direction:column; gap:12px; width:100%;">
                    ' . $hidden_items_html . '
                </div>
                <a href="javascript:void(0);" id="bottom-care-services-trigger" style="display:none; text-align:center; color:#06aa64; font-size:14px; font-weight:700; margin-top:6px; text-decoration:none; justify-content:center; align-items:center; gap:4px; padding:12px; border:1px solid #e0e0e0; border-radius:10px; background:#fff; width:100%; box-sizing:border-box;">View less care services ↑</a>
                ' : '' ) . '
            </div>
        </div>';
    }
    wp_reset_postdata();
    $cross_sells_html .= '</div>';
    ?>
    
    <script type="text/javascript">
    jQuery(function($) {
        if (window.cartModalPageStates === undefined) {
            window.cartModalPageStates = { isLoading: false };
        }

        function positionLeftColumnCrossSells() {
            var hasContent = <?php echo $has_visible_content ? 'true' : 'false'; ?>;
            if (!hasContent) return;
            var rawHtml = <?php echo wp_json_encode( $cross_sells_html ); ?>;
            var $cartForm = $('form.woocommerce-cart-form');
            if ($cartForm.length) {
                if ($('#custom-cart-left-crosssells').length === 0) {
                    $cartForm.after(rawHtml);
                } else {
                    var $tempDom = $('<div>').html(rawHtml);
                    $('#custom-cart-left-crosssells .essentials-grid').html($tempDom.find('.essentials-grid').html());
                    // Synchronously update the base visible items stack without breaking explicit states
                    $('#custom-cart-left-crosssells .support-list').html($tempDom.find('.support-list').html());
                }
            }
        }
        
        positionLeftColumnCrossSells();

        function getWcAjaxTarget() {
            if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.wc_ajax_url) {
                return wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart');
            }
            return '/?wc-ajax=add_to_cart';
        }

        function addSimpleProductToCart(productId, $button) {
            if (!productId) return;
            $button.prop('disabled', true).text('Adding...');
            
            $.ajax({
                type: 'POST',
                url: getWcAjaxTarget(),
                dataType: 'json',
                data: {
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response && response.error) {
                        window.location.href = response.product_url;
                        return;
                    }
                    
                    // Unified UI feedback across both responsive elements instantly
                    $('[data-product_id="' + productId + '"], .cart-grid-ajax-btn[data-product_id="' + productId + '"]')
                        .addClass('is-added')
                        .text('Added')
                        .css({
                            'background-color': '#06aa64',
                            'color': '#fff',
                            'border-color': '#06aa64',
                            'pointer-events': 'none'
                        });

                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
                    $(document.body).trigger('wc_fragment_refresh');
                },
                error: function() {
                    $button.prop('disabled', false).text('+ Add');
                }
            });
        }

        // Global Event Delegation for Product Buttons
        $(document.body).on('click.cartGrid', '.cart-grid-ajax-btn, .ajax_add_to_cart_essentials', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var $button = $(this);
            if ($button.prop('disabled') || $button.hasClass('is-added')) return;
            var productId = parseInt($button.data('product_id'), 10);
            if (!productId) return;
            addSimpleProductToCart(productId, $button);
        });

        // Toggle Mechanics for Care Services Section Expansion
        $(document).off('click', '#toggle-care-services-trigger').on('click', '#toggle-care-services-trigger', function(e) {
            e.preventDefault();
            var $wrapper = $('#extended-care-services-wrapper');
            var $bottomTrigger = $('#bottom-care-services-trigger');
            var $link = $(this);
            
            if ($wrapper.is(':hidden')) {
                $wrapper.css('display', 'flex');
                $bottomTrigger.css('display', 'flex');
                $link.html('View less <span class="arrow-indicator" style="font-size:10px; transform:rotate(180deg);">↓</span>');
            } else {
                $wrapper.hide();
                $bottomTrigger.hide();
                $link.html('View more <span class="arrow-indicator" style="font-size:10px;">↓</span>');
            }
        });

        $(document).off('click', '#bottom-care-services-trigger').on('click', '#bottom-care-services-trigger', function(e) {
            e.preventDefault();
            $('#extended-care-services-wrapper').hide();
            $(this).hide();
            $('#toggle-care-services-trigger').html('View more <span class="arrow-indicator" style="font-size:10px;">↑</span>');
            
            $('html, body').animate({
                scrollTop: $('.extra-support-section').offset().top - 100
            }, 300);
        });

        // Modal Action Events
        $(document).off('click', '#openCartEssentialsPopup').on('click', '#openCartEssentialsPopup', function() {
            $('#cartEssentialsModal').css('display', 'flex');
            $('body').css('overflow', 'hidden');
            bindModalScrollEvent();
        });

        $(document).off('click', '#closeCartEssModal, #cartDoneEss').on('click', '#closeCartEssModal, #cartDoneEss', function() {
            $('#cartEssentialsModal').hide();
            $('body').css('overflow', 'auto');
        });

        $(document).off('click', '.tab-item').on('click', '.tab-item', function() {
            var targetCat = $(this).data('cat');
            $('.tab-item').removeClass('active');
            $(this).addClass('active');
            $('.cat-panel').hide();
            var $activePanel = $('#cart-panel-' + targetCat);
            $activePanel.css('display', 'grid');
            $('#cartEssentialsContent').scrollTop(0);
        });

        function bindModalScrollEvent() {
            $('#cartEssentialsContent').off('scroll').on('scroll', function() {
                var container = $(this);
                var scrollTop = container.scrollTop();
                var innerHeight = container.innerHeight();
                var scrollHeight = container[0].scrollHeight;
                var $activePanel = $('.cat-panel:visible');
                var activeCat = $('.tab-item.active').data('cat');
                var currentLoadedPage = parseInt($activePanel.attr('data-loaded-page') || 1, 10);

                if (scrollTop + innerHeight >= scrollHeight - 150 && !window.cartModalPageStates.isLoading && !$activePanel.hasClass('no-more-items')) {
                    window.cartModalPageStates.isLoading = true;
                    var nextPage = currentLoadedPage + 1;
                    $activePanel.after('<div id="ess-loader" style="text-align:center; padding:15px; color:#06aa64; font-weight:700; width:100%; clear:both;">Loading...</div>');
                    
                    $.ajax({
                        url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
                        type: 'POST',
                        data: {
                            action: 'load_more_essentials',
                            paged: nextPage,
                            category: activeCat
                        },
                        success: function(data) {
                            $('#ess-loader').remove();
                            window.cartModalPageStates.isLoading = false;
                            if ($.trim(data) !== "") {
                                $activePanel.append(data);
                                $activePanel.attr('data-loaded-page', nextPage);
                            } else {
                                $activePanel.addClass('no-more-items');
                                if ($activePanel.find('.ess-no-more').length === 0) {
                                    $activePanel.append('<div class="ess-no-more" style="grid-column:1 / -1; text-align:center; padding:10px; font-size:12px; color:#999;">No more products in this category.</div>');
                                }
                            }
                        },
                        error: function() {
                            $('#ess-loader').remove();
                            window.cartModalPageStates.isLoading = false;
                        }
                    });
                }
            });
        }

        // Slider Navigation Events
        $(document).off('click', '#cart-slide-left').on('click', '#cart-slide-left', function() {
            var $grid = $('#cart-ess-drag-grid');
            $grid.animate({ scrollLeft: $grid.scrollLeft() - 240 }, 200);
        });

        $(document).off('click', '#cart-slide-right').on('click', '#cart-slide-right', function() {
            var $grid = $('#cart-ess-drag-grid');
            $grid.animate({ scrollLeft: $grid.scrollLeft() + 240 }, 200);
        });

        $(document.body).on('updated_cart_totals wc_fragments_refreshed wc_fragments_loaded updated_wc_div', function() {
            positionLeftColumnCrossSells();
        });
    });
    </script>
    <?php
}

// 3. AJAX SERVER HANDLER FOR MODAL INFINITE SCROLL
add_action( 'wp_ajax_load_more_essentials', 'cart_modal_infinite_scroll_handler' );
add_action( 'wp_ajax_nopriv_load_more_essentials', 'cart_modal_infinite_scroll_handler' );
function cart_modal_infinite_scroll_handler() {
    $paged = isset( $_POST['paged'] ) ? max( 1, intval( $_POST['paged'] ) ) : 1;
    $category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

    if ( empty( $category ) ) {
        wp_die();
    }

    $cart_item_ids = array();
    if ( function_exists( 'WC' ) && WC()->cart ) {
        $cart = WC()->cart->get_cart();
        if ( is_array( $cart ) ) {
            foreach ( $cart as $cart_item ) {
                $cart_item_ids[] = (int) $cart_item['product_id'];
            }
        }
    }

    $cat_prods = wc_get_products( array(
        'status'       => 'publish',
        'limit'        => 12,
        'page'         => $paged,
        'category'     => array( $category ),
        'type'         => array( 'simple' ),
        'stock_status' => 'instock',
    ) );

    $output = '';
    if ( $cat_prods ) {
        foreach ( $cat_prods as $cp ) {
            $mini_id = $cp->get_id();
            $mini_img_id = $cp->get_image_id();
            $mini_img_src = $mini_img_id ? wp_get_attachment_image_url( $mini_img_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src();
            $mini_in_cart = in_array( (int) $mini_id, $cart_item_ids, true );
            $m_btn_text = $mini_in_cart ? 'Added' : '+ Add';
            $m_btn_style = $mini_in_cart
                ? 'color:#fff;background-color:#06aa64;border-color:#06aa64;cursor:default;pointer-events:none;'
                : 'color:#06aa64;background-color:#fff;border:1px solid #06aa64;';

            $output .= '<div class="ess-card-mini" data-pid="' . esc_attr( $mini_id ) . '">';
            $output .= '<img src="' . esc_url( $mini_img_src ) . '" alt="">';
            $output .= '<div class="card-body-content">';
            $output .= '<h4>' . esc_html( $cp->get_name() ) . '</h4>';
            $output .= '<div class="prod-price-wrapper">' . wp_kses_post( $cp->get_price_html() ) . '</div>';
            $output .= '<button type="button" class="cart-grid-ajax-btn" data-product_id="' . esc_attr( $mini_id ) . '" style="' . esc_attr( $m_btn_style ) . '">' . esc_html( $m_btn_text ) . '</button>';
            $output .= '</div>';
            $output .= '</div>';
        }
    }

    echo $output;
    wp_die();
}
