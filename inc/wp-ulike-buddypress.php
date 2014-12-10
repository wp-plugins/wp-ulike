<?php		
	//main activities function
	function wp_ulike_buddypress($arg) {
		if(
		(wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') != '1')
		or
		(wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') == '1' && is_user_logged_in())
		){
		
		global $user_ID;
		$activityID = bp_get_activity_id();
		$counter = '';
		$return_userID = wp_ulike_reutrn_userID($user_ID);
		$loggin_method = wp_ulike_get_setting( 'wp_ulike_buddypress', 'logging_method');
		
		if($loggin_method == 'do_not_log')
		$counter = wp_ulike_activities_do_not_log_method($activityID,'activity');
		else if($loggin_method == 'by_cookie')
		$counter = wp_ulike_activities_loggedby_cookie_method($activityID,'activity');
		else if($loggin_method == 'by_ip')
		$counter = wp_ulike_activities_loggedby_ip_method($activityID,$return_userID,'activity');
		else if($loggin_method == 'by_cookie_ip')
		$counter = wp_ulike_activities_loggedby_cookie_ip_method($activityID,$return_userID,'activity');
		else
		$counter = wp_ulike_activities_loggedby_username_method($activityID,$return_userID,'activity');	
		
		$wp_ulike = '<div id="wp-ulike-activity-'.$activityID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		$user_data = wp_ulike_get_user_activities_data($activityID,$return_userID);
		if(wp_ulike_get_setting( 'wp_ulike_buddypress', 'users_liked_box') == '1' && $user_data != '')
		$wp_ulike .= '<p style="margin-top:5px">'.wp_ulike_get_setting( 'wp_ulike_buddypress', 'users_liked_box_title').'</p><ul class="tiles">' . $user_data . '</ul>';
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}
		
		else if (wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') == '1' && !is_user_logged_in()){
			return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this activity: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';
		}
		
	}
	
	//Do Not Log Method
	function wp_ulike_activities_do_not_log_method($activityID,$type){
	
		$get_like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : 0;
		$liked = wp_ulike_format_number($get_like);
		$counter = '';
		
		if($type == 'activity'){
			if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
				$counter = '<a onclick="likeThisActivity('.$activityID.', 2, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
			}
			else {
				$counter = '<a onclick="likeThisActivity('.$activityID.', 2, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
			}
		}//end activity button
		else if($type == 'process'){
			$newLike = $get_like + 1;
			bp_activity_update_meta($activityID, '_activityliked', $newLike);
			echo wp_ulike_format_number($newLike);
		}//end activity process
		
		return $counter;
	}
	
	
	//Logged By Cookie
	function wp_ulike_activities_loggedby_cookie_method($activityID,$type){

		$get_like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : 0;
		$liked = wp_ulike_format_number($get_like);
		$counter = '';
		
		if($type == 'activity'){
			if(!isset($_COOKIE['activity-liked-'.$activityID])){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 2, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 2, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
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
		}//end activity button
		else if($type == 'process'){
			if(!isset($_COOKIE['activity-liked-'.$activityID])){
				$newLike = $get_like + 1;
				bp_activity_update_meta($activityID, '_activityliked', $newLike);
				setcookie('activity-liked-'.$activityID, time(), time()+3600*24*365, '/');
				echo wp_ulike_format_number($newLike);
			}
			else{
				echo wp_ulike_format_number($get_like);
			}
		}//end activity process
		
		return $counter;
	}
	
	//Logged By IP
	function wp_ulike_activities_loggedby_ip_method($activityID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
	
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND ip = '$user_IP'");
		
		if($type == 'activity'){
			if($user_status == 0){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else{
				if(wp_ulike_get_user_activities_status_byIP($activityID,$user_IP) == "like"){
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_activities_status_byIP($activityID,$user_IP) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
							$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
							$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
						}
					}
					else{
						$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
		}//end activity button
		else if($type == 'process'){
			if ($user_status == 0) {
				$newLike = $get_like + 1;
				bp_activity_update_meta($activityID, '_activityliked', $newLike);
				//insert new logs
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_activities VALUES ('', '$activityID', NOW(), '$user_IP', '$return_userID', 'like')");
				
				echo wp_ulike_format_number($newLike);
			}
			else {
				if(wp_ulike_get_user_activities_status_byIP($activityID,$user_IP) == "like"){
					$newLike = $get_like - 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);

					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'unlike'
						WHERE activity_id = '$activityID' AND ip = '$user_IP'
					");					
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'like'
						WHERE activity_id = '$activityID' AND ip = '$user_IP'
					");						
					
					echo wp_ulike_format_number($newLike);
				}
			}
		}//end activity process

		return $counter;	
	}
	
	//Logged by cookie and IP
	function wp_ulike_activities_loggedby_cookie_ip_method($activityID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
	
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND ip = '$user_IP'");
		
		if($type == 'activity'){
			if($user_status == 0 && !isset($_COOKIE['activity-liked-'.$activityID])){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_activities_status_byIP($activityID,$user_IP) == "like"){
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_activities_status_byIP($activityID,$user_IP) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
							$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
							$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
						}
					}
					else{
						$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
			else{
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
		}	
		else if($type == 'process'){
			if ($user_status == 0 && !isset($_COOKIE['activity-liked-'.$activityID])) {
				$newLike = $get_like + 1;
				bp_activity_update_meta($activityID, '_activityliked', $newLike);
				setcookie('activity-liked-'.$activityID, time(), time()+3600*24*365, '/');
				
				//insert new logs
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_activities VALUES ('', '$activityID', NOW(), '$user_IP', '$return_userID', 'like')");
				
				echo wp_ulike_format_number($newLike);
				
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_activities_status_byIP($activityID,$user_IP) == "like"){
					$newLike = $get_like - 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);

					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'unlike'
						WHERE activity_id = '$activityID' AND ip = '$user_IP'
					");					
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'like'
						WHERE activity_id = '$activityID' AND ip = '$user_IP'
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
	function wp_ulike_activities_loggedby_username_method($activityID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND user_id = '$return_userID'");	
				
		if($type == 'activity'){
			if($user_status == 0 && !isset($_COOKIE['activity-liked-'.$activityID])){
				if(is_user_logged_in()): $num = 0; else: $num = 2; endif;
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, '.$num.');" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, '.$num.');" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_activities_status($activityID,$return_userID) == "like"){
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_activities_status($activityID,$return_userID) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
						$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
						$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
						}
					}
					else{
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
			else if($user_status == 0 && isset($_COOKIE['activity-liked-'.$activityID])){
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
		}
		
		if($type == 'process'){
			if ($user_status == 0 && !isset($_COOKIE['activity-liked-'.$activityID])) {
				$newLike = $get_like + 1;
				bp_activity_update_meta($activityID, '_activityliked', $newLike);
				setcookie('activity-liked-'.$activityID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()){
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_activities VALUES ('', '$activityID', NOW(), '$user_IP', '$return_userID', 'like')");
				}

				echo wp_ulike_format_number($newLike);
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_activities_status($activityID,$return_userID) == "like"){
					$newLike = $get_like - 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'unlike'
						WHERE activity_id = '$activityID' AND user_id = '$return_userID'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'like'
						WHERE activity_id = '$activityID' AND user_id = '$return_userID'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else if($user_status == 0 && isset($_COOKIE['activity-liked-'.$activityID])){
				echo wp_ulike_format_number($get_like);
			}
		}
		
		return $counter;
	}
	
	//Process function
	function wp_ulike_buddypress_process(){
	
		global $wpdb,$user_ID;
		$activityID = $_POST['id'];
		$return_userID = wp_ulike_reutrn_userID($user_ID);
		
		if($activityID != '') {

			$loggin_method = wp_ulike_get_setting( 'wp_ulike_buddypress', 'logging_method');
			if($loggin_method == 'do_not_log')
			$counter = wp_ulike_activities_do_not_log_method($activityID,'process');
			else if($loggin_method == 'by_cookie')
			$counter = wp_ulike_activities_loggedby_cookie_method($activityID,'process');
			else if($loggin_method == 'by_ip')
			$counter = wp_ulike_activities_loggedby_ip_method($activityID,$return_userID,'process');
			else if($loggin_method == 'by_cookie_ip')
			$counter = wp_ulike_activities_loggedby_cookie_ip_method($activityID,$return_userID,'process');
			else
			$counter = wp_ulike_activities_loggedby_username_method($activityID,$return_userID,'process');	
			
		}
		die();
	}