<?php 

/*
Plugin Name: Google Consent
Description: Creates a simple Google Consent pop-up that handles the 4 core Google Analytics Consent types: Analytics, Ads, Marketing, and Personalization. This plugin shows a simple popup that will interact with your google tag and update user consent.
Author: Evergreen By Design
Version: 1.0.4
*/

if ( ! defined('ABSPATH') ) exit;


require_once plugin_dir_path(__FILE__) . 'includes/gtag_options_page.php';

require_once plugin_dir_path(__FILE__) . 'includes/gtag_head.php';

require_once plugin_dir_path(__FILE__) . 'includes/gtag_body.php';

require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';


