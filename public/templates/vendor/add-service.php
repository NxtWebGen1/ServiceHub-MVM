<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


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

        <!-- Location -->
        <div class="mb-3">
            <label for="service_location" class="form-label">Location</label>
            <input type="text" class="form-control" id="service_location" name="service_location" required>
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

        <?php wp_nonce_field('add_service_action', 'add_service_nonce'); ?>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Service</button>
        <a href="#" class="btn btn-secondary">Cancel</a>
    </form>
</div>
