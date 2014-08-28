<?php

function wp_ulike_adminMenu() {
	add_options_page(__( 'WP Ulike', 'alimir' ), __( 'WP Ulike', 'alimir' ), 'administrator', __FILE__, 'wp_ulike_settings_page', __FILE__);
	add_action('admin_init', 'wp_ulike_register_mysettings');
}
add_action('admin_menu', 'wp_ulike_adminMenu');

function wp_ulike_register_mysettings() { // whitelist options
	register_setting( 'wp_ulike_options', 'wp_ulike_onPage' );
	register_setting( 'wp_ulike_options', 'wp_ulike_textOrImage' );
	register_setting( 'wp_ulike_options', 'wp_ulike_text' );
	register_setting( 'wp_ulike_options', 'wp_ulike_btn_text' );
	register_setting( 'wp_ulike_options', 'wp_ulike_onlyRegistered' );
}


function wp_ulike_settings_page() {
?>
<div class="wrap">
			
		<h3><?php _e('Configuration','alimir'); ?></h3>
			<form method="post" action="options.php">
			<?php settings_fields('wp_ulike_options'); ?>
			<?php do_settings_sections( 'wp_ulike_options' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Image or text?', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_textOrImage" style="padding:3px 20px 3px; background: url(<?php echo plugins_url('assets/css/add.png', dirname(__FILE__)); ?>) no-repeat right center;">
						<?php echo get_option('wp_ulike_textOrImage') == 'image' ? '<input type="radio" name="wp_ulike_textOrImage" id="wp_ulike_textOrImage" value="image" checked="checked">' : '<input type="radio" name="wp_ulike_textOrImage" id="wp_ulike_textOrImage" value="image">'; ?>
						</label>
						<label for="wp_ulike_text">
						<?php echo get_option('wp_ulike_textOrImage') == 'text' ? '<input type="radio" name="wp_ulike_textOrImage" id="wp_ulike_textOrImage" value="text" checked="checked">' : '<input type="radio" name="wp_ulike_textOrImage" id="wp_ulike_textOrImage" value="text">'; ?>
						<input type="text" name="wp_ulike_text" id="wp_ulike_text" value="<?php echo get_option('wp_ulike_text'); ?>" />
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Liked Text?', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_btn_text">
						<input type="text" name="wp_ulike_btn_text" id="wp_ulike_btn_text" value="<?php echo get_option('wp_ulike_btn_text'); ?>" />
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Automatic display', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_onPage">
						<?php echo get_option('wp_ulike_onPage') == '1' ? '<input type="checkbox" name="wp_ulike_onPage" id="wp_ulike_onPage" value="1" checked="checked">' : '<input type="checkbox" name="wp_ulike_onPage" id="wp_ulike_onPage" value="1">'; ?>
						<?php _e('<strong>On all posts</strong> (home, archives, search) at the bottom of the post', 'alimir'); ?>
						</label>
						<p class="description"><?php _e('If you disable this option, you have to put manually the code', 'alimir'); ?><code dir="ltr">&lt;?php if(function_exists('wp_ulike')) wp_ulike('get'); ?&gt;</code> <?php _e('wherever you want in your template.', 'alimir'); ?></p>
					</td>
				</tr>		
				<tr valign="top">
					<th scope="row"><?php _e('Only registered Users', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_onlyRegistered">
						<?php echo get_option('wp_ulike_onlyRegistered') == '1' ? '<input type="checkbox" name="wp_ulike_onlyRegistered" id="wp_ulike_onlyRegistered" value="1" checked="checked">' : '<input type="checkbox" name="wp_ulike_onlyRegistered" id="wp_ulike_onlyRegistered" value="1">'; ?>
						<?php _e('<strong>Active</strong> this option.', 'alimir'); ?>
						</label>
						<p class="description"><?php _e('<strong>Only</strong> registered users have permission to like posts.', 'alimir'); ?></p>
					</td>
				</tr>		
			</table>
			<?php submit_button(); ?>
			</form>

	
	<div id="poststuff" class="ui-sortable meta-box-sortables">
		<div class="postbox">
		<h3><?php _e('Like this plugin?','alimir'); ?></h3>
			<div class="inside">
				<p>
				<?php _e('Show your support by Rating 5 Star in <a href="http://wordpress.org/plugins/wp-ulike"> Plugin Directory reviews</a>','alimir'); ?><br />
				<?php _e('Follow me on <a href="https://www.facebook.com/alimir.ir"> Facebook</a>','alimir'); ?><br />
				<?php _e('Plugin Author Blog: <a href="http://alimir.ir"> Wordpress & Programming World.</a>','alimir'); ?>
				</p>
			</div>
		</div>
	</div>
</div>
<?php
}