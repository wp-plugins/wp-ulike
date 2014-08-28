<?php 
// Creating the widget 
class wp_ulike_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'wp_ulike', 
			__('WP Ulike Widget', 'alimir'), 
			array( 'description' => __( 'This plugin allows your visitors to simply like your posts instead of comment it.', 'alimir' ))
			);
	}
	
	public function most_liked_posts($numberOf, $before, $after, $show_count) {
		global $wpdb;

		$request = "SELECT ID, post_title, meta_value FROM $wpdb->posts, $wpdb->postmeta";
		$request .= " WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id";
		$request .= " AND post_status='publish' AND meta_key='_liked'";
		$request .= " ORDER BY $wpdb->postmeta.meta_value+0 DESC LIMIT $numberOf";
		$posts = $wpdb->get_results($request);

		foreach ($posts as $post) {
			$post_title = stripslashes($post->post_title);
			$permalink = get_permalink($post->ID);
			$post_count = $post->meta_value;
			
			echo $before.'<a href="' . $permalink . '" title="' . $post_title.'" rel="nofollow">' . $post_title . '</a>';
			echo $show_count == '1' ? ' ('.$post_count.')' : '';
			echo $after;
		}
	}	

	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', $instance['title'] );
		$numberOf = $instance['count'];
		$show_count = (isset($instance['show_count']) == true ) ? 1 : 0;
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		echo '<ul class="mostlikedposts">';
		echo $this->most_liked_posts($numberOf, '<li>', '</li>', $show_count);
		echo '</ul>';
		echo $args['after_widget'];
	}
	

			
	public function form( $instance ) {
		//Set up some default widget settings.
		$defaults = array( 'title' => __('Most liked posts', 'alimir'), 'count' => 15, 'show_count' => true );
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'alimir'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		 
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e('Number of posts to show:', 'alimir'); ?><small> (max. 15)</small></label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" style="width:100%;" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" <?php if($instance['show_count'] == true) echo 'checked="checked"'; ?> /> 
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e('Show post count', 'alimir'); ?></label>
		</p>	
		<?php 
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = strip_tags( $new_instance['count'] );
		$instance['show_count'] = $new_instance['show_count'];

		return $instance;
	}
}

function wp_ulike_load_widget() {
	register_widget( 'wp_ulike_widget' );
}
add_action( 'widgets_init', 'wp_ulike_load_widget' );
?>