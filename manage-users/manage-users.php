<?php

/*
Plugin Name: Manage Users from Frontend
Description: Add/List/Edit Users in Frontend
Version: 1.0
Author: Waqas Hassaan
Plugin URI: https://developer.wordpress.org/plugins/
Text Domain: manage-users
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/


//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// Activation hook
register_activation_hook( __FILE__, 'st_mg_add_option' );

// Deactivation hook
register_deactivation_hook( __FILE__, 'st_mg_on_uninstall' );



// load text domain
function st_mg_load_textdomain() {
	// First parameter should matches with text domain
	load_plugin_textdomain( 'manage-users', false, plugin_dir_path( __FILE__ ) . 'languages/' );
}
add_action( 'plugins_loaded', 'st_mg_load_textdomain' );

// if admin area
if ( is_admin() ) {
	
	// Include dependencies
	require_once plugin_dir_path( __file__ ).'install.php';
	require_once plugin_dir_path( __file__ ).'admin/admin-menu.php'; 
	require_once plugin_dir_path( __file__ ).'admin/settings-page.php';
	require_once plugin_dir_path( __file__ ).'uninstall.php';
}


// default plugin options
function st_mg_options_default() {

	return array(
		'custom_url'     => 'https://wordpress.org/',
		'custom_title'   => esc_html__( 'Powered by WordPress', 'manage-users' ),
		'custom_style'   => 'disable',
		'custom_message' => '<p class="custom-message">'.__( 'My custom message', 'manage-users' ).'</p>',
		'custom_footer'  => esc_html__( 'Special message for users', 'manage-users' ),
		'custom_toolbar' => false,
		'custom_scheme'  => 'default',
	);

}

// Check user access
function has_user_access()
{
	// Getting Roles allowed by admin
	$option = get_option('st_mg_plugin_levels');
	$opt = strtolower($option);
	
	// Splitting roles
	$wp_roles =	explode(",", $opt);
	
	// Getting current user role
	$r = wp_get_current_user();

	foreach ($r->roles as $role) {
		if (in_array( strtolower($role), $wp_roles ))
		{
			return true;
		}	
	}
	return false;
	
}


if ( !is_admin() )
{

	//Showing front end page to Update user
	function st_mg_get_user_page()
	{
		$valA = get_option('st_mg_add');
		$valB = get_option('st_mg_view');
		$valC = get_option('st_mg_edit');
		
		if ($valA == '0' || $valB == '0' || $valC == '0' ) {
			return;
		}
		
		//Add user page
	    if(is_page($valA))
	    {   
	    	if (has_user_access() === true)
			{
		        $dir = plugin_dir_path( __FILE__ );
		        include($dir."add-user.php");
		    }
		    else
			{
				add_filter('the_content', 'st_mg_not_authorized');
			}
	    }

	    //View user page
	    if(is_page($valB)){ 
	    	if (has_user_access() === true)
			{
	      		$dir = plugin_dir_path( __FILE__ );
	        	include($dir."view-users.php");
	        }	
	        else
			{
				add_filter('the_content', 'st_mg_not_authorized');
			}	
	    }

	    //Edit users page
	    if(is_page($valC)){ 
	    	if (has_user_access() === true)
			{
		        $dir = plugin_dir_path( __FILE__ );
		        include($dir."edit-user.php");
		    }    
	        else
			{
				add_filter('the_content', 'st_mg_not_authorized');
			}
	    }	
		
	}

	add_action( 'wp', 'st_mg_get_user_page' );


	function st_mg_not_authorized($content) {
		
	    return $content = "Sorry! You are not allowed to access this page.";
    }



}
?>