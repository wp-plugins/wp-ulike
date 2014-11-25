<?php

	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;

	/*******************************************************
	  Widget
	*******************************************************/
	include( plugin_dir_path(__FILE__) . 'classes/class-widget.php');

	function wp_ulike_load_widget() {
		register_widget( 'wp_ulike_posts_widget' );
		register_widget( 'wp_ulike_users_widget' );
	}
	add_action( 'widgets_init', 'wp_ulike_load_widget' );



	/*******************************************************
	  Plugin Dashboard Menu Settings
	*******************************************************/

	//include about page functions
	include( plugin_dir_path(__FILE__) . 'about.php');

	//include logs menu files
	include( plugin_dir_path(__FILE__) . 'logs.php');

	//include settings class
	include( plugin_dir_path(__FILE__) . 'classes/class-settings.php' );

	//activate general setting panel
	$wp_ulike_setting = wp_ulike_create_settings_page(
	  'wp-ulike-settings',
	  __( 'WP ULike Settings', 'alimir' ),
	  array(
		'parent'   => false,
		'title'    =>  __( 'WP ULike', 'alimir' ),
		'icon_url' => 'dashicons-smiley'
	  ),
	  array(
		'wp_ulike_general' => array(
		  'title'  => '<i class="dashicons-before dashicons-admin-settings"></i>' . ' ' . __( 'General','alimir'),
		  'fields' => array(
			'button_type'  => array(
				'type'    => 'radio',
				'label'   => __( 'Button Type', 'alimir'),
				'default'	=> 'image',
				'options' => array(
				'image'   => __( 'Icon', 'alimir'),
				'text'   => __( 'Text', 'alimir')
				)
			),
			'button_text'    => array(
			  'default'	=> __('Like','alimir'),
			  'label' => __( 'Button Text', 'alimir')
			),
			'button_url'    => array(
			  'type'  => 'media',
			  'label' => __( 'Button Icon', 'alimir'),
			  'description' => __( 'Best size: 16x16','alimir')
			),
			'format_number'    => array(
			  'type'	=> 'checkbox',
			  'default'	=> 0,
			  'label' => __('Format Number', 'alimir'),
			  'checkboxlabel' => __('Activate', 'alimir'),
			  'description' => __('Convert numbers of Likes with string (kilobyte) format.', 'alimir') . '<strong> (WHEN? likes>=1000)</strong>'
			),
			'text_after_like'    => array(
			  'default'	=> __('Unlike','alimir'),
			  'label' => __( 'Text After Like', 'alimir')
			),		
			'text_after_unlike'    => array(
			  'default'	=> __('Like Me Again!','alimir'),
			  'label' => __( 'Text After Unlike', 'alimir')
			),
			'permission_text'    => array(
			  'default'	=> __('You have not permission to unlike','alimir'),
			  'label' => __( 'Permission Text', 'alimir')
			)
		  )
		)//end wp_ulike_general
	  ),
	  array(
		'tabs'        => true,
		'updated'     => __('Settings saved.','alimir')
	  )
	);

	//activate other settings panels
	$wp_ulike_setting->apply_settings( array(
	  'wp_ulike_posts' => array(
		'title'  => '<i class="dashicons-before dashicons-admin-post"></i>' . ' ' . __( 'Posts','alimir'),
		'fields' => array(
		  'auto_display'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Automatic display', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('If you disable this option, you have to put manually this code on wordpress while loop', 'alimir') . '<code dir="ltr">&lt;?php if(function_exists(\'wp_ulike\')) wp_ulike(\'get\'); ?&gt;</code>'
		  ),
		  'auto_display_filter'  => array(
			'type'    => 'multi',
			'label'   => __( 'Auto Display Filter','alimir' ),
			'options' => array(
			  'home'   => __('Home', 'alimir'),
			  'single'   => __('Single Posts', 'alimir'),
			  'page'   => __('Pages', 'alimir'),
			  'archive'   => __('Archives', 'alimir'),
			  'category' => __('Categories', 'alimir'),
			  'search' => __('Search Results', 'alimir'),
			  'tag' => __('Tags', 'alimir'),
			  'author' => __('Author Page', 'alimir')
			),
			'description' => __('You can filter theses pages on auto display option.', 'alimir')
		  ),
		  'only_registered_users'  => array(
			'type'  => 'checkbox',
			'default'	=> 0,
			'label' => __('Only registered Users', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('<strong>Only</strong> registered users have permission to like posts.', 'alimir')
		  ),
		  'users_liked_box'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Show Liked Users Box', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('Active this option to show liked users avatars in the bottom of button like.', 'alimir')
		  ),
		  'users_liked_box_avatar_size' => array(
			'type'  => 'number',
			'default'	=> 32,
			'label' => __( 'Size of Gravatars', 'alimir'),
			'description' => __('Size of Gravatars to return (max is 512)', 'alimir')
		  ),		  
		  'users_liked_box_title'  => array(
			'default'	=> __('Users who have LIKED this post:','alimir'),
			'label' => __('Users Like Box Title', 'alimir')
		  )
		)
	  ),//end wp_ulike_posts
	  'wp_ulike_comments' => array(
		'title'  => '<i class="dashicons-before dashicons-admin-comments"></i>' . ' ' . __( 'Comments','alimir'),
		'fields' => array(
		  'auto_display'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Automatic display', 'alimir'),
			'checkboxlabel' => __('<strong>On all comments</strong> at the bottom of the comment', 'alimir'),
			'description' => __('If you disable this option, you have to put manually this code on comments text', 'alimir') . '<code dir="ltr">&lt;?php if(function_exists(\'wp_ulike_comments\')) wp_ulike_comments(\'get\'); ?&gt;</code>'
		  ),
		  'only_registered_users'  => array(
			'type'  => 'checkbox',
			'default'	=> 0,
			'label' => __('Only registered Users', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('<strong>Only</strong> registered users have permission to like comments.', 'alimir')
		  ),
		  'users_liked_box'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Show Liked Users Box', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('Active this option to show liked users avatars in the bottom of button like.', 'alimir')
		  ),
		  'users_liked_box_avatar_size' => array(
			'type'  => 'number',
			'default'	=> 32,
			'label' => __( 'Size of Gravatars', 'alimir'),
			'description' => __('Size of Gravatars to return (max is 512)', 'alimir')
		  ),		  
		  'users_liked_box_title'  => array(
			'default'	=> __('Users who have LIKED this comment:','alimir'),
			'label' => __('Users Like Box Title', 'alimir')
		  )
		)
	  ),//end wp_ulike_comments
	  'wp_ulike_buddypress' => array(
		'title'  => '<i class="dashicons-before dashicons-groups"></i>' . ' ' . __( 'BuddyPress','alimir'),
		'fields' => array(
		  'auto_display'  => array(
			'type'  => 'checkbox',
			'default'	=> 0,
			'label' => __('Automatic display', 'alimir'),
			'checkboxlabel' => __('<strong>On all buddypress activities</strong> at the top of activity', 'alimir'),
			'description' => __('If you disable this option, you have to put manually this code on buddypres activities content', 'alimir') . '<code dir="ltr">&lt;?php if(function_exists(\'wp_ulike_buddypress\')) wp_ulike_buddypress(\'get\'); ?&gt;</code>'
		  ),
		  'only_registered_users'  => array(
			'type'  => 'checkbox',
			'default'	=> 0,
			'label' => __('Only registered Users', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('<strong>Only</strong> registered users have permission to like activities.', 'alimir')
		  ),
		  'new_likes_activity'  => array(
			'type'  => 'checkbox',
			'default'	=> 0,
			'label' => __('BuddyPress Activity', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('insert new likes in buddyPress activity page', 'alimir')
		  ),	  
		  'users_liked_box'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Show Liked Users Box', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' => __('Active this option to show liked users avatars in the bottom of button like.', 'alimir')
		  ),
		  'users_liked_box_avatar_size' => array(
			'type'  => 'number',
			'default'	=> 32,
			'label' => __( 'Size of Gravatars', 'alimir'),
			'description' => __('Size of Gravatars to return (max is 512)', 'alimir')
		  ),	  
		  'users_liked_box_title'  => array(
			'default'	=> __('Users who have liked this activity:','alimir'),
			'label' => __('Users Like Box Title', 'alimir')
		  )
		)
	  ),//end wp_ulike_buddypress
	  'wp_ulike_customize'    => array(
		'title'  => '<i class="dashicons-before dashicons-art"></i>' . ' ' . __( 'Customize','alimir'),
		'fields' => array(
		  'custom_style'  => array(
			'type'  => 'checkbox',
			'default'	=> 0,
			'label' => __('Custom Style', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'attributes'  => array(
			  'class'   => 'wp_ulike_custom_style_activation'
			),		
			'description' => __('Active this option to see the custom style settings.', 'alimir')
		  ),	
		  'btn_bg'  => array(
			'type'  => 'color',
			'label' => __('Button style', 'alimir'),
			'description' => __('Background', 'alimir')
		  ),
		  'btn_border'  => array(
			'type'  => 'color',
			'description' => __('Border Color', 'alimir')
		  ),
		  'btn_color'  => array(
			'type'  => 'color',
			'description' => __('Text Color', 'alimir')
		  ),
		  'counter_bg'  => array(
			'type'  => 'color',
			'label' => __( 'Counter Style', 'alimir'),
			'description' => __('Background', 'alimir')
		  ),
		  'counter_border'  => array(
			'type'  => 'color',
			'description' => __('Border Color', 'alimir')
		  ),
		  'counter_color'  => array(
			'type'  => 'color',
			'description' => __('Text Color', 'alimir')
		  ),
		  'loading_animation'    => array(
			'type'  => 'media',
			'label' => __( 'Loading Animation', 'alimir') . ' (.GIF)',
			'description' => __( 'Best size: 16x16','alimir')
		  )	  
		)
	  ),//end wp_ulike_customize
	  'wp_ulike_admin'    => array(
		'title'  => '<i class="dashicons-before dashicons-dashboard"></i>' . ' ' . __( 'Dashboard','alimir'),
		'fields' => array(
		  'visit_post_logs'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Visit Post Logs Menu', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),	
			'description' => __('If you deactivate this option, "Post Likes Logs" Menu will hidden.', 'alimir')
		  ),	 
		  'visit_comment_logs'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Visit Comment Logs Menu', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),	
			'description' => __('If you deactivate this option, "Comment Likes Logs" Menu will hidden.', 'alimir')
		  ),	 
		  'visit_bp_logs'  => array(
			'type'  => 'checkbox',
			'default'	=> 1,
			'label' => __('Visit Activity Logs Menu', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),	
			'description' => __('If you deactivate this option, "Activity Likes Logs" Menu will hidden.', 'alimir')
		  ) 
		)
	  )//end wp_ulike_admin
	) );

	//create menu pages
	add_action('admin_menu', 'wp_ulike_admin_menu');
	function wp_ulike_admin_menu() {
		
		//Post Like Logs Menu
		if(wp_ulike_get_setting( 'wp_ulike_admin', 'visit_post_logs') == '1')
		add_submenu_page('wp-ulike-settings', __( 'Post Likes Logs', 'alimir' ), __( 'Post Likes Logs', 'alimir' ), 'manage_options', 'wp-ulike-post-logs', 'wp_ulike_post_likes_logs');
		
		//Comment Like Logs Menu
		if(wp_ulike_get_setting( 'wp_ulike_admin', 'visit_comment_logs') == '1')
		add_submenu_page('wp-ulike-settings', __( 'Comment Likes Logs', 'alimir' ), __( 'Comment Likes Logs', 'alimir' ), 'manage_options','wp-ulike-comment-logs', 'wp_ulike_comment_likes_logs');
		
		//Activity Like Logs Menu
		if(wp_ulike_get_setting( 'wp_ulike_admin', 'visit_bp_logs') == '1')
		add_submenu_page('wp-ulike-settings', __( 'Activity Likes Logs', 'alimir' ), __( 'Activity Likes Logs', 'alimir' ), 'manage_options', 'wp-ulike-bp-logs', 'wp_ulike_buddypress_likes_logs');
		
		//WP ULike About Menu
		add_submenu_page('wp-ulike-settings', __( 'About WP ULike', 'alimir' ), __( 'About WP ULike', 'alimir' ), 'manage_options', 'wp-ulike-about', 'wp_ulike_about_page');	
	}