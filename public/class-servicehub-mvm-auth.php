<?php 


// LOGIN FORM HANDLING - ONLY FOR VENDORS
add_action('init', 'servicehub_mvm_handle_vendor_login');

function servicehub_mvm_handle_vendor_login() {
    if (isset($_POST['loginbutton'])) {
        // Sanitize user input
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);

        // Validate input fields
        if (empty($username) || empty($password)) {
            wp_die('Both username and password are required.');
        }

        // Attempt login
        $credentials = array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => true,
        );

        $user = wp_signon($credentials, false);

        // Check if login was successful
        if (is_wp_error($user)) {
            wp_die($user->get_error_message()); // Display error message
        } else {
            // Get the user's role
            $user_info = get_userdata($user->ID);
            $roles = (array) $user_info->roles;

            // Check if the user is a vendor
            if (in_array('vendor', $roles)) {
                // Redirect to vendor dashboard
                wp_redirect(home_url('/registration-page')); // Change this URL
                exit;
            } else {
                // If not a vendor, log them out and show error
                wp_logout();
                wp_die('Only vendors can log in from this form.');
            }
        }
    }
}
   //CREATE SHORTCODE OF LOGIN FORM FOR NEW USER
   function my_login_form(){
      ob_start();
      include 'login-form.php';
      return ob_get_clean();
   }
   add_shortcode( 'my_login_form', 'my_login_form' );







 
 //CREATE SHORTCODE OF REGISTER FORM FOR NEW USER
 function my_registration_form(){
   ob_start();
   echo " chal gaya!"; 
   include plugin_dir_path(__FILE__) . 'public\registration-form.php';

   return ob_get_clean();
}
add_shortcode( 'my_registration_form', 'my_registration_form' );