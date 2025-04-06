<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/YOUR-FONT-AWESOME-KIT.js" crossorigin="anonymous"></script>
<style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .login__icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        .login__input {
            padding-left: 40px;
        }
        .login__submit {
            width: 100%;
            font-weight: bold;
        }
    </style>

<div class="container">
    <div class="login-container">
        <h3 class="text-center mb-4">Customer Login</h3>
        <form class="login" method="POST" action="<?php echo get_the_permalink(); ?>">
            <!-- Username / Email -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-user"></i>
                <input type="text" class="form-control login__input" name="username" id="username" placeholder="User Name / Email" required>
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-lock"></i>
                <input type="password" class="form-control login__input" name="password" id="user_password" placeholder="Password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="customer-loginbutton" class="btn btn-primary login__submit">
                <i class="fas fa-sign-in-alt me-2"></i> Log In
            </button>
        </form>

        <!-- Forgot Password & Sign Up Links -->
        <div class="text-center mt-3">
            <a href="#" class="text-decoration-none">Forgot Password?</a> | 
            <a href="<?php echo get_permalink(get_page_by_path('customer-registration')); ?>" class="text-decoration-none">Sign Up</a>  
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>