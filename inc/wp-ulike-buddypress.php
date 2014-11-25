<?php		
	//main activities function
	function wp_ulike_buddypress($arg) {
	
		global $wpdb,$user_ID;
		$activityID = bp_get_activity_id();
		$counter = '';
		$get_like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : '0';
		$liked = wp_ulike_format_number($get_like);
		
	   if ( (wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') != '1') or (wp_ulike_get_setting( 'wp_ulike_buddypress', 'only_registered_users') == '1' && is_user_logged_in()) ){
	   
	   if(is_user_logged_in()){
			$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND user_id = '$user_ID'");
			
			if(!isset($_COOKIE['activity-liked-'.$activityID]) && $user_status == 0){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_activities_status($activityID,$user_ID) == "like"){
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_activities_status($activityID,$user_ID) == "unlike"){
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			
			else if(isset($_COOKIE['activity-liked-'.$activityID]) && $user_status == 0){
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
	   }
	   else{
			if(!isset($_COOKIE['activity-liked-'.$activityID])){
			
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThisActivity('.$activityID.', 1, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}

			}
			else{
			
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				
			}
	   }	   
		
		$wp_ulike = '<div id="wp-ulike-activity-'.$activityID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		$user_data = wp_ulike_get_user_activities_data($activityID,$user_ID);
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

	//Process function
	function wp_ulike_buddypress_process(){
	
		global $wpdb,$user_ID;
		$activityID = $_POST['id'];
		$like = bp_activity_get_meta($activityID, '_activityliked') != '' ? bp_activity_get_meta($activityID, '_activityliked') : '0';
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND user_id = '$user_ID'");
		
		if($activityID != '') {
			if (!isset($_COOKIE['activity-liked-'.$activityID]) && $user_status == 0) {
				$newLike = $like + 1;
				bp_activity_update_meta($activityID, '_activityliked', $newLike);

				setcookie('activity-liked-'.$activityID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()):
				$ip = wp_ulike_get_real_ip();
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike_activities VALUES ('', '$activityID', NOW(), '$ip', '$user_ID', 'like')");
				//wp_ulike_bp_notification($user_ID,$activityID);
				endif;

				echo wp_ulike_format_number($newLike);
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_activities_status($activityID,$user_ID) == "like"){
					$newLike = $like - 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'unlike'
						WHERE activity_id = '$activityID' AND user_id = '$user_ID'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $like + 1;
					bp_activity_update_meta($activityID, '_activityliked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike_activities
						SET status = 'like'
						WHERE activity_id = '$activityID' AND user_id = '$user_ID'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else if (isset($_COOKIE['activity-liked-'.$activityID])&& $user_status == 0){
					echo wp_ulike_format_number($like);
			}			
			
		}
		die();
	}