<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//include logs menu files
include( plugin_dir_path(__FILE__) . 'wp-logs.php');

function wp_ulike_adminMenu() {
	add_menu_page(__( 'WP ULike', 'alimir' ), __( 'WP ULike', 'alimir' ), 'manage_options', __FILE__, 'wp_ulike_settings_page', 'dashicons-smiley');
	add_submenu_page(__FILE__, __( 'Post Likes Logs', 'alimir' ), __( 'Post Likes Logs', 'alimir' ), 'manage_options', __FILE__ . '/post_logs', 'wp_ulike_post_likes_logs');
	add_submenu_page(__FILE__, __( 'Comment Likes Logs', 'alimir' ), __( 'Comment Likes Logs', 'alimir' ), 'manage_options', __FILE__ . '/comment_logs', 'wp_ulike_comment_likes_logs');
	add_submenu_page(__FILE__, __( 'Activity Likes Logs', 'alimir' ), __( 'Activity Likes Logs', 'alimir' ), 'manage_options', __FILE__ . '/bp_logs', 'wp_ulike_buddypress_likes_logs');
	add_submenu_page(__FILE__, __( 'About WP ULike', 'alimir' ), __( 'About WP ULike', 'alimir' ), 'manage_options', __FILE__ . '/wp_ulike_about', 'wp_ulike_about_page');
	add_action('admin_init', 'wp_ulike_register_mysettings');
	
}
add_action('admin_menu', 'wp_ulike_adminMenu');

function wp_ulike_about_page() {
    include( plugin_dir_path(__FILE__) . 'about-page.php');
}

function wp_ulike_register_mysettings() { // whitelist options
	register_setting( 'wp_ulike_options', 'wp_ulike_onPage' );
	register_setting( 'wp_ulike_options', 'wp_ulike_onComments' );
	register_setting( 'wp_ulike_options', 'wp_ulike_onActivities' );
	register_setting( 'wp_ulike_options', 'wp_ulike_textOrImage' );
	register_setting( 'wp_ulike_options', 'wp_ulike_text' );
	register_setting( 'wp_ulike_options', 'wp_ulike_btn_text' );
	register_setting( 'wp_ulike_options', 'wp_ulike_dislike_text' );
	register_setting( 'wp_ulike_options', 'wp_ulike_onlyRegistered' );
	register_setting( 'wp_ulike_options', 'wp_ulike_bp_activity_add' );
	register_setting( 'wp_ulike_options', 'wp_ulike_user_like_box' );
	register_setting( 'wp_ulike_options', 'wp_ulike_format_number' );
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
	<?php if( isset($_GET['settings-updated']) ) { ?>
		<div id="message" class="updated">
			<p><strong><?php _e('Settings saved.') ?></strong></p>
		</div>
	<?php } ?>		
		<form method="post" action="options.php">
		<?php settings_fields('wp_ulike_options'); ?>
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
				<th scope="row"><?php _e('Comment likes', 'alimir'); ?></th>
				<td>
					<label for="wp_ulike_onComments">
					<input name="wp_ulike_onComments" id="wp_ulike_onComments" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_onComments' ) ); ?> />
					<?php _e('<strong>On all comments</strong> at the bottom of the comment', 'alimir'); ?>
					</label>
					<p class="description"><?php _e('If you disable this option, you have to put manually this code on comments text', 'alimir'); ?><code dir="ltr">&lt;?php if(function_exists('wp_ulike_comments')) wp_ulike_comments('get'); ?&gt;</code></p>
				</td>
			</tr>		
			<tr>
				<th scope="row"><?php _e('Activity likes', 'alimir'); ?></th>
				<td>
					<label for="wp_ulike_onActivities">
					<input name="wp_ulike_onActivities" id="wp_ulike_onActivities" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_onActivities' ) ); ?> />
					<?php _e('<strong>On all buddypress activities</strong> at the top of activity', 'alimir'); ?>
					</label>
					<p class="description"><?php _e('If you disable this option, you have to put manually this code on buddypres activities content', 'alimir'); ?><code dir="ltr">&lt;?php if(function_exists('wp_ulike_buddypress')) wp_ulike_buddypress('get'); ?&gt;</code></p>
				</td>
			</tr>		
			<tr>
				<th scope="row"><?php _e('Only registered Users', 'alimir'); ?></th>
				<td>
					<label for="wp_ulike_onlyRegistered">			
					<input name="wp_ulike_onlyRegistered" id="wp_ulike_onlyRegistered" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_onlyRegistered' ) ); ?> />
					<?php _e('Activate', 'alimir'); ?>
					</label>
					<p class="description"><?php _e('<strong>Only</strong> registered users have permission to like posts.', 'alimir'); ?></p>
				</td>
			</tr>		
			<tr>
				<th scope="row"><?php _e('BuddyPress Activity', 'alimir'); ?></th>
				<td>
					<label for="wp_ulike_bp_activity_add">			
					<input name="wp_ulike_bp_activity_add" id="wp_ulike_bp_activity_add" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_bp_activity_add' ) ); ?> />
					<?php _e('Activate', 'alimir'); ?>
					</label>
					<p class="description"><?php _e('insert new likes in buddyPress activity page', 'alimir'); ?></p>
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
				<th scope="row"><?php _e('Format Number', 'alimir'); ?></th>
				<td>
					<label for="wp_ulike_format_number">
					<input name="wp_ulike_format_number" id="wp_ulike_format_number" type="checkbox" value="1" <?php checked( '1', get_option( 'wp_ulike_format_number' ) ); ?> />
					<?php _e('Activate', 'alimir'); ?>
					</label>
					<p class="description"><?php _e('Convert numbers of Likes with string (kilobyte) format.', 'alimir'); ?><strong> (if -> likes>=1000)</strong></p>
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
		<?php do_settings_sections( 'wp_ulike_options' ); ?>
		<?php submit_button(); ?>
		</form>

</div>
<?php
}