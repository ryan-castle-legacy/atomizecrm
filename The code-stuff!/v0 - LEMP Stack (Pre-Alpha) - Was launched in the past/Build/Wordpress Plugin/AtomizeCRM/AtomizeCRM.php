<?php

/*
 # 	Plugin Name: 	AtomizeCRM Chat
 # 	Plugin URI: 	https://wordpress.org/plugins/atomizecrm-chat
 #	Description: 	This plugin allows website visitors to communicate with a business.
 #	Version: 		1.0.0
 #	Author: 		AtomizeCRM
 #	Author URI: 	https://www.atomizecrm.com
 #	License: 		GPLv2 or later
 #	License URI: 	https://www.gnu.org/licenses/gpl-2.0.html
*/




// Add script
function atomizecrm_enqueue_scripts() {
	// Add script
	wp_enqueue_script('atomizecrm_add_chat_widget_js', 'https://atomizecrm.com/chat-widget/main.min.js');
}

// Add script
add_action('wp_enqueue_scripts', 'atomizecrm_enqueue_scripts');
