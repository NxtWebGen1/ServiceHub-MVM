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
                wp_redirect(admin_url('admin.php?page=vendor-dashboard')); 
                exit;

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
   function vendor_login_form(){
      ob_start();
      include 'login-form.php';
      return ob_get_clean();
   }
   add_shortcode( 'vendor_login_form', 'vendor_login_form' );



   
 
//CREATE SHORTCODE OF REGISTER FORM FOR NEW USER
    function vendor_registration_form(){
        ob_start();   
        include 'registration-form.php';
        return ob_get_clean();
    }
    add_shortcode( 'vendor_registration_form', 'vendor_registration_form' );



// ENQUEUE STYLE
    function login_style()
    {
        $path_style = plugins_url('css\login.css', __FILE__);

        wp_enqueue_style('login_style', $path_style,);
    }
    add_action('wp_enqueue_scripts', 'login_style');


 





// REGISTRATION FORM HANDLING
function handle_vendor_registration() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_button'])) {
        // Sanitize input data
        $full_name = sanitize_text_field($_POST['full_name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $business_name = sanitize_text_field($_POST['business_name']);
        $website = esc_url($_POST['website']);
        $service_location = sanitize_text_field($_POST['service_location']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate input fields
        $errors = [];

        if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($service_location)) {
            $errors[] = 'Error: All required fields must be filled.';
        }

        if (!is_email($email)) {
            $errors[] = 'Error: Invalid email address.';
        }

        if (email_exists($email)) {
            $errors[] = 'Error: Email is already registered.';
        }

        if ($password !== $confirm_password) {
            $errors[] = 'Error: Passwords do not match.';
        }

        if (!empty($errors)) {
            wp_die(implode('<br>', $errors));
        }

        // Create new user with 'subscriber' role (temporary until approved)
        $user_id = wp_insert_user([
            'user_login'   => sanitize_user($full_name, true), // Ensure valid username
            'user_email'   => $email,
            'user_pass'    => $password,
            'display_name' => $full_name,
            'role'         => 'subscriber' // Vendor gets 'subscriber' role until approved
        ]);

        if (is_wp_error($user_id)) {
            wp_die('Error: Could not create user. ' . $user_id->get_error_message());
        }

        // Store vendor details & approval status
        update_user_meta($user_id, '_vendor_approval_status', 'pending');
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'business_name', $business_name);
        update_user_meta($user_id, 'website', $website);
        update_user_meta($user_id, 'service_location', $service_location);

        // ✅ Check if a profile picture file has been uploaded
        if (!empty($_FILES['profile_picture']['name'])) { 
            require_once(ABSPATH . 'wp-admin/includes/file.php'); // Include WP file handling functions

            $uploaded = wp_handle_upload($_FILES['profile_picture'], ['test_form' => false]);

            if (isset($uploaded['file'])) { 
                $attachment = [
                    'post_mime_type' => $uploaded['type'],
                    'post_title'     => basename($uploaded['file']),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                ];

                $attach_id = wp_insert_attachment($attachment, $uploaded['file']); 
                $image_url = wp_get_attachment_url($attach_id);  

                update_user_meta($user_id, 'profile_picture', $image_url);
            }
        }

        // ✅ Notify Admin
        $admin_email = get_option('admin_email');
        $subject = "New Vendor Registration Approval Needed";
        $message = "A new vendor has registered and requires approval.\n\n".
                   "Vendor Name: $full_name\n".
                   "Email: $email\n\n".
                   "Approve or Reject via Users > All Users in WP Dashboard.";

        wp_mail($admin_email, $subject, $message);

        // ✅ Redirect vendor to waiting page
        wp_redirect(home_url('/vendor-registration-pending')); 
        exit;
    }
}
add_action('init', 'handle_vendor_registration');




//DASHBOARD MAIN AUTH FILE
include 'dashboard/dashboard-mvm-auth.php';








