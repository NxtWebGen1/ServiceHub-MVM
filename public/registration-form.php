
<div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" method="POST" action="<?php echo get_the_permalink(); ?>" enctype="multipart/form-data">
                <!-- Full Name -->
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" class="login__input" name="full_name" id="full_name" placeholder="Full Name" required>
                </div>
                
                <!-- Email Address -->
                <div class="login__field">
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" class="login__input" name="email" id="email" placeholder="Email Address" required>
                </div>
                
                <!-- Phone Number -->
                <div class="login__field">
                    <i class="login__icon fas fa-phone"></i>
                    <input type="text" class="login__input" name="phone" id="phone" placeholder="Phone Number" required>
                </div>
                
                <!-- Profile Picture -->
                <div class="login__field">
                    <i class="login__icon fas fa-image"></i>
                    <input type="file" class="login__input" name="profile_picture" id="profile_picture">
                </div>
                
                <!-- Password -->
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="password" id="password" placeholder="Password" required>
                </div>
                
                <!-- Confirm Password -->
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="login__input" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                </div>
                
                <!-- Business Name -->
                <div class="login__field">
                    <i class="login__icon fas fa-briefcase"></i>
                    <input type="text" class="login__input" name="business_name" id="business_name" placeholder="Business Name">
                </div>
                
                <!-- Website URL -->
                <div class="login__field">
                    <i class="login__icon fas fa-globe"></i>
                    <input type="url" class="login__input" name="website" id="website" placeholder="GOogle.com (Optional)">
                </div>
                
                <!-- Service Location -->
                <div class="login__field">
                    <i class="login__icon fas fa-map-marker-alt"></i>
                    <input type="text" class="login__input" name="service_location" id="service_location" placeholder="Service Location (City, Country)" required>
                </div>

                <!-- Submit Button -->
                <button class="button login__submit">
                    <span class="button__text"><input type="submit" name="register_button" value="REGISTER NOW"></span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>
        </div>
        
        <!-- Background Shapes -->
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>
    </div>
</div>
