<?php // ST Manage Users - Frontend admin menu


//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}


// add top-level administrative menu
function st_mg_add_toplevel_menu() {
	
	/* 
		add_menu_page(
			string   $page_title, 
			string   $menu_title, 
			string   $capability, 
			string   $menu_slug, 
			callable $function = '', 
			string   $icon_url = '', 
			int      $position = null 
		)
	*/
	
	add_menu_page(
		'Manage User Settings',
		'Manage users',
		'manage_options',
		'st_mg',
		'st_mg_display_settings_page',
		'dashicons-admin-generic',
		null
	);
	
}
add_action( 'admin_menu', 'st_mg_add_toplevel_menu' );

?>