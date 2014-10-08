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
	register_setting( 'wp_ulike_options', 'wp_ulike_dislike_text' );
	register_setting( 'wp_ulike_options', 'wp_ulike_onlyRegistered' );
	register_setting( 'wp_ulike_options', 'wp_ulike_user_like_box' );
	register_setting( 'wp_ulike_options', 'wp_ulike_style' );
	register_setting( 'wp_ulike_options', 'wp_ulike_btn_bg' );
	register_setting( 'wp_ulike_options', 'wp_ulike_btn_border' );
	register_setting( 'wp_ulike_options', 'wp_ulike_btn_color' );
	register_setting( 'wp_ulike_options', 'wp_ulike_counter_bg' );
	register_setting( 'wp_ulike_options', 'wp_ulike_counter_border' );
	register_setting( 'wp_ulike_options', 'wp_ulike_counter_color' );	
}


function wp_ulike_settings_page() {
?>
<div class="wrap">
			
		<h3><?php _e('Configuration','alimir'); ?></h3>
			<form method="post" action="options.php">
			<?php settings_fields('wp_ulike_options'); ?>
			<?php do_settings_sections( 'wp_ulike_options' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Image or text?', 'alimir'); ?></th>
					<td>
						<label>
						<input name="wp_ulike_textOrImage" type="radio" value="image" <?php checked('image', get_option( 'wp_ulike_textOrImage' ) ); ?> /><img src="<?php echo plugins_url('assets/css/add.png', dirname(__FILE__)); ?>" alt="image">
						</label>
						<br />
						<label>
						<input name="wp_ulike_textOrImage" type="radio" value="text" <?php checked('text', get_option( 'wp_ulike_textOrImage' ) ); ?> />
						</label>
						<label>
						<input type="text" name="wp_ulike_text" id="wp_ulike_text" value="<?php echo get_option('wp_ulike_text'); ?>" />
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Like Text', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_btn_text">
						<input type="text" name="wp_ulike_btn_text" id="wp_ulike_btn_text" value="<?php echo get_option('wp_ulike_btn_text'); ?>" />
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Dislike Text?', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_dislike_text">
						<input type="text" name="wp_ulike_dislike_text" id="wp_ulike_dislike_text" value="<?php echo get_option('wp_ulike_dislike_text'); ?>" />
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Automatic display', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_onPage">
						<input name="wp_ulike_onPage" id="wp_ulike_onPage" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_onPage' ) ); ?> />
						<?php _e('<strong>On all posts</strong> (home, archives, search) at the bottom of the post', 'alimir'); ?>
						</label>
						<p class="description"><?php _e('If you disable this option, you have to put manually the code', 'alimir'); ?><code dir="ltr">&lt;?php if(function_exists('wp_ulike')) wp_ulike('get'); ?&gt;</code> <?php _e('wherever you want in your template.', 'alimir'); ?></p>
					</td>
				</tr>		
				<tr>
					<th scope="row"><?php _e('Only registered Users', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_onlyRegistered">			
						<input name="wp_ulike_onlyRegistered" id="wp_ulike_onlyRegistered" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_onlyRegistered' ) ); ?> />
						<?php _e('<strong>Active</strong> this option.', 'alimir'); ?>
						</label>
						<p class="description"><?php _e('<strong>Only</strong> registered users have permission to like posts.', 'alimir'); ?></p>
					</td>
				</tr>		
				<tr>
					<th scope="row"><?php _e('Show Users Like Box', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_user_like_box">
						<input name="wp_ulike_user_like_box" id="wp_ulike_user_like_box" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_user_like_box' ) ); ?> />
						<?php _e('Activate', 'alimir'); ?>
						</label>
						<p class="description"><?php _e('Active this option to show users avatar in like box.', 'alimir'); ?></p>
					</td>
				</tr>		
				<tr>
					<th scope="row"><?php _e('Custom Style', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_style">
						<input name="wp_ulike_style" id="wp_ulike_style" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_style' ) ); ?> />
						<?php _e('Activate', 'alimir'); ?>
						</label>
						<p class="description"><?php _e('Active this option to see custom color settings.', 'alimir'); ?></p>
					</td>
				</tr>
				<tr class="checktoshow">
					<th scope="row"><?php _e('Button style', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_btn_bg">
						<input type="text" class="my-color-field" id="wp_ulike_btn_bg" name="wp_ulike_btn_bg" value="<?php echo get_option('wp_ulike_btn_bg'); ?>" />
						</label>
						<p class="description"><?php _e('Background', 'alimir'); ?></p><br />
						<label for="wp_ulike_btn_border">
						<input type="text" class="my-color-field" id="wp_ulike_btn_border" name="wp_ulike_btn_border" value="<?php echo get_option('wp_ulike_btn_border'); ?>" />
						</label>
						<p class="description"><?php _e('Border Color', 'alimir'); ?></p><br />
						<label for="wp_ulike_btn_color">
						<input type="text" class="my-color-field" id="wp_ulike_btn_color" name="wp_ulike_btn_color" value="<?php echo get_option('wp_ulike_btn_color'); ?>" />
						</label>
						<p class="description"><?php _e('Text Color', 'alimir'); ?></p>
					</td>
				</tr>	
				<tr class="checktoshow">
					<th scope="row"><?php _e('Counter Style', 'alimir'); ?></th>
					<td>
						<label for="wp_ulike_counter_bg">
						<input type="text" class="my-color-field" id="wp_ulike_counter_bg" name="wp_ulike_counter_bg" value="<?php echo get_option('wp_ulike_counter_bg'); ?>" />
						</label>
						<p class="description"><?php _e('Background', 'alimir'); ?></p><br />
						<label for="wp_ulike_counter_border">
						<input type="text" class="my-color-field" id="wp_ulike_counter_border" name="wp_ulike_counter_border" value="<?php echo get_option('wp_ulike_counter_border'); ?>" />
						</label>
						<p class="description"><?php _e('Border Color', 'alimir'); ?></p><br />
						<label for="wp_ulike_counter_color">
						<input type="text" class="my-color-field" id="wp_ulike_counter_color" name="wp_ulike_counter_color" value="<?php echo get_option('wp_ulike_counter_color'); ?>" />
						</label>
						<p class="description"><?php _e('Text Color', 'alimir'); ?></p>
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