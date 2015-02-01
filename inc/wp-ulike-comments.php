<?php		

	/**
	 * wp_ulike_comments function for comments like/unlike display
	 *
	 * @author       	Alimir	 	
	 * @since           1.6
	 * @updated         2.0
	 * @return			String
	 */
	function wp_ulike_comments($arg) {
		//global variables
		global $wp_ulike_class,$wp_user_IP;
		
		$CommentID 		= get_comment_ID();
		$comment_meta 	= get_comment_meta($CommentID, '_commentliked', true);
		$get_like 		= $comment_meta != '' ? $comment_meta : 0;
		$return_userID 	= $wp_ulike_class->get_reutrn_id();	
		
		if(
		(wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') != '1')
		or
		(wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') == '1' && is_user_logged_in())
		){
		
		$data = array(
			"id" 		=> $CommentID,				//Comment ID
			"user_id" 	=> $return_userID,			//User ID (if the user is guest, we save ip as user_id with "ip2long" function)
			"user_ip" 	=> $wp_user_IP,				//User IP
			"get_like" 	=> $get_like,				//Number Of Likes
			"method" 	=> 'likeThisComment',		//JavaScript method
			"setting" 	=> 'wp_ulike_comments',		//Setting Key
			"type" 		=> 'post',					//Function type (post/process)
			"table" 	=> 'ulike_comments',		//Comments table
			"column"	=> 'comment_id',			//ulike_comments table column name			
			"key" 		=> '_commentliked',			//meta key
			"cookie" 	=> 'comment-liked-'			//Cookie Name
		);		
		
		//call wp_get_ulike function from class-ulike calss
		$counter 		= $wp_ulike_class->wp_get_ulike($data);		
		
		$wp_ulike 		= '<div id="wp-ulike-comment-'.$CommentID.'" class="wpulike">';
		$wp_ulike 		.= '<div class="counter">'.$counter.'</div>';
		$wp_ulike 		.= '</div>';
		$wp_ulike  		.= $wp_ulike_class->get_liked_users($CommentID,'ulike_comments','comment_id','wp_ulike_comments');
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}//end !only_registered_users condition
		
		else if (wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') == '1' && !is_user_logged_in()){
			$login_type = wp_ulike_get_setting( 'wp_ulike_general', 'login_type');
			if($login_type == "button"){
				$template = $wp_ulike_class->get_template($CommentID,'likeThisComment',$get_like,1,0);
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					return '<div id="wp-ulike-comment-'.$CommentID.'" class="wpulike"><div class="counter">' . $template['login_img'] . '</div></div>';		
				}
				else {
					return '<div id="wp-ulike-comment-'.$CommentID.'" class="wpulike"><div class="counter">' . $template['login_text'] . '</div></div>';	
				}
			}
			else
				return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this comment: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';	
		}//end only_registered_users condition
		
	}
	
	/**
	 * wp_ulike_comments_process function for comments like/unlike process
	 *
	 * @author       	Alimir	 	
	 * @since           1.6
	 * @updated         2.0
	 * @return			String
	 */
	function wp_ulike_comments_process(){
	
		global $wp_ulike_class,$wp_user_IP;
		$CommentID 		= $_POST['id'];
		$comment_meta 	= get_comment_meta($CommentID, '_commentliked', true);
		$get_like 		= $comment_meta != '' ? $comment_meta : 0;
		$return_userID 	= $wp_ulike_class->get_reutrn_id();

		$data = array(
			"id" 		=> $CommentID,				//Comment ID
			"user_id" 	=> $return_userID,			//User ID (if the user is guest, we save ip as user_id with "ip2long" function)
			"user_ip" 	=> $wp_user_IP,				//User IP
			"get_like" 	=> $get_like,				//Number Of Likes
			"method" 	=> 'likeThisComment',		//JavaScript method
			"setting" 	=> 'wp_ulike_comments',		//Setting Key
			"type" 		=> 'process',				//Function type (post/process)
			"table" 	=> 'ulike_comments',		//Comments table
			"column"	=> 'comment_id',			//ulike_comments table column name			
			"key" 		=> '_commentliked',			//meta key
			"cookie" 	=> 'comment-liked-'			//Cookie Name
		);		
		
		if($CommentID != '') {
			//call wp_get_ulike function from class-ulike calss
			echo $wp_ulike_class->wp_get_ulike($data);
		}
		die();
	}