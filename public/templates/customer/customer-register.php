<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    .register-container {
        max-width: 600px;
        margin: 80px auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container">
    <div class="register-container">
        <h3 class="text-center mb-4">Create a Customer Account</h3>

        <?php if (!empty($message)) : ?>
            <div class="alert alert-info"><?php echo esc_html($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
            <?php wp_nonce_field('customer_register_action', 'customer_register_nonce'); ?>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" name="customer_full_name" class="form-control" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="customer_email" class="form-control" required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="customer_phone" class="form-control">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2" required></textarea>
            </div>


            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="customer_password" class="form-control" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="customer_confirm_password" class="form-control" required>
            </div>

            <!-- Submit -->
            <div class="d-grid">
                <button type="submit" name="customer_register_button" class="btn btn-primary">Register</button>
            </div>
        </form>

        <!-- Already have account -->
        <div class="text-center mt-3">
            Already have an account? <a href="<?php echo get_permalink(get_page_by_path('customer-login')); ?>">Login here</a>
        </div>
    </div>
</div>
