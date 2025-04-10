<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .form-card {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        background-color: #fff;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.05);
    }

    .form-card .form-control:focus,
    .form-card .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .form-section-title {
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: .5rem;
    }
</style>

<div class="container py-5">
    <div class="form-card mx-auto" style="max-width: 900px;">
        <h3 class="mb-4">🛠️ Add New Service</h3>

        <form method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_title" class="form-label">Service Title</label>
                    <input type="text" class="form-control" id="service_title" name="service_title" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_duration" class="form-label">Service Duration</label>
                    <input type="text" class="form-control" id="service_duration" name="service_duration" placeholder="e.g., 30 mins, 1 hour" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="service_description" class="form-label">Description</label>
                <textarea class="form-control" id="service_description" name="service_description" rows="4" required></textarea>
            </div>

            <h5 class="form-section-title">💰 Pricing & Payment</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="service_price" class="form-label">Price (optional)</label>
                    <input type="text" class="form-control" id="service_price" name="service_price">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="service_discount_price" class="form-label">Discount Price</label>
                    <input type="number" class="form-control" id="service_discount_price" name="service_discount_price" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="service_price_unit" class="form-label">Price Unit</label>
                    <select class="form-select" id="service_price_unit" name="service_price_unit">
                        <option value="Fixed Price">Fixed Price</option>
                        <option value="Per Hour">Per Hour</option>
                        <option value="Per Day">Per Day</option>
                        <option value="Per Session">Per Session</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_payment_type" class="form-label">Payment Type</label>
                    <select class="form-select" id="service_payment_type" name="service_payment_type">
                        <option value="One-Time Payment">One-Time Payment</option>
                        <option value="Recurring (Monthly)">Recurring (Monthly)</option>
                        <option value="Recurring (Weekly)">Recurring (Weekly)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_max_bookings" class="form-label">Max Bookings Per Day</label>
                    <input type="number" class="form-control" id="service_max_bookings" name="service_max_bookings" min="1">
                </div>
            </div>

            <h5 class="form-section-title">📍 Location & Type</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_location" class="form-label">Location</label>
                    <select class="form-select" id="service_location" name="service_location" required>
                        <option value="">Select Location</option>
                        <?php 
                        $locations = get_terms(array(
                            'taxonomy' => 'service_location',
                            'hide_empty' => false,
                        ));
                        foreach ($locations as $location) {
                            echo '<option value="' . esc_attr($location->term_id) . '">' . esc_html($location->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="service_type" class="form-label">Service Type</label>
                    <select class="form-select" id="service_type" name="service_type" required>
                        <option value="">Select Service Type</option>
                        <?php 
                        $types = get_terms(array(
                            'taxonomy' => 'service_type',
                            'hide_empty' => false,
                        ));
                        foreach ($types as $type) {
                            echo '<option value="' . esc_attr($type->term_id) . '">' . esc_html($type->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_availability_type" class="form-label">Availability Type</label>
                    <select class="form-select" id="service_availability_type" name="service_availability_type">
                        <option value="Online Service">Online Service</option>
                        <option value="In-Person Service">In-Person Service</option>
                        <option value="Both">Both</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="service_status" class="form-label">Service Status</label>
                    <select class="form-select" id="service_status" name="service_status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="service_availability" class="form-label">Availability Schedule</label>
                <input type="text" class="form-control" id="service_availability" name="service_availability" required>
            </div>

            <h5 class="form-section-title">🖼️ Media Uploads</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_image" class="form-label">Featured Image</label>
                    <input type="file" class="form-control" id="service_image" name="service_image">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="service_gallery" class="form-label">Gallery (multiple images)</label>
                    <input type="file" class="form-control" id="service_gallery" name="service_gallery[]" multiple accept="image/*">
                </div>
            </div>

            <?php wp_nonce_field('add_service_action', 'add_service_nonce'); ?>

            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-circle"></i> Submit Service
                </button>
                <a href="#" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
