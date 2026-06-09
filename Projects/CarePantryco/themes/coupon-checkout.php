<?php

// Fixing the coupon code not updating issue on the checkout page

add_action( 'wp_footer', 'force_checkout_smooth_loader_refresh' );
function force_checkout_smooth_loader_refresh() {
    if ( is_checkout() ) {
        ?>
        <style>
            /* Custom smooth loader overlay */
            #custom-checkout-overlay {
                position: fixed;
                top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(255, 255, 255, 0.85);
                z-index: 999999;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            #custom-checkout-overlay.active {
                opacity: 1;
                pointer-events: auto;
            }
            .custom-spinner {
                width: 50px;
                height: 50px;
                border: 5px solid #f3f3f3;
                border-top: 5px solid #111111; /* Matches dark text/buttons */
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .custom-loader-text {
                margin-top: 15px;
                font-family: sans-serif;
                font-size: 16px;
                color: #333;
                font-weight: 500;
            }
        </style>

        <div id="custom-checkout-overlay">
            <div class="custom-spinner"></div>
            <div class="custom-loader-text">Updating cart...</div>
        </div>

        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                const overlay = document.getElementById('custom-checkout-overlay');

                function triggerSmoothRefresh() {
                    // 1. Show the smooth loading screen immediately
                    if (overlay) {
                        overlay.classList.add('active');
                    }

                    // 2. Wait 900ms to let WooCommerce process the form submit, then reload
                    setTimeout(function() {
                        window.location.reload();
                    }, 900);
                }

                // Listen for clicks on Apply or Remove buttons
                document.body.addEventListener('click', function(event) {
                    // "Apply Coupon" Button
                    if (event.target && event.target.name === 'apply_coupon') {
                        triggerSmoothRefresh();
                    }
                    // "Remove Coupon" Link
                    if (event.target && (event.target.classList.contains('woocommerce-remove-coupon') || event.target.closest('.woocommerce-remove-coupon'))) {
                        triggerSmoothRefresh();
                    }
                });
            });
        </script>
        <?php
    }
}
