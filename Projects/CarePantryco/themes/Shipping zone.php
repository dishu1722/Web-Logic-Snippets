<?php 

/**
 * CarePantry: Combined and Customized Shipping Methods for Cart & Checkout
 */
add_filter( 'woocommerce_cart_shipping_method_full_label', 'custom_carepantry_shipping_labels', 10, 2 );
function custom_carepantry_shipping_labels( $label, $method ) {
    $method_id = $method->id; 
    $title = $method->get_label(); 
    $cost_val = (float) $method->cost;
    
    $subtitle = '';
    $clean_title = '';

    // Bulletproof evaluation: Check Title, then Method ID, then exact Cost fallback
    if ( strpos( $title, 'T1' ) !== false || $method_id === 'flat_rate:1' || round($cost_val, 2) === 25.50 ) {
        $clean_title = 'Delivery Zone T1';
        $subtitle = '<div class="ship-subtitle">Urban cities and towns</div><div class="ship-min-order">Minimum order: <strong>US$80</strong></div>';
    } elseif ( strpos( $title, 'T2' ) !== false || $method_id === 'flat_rate:2' || round($cost_val, 2) === 49.05 ) {
        $clean_title = 'Delivery Zone T2';
        $subtitle = '<div class="ship-subtitle">Regional towns and rural areas</div><div class="ship-min-order">Minimum order: <strong>US$200</strong></div>';
    } elseif ( strpos( $title, 'T3' ) !== false || $method_id === 'flat_rate:3' || round($cost_val, 2) === 70.63 ) {
        $clean_title = 'Delivery Zone T3';
        $subtitle = '<div class="ship-subtitle">Remote rural areas</div><div class="ship-min-order">Minimum order: <strong>US$350</strong></div>';
    } else {
        // Safe global fallback if a generic flat rate or unknown zone runs
        $clean_title = !empty($title) ? $title : 'Delivery Rate';
    }

    $cost = '';
    if ( $cost_val > 0 ) {
        $cost = '<span class="ship-cost">' . wc_price( $method->cost ) . '</span>';
    } else {
        $cost = '<span class="ship-cost free-ship">Free</span>';
    }

    // Build the structural block framework
    $new_label = '<div class="custom-shipping-rate-wrapper">';
    $new_label .= '  <div class="ship-row-header">';
    $new_label .= '     <span class="ship-main-title">' . esc_html( $clean_title ) . '</span>';
    $new_label .= '     ' . $cost;
    $new_label .= '  </div>';
    if ( ! empty( $subtitle ) ) {
        $new_label .= '  <div class="ship-details-body">' . $subtitle . '</div>';
    }
    $new_label .= '  <a href="javascript:void(0);" class="open-zone-popup-link" style="color: #06aa64; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; margin-top: 6px;">View delivery areas &rarr;</a>';
    $new_label .= '</div>';

    return $new_label;
}

/**
 * CarePantry: Inject HTML Modal, CSS styles, and jQuery Script into Footer (Cart + Checkout)
 */
