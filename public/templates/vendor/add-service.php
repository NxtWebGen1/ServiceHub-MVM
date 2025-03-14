<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRuOFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<div class="container mt-4">
    <h2 class="mb-4">Add New Service</h2>

    <form method="post" enctype="multipart/form-data">
        <!-- Service Title -->
        <div class="mb-3">
            <label for="service_title" class="form-label">Service Title</label>
            <input type="text" class="form-control" id="service_title" name="service_title" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="service_description" class="form-label">Description</label>
            <textarea class="form-control" id="service_description" name="service_description" rows="4" required></textarea>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="service_price" class="form-label">Price (Leave empty for quote)</label>
            <input type="text" class="form-control" id="service_price" name="service_price">
        </div>

        <!-- Price Unit -->
        <div class="mb-3">
            <label for="service_price_unit" class="form-label">Price Unit</label>
            <select class="form-control" id="service_price_unit" name="service_price_unit">
                <option value="Fixed Price">Fixed Price</option>
                <option value="Per Hour">Per Hour</option>
                <option value="Per Day">Per Day</option>
                <option value="Per Session">Per Session</option>
            </select>
        </div>

        <!-- Discount Price -->
        <div class="mb-3">
            <label for="service_discount_price" class="form-label">Discount Price</label>
            <input type="number" class="form-control" id="service_discount_price" name="service_discount_price" min="0">
        </div>

        <!-- Service Duration -->
        <div class="mb-3">
            <label for="service_duration" class="form-label">Service Duration</label>
            <input type="text" class="form-control" id="service_duration" name="service_duration" placeholder="e.g., 30 mins, 1 hour" required>
        </div>

        <!-- Service Location (Dropdown) -->
        <div class="mb-3">
            <label for="service_location" class="form-label">Location</label>
            <select class="form-control" id="service_location" name="service_location" required>
                <option value="">Select Location</option>
                <?php 
                $locations = get_terms(array(
                    'taxonomy' => 'service_location',
                    'hide_empty' => false,
                ));
                if (!empty($locations)) {
                    foreach ($locations as $location) {
                        echo '<option value="' . esc_attr($location->term_id) . '">' . esc_html($location->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <!-- Service Type (Dropdown) -->
        <div class="mb-3">
            <label for="service_type" class="form-label">Service Type</label>
            <select class="form-control" id="service_type" name="service_type" required>
                <option value="">Select Service Type</option>
                <?php 
                $service_types = get_terms(array(
                    'taxonomy' => 'service_type',
                    'hide_empty' => false,
                ));
                if (!empty($service_types)) {
                    foreach ($service_types as $service_type) {
                        echo '<option value="' . esc_attr($service_type->term_id) . '">' . esc_html($service_type->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <!-- Payment Type -->
        <div class="mb-3">
            <label for="service_payment_type" class="form-label">Payment Type</label>
            <select class="form-control" id="service_payment_type" name="service_payment_type">
                <option value="One-Time Payment">One-Time Payment</option>
                <option value="Recurring (Monthly)">Recurring (Monthly)</option>
                <option value="Recurring (Weekly)">Recurring (Weekly)</option>
            </select>
        </div>

        <!-- Availability Type -->
        <div class="mb-3">
            <label for="service_availability_type" class="form-label">Availability Type</label>
            <select class="form-control" id="service_availability_type" name="service_availability_type">
                <option value="Online Service">Online Service</option>
                <option value="In-Person Service">In-Person Service</option>
                <option value="Both">Both</option>
            </select>
        </div>

        <!-- Max Bookings Per Day -->
        <div class="mb-3">
            <label for="service_max_bookings" class="form-label">Max Number of Bookings Per Day</label>
            <input type="number" class="form-control" id="service_max_bookings" name="service_max_bookings" min="1">
        </div>

        <!-- Service Status -->
        <div class="mb-3">
            <label for="service_status" class="form-label">Service Status</label>
            <select class="form-control" id="service_status" name="service_status">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <!-- Availability -->
        <div class="mb-3">
            <label for="service_availability" class="form-label">Availability</label>
            <input type="text" class="form-control" id="service_availability" name="service_availability" required>
        </div>

        <!-- Featured Image -->
        <div class="mb-3">
            <label for="service_image" class="form-label">Featured Image</label>
            <input type="file" class="form-control" id="service_image" name="service_image">
        </div>

        <!-- Gallery Upload -->
        <div class="mb-3">
            <label for="service_gallery" class="form-label">Service Gallery (Upload multiple images)</label>
            <input type="file" class="form-control" id="service_gallery" name="service_gallery[]" multiple accept="image/*">
        </div>

        <?php wp_nonce_field('add_service_action', 'add_service_nonce'); ?>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Service</button>
        <a href="#" class="btn btn-secondary">Cancel</a>
    </form>
</div>
