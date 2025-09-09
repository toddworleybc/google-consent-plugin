<?php 

if ( ! defined('ABSPATH') ) exit;


use YahnisElsts\PluginUpdateChecker\v5\PucFactory;


$puc_path = __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
if ( file_exists($puc_path) ) {
    require_once $puc_path;

    

    $gcp_update_checker = PucFactory::buildUpdateChecker(
        'https://github.com/toddworleybc/google-consent-plugin', // repo
        __FILE__,                                                // main plugin file
        'google-consent-plugin'                                  // plugin slug (folder name)
    );

    // If your default branch is main:
    $gcp_update_checker->setBranch('main');

    // Prefer Release assets (attach a proper ZIP to each GitHub Release).
    $gcp_update_checker->getVcsApi()->enableReleaseAssets();

} else {
    // Fail-safe: show an admin notice instead of fatal error if vendor is missing.
    add_action('admin_notices', function () {
        echo '<div class="notice notice-error"><p><strong>Google Consent Plugin:</strong> Plugin Update Checker not found. Make sure the <code>vendor/</code> folder is deployed (commit it, or ship a Release ZIP that includes it).</p></div>';
    });
  
}
