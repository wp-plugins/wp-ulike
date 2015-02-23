<?php
	
	/**
	 * wp_ulike function for posts like/unlike display
	 *
	 * @author       	Alimir	 	
	 * @since           1.0
	 * @updated         2.0
	 * @return			String
	 */
	function wp_ulike($arg) {
		//global variables
		global $post,$wp_ulike_class,$wp_user_IP;
		
		$post_ID 		= $post->ID;
		$get_post_meta 	= get_post_meta($post_ID, '_liked', true);
		$get_like 		= $get_post_meta != '' ? $get_post_meta : 0;
		$return_userID 	= $wp_ulike_class->get_reutrn_id();
		
		if(
			(wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') != '1')
		or
			(wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') == '1' && is_user_logged_in())
		){
		
		$data = array(
			"id" 		=> $post_ID,				//Post ID
			"user_id" 	=> $return_userID,			//User ID (if the user is guest, we save ip as user_id with "ip2long" function)
			"user_ip" 	=> $wp_user_IP,				//User IP
			"get_like" 	=> $get_like,				//Number Of Likes
			"method" 	=> 'likeThis',				//JavaScript method
			"setting" 	=> 'wp_ulike_posts',		//Setting Key
			"type" 		=> 'post',					//Function type (post/process)
			"table" 	=> 'ulike',					//posts table
			"column" 	=> 'post_id',				//ulike table column name			
			"key" 		=> '_liked',				//meta key
			"cookie" 	=> 'liked-'					//Cookie Name
		);		
		
		//call wp_get_ulike function from class-ulike calss
		$counter 		= $wp_ulike_class->wp_get_ulike($data);
		
		$wp_ulike 		= '<div id="wp-ulike-'.$post_ID.'" class="wpulike">';
		$wp_ulike  		.= '<div class="counter">'.$counter.'</div>';
		$wp_ulike  		.= '</div>';
		$wp_ulike  		.= $wp_ulike_class->get_liked_users($post_ID,'ulike','post_id','wp_ulike_posts');
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}//end !only_registered_users condition
		
		else if (wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') == '1' && !is_user_logged_in()){
			$login_type = wp_ulike_get_setting( 'wp_ulike_general', 'login_type');
			if($login_type == "button"){
				$template = $wp_ulike_class->get_template($post_ID,'likeThis',$get_like,1,0);
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					return '<div id="wp-ulike-'.$post_ID.'" class="wpulike"><div class="counter">' . $template['login_img'] . '</div></div>';		
				}
				else {
					return '<div id="wp-ulike-'.$post_ID.'" class="wpulike"><div class="counter">' . $template['login_text'] . '</div></div>';	
				}
			}
			else
				return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this post: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';
		}//end only_registered_users condition
		
	}

	/**
	 * wp_ulike_process function for posts like/unlike process
	 *
	 * @author       	Alimir	 	
	 * @since           1.0
	 * @updated         2.0
	 * @return			String
	 */
	function wp_ulike_process(){
	
		global $wp_ulike_class,$wp_user_IP;
		$post_ID 		= $_POST['id'];
		$get_post_meta 	= get_post_meta($post_ID, '_liked', true);
		$get_like 		= $get_post_meta != '' ? $get_post_meta : 0;
		$return_userID 	= $wp_ulike_class->get_reutrn_id();
		
		$data = array(
			"id" 		=> $post_ID,				//Post ID
			"user_id" 	=> $return_userID,			//User ID (if the user is guest, we save ip as user_id with "ip2long" function)
			"user_ip" 	=> $wp_user_IP,				//User IP
			"get_like" 	=> $get_like,				//Number Of Likes
			"method" 	=> 'likeThis',				//JavaScript method
			"setting" 	=> 'wp_ulike_posts',		//Setting Key
			"type" 		=> 'process',				//Function type (post/process)
			"table" 	=> 'ulike',					//posts table
			"column" 	=> 'post_id',				//ulike table column name			
			"key" 		=> '_liked',				//meta key
			"cookie" 	=> 'liked-'					//Cookie Name
		);			
		
		if($post_ID != '') {
			//call wp_get_ulike function from class-ulike calss
			echo $wp_ulike_class->wp_get_ulike($data);
		}
		die();
	}