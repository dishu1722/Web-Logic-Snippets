<?php 
// Customizing the checkout page

add_action( 'wp_head', 'checkout_instant_title_css' );
function checkout_instant_title_css() {
    if ( ! is_checkout() ) return;
    ?>
    <style id="instant-title-fix">
        /* Hide the old text completely until our JavaScript renames it */
        .c-cart__header { 
            color: transparent !important; 
        }
        /* Make it instantly visible the microsecond it gets the correct title */
        .c-cart__header.title-renamed { 
            color: #243f2f !important; 
        }
    </style>
    <?php
}

add_action( 'wp_footer', 'checkout_custom_frontend_tweaks', 999 );
function checkout_custom_frontend_tweaks() {
    if ( ! is_checkout() ) return;
    ?>
    <script type="text/javascript">
    (function($) {
        // 1. FASTEST REWRITE ENGINE
        function renameCheckoutHeader() {
            $('.c-cart__header').each(function() {
                var $el = $(this);
                var text = $el.text().trim();
                if (text === 'Billing & Shipping' || text === 'Billing and Shipping' || !$el.hasClass('title-renamed')) {
                    $el.html('Billing Details').addClass('title-renamed');
                }
            });
        }

        // Run instantly right here in the footer
        renameCheckoutHeader();

        // 2. MUTATION OBSERVER
        var observer = new MutationObserver(function(mutations) {
            renameCheckoutHeader();
        });
        
        observer.observe(document.documentElement, {
            childList: true,
            subtree: true
        });

        jQuery(document).ready(function($) {
            injectReceiverFields();
            highlightMinimumOrderWarnings();

            // 3. Instant Receiver Form Field Injection with Integrated Circle Info Disclaimers
            function injectReceiverFields() {
                if ($('#custom_receiver_details_section').length === 0) {
                    var receiverFieldsHtml = `
                        <div id="custom_receiver_details_section" class="c-cart__form" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5; clear: both; width: 100%;">
                            <h3 class="c-cart__header title-renamed" style="font-size: 1.4em; margin-bottom: 15px; color: #243f2f;">Recipient Details (in Zimbabwe) <span style="width: 25px; display: inline-block;">💚</span></h3>
                            
                            <div class="receiver-row" style="display: flex; gap: 15px; flex-wrap: wrap;">
                                <p class="form-row form-row-first validate-required" style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                                    <label for="receiver_full_name" class="required_field">Recipient full name&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                    <span class="woocommerce-input-wrapper">
                                        <input type="text" class="input-text" name="receiver_full_name" id="receiver_full_name" placeholder="e.g. Tendai Moyo" required>
                                    </span>
                                </p>
                                <p class="form-row form-row-last validate-required" style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                                    <label for="receiver_phone_number" class="required_field">Recipient phone number&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                    <span class="woocommerce-input-wrapper">
                                        <input type="tel" class="input-text" name="receiver_phone_number" id="receiver_phone_number" placeholder="e.g. +263 77 123 4567" required>
                                    </span>
                                </p>
                            </div>

                            <p class="form-row form-row-wide validate-required" style="margin-bottom: 15px; width: 100%;">
                                <label for="receiver_delivery_address" class="required_field">Delivery / Service address&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                <span class="woocommerce-input-wrapper">
                                    <input type="text" class="input-text" name="receiver_delivery_address" id="receiver_delivery_address" placeholder="e.g. House number, street name, area, landmark" required>
                                </span>
                            </p>

                            <p class="form-row form-row-wide validate-required" style="margin-bottom: 25px; width: 100%;">
                                <label for="receiver_city_suburb" class="required_field">City / Suburb&nbsp;<span class="required" aria-hidden="true">*</span></label>
                                <span class="woocommerce-input-wrapper">
                                    <input type="text" class="input-text" name="receiver_city_suburb" id="receiver_city_suburb" placeholder="e.g. Harare" required>
                                </span>
                            </p>

                            <div class="form-static-disclaimers" style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px; width: 100%;">
                                <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 15px; background-color: rgba(250, 250, 250, 0.8); border: 1px solid #ccead7; border-radius: 8px;">
                    
                                        <div style="background: #004d2c; color: #fff; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">i</div>
                                  
                                    <span style="color: #004d2c; font-size: 13px; font-weight: 500; line-height: 1.45;">Someone must be available at the address during delivery or the service visit.</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 15px; background-color: rgba(250, 250, 250, 0.8); border: 1px solid #ccead7; border-radius: 8px;">
                                    <div style="background: #004d2c; color: #fff; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">i</div>
                                   
                                    <span style="color: #004d2c; font-size: 13px; font-weight: 500; line-height: 1.45;">We do not offer collection. All orders and services are delivered to the address provided.</span>
                                </div>
                                <div style="display: flex; align-items: flex-start; gap: 12px; padding: 12px 15px; background-color: rgba(250, 250, 250, 0.8); border: 1px solid #ccead7; border-radius: 8px;">
                                    <div style="background: #004d2c; color: #fff; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; flex-shrink: 0;">i</div>
                                    <span style="color: #004d2c; font-size: 13px; font-weight: 500; line-height: 1.45;">A valid ID is required upon delivery for grocery orders to help prevent fraud.</span>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    var $target = $('.c-cart__form--billing-fields').length ? $('.c-cart__form--billing-fields') : $('.woocommerce-billing-fields');
                    $target.append(receiverFieldsHtml);
                }
            }

            // 4. Highlight minimum order warnings selectively
            function highlightMinimumOrderWarnings() {
                $('.c-cart__totals-td').css({'color': 'inherit', 'font-weight': 'normal'});

                $('.c-cart__shipping-methods-label').each(function() {
                    var html = $(this).html();
                    
                    if (html && html.includes('PLEASE NOTE THE MINIMUM') && !html.includes('class="shipping-red-alert"')) {
                        var warningIndex = html.indexOf('PLEASE NOTE THE MINIMUM');
                        var priceIndex = html.indexOf('<span class="woocommerce-Price-amount');

                        if (warningIndex !== -1 && priceIndex !== -1) {
                            var baseLocationText = html.substring(0, warningIndex);
                            var warningSegment = html.substring(warningIndex, priceIndex);
                            var priceSegment = html.substring(priceIndex);

                            var newHtml = baseLocationText + 
                                          '<span class="shipping-red-alert" style="color: #d93838; font-weight: 700; display: contents; margin-top: 6px; margin-bottom: 2px;"> ' + warningSegment.trim() + '</span>' + 
                                          priceSegment;

                            $(this).html(newHtml);
                        }
                    }
                });
            }

            $(document.body).on('updated_checkout_fragments updated_checkout layout_updated', function() {
                renameCheckoutHeader();
                injectReceiverFields();
                highlightMinimumOrderWarnings();
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * 6. BACKEND FIELD VALIDATION
 */
add_action( 'woocommerce_checkout_process', 'validate_receiver_details_fields' );
function validate_receiver_details_fields() {
    if ( empty($_POST['receiver_full_name']) ) {
        wc_add_notice( '<strong>Recipient full name</strong> is a required field.', 'error' );
    }
    if ( empty($_POST['receiver_phone_number']) ) {
        wc_add_notice( '<strong>Recipient phone number</strong> is a required field.', 'error' );
    }
    if ( empty($_POST['receiver_delivery_address']) ) {
        wc_add_notice( '<strong>Delivery / Service address</strong> is a required field.', 'error' );
    }
    if ( empty($_POST['receiver_city_suburb']) ) {
        wc_add_notice( '<strong>Receiver City / Suburb</strong> is a required field.', 'error' );
    }
}

/**
 * 7. BULLETPROOF ORDER SAVE ENGINE (Saves directly to Order Object & Metadata)
 */
add_action( 'woocommerce_checkout_create_order', 'save_receiver_details_to_order_object', 10, 2 );
function save_receiver_details_to_order_object( $order, $data ) {
    if ( ! empty( $_POST['receiver_full_name'] ) ) {
        $order->update_meta_data( 'Receiver Full Name', sanitize_text_field( $_POST['receiver_full_name'] ) );
    }
    if ( ! empty( $_POST['receiver_phone_number'] ) ) {
        $order->update_meta_data( 'Receiver Phone Number', sanitize_text_field( $_POST['receiver_phone_number'] ) );
    }
    if ( ! empty( $_POST['receiver_delivery_address'] ) ) {
        $order->update_meta_data( 'Receiver Address', sanitize_text_field( $_POST['receiver_delivery_address'] ) );
    }
    if ( ! empty( $_POST['receiver_city_suburb'] ) ) {
        $order->update_meta_data( 'Receiver City/Suburb', sanitize_text_field( $_POST['receiver_city_suburb'] ) );
    }
}

/**
 * 8. DISPLAY RECEIVER DETAILS NATIVELY IN THE BACKEND EDIT ORDER SCREEN
 * This injects the data beautifully right onto the screen you showed in your screenshot
 */
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'display_receiver_details_in_admin_order_screen' );
function display_receiver_details_in_admin_order_screen( $order ) {
    // Pull metadata keys using the modern CRUD approach
    $res_name    = $order->get_meta( 'Receiver Full Name' );
    $res_phone   = $order->get_meta( 'Receiver Phone Number' );
    $res_address = $order->get_meta( 'Receiver Address' );
    $res_suburb  = $order->get_meta( 'Receiver City/Suburb' );

    // If there is data saved, render it under the Shipping block
    if ( ! empty( $res_name ) || ! empty( $res_address ) ) {
        echo '<br class="clear" />';
        echo '<div class="order_receiver_details" style="border-top: 1px dashed #ccc; padding-top: 12px; margin-top: 12px; clear: both;">';
        echo '<h3 style="font-size: 14px; margin-bottom: 8px; color: #243f2f; display: flex; align-items: center; gap: 5px;">Recipient Details (In Zimbabwe)</h3>';
        echo '<p style="margin: 3px 0; font-size: 12px;"><strong>Name:</strong> ' . esc_html( $res_name ) . '</p>';
        echo '<p style="margin: 3px 0; font-size: 12px;"><strong>Phone:</strong> ' . esc_html( $res_phone ) . '</p>';
        echo '<p style="margin: 3px 0; font-size: 12px;"><strong>Address:</strong> ' . esc_html( $res_address ) . '</p>';
        echo '<p style="margin: 3px 0; font-size: 12px;"><strong>City/Suburb:</strong> ' . esc_html( $res_suburb ) . '</p>';
        echo '</div>';
    }
}
