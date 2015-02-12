<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

$option_name = 'wc_attribute_menu';

delete_option( $option_name );

?>