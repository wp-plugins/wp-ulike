<?php

//JS
add_action('init', 'wp_ulike_enqueue_scripts');
function wp_ulike_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('wp_ulike', plugins_url('assets/js/wp-ulike.js', dirname(__FILE__)), array('jquery'));	

    wp_localize_script( 'wp_ulike', 'ulike_obj', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'text_after_like' => wp_ulike_get_setting( 'wp_ulike_general', 'text_after_like'),
        'text_after_unlike' => wp_ulike_get_setting( 'wp_ulike_general', 'text_after_unlike')
    ));
	add_action('wp_ajax_ulikeprocess','wp_ulike_process');
	add_action('wp_ajax_nopriv_ulikeprocess', 'wp_ulike_process');
	add_action('wp_ajax_ulikecommentprocess','wp_ulike_comments_process');
	add_action('wp_ajax_nopriv_ulikecommentprocess', 'wp_ulike_comments_process');
	add_action('wp_ajax_ulikebuddypressprocess','wp_ulike_buddypress_process');
	add_action('wp_ajax_nopriv_ulikebuddypressprocess', 'wp_ulike_buddypress_process');
}

//CSS
add_action('wp_print_styles', 'wp_ulike_enqueue_style');
function wp_ulike_enqueue_style() {

	//Plugin default style for RTL & LTR languages.
	if(!is_rtl())
	wp_enqueue_style( 'wp-ulike', plugins_url('assets/css/wp-ulike.css', dirname(__FILE__)) );
	else
	wp_enqueue_style( 'wp-ulike', plugins_url('assets/css/wp-ulike-rtl.css', dirname(__FILE__)) );
	
	//add your custom style from setting panel.
	wp_ulike_get_custom_style();	
}