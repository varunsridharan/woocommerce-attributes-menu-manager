<script>
	jQuery( document ).ready( function() {
		jQuery( "span.spinner" ).hide();
		jQuery( "#submit" ).click( function() {
			jQuery( "span.spinner" ).show();
		} );
	} );
</script>

<style>
	.checkbox {
		display: inline-block;
		position: relative;
		text-align: left;
		width: 60px;
		height: 30px;
		background-color: #222;
		overflow: hidden;
		-webkit-box-shadow: inset 0 1px 2px black, 0 1px 0 rgba(255, 255, 255, 0.1);
		-moz-box-shadow: inset 0 1px 2px black, 0 1px 0 rgba(255, 255, 255, 0.1);
		box-shadow: inset 0 1px 2px black, 0 1px 0 rgba(255, 255, 255, 0.1);
		-webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;
	}

	.checkbox input {
		display: block;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100%;
		margin: 0 0;
		cursor: pointer;
		opacity: 0;
		filter: alpha(opacity=0);
		z-index: 2;
	}

	.checkbox label {
		background-color: #3c3c3c;
		background-image: -webkit-linear-gradient(-40deg, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.2));
		background-image: -moz-linear-gradient(-40deg, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.2));
		background-image: -ms-linear-gradient(-40deg, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.2));
		background-image: -o-linear-gradient(-40deg, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.2));
		background-image: linear-gradient(-40deg, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.1), rgba(0, 0, 0, 0.2));
		-webkit-box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.7);
		-moz-box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.7);
		box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.7);
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
		display: inline-block;
		width: 40px;
		text-align: center;
		font: bold 11px/28px Arial, Sans-Serif;
		color: #999;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.7);
		-webkit-transition: margin-left 0.2s ease-in-out;
		-moz-transition: margin-left 0.2s ease-in-out;
		-ms-transition: margin-left 0.2s ease-in-out;
		-o-transition: margin-left 0.2s ease-in-out;
		transition: margin-left 0.2s ease-in-out;
		margin: 1px;
	}

	.checkbox label:before {
		content: attr(data-off);
	}

	.checkbox input:checked + label {
		margin-left: 19px;
		background-color: #034B78;
		color: white;
	}

	.checkbox input:checked + label:before {
		content: attr(data-on);
	}

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

	.bounty-indicator-tab.red {
		background-color: #E74C3C;
	}

	.bounty-indicator-tab.green {
		background-color: #519E2A;
	}
</style>

