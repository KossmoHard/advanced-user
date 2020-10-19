<?php
if( ! defined('WP_UNINSTALL_PLUGIN') )
	exit;

$users = get_users();
foreach ($users as $user) {
     delete_user_meta($user->ID, 'address');
     delete_user_meta($user->ID, 'phone');
     delete_user_meta($user->ID, 'gender');
     delete_user_meta($user->ID, 'family-status');
}
