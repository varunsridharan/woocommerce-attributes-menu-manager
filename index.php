<?php
/**
 * Plugin Name: Attributes Menu Manager For WooCommerce
 * Plugin URI: http://varunsridharan.in/
 * Description: Attributes Menu Manager For WooCommerce
 * Version: 1.0
 * Author: Varun Sridharan
 * Text Domain: wc-attrmm
 * Domain Path: /i18n/
 * Author URI: http://varunsridharan.in/
 * License: GPL3+
 * GitHub Plugin URI: https://github.com/varunsridharan/wc-attributes-menu-manager/
 * WC requires at least: 2.3.2
 * WC tested up to: 4.6
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Class WC_Attributes_Menu_Manager
 *
 * @author Varun Sridharan <varunsridharan23@gmail.com>
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
	 * WC_Attributes_Menu_Manager constructor.
	 */
	public function __construct() {
		self::$db_key           = 'wc_attribute_menu';
		self::$wc_amm_priority  = 'wc_amm_priority';
		self::$default_priority = 999;
		$this->attributes       = $this->get_settings();
		$this->save_settings();

		/**
		 * @uses admin_register_menu
		 * @uses init_menu
		 * @uses plugin_row_links
		 * @uses _activate
		 */
		register_activation_hook( __FILE__, array( __CLASS__, '_activate' ) );
		add_action( 'admin_menu', array( $this, 'admin_register_menu' ) );
		add_filter( 'woocommerce_attribute_show_in_nav_menus', array( $this, 'init_menu' ), $this->get_priority(), 2 );
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
		$attributes = ( ! empty( $attributes ) ) ? unserialize( $attributes ) : '';
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

	/**
	 * Generates Template File.
	 *
	 * @return bool
	 */
	public function attribute_template_file() {
		$current_temp_dir = get_template_directory();
		if ( empty( $_POST['attributes'] ) ) {
			return false;
		}
		$attributes = array_keys( $_POST['attributes'] );

		if ( ! is_dir( $current_temp_dir . '/woocommerce/' ) ) {
			self::$is_file_create_issue = true;
			return false;
		}

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
		return true;
	}

	/**
	 * Validates if Template File Exists.
	 *
	 * @param $attribute
	 *
	 * @return bool
	 */
	public function check_attribute_template_file( $attribute ) {
		$current_temp_dir = get_template_directory();
		return file_exists( $current_temp_dir . '/woocommerce/taxonomy-' . $attribute . '.php' );
	}

	/**
	 * Get Plugin Priority
	 *
	 * @return int
	 * @since 0.4
	 */
	public function get_priority() {
		$priority = get_option( self::$wc_amm_priority );
		return ( ! empty( $priority ) ) ? $priority : self::$default_priority;
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
	 * @param String $register Refer WC
	 * @param String $name [$name = ''] Name of the attribute
	 *
	 * @return boolean
	 * @Since 0.1
	 */
	public function init_menu( $register, $name = '' ) {
		return ( ! empty( $this->attributes ) && in_array( $name, $this->attributes, true ) ) ? true : $register;
	}

	/**
	 * Register Plugin Menu
	 * Filter Use admin_menu
	 * Since 0.1
	 */
	public function admin_register_menu() {
		$name = __( 'Attributes Menu Manager', 'wc-attrmm' );
		add_submenu_page( 'edit.php?post_type=product', $name, $name, 'manage_woocommerce', 'wc-attribute-menu', array(
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
		$saved_attrs   = $this->get_settings();
		require __DIR__ . '/template/page.php';
	}

	/**
	 * Show's Plugin Message
	 *
	 * @since 0,1
	 */
	private function show_messages() {
		if ( isset( $_REQUEST['action'] ) && 'save_wc_attribute_menu' === $_REQUEST['action'] ) {
			echo '<div class="updated settings-error" id="setting-error-settings_updated"> 
        <p><strong>' . __( 'Settings saved.', 'wc-attrmm' ) . '</strong></p></div>';

			if ( self::$is_file_create_issue ) {
				echo '<div class="error settings-error" id="setting-error-settings_updated"> 
        <p><strong>' . __( 'Unable To Create Template File. Kindly Create It Manual .', 'wc-attrmm' ) . '</strong></p></div>';
			}
		}
	}

	/**
	 * Adds Some Plugin Options
	 *
	 * @param array  $plugin_meta
	 * @param string $plugin_file
	 *
	 * @return array
	 * @since 0.11
	 */
	public function plugin_row_links( $plugin_meta, $plugin_file ) {
		if ( $plugin_file === plugin_basename( __FILE__ ) ) {
			$plugin_meta[] = sprintf( ' <a href="%s">%s</a>', admin_url( 'edit.php?post_type=product&page=wc-attribute-menu' ), __( 'Settings', 'wc-attrmm' ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', 'https://wordpress.org/plugins/woocommerce-attributes-menu-manager/faq/', __( 'F.A.Q', 'wc-attrmm' ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', 'https://github.com/varunsridharan/wc-attributes-menu-manager', __( 'View On Github', 'wc-attrmm' ) );
			$plugin_meta[] = sprintf( '<a href="%s">%s</a>', 'https://github.com/varunsridharan/wc-attributes-menu-manager/issues/new', __( 'Report Issue', 'wc-attrmm' ) );
			$plugin_meta[] = sprintf( '&hearts; <a href="%s">%s</a>', 'http://paypal.me/varunsridharan23', __( 'Donate', 'wc-attrmm' ) );
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
	_e( '<strong> <i> Attributes Menu Manager For WooCommerce </i> </strong>', 'wc-attrmm' );
	echo '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&plugin=woocommerce' ) . '">';
	_e( 'Requires  <strong> <u>Woocommerce</u></strong>', 'wc-attrmm' );
	echo '</a>';
	_e( 'To Be Installed And Activated', 'wc-attrmm' );
	echo '</p></div>';
}
