<?php

if ( ! defined('ABSPATH') ) exit;


add_action('wp_enqueue_scripts', function() {
    // Get options
    $privacy_policy_path = get_option('ebd_gc_privacy_policy_path', 'privacy-policy');
    $privacy_policy_message = get_option('ebd_gc_privacy_policy_message', '');
    $privacy_policy_active = get_option('ebd_gc_privacy_policy_active', false);
    $force_accept_consent = get_option('ebd_gc_force_accept_consent', false);
    $delay_seconds = get_option('ebd_gc_modal_delay_seconds', 3);
    

    // Enqueue modal.css
    wp_enqueue_style(
        'ebd-gc-modal-css',
        plugins_url('../css/modal.css', __FILE__)
    );

    // Enqueue js-cookie from CDN
    wp_enqueue_script(
        'js-cookie',
        '//cdn.jsdelivr.net/npm/js-cookie@3.0.5/dist/js.cookie.min.js',
        [],
        '3.0.5',
        true
    );

    // Enqueue modal-dom.js
    wp_enqueue_script(
        'ebd-gc-modal-dom',
        plugins_url('../js/modal-dom.js', __FILE__),
        ['js-cookie'],
        null,
        true
    );

    // Pass options to modal-dom.js
    wp_localize_script('ebd-gc-modal-dom', 'ebd_gc_options', [
        'privacy_policy_path' => $privacy_policy_path,
        'privacy_policy_message' => $privacy_policy_message,
        'force_accept_consent' => $force_accept_consent
    ]);

    // Enqueue modal-control.js, dependent on modal-dom.js
    wp_enqueue_script(
        'ebd-gc-modal-control',
        plugins_url('../js/modal-control.js', __FILE__),
        ['ebd-gc-modal-dom'],
        null,
        true
    );

    // Localize script for modal-control.js
    wp_localize_script('ebd-gc-modal-control', 'ebd_gc_control', [
        'privacy_policy_active' => $privacy_policy_active,
        'delay_seconds' => (int) $delay_seconds,
        'is_logged_in' => is_user_logged_in() ? 1 : 0
    ]);



    // Enqueue human-check.js, dependent on js-cookie and modal-dom.js
    wp_enqueue_script(
        'ebd-gc-human-check',
        plugins_url('../js/human-check.js', __FILE__),
        ['js-cookie', 'ebd-gc-modal-dom'],
        null,
        true
    );
});