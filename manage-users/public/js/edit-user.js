// Change input type hidden to 
jQuery( document ).ready(function() {

	jQuery('#show_password').click(function(){
		if (jQuery("#show_password").is(':checked')) 
		{
		    document.getElementById("password").disabled = false;

		} 
		else 
		{
		   document.getElementById("password").disabled = true;
		}
	});
});	