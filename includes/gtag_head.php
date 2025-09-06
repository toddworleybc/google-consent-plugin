<?php 

function ebd_insert_gtag() {
    $gtag_code = get_option('ebd_gc_gtag_js');
    $repeat_days = get_option('ebd_gc_modal_repeat_days', 730); // Default to 2 years

    if (!empty($gtag_code)) {
        ?>
        <script>
        // Define dataLayer and the gtag function.
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}

        // Set default consent to 'denied'
        gtag('consent', 'default', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied',
            'analytics_storage': 'denied'
        });
        </script>

        <!-- Google Tag Manager -->
        <?php echo "<script>$gtag_code</script>"; ?>
        <!-- End Google Tag Manager -->

        <!-- JS Cookie CDN -->
        <script src="//cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js"></script>



        <script>
        function consentGrantedAdStorage() {
           
            gtag('consent', 'update', window.ebdConsentGrantedSettings);

            if(Cookies.get('EbdPolicyAccepted') !== 'Granted') {

                Cookies.set('EbdPolicyAccepted', 'Granted', { expires: <?php echo (int)$repeat_days; ?>, path: '/', domain: window.location.hostname });


                Cookies.set("EbdUserSelectedConsent", JSON.stringify(window.ebdConsentGrantedSettings), { expires: <?php echo (int) $repeat_days; ?>, path: '/', domain: window.location.hostname });
            
                //---- Close the modal if it exists
                window.ebdCloseOptionsModal();
                window.ebdCloseModal();
            }

             
        } // end consentGrantedAdStorage


        </script>
    <?php
    }
}

add_action('wp_head', 'ebd_insert_gtag', 0);