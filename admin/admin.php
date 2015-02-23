<?php

	// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) exit;

	/*******************************************************
	  Widget
	*******************************************************/
	include( plugin_dir_path(__FILE__) . 'classes/class-widget.php');
	
	/**
	 * Register WP ULike Widgets
	 *
	 * @author       	Alimir
	 * @since           1.2 
	 * @updated         2.0	  
	 * @return			Void
	 */
	function wp_ulike_load_widget() {
		register_widget( 'wp_ulike_widget' );
	}
	add_action( 'widgets_init', 'wp_ulike_load_widget' );

	/*******************************************************
	  WP ULike CopyRight
	*******************************************************/
	//check for wp ulike page
	if(isset($_GET["page"]) && stripos($_GET["page"], "wp-ulike") !== false)
	add_filter( 'admin_footer_text', 'wp_ulike_copyright');
	/**
	 * Add WP ULike CopyRight in footer
	 *
	 * @author       	Alimir	 	
	 * @param           String $content	 
	 * @since           2.0
	 * @return			String
	 */		
	function wp_ulike_copyright( $text ) {
		return sprintf( __( ' Thank you for choosing <a href="%s" title="Wordpress ULike" target="_blank">WP ULike</a>. Created by <a href="%s" title="Wordpress ULike" target="_blank">Ali Mirzaei</a>' ), 'http://wordpress.org/plugins/wp-ulike/', 'http://about.alimir.ir' );
	}

	/*******************************************************
	  Plugin Dashboard Menu Settings
	*******************************************************/

	//include about menu functions
	include( plugin_dir_path(__FILE__) . 'about.php');

	//include logs menu functions
	include( plugin_dir_path(__FILE__) . 'logs.php');

	//include statistics menu functions
	include( plugin_dir_path(__FILE__) . 'stats.php');

	/**
	 * Start Setting Class Options
	 *
	 * @author       	Alimir	 	
	 * @since           1.7
	 * @updated         2.0
	 * @return			String
	 */
	 
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
			'button_type'  	=> array(
				'type'    	=> 'radio',
				'label'   	=> __( 'Button Type', 'alimir'),
				'default'	=> 'image',
				'options' 	=> array(
					'image' => __( 'Icon', 'alimir'),
					'text'  => __( 'Text', 'alimir')
				)
			),
			'button_text'   => array(
			  'default'		=> __('Like','alimir'),
			  'label' 		=> __( 'Button Text', 'alimir')
			),
			'button_url'    => array(
			  'type'  		=> 'media',
			  'label' 		=> __( 'Button Icon', 'alimir'),
			  'description' => __( 'Best size: 16x16','alimir')
			),
			'text_after_like'   => array(
			  'default'			=> __('Unlike','alimir'),
			  'label' 			=> __( 'Text After Like', 'alimir')
			),
		    'return_initial_after_unlike'  => array(
			  'type'  			=> 'checkbox',
			  'default'			=> 0,
			  'label' 			=> __('Return To The Initial', 'alimir'),
			  'checkboxlabel' 	=> __('Activate', 'alimir'),
			  'description' 	=> __('Return to the initial Like button after Unlike. (Not Showing text after unlike)', 'alimir')
		    ),			
			'text_after_unlike'	=> array(
			  'default'		=> __('Like Me Again!','alimir'),
			  'label' 		=> __( 'Text After Unlike', 'alimir')
			),
			'permission_text'   => array(
			  'default'		=> __('You have not permission to unlike','alimir'),
			  'label' 		=> __( 'Permission Text', 'alimir')
			),
		    'login_type'  		=> array(
			  'type'    	=> 'radio',
			  'label'   	=> __( 'Users Login Type','alimir'),
			  'default'		=> 'alert',
			  'options' 	=> array(
			    'alert'   	=> __('Alert Box', 'alimir'),
			    'button'	=> __('Like Button', 'alimir')
			  )
		    ),
			'login_text'    	=> array(
			  'default'		=> __('You Should Login To Submit Your Like','alimir'),
			  'label' 		=> __( 'Users Login Text', 'alimir')
			),
			'format_number'    	=> array(
			  'type'			=> 'checkbox',
			  'default'			=> 0,
			  'label' 			=> __('Format Number', 'alimir'),
			  'checkboxlabel' 	=> __('Activate', 'alimir'),
			  'description' 	=> __('Convert numbers of Likes with string (kilobyte) format.', 'alimir') . '<strong> (WHEN? likes>=1000)</strong>'
			),				
		  )
		)//end wp_ulike_general
	  ),
	  array(
		'tabs'        		=> true,
		'updated'     		=> __('Settings saved.','alimir')
	  )
	);
	
	//activate other settings panels
	$wp_ulike_setting->apply_settings( array(
	  'wp_ulike_posts' 	=> array(
		'title'  			=> '<i class="dashicons-before dashicons-admin-post"></i>' . ' ' . __( 'Posts','alimir'),
		'fields' 			=> array(
		  'auto_display'  	=> array(
			'type'  		=> 'checkbox',
			'default'		=> 1,
			'label' 		=> __('Automatic display', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir')
		  ),
		  'auto_display_position'  => array(
			'type'    		=> 'radio',
			'label'   		=> __( 'Auto Display Position','alimir'),
			'default'		=> 'bottom',
			'options' 		=> array(
			  'top'   		=> __('Top of Content', 'alimir'),
			  'bottom'   	=> __('Bottom of Content', 'alimir'),
			  'top_bottom' 	=> __('Top and Bottom', 'alimir')
			)
		  ),		  
		  'auto_display_filter'  => array(
			'type'    		=> 'multi',
			'label'   		=> __( 'Auto Display Filter','alimir' ),
			'options' 		=> array(
			  'home'   		=> __('Home', 'alimir'),
			  'single'   	=> __('Single Posts', 'alimir'),
			  'page'   		=> __('Pages', 'alimir'),
			  'archive'   	=> __('Archives', 'alimir'),
			  'category' 	=> __('Categories', 'alimir'),
			  'search' 		=> __('Search Results', 'alimir'),
			  'tag' 		=> __('Tags', 'alimir'),
			  'author' 		=> __('Author Page', 'alimir')
			),
			'description' => __('You can filter theses pages on auto display option.', 'alimir')
		  ),
		  'only_registered_users'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 0,
			'label' 		=> __('Only registered Users', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('<strong>Only</strong> registered users have permission to like posts.', 'alimir')
		  ),
		  'logging_method' 	=> array(
			'type'    		=> 'select',
			'default'		=> 'by_username',
			'label'   		=> __( 'Logging Method','alimir'),
			'options' 		=> array(
			  'do_not_log'  => __('Do Not Log', 'alimir'),
			  'by_cookie'   => __('Logged By Cookie', 'alimir'),
			  'by_ip' 		=> __('Logged By IP', 'alimir'),
			  'by_cookie_ip'=> __('Logged By Cookie & IP', 'alimir'),
			  'by_username' => __('Logged By Username', 'alimir')
			)
		  ),
		  'users_liked_box'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 1,
			'label' 		=> __('Show Liked Users Box', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('Active this option to show liked users avatars in the bottom of button like.', 'alimir')
		  ),
		  'users_liked_box_avatar_size' => array(
			'type'  		=> 'number',
			'default'		=> 32,
			'label' 		=> __( 'Size of Gravatars', 'alimir'),
			'description' 	=> __('Size of Gravatars to return (max is 512)', 'alimir')
		  ),
		  'number_of_users' => array(
			'type'			=> 'number',
			'default'		=> 10,
			'label' 		=> __( 'Number Of The Users', 'alimir'),
			'description' 	=> __('The number of users to show in the users liked box', 'alimir')
		  ),
		  'users_liked_box_template'  => array(
			'type'  		=> 'textarea',
			'default'		=> '<br /><p style="margin-top:5px"> '.__('Users who have LIKED this post:','alimir').'</p> <ul class="tiles">%START_WHILE%<li><a class="user-tooltip" title="%USER_NAME%">%USER_AVATAR%</a></li>%END_WHILE%</ul>',
			'label' 		=> __('Users Like Box Template', 'alimir'),
			'description' 	=> __('Allowed Variables:', 'alimir') . ' <code>%USER_AVATAR%</code> , <code>%USER_NAME%</code> , <code>%START_WHILE%</code> , <code>%END_WHILE%</code>'
		  )			  
		)	
	  ),//end wp_ulike_posts
	  'wp_ulike_comments' => array(
		'title'  			=> '<i class="dashicons-before dashicons-admin-comments"></i>' . ' ' . __( 'Comments','alimir'),
		'fields' 			=> array(
		  'auto_display'  	=> array(
			'type'  		=> 'checkbox',
			'default'		=> 1,
			'label' 		=> __('Automatic display', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir')
		  ),
		  'auto_display_position'  => array(
			'type'   		=> 'radio',
			'label'   		=> __( 'Auto Display Position','alimir'),
			'default'		=> 'bottom',
			'options' 	=> array(
			  'top'   		=> __('Top of Content', 'alimir'),
			  'bottom'   	=> __('Bottom of Content', 'alimir'),
			  'top_bottom' 	=> __('Top and Bottom', 'alimir')
			)
		  ),			  
		  'only_registered_users'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 0,
			'label' 		=> __('Only registered Users', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('<strong>Only</strong> registered users have permission to like comments.', 'alimir')
		  ),
		  'logging_method' => array(
			'type'    		=> 'select',
			'default'		=> 'by_username',
			'label'   		=> __( 'Logging Method','alimir'),
			'options' => array(
			  'do_not_log'  => __('Do Not Log', 'alimir'),
			  'by_cookie'   => __('Logged By Cookie', 'alimir'),
			  'by_ip' 		=> __('Logged By IP', 'alimir'),
			  'by_cookie_ip'=> __('Logged By Cookie & IP', 'alimir'),
			  'by_username' => __('Logged By Username', 'alimir')
			)
		  ),		  
		  'users_liked_box'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 1,
			'label' 		=> __('Show Liked Users Box', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('Active this option to show liked users avatars in the bottom of button like.', 'alimir')
		  ),
		  'users_liked_box_avatar_size' => array(
			'type'  		=> 'number',
			'default'		=> 32,
			'label' 		=> __( 'Size of Gravatars', 'alimir'),
			'description' 	=> __('Size of Gravatars to return (max is 512)', 'alimir')
		  ),		  
		  'number_of_users' => array(
			'type'  		=> 'number',
			'default'		=> 10,
			'label' 		=> __( 'Number Of The Users', 'alimir'),
			'description'	=> __('The number of users to show in the users liked box', 'alimir')
		  ),
		  'users_liked_box_template'  => array(
			'type'  		=> 'textarea',
			'default'		=> '<br /><p style="margin-top:5px"> '.__('Users who have LIKED this comment:','alimir').'</p> <ul class="tiles">%START_WHILE%<li><a class="user-tooltip" title="%USER_NAME%">%USER_AVATAR%</a></li>%END_WHILE%</ul>',
			'label' 		=> __('Users Like Box Template', 'alimir'),
			'description' 	=> __('Allowed Variables:', 'alimir') . ' <code>%USER_AVATAR%</code> , <code>%USER_NAME%</code> , <code>%START_WHILE%</code> , <code>%END_WHILE%</code>'
		  )	
		)
	  ),//end wp_ulike_comments
	  'wp_ulike_buddypress' => array(
		'title'  			=> '<i class="dashicons-before dashicons-groups"></i>' . ' ' . __( 'BuddyPress','alimir'),
		'fields' 	=> array(
		  'auto_display'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 0,
			'label' 		=> __('Automatic display', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir')
		  ),
		  'auto_display_position'  => array(
			'type'    		=> 'radio',
			'label'   		=> __( 'Auto Display Position','alimir'),
			'default'		=> 'bottom',
			'options' 		=> array(
			  'content'   	=> __('Activity Content', 'alimir'),
			  'meta' 		=> __('Activity Meta', 'alimir')
			)
		  ),		  
		  'only_registered_users'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 0,
			'label' 		=> __('Only registered Users', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('<strong>Only</strong> registered users have permission to like activities.', 'alimir')
		  ),
		  'logging_method' => array(
			'type'    		=> 'select',
			'default'		=> 'by_cookie_ip',
			'label'   		=> __( 'Logging Method','alimir'),
			'options' => array(
			  'do_not_log'  => __('Do Not Log', 'alimir'),
			  'by_cookie'   => __('Logged By Cookie', 'alimir'),
			  'by_ip' 		=> __('Logged By IP', 'alimir'),
			  'by_cookie_ip'=> __('Logged By Cookie & IP', 'alimir'),
			  'by_username' => __('Logged By Username', 'alimir')
			)
		  ),		    
		  'users_liked_box'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 1,
			'label' 		=> __('Show Liked Users Box', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('Active this option to show liked users avatars in the bottom of button like.', 'alimir')
		  ),
		  'users_liked_box_avatar_size' => array(
			'type'  		=> 'number',
			'default'		=> 32,
			'label' 		=> __( 'Size of Gravatars', 'alimir'),
			'description'	=> __('Size of Gravatars to return (max is 512)', 'alimir')
		  ),
		  'number_of_users' => array(
			'type'  		=> 'number',
			'default'		=> 10,
			'label' 		=> __( 'Number Of The Users', 'alimir'),
			'description' 	=> __('The number of users to show in the users liked box', 'alimir')
		  ),
		  'users_liked_box_template'  => array(
			'type'  		=> 'textarea',
			'default'		=> '<br /><p style="margin-top:5px"> '.__('Users who have liked this activity:','alimir').'</p> <ul class="tiles">%START_WHILE%<li><a class="user-tooltip" title="%USER_NAME%">%USER_AVATAR%</a></li>%END_WHILE%</ul>',
			'label' 		=> __('Users Like Box Template', 'alimir'),
			'description' 	=> __('Allowed Variables:', 'alimir') . ' <code>%USER_AVATAR%</code> , <code>%USER_NAME%</code> , <code>%START_WHILE%</code> , <code>%END_WHILE%</code>'
		  ),
		  'new_likes_activity'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 0,
			'label' 		=> __('BuddyPress Activity', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'description' 	=> __('insert new likes in buddyPress activity page', 'alimir')
		  ),		  
		  'bp_post_activity_add_header'  => array(
			'type'  		=> 'textarea',
			'default'		=> '<strong>%POST_LIKER%</strong> liked <a href="%POST_PERMALINK%" title="%POST_TITLE%">%POST_TITLE%</a>. (So far, This post has <span class="badge">%POST_COUNT%</span> likes)',
			'label' 		=> __('Post Activity Text', 'alimir'),
			'description' 	=> __('Allowed Variables:', 'alimir') . ' <code>%POST_LIKER%</code> , <code>%POST_PERMALINK%</code> , <code>%POST_COUNT%</code> , <code>%POST_TITLE%</code>'
		  ),
		  'bp_comment_activity_add_header'  => array(
			'type'  		=> 'textarea',
			'default'		=> '<strong>%COMMENT_LIKER%</strong> liked <strong>%COMMENT_AUTHOR%</strong> comment. (So far, %COMMENT_AUTHOR% has <span class="badge">%COMMENT_COUNT%</span> likes for this comment)',
			'label' 		=> __('Comment Activity Text', 'alimir'),
			'description' 	=> __('Allowed Variables:', 'alimir') . ' <code>%COMMENT_LIKER%</code> , <code>%COMMENT_AUTHOR%</code> , <code>%COMMENT_COUNT%</code>'
		  )
		)
	  ),//end wp_ulike_buddypress
	  'wp_ulike_customize'    => array(
		'title'  => '<i class="dashicons-before dashicons-art"></i>' . ' ' . __( 'Customize','alimir'),
		'fields' => array(
		  'custom_style'  => array(
			'type'  		=> 'checkbox',
			'default'		=> 0,
			'label' 		=> __('Custom Style', 'alimir'),
			'checkboxlabel' => __('Activate', 'alimir'),
			'attributes'  	=> array(
			  'class'   	=> 'wp_ulike_custom_style_activation'
			),		
			'description' 	=> __('Active this option to see the custom style settings.', 'alimir')
		  ),	
		  'btn_bg'  => array(
			'type'  		=> 'color',
			'label' 		=> __('Button style', 'alimir'),
			'description' 	=> __('Background', 'alimir')
		  ),
		  'btn_border'  => array(
			'type'  		=> 'color',
			'description' 	=> __('Border Color', 'alimir')
		  ),
		  'btn_color'  => array(
			'type'  		=> 'color',
			'description' 	=> __('Text Color', 'alimir')
		  ),
		  'counter_bg'  => array(
			'type'  		=> 'color',
			'label' 		=> __( 'Counter Style', 'alimir'),
			'description' 	=> __('Background', 'alimir')
		  ),
		  'counter_border'  => array(
			'type'  		=> 'color',
			'description' 	=> __('Border Color', 'alimir')
		  ),
		  'counter_color'  => array(
			'type'  		=> 'color',
			'description' 	=> __('Text Color', 'alimir')
		  ),
		  'loading_animation'    => array(
			'type'  		=> 'media',
			'label' 		=> __( 'Loading Animation', 'alimir') . ' (.GIF)',
			'description' 	=> __( 'Best size: 16x16','alimir')
		  )	  
		)
	  )//end wp_ulike_customize
	) );

	
	/**
	 * Add menu to admin
	 *
	 * @author       	Alimir	 	
	 * @since           1.0
	 * @updated         2.1
	 * @return			String
	 */
	add_action('admin_menu', 'wp_ulike_admin_menu');
	function wp_ulike_admin_menu() {
	
		//Post Like Logs Menu
		$posts_screen 		= add_submenu_page(null, __( 'Post Likes Logs', 'alimir' ), __( 'Post Likes Logs', 'alimir' ), 'manage_options', 'wp-ulike-post-logs', 'wp_ulike_post_likes_logs');
		add_action("load-$posts_screen",'wp_ulike_logs_per_page');
		
		//Comment Like Logs Menu
		$comments_screen 	= add_submenu_page(null, __( 'Comment Likes Logs', 'alimir' ), __( 'Comment Likes Logs', 'alimir' ), 'manage_options','wp-ulike-comment-logs', 'wp_ulike_comment_likes_logs');
		add_action("load-$comments_screen",'wp_ulike_logs_per_page');
		
		//Activity Like Logs Menu
		$activities_screen 	= add_submenu_page(null, __( 'Activity Likes Logs', 'alimir' ), __( 'Activity Likes Logs', 'alimir' ), 'manage_options', 'wp-ulike-bp-logs', 'wp_ulike_buddypress_likes_logs');
		add_action("load-$activities_screen",'wp_ulike_logs_per_page');
		
		//Statistics Menu
		$statistics_screen 	= add_submenu_page('wp-ulike-settings', __( 'WP ULike Statistics', 'alimir' ), __( 'WP ULike Statistics', 'alimir' ), 'manage_options', 'wp-ulike-statistics', 'wp_ulike_statistics');
		add_action("load-$statistics_screen",'wp_ulike_statistics_register_option');
		
		//WP ULike About Menu
		add_submenu_page('wp-ulike-settings', __( 'About WP ULike', 'alimir' ), __( 'About WP ULike', 'alimir' ), 'manage_options', 'wp-ulike-about', 'wp_ulike_about_page');	
	}