add_action( 'wp_footer', 'inject_shipping_zones_complete_assets' );
function inject_shipping_zones_complete_assets() {
    if ( ! function_exists( 'is_cart' ) || ( ! is_cart() && ! is_checkout() ) ) return;
    ?>
    <div id="shippingZonesModal" style="display:none; position: fixed; z-index: 9999999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(2px); align-items: center; justify-content: center; padding: 15px; box-sizing: border-box;">
        <div style="background: #fff; width: 100%; max-width: 500px; max-height: 80vh; border-radius: 20px; position: relative; display: flex; flex-direction: column; font-family: inherit; box-sizing: border-box; box-shadow: 0 10px 30px rgba(0,0,0,0.15); animation: scaleUpZoneMod 0.2s ease-out;">
            
            <div style="padding: 20px 25px 15px; border-bottom: 1px solid #f2f2f2; position: relative;">
                <h3 style="margin: 0; font-size: 20px; font-weight: 700; color: #243f2f;">Delivery Areas & Zones</h3>
                <span id="closeZonesModal" style="position: absolute; right: 20px; top: 16px; font-size: 28px; cursor: pointer; color: #999; line-height: 1;">&times;</span>
            </div>
            
            <div style="flex: 1; overflow-y: auto; padding: 20px 25px; box-sizing: border-box; font-size: 14px; line-height: 1.5; color: #444;">
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0 0 5px; color: #004d2c; font-size: 15px; font-weight: 700;">Delivery Zone T1 (Urban)</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; background: #f9f9f9; padding: 10px; border-radius: 8px;">
                        Bulawayo, Chinhoyi, Chiredzi, Chitungwiza, Gokwe Townships, Gweru, Harare Exc, Norton, Hwange, Kadoma, Kwekwe Exc, Redcliffe, Marondera, Masvingo, Mutare, Rusape Townships, Ruwa, Vic Falls Urban, Zvishavane.
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0 0 5px; color: #004d2c; font-size: 15px; font-weight: 700;">Delivery Zone T2 (Regional)</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; background: #f9f9f9; padding: 10px; border-radius: 8px;">
                        Bindura, Chegutu, Chipinge, Concession, Macheke Townships, Mahusekwa, Manica Bridge, Mutare, Darwendale, Norton, Domboramwari, Glendale, Goromonzi, Headlands, Juru, Karoi, Lower Gweru, Murehwa Central, Murehwa Rural (Zororo, Mapfupange, Chitambe, Njedza, Nyamutumbu, Musami), Nyabira, Nyanga Town, Nyazura, Odzi, Penhalonga, Shurugwi Urban, Esigodini, Triangle, Nyati, Mvuma, Mushagashe, Zimunya, Zaka, Zhombe.
                    </p>
                </div>
                
                <div>
                    <h4 style="margin: 0 0 5px; color: #004d2c; font-size: 15px; font-weight: 700;">Delivery Zone T3 (Remote)</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; background: #f9f9f9; padding: 10px; border-radius: 8px;">
                        Chivhu, Chatsworth, Chiwundura, Filabusi, Maphisa, Mt. Darwin, Mpandawana, Murambinda, Mutoko Center, Plumtree, Sanyati, Senga, Tete, Wedza, Zaka JMP.
                    </p>
                </div>
            </div>
            
            <div style="padding: 15px 25px; border-top: 1px solid #f2f2f2; text-align: right; background: #fafafa; border-radius: 0 0 20px 20px;">
                <button id="closeZonesModalBtn" style="background: #182d21; color: #fff; border: none; padding: 8px 24px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Close</button>
            </div>
        </div>
    </div>

    <style>
        ul#shipping_method { list-style: none !important; padding: 0 !important; margin: 0 !important; width: 100%; }
        ul#shipping_method li { display: flex !important; align-items: flex-start !important; gap: 10px !important; margin-bottom: 15px !important; padding: 5px 0; width: 100%; }
        ul#shipping_method li input[type="radio"] { margin-top: 4px !important; flex-shrink: 0; }
        ul#shipping_method li label { flex: 1; cursor: pointer; display: block; width: 100%; }
        
        /* Layout overrides to ensure style continuity inside checkout tables */
        .woocommerce-checkout-review-order-table ul#shipping_method li { margin-bottom: 12px !important; }
        
        .custom-shipping-rate-wrapper { width: 100%; font-family: inherit; box-sizing: border-box; }
        .ship-row-header { display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 10px; }
        .ship-main-title { font-weight: 700; color: #243f2f; font-size: 15px; }
        .ship-cost { font-weight: 700; color: #06aa64; font-size: 15px; white-space: nowrap; }
        .ship-subtitle { font-size: 13px; color: #666; margin-top: 2px; line-height: 1.3; }
        .ship-min-order { font-size: 13px; color: #444; margin-top: 1px; }
        .open-zone-popup-link:hover { text-decoration: underline !important; opacity: 0.85; }
        
        @keyframes scaleUpZoneMod { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Global event delegation works on both cart and checkout AJAX updates
            $(document).on('click', '.open-zone-popup-link', function(e) {
                e.preventDefault();
                $('#shippingZonesModal').css('display', 'flex');
                $('body').css('overflow', 'hidden'); 
            });

            $(document).on('click', '#closeZonesModal, #closeZonesModalBtn', function() {
                $('#shippingZonesModal').hide();
                $('body').css('overflow', 'auto'); 
            });

            $(document).on('click', '#shippingZonesModal', function(e) {
                if ($(e.target).is('#shippingZonesModal')) {
                    $('#shippingZonesModal').hide();
                    $('body').css('overflow', 'auto');
                }
            });
        });
    </script>
    <?php
}
