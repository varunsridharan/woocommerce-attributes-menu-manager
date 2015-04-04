<?php
/*  Copyright 2014  Varun Sridharan  (email : varunsridharan23@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 
    Plugin Name: WooCommerce Attributes Menu Manager
    Plugin URI: http://varunsridharan.in/
    Description: WooCommerce Attributes Menu Manager
    Version: 0.3
    Author: Varun Sridharan
    Author URI: http://varunsridharan.in/
    License: GPL2
*/
defined('ABSPATH') or die("No script kiddies please!"); 


class wc_attributes_menu_manager     {
    private static $db_key;
    private $attributes;
    
    
    /**
     * Construct
     */
    function __construct() {
        $this->db_key = 'wc_attribute_menu';
        $this->attributes = $this->get_settings();
        $this->save_settings();
        
        register_activation_hook( __FILE__, array(__CLASS__ ,'_activate') );
        add_action('admin_menu', array($this,'admin_register_menu'));
        add_filter('woocommerce_attribute_show_in_nav_menus', array($this,'register_menu'), 99, 2);
        
    }
    
    /**
     * Registers Menu Based On Saved Settings
     * @param   String $register  Refer WC
     * @param   String  [$name = ''] Name of the attribute
     * @returns boolean
     * Since 0.1
     */
    public function register_menu( $register, $name = '' ) { 
        if(! empty($this->attributes)){
         if (in_array($name,$this->attributes)) $register = true;
         
        }
        return $register;
    }

    
    /**
     * Runs When the Plugin Is Activated
     * Filter Use register_activation_hook
     * @Since 0.1
     * @updated 0.3
     */
    public static function _activate(){
        add_option(self::$db_key,'','', ''); 
    }
    
    /**
     * Register Plugin Menu
     * Filter Use admin_menu
     * Since 0.1
     */
    public function admin_register_menu(){
        add_submenu_page('edit.php?post_type=product', 'Attributes Menu Manager', 'Attributes Menu Manager', 'manage_woocommerce', 'wc-attribute-menu', array($this,'wc_attribute_menu' ));
    }
    
    /**
     * Saves Settings In DB
     * @Since 0.1
     * @updated 0.3
     */
    public function save_settings(){
        if(isset($_REQUEST['action'])){
			if($_REQUEST['action'] == 'save_wc_attribute_menu'){
				if(isset($_POST['attributes'])){
					$attributes = array_keys($_POST['attributes']);
					$attributes = serialize($attributes);	
				} else {
					$attributes = '';
				}
                
                update_option($this->db_key,$attributes);
            }
        }
        
    }
    
    /**
     * Retrives Settings From DB
     * @since 0.1
     * @updated 0.3
     */
    private function get_settings(){
        $attributes = get_option($this->db_key);
		if(!empty($attributes)){
        	$attributes = unserialize($attributes);
		}else {
			$attributes = '';
		}
        return $attributes;
    }
    
    /**
     * Show's Plugin Message
     * @since 0,1
     */
    private function show_messages(){
         if(isset($_REQUEST['action'])){
			if($_REQUEST['action'] == 'save_wc_attribute_menu'){
                echo '<div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong>Settings saved.</strong></p></div>';
            }
         }
    }
    
