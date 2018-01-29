<?php // ST Manage Users - Frontend settings page


//Exit if file called directly
if (! defined( 'ABSPATH' )) {
	exit;
}

// Including stylesheet for add user
wp_enqueue_style( 'st_mg', plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/st_mg.css', array(), null, 'screen' );

// display plugin setting page
function st_mg_display_settings_page()
{

	// check if user is allowed access
	if ( ! current_user_can( 'manage_options' ) ) return;


	// Getting page from user
	if (isset($_POST['get_page']))
	{
		
		$pageA = $_POST['page_a'];
		$pageB = $_POST['page_b'];
		$pageC = $_POST['page_c'];
		// echo $pageA;
		// echo $pageB;
		// echo $pageC;

		if ($pageA == 'empty')
		{
			echo '<h3 class="error-msg">Select Add User Page!</h3>';
		}
		else
		{
			// echo "add";
			if (update_option( 'st_mg_add', $pageA )) {
				echo '<h3 class="success-msg">Add User Page Successfuly Selected!</h3>';
			}
		}

		if ($pageB == 'empty')
		{
			echo '<h3 class="error-msg">Select View Users Page!</h3>';
		}
		else
		{
			// echo "view";
			if (update_option( 'st_mg_view', $pageB )){
				echo '<h3 class="success-msg">View Users Page Successfuly Selected!</h3>';
			}
		}

		if ($pageC == 'empty')
		{
			echo '<h3 class="error-msg">Select Edit User Page!</h3>';
		}
		else
		{
			// echo "edit";
			if (update_option( 'st_mg_edit', $pageC )) {
				echo '<h3 class="success-msg">Edit User Page Successfuly Selected!</h3>';
			}
		}
	}

?>
	<div class="wrap">
		
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		
		<h3 class="page-heading">Configuration :</h3>
		<p>Please create new pages for the listed functionalities and select them below</p>
		
		<div class="form-area">
			<form method="post">
				
				<?php 
					$add  = get_option( 'st_mg_add' );
					$edit = get_option( 'st_mg_edit' ); 
					$view = get_option( 'st_mg_view' );


					$page_ids = get_all_page_ids();
					//print_r($page_ids);
					// echo '<h3 class="page-heading">My Page List :</h3>';
					
					// First dropdown
					echo '<strong>Select Page to add Users</strong>';
					echo '<br>';
					echo '<select name="page_a" class="user-levels" style="width: 320px; margin-right: 10px" required>'; 
					echo '<option value="empty">Select Page</option>';
					foreach($page_ids as $key=>$page)
					{
						$page_id = get_the_title($page);

						// exact page slug
						$url = str_replace(' ', '-', $page_id);

						// Make the option selected by comparing it with option
						if ($add == strtolower($url)) {
							echo '<option value='.strtolower($url).' selected>'.get_the_title($page).'</option>';
						}
						else {
							echo '<option value='.strtolower($url).'>'.get_the_title($page).'</option>';
						}
					}
					echo '</select>';
					echo '<br>';

					// Second dropdown
					echo '<strong>Select Page to list Users</strong>';
					echo '<br>';
					echo '<select name="page_b" class="user-levels" style="width: 320px; margin-right: 10px" required>';
					echo '<option value="empty">Select Page</option>';
					foreach($page_ids as $page)
					{
						$page_id = get_the_title($page);
						// exact page slug
						$url = str_replace(' ', '-', $page_id);
						
						// Make the option selected by comparing it with option
						if ($view == strtolower($url)) {
							echo '<option value='.strtolower($url).' selected>'.get_the_title($page).'</option>';
						}
						else {
							echo '<option value='.strtolower($url).'>'.get_the_title($page).'</option>';
						}
					}
					echo '</select>';
					echo '<br>';

					// Third dropdown
					echo '<strong>Select Page to Edit Users</strong>';
					echo '<br>';
					echo '<select name="page_c" class="user-levels" style="width: 320px" required>';
					echo '<option value="empty">Select Page</option>';
					foreach($page_ids as $page)
					{
						$page_id = get_the_title($page);
						// exact page slug
						$url = str_replace(' ', '-', $page_id);
						
						// Make the option selected by comparing it with option
						if ($edit == strtolower($url)) {
							echo '<option value='.strtolower($url).' selected>'.get_the_title($page).'</option>';
						}
						else {
							echo '<option value='.strtolower($url).'>'.get_the_title($page).'</option>';
						}
					}
					echo '</select>';
					echo '<br>';
				?>
					
				<input type="submit" name="get_page" value="GO"  class="submit-button">
			</form>
		</div>
		
		<div class="levels-area">
			<?php
				// Getting level
				if (isset($_POST['get_levels']))
				{
					$user_levels = '';
					
					$levels = $_POST['selected_levels'];
					
					if (empty($levels)) {
						echo '<p class="error-msg">Select a level.</p>';
					}
					else
					{
						foreach ( $levels as $key=>$value ):
							$user_levels .= $value.",";
						endforeach;	
					}

					// Creating levels for users
					if (update_option( 'st_mg_plugin_levels', $user_levels))
					{
						echo '<p class="success-msg">Levels Added!</p>';
					}
				}
			?>
			<form method="post">
				<div class="levels-content">
					<p class="tab-heading">Select the Permissions for this Functionality at frontend:</p>
					<small>Required for the plugin to work properly</small>
				<?php
					$levels = get_option('st_mg_plugin_levels');

					// Splitting array to isolate levels
					$pre_levels = explode(",", $levels);
					
		    		global $wp_roles;
					foreach ( $wp_roles->roles as $key=>$value ):
						if ( in_array($value['name'], $pre_levels) )
						{
				?>
							<input type="checkbox" name="selected_levels[]" value="<?php echo $value['name']; ?>" checked> <?php echo '<span>'.$value['name'].'</span>'; ?>
							<br />
				<?php
						}
						else
						{
				?>
							<input type="checkbox" name="selected_levels[]" value="<?php echo $value['name']; ?>"> <?php echo '<span>'.$value['name'].'</span>'; ?>
							<br />
				<?php
						}
					endforeach;
				?>
					<input type="submit" name="get_levels" value="Set Levels"  class="submit-button" style="padding: 5px 12px;">
				</div>
			</form>
			
		</div>
		<div style="clear: both;"></div>	

	</div>

<?php
	
}

