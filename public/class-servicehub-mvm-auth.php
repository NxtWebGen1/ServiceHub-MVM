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
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register_button'])) {

        // Sanitize & gather input
        $full_name         = sanitize_text_field($_POST['full_name']);
        $email             = sanitize_email($_POST['email']);
        $phone             = sanitize_text_field($_POST['phone']);
        $password          = $_POST['password'];
        $confirm_password  = $_POST['confirm_password'];

        $business_name     = sanitize_text_field($_POST['business_name']);
        $business_category = sanitize_text_field($_POST['business_category']);
        $years_in_business = sanitize_text_field($_POST['years_in_business']);
        $website           = esc_url($_POST['website']);
        $social_links      = sanitize_text_field($_POST['social_links']);
        $service_location  = sanitize_text_field($_POST['service_location']);
        $street_address    = sanitize_text_field($_POST['street_address']);
        $service_radius    = sanitize_text_field($_POST['service_radius']);
        $gender            = sanitize_text_field($_POST['gender']);
        $emergency_contact = sanitize_text_field($_POST['emergency_contact']);

      


        $errors = [];

        //  Validate required fields
        if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
            $errors[] = 'All personal fields are required.';
        }
        if (empty($business_name) || empty($business_category)) {
            $errors[] = 'Business Name and Category are required.';
        }
        if (empty($service_location)) {
            $errors[] = 'City / Service Location is required.';
        }
        if (empty($gender)) {
            $errors[] = 'Gender is required.';
        }
        if (!is_email($email)) {
            $errors[] = 'Invalid email format.';
        }
        if (email_exists($email)) {
            $errors[] = 'Email is already registered.';
        }
        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        }
        

        if (!empty($errors)) {
            wp_die(implode('<br>', $errors));
        }

        //  Create the user
        $user_id = wp_insert_user([
            'user_login'   => sanitize_user($full_name, true),
            'user_email'   => $email,
            'user_pass'    => $password,
            'display_name' => $full_name,
            'role'         => 'subscriber',
        ]);

        if (is_wp_error($user_id)) {
            wp_die('User creation failed: ' . $user_id->get_error_message());
        }

        //  Store meta fields
        update_user_meta($user_id, '_vendor_approval_status', 'pending');
        update_user_meta($user_id, 'phone', $phone);
        update_user_meta($user_id, 'business_name', $business_name);
        update_user_meta($user_id, 'business_category', $business_category);
        update_user_meta($user_id, 'years_in_business', $years_in_business);
        update_user_meta($user_id, 'website', $website);
        update_user_meta($user_id, 'social_links', $social_links);
        update_user_meta($user_id, 'service_location', $service_location);
        update_user_meta($user_id, 'street_address', $street_address);
        update_user_meta($user_id, 'service_radius', $service_radius);
        update_user_meta($user_id, 'gender', $gender);
        update_user_meta($user_id, 'emergency_contact', $emergency_contact);

        //  Handle File Uploads
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        // Profile Picture
        if (!empty($_FILES['profile_picture']['name'])) {
            $upload = wp_handle_upload($_FILES['profile_picture'], ['test_form' => false]);
            if (!isset($upload['error'])) {
                $attachment_id = wp_insert_attachment([
                    'post_mime_type' => $upload['type'],
                    'post_title'     => basename($upload['file']),
                    'post_status'    => 'inherit'
                ], $upload['file']);
                update_user_meta($user_id, 'profile_picture', wp_get_attachment_url($attachment_id));
            }
        }

        // Portfolio Upload
        if (!empty($_FILES['portfolio_upload']['name'])) {
            $upload = wp_handle_upload($_FILES['portfolio_upload'], ['test_form' => false]);
            if (!isset($upload['error'])) {
                $attachment_id = wp_insert_attachment([
                    'post_mime_type' => $upload['type'],
                    'post_title'     => basename($upload['file']),
                    'post_status'    => 'inherit'
                ], $upload['file']);
                update_user_meta($user_id, 'portfolio_upload', wp_get_attachment_url($attachment_id));
            }
        }

        // National ID
        if (!empty($_FILES['national_id']['name'])) {
            $upload = wp_handle_upload($_FILES['national_id'], ['test_form' => false]);
            if (!isset($upload['error'])) {
                $attachment_id = wp_insert_attachment([
                    'post_mime_type' => $upload['type'],
                    'post_title'     => basename($upload['file']),
                    'post_status'    => 'inherit'
                ], $upload['file']);
                update_user_meta($user_id, 'national_id', wp_get_attachment_url($attachment_id));
            }
        }

        //  Notify Admin
        $admin_email = get_option('admin_email');
        $subject = "New Vendor Registration – Approval Needed";
        $message = "A new vendor has registered:\n\n" .
                   "Name: $full_name\nEmail: $email\nBusiness: $business_name\n\n" .
                   "Login to review and approve.";

        wp_mail($admin_email, $subject, $message);

        //  Redirect
        wp_redirect(home_url('/vendor-registration-pending'));
        exit;
    }
}
add_action('init', 'handle_vendor_registration');





//DASHBOARD MAIN AUTH FILE
include 'dashboard/dashboard-mvm-auth.php';








