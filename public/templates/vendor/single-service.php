<?php
get_header();
?>

<div class="container my-5">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="row g-5">
            <!-- Featured Image -->
            <div class="col-lg-6">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('large'); ?>" class="img-fluid rounded shadow-sm w-100" alt="<?php the_title(); ?>">
                <?php endif; ?>
            </div>

            <!-- Service Details -->
            <div class="col-lg-6">
                <h1 class="fw-bold mb-3"><?php the_title(); ?></h1>
                <p class="text-muted"><?php the_excerpt(); ?></p>

                <ul class="list-unstyled">
                    <li><strong>📌 Service Type:</strong> <?php echo get_the_term_list(get_the_ID(), 'service_type', '', ', '); ?></li>
                    <li><strong>📍 Location:</strong> <?php echo get_the_term_list(get_the_ID(), 'service_location', '', ', '); ?></li>
                    <li><strong>🕒 Availability:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_service_schedule', true)); ?></li>
                    <li><strong>💰 Price:</strong> 
                        <span class="text-success fw-bold">
                            <?php echo get_post_meta(get_the_ID(), '_service_price', true) ? '$' . get_post_meta(get_the_ID(), '_service_price', true) : 'Contact for quote'; ?>
                        </span>
                    </li>
                </ul>

                <!-- CTA Button -->
                <button id="book-now-btn" class="btn btn-primary btn-lg mt-3">Book This Service</button>
            </div>
        </div>

        <!-- Service Description -->
        <div class="mt-5">
            <h3 class="fw-bold">About This Service</h3>
            <div class="text-muted"><?php the_content(); ?></div>
        </div>

        <!-- Service Gallery (Displayed Separately) -->
        <?php
        $gallery = get_post_meta(get_the_ID(), '_service_gallery', true);
        if (!empty($gallery) && is_array($gallery)) :
        ?>
            <div class="mt-5">
                <h3 class="fw-bold mb-3">📸 Service Gallery</h3>
                <div class="row g-3">
                    <?php foreach ($gallery as $image_id) : ?>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <?php echo wp_get_attachment_image($image_id, 'medium', false, ['class' => 'card-img-top rounded']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Service Booking Form (Initially Hidden) -->
        <div id="service-booking-container" class="container mt-4 d-none">
            <h3 class="mb-4">Book This Service</h3>
            
            <form id="service-booking-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <?php wp_nonce_field('service_booking_action', 'service_booking_nonce'); ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Preferred Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Additional Message</label>
                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                </div>

                <input type="hidden" name="service_id" value="<?php echo get_the_ID(); ?>">
                <input type="hidden" name="vendor_id" value="<?php echo get_the_author_meta('ID'); ?>"> 


                <button type="submit" class="btn btn-success w-100">Submit Booking</button>
            </form>

            <!-- Success Message -->
            <div id="booking-success" class="alert alert-success mt-3 d-none">
                ✅ Your booking request has been sent successfully!
            </div>
        </div>

    <?php endwhile; endif; ?>
</div>

<!-- JavaScript to Toggle Form Visibility -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('book-now-btn').addEventListener('click', function() {
        document.getElementById('service-booking-container').classList.toggle('d-none');
    });

    // Handle form submission via AJAX
    document.getElementById('service-booking-form').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('action', 'servicehub_mvm_book_service'); // WordPress AJAX action

        fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('booking-success').classList.remove('d-none');
                this.reset(); // Reset form fields
            } else {    
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<?php
get_footer();
?>

