<!-- MULTI-STEP VENDOR REGISTRATION WITH PROGRESS -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/YOUR-FONT-AWESOME-KIT.js" crossorigin="anonymous"></script>

<style>
    body {
        background-color: #f8f9fa;
    }

    .registration-container {
        max-width: 650px;
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

    .step {
        display: none;
    }

    .step.active {
        display: block;
    }
</style>

<div class="container">
    <div class="registration-container">
        <h3 class="text-center mb-3">Register as a Vendor</h3>

        <!-- Progress Bar -->
        <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" id="formProgress" style="width: 33%;">Step 1 of 3</div>
        </div>

        <form id="multiStepForm" method="POST" action="<?php echo get_the_permalink(); ?>" enctype="multipart/form-data" onsubmit="return validateStep(currentStep)">
            
            <!-- Step 1 -->
            <div class="step active" id="step1">
                <h5 class="mb-3">Step 1: Basic Information</h5>

                <div class="mb-3 position-relative">
                    <i class="login__icon fas fa-user"></i>
                    <input type="text" class="form-control login__input" name="full_name" placeholder="Full Name" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="login__icon fas fa-envelope"></i>
                    <input type="email" class="form-control login__input" name="email" placeholder="Email Address" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="login__icon fas fa-phone"></i>
                    <input type="text" class="form-control login__input" name="phone" placeholder="Phone Number" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" name="profile_picture" require>
                </div>

                <div class="mb-3 position-relative">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="form-control login__input" name="password" placeholder="Password" required>
                </div>

                <div class="mb-3 position-relative">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" class="form-control login__input" name="confirm_password" placeholder="Confirm Password" required>
                </div>

                <button type="button" class="btn btn-primary w-100" onclick="nextStep()">Next</button>
            </div>

            <!-- Step 2 -->
            <div class="step" id="step2">
                <h5 class="mb-3">Step 2: Business Details</h5>

                <div class="mb-3 position-relative">
                    <i class="login__icon fas fa-briefcase"></i>
                    <input type="text" class="form-control login__input" name="business_name" placeholder="Business Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Business Category</label>
                    <select name="business_category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Cleaning">Cleaning</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="number" class="form-control" name="years_in_business" placeholder="Years in Business">
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" name="website" placeholder="Website URL" placeholder="">
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" name="social_links" placeholder="Social Media Links (comma separated)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Portfolio</label>
                    <input type="file" class="form-control" name="portfolio_upload">
                </div>

                <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                <button type="button" class="btn btn-primary float-end" onclick="nextStep()">Next</button>
            </div>

            <!-- Step 3 -->
            <div class="step" id="step3">
                <h5 class="mb-3">Step 3: Address & Identity</h5>

                <div class="mb-3">
                    <input type="text" class="form-control" name="service_location" placeholder="City" required>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" name="street_address" placeholder="Street Address">
                </div>

                <div class="mb-3">
                    <input type="number" class="form-control" name="service_radius" placeholder="Service Radius (e.g., 20 km)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="Male" required> <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="Female" required> <label class="form-check-label">Female</label>
                    </div>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" name="emergency_contact" placeholder="Emergency Contact Number">
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload National ID</label>
                    <input type="file" class="form-control" name="national_id" >
                </div>

                <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                <button type="submit" name="register_button" class="btn btn-success float-end">
                    <i class="fas fa-user-plus me-2"></i> Register
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="<?php echo get_permalink(get_page_by_path('vendor-login')); ?>" class="text-decoration-none">
                Already have an account? Log in
            </a>
        </div>
    </div>
</div>

<!-- JavaScript for Multi-Step Form with Progress -->
<script>
    let currentStep = 1;
    const totalSteps = 3;

    function showStep(step) {
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('step' + i).classList.remove('active');
        }
        document.getElementById('step' + step).classList.add('active');

        // Update progress bar
        const progress = Math.floor((step / totalSteps) * 100);
        const progressBar = document.getElementById('formProgress');
        progressBar.style.width = progress + '%';
        progressBar.innerText = `Step ${step} of ${totalSteps}`;
    }

    function nextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            if (currentStep <= totalSteps) {
                showStep(currentStep);
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function validateStep(step) {
        const stepDiv = document.getElementById('step' + step);
        const requiredInputs = stepDiv.querySelectorAll('[required]');
        for (let input of requiredInputs) {
            if ((input.type === 'radio' && !document.querySelector(`input[name="${input.name}"]:checked`)) ||
                (input.type !== 'radio' && !input.value.trim())) {
                input.focus();
                alert('Please fill in all required fields on this step.');
                return false;
            }
        }
        return true;
    }

    showStep(currentStep);
</script>
