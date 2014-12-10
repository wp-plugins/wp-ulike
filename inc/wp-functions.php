<?php
	
/*******************************************************
  Posts Likes Functions
*******************************************************/

	//Shortcode function for the post likes
	add_shortcode( 'wp_ulike', 'wp_ulike_shortcode' );	
	function  wp_ulike_shortcode(){
		return wp_ulike('put');
	}
		
	//Insert ULike button to the posts
	if (wp_ulike_get_setting( 'wp_ulike_posts', 'auto_display' ) == '1') {
		function wp_ulike_put_posts($content) {
			//auto display position
			$position = wp_ulike_get_setting( 'wp_ulike_posts', 'auto_display_position');
			$button = '';
			
			//add wp_ulike function
			if(	!is_feed() && wp_ulike_post_auto_display_filters()){
				$button = wp_ulike('put');
			}
			
			//return by position
			if($position=='bottom')
			return $content . $button;
			else if($position=='top')
			return $button . $content;
			else if($position=='top_bottom')
			return $button . $content . $button;
			else
			return $content . $button;
		}

		add_filter('the_content', 'wp_ulike_put_posts');
	}
	
	//check the auto display filters
	function wp_ulike_post_auto_display_filters(){
		$filter = wp_ulike_get_setting( 'wp_ulike_posts', 'auto_display_filter');
		if(is_home() && $filter['home'] == '1')
		return 0;
		else if(is_single() && $filter['single'] == '1')
		return 0;
		else if(is_page() && $filter['page'] == '1')
		return 0;
		else if(is_archive() && $filter['archive'] == '1')
		return 0;
		else if(is_category() && $filter['category'] == '1')
		return 0;
		else if( is_search() && $filter['search'] == '1')
		return 0;
		else if(is_tag() && $filter['tag'] == '1')
		return 0;
		else if(is_author() && $filter['author'] == '1')
		return 0;
		else
		return 1;
	}
	
	//get user status by user_ID (like or unlike)
	function wp_ulike_get_user_post_status($post_ID,$user_ID){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$user_ID'");
		if ($like_status == "like")
		return "like";
		else
		return "unlike";
	}
	
	//get user status by user_IP (like or unlike)
	function wp_ulike_get_user_post_status_byIP($post_ID,$user_IP){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND ip = '$user_IP'");
		if ($like_status == "like")
		return "like";
		else
		return "unlike";
	}
	
	
	
	//get users data and list their avatar
	function wp_ulike_get_user_posts_data($post_ID,$user_ID){
		global $wpdb;
		$users_list = '';
		
		$get_users = $wpdb->get_results("SELECT user_id FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' GROUP BY user_id");
		
		foreach ( $get_users as $get_user ) 
		{
			$user_info = get_userdata($get_user->user_id);
			if ($user_ID != $get_user->user_id && $user_info):
			$avatar_size = wp_ulike_get_setting( 'wp_ulike_posts', 'users_liked_box_avatar_size');
			$users_list .= '<li><a class="user-tooltip" title="'.$user_info->display_name.'">'.get_avatar( $user_info->user_email, $avatar_size, '' , 'avatar').'</a></li>';
			endif;
		}
		
		return $users_list;
	}

	//get the post likes number
	function wp_ulike_get_post_likes($postID){
		$val = get_post_meta($postID, '_liked', true);
		return wp_ulike_format_number($val);
	}
	
	
	
