<?php
// Include integrated plugins
if ( get_option( 'looppress_buddypress_enabled' ) == 1 ) {
    include_once( 'integrations/buddypress_integration.php' );
}
if ( get_option( 'looppress_woocommerce_enabled' ) == 1 ) {
    include_once( 'integrations/woocommerce_integration.php' );
}
if ( get_option( 'looppress_tinymce_enabled' ) == 1 ) {
    include_once( 'integrations/tinymce.php' );
}