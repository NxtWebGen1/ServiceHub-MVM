 <!-- STRIPE CONNECT SECTION START | Added below profile card -->
 <?php
    $vendor_id = get_current_user_id();
    $stripe_account_id = get_user_meta($vendor_id, '_stripe_account_id', true);

    // Your Stripe TEST client ID
    $stripe_client_id = 'ca_S5cGOVTyNa0QqgcvTYvIpjZhzxmYfFlw';
    $redirect_uri = site_url('/wp-content/plugins/servicehub-mvm/public/vendors/vendor-stripe.php');
    $stripe_connect_url = "https://connect.stripe.com/oauth/authorize?response_type=code&client_id={$stripe_client_id}&scope=read_write&redirect_uri={$redirect_uri}";
    ?>

    <div class="card mt-4 p-3">
        <h5>Stripe Connection</h5>
        <?php if ($stripe_account_id): ?>
            <div class="alert alert-success">
                ✅ Stripe Connected!<br>
                <strong>Account ID:</strong> <?= esc_html($stripe_account_id) ?>
            </div>
        <?php else: ?>
            <p>To receive payments, please connect your Stripe account:</p>
            <a href="<?= esc_url($stripe_connect_url) ?>" class="btn btn-primary">Connect with Stripe</a>
        <?php endif; ?>
    </div>
    <!-- STRIPE CONNECT SECTION END -->
</div>