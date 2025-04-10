<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    .customer-login-wrapper {
        max-width: 450px;
        margin: 80px auto;
        padding: 2.5rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 1rem;
    }

    .customer-login-wrapper h3 {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 1.75rem;
    }

    .form-floating > .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        font-size: 0.95rem;
    }

    .form-floating > .form-control:focus {
        border-color: #0d6efd;
        box-shadow: none;
    }

    .btn-login {
        width: 100%;
        font-weight: 600;
        padding: 0.65rem;
        font-size: 1rem;
        border-radius: 8px;
    }

    .customer-login-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
    }

    .customer-login-footer a {
        color: #0d6efd;
        text-decoration: none;
    }

    .customer-login-footer a:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <div class="customer-login-wrapper">
        <h3><i class="fa-solid fa-user-lock me-2 text-primary"></i>Customer Login</h3>

        <form method="POST" action="<?php echo get_the_permalink(); ?>">
            <!-- Username -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="username" placeholder="you@example.com" required>
                <label for="username"><i class="fa fa-user me-1"></i> Username or Email</label>
            </div>

            <!-- Password -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="user_password" placeholder="Password" required>
                <label for="user_password"><i class="fa fa-lock me-1"></i> Password</label>
            </div>

            <!-- Submit -->
            <button type="submit" name="customer-loginbutton" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Log In
            </button>
        </form>

        <!-- Links -->
        <div class="customer-login-footer">
            <a href="#">Forgot Password?</a> |
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('customer-registration'))); ?>">Sign Up</a>
        </div>
    </div>
</div>
