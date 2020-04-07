<?php
/**
 * Fired when the plugin is uninstalled.
 */
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$options = array( 'wc_attribute_menu', 'wc_amm_priority' );
foreach ( $options as $option_name ) {
	delete_option( $option_name );
	delete_site_option( $option_name );
}
