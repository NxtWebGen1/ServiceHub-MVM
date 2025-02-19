<?php 

//LOGIN FORM HANDLING
//WP_SIGNON function needs to run before the header of a page loads, that is why we are hooking in to template_redurect hook
add_action( 'template_redirect', 'my_login');
function my_login(){
   if(isset($_POST['loginbutton'])){
        $username = esc_sql( $_POST['username'] );
        $user_password = esc_sql( $_POST['username_password'] );

        $credentials = array(
         'user_login' => $username,
         'user_password' => $user_password,
        );
         $user_login_status = wp_signon($credentials);
         if(is_wp_error(  $user_login_status)){
            echo $user_login_status->get_error_message();
         }
         else{
            echo "User LOGIN SUCCESSFUL";
            wp_redirect( site_url('profile'));
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

