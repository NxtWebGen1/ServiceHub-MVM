<?php

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





// Enqueue Bootstrap CSS
function vendor_dashboard_enqueue_styles() {
    // Bootstrap CDN link
    wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' );
}

add_action( 'wp_enqueue_scripts', 'vendor_dashboard_enqueue_styles' );












// HANDLING PROFILE FORM CHANGES
// SANITIZING FIELDS
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['profile_change'])) {
//     // $user_id_from_form = $_POST['user_id_from_form';]
//     $full_name = sanitize_text_field($_POST['full_name']);
//     $phone = sanitize_text_field($_POST['phone']);
//     $website = sanitize_text_field($_POST['website']);
//     $service_location = sanitize_text_field($_POST['service_location']);
//     $old_password = $_POST['old_password'];
//     $new_password = $_POST['new_password'];
//     $confirm_password = $_POST['confirm_password'];

    
//     // Initialize an array to store error messages
//     $error_messages = array();

    
//         // FIELDS SHOULDNT BE EMPTY
//     if (empty($full_name) || empty($phone) || empty($service_location)) {
//         $error_messages[] = 'ERRORSS: Full Name, Phone, and Service Location are required.';
//     }

//         // IF USER WANT TO CHANGE THE PASSWORD, HE MUST ENTER OLD ONE 
//         // TO CHANGE PASSWORD, ENTER ALL 3 PASSWORD FIELDS
//     if (!empty($new_password) || !empty($confirm_password) || !empty($old_password)) {
       

//         //if any password os empty
//         if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
//             $error_messages[] = 'Error: Please fill in all password fields to change your password.';
//         } 
//         elseif ($new_password !== $confirm_password) {
//             $error_messages[] = 'Error: New password and confirm password do not match.';
//         }
//          else {
//             // Verify old password prensent in DB
//             global $wpdb;
//             $current_user = wp_get_current_user();
//             $user_id = $current_user->ID;

//             // Retrieve the hashed password from the database
//             $hashed_password = $wpdb->get_var($wpdb->prepare( //get_var() helps to retrieve a single column data(faster);  prepare helps to run SQL query securely 
//                 "SELECT user_pass FROM {$wpdb->users} WHERE ID = %d", //%d is just for placeholding of id 
//                 $user_id
//             ));

//             // Check if old password is correct
//             if (!wp_check_password($old_password, $hashed_password, $user_id)) {
//                 $error_messages[] = 'Error: Incorrect old password.';
//             }
//         }
//     }

//         // diw it if there is any error
//     if (!empty($error_messages)) {
//         wp_die(implode('<br>', $error_messages));
//     }

//     //LET'S UPDATE USER META DATA IN db
//     update_user_meta($user_id, 'phone', $phone);
//     update_user_meta($user_id, 'website', $website);
//     update_user_meta($user_id, 'service_location', $service_location);

//     // If user entered a new password and old password is verified, update the password
//     if (!empty($new_password) && !empty($confirm_password) && wp_check_password($old_password, $hashed_password, $user_id)) {
//         wp_set_password($new_password, $user_id);
//         echo "Password successfully updated!";
//     }

//     echo "Profile updated successfully!";
// }
