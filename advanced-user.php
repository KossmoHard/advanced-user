<?php
/*
*
* Plugin Name: Advanced User
*
*/

define('ADVANCED_USER_DIR',  plugin_dir_path(__FILE__));


/* Подключение css/js */
add_action('login_enqueue_scripts', 'advanced_user_scripts_login');
function advanced_user_scripts_login(){
	wp_enqueue_script( 'jquery-maskedinput', plugins_url('js/jquery.maskedinput.min.js', __FILE__ ), array('jquery'));
	wp_enqueue_script( 'advanced-user-script', plugins_url('js/script.js', __FILE__ ), array('jquery'));
}

add_action( 'wp_enqueue_scripts', 'advanced_user_scripts' );
function advanced_user_scripts() {
	wp_enqueue_style( 'advanced-user-style', plugins_url('css/style.css', __FILE__ ));
}

require_once(ADVANCED_USER_DIR . 'includes/advanced-user-functions.php');

add_action( 'activated_plugin', 'advanced_user_add_meta');
function advanced_user_add_meta(){
  $users = get_users();

  foreach ($users as $user){
  	//echo 'test', $user->ID;
    add_user_meta($user->ID, 'address', '', true);
    add_user_meta($user->ID, 'phone', '', true);
    add_user_meta($user->ID, 'gender', '', true);
    add_user_meta($user->ID, 'family-status', '', true);
  }
}

register_activation_hook('__FILE__', 'advanced_user_activation');
function advanced_user_activation(){

}

register_deactivation_hook('__FILE__', 'advanced_user_deactivation');
function advanced_user_deactivation(){

}
