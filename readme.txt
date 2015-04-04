=== WooCommerce Attributes Menu Manager ===
Contributors: varunms
Author URI: http://varunsridharan.in/
Plugin URL: https://wordpress.org/plugins/woocommerce-attributes-menu-manager/
Tags: Woocommerce,wc,menu,taxonomy,menu manager,attribute,attribute menu, wc attribute menu, affiliate, cart, checkout, commerce, configurable, digital, download, downloadable, e-commerce, ecommerce, inventory, reports, sales, sell, shipping, shop, shopping, stock, store, tax, variable, widgets, woothemes, wordpress ecommerce
Donate link: http://varunsridharan.in
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 0.3
WC requires at least: 1.0
WC tested up to: 2.3.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 

Show Woocommerce Custom Attributes in Wordpress Menu Page

== Description ==
Attributes (which can be used for the layered nav) are a custom taxonomy, meaning you can display them in menus, or display products by attributes.

<h4> Create a template </h4>
You will need to theme your attribute to make it display products how you want. To do this:

* Copy `woocommerce/templates/taxonomy-product_cat.php` into your theme folder
* Rename the template to reflect your attribute – in our example we’d use `taxonomy-pa_size.php`

Thats all there is to it. You will now see this template when viewing taxonomy terms for your custom attribute.

**Settings Available Under**
`Products ==> Attributes Menu Manager`
 
== Screenshots ==
1. Plugin Menu
2. Plugin Settings Page
3. WP Menu Page

== Installation ==
= Minimum Requirements =

* WordPress 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of WooCommerce Attributes Menu Manager, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Attributes Menu Manager"  and click Search Plugins. Once you've found our plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now"

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your Web Server via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

1. Installing alternatives:
 * via Admin Dashboard:
 * Go to 'Plugins > Add New', search for "WooCommerce Attributes Menu Manager", click "install"
 * OR via direct ZIP upload:
 * Upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
 * OR via FTP upload:
 * Upload `woocommerce-quick-buy` folder to the `/wp-content/plugins/` directory
 
2. Activate the plugin through the 'Plugins' menu in WordPress
3. For Settings Look at your `Products ==> Attributes Menu Manager`

== Frequently Asked Questions ==
**I have an idea for your plugin!**  
That's great. We are always open to your input, and we would like to add anything we think will be useful to a lot of people. Please send your comment/idea to varunsridharan23@gmail.com

**I found a bug!**  
Oops. Please User github / WordPress to post bugs.  <a href="https://github.com/technofreaky/WooCommerce-Attributes-Menu-Manager"> Open an Issue </a>

== Changelog ==
= 0.3 =
* Fixed Activation Issue Bug <a href="https://github.com/technofreaky/WooCommerce-Attributes-Menu-Manager/issues/1"> [#1] </a> 
* Fixed Settings Saving / Getting Issue.

= 0.2 =
* Minor Fix
* Added Screenshot 
* Updated ReadMe

= 0.1 =
* Base Version
