<?php
if ( ! defined('ABSPATH') ) exit;

// Add menu page
add_action('admin_menu', function() {
    add_menu_page(
        'Google Consent Settings',
        'Google Consent',
        'manage_options',
        'ebd-google-consent',
        'ebd_gc_options_page',
        'dashicons-thumbs-up',
        81
    );
});

add_action('admin_notices', function() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'ebd-google-consent') {
        return; // only show on your settings page
    }

    if (isset($_GET['settings-updated'])) {
        if ($_GET['settings-updated']) {
            echo '<div class="notice notice-success is-dismissible"><p>Google consent updated successfully.</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>There was an error updating the Google Consent.</p></div>';
        }
    }
});

// Remove or replace the admin footer text in WP dashboard
add_filter('admin_footer_text', function() {
    return '<p style="font-style: italic; text-align: right;">Developed by <a target="_blank" href="https://evergreenbydesign.com">Evergreen By Design</a></p>';
});

// Remove wordpress versison number from footer
add_filter('update_footer', function() {
    return '';
}, 9999);

// Register settings
add_action('admin_init', function() {

    register_setting('ebd_gc_settings', 'ebd_gc_privacy_policy_path', [
        'type' => 'string',
        'sanitize_callback' => 'ebd_gc_sanitize_path',
        'default' => 'privacy-policy'
    ]);

    register_setting('ebd_gc_settings', 'ebd_gc_gtag_js', [
        'type' => 'string',
        'sanitize_callback' => 'ebd_gc_sanitize_gtag_js',
        'default' => ''
    ]);

    register_setting('ebd_gc_settings', 'ebd_gc_nojs_body', [
        'type' => 'string',
        'sanitize_callback' => 'ebd_gc_sanitize_gtag_js',
        'default' => ''
    ]);

    register_setting('ebd_gc_settings', 'ebd_gc_privacy_policy_message', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_textarea_field',
        'default' => 'This website uses cookies and similar technologies to enhance your experience, analyze site traffic, and improve our services. By clicking ‘Accept,’ or by continued use of our website you consent to our use of cookies.'
    ]);

    register_setting('ebd_gc_settings', 'ebd_gc_privacy_policy_active', [
    'type' => 'integer',
    'sanitize_callback' => 'rest_sanitize_boolean',
    'default' => false
    ]);

    register_setting('ebd_gc_settings', 'ebd_gc_force_accept_consent', [
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
        'default' => false
    ]);

    // New: Days before showing modal again
    register_setting('ebd_gc_settings', 'ebd_gc_modal_repeat_days', [
        'type' => 'integer',
        'sanitize_callback' => 'absint',
        'default' => 730 // 2 years
    ]);
    
    // New: Seconds before showing modal
    register_setting('ebd_gc_settings', 'ebd_gc_modal_delay_seconds', [
        'type' => 'integer',
        'sanitize_callback' => 'absint',
        'default' => 3
    ]);
});

// Sanitization for path (URL or slug)
function ebd_gc_sanitize_path($input) {
    $input = trim($input);
    if (filter_var($input, FILTER_VALIDATE_URL)) {
        return esc_url_raw($input);
    }
    // Only allow slug characters
    return sanitize_title($input);
}

// Sanitization for Google Tag JS
function ebd_gc_sanitize_gtag_js($input) {
    // Remove HTML comments
    $input = preg_replace('/<!--.*?-->/s', '', $input);
    // Strip all tags except allowed by wp_kses_post (removes <script> and others not allowed)
    $input = wp_kses_post($input);
    return $input;
}

// Options page output
function ebd_gc_options_page() {
    ?>
    <div class="wrap">
        <div style="border-bottom: 1px solid lightgray; padding-bottom: 10px;">
            <h1 style="margin-bottom: 5px;">Google Consent Setting</h1>
        </div>
        
        <form method="post" action="options.php">
            <?php settings_fields('ebd_gc_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="ebd_gc_privacy_policy_path">Privacy Policy Path</label></th>
                    <td>
                        <input type="text" id="ebd_gc_privacy_policy_path" name="ebd_gc_privacy_policy_path" value="<?php echo esc_attr(get_option('ebd_gc_privacy_policy_path', 'privacy-policy')); ?>" class="regular-text" required />
                        <p class="description">Enter a URL or slug for your privacy policy. Default: <code>privacy-policy</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_gtag_js">Google Tag Script</label></th>
                    <td>
                        <textarea id="ebd_gc_gtag_js" name="ebd_gc_gtag_js" rows="8" cols="60" required><?php echo esc_textarea(get_option('ebd_gc_gtag_js')); ?></textarea>
                        <p class="description">Paste your Google Tag script for the <code>&lt;head&gt;</code> section.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_nojs_body">No JavaScript Body Tag</label></th>
                    <td>
                        <textarea id="ebd_gc_nojs_body" name="ebd_gc_nojs_body" rows="4" cols="60" required><?php echo esc_textarea(get_option('ebd_gc_nojs_body')); ?></textarea>
                        <p class="description">Content for users with JavaScript disabled (placed in <code>&lt;body&gt;</code>).</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_privacy_policy_message">Privacy Policy Message</label></th>
                    <td>
                        <textarea id="ebd_gc_privacy_policy_message" name="ebd_gc_privacy_policy_message" rows="4" cols="60"><?php echo esc_textarea(get_option('ebd_gc_privacy_policy_message', "This website uses cookies and similar technologies to enhance your experience, analyze site traffic, and improve our services. By clicking ‘Accept,’ or by continued use of our website you consent to our use of cookies")); ?></textarea>
                        <p class="description">Message to display in the privacy policy modal.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_modal_repeat_days">Days Before Showing Modal Again</label></th>
                    <td>
                        <input type="number" id="ebd_gc_modal_repeat_days" name="ebd_gc_modal_repeat_days" value="<?php echo esc_attr(get_option('ebd_gc_modal_repeat_days', 730)); ?>" min="1" class="small-text" />
                        <p class="description">Number of days before the privacy policy modal is shown again to the same user. Default: <code>730 days (2 years)</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_modal_delay_seconds">Delay Before Showing Modal (seconds)</label></th>
                    <td>
                        <input type="number" id="ebd_gc_modal_delay_seconds" name="ebd_gc_modal_delay_seconds" value="<?php echo esc_attr(get_option('ebd_gc_modal_delay_seconds', 3)); ?>" min="0" class="small-text" />
                        <p class="description">How many seconds to wait before showing the privacy policy modal after page load. Default: <code>3 seconds</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_force_accept_consent">Force Accept Consent</label></th>
                    <td>
                        <input type="checkbox" id="ebd_gc_force_accept_consent" name="ebd_gc_force_accept_consent" value="1" <?php checked(1, get_option('ebd_gc_force_accept_consent'), true); ?> />
                        <span class="description">If enabled, users will be forced to accept all 4 core ad consents (ad_storage, ad_user_data, ad_personalization, analytics_storage).</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="ebd_gc_privacy_policy_active">Activate Privacy Policy Consent Modal</label></th>
                    <td>
                        <input type="hidden" name="ebd_gc_privacy_policy_active" value="0" />
                        <input type="checkbox" id="ebd_gc_privacy_policy_active" name="ebd_gc_privacy_policy_active" value="1" <?php checked(1, get_option('ebd_gc_privacy_policy_active'), true); ?> />
                        <span class="description">Enable the privacy policy consent modal on your site.</span>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <p>Version: <?php echo EBD_GC_VERSION ?> </p>
    </div>
    <?php
}