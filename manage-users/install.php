<?php

//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// add option
function st_mg_add_option() {

	/*
		add_option(
			string      $option,
			mixed       $value = '',
			string      $deprecated = '',
			string|bool $autoload = 'yes'
		)
	*/

	$data = '0';

	add_option( 'st_mg_add', $data);
	add_option( 'st_mg_view', $data );
	add_option( 'st_mg_edit', $data );
	add_option( 'st_mg_plugin_levels', $data );
	// die();
}

?>