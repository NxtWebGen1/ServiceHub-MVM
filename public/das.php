<?php 







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
            
          // Initialize an array to store error messages
          $error_messages = array();

          // Check all conditions and collect errors
          if (empty($full_name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($service_location)) {
              $error_messages[] = 'Error: All required fields must be filled.';
          }
  
          if (!is_email($email)) {
              $error_messages[] = 'Error: Invalid email address.';
          }
              //separate suer name existance condition
          if (username_exists($full_name)) {
              $error_messages[] = 'Error: Username already exists. Try a different name.';
          }
  
          if (email_exists($email)  ){
              $error_messages[] = 'Error: Email is already registered.';
          }
  
          if ($password !== $confirm_password) {
              $error_messages[] = 'Error: Passwords do not match.';
          }
  
          // If there are any errors, display them
          if (!empty($error_messages)) {
              wp_die(implode('<br>', $error_messages));
          }


    // Create new user
    $user_data = array(
        'user_login'   => $full_name,  // Consider generating a unique username if needed
        'user_email'   => $email,
        'user_pass'    => $password,
        'display_name' => $full_name
    );
    $user_id = wp_insert_user($user_data);

                // CHECKING IF THE USER ALREADY THERE IN DB
    if (is_wp_error($user_id)) {
        wp_die('Error: Could not create user. ' . $user_id->get_error_message());
    }

            // Assign vendor role
            wp_update_user([
                'ID' => $user_id,
                'role' => 'vendor',
                'display_name' => $full_name,
                'user_nicename' =>$full_name //We can just add the nick name here 
            ]);

            // STORING aLL  USER DETAILS 
            update_user_meta($user_id, 'phone', $phone);
            update_user_meta($user_id, 'business_name', $business_name);
            update_user_meta($user_id, 'website', $website);
            update_user_meta($user_id, 'service_location', $service_location);




        // Check if a profile picture file has been uploaded
    if (!empty($_FILES['profile_picture']['name'])) { 

        // Include WordPress file handling functions
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        // Handle the file upload, disabling form test validation
        $uploaded = wp_handle_upload($_FILES['profile_picture'], ['test_form' => false]);

        // Check if the file was successfully uploaded
        if (isset($uploaded['file'])) { 

            // Prepare attachment details for the uploaded file
            $attachment = [
                'post_mime_type' => $uploaded['type'], // Set the file MIME type
                'post_title' => basename($uploaded['file']), // Set the attachment title as the file name
                'post_content' => '', // No content needed for the attachment
                'post_status' => 'inherit' // Inherit status from the parent (typically 'publish')
            ];

            // Insert the attachment into the WordPress database and get its ID
            $attach_id = wp_insert_attachment($attachment, $uploaded['file']); 

                // Retrieve the URL of the uploaded image
            $image_url = wp_get_attachment_url($attach_id);   //THIS WILL HELP TO GET THE URL

            // Store the URL instead of just the ID
            update_user_meta($user_id, 'profile_picture', $image_url);

        }
    }


            // Redirect to login page     
            wp_redirect(get_permalink(get_page_by_path('vendor-login')));
            exit;
        }
}
add_action('init', 'handle_vendor_registration');





