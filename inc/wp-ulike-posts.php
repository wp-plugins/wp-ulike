<?php		
	//main function
	function wp_ulike($arg) {
	
		global $post,$wpdb,$user_ID;
		$post_ID = $post->ID;
		$counter = '';
		$get_like = get_post_meta($post_ID, '_liked', true) != '' ? get_post_meta($post_ID, '_liked', true) : '0';
		$liked = wp_ulike_format_number($get_like);
		
	   if ( (wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') != '1') or (wp_ulike_get_setting( 'wp_ulike_posts', 'only_registered_users') == '1' && is_user_logged_in()) ){
	   
	   
	   if(is_user_logged_in()){
			$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$user_ID'");
			
			if(!isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0){
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			else if($user_status != 0){
				if(wp_ulike_get_user_post_status($post_ID,$user_ID) == "like"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 1);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				}
				else if(wp_ulike_get_user_post_status($post_ID,$user_ID) == "unlike"){
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 0);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike').'</a><span class="count-box">'.$liked.'</span>';
				}
			}
			
			else if(isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0){
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
			}
	   }
	   else{
			if(!isset($_COOKIE['liked-'.$post_ID])){
			
				if (wp_ulike_get_setting( 'wp_ulike_general', 'button_type') == 'image') {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 2);" class="image"></a><span class="count-box">'.$liked.'</span>';		
				}
				else {
					$counter = '<a onclick="likeThis('.$post_ID.', 1, 2);" class="text">'.wp_ulike_get_setting( 'wp_ulike_general', 'button_text').'</a><span class="count-box">'.$liked.'</span>';			
				}

			}
			else{
			
				$counter = '<a class="text user-tooltip" title="'.wp_ulike_get_setting( 'wp_ulike_general', 'permission_text').'">'.wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like').'</a><span class="count-box">'.$liked.'</span>';
				
			}
	   }
		
		$wp_ulike = '<div id="wp-ulike-'.$post_ID.'" class="wpulike">';
		$wp_ulike .= '<div class="counter">'.$counter.'</div>';
		$wp_ulike .= '</div>';
		
		
		$user_data = wp_ulike_get_user_posts_data($post_ID,$user_ID);
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

	//Process function
	function wp_ulike_process(){
	
		global $wpdb,$user_ID;
		$post_ID = $_POST['id'];
		$like = get_post_meta($post_ID, '_liked', true);
		$user_status = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$user_ID'");
		
		if($post_ID != '') {
			if (!isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0) {
				$newLike = $like + 1;
				update_post_meta($post_ID, '_liked', $newLike);

				setcookie('liked-'.$post_ID, time(), time()+3600*24*365, '/');
				
				if(is_user_logged_in()):
				$ip = wp_ulike_get_real_ip();
				$wpdb->query("INSERT INTO ".$wpdb->prefix."ulike VALUES ('', '$post_ID', NOW(), '$ip', '$user_ID', 'like')");
				wp_ulike_bp_activity_add($user_ID,$post_ID,'post');
				endif;

				echo wp_ulike_format_number($newLike);
			}
			else if ($user_status != 0) {
				if(wp_ulike_get_user_post_status($post_ID,$user_ID) == "like"){
					$newLike = $like - 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'unlike'
						WHERE post_id = '$post_ID' AND user_id = '$user_ID'
					");
					
					echo wp_ulike_format_number($newLike);					
				}
				else{
					$newLike = $like + 1;
					update_post_meta($post_ID, '_liked', $newLike);
					
					$wpdb->query("
						UPDATE ".$wpdb->prefix."ulike
						SET status = 'like'
						WHERE post_id = '$post_ID' AND user_id = '$user_ID'
					");
					
					echo wp_ulike_format_number($newLike);
				}
			}
			else if (isset($_COOKIE['liked-'.$post_ID]) && $user_status == 0){
					echo wp_ulike_format_number($like);
			}
		}
		die();
	}