<?php


//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// Register style sheet.
function st_mg_register_plugin_styles() {
	wp_register_style( 'view-users', plugins_url( 'manage-users/public/css/add-user.css' ) );
	wp_enqueue_style( 'view-users' );
}

// Register style sheet.
add_action( 'wp_enqueue_scripts', 'st_mg_register_plugin_styles' );

// Applying filter to get content
add_filter( 'the_content', 'st_mg_filter_the_content_in_the_main_loop' );
 
function st_mg_filter_the_content_in_the_main_loop( $content )
{

	// Add user
	if (isset($_POST['add_n_user'])) 
	{
		if (!empty($_POST['username']) && !empty($_POST['femail']) && !empty($_POST['fpassword']) && !empty($_POST['role']) && $_POST['role'] != '0' && !empty($_POST['first-name']) && !empty($_POST['last-name']))
		{
			
			$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
			$role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);
			$first_name = filter_var($_POST['first-name'], FILTER_SANITIZE_STRING);
			$last_name = filter_var($_POST['last-name'], FILTER_SANITIZE_STRING);
			$password = $_POST['fpassword'];

			// username name length
			if (strlen($username) <= 2){
				echo '<p class="error-msg">Username is too short.</p>';
			}

			// First name
			if (strlen($first_name) <= 2){
				echo '<p class="error-msg">First name is too short.</p>';
			}

			// Check if first name contains number or other characters
			if( preg_match('~[0-9]~', $first_name) || preg_match('/[^a-zA-Z]+/', $first_name) )
			{
				echo '<p class="error-msg">Shot or Invalid last name.</p>';
			}

			// First name
			if (strlen($last_name) <= 2){
				echo '<p class="error-msg">Last name is too short.</p>';
			}

			// Check if first name contains number or other characters
			if( preg_match('~[0-9]~', $last_name) || preg_match('/[^a-zA-Z]+/', $last_name) )
			{
				echo '<p class="error-msg">Shot or Invalid last name.</p>';
			}

			// Valid email
			if (!filter_var($_POST['femail'], FILTER_VALIDATE_EMAIL)) {
			    echo '<p class="error-msg">Email is not valid.</p>';
			}
			else
			{
				$email = filter_var($_POST['femail'], FILTER_VALIDATE_EMAIL);
			}

			if (strlen($password) <= 5){
			  // user has specified first and last name
				echo '<p class="error-msg">Password is too short.</p>';
			}

			if(isset($username) && isset($first_name) && isset($last_name) && isset($password) && isset($role) && isset($email))
			{

				// Check if email already exist
				if ( email_exists( $email ) || username_exists( $username ))
				{
					echo '<p class="error-msg">Either username or email is already exist! Try another.</p>';
				}
				else
				{
					// Insert user
					$userdata = array(
				    'user_nicename' =>  $username,
				    'user_login'   =>  $username,
				    'first_name' =>	$first_name,
				    'last_name' =>  $last_name,
				    'user_email' => $email,
				    'role' => $role,
				    'user_pass'  =>  $password
					);
					 
					// print_r($userdata);
					$user_id = wp_insert_user( $userdata ) ;
					
					// On success.
					if ( ! is_wp_error( $user_id ) ) {

					   echo '<p class="success-msg">New user created : '.$user_id.'</p>';
					}
				}	
			}
		}
		else
		{
			echo '<p class="error-msg">Please, Fill all the given fields.</p>';
		}
	}

	$content .= '
				<div class="tabcontent" >
					<h3 class="page-heading">Add New User</h3>
					<div class="insert-content">
						<h3 class="form-heading">Enter Details:</h3>
						<form method="post">

							<div class="form-group">  
					    	  <label for="name">Username <sub>(required)</sub></label>
						      <input type="text"  name="username" placeholder="username..." required="" id="username" class="fields" />
						    </div>

						    <div class="form-group">   
						      <label for="name">Fist Name <sub>(required)</sub></label>
						      <input type="text" name="first-name" placeholder="First name..." required="" id="full_name" class="fields"  />
						    </div>

						    <div class="form-group">   
						      <label for="name">Last Name <sub>(required)</sub></label>
						      <input type="text" name="last-name" placeholder="Last name..." required="" id="full_name" class="fields"  />
						    </div>

						    <div class="form-group">
						      <label for="email">Email <sub>(required)</sub></label>
						      <input type="email" name="femail" placeholder="Your email..." required="" id="email" class="fields" />
						    </div>

						    <div class="form-group">
						      <label for="password">Password <sub>(required)</sub></label>
						      <input type="password" id="password" name="fpassword" placeholder="Your password..." required="" class="fields" />
						    </div>

						    <div class="form-group">
							    <label for="role">User Role <sub>(required)</sub></label>
							   	<select name="role" id="role" class="user-levels">
							   	<option value="0">Select Role</option>
											
				';
								global $wp_roles;
								foreach ( $wp_roles->roles as $key=>$value ): 
									$content .=	'<option value="'.$key.'">'.$value['name'].'</option>';
								endforeach;
	$content .= '				</select>
							</div>

							<div class="form-group">
						      <input type="submit" value="Submit" name="add_n_user" id="add_user" class="submit-button" />
						    </div>

						</form>
					</div>
				</div>
				';
	return $content;
}

?>