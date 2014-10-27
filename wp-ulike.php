<?php
/*
Plugin Name:WP ULike
Plugin URI: http://wordpress.org/plugins/wp-ulike
Description: WP ULike plugin allows to integrate Like Button into your WordPress website to allow your visitors to like pages, posts AND comments. Its very simple to use and support a widget to display the most liked posts.
Version: 1.6
Author: Ali Mirzaei
Author URI: http://about.alimir.ir
Text Domain: alimir
Domain Path: /lang/
License: GPL2
*/

	//Load Translations
	load_plugin_textdomain( 'alimir', false, dirname( plugin_basename( __FILE__ ) ) .'/lang/' );
	__('WP ULike', 'alimir');
	__('WP ULike plugin allows to integrate Like Button into your WordPress website to allow your visitors to like pages, posts AND comments. Its very simple to use and support a widget to display the most liked posts.', 'alimir');
	
	//Do not change this value
	define('WP_ULIKE_DB_VERSION', '1.1');
	
	//register activation hook
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
		
		$table_name_2 = $wpdb->prefix . "ulike_comments";
		if($wpdb->get_var("show tables like '$table_name_2'") != $table_name_2) {
			$sql = "CREATE TABLE " . $table_name_2 . " (
				`id` bigint(11) NOT NULL AUTO_INCREMENT,
				`comment_id` int(11) NOT NULL,
				`date_time` datetime NOT NULL,
				`ip` varchar(30) NOT NULL,
				`user_id` int(11) NOT NULL,
				`status` varchar(15) NOT NULL,
				PRIMARY KEY (`id`)
			);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			update_option('wp_ulike_dbVersion', WP_ULIKE_DB_VERSION);
		}
		
		add_option('wp_ulike_onPage', '1', '', 'yes');
		add_option('wp_ulike_onComments', '1', '', 'yes');
		add_option('wp_ulike_onlyRegistered', '0', '', 'yes');
		add_option('wp_ulike_bp_activity_add', '0', '', 'yes');
		add_option('wp_ulike_user_like_box', '1', '', 'yes');
		add_option('wp_ulike_textOrImage', 'image', '', 'yes');
		add_option('wp_ulike_text', __('Like','alimir'), '', 'yes');
		add_option('wp_ulike_btn_text', __('You Like This','alimir'), '', 'yes');
		add_option('wp_ulike_dislike_text', __('You Dislike This','alimir'), '', 'yes');
		add_option('wp_ulike_format_number', '0', '', 'yes');
		add_option('wp_ulike_style', '0', '', 'yes');
	}
	register_activation_hook(__FILE__, 'wp_ulike_options');
	
	//plugin update check
	function wp_ulike_update_db_check() {
		if ( get_site_option( 'wp_ulike_dbVersion' ) != WP_ULIKE_DB_VERSION ) {
			wp_ulike_options();
		}
	}
	add_action( 'plugins_loaded', 'wp_ulike_update_db_check' );	
	
	//register uninstall hook
	function wp_ulike_unset_options() {
		delete_option('wp_ulike_onPage');
		delete_option('wp_ulike_onComments');
		delete_option('wp_ulike_onlyRegistered');
		delete_option('wp_ulike_bp_activity_add');
		delete_option('wp_ulike_user_like_box');
		delete_option('wp_ulike_textOrImage');
		delete_option('wp_ulike_text');
		delete_option('wp_ulike_btn_text');
		delete_option('wp_ulike_dislike_text');
		delete_option('wp_ulike_format_number');
		delete_option('wp_ulike_style');
	}
	register_uninstall_hook(__FILE__, 'wp_ulike_unset_options');
	
	//Load plugin widget
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-widget.php');
	//Load plugin setting panel
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-options.php');
	//Load plugin scripts
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-script.php');
	//Load general functions
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-functions.php');
	//Load WP ULike posts functions
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-ulike-posts.php');
	//Load WP ULike comments functions
	include( plugin_dir_path( __FILE__ ) . 'inc/wp-ulike-comments.php');		