/*******************************************************
  Comments Likes Functions
*******************************************************/	
	
	//Insert ULike button to the comments
	if (wp_ulike_get_setting( 'wp_ulike_comments', 'auto_display' ) == '1'  && !is_admin()) {
		function wp_ulike_put_comments($content) {
			//auto display position
			$position = wp_ulike_get_setting( 'wp_ulike_comments', 'auto_display_position');
			
			//add wp_ulike_comments function
			$button = wp_ulike_comments('put');
			
			//return by position
			if($position=='bottom')
			return $content . $button;
			else if($position=='top')
			return $button . $content;
			else if($position=='top_bottom')
			return $button . $content . $button;
			else
			return $content . $button;
		}
		
		add_filter('comment_text', 'wp_ulike_put_comments');
	}
	
	//get user comment status (like or unlike)
	function wp_ulike_get_user_comments_status($CommentID,$user_ID){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND user_id = '$user_ID'");
		if ($like_status == "like")
		return "like";
		else
		return "unlike";
	}
	
	//get user status by user_IP (like or unlike)
	function wp_ulike_get_user_comments_status_byIP($CommentID,$user_IP){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND ip = '$user_IP'");
		if ($like_status == "like")
		return "like";
		else
		return "unlike";
	}
	
	//get users data and list their avatar
	function wp_ulike_get_user_comments_data($CommentID,$user_ID){
		global $wpdb;
		$users_list = '';
		
		$get_users = $wpdb->get_results("SELECT user_id FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' GROUP BY user_id");
		
		foreach ( $get_users as $get_user ) 
		{
			$user_info = get_userdata($get_user->user_id);
			if ($user_ID != $get_user->user_id && $user_info):
			$avatar_size = wp_ulike_get_setting( 'wp_ulike_comments', 'users_liked_box_avatar_size');
			$users_list .= '<li><a class="user-tooltip" title="'.$user_info->display_name.'">'.get_avatar( $user_info->user_email, $avatar_size, '' , 'avatar').'</a></li>';
			endif;
		}
		
		return $users_list;
	}


	
/*******************************************************
  BuddyPress Likes Functions
*******************************************************/	
	
	//Insert ULike button to the activities
	if (wp_ulike_get_setting( 'wp_ulike_buddypress', 'auto_display' ) == '1' && !is_admin()) {
		function wp_ulike_put_buddypress($content) {
			$content.= wp_ulike_buddypress('put');
			return $content;
		}
		
		add_filter('bp_get_activity_action', 'wp_ulike_put_buddypress');
	}
	
	//get users data and list their avatar
	function wp_ulike_get_user_activities_data($activityID,$user_ID){
		global $wpdb;
		$users_list = '';
		
		$get_users = $wpdb->get_results("SELECT user_id FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' GROUP BY user_id");
		
		foreach ( $get_users as $get_user ) 
		{
			$user_info = get_userdata($get_user->user_id);
			if ($user_ID != $get_user->user_id && $user_info):
			$avatar_size = wp_ulike_get_setting( 'wp_ulike_buddypress', 'users_liked_box_avatar_size');
			$users_list .= '<li><a class="user-tooltip" title="'.$user_info->display_name.'">'.get_avatar( $user_info->user_email, $avatar_size, '' , 'avatar').'</a></li>';
			endif;
		}
		
		return $users_list;

	}
	
	//get user activity status (like or unlike)
	function wp_ulike_get_user_activities_status($activityID,$user_ID){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND user_id = '$user_ID'");
		if ($like_status == "like")
		return "like";
		else
		return "unlike";
	}
	
	//get user status by user_IP (like or unlike)
	function wp_ulike_get_user_activities_status_byIP($activityID,$user_IP){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND ip = '$user_IP'");
		if ($like_status == "like")
		return "like";
		else
		return "unlike";
	}	
	
	//register activity action
	add_action( 'bp_register_activity_actions', 'wp_ulike_register_activity_actions' );	
	function wp_ulike_register_activity_actions() {
		global $bp;
		bp_activity_set_action(
			$bp->activity->id,
			'wp_like_group',
			__( 'WP ULike Activity', 'alimir' )
		);
	}
	
	//add activity function
	function wp_ulike_bp_activity_add($user_ID,$cp_ID,$type){
		if (function_exists('bp_is_active') && wp_ulike_get_setting( 'wp_ulike_buddypress', 'new_likes_activity' ) == '1') {
			if($type=='comment'){
				bp_activity_add( array(
					'user_id' => $user_ID,
					'action' => '<strong>'.bp_core_get_userlink($user_ID).'</strong> '.__('liked','alimir').' <strong>'.get_comment_author($cp_ID).'</strong> '.__('comment','alimir').'. (So far, '.get_comment_author($cp_ID).' has <span class="badge">'. get_comment_meta($cp_ID, '_commentliked', true) .'</span> likes for this comment)',
					'component' => 'activity',
					'type' => 'wp_like_group',
					'item_id' => $cp_ID
				));
			}
			else if($type=='post'){
				$parent_title = get_the_title($cp_ID);
				bp_activity_add( array(
					'user_id' => $user_ID,
					'action' => '<strong>'.bp_core_get_userlink($user_ID).'</strong> '.__('liked','alimir').' <a href="'.get_permalink($cp_ID). '" title="'.$parent_title.'">'.$parent_title.'</a>. (So far, This post has <span class="badge">'.get_post_meta($cp_ID, '_liked', true).'</span> likes)',
					'component' => 'activity',
					'type' => 'wp_like_group',
					'item_id' => $cp_ID
				));
			}
		}
		else{
			return '';
		}
	}	

	
	
