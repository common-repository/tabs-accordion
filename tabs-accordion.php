<?php
/*
   Plugin Name: Tabs & Accordion
   Description: A plugin to add tabs and accordion to the website with respect to window width
   Version: 1.0.0
   Author: Ashok
   Author URI: https://profiles.wordpress.org/askkumar14/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$plugin_url 	= WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) );
$plugin_path	= plugin_dir_path(__FILE__);
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
* include global functions
**/
include($plugin_path.'includes/functions.php');

/**
*function to add main menu of the plugin.
**/

function tbacc_add_main_menu(){
	$icon_url = plugins_url('assets/images/tbacc-icon.png',__FILE__);
	add_menu_page('Tabs & Accordion','Tabs & Accordion','activate_plugins','tabs-accordion','tbacc_listing_page',$icon_url);
	add_submenu_page('tabs-accordion','Add New', 'Add New', 'manage_options','add-new-tabs-accordion','tbacc_add_new_page');
	add_submenu_page('tabs-accordion','Settings', 'Settings', 'manage_options','tabs-accordion-settings','tbacc_settings_page');
}
add_action('admin_menu','tbacc_add_main_menu');


/**
*function to tab accordions listing page
**/
function tbacc_listing_page(){
 	include($plugin_path.'includes/pages/tbacc-listing.php');
}
/**
*function to tab accordions add new page
**/
function tbacc_add_new_page(){
 	include($plugin_path.'includes/pages/tbacc-add-new.php');
}
/**
*function to tab accordions settings page
**/
function tbacc_settings_page(){
 	include($plugin_path.'includes/pages/tbacc-settings.php');
}


/**
*Function for add css file
*
**/
function tbacc_style() {
	wp_register_style( 'tbacc-style', plugins_url('assets/css/tbacc-style.css', __FILE__) );
	wp_enqueue_style( 'tbacc-style');
}
add_action( 'admin_print_styles','tbacc_style');


/**
*function to add javascript
**/
function tbacc_scripts() {
    wp_enqueue_script( 'tbacc-jscolor',plugins_url('assets/js/jscolor.js',__FILE__),array(),'4.3.1',false);
    wp_enqueue_script( 'tbacc-script',plugins_url('assets/js/tbacc-script.js',__FILE__),array(),'4.3.1',false);
}
add_action( 'admin_enqueue_scripts','tbacc_scripts');

/**
* fucntion to add frontend css and script
**/
function tbacc_frontend_css_script(){
	wp_register_style( 'tbacc-frontend-style', plugins_url('assets/css/tbacc-frontend-style.css', __FILE__) );
	wp_enqueue_style( 'tbacc-frontend-style');
	wp_enqueue_script( 'tbacc-frontend-script',plugins_url('assets/js/tbacc-frontend-script.js',__FILE__),array(),'4.3.1',false);
}
add_action('wp_enqueue_scripts','tbacc_frontend_css_script');
/**
* action to add shortcode
**/
add_shortcode('tbacc','tbacc_shortcode_func');
/**
* action to clear buffer 
**/
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}