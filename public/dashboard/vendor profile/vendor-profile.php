<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<?php


// Output the HTML content
echo 'this is john';

$current_user = wp_get_current_user();  // Get the current logged-in user

// DATA FROM META TABLE
$phone = get_user_meta($current_user->ID, 'phone', true);  //THIS TRUE RETURNS A SINGLE VALUE (not array)
$business_name = get_user_meta($current_user->ID, 'business_name', true);
$website = get_user_meta($current_user->ID, 'website', true);
$service_location = get_user_meta($current_user->ID, 'service_location', true);
$business_name = get_user_meta($current_user->ID, 'business_name', true);

$profile_picture = get_user_meta(get_current_user_id(), 'profile_picture', true);


?>
<div class="container mt-5">
    <div class="card full-width">
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="row mb-3">

                    <!-- USER ID HIDDEN FIELD -->
                    <input type="hidden" class="form-control" name="user_id_from_form" value="<?php $current_user->ID; ?>">



                    <!-- Profile Picture -->
                    <div class="col-md-12">

                        <?php if ($profile_picture) { ?>
                            <img src="<?php echo esc_url($profile_picture); ?>" width="150" height="150" style="border-radius: 50%; display: block; margin-bottom: 10px;">
                        <?php  } ?>
                        <input type="file" name="profile_picture" id="profile_picture">
                        <!-- <input type="hidden" name="profile_picture_existing" value="<?php echo esc_attr($profile_picture); ?>"> -->


                    </div>

                  
                </div>

                <div class="row mb-3">

                  <!-- UsSer Name  DISABLED-->
                  <div class="col-md-6">
                        <label class="form-label">User Name</label>
                        <input type="text" class="form-control" name="full_name" value="<?php echo $current_user->user_login ?>" disabled>
                    </div>

                    <!-- EMail (Disabled) -->
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $current_user->user_email ?>" disabled>
                    </div>
                    <!-- Vendor Phone Number -->
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" value="<?php echo $phone ?>">
                    </div>

                    <!-- Vendor Shope Name -->
                    <div class="col-md-6">
                        <label class="form-label">SBusiness Name</label>
                        <input type="text" class="form-control" name="business_name" value="<?php echo $business_name ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- VendorWebsite -->
                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url" class="form-control" name="website" value="<?php echo $website ?>">
                    </div>
                    <!-- Service Location -->
                    <div class="col-md-6">
                        <label class="form-label">Service Location</label>
                        <input type="text" class="form-control" name="service_location" value="<?php echo $service_location ?>">
                    </div>
                </div>


                <div class="row mb-3">
                    <!-- Password Change -->
                    <h6 class="change" id="changeBtn">Wanna Change Password?</h6>
                    <div class="open-change" id="changeContainer" style="display: none;">
                        <div class="col-md-6"> <!-- OLD PASSWORD -->
                            <label class="form-label">Enter Old Password</label>
                            <input type="password" class="form-control" name="old_password" placeholder="Enter your Old password ">
                        </div>
                        <div class="col-md-6"> <!-- NEW PASSWORD -->
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" placeholder="Enter new password (optional)">
                        </div>

                        <div class="col-md-6"> <!-- CONFIRM PASSWORD -->
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password (optional)">
                        </div>
                    </div>



                </div>

                <!-- Submit Button -->
                <div class="row mb-3">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary" name='profile_change'>Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Add a click event listener to the "Change Password" link
    document.getElementById('changeBtn').addEventListener('click', function() {
        var container = document.getElementById('changeContainer');

        // Toggle the visibility of the change password container
        if (container.style.display === 'none' || container.style.display === '') {
            container.style.display = 'block'; // Show the container
        } else {
            container.style.display = 'none'; // Hide the container
        }
    });
</script>