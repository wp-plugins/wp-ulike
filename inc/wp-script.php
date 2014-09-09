<?php 
//Scripts
function enqueueScripts() {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('wp_ulike', plugins_url('assets/js/wp-ulike.js', dirname(__FILE__)), array('jquery'));	

    wp_localize_script( 'wp_ulike', 'ulike_obj', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'likeText' => get_option('wp_ulike_btn_text'),
        'disLikeText' => get_option('wp_ulike_dislike_text')
    ));
	add_action('wp_ajax_ulikeprocess','wp_ulike_process');
	add_action('wp_ajax_nopriv_ulikeprocess', 'wp_ulike_process');
}

function enqueueStyle() {
	if(!is_rtl())
	wp_enqueue_style( 'wp-ulike', plugins_url('assets/css/wp-ulike.css', dirname(__FILE__)) );
	else
	wp_enqueue_style( 'wp-ulike', plugins_url('assets/css/wp-ulike-rtl.css', dirname(__FILE__)) );
}

add_action('init', 'enqueueScripts');
add_action('wp_print_styles', 'enqueueStyle');