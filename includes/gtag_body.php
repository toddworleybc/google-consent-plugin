<?php 

if ( ! defined('ABSPATH') ) exit;

function ebd_insert_noJavascriptTag() {
   
?>

<!-- Google Tag Manager (noscript) -->
<noscript><?php echo get_option( "ebd_gc_nojs_body"); ?></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php
}

add_action("wp_body_open", "ebd_insert_noJavascriptTag", 0);