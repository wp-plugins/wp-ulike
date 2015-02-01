<?php

	//include pagination class
	include( plugin_dir_path(__FILE__) . 'classes/class-pagination.php');

	/*******************************************************
	  Post Logs Page
	*******************************************************/	
	/**
	 * Create WP ULike Post Logs page with separate pagination
	 *
	 * @author       	Alimir	 	
	 * @since           1.7
	 * @updated         2.0	
	 * @return			String
	 */		
	function wp_ulike_post_likes_logs(){
		global $wpdb;
		$alternate 	= true;
		$items 		= $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike");
		if($items > 0) {
				$p = new pagination;
				$p->items($items);
				$p->limit(20); // Limit entries per page
				$p->target("admin.php?page=wp-ulike-post-logs"); 
				$p->calculate(); // Calculates what to show
				$p->parameterName('page_number');
				$p->adjacents(1); //No. of page away from the current page
						 
				if(!isset($_GET['page_number'])) {
					$p->page = 1;
				} else {
					$p->page = $_GET['page_number'];
				}
				 
				//Query for limit page_number
				$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
				 
		$get_ulike_logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ulike ORDER BY id DESC ".$limit."");
		
	?>
	<div class="wrap">
		<h2><?php _e('WP ULike Logs', 'alimir'); ?></h2>
		<h3><?php _e('Post Likes Logs', 'alimir'); ?></h3>
		<div class="tablenav">
			<div class='tablenav-pages'>
				<?php echo $p->show();  // Echo out the list of paging. ?>
			</div>
		</div>	
		<table class="widefat">
			<thead>
				<tr>
					<th width="2%"><?php _e('ID', 'alimir'); ?></th>
					<th width="10%"><?php _e('Username', 'alimir'); ?></th>
					<th><?php _e('Status', 'alimir'); ?></th>
					<th width="4%"><?php _e('Post ID', 'alimir'); ?></th>
					<th><?php _e('Post Title', 'alimir'); ?></th>
					<th width="20%"><?php _e('Date / Time', 'alimir'); ?></th>
					<th><?php _e('IP', 'alimir'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $get_ulike_logs as $get_ulike_log ) 
				{
				?>
				<tr <?php if ($alternate == true) echo 'class="alternate"';?>>
				<td>
				<?php echo $get_ulike_log->id; ?>
				</td>
				<td>
				<?php
				$user_info = get_userdata($get_ulike_log->user_id);
				if($user_info)
				echo get_avatar( $user_info->user_email, 16, '' , 'avatar') . '<em> @' . $user_info->user_login . '</em>';
				else
				echo '<em> #'. __('Guest User','alimir') .'</em>';
				?>
				</td>
				<td>
				<?php
				$get_the_status = $get_ulike_log->status;
				if($get_the_status == 'like')
				echo '<img src="'.plugin_dir_url( __FILE__ ).'/classes/img/like.png" alt="like" width="24"/>';
				else
				echo '<img src="'.plugin_dir_url( __FILE__ ).'/classes/img/unlike.png" alt="unlike" width="24"/>';
				?>
				</td>
				<td>
				<?php echo $get_ulike_log->post_id; ?>
				</td>
				<td>
				<?php echo '<a href="'.get_permalink($get_ulike_log->post_id).'" title="'.get_the_title($get_ulike_log->post_id).'">'.get_the_title($get_ulike_log->post_id).'</a>'; ?> 
				</td>
				<td>
				<?php
				$get_the_date = $get_ulike_log->date_time;
				$get_the_date_timestamp = strtotime($get_the_date);
				echo date_i18n(get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), $get_the_date_timestamp );
				?> 
				</td>
				<td>
				<?php echo $get_ulike_log->ip; ?> 
				</td>
				<?php 
				$alternate = !$alternate;
				}
				?>
				</tr>
			</tbody>
		</table>
		<div class="tablenav">
			<div class='tablenav-pages'>
				<?php echo $p->show();  // Echo out the list of paging. ?>
			</div>
		</div>
	</div>
	<?php
		} else {
			echo "<div class='error'><p>" . __('<strong>ERROR:</strong> No Record Found. (This problem is created because you don\'t have any data on this table)','alimir') . "</p></div>";
		}
	}
	
	/*******************************************************
	  Comment Logs Page
	*******************************************************/		
	/**
	 * Create WP ULike Comment Logs page with separate pagination
	 *
	 * @author       	Alimir	 	
	 * @since           1.7
	 * @updated         2.0	
	 * @return			String
	 */
	function wp_ulike_comment_likes_logs(){
		global $wpdb;
		$alternate 	= true;
		$items 		= $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments");
		if($items > 0) {
				$p = new pagination;
				$p->items($items);
				$p->limit(20); // Limit entries per page
				$p->target("admin.php?page=wp-ulike-comment-logs"); 
				$p->calculate(); // Calculates what to show
				$p->parameterName('page_number');
				$p->adjacents(1); //No. of page away from the current page
						 
				if(!isset($_GET['page_number'])) {
					$p->page = 1;
				} else {
					$p->page = $_GET['page_number'];
				}
				 
				//Query for limit page_number
				$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
				 
		$get_ulike_logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ulike_comments ORDER BY id DESC ".$limit."");
	?>
	<div class="wrap">
		<h2><?php _e('WP ULike Logs', 'alimir'); ?></h2>
		<h3><?php _e('Comment Likes Logs', 'alimir'); ?></h3>
		<div class="tablenav">
			<div class='tablenav-pages'>
				<?php echo $p->show();  // Echo out the list of paging. ?>
			</div>
		</div>	
		<table class="widefat">
			<thead>
				<tr>
					<th width="2%"><?php _e('ID', 'alimir'); ?></th>
					<th width="10%"><?php _e('Username', 'alimir'); ?></th>
					<th width="5%"><?php _e('Status', 'alimir'); ?></th>
					<th width="3%"><?php _e('Comment ID', 'alimir'); ?></th>
					<th><?php _e('Comment Author', 'alimir'); ?></th>
					<th><?php _e('Comment Text', 'alimir'); ?></th>
					<th width="20%"><?php _e('Date / Time', 'alimir'); ?></th>
					<th><?php _e('IP', 'alimir'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $get_ulike_logs as $get_ulike_log ) 
				{
				?>
				<tr <?php if ($alternate == true) echo 'class="alternate"';?>>
				<td>
				<?php echo $get_ulike_log->id; ?>
				</td>
				<td>
				<?php
				$user_info = get_userdata($get_ulike_log->user_id);
				if($user_info)
				echo get_avatar( $user_info->user_email, 16, '' , 'avatar') . '<em> @' . $user_info->user_login . '</em>';
				else
				echo '<em> #'. __('Guest User','alimir') .'</em>';
				?>
				</td>
				<td>
				<?php
				$get_the_status = $get_ulike_log->status;
				if($get_the_status == 'like')
				echo '<img src="'.plugin_dir_url( __FILE__ ).'/classes/img/like.png" alt="like" width="24"/>';
				else
				echo '<img src="'.plugin_dir_url( __FILE__ ).'/classes/img/unlike.png" alt="unlike" width="24"/>';
				?>
				</td>
				<td>
				<?php echo $get_ulike_log->comment_id; ?>
				</td>
				<td>
				<?php echo get_comment_author($get_ulike_log->comment_id) ?> 
				</td>
				<td>
				<?php echo get_comment_text($get_ulike_log->comment_id) ?> 
				</td>
				<td>	
				<?php
				$get_the_date = $get_ulike_log->date_time;
				$get_the_date_timestamp = strtotime($get_the_date);
				echo date_i18n(get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), $get_the_date_timestamp );			
				?> 
				</td>
				<td>
				<?php echo $get_ulike_log->ip; ?> 
				</td>
				<?php 
				$alternate = !$alternate;
				}
				?>
				</tr>
			</tbody>
		</table>
		<div class="tablenav">
			<div class='tablenav-pages'>
				<?php echo $p->show();  // Echo out the list of paging. ?>
			</div>
		</div>
	</div>
	
	<?php
		} else {
			echo "<div class='error'><p>" . __('<strong>ERROR:</strong> No Record Found. (This problem is created because you don\'t have any data on this table)','alimir') . "</p></div>";
		}
	}

	/*******************************************************
	  BuddyPress Logs Page
	*******************************************************/		
	/**
	 * Create WP ULike BuddyPress Logs page with separate pagination
	 *
	 * @author       	Alimir	 	
	 * @since           1.7
	 * @updated         2.0	
	 * @return			String
	 */	
	function wp_ulike_buddypress_likes_logs(){
		global $wpdb;
		$alternate 	= true;
		$items 		= $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities");
		if($items > 0) {
				$p = new pagination;
				$p->items($items);
				$p->limit(20); // Limit entries per page
				$p->target("admin.php?page=wp-ulike-bp-logs"); 
				$p->calculate(); // Calculates what to show
				$p->parameterName('page_number');
				$p->adjacents(1); //No. of page away from the current page
						 
				if(!isset($_GET['page_number'])) {
					$p->page = 1;
				} else {
					$p->page = $_GET['page_number'];
				}
				 
				//Query for limit page_number
				$limit = "LIMIT " . ($p->page - 1) * $p->limit  . ", " . $p->limit;
				 
		$get_ulike_logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ulike_activities ORDER BY id DESC ".$limit."");
		
	?>
		<div class="wrap">
			<h2><?php _e('WP ULike Logs', 'alimir'); ?></h2>
			<h3><?php _e('Activity Likes Logs', 'alimir'); ?></h3>
			<div class="tablenav">
				<div class='tablenav-pages'>
					<?php echo $p->show();  // Echo out the list of paging. ?>
				</div>
			</div>	
			<table class="widefat">
				<thead>
					<tr>
						<th width="3%"><?php _e('ID', 'alimir'); ?></th>
						<th width="13%"><?php _e('Username', 'alimir'); ?></th>
						<th><?php _e('Status', 'alimir'); ?></th>
						<th width="3%"><?php _e('Activity ID', 'alimir'); ?></th>
						<th><?php _e('Permalink', 'alimir'); ?></th>
						<th><?php _e('Date / Time', 'alimir'); ?></th>
						<th><?php _e('IP', 'alimir'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $get_ulike_logs as $get_ulike_log ) 
					{
					?>
					<tr <?php if ($alternate == true) echo 'class="alternate"';?>>
					<td>
					<?php echo $get_ulike_log->id; ?>
					</td>
					<td>
					<?php
					$user_info = get_userdata($get_ulike_log->user_id);
					if($user_info)
					echo get_avatar( $user_info->user_email, 16, '' , 'avatar') . '<em> @' . $user_info->user_login . '</em>';
					else
					echo '<em> #'. __('Guest User','alimir') .'</em>';
					?>
					</td>
					<td>
					<?php
					$get_the_status = $get_ulike_log->status;
					if($get_the_status == 'like')
					echo '<img src="'.plugin_dir_url( __FILE__ ).'/classes/img/like.png" alt="like" width="24"/>';
					else
					echo '<img src="'.plugin_dir_url( __FILE__ ).'/classes/img/unlike.png" alt="unlike" width="24"/>';
					?>
					</td>
					<td>
					<?php echo $get_ulike_log->activity_id; ?>
					</td>
					<td>
					<?php printf( __( '<a href="%1$s">Activity Permalink</a>', 'alimir' ), bp_activity_get_permalink( $get_ulike_log->activity_id ) ); ?>
					</td>
					<td>
					<?php
					$get_the_date = $get_ulike_log->date_time;
					$get_the_date_timestamp = strtotime($get_the_date);
					echo date_i18n(get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), $get_the_date_timestamp );			
					?>
					</td>
					<td>
					<?php echo $get_ulike_log->ip; ?> 
					</td>
					<?php 
					$alternate = !$alternate;
					}
					?>
					</tr>
				</tbody>
			</table>
			<div class="tablenav">
				<div class='tablenav-pages'>
					<?php echo $p->show();  // Echo out the list of paging. ?>
				</div>
			</div>
		</div>	
		
	<?php
		} else {
			echo "<div class='error'><p>" . __('<strong>ERROR:</strong> No Record Found. (This problem is created because you don\'t have any data on this table)','alimir') . "</p></div>";
		}	
	}