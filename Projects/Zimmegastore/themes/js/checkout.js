//Adding extra fields in the checkout delivery details and DATE field customizing

<script> 
document.addEventListener("DOMContentLoaded", function() {

    // Helper function to insert descriptions directly under inputs
    function addDescription(inputElement, text) {
        if (!inputElement) return;
        var desc = document.createElement('div');
        desc.className = 'custom-field-description';
        desc.innerText = text;
        desc.style.color = 'rgb(148, 148, 148)';
        desc.style.fontSize = '12px';
        desc.style.marginTop = '3px';
        
        var wrapper = inputElement.closest('.form-row') || inputElement.parentNode;
        wrapper.appendChild(desc);
    }

    // 1. ADD THE TOP DELIVERY INFORMATION BOX
    var deliveryDetailsHeading = document.querySelector('.woocommerce-additional-fields > h3') || document.querySelector('h3');
    if (deliveryDetailsHeading && !document.getElementById('custom-delivery-info-box')) {
        var infoBox = document.createElement('div');
        infoBox.id = 'custom-delivery-info-box';
        infoBox.style.backgroundColor = '#FFFDF9';
        infoBox.style.border = '1px solid #FFEAD2';
        infoBox.style.borderRadius = '8px';
        infoBox.style.padding = '16px';
        infoBox.style.marginBottom = '24px';
        infoBox.style.display = 'flex';
        infoBox.style.gap = '12px';
        infoBox.style.fontFamily = 'inherit';

        infoBox.innerHTML = `
            <div style="font-size: 20px; font-weight: bold; line-height: 1;">
                <span style="background-color: #ed6600; color: #fff; border-radius: 40px; height: 17px; width: 17px; display: block; margin-top: 5px; text-align: center; line-height: 19px; font-size: 17px;">ⓘ</span>
            </div>
            <div>
                <strong style="color: #222222; display: block; margin-bottom: 8px; font-size: 15px;">Delivery Information</strong>
                <ul style="margin: 0; padding-left: 20px; color: #444444; font-size: 14px; line-height: 1.6; list-style-type: disc;">
                    <li>Deliveries: Monday–Saturday</li>
                    <li>Receiver must present a valid ID upon delivery.</li>
                    <li>We do not offer collections.</li>
                    <li>Same-day or next-day delivery where available.</li>
                </ul>
            </div>
        `;
        deliveryDetailsHeading.parentNode.insertBefore(infoBox, deliveryDetailsHeading);
    }

    // 2. ADD DESCRIPTIONS UNDER FIELDS 
    var nameInput = document.getElementById('additional_name');
    if (nameInput) {
        addDescription(nameInput, 'The receiver must present a valid ID upon delivery.');
    }

    var addressInput = document.getElementById('order_comments');
    if (addressInput) {
        addDescription(addressInput, 'Home or work address only. We do not offer collections.');
    }

    // 3. CALENDAR LIMITATION & SUNDAY AUTOMATED BLOCK
    var dateInput = document.getElementById('additional_date');
    if (dateInput) {
        addDescription(dateInput, 'Leave blank for the earliest available delivery.');

        // --- CALENDAR LOGIC (ZIMBABWE TIME GMT+2) ---
        var now = new Date();
        var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        var zimTime = new Date(utc + (3600000 * 2)); 
        
        // Rule: If past 9:00 AM Zimbabwe time, block same-day
        if (zimTime.getHours() >= 9) {
            zimTime.setDate(zimTime.getDate() + 1);
        }
        // Rule: If minimum date lands on a Sunday, bump to Monday
        if (zimTime.getDay() === 0) {
            zimTime.setDate(zimTime.getDate() + 1);
        }
        
        var yyyy = zimTime.getFullYear();
        var mm = String(zimTime.getMonth() + 1).padStart(2, '0');
        var dd = String(zimTime.getDate()).padStart(2, '0');
        var minDateString = yyyy + '-' + mm + '-' + dd;
        
        // Lock out past dates dynamically
        dateInput.setAttribute('min', minDateString);
        
        // Instant dynamic validation check (avoids annoying double alerts)
        dateInput.addEventListener('change', function() {
            if (!this.value) return;
            
            var selectedDate = new Date(this.value);
            var day = selectedDate.getUTCDay();
            
            // If Sunday (0) is picked, silently wipe out selection instantly
            if (day === 0) {
                this.value = '';
                this.style.borderColor = '#ed6600';
            } else {
                this.style.borderColor = '';
            }
        });
    }
});

// Customizing delivery zones

