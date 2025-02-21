<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/YOUR-FONT-AWESOME-KIT.js" crossorigin="anonymous"></script>

<style>
    body {
        background-color: #f8f9fa;
    }

    .registration-container {
        max-width: 500px;
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
    <div class="registration-container">
        <h3 class="text-center mb-4">Register as a Vendor</h3>
        <form class="registration" method="POST" action="<?php echo get_the_permalink(); ?>" enctype="multipart/form-data">
            
            <!-- Full Name -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-user"></i>
                <input type="text" class="form-control login__input" name="full_name" id="full_name" placeholder="Full Name" required>
            </div>

            <!-- Email Address -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-envelope"></i>
                <input type="email" class="form-control login__input" name="email" id="email" placeholder="Email Address" required>
            </div>

            <!-- Phone Number -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-phone"></i>
                <input type="text" class="form-control login__input" name="phone" id="phone" placeholder="Phone Number" required>
            </div>

            <!-- Profile Picture -->
            <div class="mb-3">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" name="profile_picture" id="profile_picture">
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-lock"></i>
                <input type="password" class="form-control login__input" name="password" id="password" placeholder="Password" required>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-lock"></i>
                <input type="password" class="form-control login__input" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            </div>

            <!-- Business Name -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-briefcase"></i>
                <input type="text" class="form-control login__input" name="business_name" id="business_name" placeholder="Business Name">
            </div>

            <!-- Website URL -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-globe"></i>
                <input type="url" class="form-control login__input" name="website" id="website" placeholder="Google.com (Optional)">
            </div>

            <!-- Service Location -->
            <div class="mb-3 position-relative">
                <i class="login__icon fas fa-map-marker-alt"></i>
                <input type="text" class="form-control login__input" name="service_location" id="service_location" placeholder="Service Location (City, Country)" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="register_button" class="btn btn-primary login__submit">
                <i class="fas fa-user-plus me-2"></i> Register
            </button>
        </form>

        <!-- Already have an account? -->
        <div class="text-center mt-3">
        <a href="<?php echo get_permalink(get_page_by_path('vendor-login')); ?>" class="text-decoration-none">
    Already have an account? Log in
</a>


</div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
