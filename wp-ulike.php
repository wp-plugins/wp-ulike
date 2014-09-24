<?php
/*
Plugin Name:WP ULike
Plugin URI: http://wordpress.org/plugins/wp-ulike
Description: WP ULike plugin allows to integrate Like Button into your WordPress website to allow your visitors to like pages and posts. Its very simple to use and support a widget to display the most liked posts.
Version: 1.2
Author: Ali Mirzaei
Author URI: http://about.alimir.ir
Text Domain: alimir
Domain Path: /lang/
License: GPL2
*/

	//Load Translations
	load_plugin_textdomain( 'alimir', false, dirname( plugin_basename( __FILE__ ) ) .'/lang/' );
	__('WP ULike', 'alimir');
	__('WP ULike plugin allows to integrate Like Button into your WordPress website to allow your visitors to like pages and posts. Its very simple to use and support a widget to display the most liked posts.', 'alimir');

	//Load Widget
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-widget.php');
	//Load Options
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-options.php');
	//Load Scripts
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-script.php');
	//Load Functions
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-functions.php');
	
	//Do not change this value
	define('WP_ULIKE_DB_VERSION', '1.0');

	function wp_ulike_options() {
	
		global $wpdb;
		$table_name = $wpdb->prefix . "ulike";
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
				`id` bigint(11) NOT NULL AUTO_INCREMENT,
				`post_id` int(11) NOT NULL,
				`date_time` datetime NOT NULL,
				`ip` varchar(30) NOT NULL,
				`user_id` int(11) NOT NULL,
				`status` varchar(15) NOT NULL,
				PRIMARY KEY (`id`)
			);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			add_option('wp_ulike_dbVersion', WP_ULIKE_DB_VERSION);
		}
		
		add_option('wp_ulike_onPage', '1', '', 'yes');
		add_option('wp_ulike_onlyRegistered', '0', '', 'yes');
		add_option('wp_ulike_user_like_box', '1', '', 'yes');
		add_option('wp_ulike_textOrImage', 'image', '', 'yes');
		add_option('wp_ulike_text', __('Like','alimir'), '', 'yes');
		add_option('wp_ulike_btn_text', __('You Like This','alimir'), '', 'yes');
		add_option('wp_ulike_dislike_text', __('You Dislike This','alimir'), '', 'yes');
	}

	register_activation_hook(__FILE__, 'wp_ulike_options');

	function wp_ulike_unset_options() {
	
		global $wpdb;
		$current_db_version = get_option('wp_ulike_dbVersion');
		
		if ($current_db_version != WP_ULIKE_DB_VERSION) {
		$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."ulike");
		update_option('wp_ulike_dbVersion', $current_db_version);
		}
	
		delete_option('wp_ulike_onPage');
		delete_option('wp_ulike_textOrImage');
		delete_option('wp_ulike_text');
		delete_option('wp_ulike_btn_text');
		delete_option('wp_ulike_user_like_box');
		delete_option('wp_ulike_onlyRegistered');
	}

	register_uninstall_hook(__FILE__, 'wp_ulike_unset_options');
		
	//main function
	function wp_ulike($arg) {
	
		global $post,$wpdb,$user_ID;
		$post_ID = $post->ID;
		$counter = '';
		$liked = get_post_meta($post_ID, '_liked', true) != '' ? '+' . get_post_meta($post_ID, '_liked', true) : '0';
		
	   if ( (get_option('wp_ulike_onlyRegistered') != '1') or (get_option('wp_ulike_onlyRegistered') == '1' && is_user_logged_in()) ){
	   
	   
	   if(is_user_logged_in()){
			$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$user_ID'");
			
			if(!isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0){
				if (get_option('wp_ulike_textOrImage') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.get_option('wp_ulike_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0){
				if(get_user_status($post_ID,$user_ID) == "like"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 1);" class="text">'.get_option('wp_ulike_btn_text').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(get_user_status($post_ID,$user_ID) == "dislike"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.get_option('wp_ulike_dislike_text').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			
			else if(isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0){
				$counter = '<a class="text user-tooltip" title="'.__('Already Voted','alimir').'">'.get_option('wp_ulike_btn_text').'</a><span class="count-box">'.$liked.'</span>';
			}
	   }
	   else{
			if(!isset($_COOKIE['liked-'.$post_ID])){
			
				if (get_option('wp_ulike_textOrImage') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 2);" class="text">'.get_option('wp_ulike_text').'</a><span class="count-box">'.$liked.'</span>';			
				}

			}
			else{
			
				$counter = '<a class="text user-tooltip" title="'.__('Already Voted','alimir').'">'.get_option('wp_ulike_btn_text').'</a><span class="count-box">'.$liked.'</span>';
				
			}
	   }
		
		$wp_ulike = '<div id="wp-ulike-'.$post_ID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		$user_data = get_user_data($post_ID,$user_ID);
		if(get_option('wp_ulike_user_like_box') == '1' && $user_data != '')
		$wp_ulike .= '<br /><p style="margin-top:5px">'.__('Users who have LIKED this post:','alimir').'</p><ul id="tiles">' . $user_data . '</ul>';				
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}
		
		else if (get_option('wp_ulike_onlyRegistered') == '1' && !is_user_logged_in()){
			return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this post: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';
		}
		
	}

	//Process function
	function wp_ulike_process(){
	
		global $wpdb,$user_ID;
		$post_ID = $_POST['id'];
		$like = get_post_meta($post_ID, '_liked', true);
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$user_ID'");
		
		if($post_ID != '') {
			if (!isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0) {
				$newLike = $like + 1;
				update_post_meta($post_ID, '_liked', $newLike);

				setcookie('liked-'.$post_ID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()):
				$ip = wp_ulike_get_real_ip();
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike VALUES ('', '$post_ID', NOW(), '$ip', '$user_ID', 'like')");
				endif;

				echo '+' . $newLike;
			}
			else if ($user_status != 0) {
				if(get_user_status($post_ID,$user_ID) == "like"){
					$newLike = $like - 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'dislike'
						WHERE post_id = '$post_ID' AND user_id = '$user_ID'
					");
					
					echo '+' . $newLike;					
				}
				else{
					$newLike = $like + 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'like'
						WHERE post_id = '$post_ID' AND user_id = '$user_ID'
					");
					
					echo '+' . $newLike;
				}
			}
			else if (isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0){
					echo '+' . $like;
			}
		}
		die();
	}