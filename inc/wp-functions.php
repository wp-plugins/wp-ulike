<?php

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
	
	//get users data and list their avatar
	function get_user_data($post_ID,$user_ID){
		global $wpdb;
		$users_list = '';
		
		$get_users = $wpdb->get_results("SELECT user_id FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID'");
		
		foreach ( $get_users as $get_user ) 
		{
			if ($user_ID != $get_user->user_id):
			$user_info = get_userdata($get_user->user_id);
			$users_list .= '<li><a class="user-tooltip" title="'.$user_info->display_name.'">'.get_avatar( $user_info->user_email, 32, '' , 'avatar').'</a></li>';
			endif;
		}
		
		return $users_list;

	}
	
	//get users comments data and list their avatar
	function get_user_comments_data($CommentID,$user_ID){
		global $wpdb;
		$users_list = '';
		
		$get_users = $wpdb->get_results("SELECT user_id FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID'");
		
		foreach ( $get_users as $get_user ) 
		{
			if ($user_ID != $get_user->user_id):
			$user_info = get_userdata($get_user->user_id);
			$users_list .= '<li><a class="user-tooltip" title="'.$user_info->display_name.'">'.get_avatar( $user_info->user_email, 32, '' , 'avatar').'</a></li>';
			endif;
		}
		
		return $users_list;

	}
	
	//get user status (like or dislike)
	function get_user_activities_data($activityID,$user_ID){
		global $wpdb;
		$users_list = '';
		
		$get_users = $wpdb->get_results("SELECT user_id FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID'");
		
		foreach ( $get_users as $get_user ) 
		{
			if ($user_ID != $get_user->user_id):
			$user_info = get_userdata($get_user->user_id);
			$users_list .= '<li><a class="user-tooltip" title="'.$user_info->display_name.'">'.get_avatar( $user_info->user_email, 32, '' , 'avatar').'</a></li>';
			endif;
		}
		
		return $users_list;

	}
	
	//get user status (like or dislike)
	function get_user_status($post_ID,$user_ID){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike WHERE post_id = '$post_ID' AND user_id = '$user_ID'");
		if ($like_status == "like")
		return "like";
		else
		return "dislike";
	}
	
	//get user comment status (like or dislike)
	function get_user_comments_status($CommentID,$user_ID){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike_comments WHERE comment_id = '$CommentID' AND user_id = '$user_ID'");
		if ($like_status == "like")
		return "like";
		else
		return "dislike";
	}
	
	//get user activity status (like or dislike)
	function get_user_activities_status($activityID,$user_ID){
		global $wpdb;
		$like_status = $wpdb->get_var("SELECT status FROM ".$wpdb->prefix."ulike_activities WHERE activity_id = '$activityID' AND user_id = '$user_ID'");
		if ($like_status == "like")
		return "like";
		else
		return "dislike";
	}
	
	//get user style settings
	function get_user_style(){
		$btn_style = '';
		$counter_style = '';
		$btn_bg = get_option('wp_ulike_btn_bg');
		$btn_border = get_option('wp_ulike_btn_border');
		$btn_color = get_option('wp_ulike_btn_color');
		$counter_bg = get_option('wp_ulike_counter_bg');
		$counter_border = get_option('wp_ulike_counter_border');
		$counter_color = get_option('wp_ulike_counter_color');
		
		if(isset($btn_bg)){
		$btn_style .= "background-color:$btn_bg !important; ";
		}			
		if(isset($btn_border)){
		$btn_style .= "border-color:$btn_border !important; ";
		}			
		if(isset($btn_color)){
		$btn_style .= "color:$btn_color !important;";
		}

		if(isset($counter_bg)){
		$counter_style .= "background-color:$counter_bg !important; ";
		}			
		if(isset($counter_border)){
		$counter_style .= "border-color:$counter_border !important; ";
		}			
		if(isset($counter_color)){
		$counter_style .= "color:$counter_color !important;";
		}

		echo "
		<style>
		.wpulike .counter a{
			$btn_style	
		}
		.wpulike .count-box,.wpulike .count-box:before{
			$counter_style
		}
		</style>
		";
	}
	
	function wp_ulike_register_activity_actions() {
		global $bp;
		bp_activity_set_action(
			$bp->activity->id,
			'wp_like_group',
			__( 'WP ULike Activity', 'alimir' )
		);
	}

	add_action( 'bp_register_activity_actions', 'wp_ulike_register_activity_actions' );	
	
	function wp_ulike_bp_activity_add($user_ID,$cp_ID,$type){
		if (function_exists('bp_is_active') && get_option('wp_ulike_bp_activity_add') == '1') {
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
	
	//This function will be complete in the future
	/*
	function wp_ulike_bp_notification($user_ID,$activityID){
	global $bp;
    if ( bp_is_active( 'notifications' ) ) {
        bp_notifications_add_notification( array(
            'user_id'           => 1,
            'item_id'           => $activityID,
            'secondary_item_id' => 3,
            'date_notified'     => bp_core_current_time(),
            'is_new'            => 1,
        ) );
    }	
	}
	add_action( 'bp_activity_sent_mention_email', 'wp_ulike_bp_notification', 10, 5 );
	*/
	
	//Convert numbers of Likes with string (kilobyte) format.
	function wp_ulike_format_number($num){
		$plus = '+';
		if ($num >= 1000 && get_option('wp_ulike_format_number') == '1')
		$value = round($num/1000, 2) . 'K' . $plus;
		else
		$value = $num . $plus;
		$value = apply_filters( 'wp_ulike_format_number', $value, $num, $plus);
		return $value;
	}
	
	//get the post likes number
	function wp_ulike_get_post_likes($postID){
		$val = get_post_meta($postID, '_liked', true);
		return wp_ulike_format_number($val);
	}	
	
	//Shortcode function for the post likes
	function  wp_ulike_shortcode(){
		return wp_ulike('put');
	}
	add_shortcode( 'wp_ulike', 'wp_ulike_shortcode' );		

	//Insert ULike button to the posts
	if (get_option('wp_ulike_onPage') == '1') {
		function wp_put_ulike($content) {
			if(!is_feed() && !is_page()) {
				$content.= wp_ulike('put');
			}
			return $content;
		}

		add_filter('the_content', 'wp_put_ulike');
	}
	
	//Insert ULike button to the comments
	if (get_option('wp_ulike_onComments') == '1' && !is_admin()) {
		function wp_put_ulike_comments($content) {
			$content.= wp_ulike_comments('put');
			return $content;
		}
		
		add_filter('comment_text', 'wp_put_ulike_comments');
	}
	
	//Insert ULike button to the activities
	if (get_option('wp_ulike_onActivities') == '1' && !is_admin()) {
		function wp_put_ulike_buddypress($content) {
			$content.= wp_ulike_buddypress('put');
			return $content;
		}
		
		add_filter('bp_get_activity_action', 'wp_put_ulike_buddypress',10,2);
	}