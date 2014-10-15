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
		
		/*
		$you_liked_this = $wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."ulike WHERE user_id = '$user_ID'");
		$get_user_likes_count = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike WHERE user_id = '$get_user->user_id'");
		$count_user = count($get_users);
		if ($user_ID == $you_liked_this){
			$users_list .= "You , $count_user";
		}	
		*/
		
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
	
	//Convert numbers of Likes with string (kilobyte) format.
	function wp_ulike_format_number($num){
		if ($num >= 1000 && get_option('wp_ulike_format_number') == '1'){
			return round($num/1000, 2) . 'K+';
		}
		else
		return $num . '+';
	}	
	
	//Shortcode function
	function  wp_ulike_shortcode(){
		return wp_ulike('put');
	}
	add_shortcode( 'wp_ulike', 'wp_ulike_shortcode' );		

	//add ULike button to the posts
	if (get_option('wp_ulike_onPage') == '1') {
		function wp_put_ulike($content) {
			if(!is_feed() && !is_page()) {
				$content.= wp_ulike('put');
			}
			return $content;
		}

		add_filter('the_content', 'wp_put_ulike');
	}