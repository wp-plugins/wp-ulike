<?php 
if ( ! class_exists( 'wp_ulike_stats' ) ) {

	class wp_ulike_stats{
		private $wpdb;
		
		/**
		 * Constructor
		 */	
		public function __construct()
		{
			global $wpdb;
			$this->wpdb = $wpdb;
			add_action('admin_enqueue_scripts', array($this,'enqueue_script'));
		}

		/**
		 * Add chart scripts files + Creating Localize Objects
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			Void
		 */		
		public function enqueue_script()
		{
			wp_register_script('wp_ulike_chart', plugins_url( 'js/chart.min.js' , __FILE__ ), array('jquery'), null, true);
			wp_enqueue_script('wp_ulike_chart');
			wp_register_script('wp_ulike_stats', plugins_url( 'js/statistics.js' , __FILE__ ), array('jquery'), null, true);
			wp_enqueue_script('wp_ulike_stats');
			wp_localize_script( 'wp_ulike_stats', 'wp_ulike_statistics', array(
				'posts_date_labels' => $this->posts_dataset('label'),
				'comments_date_labels' => $this->comments_dataset('label'),
				'activities_date_labels' => $this->activities_dataset('label'),
				'posts_dataset' => $this->posts_dataset('dataset'),
				'comments_dataset' => $this->comments_dataset('dataset'),
				'activities_dataset' => $this->activities_dataset('dataset')
			));			
			wp_enqueue_script('postbox');
		}
		
		/**
		 * Get The Posts Data Set
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			JSON Array
		 */				
		public function posts_dataset($type){
			$return_type = $type != 'dataset' ? 'new_date_time' : 'count_date_time';
			$return_val = $this->select_data('ulike');
			foreach($return_val as $val){
				$newarray[] = $val->$return_type;
			}
			return json_encode($newarray, JSON_NUMERIC_CHECK);
		}
		
		/**
		 * Get The Comments Data Set
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			JSON Array 
		 */			
		public function comments_dataset($type){
			$return_type = $type != 'dataset' ? 'new_date_time' : 'count_date_time';
			$return_val = $this->select_data('ulike_comments');
			foreach($return_val as $val){
				$newarray[] = $val->$return_type;
			}				
			return json_encode($newarray, JSON_NUMERIC_CHECK);
		}

		/**
		 * Get The Activities Data Set
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			JSON Array 
		 */			
		public function activities_dataset($type){
			$return_type = $type != 'dataset' ? 'new_date_time' : 'count_date_time';
			$return_val = $this->select_data('ulike_activities');
			foreach($return_val as $val){
				$newarray[] = $val->$return_type;
			}				
			return json_encode($newarray, JSON_NUMERIC_CHECK);
		}
		
		/**
		 * Get The Logs Data From Tables
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			String
		 */		
		public function select_data($table){
			$return_val = $this->wpdb->get_results(
			"
			SELECT DATE(date_time) AS new_date_time, count(date_time) AS count_date_time
			FROM ".$this->wpdb->prefix."$table
			GROUP BY new_date_time DESC LIMIT 20
			");
			return $return_val;
		}
		
		/**
		 * Get The Summary Of Like Data
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			Integer
		 */			
		public function get_data_date($table,$time){
			if($time == 'today')
			$where_val = "DATE(date_time) = DATE(NOW())";
			else if($time == 'yesterday')
			$where_val = "DATE(date_time) = DATE(subdate(current_date, 1))";
			else if($time == 'week')
			$where_val = "week(DATE(date_time)) = week(DATE(NOW()))";
			else 
			$where_val = "month(DATE(date_time)) = month(DATE(NOW()))";
			
			$return_val = $this->wpdb->get_var(
			"
			SELECT COUNT(*)
			FROM ".$this->wpdb->prefix."$table
			WHERE $where_val
			");
			return $return_val;		
		}

		/**
		 * Get The Sum Of All Likes
		 *
		 * @author       	Alimir
		 * @since           2.0
		 * @return			Integer
		 */					
		public function get_all_data_date($table,$name){
			$return_val = $this->wpdb->get_var(
			"
			SELECT SUM(meta_value)
			FROM ".$this->wpdb->prefix."$table
			WHERE meta_key LIKE '$name'
			");
			return $return_val;		
		}
		
	}
	
	//create global variable
	global $wp_ulike_stats;
	$wp_ulike_stats = new wp_ulike_stats();
	
}