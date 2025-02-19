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
   include 'registration-form.php';
   return ob_get_clean();
}
add_shortcode( 'my_registration_form', 'my_registration_form' );





 






// REGISTRATION FORM HANDLING
function handle_vendor_registration() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_button'])) {
        // Sanitizeing  input data
        $full_name = sanitize_text_field($_POST['full_name']);
        $email = sanitize_email($_POST['email']); //SEPARATE EMAIL SENITIZER 
        $phone = sanitize_text_field($_POST['phone']);
        $business_name = sanitize_text_field($_POST['business_name']);
        $website = esc_url($_POST['website']);
        $service_location = sanitize_text_field($_POST['service_location']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

                // Basic validation
            
        // empty fields
        if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($service_location)) {
            wp_die('Error: All required fields must be filled.');
        }
        if (!is_email($email)) {
            wp_die('Error: Invalid email address.');
        }
        if (username_exists($email) || email_exists($email)) { //BOTH CHECKING EMAIL
            wp_die('Error: Email is already registered.');
        }
        if ($password !== $confirm_password) {
            wp_die('Error: Passwords do not match.');
        }

        // Create new user
        $user_id = wp_create_user($email, $password, $email); // i am using create not insert for better felxibility

        if (is_wp_error($user_id)) {
            wp_die('Error: User registration failed.');
        }

        // Assign vendor role
        wp_update_user([
            'ID' => $user_id,
            'role' => 'vendor',
            'display_name' => $full_name,
        ]);

        // STORING OTHER USER DETAILS USING META
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'business_name', $business_name);
        update_user_meta($user_id, 'website', $website);
        update_user_meta($user_id, 'service_location', $service_location);

        // Handle profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $uploaded = wp_handle_upload($_FILES['profile_picture'], ['test_form' => false]);

            if (isset($uploaded['file'])) {
                $attachment = [
                    'post_mime_type' => $uploaded['type'],
                    'post_title' => basename($uploaded['file']),
                    'post_content' => '',
                    'post_status' => 'inherit'
                ];
                $attach_id = wp_insert_attachment($attachment, $uploaded['file']);
                update_user_meta($user_id, 'profile_picture', $attach_id);
            }
        }

        // Redirect to login page or dashboard        
        wp_redirect(site-url()); //CUrretly redirecting to home page

        
        exit;
    }
}
add_action('init', 'handle_vendor_registration');