(function() {
    var configurations = {
        "flat_rate2": {
            id: "shipping_method_0_flat_rate2",
            title: "Zone 1 – Free Delivery",
            subtitle: "Urban cities and towns",
            minOrder: "Minimum order: US$80",
            badgeClass: "badge-free",
            locations: "Bulawayo, Chinhoyi, Chiredzi, Chitungwiza, Gokwe Townships, Gweru, Harare (Exc Norton), Hwange, Kadoma, Kwekwe (Exc Redcliffe), Marondera, Masvingo, Mutare, Rusape Townships, Ruwa, Vic Falls Urban, Ziko, Zvishavane."
        },
        "flat_rate9": {
            id: "shipping_method_0_flat_rate9",
            title: "Zone 2 Delivery",
            subtitle: "Regional towns and rural areas",
            minOrder: "Minimum order: US$200",
            badgeClass: "badge-warn",
            locations: "Bindura, Chegutu, Chigodora, Concession, Macheke Townships, Mahusekwa, Manica Bridge Mutare, Darwendale, Norton, Domboshava, Glendale, Goromonzi, Headlands, Juru, Karoi, Lower Gweloava, Murehwa Central, Murehwa Rural (Zaranyika, Rupange, Chitimbe, Njedza, Nyamutumbu Musami), Ntabazinduna, Nyabira, Nyanga town, Nyathi, Nyazura, Odzani, Odzi Mutare, Penhalonga, Redcliffe Kwekwe, Shurugwi Urban, Svosve, Triangle, Wengezi, Zimuto Mushagashe (Masvingo), Zimunya (Mutare), Zhakata Village Seke, Zhombe."
        },
        "flat_rate10": {
            id: "shipping_method_0_flat_rate10",
            title: "Zone 3 Delivery",
            subtitle: "Remote rural areas",
            minOrder: "Minimum order: US$350",
            badgeClass: "badge-blue",
            locations: "Chivhu, Chartsworth, Chipinge, Filabusi, Mapitsha, Mt. Darwin, Gwanda, Mhondoro, Mutoko Center, Plumtree, Tsha, Kezi, Nyika, Dolotsho, Zaka, UMP-Murehwa."
        }
    };

    function forceFormatShipping() {
        var labels = document.querySelectorAll('#shipping_method label, .woocommerce-shipping-methods label');
        
        labels.forEach(function(label) {
            var labelFor = label.getAttribute('for') || '';
            var matchingKey = null;

            Object.keys(configurations).forEach(function(key) {
                if (labelFor.indexOf(key) !== -1 || label.innerHTML.indexOf(key) !== -1) {
                    matchingKey = key;
                }
            });

            if (!matchingKey) {
                if (label.textContent.indexOf('ZONE 1') !== -1) matchingKey = "flat_rate2";
                if (label.textContent.indexOf('ZONE 2') !== -1) matchingKey = "flat_rate9";
                if (label.textContent.indexOf('ZONE 3') !== -1) matchingKey = "flat_rate10";
            }

            if (matchingKey) {
                var config = configurations[matchingKey];
                
                if (label.textContent.indexOf('[') !== -1 || !label.classList.contains('formatted-shipping-label')) {
                    var priceElement = label.querySelector('.woocommerce-Price-amount');
                    var priceHtml = priceElement ? priceElement.outerHTML : '<span class="amount free-text">FREE</span>';
                    
                    label.innerHTML = `
                        <div class="shipping-method-info-row">
                            <div class="shipping-method-text-side">
                                <span class="shipping-method-title">${config.title}</span>
                                <span class="shipping-method-subtitle">${config.subtitle}</span>
                                <span class="shipping-method-badge ${config.badgeClass}">${config.minOrder}</span>
                                <a href="#" class="view-delivery-areas-link" data-zone-key="${matchingKey}">View delivery areas →</a>
                            </div>
                            <div class="shipping-method-price-side">
                                ${priceHtml}
                            </div>
                        </div>
                    `;
                    label.className = 'formatted-shipping-label';
                }
            }
        });

        // Inject Important Notice Box
        var shippingList = document.getElementById('shipping_method') || document.querySelector('.woocommerce-shipping-methods');
        if (shippingList && !document.getElementById('shipping-zone-notice-box')) {
            var noticeBox = document.createElement('div');
            noticeBox.id = 'shipping-zone-notice-box';
            noticeBox.innerHTML = `
                <div class="notice-icon">ⓘ</div>
                <div class="notice-body">
                    <strong>Important</strong>
                    <p>Please select the correct delivery zone. Delivery zones are verified against the receiver's address. If an incorrect zone is selected, we will contact you to collect the correct delivery fee before processing your order.</p>
                </div>
            `;
            shippingList.parentNode.appendChild(noticeBox);
        }

        // UNIQUE REWRITE: Completely separate modal IDs away from generic paragraph targets
        if (!document.getElementById('delivery-zone-modal')) {
            var modal = document.createElement('div');
            modal.id = 'delivery-zone-modal';
            modal.className = 'custom-delivery-modal';
            modal.innerHTML = `
                <div class="modal-overlay"></div>
                <div class="modal-container">
                    <div class="modal-header">
                        <h3 id="unique-modal-zone-title">Delivery Locations</h3>
                        <button type="button" class="modal-close-btn">×</button>
                    </div>
                    <div class="modal-body">
                        <div id="unique-modal-zone-locations" class="modal-location-content-box"></div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            modal.querySelector('.modal-close-btn').addEventListener('click', hideModal);
            modal.querySelector('.modal-overlay').addEventListener('click', hideModal);
        }
    }

    function showModal(title, locations) {
        var modal = document.getElementById('delivery-zone-modal');
        if (modal) {
            document.getElementById('unique-modal-zone-title').innerText = title;
            document.getElementById('unique-modal-zone-locations').innerText = locations;
            modal.classList.add('modal-open');
        }
    }

    function hideModal() {
        var modal = document.getElementById('delivery-zone-modal');
        if (modal) modal.classList.remove('modal-open');
    }

    document.addEventListener('click', function(e) {
        var link = e.target.closest('.view-delivery-areas-link');
        if (link) {
            e.preventDefault();
            e.stopPropagation();
            
            var zoneKey = link.getAttribute('data-zone-key');
            var selectedMatch = configurations[zoneKey];

            if (selectedMatch) {
                showModal(selectedMatch.title + " Areas", selectedMatch.locations);
            }
        }
    }, true);

    forceFormatShipping();
    setInterval(forceFormatShipping, 250);

    window.addEventListener('load', function() {
        if (window.jQuery) {
            jQuery(document).body.on('updated_checkout updated_shipping_method checkout_init fragments_refreshed updated_cart_totals', function() {
                forceFormatShipping();
            });
        }
    });
})();
</script>
