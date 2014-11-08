<?php
//include pagination class
include( plugin_dir_path(dirname(__FILE__)) . 'class/pagination.class.php');

function wp_ulike_post_likes_logs(){
	global $wpdb;
	$alternate = true;
	$items = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike");
	if($items > 0) {
			$p = new pagination;
			$p->items($items);
			$p->limit(20); // Limit entries per page
			$p->target("admin.php?page=" . plugin_basename( dirname(__FILE__) ) . '/wp-options.php/post_logs'); 
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
			 
	$get_ulike_logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ulike ORDER BY id ASC ".$limit."");
	$count_total_like = $wpdb->get_var("SELECT SUM(meta_value) FROM ".$wpdb->prefix."postmeta  WHERE meta_key LIKE '_liked'" );
	$count_total_post = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."postmeta  WHERE meta_key LIKE '_liked'" );
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
				<th width="15%"><?php _e('Username', 'alimir'); ?></th>
				<th width="10%"><?php _e('Status', 'alimir'); ?></th>
				<th width="8%"><?php _e('Post ID', 'alimir'); ?></th>
				<th width="35%"><?php _e('Post Title', 'alimir'); ?></th>
				<th width="15%"><?php _e('Date / Time', 'alimir'); ?></th>
				<th width="15%"><?php _e('IP', 'alimir'); ?></th>
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
			echo $user_info->display_name;
			?>
			</td>
			<td>
			<?php echo $get_ulike_log->status; ?>
			</td>
			<td>
			<?php echo $get_ulike_log->post_id; ?>
			</td>
			<td>
			<?php echo get_the_title($get_ulike_log->post_id); ?> 
			</td>
			<td>
			<?php echo $get_ulike_log->date_time; ?> 
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
<div class="wrap">
	<h3><?php _e('Post Likes Logs Stats', 'alimir'); ?></h3>
	<br style="clear" />
	<table class="widefat">
		<tr class="alternate">
			<th><?php _e('Total Users Liked:', 'alimir'); ?></th>
			<td><?php echo $items; ?></td>
		</tr>
		<tr>
			<th><?php _e('Total Posts Liked:', 'alimir'); ?></th>
			<td><?php echo $count_total_post; ?></td>
		</tr>		
		<tr class="alternate">
			<th><?php _e('Total Likes Sum:', 'alimir'); ?></th>
			<td><?php echo $count_total_like; ?></td>
		</tr>
	</table>	
</div>
<?php
	} else {
		echo "No Record Found";
	}
}

function wp_ulike_comment_likes_logs(){
	global $wpdb;
	$alternate = true;
	$items = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_comments");
	if($items > 0) {
			$p = new pagination;
			$p->items($items);
			$p->limit(20); // Limit entries per page
			$p->target("admin.php?page=" . plugin_basename( dirname(__FILE__) ) . '/wp-options.php/comment_logs'); 
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
			 
	$get_ulike_logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ulike_comments ORDER BY id ASC ".$limit."");
	$count_total_like = $wpdb->get_var("SELECT SUM(meta_value) FROM ".$wpdb->prefix."commentmeta  WHERE meta_key LIKE '_commentliked'" );
	$count_total_comments = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."commentmeta  WHERE meta_key LIKE '_commentliked'" );
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
				<th width="15%"><?php _e('Username', 'alimir'); ?></th>
				<th width="10%"><?php _e('Status', 'alimir'); ?></th>
				<th width="8%"><?php _e('Comment ID', 'alimir'); ?></th>
				<th width="15%"><?php _e('Comment Author', 'alimir'); ?></th>
				<th width="15%"><?php _e('Date / Time', 'alimir'); ?></th>
				<th width="15%"><?php _e('IP', 'alimir'); ?></th>
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
			echo $user_info->display_name;
			?>
			</td>
			<td>
			<?php echo $get_ulike_log->status; ?>
			</td>
			<td>
			<?php echo $get_ulike_log->comment_id; ?>
			</td>
			<td>
			<?php echo get_comment_author($get_ulike_log->comment_id) ?> 
			</td>
			<td>
			<?php echo $get_ulike_log->date_time; ?> 
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
<div class="wrap">
	<h3><?php _e('Comment Likes Logs Stats', 'alimir'); ?></h3>
	<br style="clear" />
	<table class="widefat">
		<tr class="alternate">
			<th><?php _e('Total Users Liked:', 'alimir'); ?></th>
			<td><?php echo $items; ?></td>
		</tr>
		<tr>
			<th><?php _e('Total Comments Liked:', 'alimir'); ?></th>
			<td><?php echo $count_total_comments; ?></td>
		</tr>
		<tr class="alternate">
			<th><?php _e('Total Likes Sum:', 'alimir'); ?></th>
			<td><?php echo $count_total_like; ?></td>
		</tr>
	</table>	
</div>
<?php
	} else {
		echo "No Record Found";
	}
}

function wp_ulike_buddypress_likes_logs(){
	global $wpdb;
	$alternate = true;
	$items = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."ulike_activities");
	if($items > 0) {
			$p = new pagination;
			$p->items($items);
			$p->limit(20); // Limit entries per page
			$p->target("admin.php?page=" . plugin_basename( dirname(__FILE__) ) . '/wp-options.php/bp_logs'); 
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
			 
	$get_ulike_logs = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."ulike_activities ORDER BY id ASC ".$limit."");
	$count_total_like = $wpdb->get_var("SELECT SUM(meta_value) FROM ".$wpdb->prefix."bp_activity_meta  WHERE meta_key LIKE '_activityliked'" );
	$count_total_activity = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."bp_activity_meta  WHERE meta_key LIKE '_activityliked'" );
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
					<th><?php _e('ID', 'alimir'); ?></th>
					<th><?php _e('Username', 'alimir'); ?></th>
					<th><?php _e('Status', 'alimir'); ?></th>
					<th><?php _e('Activity ID', 'alimir'); ?></th>
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
				echo $user_info->display_name;
				?>
				</td>
				<td>
				<?php echo $get_ulike_log->status; ?>
				</td>
				<td>
				<?php echo $get_ulike_log->activity_id; ?>
				</td>
				<td>
				<?php echo $get_ulike_log->date_time; ?> 
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
	<div class="wrap">
		<h3><?php _e('Activity Likes Logs Stats', 'alimir'); ?></h3>
		<br style="clear" />
		<table class="widefat">
			<tr class="alternate">
				<th><?php _e('Total Users Liked:', 'alimir'); ?></th>
				<td><?php echo $items; ?></td>
			</tr>
			<tr>
				<th><?php _e('Total Activities Liked:', 'alimir'); ?></th>
				<td><?php echo $count_total_activity; ?></td>
			</tr>
			<tr class="alternate">
				<th><?php _e('Total Likes Sum:', 'alimir'); ?></th>
				<td><?php echo $count_total_like; ?></td>
			</tr>
		</table>	
	</div>
<?php
	} else {
		echo "No Record Found";
	}	
}