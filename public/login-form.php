<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    .vendor-login-wrapper {
        max-width: 480px;
        margin: 60px auto;
        padding: 0;
    }

    .vendor-login-form {
        border: 1px solid #e0e0e0;
        padding: 2rem;
        border-radius: 16px;
        background-color: #ffffff;
    }

    .vendor-login-title {
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        color: #333;
    }

    .form-floating > .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .form-floating > .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.1);
    }

    .vendor-login-form .btn-primary {
        width: 100%;
        font-weight: 600;
        padding: 0.65rem;
        margin-top: 1rem;
        border-radius: 8px;
    }

    .vendor-login-footer {
        margin-top: 1.25rem;
        text-align: center;
        font-size: 0.95rem;
    }

    .vendor-login-footer a {
        color: #0d6efd;
        text-decoration: none;
        margin: 0 5px;
    }

    .vendor-login-footer a:hover {
        text-decoration: underline;
    }
</style>

<div class="container vendor-login-wrapper">
    <form method="POST" action="<?php echo get_the_permalink(); ?>" class="vendor-login-form">
        <div class="vendor-login-title text-center">
            <i class="fa-solid fa-store me-2 text-primary"></i> Vendor Login
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="username@example.com" required>
            <label for="username"><i class="fa fa-user me-1"></i> Username or Email</label>
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="user_password" name="password" placeholder="Password" required>
            <label for="user_password"><i class="fa fa-lock me-1"></i> Password</label>
        </div>

        <button type="submit" name="vendor-loginbutton" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-1"></i> Log In
        </button>

        <div class="vendor-login-footer mt-3">
            <a href="#">Forgot Password?</a> |
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('vendor-registration'))); ?>">Sign Up</a>
        </div>
    </form>
</div>