/*******************************************************
  General Functions
*******************************************************/

	//get real IP
	function wp_ulike_get_real_ip() {
		if (getenv('HTTP_CLIENT_IP')) {
			$ip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_X_FORWARDED')) {
			$ip = getenv('HTTP_X_FORWARDED');
		} elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		} elseif (getenv('HTTP_FORWARDED')) {
			$ip = getenv('HTTP_FORWARDED');
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}

	function wp_ulike_reutrn_userID($user_ID){		
		if(!is_user_logged_in()){
			$ip = wp_ulike_get_real_ip();
			$user_ip = ip2long($ip);
			return $user_ip;
		}
		else
			return $user_ID;
	}	
	
	//get custom style settings
	function wp_ulike_get_custom_style(){
		$btn_style = '';
		$counter_style = '';
		$customstyle = '';
		$customloading = '';
		
		//get custom icon
		$customicon = wp_ulike_get_setting( 'wp_ulike_general', 'button_url' );
		$iconurl = wp_get_attachment_url( $customicon );
				
		if(wp_ulike_get_setting( 'wp_ulike_customize', 'custom_style') == '1'){
		
		//get custom options
		$customstyle = get_option( 'wp_ulike_customize' );
		
		//button style
		$btn_bg = $customstyle['btn_bg'];
		$btn_border = $customstyle['btn_border'];
		$btn_color = $customstyle['btn_color'];
		
		//counter style
		$counter_bg = $customstyle['counter_bg'];
		$counter_border = $customstyle['counter_border'];
		$counter_color = $customstyle['counter_color'];
		
		//Loading animation
		$customloading = $customstyle['loading_animation'];
		$loadingurl = wp_get_attachment_url( $customloading );
		
		
		if($btn_bg != ''){
		$btn_style .= "background-color:$btn_bg !important; ";
		}			
		if($btn_border != ''){
		$btn_style .= "border-color:$btn_border !important; ";
		}			
		if($btn_color != ''){
		$btn_style .= "color:$btn_color !important;";
		}

		if($counter_bg != ''){
		$counter_style .= "background-color:$counter_bg !important; ";
		}			
		if($counter_border != ''){
		$counter_style .= "border-color:$counter_border !important; ";
		}			
		if($counter_color != ''){
		$counter_style .= "color:$counter_color !important;";
		}
		
		}
		
		if($customicon != '' || $customstyle != ''){
		
		echo "<style>";
		
		if($customicon != ''){
		echo '
		.wpulike .counter a.image {
			background-image: url('.$iconurl.') !important;
		}
		';
		}
		
		if($customloading != ''){
		echo '
		.wpulike .counter a.loading {
			background-image: url('.$loadingurl.') !important;
		}
		';
		}
		
		if($btn_style != ''){
		echo "
		.wpulike .counter a{
			$btn_style	
		}";
		}
		
		if($counter_style != ''){
		echo"
		.wpulike .count-box,.wpulike .count-box:before{
			$counter_style
		}";
		}
		
		echo "</style>";
		}
	}
	
	//Convert numbers of Likes with string (kilobyte) format.
	function wp_ulike_format_number($num){
		$plus = '+';
		if ($num >= 1000 && wp_ulike_get_setting( 'wp_ulike_general', 'format_number' ) == '1')
		$value = round($num/1000, 2) . 'K' . $plus;
		else
		$value = $num . $plus;
		$value = apply_filters( 'wp_ulike_format_number', $value, $num, $plus);
		return $value;
	}