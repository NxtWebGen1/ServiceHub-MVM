<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

//----------------------------------------
// HANDLE CUSTOMER LOGIN FORM SUBMISSION
//----------------------------------------
add_action('init', 'servicehub_mvm_handle_customer_login');

function servicehub_mvm_handle_customer_login() {
    if (isset($_POST['loginbutton'])) {
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);

        if (empty($username) || empty($password)) {
            wp_die('Both username and password are required.');
        }

        $credentials = [
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => true,
        ];

        $user = wp_signon($credentials, false);

        if (is_wp_error($user)) {
            wp_die($user->get_error_message());
        } else {
            $roles = (array) get_userdata($user->ID)->roles;

            if (in_array('customer', $roles)) {
                wp_redirect(home_url('/customer-dashboard'));
                exit;
            } else {
                wp_logout();
                wp_die('Only customers can log in from this form.');
            }
        }
    }
}

//----------------------------------------
// HANDLE CUSTOMER REGISTRATION FORM SUBMISSION
//----------------------------------------
add_action('init', 'servicehub_mvm_handle_customer_registration');

function servicehub_mvm_handle_customer_registration() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['customer_register_button'])) {
        $full_name        = sanitize_text_field($_POST['customer_full_name']);
        $email            = sanitize_email($_POST['customer_email']);
        $password         = $_POST['customer_password'];
        $confirm_password = $_POST['customer_confirm_password'];

        $errors = [];

        if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
            $errors[] = 'All fields are required.';
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

        $user_id = wp_insert_user([
            'user_login'   => sanitize_user($full_name, true),
            'user_email'   => $email,
            'user_pass'    => $password,
            'display_name' => $full_name,
            'role'         => 'customer',
        ]);

        if (is_wp_error($user_id)) {
            wp_die('Registration failed: ' . $user_id->get_error_message());
        }

        update_user_meta($user_id, '_vendor_approval_status', 'approved');

        wp_redirect(home_url('/customer-login'));
        exit;
    }
}

//----------------------------------------
// SHORTCODES FOR CUSTOMER FORMS
//----------------------------------------
function customer_login_form_shortcode() {
    ob_start();
    include SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/customer/customer-login.php';
    return ob_get_clean();
}
add_shortcode('customer_login_form', 'customer_login_form_shortcode');

function customer_registration_form_shortcode() {
    ob_start();
    include SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/customer/customer-register.php';
    return ob_get_clean();
}
add_shortcode('customer_registration_form', 'customer_registration_form_shortcode');
