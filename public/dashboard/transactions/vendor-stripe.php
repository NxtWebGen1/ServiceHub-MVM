<?php
// Replace with your own keys
$stripe_client_id = 'ca_XXXXXXXXXXXX'; // We'll get this from Stripe in a bit
$publishable_key = 'pk_test_51RB0B32NPtk6CrJml6dUAxnfAjTZLXZhgilTQ0J7n6gQuiUAdycGKaDLytMZTgabbZh9BQRH1WNU54hUMif7V1tK00aTbmtecS';
$secret_key = 'sk_test_51RB0B32NPtk6CrJmBtKfN1gYKFxWp6Ko5J9sqM1Ycgg5CvffrO0IN7SckHDHA1aBY6QLGwf7JmIuJhVuUIF1WBVu00dQcsZIxN';

// Include Stripe SDK
require_once plugin_dir_path(__FILE__) . '../../includes/stripe/init.php';
\Stripe\Stripe::setApiKey($secret_key);

// Handle the redirect after vendor connects their account
if (isset($_GET['code']) && !empty($_GET['code'])) {
    $response = \Stripe\OAuth::token([
        'grant_type' => 'authorization_code',
        'code' => $_GET['code'],
    ]);

    $account_id = $response->stripe_user_id;

    // Save account ID in user meta
    $vendor_id = get_current_user_id();
    update_user_meta($vendor_id, '_stripe_account_id', $account_id);

    echo "<p>Your Stripe account is now connected! 🎉</p>";
    return;
}
?>
