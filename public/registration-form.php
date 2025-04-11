<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    .vendor-registration-wrapper {
        max-width: 750px;
        margin: 60px auto;
        padding: 2rem;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 1rem;
    }

    .registration-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .registration-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
    }

    .registration-progress {
        position: relative;
        display: flex;
        justify-content: space-between;
        margin-bottom: 2.5rem;
        padding: 0 10px;
    }

    .registration-progress::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 30px;
        right: 30px;
        height: 3px;
        background-color: #dee2e6;
        z-index: 0;
        transform: translateY(-50%);
    }

    .step-icon {
        position: relative;
        z-index: 1;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: #dee2e6;
        color: #6c757d;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.3s ease;
    }

    .step-icon.active {
        background-color: #0d6efd;
        color: #fff;
    }

    .step {
        display: none;
    }

    .step.active {
        display: block;
    }

    .form-floating > .form-control,
    .form-floating > .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        font-size: 0.95rem;
    }

    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
        border-color: #0d6efd;
        box-shadow: none;
    }

    .btn-group-nav {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        gap: 1rem;
    }

    .btn-group-nav .btn {
        flex: 1;
    }

    .form-text {
        font-size: 0.85rem;
        margin-top: 4px;
        color: #6c757d;
    }
</style>

<div class="container">
    <div class="vendor-registration-wrapper">
        <div class="registration-header">
            <h2><i class="fa-solid fa-user-plus text-primary me-2"></i>Vendor Registration</h2>
        </div>

        <!-- Custom Progress Bar -->
        <div class="registration-progress">
            <div class="step-icon" id="stepIcon1">1</div>
            <div class="step-icon" id="stepIcon2">2</div>
            <div class="step-icon" id="stepIcon3">3</div>
        </div>

        <form id="multiStepForm" method="POST" enctype="multipart/form-data" onsubmit="return validateStep(currentStep)">
            <!-- STEP 1 -->
            <div class="step active" id="step1">
                <h5 class="mb-4">Step 1: Basic Information</h5>

                <div class="form-floating mb-3">
                    <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Full Name" required>
                    <label for="full_name">Full Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone" required>
                    <label for="phone">Phone Number</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                    <div class="form-text">Accepted formats: jpg, jpeg, png, webp. Max 2MB</div>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" required>
                    <label for="confirm_password">Confirm Password</label>
                </div>

                <div class="btn-group-nav">
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                </div>
            </div>

            <!-- STEP 2 -->
            <div class="step" id="step2">
                <h5 class="mb-4">Step 2: Business Details</h5>

                <div class="form-floating mb-3">
                    <input type="text" name="business_name" class="form-control" id="business_name" placeholder="Business Name" required>
                    <label for="business_name">Business Name</label>
                </div>

                <div class="form-floating mb-3">
                    <select name="business_category" class="form-select" id="business_category" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Cleaning">Cleaning</option>
                        <option value="Other">Other</option>
                    </select>
                    <label for="business_category">Business Category</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" name="years_in_business" class="form-control" id="years_in_business" placeholder="Years">
                    <label for="years_in_business">Years in Business</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="website" class="form-control" id="website" placeholder="Website">
                    <label for="website">Website</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="social_links" class="form-control" id="social_links" placeholder="Links">
                    <label for="social_links">Social Media Links</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Portfolio</label>
                    <input type="file" name="portfolio_upload" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div class="form-text">Accepted formats: pdf, doc, docx, jpg, jpeg, png. Max 5MB</div>
                </div>

                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep()">Next</button>
                </div>
            </div>

            <!-- STEP 3 -->
            <div class="step" id="step3">
                <h5 class="mb-4">Step 3: Address & Identity</h5>

                <div class="form-floating mb-3">
                    <input type="text" name="service_location" class="form-control" id="service_location" placeholder="City" required>
                    <label for="service_location">City</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="street_address" class="form-control" id="street_address" placeholder="Street">
                    <label for="street_address">Street Address</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" name="service_radius" class="form-control" id="service_radius" placeholder="Radius">
                    <label for="service_radius">Service Radius (e.g. 20 km)</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="Male" class="form-check-input" required>
                        <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="Female" class="form-check-input" required>
                        <label class="form-check-label">Female</label>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="emergency_contact" class="form-control" id="emergency_contact" placeholder="Emergency">
                    <label for="emergency_contact">Emergency Contact</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload National ID</label>
                    <input type="file" name="national_id" class="form-control">
                </div>

                <div class="btn-group-nav">
                    <button type="button" class="btn btn-secondary" onclick="prevStep()">Back</button>
                    <button type="submit" name="register_button" class="btn btn-success">
                        <i class="fa fa-user-plus me-1"></i> Register
                    </button>

                </div>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="<?php echo get_permalink(get_page_by_path('vendor-login')); ?>" class="text-decoration-none">
                Already have an account? Log in
            </a>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;

    function showStep(step) {
        document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
        document.getElementById('step' + step).classList.add('active');

        for (let i = 1; i <= 3; i++) {
            document.getElementById('stepIcon' + i).classList.remove('active');
            if (i <= step) document.getElementById('stepIcon' + i).classList.add('active');
        }
    }

    function nextStep() {
        if (validateStep(currentStep)) {
            currentStep++;
            if (currentStep <= 3) showStep(currentStep);
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
        const required = stepDiv.querySelectorAll('[required]');
        for (let el of required) {
            if (el.type === 'radio') {
                const name = el.name;
                const group = document.querySelectorAll(`input[name="${name}"]`);
                if (![...group].some(r => r.checked)) {
                    alert("Please complete all required fields.");
                    return false;
                }
            } else if (!el.value.trim()) {
                alert("Please complete all required fields.");
                el.focus();
                return false;
            }
        }
        return true;
    }

    showStep(currentStep);
</script>
