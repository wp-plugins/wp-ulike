<?php		
	//main function
	function wp_ulike($arg) {
		if(
		(wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') != '1')
		or
		(wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') == '1' && is_user_logged_in())
		){
		
		global $post,$user_ID;
		$post_ID = $post->ID;		
		$return_userID = wp_ulike_reutrn_userID($user_ID);
		$loggin_method = wp_ulike_get_setting( 'wp_ulike_posts', 'logging_method');
	   
		if($loggin_method == 'do_not_log')
		$counter = wp_ulike_posts_do_not_log_method($post_ID,'post');
		else if($loggin_method == 'by_cookie')
		$counter = wp_ulike_posts_loggedby_cookie_method($post_ID,'post');
		else if($loggin_method == 'by_ip')
		$counter = wp_ulike_posts_loggedby_ip_method($post_ID,$return_userID,'post');
		else if($loggin_method == 'by_cookie_ip')
		$counter = wp_ulike_posts_loggedby_cookie_ip_method($post_ID,$return_userID,'post');
		else
		$counter = wp_ulike_posts_loggedby_username_method($post_ID,$return_userID,'post');
		
		
		$wp_ulike = '<div id="wp-ulike-'.$post_ID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		
		$user_data = wp_ulike_get_user_posts_data($post_ID,$return_userID);
		if(wp_ulike_get_setting( 'wp_ulike_posts', 'users_liked_box') == '1' && $user_data != '')
		$wp_ulike .= '<br /><p style="margin-top:5px">'.wp_ulike_get_setting( 'wp_ulike_posts', 'users_liked_box_title').'</p><ul class="tiles">' . $user_data . '</ul>';				
		
		if ($arg == 'put') {
			return $wp_ulike;
		}
		else {
			echo $wp_ulike;
		}
		
		}
		
		else if (wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') == '1' && !is_user_logged_in()){
			return '<p class="alert alert-info fade in" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'.__('You need to login in order to like this post: ','alimir').'<a href="'.wp_login_url( get_permalink() ).'"> '.__('click here','alimir').' </a></p>';
		}
		
	}
	
	
	//Do Not Log Method
	function wp_ulike_posts_do_not_log_method($post_ID,$type){
	
		$get_like = get_post_meta($post_ID, '_liked', true) != '' ? get_post_meta($post_ID, '_liked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$counter = '';
		
		if($type == 'post'){
			if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
				$counter = '<a onclick="likeThis('.$post_ID.', 2, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';
			}
			else {
				$counter = '<a onclick="likeThis('.$post_ID.', 2, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
			}
		}//end post button
		else if($type == 'process'){
			$newLike = $get_like + 1;
			update_post_meta($post_ID, '_liked', $newLike);
			echo wp_ulike_format_number($newLike);
		}//end post process
		
		return $counter;
	}
	
	
	//Logged By Cookie
	function wp_ulike_posts_loggedby_cookie_method($post_ID,$type){

		$get_like = get_post_meta($post_ID, '_liked', true) != '' ? get_post_meta($post_ID, '_liked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$counter = '';
		
		if($type == 'post'){
			if(!isset($_COOKIE['liked-'.$post_ID])){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 2, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 2, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
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
		}//end post button
		else if($type == 'process'){
			if(!isset($_COOKIE['liked-'.$post_ID])){
				$newLike = $get_like + 1;
				update_post_meta($post_ID, '_liked', $newLike);
				setcookie('liked-'.$post_ID, time(), time()+3600*24*365, '/');
				echo wp_ulike_format_number($newLike);
			}
			else{
				echo wp_ulike_format_number($get_like);
			}
		}//end post process
		
		return $counter;
	}
	
	//Logged By IP
	function wp_ulike_posts_loggedby_ip_method($post_ID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = get_post_meta($post_ID, '_liked', true) != '' ? get_post_meta($post_ID, '_liked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND ip = '$user_IP'");
		
		if($type == 'post'){
			if($user_status == 0){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			else{
				if(wp_ulike_get_user_post_status_byIP($post_ID,$user_IP) == "like"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_post_status_byIP($post_ID,$user_IP) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
							$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
							$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
						}
					}
					else{
						$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
		}//end post button
		else if($type == 'process'){
			if ($user_status == 0) {
				$newLike = $get_like + 1;
				update_post_meta($post_ID, '_liked', $newLike);
				//insert new logs
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike VALUES ('', '$post_ID', NOW(), '$user_IP', '$return_userID', 'like')");
				echo wp_ulike_format_number($newLike);
			}
			else {
				if(wp_ulike_get_user_post_status_byIP($post_ID,$user_IP) == "like"){
					$newLike = $get_like - 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'unlike'
						WHERE post_id = '$post_ID' AND ip = '$user_IP'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'like'
						WHERE post_id = '$post_ID' AND ip = '$user_IP'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
		}//end post process

		return $counter;	
	}
	
	//Logged by cookie and IP
	function wp_ulike_posts_loggedby_cookie_ip_method($post_ID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = get_post_meta($post_ID, '_liked', true) != '' ? get_post_meta($post_ID, '_liked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND ip = '$user_IP'");
		
		if($type == 'post'){
			if($user_status == 0 && !isset($_COOKIE['liked-'.$post_ID])){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			else if($user_status != 0 && isset($_COOKIE['liked-'.$post_ID])){
				if(wp_ulike_get_user_post_status_byIP($post_ID,$user_IP) == "like"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_post_status_byIP($post_ID,$user_IP) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
							$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
							$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
						}
					}
					else{
						$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
			else{
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
		}	
		else if($type == 'process'){
			if ($user_status == 0 && !isset($_COOKIE['liked-'.$post_ID])) {
			
				$newLike = $get_like + 1;
				update_post_meta($post_ID, '_liked', $newLike);
				setcookie('liked-'.$post_ID, time(), time()+3600*24*365, '/');
				
				//insert new logs
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike VALUES ('', '$post_ID', NOW(), '$user_IP', '$return_userID', 'like')");

				if(is_user_logged_in()){
					wp_ulike_bp_activity_add($return_userID,$post_ID,'post');
				}
				
				echo wp_ulike_format_number($newLike);
				
			}
			else if ($user_status != 0  && isset($_COOKIE['liked-'.$post_ID])) {
				if(wp_ulike_get_user_post_status_byIP($post_ID,$user_IP) == "like"){
					$newLike = $get_like - 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'unlike'
						WHERE post_id = '$post_ID' AND ip = '$user_IP'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'like'
						WHERE post_id = '$post_ID' AND ip = '$user_IP'
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
	function wp_ulike_posts_loggedby_username_method($post_ID,$return_userID,$type){
	
		global $wpdb;
		
		$get_like = get_post_meta($post_ID, '_liked', true) != '' ? get_post_meta($post_ID, '_liked', true) : 0;
		$liked = wp_ulike_format_number($get_like);
		$user_IP = wp_ulike_get_real_ip();
		$counter = '';
		
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$return_userID'");
				
		if($type == 'post'){
			if($user_status == 0 && !isset($_COOKIE['liked-'.$post_ID])){
				if(is_user_logged_in()): $num = 0; else: $num = 2; endif;
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, '.$num.');" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, '.$num.');" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_post_status($post_ID,$return_userID) == "like"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_post_status($post_ID,$return_userID) == "unlike"){
					if(wp_ulike_get_setting( 'wp_ulike_general', 'return_initial_after_unlike') == 1){
						if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
						$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
						}
						else {
						$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
						}
					}
					else{
						$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
					}
				}
			}
			else if($user_status == 0 && isset($_COOKIE['liked-'.$post_ID])){
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
		}
		
		if($type == 'process'){
			if ($user_status == 0 && !isset($_COOKIE['liked-'.$post_ID])) {
				$newLike = $get_like + 1;
				update_post_meta($post_ID, '_liked', $newLike);
				setcookie('liked-'.$post_ID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()){
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike VALUES ('', '$post_ID', NOW(), '$user_IP', '$return_userID', 'like')");
				wp_ulike_bp_activity_add($return_userID,$post_ID,'post');
				}

				echo wp_ulike_format_number($newLike);
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_post_status($post_ID,$return_userID) == "like"){
					$newLike = $get_like - 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'unlike'
						WHERE post_id = '$post_ID' AND user_id = '$return_userID'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $get_like + 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'like'
						WHERE post_id = '$post_ID' AND user_id = '$return_userID'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else if($user_status == 0 && isset($_COOKIE['liked-'.$post_ID])){
				echo wp_ulike_format_number($get_like);
			}
		}
		
		return $counter;
	}

	//Process function
	function wp_ulike_process(){
	
		global $wpdb,$user_ID;
		$post_ID = $_POST['id'];
		$return_userID = wp_ulike_reutrn_userID($user_ID);
		
		if($post_ID != '') {
		
			$loggin_method = wp_ulike_get_setting( 'wp_ulike_posts', 'logging_method');
			if($loggin_method == 'do_not_log')
			$counter = wp_ulike_posts_do_not_log_method($post_ID,'process');
			else if($loggin_method == 'by_cookie')
			$counter = wp_ulike_posts_loggedby_cookie_method($post_ID,'process');
			else if($loggin_method == 'by_ip')
			$counter = wp_ulike_posts_loggedby_ip_method($post_ID,$return_userID,'process');
			else if($loggin_method == 'by_cookie_ip')
			$counter = wp_ulike_posts_loggedby_cookie_ip_method($post_ID,$return_userID,'process');
			else
			$counter = wp_ulike_posts_loggedby_username_method($post_ID,$return_userID,'process');
			
		}
		die();
	}