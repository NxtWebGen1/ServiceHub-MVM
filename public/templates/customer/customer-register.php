<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .register-container {
        max-width: 600px;
        margin: 80px auto;
        padding: 30px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .register-container h3 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.75rem;
        text-align: center;
    }

    .form-floating > .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 1rem;
        padding-left: 15px;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus {
        border-color: #0d6efd;
        box-shadow: none;
        transform: translateY(-1px);
    }

    .form-control {
        background-color: #fafafa;
    }

    .form-control:disabled, .form-control[readonly] {
        background-color: #f4f4f4;
    }

    .form-text {
        font-size: 0.85rem;
        color: #888;
    }

    .d-grid button {
        padding: 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        background-color: #0d6efd;
        border: none;
    }

    .d-grid button:hover {
        background-color: #0a58ca;
    }

    .customer-login-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.95rem;
    }

    .customer-login-footer a {
        color: #0d6efd;
        text-decoration: none;
    }

    .customer-login-footer a:hover {
        text-decoration: underline;
    }

    /* Placeholder styling */
    ::placeholder {
        color:rgb(253, 253, 253);
        font-style: italic;
    }
</style>

<div class="container">
    <div class="register-container">
        <h3>Create a Customer Account</h3>

        <?php if (!empty($message)) : ?>
            <div class="alert alert-info"><?php echo esc_html($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
            <?php wp_nonce_field('customer_register_action', 'customer_register_nonce'); ?>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" name="customer_full_name" class="form-control" placeholder="John Doe" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="customer_email" class="form-control" placeholder="you@example.com" required>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" name="customer_phone" class="form-control" placeholder="(123) 456-7890">
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="123 Main St, City, Country" required></textarea>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="customer_password" class="form-control" placeholder="********" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="customer_confirm_password" class="form-control" placeholder="********" required>
            </div>

            <!-- Submit -->
            <div class="d-grid">
                <button type="submit" name="customer_register_button" class="btn btn-primary">Register</button>
            </div>
        </form>

        <!-- Already have account -->
        <div class="customer-login-footer">
            Already have an account? <a href="<?php echo get_permalink(get_page_by_path('customer-login')); ?>">Login here</a>
        </div>
    </div>
</div>
