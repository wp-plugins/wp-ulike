<?php
/*
Plugin Name:WP ULike
Plugin URI: http://wordpress.org/plugins/wp-ulike
Description: WP ULike plugin allows to integrate Like Button into your WordPress website to allow your visitors to like pages and posts. Its very simple to use and support a widget to display the most liked posts.
Version: 1.0
Author: Ali Mirzaei
Author URI: http://alimir.ir
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

	function wp_ulike_options() {
		add_option('wp_ulike_onPage', '1', '', 'yes');
		add_option('wp_ulike_onlyRegistered', '0', '', 'yes');
		add_option('wp_ulike_textOrImage', 'image', '', 'yes');
		add_option('wp_ulike_text', 'Like', '', 'yes');
		add_option('wp_ulike_btn_text', 'You Liked Before', '', 'yes');
	}

	register_activation_hook(__FILE__, 'wp_ulike_options');

	function wp_ulike_unsetOptions() {
	
		delete_option('wp_ulike_onPage');
		delete_option('wp_ulike_textOrImage');
		delete_option('wp_ulike_text');
		delete_option('wp_ulike_btn_text');
		delete_option('wp_ulike_onlyRegistered');
	}

	register_uninstall_hook(__FILE__, 'wp_ulike_unsetOptions');

	//main function
	function wp_ulike($arg) {
		global $post;
		$post_ID = $post->ID;
		$liked = get_post_meta($post_ID, '_liked', true) != '' ? '+' . get_post_meta($post_ID, '_liked', true) : '0';
		
		
	   if ( (get_option('wp_ulike_onlyRegistered') != '1') or (get_option('wp_ulike_onlyRegistered') == '1' && is_user_logged_in()) ){
	   
	   if (!isset($_COOKIE['liked-'.$post_ID])) {
			if (get_option('wp_ulike_textOrImage') == 'image') {
				$counter = '<a onclick="likeThis('.$post_ID.');" class="image"></a><span class="count-box">'.$liked.'</span>';
			}
			else {
				$counter = '<a onclick="likeThis('.$post_ID.');" class="text">'.get_option('wp_ulike_text').'</a><span class="count-box">'.$liked.'</span>';
			}
		}
		else {
			$counter = '<a class="text">'.get_option('wp_ulike_btn_text').'</a><span class="count-box">'.$liked.'</span>';
		}
		
		
		$wp_ulike = '<div id="wp-ulike-'.$post_ID.'" class="wpulike">';
			$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		}
		else if (get_option('wp_ulike_onlyRegistered') == '1' && !is_user_logged_in()){
		return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this post: ','alimir').'<a href="'.wp_login_url().'"> '.__('click here','alimir').' </a></p>';
		}
	}

	//Process function
	function wp_ulike_process(){
		$post_ID = $_POST['id'];
		$like = get_post_meta($post_ID, '_liked', true);

		if($post_ID != '') {
		
			if (!isset($_COOKIE['liked-'.$post_ID])) {
				$newLike = $like + 1;
				update_post_meta($post_ID, '_liked', $newLike);

				setcookie('liked-'.$post_ID, time(), time()+3600*24*365, '/');

				echo '+' . $newLike;
			}
			else {
				echo $like;
			}
		}
		die();
	}

	//add to post/pages
	if (get_option('wp_ulike_onPage') == '1') {
		function wp_put_ulike($content) {
			if(!is_feed() && !is_page()) {
				$content.= wp_ulike('put');
			}
			return $content;
		}

		add_filter('the_content', 'wp_put_ulike');
	}