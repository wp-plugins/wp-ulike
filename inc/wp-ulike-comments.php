<?php		
	//main comments function
	function wp_ulike_comments($arg) {
	
		global $wpdb,$user_ID;
		$CommentID = get_comment_ID();
		$counter = '';
		$get_like = get_comment_meta($CommentID, '_commentliked', true) != '' ? get_comment_meta($CommentID, '_commentliked', true) : '0';
		$liked = wp_ulike_format_number($get_like);
		
	   if ( (wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') != '1') or (wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') == '1' && is_user_logged_in()) ){
	   
	   if(is_user_logged_in()){
			$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND user_id = '$user_ID'");
			
			if(!isset($_COOKIE['comment-liked-'.$CommentID]) && $user_status == 0){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_comments_status($CommentID,$user_ID) == "like"){
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_comments_status($CommentID,$user_ID) == "unlike"){
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			
			else if(isset($_COOKIE['comment-liked-'.$CommentID]) && $user_status == 0){
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
	   }
	   else{
			if(!isset($_COOKIE['comment-liked-'.$CommentID])){
			
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}

			}
			else{
			
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				
			}
	   }	   
		
		$wp_ulike = '<div id="wp-ulike-comment-'.$CommentID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		$user_data = wp_ulike_get_user_comments_data($CommentID,$user_ID);
		if(wp_ulike_get_setting( 'wp_ulike_comments', 'users_liked_box') == '1' && $user_data != '')
		$wp_ulike .= '<p style="margin-top:5px">'.wp_ulike_get_setting( 'wp_ulike_comments', 'users_liked_box_title').'</p><ul class="tiles">' . $user_data . '</ul>';
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}
		
		else if (wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') == '1' && !is_user_logged_in()){
			return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this comment: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';
		}
		
	}

	//Process function
	function wp_ulike_comments_process(){
	
		global $wpdb,$user_ID;
		$CommentID = $_POST['id'];
		$like = get_comment_meta($CommentID, '_commentliked', true);
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND user_id = '$user_ID'");
		
		if($CommentID != '') {
			if (!isset($_COOKIE['comment-liked-'.$CommentID]) && $user_status == 0) {
				$newLike = $like + 1;
				update_comment_meta($CommentID, '_commentliked', $newLike);

				setcookie('comment-liked-'.$CommentID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()):
				$ip = wp_ulike_get_real_ip();
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_comments VALUES ('', '$CommentID', NOW(), '$ip', '$user_ID', 'like')");
				wp_ulike_bp_activity_add($user_ID,$CommentID,'comment');
				endif;

				echo wp_ulike_format_number($newLike);
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_comments_status($CommentID,$user_ID) == "like"){
					$newLike = $like - 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'unlike'
						WHERE comment_id = '$CommentID' AND user_id = '$user_ID'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $like + 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'like'
						WHERE comment_id = '$CommentID' AND user_id = '$user_ID'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else if (isset($_COOKIE['comment-liked-'.$CommentID])&& $user_status == 0){
					echo wp_ulike_format_number($like);
			}			
			
		}
		die();
	}