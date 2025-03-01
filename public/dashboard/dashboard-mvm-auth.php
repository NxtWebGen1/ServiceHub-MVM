<?php
if (!function_exists('wp_get_current_user')) {
    require_once ABSPATH . 'wp-includes/pluggable.php';
}


// VENDOR ADMIN MENU FOR VENDOR USER CONTAINIGN PROFILE AD SERVICES SECTION
function vendor_menu() {
    add_menu_page(
        'Vendor Dashboard', // Page Title
        'Vendor Dashboard', // Menu Title
        'read', // only cendor should see this
        'vendor-dashboard', //  Slug
        'vendor_dashboard_menu', // Callback  Function
        'dashicons-store', // Icon       
    );
    include 'dashboard-home.php';

}
add_action('admin_menu', 'vendor_menu');
















// HANDLING VENDOT PROFILE FORM CHANGES/UPDATE

// SANITIZING FIELDS
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['profile_change'])) {

    global $wpdb;
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID; // DEFINING AT TOP TO NOT HAVE UNDEFINE ERROR

    $phone = sanitize_text_field($_POST['phone']);
    $website = sanitize_text_field($_POST['website']);
    $service_location = sanitize_text_field($_POST['service_location']);
    $business_name = sanitize_text_field($_POST['business_name']);
    


    

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize an array to store error messages
    $error_messages = array();

    // FIELDS SHOULD NOT BE EMPTY
    if ( empty($phone) || empty($service_location) ) {
        $error_messages[] = 'ERROR: Full Name, Phone, and Service Location are required.';
    }

    // IF USER WANTS TO CHANGE THE PASSWORD, HE MUST ENTER THE OLD ONE 
    if (!empty($new_password) || !empty($confirm_password) || !empty($old_password)) {

        // If any password field is empty
        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            $error_messages[] = 'Error: Please fill in all password fields to change your password.';
        } 
        elseif ($new_password !== $confirm_password) {
            $error_messages[] = 'Error: New password and confirm password do not match.';
        } 
        elseif(
                $new_password  == $old_password ) //php dosnt support three-way assigning equality
        {
            $error_messages[] = 'Error:NEW PASSWORD CANNOT BE OLD ONE .';

        }
        else {
            // Retrieve the hashed/Encrypted password from the database
            $hashed_password = $wpdb->get_var($wpdb->prepare(
                "SELECT user_pass FROM {$wpdb->users} WHERE ID = %d",
                $user_id
            ));

            // Check if OLD password is correct
            if (!wp_check_password($old_password, $hashed_password, $user_id)) {
                $error_messages[] = 'Error: Incorrect old password.';
            }
        }
    }

     // NO execution if theres any errors
    if (!empty($error_messages)) {
        wp_die(implode('<br>', $error_messages));
    }

    //  UPDATE USER META DATA IN DB
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'website', $website);
    update_user_meta($user_id, 'service_location', $service_location);
    update_user_meta($user_id, 'business_name', $business_name);


    //FILE HANDLING
    if (!empty($_FILES['profile_picture']['name'])) { 
                // THESE ARE WP FILES THAT INACTIVE DEFAULT BUT HELPS TO HANDLE FILE THINGS 
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $upload = media_handle_upload('profile_picture', 0); //handles the file upload process in WordPress. 0REPRESENTS NO POST ATACHED
        if (!is_wp_error($upload)) {
            $profile_picture = wp_get_attachment_url($upload);
            update_user_meta($user_id, 'profile_picture', $profile_picture);
        }
    }



    //  If user entered a new password and old password is verified, update the password
    if (!empty($new_password) && !empty($confirm_password) && wp_check_password($old_password, $hashed_password, $user_id)) {
        wp_set_password($new_password, $user_id);
        echo "Password successfully updated!";
    }

    echo "Profile updated successfully!";
}









