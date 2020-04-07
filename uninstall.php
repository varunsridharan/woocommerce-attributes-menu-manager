<?php
/**
* Fired when the plugin is uninstalled.
*
* @package Attributes Menu Manager For WooCommerce
* @author Varun Sridharan <varunsridharan23@gmail.com>
* @license GPL - 2.0+
* @link https://wordpress.org/plugins/woocommerce-attributes-menu-manager/
* @copyright 2015 Varun Sridharan [TechNoFreaky]
*/
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
exit;
}

$options = array('wc_attribute_menu','wc_amm_priority');
foreach($options as $option_name){
    delete_option( $option_name );
    delete_site_option( $option_name );
}
