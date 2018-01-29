<?php

//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// Register style sheet.
function st_mg_register_plugin_styles() {
	// CSS
	wp_register_style( 'edit-user', plugins_url( 'manage-users/public/css/add-user.css' ) );
	wp_enqueue_style( 'edit-user' );

	// Script
	wp_register_script( 'edit-user-js', plugins_url( 'manage-users/public/js/edit-user.js' ) );
	wp_enqueue_script( 'edit-user-js' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'st_mg_register_plugin_styles' );


// Applying filter to get content
add_filter( 'the_content', 'st_mg_filter_the_content_in_the_main_loop' );
 
function st_mg_filter_the_content_in_the_main_loop( $content )
{
	//Update user
	if (isset($_POST['update_user']))
	{
		
		if (!empty($_POST['nicename']) && !empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['user_email']) && !empty($_POST['user_role']) )
		{
			
			$nicename = filter_var($_POST['nicename'], FILTER_SANITIZE_STRING);
			$first_name = filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
			$last_name = filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
			$role = filter_var($_POST['user_role'], FILTER_SANITIZE_STRING);


			// username name length
			if (strlen($nicename) <= 2){
				echo '<p class="error-msg">Username is too short.</p>';
			}

			// First name
			if (strlen($first_name) <= 2){
				echo '<p class="error-msg">First name is too short.</p>';
			}

			// Check if first name contains number or other characters
			if( preg_match('~[0-9]~', $first_name) || preg_match('/[^a-zA-Z]+/', $first_name) )
			{
				echo '<p class="error-msg">Shot or Invalid first name.</p>';
			}

			// Last name
			if (strlen($last_name) <= 2){
				echo '<p class="error-msg">Last name is too short.</p>';
			}

			// Check if last name contains number or other characters
			if( preg_match('~[0-9]~', $last_name) || preg_match('/[^a-zA-Z]+/', $last_name) )
			{
				echo '<p class="error-msg">Shot or Invalid last name.</p>';
			}
			

			// Valid email
			if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
			    echo '<p class="error-msg">Email is not valid.</p>';
			}
			else
			{
				$email = filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL);
			}

			if (isset($nicename) && isset($first_name) && isset($last_name) && isset($role))
			{
				
				// If Password field is enabled
				if (isset($_POST['update_pass']))
				{
					//echo $pass = $_POST['user_password'];
					if (strlen($_POST['user_password']) <= 5)
					{
						echo '<p class="error-msg">Password is too weak.</p>';
					}
					else
					{
						$userdata = array (
							'ID' => $_GET['user_id'],
						    'user_nicename' =>  $nicename,
						    'first_name'   =>  $first_name,
					    	'last_name'	=> $last_name,
						    'user_email' => $email,
						    'user_pass'  => $pass,
						    'role' => $role,
						);

						//print_r($userdata);

						$update = wp_update_user( $userdata );

						if ( ! is_wp_error( $update ) ) {
						    echo '<p class="success-msg">User updated successfuly!</p>';
						}
					}	
				}
				else
				{
					//echo "empty pass";
					$userdata = array (
						'ID' => $_GET['user_id'],
					    'user_nicename' =>  $nicename,
					    'first_name'   =>  $first_name,
					    'last_name'	=> $last_name,
					    'user_email' => $email,
					    'role' => $role,
					);

					//print_r($userdata);
					$update = wp_update_user( $userdata );

					if ( ! is_wp_error( $update ) ) {
					    echo '<p class="success-msg">User updated successfuly!</p>';
					}
				}	
			}
		}
		else
		{
			echo '<p class="error-msg">Please, Fill all the given fields.</p>';
		}
		
		// if (isset($_POST['update_pass']))
		// {
		// 	//echo "password not empty";
		// 	$pass = $_POST['user_password'];
			
		// 	$userdata = array (
		// 		'ID' => $_GET['user_id'],
		// 	    'user_nicename' =>  $nicename,
		// 	    'user_login'   =>  $login_name,
		// 	    'user_email' => $email,
		// 	    'user_pass'  => $pass,
		// 	    'role' => $role,
		// 	);

		// 	$update = wp_update_user( $userdata );

		// 	if ( ! is_wp_error( $update ) ) {
		// 	    echo '<p class="success-msg">User updated successfuly!</p>';
		// 	}
		// }
		// else
		// {
		// 	//echo "empty pass";
		// 	$userdata = array (
		// 		'ID' => $_GET['user_id'],
		// 	    'user_nicename' =>  $nicename,
		// 	    'user_login'   =>  $login_name,
		// 	    'user_email' => $email,
		// 	    'role' => $role,
		// 	);

		// 	$update = wp_update_user( $userdata );

		// 	if ( ! is_wp_error( $update ) ) {
		// 	    echo '<p class="success-msg">User updated successfuly!</p>';
		// 	}
		// }	
	}

	//Getting user 
	$user = get_user_by( 'id', $_GET['user_id'] );

	// Getting user role by id
	$user_meta = get_userdata($_GET['user_id']);
	$user_roles = $user_meta->roles;

	//Converting first alphabet in upper case
	$role = ucfirst($user_roles[0]);

	$nicename = $user->user_nicename;
	$login_name = $user->user_login;
	$first_name = $user->first_name;
	$last_name = $user->last_name;
	$email = $user->user_email;


	// if ( empty( $user ) ) {
	// 	echo "User is not found";
	// }

	$content .= '
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-heading">Edit User:</h3>
				<div class="insert-content">
					<h3 class="form-heading">Update User:</h3>

					<form method="post" class="input-form">
						
						<div class="form-group">
				    		<label for="name">Username <sub>Cannot change!</sub></label>
					    	<input type="text"  name="username" placeholder="username.." required="" id="username" class="fields" value="'.$login_name.'" disabled />
					    </div>

					    <div class="form-group">
						    <label for="name">Nicename</label>
						    <input type="text" name="nicename" placeholder="Your name.." required="" id="full_name" class="fields" value="'.$nicename.'"  />
						</div>

						<div class="form-group">
						    <label for="name">First name</label>
						    <input type="text" name="first_name" placeholder="Your name.." required="" id="full_name" class="fields" value="'.$first_name.'"  />
						</div>

						<div class="form-group">
						    <label for="name">Last name</label>
						    <input type="text" name="last_name" placeholder="Your name.." required="" id="full_name" class="fields" value="'.$last_name.'"  />
						</div>

						<div class="form-group">
						    <label for="email">Email</label>
						    <input type="text" name="user_email" placeholder="Your email.." required="" id="email" class="fields" value="'.$email.'" />
						</div>

						<div class="form-group">
							<input type="checkbox" id="show_password" value="pass" name="update_pass"> <span><small>Update password ?</small></span>
						</div>   
						
						<div class="form-group" id="password-content">
						    <label for="password">Password</label>
						    <input type="password" id="password" name="user_password" disabled placeholder="Your password.." required="" class="fields" />
						</div>  

						<div class="form-group">
						    <label for="role">User Role</label>
						    <select name="user_role" id="role" class="fields" required>
	';
							global $wp_roles;
							foreach ( $wp_roles->roles as $key=>$value ):
								if ($value['name'] == $role) 
								{
									$content .= '<option value="'.$key.'" selected>'.$value['name'].'</option>';
								}
								else
								{
									$content .= '<option value="'.$key.'">'.$value['name'].'</option>';
								}
							endforeach;
	$content .= '			</select>
						</div>

						<div class="form-group">
			    			<input type="submit" value="Update" name="update_user" id="add_user" class="submit-button" />
			    		</div>	
					</form>
				</div>
			</div>
		</div>
	</div>';

	return $content;
}
	
?>
