<?php
	//include wp_ulike_stats class
	include( plugin_dir_path(__FILE__) . 'classes/class-charts.php');
	
	/**
	 * Create WP ULike statistics with wp_ulike_stats class
	 *
	 * @author       	Alimir	 	
	 * @since           2.0	
	 * @return			String
	 */	
	function wp_ulike_statistics(){
	global $wp_ulike_stats;	
			
	echo '<div class="wrap">';
	
	/*******************************************************
	  Welcome Panel Section
	*******************************************************/
	
	$WelcomesArr = array(
		"posts" => array(
			"type" 		=> "ulike",
			"table" 	=> "postmeta",
			"key" 		=> "_liked",
			"title" 	=> __('Posts Likes Summary','alimir')
		),
		"comments" => array(
			"type" 		=> "ulike_comments",
			"table" 	=> "commentmeta",
			"key" 		=> "_commentliked",
			"title" 	=> __('Comments Likes Summary','alimir')
		),
		"activities" 	=> array(
			"type" 		=> "ulike_activities",
			"table" 	=> "bp_activity_meta",
			"key" 		=> "_activityliked",
			"title" 	=> __('Activities Likes Summary','alimir')
		)
	);	
	
	echo '<div id="welcome-panel" class="welcome-panel"><div class="welcome-panel-content">';
	echo '<h3>' . __('Welcome to WP ULike Statistics!','alimir') . '</h3>';
	echo '<p class="about-description">' . __('We have provided some useful statistics tools in this page:','alimir') . '</p>';
	echo '<div class="welcome-panel-column-container">';
	
	foreach ($WelcomesArr as $WelcomeArr) {
		
	echo'
	<div class="welcome-panel-column">
		<h4>'.$WelcomeArr["title"].'</h4>
		<ul>
		<li><em class="welcome-icon dashicons-star-filled">'. __('Today','alimir') .': <span style="color: #27ae60 !important;font-size: 24px;padding:0 10px;">'. $wp_ulike_stats->get_data_date($WelcomeArr["type"],'today').'</span></em></li>
		<li><em class="welcome-icon dashicons-star-empty">'. __('Yesterday','alimir') .': <span style="color: #27ae60 !important;font-size: 24px;padding:0 10px;">'. $wp_ulike_stats->get_data_date($WelcomeArr["type"],'yesterday').'</span></em></li>
		<li><em class="welcome-icon dashicons-calendar">'. __('Week','alimir') .': <span style="color: #27ae60 !important;font-size: 24px;padding:0 10px;">'. $wp_ulike_stats->get_data_date($WelcomeArr["type"],'week').'</span></em></li>
		<li><em class="welcome-icon dashicons-flag">'. __('Month','alimir') .': <span style="color: #27ae60 !important;font-size: 24px;padding:0 10px;">'. $wp_ulike_stats->get_data_date($WelcomeArr["type"],'month').'</span></em></li>
		<li><em class="welcome-icon dashicons-chart-area">'. __('Total','alimir') .': <span style="color: #27ae60 !important;font-size: 24px;padding:0 10px;">'. $wp_ulike_stats->get_all_data_date($WelcomeArr["table"],$WelcomeArr["key"]).'</span></em></li>
		</ul>
	</div>				
	';
		
	}
	
	echo '</div></div></div>';
	

	/*******************************************************
	  Charts Panel Section
	*******************************************************/
	
	$ChartsArr = array(
		"posts" => array(
			"id" 		=> "posts_likes_stats",
			"view_logs" => ' <a style="text-decoration:none;" href="?page=wp-ulike-post-logs" target="_blank"><i class="dashicons dashicons-visibility"></i> '. __('View Logs','alimir') .'</a>',
			"title" 	=> __('Posts Likes Stats','alimir'),
			"chart" 	=> "chart1"
		),
		"activities" => array(
			"id" 		=> "activities_likes_stats",
			"view_logs" => ' <a style="text-decoration:none;" href="?page=wp-ulike-bp-logs" target="_blank"><i class="dashicons dashicons-visibility"></i> '. __('View Logs','alimir') .'</a>',
			"title" 	=> __('Activities Likes Stats','alimir'),
			"chart" 	=> "chart3"
		),		
		"comments" => array(
			"id" 		=> "comments_likes_stats",
			"view_logs" => ' <a style="text-decoration:none;" href="?page=wp-ulike-comment-logs" target="_blank"><i class="dashicons dashicons-visibility"></i> '. __('View Logs','alimir') .'</a>',
			"title" 	=> __('Comments Likes Stats','alimir'),
			"chart" 	=> "chart2"
		),
		"piechar" => array(
			"id" 		=> "piechart_stats",
			"view_logs" => null,
			"title" 	=> __('Likes Percent In The Last 20 Days','alimir'),
			"chart"		=> "piechart"
		)
	);
	
	echo '<div id="dashboard-widgets-wrap"><div id="dashboard-widgets" class="metabox-holder">';
	
	foreach ($ChartsArr as $ChartArr) {
	
	if ($ChartArr['id'] == "posts_likes_stats")
	echo '<div id="postbox-container-2" class="postbox-container"><div class="meta-box-sortables ui-sortable">';
	else if($ChartArr['id'] == "comments_likes_stats")
	echo '<div id="postbox-container-1" class="postbox-container"><div class="meta-box-sortables ui-sortable">';
	
	echo '
	<div id="'.$ChartArr['id'].'" class="postbox">
		<div class="handlediv" title="Click to toggle"><br></div>
		<h3 class="hndle"><span>'.$ChartArr['title'] . $ChartArr['view_logs'].' </span></h3>
		<div class="inside">
			<div class="main">
			<div>
				<canvas id="'.$ChartArr['chart'].'"></canvas>
			</div>
			</div>
		</div>
	</div>';
	
	if ($ChartArr['id'] == "activities_likes_stats" || $ChartArr['id'] == "piechart_stats")
	echo '</div></div>';
	
	}
	
	echo '</div></div></div>';
			
	}