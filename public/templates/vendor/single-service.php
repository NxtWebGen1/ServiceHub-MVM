<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
$vendor_id = get_the_author_meta('ID');
$vendor_username = get_the_author_meta('user_login');
$vendor_profile_url = site_url('/vendor/' . $vendor_username);
$profile_picture = get_user_meta($vendor_id, 'profile_picture', true);
$display_name = get_the_author_meta('display_name');
?>

<style>
    body {
        background: #f5f7fb;
        font-family: 'Segoe UI', sans-serif;
    }
    .section-heading {
        margin-top: 3rem;
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        color: #222;
        position: relative;
    }
    .section-heading::after {
        content: '';
        width: 60px;
        height: 4px;
        background: #007bff;
        display: block;
        margin: 0.5rem auto 0;
        border-radius: 4px;
    }
    .fade-slide {
        animation: fadeSlideUp 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    @keyframes fadeSlideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }
    .modal-content {
        background: #fff;
        padding: 2.5rem 2rem 2rem;
        border-radius: 20px;
        max-width: 700px;
        width: 90%;
        position: relative;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        transform: translateY(-30px);
        animation: slideFadeIn 0.4s ease forwards;
    }
    @keyframes slideFadeIn {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


</style>

<div class="service-container" style="max-width:1200px; margin:0 auto; padding:2rem 1rem;">

    <!-- Hero Section -->
    <div class="service-hero">
        <div style="position:relative;">
            <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title(); ?>" class="rounded-4" style="width:100%; max-height:500px; object-fit:cover; border-radius:16px;">
            <div style="position:absolute; bottom:2rem; left:2rem; background:rgba(0,0,0,0.6); padding:1.5rem 2rem; border-radius:16px; color:white; max-width:80%; backdrop-filter: blur(6px);">
                <h1 style="font-size:2.5rem; margin-bottom:0.5rem;"><?php the_title(); ?></h1>
                <p style="font-size:1.1rem; margin-bottom:0;"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
            </div>
        </div>

        <!-- Vendor Info -->
        <div class="vendor-info-card" style="display:flex; align-items:center; gap:16px; background:#fff; border-radius:16px; padding:1rem 1.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.05); max-width:400px; margin:2rem auto;">
            <img src="<?php echo esc_url($profile_picture ?: 'https://ui-avatars.com/api/?name=' . urlencode($display_name)); ?>" style="width:60px; height:60px; border-radius:50%; object-fit:cover; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
            <div>
                <strong style="font-size:1.05rem;">@<?php echo esc_html($display_name); ?></strong><br>
                <a href="<?php echo esc_url($vendor_profile_url); ?>" style="font-size:0.9rem; color:#007bff;">View Full Profile →</a>
            </div>
        </div>
    </div>

    <!-- Section Heading: Service Details -->
    <h2 class="section-heading">Service Details</h2>

    <!-- Meta Data -->
    <div class="service-meta row gx-4 gy-4 fade-slide" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap:1.5rem;">
        <div class="meta-card text-center" style="background:#fff; border-radius:16px; padding:1rem; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <div class="icon">📌</div>
            <div class="label">Type</div>
            <div class="value"><?php echo get_the_term_list(get_the_ID(), 'service_type', '', ', '); ?></div>
        </div>
        <div class="meta-card text-center" style="background:#fff; border-radius:16px; padding:1rem; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <div class="icon">📍</div>
            <div class="label">Location</div>
            <div class="value"><?php echo get_the_term_list(get_the_ID(), 'service_location', '', ', '); ?></div>
        </div>
        <div class="meta-card text-center" style="background:#fff; border-radius:16px; padding:1rem; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <div class="icon">🕒</div>
            <div class="label">Availability</div>
            <div class="value"><?php echo esc_html(get_post_meta(get_the_ID(), '_service_schedule', true)); ?></div>
        </div>
        <div class="meta-card text-center" style="background:#fff; border-radius:16px; padding:1rem; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
            <div class="icon">💰</div>
            <div class="label">Price</div>
            <div class="value">$<?php echo esc_html(get_post_meta(get_the_ID(), '_service_price', true) ?: 'Contact'); ?></div>
        </div>
    </div>

    <!-- Section Heading: About This Service -->
    <h2 class="section-heading">About This Service</h2>

    <div class="service-description fade-slide" style="margin-top:1rem; background:#fff; padding:2rem; border-radius:16px; box-shadow:0 4px 10px rgba(0,0,0,0.03);">
        <h3 style="font-size:1.6rem; margin-bottom:1rem;">What’s Included</h3>
        <?php the_content(); ?>
        <hr style="margin:2rem 0;">
        <h3 style="font-size:1.2rem; margin-bottom:0.75rem;">Why choose this service?</h3>
        <ul style="padding-left:1.25rem; color:#444; line-height:1.8;">
            <li>✔️ Expert-level delivery</li>
            <li>✔️ Clear communication & fast turnaround</li>
            <li>✔️ Custom tailored to your goals</li>
        </ul>
    </div>

    <?php $gallery = get_post_meta(get_the_ID(), '_service_gallery', true); ?>
    <?php if (!empty($gallery) && is_array($gallery)) : ?>
        <h2 class="section-heading">Service Gallery</h2>
        <div class="gallery-grid fade-slide" style="margin-top:1rem; display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:1rem;">
            <?php foreach ($gallery as $image_id) : echo wp_get_attachment_image($image_id, 'medium'); endforeach; ?>
        </div>
    <?php endif; ?>

    <h2 class="section-heading">Book This Service</h2>

    <div style="text-align:center; margin:2rem 0;">
        <button onclick="openBookingModal()" style="padding:0.75rem 2rem; background:#007bff; color:#fff; border:none; border-radius:8px; font-size:1rem;">Open Booking Form</button>
    </div>

    <!-- Modal -->
    <div id="bookingModal" class="modal-overlay">
        <div class="modal-content">
            <form id="service-booking-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <?php wp_nonce_field('service_booking_action', 'service_booking_nonce'); ?>
                <?php $user = wp_get_current_user(); ?>
                <input type="hidden" name="action" value="servicehub_mvm_book_service">
                <input type="hidden" name="service_id" value="<?php the_ID(); ?>">
                <input type="hidden" name="vendor_id" value="<?php echo esc_attr($vendor_id); ?>">

                <div class="form-group"><input type="text" name="name" required placeholder="Full Name" value="<?php echo esc_attr($user->display_name); ?>"></div>
                <div class="form-group"><input type="email" name="email" required placeholder="Email" value="<?php echo esc_attr($user->user_email); ?>"></div>
                <div class="form-group"><input type="text" name="phone" required placeholder="Phone"></div>
                <div class="form-group"><textarea name="address" placeholder="Address" rows="2"></textarea></div>
                <div class="form-group"><input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required></div>
                <div class="form-group"><textarea name="message" placeholder="Additional Message" rows="3"></textarea></div>

                <button type="submit" class="glow-btn" style="background:linear-gradient(135deg,#007bff,#0056b3); color:#fff; padding:0.75rem 2rem; border:none; border-radius:12px; font-weight:600; width:100%;">Submit Booking</button>
            </form>
            <div id="booking-success" class="success-message" style="display:none; margin-top:1rem; padding:1rem; background:#d4edda; border-radius:10px; color:#155724; font-weight:500;">✅ Booking submitted successfully!</div>
        </div>
    </div>
</div>

<script>
    function openBookingModal() {
        const modal = document.getElementById('bookingModal');
        modal.classList.add('active');
    }
    function closeBookingModal() {
        const modal = document.getElementById('bookingModal');
        modal.classList.remove('active');
    }

    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('service-booking-form');
        const successMsg = document.getElementById('booking-success');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("<?php echo admin_url('admin-ajax.php'); ?>", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    form.style.display = 'none';
                    successMsg.style.display = 'block';
                    form.reset();
                } else {
                    alert('❌ ' + (data.message || 'Something went wrong'));
                }
            })
            .catch(err => {
                console.error('Booking error:', err);
                alert('❌ Network error');
            });
        });
    });
    
</script>

<?php endwhile; endif; ?>
<?php get_footer(); ?>
