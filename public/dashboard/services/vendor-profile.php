<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Vendor Profile</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                
                <!-- Profile Picture -->
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" name="profile_picture">
                </div>

                <!-- Full Name -->
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="full_name" value="John Doe" required>
                </div>

                <!-- Email (Disabled) -->
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" value="vendor@example.com" disabled>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="123-456-7890">
                </div>

                <!-- Website -->
                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="url" class="form-control" name="website" value="https://yourbusiness.com">
                </div>

                <!-- Service Location -->
                <div class="mb-3">
                    <label class="form-label">Service Location</label>
                    <input type="text" class="form-control" name="service_location" value="New York, USA">
                </div>

                <!-- Password Change -->
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Enter new password (optional)">
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>
