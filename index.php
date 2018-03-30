<?php
/*
Copyright 2015  Varun Sridharan  (email : varunsridharan23@gmail.com)
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
Version: 0.6
Author: Varun Sridharan
Text Domain: woocommerce-attributes-menu-manager
Domain Path: /
Author URI: http://varunsridharan.in/
License: GPL2
GitHub Plugin URI: https://github.com/varunsridharan/wc-attributes-menu-manager/
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define( 'WC_AMM_TXT', 'woocommerce-attributes-menu-manager' );

/**
 * Class wc_attributes_menu_manager
 */
class WC_Attributes_Menu_Manager {
	/**
	 * db_key
	 *
	 * @var string
	 */
	private static $db_key;

	/**
	 * wc_amm_priority
	 *
	 * @var string
	 */
	private static $wc_amm_priority;

	/**
	 * default_priority
	 *
	 * @var int
	 */
	private static $default_priority;

	/**
	 * is_file_create_issue
	 *
	 * @var bool
	 */
	private static $is_file_create_issue = false;

	/**
	 * attributes
	 *
	 * @var mixed|string|void
	 */
	private $attributes;

	/**
	 * Construct
	 */
	function __construct() {
		self::$db_key           = 'wc_attribute_menu';
		self::$wc_amm_priority  = 'wc_amm_priority';
		self::$default_priority = 999;
		$this->attributes       = $this->get_settings();
		$this->save_settings();

		register_activation_hook( __FILE__, array( __CLASS__, '_activate' ) );
		add_action( 'plugins_loaded', array( $this, 'after_plugins_loaded' ) );
		add_filter( 'load_textdomain_mofile', array( $this, 'load_plugin_mo_files' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'admin_register_menu' ) );
		add_filter( 'woocommerce_attribute_show_in_nav_menus', array(
			$this,
			'register_menu',
		), $this->get_priority(), 2 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_links' ), 10, 2 );
	}

	/**
	 * Retrives Settings From DB
	 *
	 * @since 0.1
	 * @updated 0.3
	 */
	private function get_settings() {
		$attributes = get_option( self::$db_key );
		if ( ! empty( $attributes ) ) {
			$attributes = unserialize( $attributes );
		} else {
			$attributes = '';
		}
		return $attributes;
	}

	/**
	 * Saves Settings In DB
	 *
	 * @Since 0.1
	 * @updated 0.3
	 */
	public function save_settings() {
		if ( isset( $_REQUEST['action'] ) ) {
			if ( 'save_wc_attribute_menu' === $_REQUEST['action'] ) {
				if ( isset( $_POST['attributes'] ) ) {
					$attributes = array_keys( $_POST['attributes'] );
					$attributes = serialize( $attributes );
				} else {
					$attributes = '';
				}

				if ( isset( $_POST['wc_amm_priority'] ) ) {
					$priority = intval( $_POST['wc_amm_priority'] );

					if ( $priority > 0 ) {
						$wc_amm_save_priority = $priority;
					} else {
						$wc_amm_save_priority = self::$default_priority;
					}
				} else {
					$wc_amm_save_priority = self::$default_priority;
				}

				self::attribute_template_file();

				update_option( self::$wc_amm_priority, $wc_amm_save_priority );
				update_option( self::$db_key, $attributes );
			}
		}

	}

	public function attribute_template_file() {
		$current_temp_dir = get_template_directory();
		if ( empty( $_POST['attributes'] ) ) {
			return false;
		}
		$attributes = array_keys( $_POST['attributes'] );

		foreach ( $attributes as $attribute ) {
			$file_name     = $current_temp_dir . '/woocommerce/taxonomy-' . $attribute . '.php';
			$already_exist = $this->check_attribute_template_file( $attribute );
			if ( ! $already_exist ) {
				$pre_file = WP_PLUGIN_DIR . '/woocommerce/templates/taxonomy-product_cat.php';
				if ( ! copy( $pre_file, $file_name ) ) {
					self::$is_file_create_issue = true;
				}
			}
		}
	}

	public function check_attribute_template_file( $attribute ) {
		$current_temp_dir = get_template_directory();
		$file_name        = $current_temp_dir . '/woocommerce/taxonomy-' . $attribute . '.php';
		$already_exist    = file_exists( $file_name );
		return $already_exist;
	}

	/**
	 * Get Plugin Priority
	 *
	 * @since 0.4
	 * @return int
	 */
	public function get_priority() {
		$priority = get_option( self::$wc_amm_priority );

		if ( ! empty( $priority ) ) {
			return $priority;
		} else {
			return self::$default_priority;
		}

	}

	/**
	 * Runs When the Plugin Is Activated
	 * Filter Use register_activation_hook
	 *
	 * @Since 0.1
	 * @updated 0.3
	 */
	public static function _activate() {
		add_option( self::$db_key, '', '', '' );
		add_option( self::$wc_amm_priority, '99', '', '' );
	}

	/**
	 * Registers Menu Based On Saved Settings
	 *
	 * @param   String $register Refer WC
	 * @param   String  [$name = ''] Name of the attribute
	 *
	 * @returns boolean
	 * Since 0.1
	 */
	public function register_menu( $register, $name = '' ) {
		if ( ! empty( $this->attributes ) ) {
			if ( in_array( $name, $this->attributes ) ) {
				$register = true;
			}
		}
		return $register;
	}

	/**
	 * Set Plugin Text Domain
	 */
	public function after_plugins_loaded() {
		load_plugin_textdomain( WC_AMM_TXT, false, __DIR__ );
	}

	/**
	 * Load translated mo file based on wp settings
	 *
	 * @param $mofile
	 * @param $domain
	 *
	 * @return string
	 */
	public function load_plugin_mo_files( $mofile, $domain ) {
		if ( WC_AMM_TXT === $domain ) {
			return __DIR__ . '/' . get_locale() . '.mo';
		}
		return $mofile;
	}

	/**
	 * Register Plugin Menu
	 * Filter Use admin_menu
	 * Since 0.1
	 */
	public function admin_register_menu() {
		add_submenu_page( 'edit.php?post_type=product', __( 'Attributes Menu Manager', WC_AMM_TXT ), __( 'Attributes Menu Manager', WC_AMM_TXT ), 'manage_woocommerce', 'wc-attribute-menu', array(
			$this,
			'wc_attribute_menu',
		) );
	}

	/**
	 * Generates Page HTML
	 *
	 * @since 0.1
	 */
	public function wc_attribute_menu() {
		$wc_attr_names = wc_get_attribute_taxonomies();

		$saved_attrs = $this->get_settings();

		echo '<div class="wrap">
                <form method="post">
        <h2>' . __( 'WC Attributes Menu Manager', WC_AMM_TXT ) . '</h2>';
		$this->show_messages();
		echo '
        <script>
        

        jQuery(document).ready(function () { jQuery("span.spinner").hide(); jQuery("#submit").click(function () { jQuery("span.spinner").show(); }); });
        </script>
        <style> .checkbox {display:inline-block;position:relative;text-align:left;width:60px;height:30px;background-color:#222;overflow:hidden;-webkit-box-shadow:inset 0 1px 2px black,0 1px 0 rgba(255,255,255,0.1);-moz-box-shadow:inset 0 1px 2px black,0 1px 0 rgba(255,255,255,0.1);box-shadow:inset 0 1px 2px black,0 1px 0 rgba(255,255,255,0.1);-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;}.checkbox input {display:block;position:absolute;top:0;right:0;bottom:0;left:0;width:100%;height:100%;margin:0 0;cursor:pointer;opacity:0;filter:alpha(opacity=0);z-index:2;}.checkbox label {background-color:#3c3c3c;background-image:-webkit-linear-gradient(-40deg,rgba(0,0,0,0),rgba(255,255,255,0.1),rgba(0,0,0,0.2));background-image:-moz-linear-gradient(-40deg,rgba(0,0,0,0),rgba(255,255,255,0.1),rgba(0,0,0,0.2));background-image:-ms-linear-gradient(-40deg,rgba(0,0,0,0),rgba(255,255,255,0.1),rgba(0,0,0,0.2));background-image:-o-linear-gradient(-40deg,rgba(0,0,0,0),rgba(255,255,255,0.1),rgba(0,0,0,0.2));background-image:linear-gradient(-40deg,rgba(0,0,0,0),rgba(255,255,255,0.1),rgba(0,0,0,0.2));-webkit-box-shadow:0 0 0 1px rgba(0,0,0,0.1),0 1px 2px rgba(0,0,0,0.7);-moz-box-shadow:0 0 0 1px rgba(0,0,0,0.1),0 1px 2px rgba(0,0,0,0.7);box-shadow:0 0 0 1px rgba(0,0,0,0.1),0 1px 2px rgba(0,0,0,0.7);-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;display:inline-block;width:40px;text-align:center;font:bold 11px/28px Arial,Sans-Serif;color:#999;text-shadow:0 -1px 0 rgba(0,0,0,0.7);-webkit-transition:margin-left 0.2s ease-in-out;-moz-transition:margin-left 0.2s ease-in-out;-ms-transition:margin-left 0.2s ease-in-out;-o-transition:margin-left 0.2s ease-in-out;transition:margin-left 0.2s ease-in-out;margin:1px;}.checkbox label:before {content:attr(data-off);}.checkbox input:checked + label {margin-left:19px;background-color:#034B78;color:white;}.checkbox input:checked + label:before {content:attr(data-on);}

.bounty-indicator-tab {
    margin-right: 0;
    line-height: 28px;
    display: inline-block;
    margin-left: -4px;
    padding: 0 4px;
    border-radius: 3px;
    color: #FFFFFF !important;
    font-size: 90%;
    font-weight: bold;
    margin-right: 5px;
}
.bounty-indicator-tab.red { background-color: #E74C3C; }
.bounty-indicator-tab.green { background-color: #519E2A; }        
        
        </style>
        ';
		?>


        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <table class="wp-list-table widefat fixed pages">
                            <thead>
                            <tr>
                                <th class="manage-column column-title"><a
                                            href="#"><span><?php _e( 'Name', WC_AMM_TXT ); ?></span></a></th>
                                <th class="manage-column column-title"><a
                                            href="#"><span><?php _e( 'Slug', WC_AMM_TXT ); ?></span></a></th>
                                <th class="manage-column "><?php _e( 'Template File', WC_AMM_TXT ); ?></th>
                                <th class="manage-column column-title"><a
                                            href="#"><span><?php _e( 'Visibility', WC_AMM_TXT ); ?></span></a></th>
                                <th class="manage-column column-author" id="author"
                                    scope="col"><?php _e( 'Menu Status', WC_AMM_TXT ); ?></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="manage-column column-title"><a
                                            href="#"><span><?php _e( 'Name', WC_AMM_TXT ); ?></span></a></th>
                                <th class="manage-column column-title"><a
                                            href="#"><span><?php _e( 'Slug', WC_AMM_TXT ); ?></span></a></th>
                                <th class="manage-column "><?php _e( 'Template File', WC_AMM_TXT ); ?></th>
                                <th class="manage-column column-title"><a
                                            href="#"><span><?php _e( 'Visibility', WC_AMM_TXT ); ?></span></a></th>
                                <th class="manage-column column-author"><?php _e( 'Menu Status', WC_AMM_TXT ); ?></th>
                            </tr>
                            </tfoot>
                            <tbody id="the-list">
							<?php
							if ( ! empty( $wc_attr_names ) ) {
								foreach ( $wc_attr_names as $names ) {

									$checked              = '';
									$attr_slug            = wc_attribute_taxonomy_name( $names->attribute_label );
									$label                = $names->attribute_label;
									$name                 = $names->attribute_name;
									$template_file        = $this->check_attribute_template_file( $attr_slug );
									$status               = '';
									$template_file_status = '';
									if ( ! empty( $saved_attrs ) ) {
										if ( in_array( $attr_slug, $saved_attrs ) ) {
											$checked = 'checked';
										};
									}

									if ( $template_file ) {
										$template_file_status = '<span class="bounty-indicator-tab green">Exist</span>';
									}

									if ( $names->attribute_public == 1 ) {
										$status = '<span class="bounty-indicator-tab green">Visible</span>';
									} elseif ( $names->attribute_public == 0 ) {
										$status = '<span class="bounty-indicator-tab red">Hidden</span>';
									} else {
										$status = '<span class="bounty-indicator-tab green">Visible</span>';
									}
									echo '<tr class="" id="post-170">
                                            <td class="post-title page-title column-title" ><strong><a class="row-title">
                                                <label for="' . $attr_slug . '">' . $name . ' [ ' . $label . ' ]</label></a></strong>
                                            </td>
                                            <td class="post-title page-title column-title" ><strong>
                                                <label for="' . $attr_slug . '">' . $attr_slug . '</label></strong>
                                            </td>
                                             <td >' . $template_file_status . '
                                            </td>
                                            <td>' . $status . ' </td>
                                            <td class="">
    <span class="checkbox">
        <input type="checkbox" id="' . $attr_slug . '" name="attributes[' . $attr_slug . ']"  ' . $checked . '>
        <label data-on="ON" data-off="OFF"></label>
    </span>

                                            </td>
                                        </tr>';
								}
							} else {
								echo '<tr class="" id="post-170">
                                            <td colspan="3" class="post-title page-title column-title" > No Attributes Created.. <a href="' . admin_url( 'edit.php?post_type=product&page=product_attributes' ) . '"> Please 
                                            Create One </a>.
                                            </td> 
                                        </tr>';
							}

							?>
                            </tbody>
                        </table>

                        <input type="hidden" name="action" value="save_wc_attribute_menu">
                        <p class="submit" style="text-align:right; padding:0px 30px;"><span class="spinner"
                                                                                            style="display: inline-block; float:none; vertical-align:middle; margin-right:10px;"></span><input
                                    type="submit" name="submit" id="submit" class="button button-primary"
                                    value="Save Changes"></p>
                        <br class="clear">

                        <div class="postbox">
                            <h3><span><?php _e( 'Create a template', WC_AMM_TXT ); ?></span></h3>
                            <div class="inside">
                                <p><?php _e( 'You will need to theme your attribute to make it display products how you want. To do this:', WC_AMM_TXT ); ?></p>


                                <ul>
                                    <li><?php _e( '* Copy <strong>woocommerce/templates/taxonomy-product_cat.php</strong> into your theme folder', WC_AMM_TXT ); ?></li>
                                    <li><?php _e( '* Rename the template to reflect your attribute <code>taxonomy-{attribute_slug}.php</code> – in our example we’d use <strong>taxonomy-pa_size.php</strong>', WC_AMM_TXT ); ?></li>
                                </ul>
								<?php _e( 'Thats all there is to it. You will now see this template when viewing taxonomy terms for your custom attribute.', WC_AMM_TXT ); ?>
                            </div>
                        </div>
                    </div>


                </div>

                <div id="postbox-container-1" class="postbox-container">
                    <div class="meta-box-sortables">
                        <div class="postbox">
                            <h3><span><?php _e( 'Troubleshoot / F.A.Q', WC_AMM_TXT ); ?></span></h3>
                            <div class="inside">

                                <p>
									<?php _e( '<strong> Some Attribute Not Listing In WP Menu Page ? </strong> <br/> <br/>
                            1. Check attribute Visibility if using latest WooCommerce. if hidden please enable by <strong>Enable Archives?</strong> in edit page
                            <br/><br/>
                            
                            2. Increase plugin priority If Some attribute is not showing in WP Admin Menu Page. also enable the attribute in screen option at WP Admin Menu Page</p>
                        <strong>Plugin Priority : </strong>', WC_AMM_TXT ); ?>
                                    <input type="text" value="<?php echo $this->get_priority(); ?> "
                                           name="wc_amm_priority" id="wc_amm_priority" class="small-text"/>
                            </div>
                        </div>
                        <div class="postbox">
                            <h3>
                                <span><?php _e( 'About WC Attributes Menu Manager <small> V0.6 </small>', WC_AMM_TXT ); ?></span>
                            </h3>
                            <div class="inside">


                                <p><?php _e( 'Show Woocommerce Custom Attributes in WordPress Menu Page. Attributes (which can be used for the layered nav) are a custom taxonomy, meaning you can display them in menus, or display products by attributes.', WC_AMM_TXT ); ?></p>

                                <ul>


                                    <li>
                                        <a href="https://github.com/varunsridharan/wc-attributes-menu-manager"><?php _e( 'View On Github', WC_AMM_TXT ); ?></a>
                                    </li>
                                    <li>
                                        <a href="https://wordpress.org/support/plugin/woocommerce-attributes-menu-manager"><?php _e( 'WordPress Support', WC_AMM_TXT ); ?></a>
                                    </li>
                                    <li>
                                        <a href="https://github.com/varunsridharan/wc-attributes-menu-manager/issues"><?php _e( 'Report Issue', WC_AMM_TXT ); ?></a>
                                    </li>
                                    <li>
                                        <a href="https://wordpress.org/support/view/plugin-reviews/woocommerce-attributes-menu-manager"><?php _e( 'Write A Review', WC_AMM_TXT ); ?></a>
                                    </li>
                                    <li>
                                        <a href="http://paypal.me/varunsridharan23"><?php _e( '♥ Donate', WC_AMM_TXT ); ?></a>
                                    </li>

                                </ul>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <br class="clear">

            </form>
        </div>
        </div>
		<?php


	}

	/**
	 * Show's Plugin Message
	 *
	 * @since 0,1
	 */
	private function show_messages() {

		if ( isset( $_REQUEST['action'] ) ) {
			if ( $_REQUEST['action'] == 'save_wc_attribute_menu' ) {
				echo '<div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong>' . __( 'Settings saved.', WC_AMM_TXT ) . '</strong></p></div>';

				if ( self::$is_file_create_issue ) {
					echo '<div class="error settings-error" id="setting-error-settings_updated"> 
        <p><strong>' . __( 'Unable To Create Template File. Kindly Create It Manual .', WC_AMM_TXT ) . '</strong></p></div>';
				}
			}
		}
	}

	/**
	 * Adds Some Plugin Options
	 *
	 * @param  array  $plugin_meta
	 * @param  string $plugin_file
	 *
	 * @since 0.11
	 * @return array
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( plugin_basename( __FILE__ ) == $plugin_file ) {
			$plugin_meta[] = sprintf( ' <a href="%s">%s</a>', admin_url( 'edit.php?post_type=product&page=wc-attribute-menu' ), __( 'Settings', WC_AMM_TXT ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', 'https://wordpress.org/plugins/woocommerce-attributes-menu-manager/faq/', __( 'F.A.Q', WC_AMM_TXT ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', 'https://github.com/varunsridharan/wc-attributes-menu-manager', __( 'View On Github', WC_AMM_TXT ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', 'https://github.com/varunsridharan/wc-attributes-menu-manager/issues/new', __( 'Report Issue', WC_AMM_TXT ) );
			$plugin_meta[] = sprintf( '&hearts; <a href="%s">%s</a>', 'http://paypal.me/varunsridharan23', __( 'Donate', WC_AMM_TXT ) );
		}
		return $plugin_meta;
	}
}


/**
 * Check if WooCommerce is active
 * if yes then call the class
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	new WC_Attributes_Menu_Manager;
} else {
	add_action( 'admin_notices', 'wc_attributes_menu_manager_plugin_notice' );
}

function wc_attributes_menu_manager_plugin_notice() {
	echo '<div class="error"><p>';
	_e( '<strong> <i> Woocommerce Attributes Menu Manager </i> </strong>', WC_AMM_TXT );
	echo '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce' ) . '">';
	_e( 'Requires  <strong> <u>Woocommerce</u></strong>', WC_AMM_TXT );
	echo '</a>';
	_e( 'To Be Installed And Activated', WC_AMM_TXT );
	echo '</p></div>';
}

?>