<div class="wrap">
	<form method="post">
		<h2><?php _e( 'WC Attributes Menu Manager', 'wc-attrmm' ); ?></h2>
		<?php $this->show_messages(); ?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div class="meta-box-sortables ui-sortable">
						<table class="wp-list-table widefat fixed pages">
							<thead>
							<tr>
								<th class="manage-column"><?php _e( 'Name', 'wc-attrmm' ); ?></th>
								<th class="manage-column"><?php _e( 'Slug', 'wc-attrmm' ); ?></th>
								<th class="manage-column "><?php _e( 'Template File', 'wc-attrmm' ); ?></th>
								<th class="manage-column"><?php _e( 'Visibility', 'wc-attrmm' ); ?></th>
								<th class="manage-column"><?php _e( 'Menu Status', 'wc-attrmm' ); ?></th>
							</tr>
							</thead>
							<tfoot>
							<tr>
								<th class="manage-column"><?php _e( 'Name', 'wc-attrmm' ); ?></th>
								<th class="manage-column"><?php _e( 'Slug', 'wc-attrmm' ); ?></th>
								<th class="manage-column "><?php _e( 'Template File', 'wc-attrmm' ); ?></th>
								<th class="manage-column"><?php _e( 'Visibility', 'wc-attrmm' ); ?></th>
								<th class="manage-column"><?php _e( 'Menu Status', 'wc-attrmm' ); ?></th>
							</tr>
							</tfoot>

							<tbody id="the-list">
							<?php
							if ( ! empty( $wc_attr_names ) ) {
								foreach ( $wc_attr_names as $names ) {
									$attr_slug            = wc_attribute_taxonomy_name( $names->attribute_name );
									$name                 = $names->attribute_name;
									$template_file        = $this->check_attribute_template_file( $attr_slug );
									$status               = '<span class="bounty-indicator-tab green">' . __( 'Visible', 'wc-attrmm' ) . '</span>';
									$checked              = ( ! empty( $saved_attrs ) && in_array( $attr_slug, $saved_attrs, true ) ) ? 'checked' : '';
									$template_file_status = ( $template_file ) ? '<span class="bounty-indicator-tab green">Exist</span>' : '';

									if ( 0 === $names->attribute_public ) {
										$status = '<span class="bounty-indicator-tab red">' . __( 'Hidden', 'wc-attrmm' ) . '</span>';
									}

									echo <<<HTML
<tr>
	<td><strong>$name [$names->attribute_label]</strong></td>
	<td>$attr_slug</td>
	<td>$template_file_status</td>
	<td>$status</td>
	<td>
		<span class="checkbox">
			<input type="checkbox" id="$attr_slug" name="attributes[$attr_slug]"  $checked>
			<label data-on="ON" data-off="OFF"></label>
		</span>
	</td>
</tr>
HTML;
								}
							} else {
								$no  = __( 'No Attributes Created..', 'wc-attrmm' );
								$c1  = __( 'Please Create One', 'wc-attrmm' );
								$url = admin_url( 'edit.php?post_type=product&page=product_attributes' );
								echo <<<HTML
<tr> <td colspan="3"> $no <a href="$url"> $c1 </a> </td> </tr>
HTML;
							}

							?>
							</tbody>
						</table>
						<input type="hidden" name="action" value="save_wc_attribute_menu">
						<p class="submit" style="text-align:right; padding:0px 30px;">
							<span class="spinner"
								  style="display: inline-block; float:none; vertical-align:middle; margin-right:10px;"></span>
							<input type="submit" name="submit" id="submit" class="button button-primary"
								   value="<?php _e( 'Save Changes', 'wc-attrmm' ); ?>">
						</p>
						<br class="clear">

						<div class="postbox">
							<h3><span><?php _e( 'Create a template', 'wc-attrmm' ); ?></span></h3>
							<div class="inside">
								<p><?php _e( 'You will need to theme your attribute to make it display products how you want. To do this:', 'wc-attrmm' ); ?></p>

								<ul>
									<li><?php _e( '* Copy <strong>woocommerce/templates/taxonomy-product_cat.php</strong> into your theme folder', 'wc-attrmm' ); ?></li>
									<li><?php _e( '* Rename the template to reflect your attribute <code>taxonomy-{attribute_slug}.php</code> – in our example we’d use <strong>taxonomy-pa_size.php</strong>', 'wc-attrmm' ); ?></li>
								</ul>
								<?php _e( 'Thats all there is to it. You will now see this template when viewing taxonomy terms for your custom attribute.', 'wc-attrmm' ); ?>
							</div>
						</div>

					</div>
				</div>

				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox">
							<h3><span><?php _e( 'Troubleshoot / F.A.Q', 'wc-attrmm' ); ?></span></h3>
							<div class="inside">
								<p>
									<?php
									_e( '<strong> Some Attribute Not Listing In WP Menu Page ? </strong>
<br/> <br/>
                            1. Check attribute Visibility if using latest WooCommerce. if hidden please enable by <strong>Enable Archives?</strong> in edit page
                            <br/><br/>
                            2. Increase plugin priority If Some attribute is not showing in WP Admin Menu Page. also enable the attribute in screen option at WP Admin Menu Page
                            <br/><br/>
                        <strong>Plugin Priority : </strong>', 'wc-attrmm' );
									?>
									<input type="text" value="<?php echo $this->get_priority(); ?>"
										   name="wc_amm_priority" id="wc_amm_priority" class="small-text"/>
								</p>
							</div>
						</div>
						<div class="postbox">
							<h3>
								<span><?php _e( 'About WC Attributes Menu Manager <small> V1.0 </small>', 'wc-attrmm' ); ?></span>
							</h3>
							<div class="inside">
								<p><?php _e( 'Show Woocommerce Custom Attributes in WordPress Menu Page. Attributes (which can be used for the layered nav) are a custom taxonomy, meaning you can display them in menus, or display products by attributes.', 'wc-attrmm' ); ?></p>
								<ul>
									<li>
										<a href="https://github.com/varunsridharan/wc-attributes-menu-manager"><?php _e( 'View On Github', 'wc-attrmm' ); ?></a>
									</li>
									<li>
										<a href="https://wordpress.org/support/plugin/woocommerce-attributes-menu-manager"><?php _e( 'WordPress Support', 'wc-attrmm' ); ?></a>
									</li>
									<li>
										<a href="https://github.com/varunsridharan/wc-attributes-menu-manager/issues"><?php _e( 'Report Issue', 'wc-attrmm' ); ?></a>
									</li>
									<li>
										<a href="https://wordpress.org/support/view/plugin-reviews/woocommerce-attributes-menu-manager"><?php _e( 'Write A Review', 'wc-attrmm' ); ?></a>
									</li>
									<li>
										<a href="http://paypal.me/varunsridharan23"><?php _e( '♥ Donate', 'wc-attrmm' ); ?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

			<br class="clear">
		</div>
	</form>
</div>