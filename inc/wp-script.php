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
	add_action('wp_ajax_ulikecommentprocess','wp_ulike_comments_process');
	add_action('wp_ajax_nopriv_ulikecommentprocess', 'wp_ulike_comments_process');
	add_action('wp_ajax_ulikebuddypressprocess','wp_ulike_buddypress_process');
	add_action('wp_ajax_nopriv_ulikebuddypressprocess', 'wp_ulike_buddypress_process');
}

function enqueueStyle() {

	//Plugin default style for RTL & LTR languages.
	if(!is_rtl())
	wp_enqueue_style( 'wp-ulike', plugins_url('assets/css/wp-ulike.css', dirname(__FILE__)) );
	else
	wp_enqueue_style( 'wp-ulike', plugins_url('assets/css/wp-ulike-rtl.css', dirname(__FILE__)) );
	
	//add your custom style from setting panel.
	if(get_option('wp_ulike_style') == 1)
	get_user_style();	
}

function enqueueAdmin(){
	//Add Wordpress color picker style.
	wp_enqueue_style( 'wp-color-picker' );
	//Admin js file.
	wp_enqueue_script( 'wp-ulike-admin', plugins_url('assets/js/wp-admin.js', dirname(__FILE__)), array( 'wp-color-picker' ), false, true );
}

add_action('admin_enqueue_scripts', 'enqueueAdmin' );
add_action('init', 'enqueueScripts');
add_action('wp_print_styles', 'enqueueStyle');