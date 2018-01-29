<?php 

//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}


require_once 'wp-admin/includes/user.php';

// Register style sheet.
function register_plugin_styles() {
	wp_register_style( 'view-users', plugins_url( 'manage-users/public/css/view-users.css' ) );
	wp_enqueue_style( 'view-users' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );

// Applying filter to get content
add_filter( 'the_content', 'filter_the_content_in_the_main_loop' );
 
function filter_the_content_in_the_main_loop( $content ) {

if (isset($_GET['delete']))
{
	if (!isset($_GET['row_id'])) 
	{
		echo '<p class="error-msg">Select user to perform this action!</p>';
	}
	else 
	{
		foreach ($_GET['row_id'] as $users=>$id) 
		{
			$user_id = wp_delete_user( $id );

			if ( ! is_wp_error( $user_id ) ) {
		    	echo '<p class="success-msg">User delete, Successfuly!</p>';
			}
		}
	}
}

$content .= '
	<div id="Paris" class="tabcontent">
		<h3 class="page-heading">List of All Users</h3>
		<form method="get">
			<table id="example" class="table-content">
				<thead>
		            <tr>
		                <th>Name</th>
		                <th>Nicename</th>
		                <th>Email</th>
		                <th>Role</th>
		                <th>Select</th>
		            </tr>
		        </thead>
		        <tbody>
		        ';

		        $blogusers = get_users( array( 'fields' => array( 'display_name', 'user_email', 'user_pass', 'user_nicename', 'ID' ) ) );
		        // Getting page
		        $edit_page = get_option('st_mg_edit'); 
		        // Array of stdClass objects.
				foreach ( $blogusers as $user )
				{
					// Getting user role by ID 
					$user = new WP_User( $user->ID );
					wp_sprintf_l( '%l', $user->roles );

					$content .= '<tr>
						<td>'.$user->display_name.'</td>
						<td>'.$user->user_nicename.'</td>
						<td>'.$user->user_email.'</td><td>';
					//$content .= '<td>';
						//echo $user->roles[0];
					// $content .= '<td>';
						foreach ($user->roles as $key => $value)
						{ 
               				$content .= $value;	
               			}
               		$content .= '
               		</td><td>
	               		<label class="Check_Select">Select
							<input type="checkbox" value='.$user->ID.' name="row_id[]">
						</label>
						<button class="edit-button"><a href="'.get_template_directory_uri().'/'.$edit_page.'/?user_id='.$user->ID.'">Edit</a></button>
						<div style="clear: both;"></div>
					</td>
					</tr>';
				}

				$content .= '</tbody>
			</table>
			<input type="submit" name="delete" value="Delete" class="btn btn-default submit-button" style="width: 120px;">
		</form>
	</div>
	';
 
    return $content;
}

?>

