<?php		
	//main comments function
	function wp_ulike_comments($arg) {
		if(
		(wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') != '1')
		or
		(wp_ulike_get_setting( 'wp_ulike_comments', 'only_registered_users') == '1' && is_user_logged_in())
		){
		
		global $user_ID;
		$CommentID = get_comment_ID();
		$counter = '';
		$return_userID = wp_ulike_reutrn_userID($user_ID);
		$loggin_method = wp_ulike_get_setting( 'wp_ulike_comments', 'logging_method');
		
		if($loggin_method == 'do_not_log')
		$counter = wp_ulike_comments_do_not_log_method($CommentID,'comment');
		else if($loggin_method == 'by_cookie')
		$counter = wp_ulike_comments_loggedby_cookie_method($CommentID,'comment');
		else if($loggin_method == 'by_ip')
		$counter = wp_ulike_comments_loggedby_ip_method($CommentID,$return_userID,'comment');
		else if($loggin_method == 'by_cookie_ip')
		$counter = wp_ulike_comments_loggedby_cookie_ip_method($CommentID,$return_userID,'comment');
		else
		$counter = wp_ulike_comments_loggedby_username_method($CommentID,$return_userID,'comment');	
		
		$wp_ulike = '<div id="wp-ulike-comment-'.$CommentID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		$user_data = wp_ulike_get_user_comments_data($CommentID,$return_userID);
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
	
	//Do Not Log Method
	function wp_ulike_comments_do_not_log_method($CommentID,$type){
	
		$get_like = get_comment_meta($CommentID, '_commentliked', true) != '' ? get_comment_meta($CommentID, '_commentliked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$counter = '';
		
		if($type == 'comment'){
			if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
				$counter = '<a onclick="likeThisComment('.$CommentID.', 2, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
			}
			else {
				$counter = '<a onclick="likeThisComment('.$CommentID.', 2, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
			}
		}//end comment button
		else if($type == 'process'){
			$newLike = $get_like + 1;
			update_comment_meta($CommentID, '_commentliked', $newLike);
			echo wp_ulike_format_number($newLike);
		}//end comment process
		
		return $counter;
	}
	
	
	//Logged By Cookie
	function wp_ulike_comments_loggedby_cookie_method($CommentID,$type){

		$get_like = get_comment_meta($CommentID, '_commentliked', true) != '' ? get_comment_meta($CommentID, '_commentliked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$counter = '';
		
		if($type == 'comment'){
			if(!isset($_COOKIE['comment-liked-'.$CommentID])){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 2, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 2, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else{
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'" class="image user-tooltip"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'" class="text user-tooltip">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
		}//end comment button
		else if($type == 'process'){
			if(!isset($_COOKIE['comment-liked-'.$CommentID])){
				$newLike = $get_like + 1;
				update_comment_meta($CommentID, '_commentliked', $newLike);
				setcookie('comment-liked-'.$CommentID, time(), time()+3600*24*365, '/');
				echo wp_ulike_format_number($newLike);
			}
			else{
				echo wp_ulike_format_number($get_like);
			}
		}//end comment process
		
		return $counter;
	}
	
	//Logged By IP
	function wp_ulike_comments_loggedby_ip_method($CommentID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = get_comment_meta($CommentID, '_commentliked', true) != '' ? get_comment_meta($CommentID, '_commentliked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND ip = '$user_IP'");
		
		if($type == 'comment'){
			if($user_status == 0){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else{
				if(wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP) == "like"){
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
							$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
							$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
						}
					}
					else{
						$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
		}//end comment button
		else if($type == 'process'){
			if ($user_status == 0) {
				$newLike = $get_like + 1;
				update_comment_meta($CommentID, '_commentliked', $newLike);
				//insert new logs
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_comments VALUES ('', '$CommentID', NOW(), '$user_IP', '$return_userID', 'like')");
				
				if(is_user_logged_in()){
					wp_ulike_bp_activity_add($return_userID,$CommentID,'comment');
				}
				
				echo wp_ulike_format_number($newLike);
			}
			else {
				if(wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP) == "like"){
					$newLike = $get_like - 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);

					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'unlike'
						WHERE comment_id = '$CommentID' AND ip = '$user_IP'
					");					
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'like'
						WHERE comment_id = '$CommentID' AND ip = '$user_IP'
					");						
					
					echo wp_ulike_format_number($newLike);
				}
			}
		}//end comment process

		return $counter;	
	}
	
	//Logged by cookie and IP
	function wp_ulike_comments_loggedby_cookie_ip_method($CommentID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = get_comment_meta($CommentID, '_commentliked', true) != '' ? get_comment_meta($CommentID, '_commentliked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND ip = '$user_IP'");
		
		if($type == 'comment'){
			if($user_status == 0 && !isset($_COOKIE['comment-liked-'.$CommentID])){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0 && isset($_COOKIE['comment-liked-'.$CommentID])){
				if(wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP) == "like"){
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
							$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
							$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
						}
					}
					else{
						$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
			else{
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
		}	
		else if($type == 'process'){
			if ($user_status == 0 && !isset($_COOKIE['comment-liked-'.$CommentID])) {
				$newLike = $get_like + 1;
				update_comment_meta($CommentID, '_commentliked', $newLike);
				setcookie('comment-liked-'.$CommentID, time(), time()+3600*24*365, '/');
				
				//insert new logs
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_comments VALUES ('', '$CommentID', NOW(), '$user_IP', '$return_userID', 'like')");
				
				if(is_user_logged_in()){
					wp_ulike_bp_activity_add($return_userID,$CommentID,'comment');
				}
				
				echo wp_ulike_format_number($newLike);
				
			}
			else if ($user_status != 0  && isset($_COOKIE['comment-liked-'.$CommentID])) {
				if(wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP) == "like"){
					$newLike = $get_like - 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);

					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'unlike'
						WHERE comment_id = '$CommentID' AND ip = '$user_IP'
					");					
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'like'
						WHERE comment_id = '$CommentID' AND ip = '$user_IP'
					");						
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else{
				echo wp_ulike_format_number($get_like);
			}
		}

		return $counter;	
	}
	
	//Logged by username
	function wp_ulike_comments_loggedby_username_method($CommentID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = get_comment_meta($CommentID, '_commentliked', true) != '' ? get_comment_meta($CommentID, '_commentliked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND user_id = '$return_userID'");		
				
		if($type == 'comment'){
			if($user_status == 0 && !isset($_COOKIE['comment-liked-'.$CommentID])){
				if(is_user_logged_in()): $num = 0; else: $num = 2; endif;
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, '.$num.');" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, '.$num.');" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_comments_status($CommentID,$return_userID) == "like"){
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_comments_status($CommentID,$return_userID) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
						$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
						$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
						}
					}
					else{
					$counter = '<a onclick="likeThisComment('.$CommentID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
			else if($user_status == 0 && isset($_COOKIE['comment-liked-'.$CommentID])){
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
		}
		
		if($type == 'process'){
			if ($user_status == 0 && !isset($_COOKIE['comment-liked-'.$CommentID])) {
				$newLike = $get_like + 1;
				update_comment_meta($CommentID, '_commentliked', $newLike);
				setcookie('comment-liked-'.$CommentID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()){
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_comments VALUES ('', '$CommentID', NOW(), '$user_IP', '$return_userID', 'like')");
				wp_ulike_bp_activity_add($return_userID,$CommentID,'comment');
				}

				echo wp_ulike_format_number($newLike);
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_comments_status($CommentID,$return_userID) == "like"){
					$newLike = $get_like - 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'unlike'
						WHERE comment_id = '$CommentID' AND user_id = '$return_userID'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					update_comment_meta($CommentID, '_commentliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_comments
						SET status = 'like'
						WHERE comment_id = '$CommentID' AND user_id = '$return_userID'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else if($user_status == 0 && isset($_COOKIE['comment-liked-'.$CommentID])){
				echo wp_ulike_format_number($get_like);
			}
		}
		
		return $counter;
	}

	//Process function
	function wp_ulike_comments_process(){
	
		global $wpdb,$user_ID;
		$CommentID = $_POST['id'];
		$return_userID = wp_ulike_reutrn_userID($user_ID);
		
		if($CommentID != '') {

			$loggin_method = wp_ulike_get_setting( 'wp_ulike_comments', 'logging_method');
			if($loggin_method == 'do_not_log')
			$counter = wp_ulike_comments_do_not_log_method($CommentID,'process');
			else if($loggin_method == 'by_cookie')
			$counter = wp_ulike_comments_loggedby_cookie_method($CommentID,'process');
			else if($loggin_method == 'by_ip')
			$counter = wp_ulike_comments_loggedby_ip_method($CommentID,$return_userID,'process');
			else if($loggin_method == 'by_cookie_ip')
			$counter = wp_ulike_comments_loggedby_cookie_ip_method($CommentID,$return_userID,'process');
			else
			$counter = wp_ulike_comments_loggedby_username_method($CommentID,$return_userID,'process');		
			
		}
		die();
	}