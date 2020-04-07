=== Attributes Menu Manager For WooCommerce ===
Contributors: varunms
Tags: Woocommerce,wc,menu,taxonomy,menu manager,attribute,attribute menu, wc attribute menu, affiliate, cart, checkout, commerce, configurable, digital, download, downloadable, e-commerce, ecommerce, inventory, reports, sales, sell, shipping, shop, shopping, stock, store, tax, variable, widgets, woothemes, wordpress ecommerce,menu,attribute menu manager,attr menu,wc attr menu, wc attribute menu manager
Donate link: https://www.paypal.me/varunsridharan23
Requires at least: 3.0
Tested up to: 5.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 

Show Woocommerce Custom Attributes in Wordpress Menu Page

== Description ==
Attributes (which can be used for the layered nav) are a custom taxonomy, meaning you can display them in menus, or display products by attributes.

[youtube https://www.youtube.com/watch?v=s7oELNNim0U]

<h4> Create a template </h4>
If you enable a attribute it will create a template file. if not You  need to do the below stepts 

* Copy `woocommerce/templates/taxonomy-product_cat.php` into your theme folder
* Rename the template to reflect your attribute – in our example we’d use `taxonomy-pa_size.php`

Thats all there is to it. You will now see this template when viewing taxonomy terms for your custom attribute.

**Settings Available Under**
`Products ==> Attributes Menu Manager`
 
== Screenshots ==
1. Plugin Settings Menu
2. Settings Page
3. WooCommerce New Attribute Form [Dont Forget To Check Enable Archives] 
4. WordPress Admin Menu Page

== Installation ==
= Minimum Requirements =

* WordPress version 3.8 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater
* WooCommerce version 2.3.2 or greater
= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To do an automatic install of Attributes Menu Manager For WooCommerce, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "Attributes Menu Manager For WooCommerce"  and click Search Plugins. Once you've found our plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now"

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your Web Server via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

1. Installing alternatives:
 * via Admin Dashboard:
 * Go to 'Plugins > Add New', search for "Attributes Menu Manager For WooCommerce", click "install"
 * OR via direct ZIP upload:
 * Upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
 * OR via FTP upload:
 * Upload `wc-attributes-menu-manager` folder to the `/wp-content/plugins/` directory
 
2. Activate the plugin through the 'Plugins' menu in WordPress
3. For Settings Look at your `Products ==> Attributes Menu Manager`

== Frequently Asked Questions ==
**Some Attribute Not Listing In WP Menu Page ?**
* 1. Check attribute Visibility if using latest WooCommerce. if hidden please enable by Enable Archives? in edit page 
* 2. Increase plugin priority If Some attribute is not showing in WP Admin Menu Page. also enable the attribute in screen option at WP Admin Menu Page

**I have an idea for your plugin!**  
That's great. We are always open to your input, and we would like to add anything we think will be useful to a lot of people. Please send your comment/idea to varunsridharan23@gmail.com

**I found a bug!**  
Oops. Please User github / WordPress to post bugs.  <a href="https://github.com/varunsridharan/WooCommerce-Attributes-Menu-Manager"> Open an Issue </a>

== Changelog ==
= 1.0 - 07/04/2020 =
* Tested : With Latest WP & WC
* Minor Code Cleanup & Added Docblock.

= 0.8 - 05/04/2018 = 
* Fixed An Major Slug Issue.

= 0.7 - 30/03/2018 =
* Tested : With Latest WP & WC
* Minor Code Cleanup & Added Docblock.

= 0.6 - 19/01/2016 =
* Minor BUg Fix
* Tested With Latest Version of WP & WC

= 0.5 - 13/09/2015 =
* Auto Create Template File If Not Existing. its done when saving settings
* Minor Fix
* Tested With Latest WooCommerce & WordPress

= 0.4 - 04/04/2015 =
* Fixed Activation Issue Bug <a href="https://github.com/varunsridharan/woocommerce-attributes-Menu-Manager/issues/1"> [#1] </a>
* Fixed Show on screen not showing attributes <a href="https://github.com/varunsridharan/woocommerce-attributes-Menu-Manager/issues/3"> [#3] </a>
* Added Plugin priority 
* Minor Bug Fix
* Added Paypal Donation Link
* Added Github Link
* Added Report Issue Link
* Added Settings Link

= 0.3 - 02/03/2015 =
* Fixed Activation Issue Bug <a href="https://github.com/varunsridharan/WooCommerce-Attributes-Menu-Manager/issues/1"> [#1] </a> 
* Fixed Settings Saving / Getting Issue.

= 0.2 - 26/02/2015 =
* Minor Fix
* Added Screenshot 
* Updated ReadMe

= 0.1 - 12/02/2015 =
* Base Version