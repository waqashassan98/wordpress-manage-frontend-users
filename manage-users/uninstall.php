<?php
/*
	
	uninstall.php
	
	- fires when plugin is uninstalled via the Plugins screen
	
*/



//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}


// remove options on uninstall
function st_mg_on_uninstall() {

	if ( ! current_user_can( 'activate_plugins' ) ) return;

	delete_option( 'st_mg_add' );
	delete_option( 'st_mg_view' );
	delete_option( 'st_mg_edit' );
	delete_option( 'st_mg_plugin_levels' );

}
register_uninstall_hook( __FILE__, 'st_mg_on_uninstall' );