    /**
     * Generates Page HTML
     * @since 0.1
     */
    public function wc_attribute_menu(){
        $wc_attr_names = wc_get_attribute_taxonomies();
        $saved_attrs = $this->get_settings();

        echo ' <form method="post">
            
<div class="wrap">
        <h1> WC Attributes Menu Manager </h1>';
        $this->show_messages();
        echo '
        <table class="wp-list-table widefat fixed pages">
            <thead>
                <tr>
                    <th class="manage-column column-title"><a href="#"><span>Attribute Name</span></a></th>
                    <th style="" class="manage-column column-author" id="author" scope="col">Menu Status</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="manage-column column-title"><a href="#"><span>Attribute Name</span></a></th>
                    <th class="manage-column column-author">Menu Status</th>
                </tr>
            </tfoot>

            <tbody id="the-list">';
                foreach($wc_attr_names as $names){
                    $checked = '';
                    $attr_slug = wc_attribute_taxonomy_name($names->attribute_label);
                    if(!empty($saved_attrs)) {
                        if(in_array($attr_slug,$saved_attrs)) {$checked = 'checked';};
                    }
                    
                    echo '<tr class="" id="post-170">
                    <td class="post-title page-title column-title" ><strong><a class="row-title"><label for="'.$attr_slug.'">'.$names->attribute_label.'</label></a></strong></td>
                    <td class=""><label><input type="checkbox" id="'.$attr_slug.'" name="attributes['.$attr_slug.']" class="ios-switch" '.$checked.' /></label></td>
                </tr>';
                
                } 
           echo ' </tbody>
        </table>
    </div> 
    <input type="hidden" name="action" value="save_wc_attribute_menu">
    <p class="submit" style="text-align:right; padding:0px 30px;"> <span class="spinner" style="display: inline-block; float:none; vertical-align:middle; margin-right:10px;"></span><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
    
    <script>
        // JS is only used to add the <div>s
        var switches = document.querySelectorAll(\'input[type="checkbox"].ios-switch\');
                for (var i = 0, sw; sw = switches[i++];) { var div = document.createElement(\'div\'); div.className = \'switch\'; sw.parentNode.insertBefore(div, sw.nextSibling); }

        jQuery(document).ready(function () { jQuery("span.spinner").hide(); jQuery("#submit").click(function () { jQuery("span.spinner").show(); }); });
    </script>
    <style>:root input[type="checkbox"] { position: absolute;opacity: 0;}:root input[type="checkbox"].ios-switch + div {display: inline-block;vertical-align: middle;width: 3em;	height: 1em;border: 1px solid rgba(0,0,0,.3);border-radius: 999px;margin: 0 .5em;background: white;background-image: linear-gradient(rgba(0,0,0,.1), transparent),linear-gradient(90deg, hsl(210, 90%, 60%) 50%, transparent 50%);background-size: 200% 100%;background-position: 100% 0;background-origin: border-box;background-clip: border-box;overflow: hidden;transition-duration: .4s;transition-property: padding, width, background-position, text-indent;box-shadow: 0 .1em .1em rgba(0,0,0,.2) inset,0 .45em 0 .1em rgba(0,0,0,.05) inset;font-size: 150%; /* change this and see how they adjust! */}:root input[type="checkbox"].ios-switch:checked + div {padding-left: 2em;	width: 1em;background-position: 0 0;}:root input[type="checkbox"].ios-switch + div:before {content: "On";float: left;width: 1.65em; height: 1.65em;margin: -.1em;border: 1px solid rgba(0,0,0,.35);border-radius: inherit;background: white;background-image: linear-gradient(rgba(0,0,0,.2), transparent);box-shadow: 0 .1em .1em .1em hsla(0,0%,100%,.8) inset,0 0 .5em rgba(0,0,0,.3);color: white;text-shadow: 0 -1px 1px rgba(0,0,0,.3);text-indent: -2.5em;}:root input[type="checkbox"].ios-switch:active + div:before {background-color: #eee;}:root input[type="checkbox"].ios-switch:focus + div {box-shadow: 0 .1em .1em rgba(0,0,0,.2) inset,0 .45em 0 .1em rgba(0,0,0,.05) inset,0 0 .4em 1px rgba(255,0,0,.5);}:root input[type="checkbox"].ios-switch + div:before,:root input[type="checkbox"].ios-switch + div:after {font: bold 60%/1.9 sans-serif;text-transform: uppercase;}:root input[type="checkbox"].ios-switch + div:after {content: "off";float: left;text-indent: .5em;color: rgba(0,0,0,.45);text-shadow: none;}</style>
    ';
    }
}


/**
 * Check if WooCommerce is active 
 * if yes then call the class
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	new wc_attributes_menu_manager; 
} else {
	add_action( 'admin_notices', 'wc_attributes_menu_manager_plugin_notice' );
}

function wc_attributes_menu_manager_plugin_notice() {
	echo '<div class="error"><p><strong> <i> Woocommerce Attributes Menu Manager </i> </strong> Requires <a href="'.admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce').'"> <strong> <u>Woocommerce</u></strong>  </a> To Be Installed And Activated </p></div>';
} 
?>