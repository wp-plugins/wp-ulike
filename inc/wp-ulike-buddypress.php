<?php

	/**
	 * wp_ulike_buddypress function for activities like/unlike display
	 *
	 * @author       	Alimir	 	
	 * @since           1.7
	 * @updated         2.0
	 * @return			String
	 */
	function wp_ulike_buddypress($arg) {
		//global variables
		global $wp_ulike_class,$wp_user_IP;
		
		$activityID 	= bp_get_activity_id();
		$bp_get_meta	= bp_activity_get_meta($activityID, '_activityliked');
		$get_like 		= $bp_get_meta != '' ? $bp_get_meta : 0;
		$return_userID 	= $wp_ulike_class->get_reutrn_id();	
	
		if(
		(wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') != '1')
		or
		(wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') == '1' && is_user_logged_in())
		){
		
		$data = array(
			"id" 		=> $activityID,				//Activity ID
			"user_id" 	=> $return_userID,			//User ID (if the user is guest, we save ip as user_id with "ip2long" function)
			"user_ip" 	=> $wp_user_IP,				//User IP
			"get_like" 	=> $get_like,				//Number Of Likes
			"method" 	=> 'likeThisActivity',		//JavaScript method
			"setting" 	=> 'wp_ulike_buddypress',	//Setting Key
			"type" 		=> 'post',					//Function type (post/process)
			"table" 	=> 'ulike_activities',		//Activities table
			"column"	=> 'activity_id',			//ulike_activities table column name			
			"key" 		=> '_activityliked',		//meta key
			"cookie" 	=> 'activity-liked-'		//Cookie Name
		);	
	
		//call wp_get_ulike function from class-ulike calss
		$counter 		= $wp_ulike_class->wp_get_ulike($data);
		
		if (wp_ulike_get_setting( 'wp_ulike_buddypress', 'auto_display_position' ) == 'meta')
		$html_tag = 'span';
		else
		$html_tag = 'div';
		
		$wp_ulike 		= '<'.$html_tag.' id="wp-ulike-activity-'.$activityID.'" class="wpulike">';
		$wp_ulike 		.= '<'.$html_tag.' class="counter">'.$counter.'</'.$html_tag.'>';
		$wp_ulike 		.= '</'.$html_tag.'>';
		$wp_ulike  		.= $wp_ulike_class->get_liked_users($activityID,'ulike_activities','activity_id','wp_ulike_buddypress');
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}//end !only_registered_users condition
		
		else if (wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') == '1' && !is_user_logged_in()){
			$login_type = wp_ulike_get_setting( 'wp_ulike_general', 'login_type');
			if($login_type == "button"){
				$template = $wp_ulike_class->get_template($activityID,'likeThisActivity',$get_like,1,0);
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					return '<'.$html_tag.' id="wp-ulike-activity-'.$activityID.'" class="wpulike"><'.$html_tag.' class="counter">' . $template['login_img'] . '</'.$html_tag.'></'.$html_tag.'>';		
				}
				else {
					return '<'.$html_tag.' id="wp-ulike-activity-'.$activityID.'" class="wpulike"><'.$html_tag.' class="counter">' . $template['login_text'] . '</'.$html_tag.'></'.$html_tag.'>';	
				}
			}
			else		
				return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this activity: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';
		}//end only_registered_users condition
		
	}

	/**
	 * wp_ulike_buddypress_process function for activities like/unlike process
	 *
	 * @author       	Alimir	 	
	 * @since           1.7
	 * @updated         2.0
	 * @return			String
	 */
	function wp_ulike_buddypress_process(){
	
		global $wp_ulike_class,$wp_user_IP;
		$activityID 	= $_POST['id'];
		$bp_get_meta	= bp_activity_get_meta($activityID, '_activityliked');
		$get_like 		= $bp_get_meta != '' ? $bp_get_meta : 0;
		$return_userID 	= $wp_ulike_class->get_reutrn_id();
		
		$data = array(
			"id" 		=> $activityID,				//Activity ID
			"user_id" 	=> $return_userID,			//User ID (if the user is guest, we save ip as user_id with "ip2long" function)
			"user_ip" 	=> $wp_user_IP,				//User IP
			"get_like" 	=> $get_like,				//Number Of Likes
			"method" 	=> 'likeThisActivity',		//JavaScript method
			"setting" 	=> 'wp_ulike_buddypress',	//Setting Key
			"type" 		=> 'process',				//Function type (post/process)
			"table" 	=> 'ulike_activities',		//Activities table
			"column"	=> 'activity_id',			//ulike_activities table column name			
			"key" 		=> '_activityliked',		//meta key
			"cookie" 	=> 'activity-liked-'		//Cookie Name
		);		
		
		if($activityID != '') {
			//call wp_get_ulike function from class-ulike calss
			echo $wp_ulike_class->wp_get_ulike($data);
		}
		die();
	}