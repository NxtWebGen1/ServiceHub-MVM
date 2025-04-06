<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
$vendor_id = get_the_author_meta('ID');
$vendor_username = get_the_author_meta('user_login');
$vendor_profile_url = site_url('/vendor/' . $vendor_username);
$profile_picture = get_user_meta($vendor_id, 'profile_picture', true);
?>

<style>
    body {
        background: #f5f7fb;
        font-family: 'Segoe UI', sans-serif;
    }

    .service-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .service-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-bottom: 40px;
    }

    .service-hero img {
        max-width: 100%;
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
    }

    .service-hero h1 {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
    }

    .service-hero .vendor-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 12px;
    }

    .vendor-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .vendor-info a {
        font-weight: 600;
        color: #0077cc;
        text-decoration: none;
    }

    .service-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-top: 40px;
        background: #fff;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .service-meta div {
        font-size: 0.95rem;
        color: #333;
    }

    .service-description {
        margin-top: 40px;
        line-height: 1.7;
        background: #fff;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .booking-button {
        margin: 40px 0;
        text-align: center;
    }

    .booking-button button {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 0.8rem 2rem;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
    }

    .gallery-grid {
        margin-top: 40px;
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }

    .gallery-grid img {
        border-radius: 12px;
        width: 100%;
        height: auto;
    }

    .booking-form {
        background: #fff;
        padding: 2rem;
        margin-top: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
    }

    .booking-form input,
    .booking-form textarea {
        width: 100%;
        padding: 0.6rem;
        margin-bottom: 1rem;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .booking-form button {
        background: #28a745;
        color: #fff;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
    }

    .success-message {
        margin-top: 1rem;
        padding: 1rem;
        background: #d4edda;
        border-radius: 8px;
        color: #155724;
        display: none;
    }

    .d-none { display: none; }
</style>

<div class="service-container">

    <div class="service-hero">
        <?php if (has_post_thumbnail()) : ?>
            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
        <?php endif; ?>

        <h1><?php the_title(); ?></h1>
        <p><?php the_excerpt(); ?></p>

        <?php
            $author_id = get_the_author_meta('ID');
            $author = get_userdata($author_id);
            $profile_picture = get_user_meta($author_id, 'profile_picture', true);
            $vendor_profile_url = home_url('/vendor/' . $author->user_nicename);
            $display_name = $author->display_name;
            ?>

            <div class="vendor-info">
                <img 
                    src="<?php echo esc_url($profile_picture ?: 'https://ui-avatars.com/api/?name=' . urlencode($display_name)); ?>" 
                    alt="<?php echo esc_attr($display_name); ?>" 
                    class="img-fluid rounded-circle" 
                    width="60" 
                />
                <a href="<?php echo esc_url($vendor_profile_url); ?>">@<?php echo esc_html($display_name); ?></a>
            </div>

    </div>

    <div class="service-meta">
        <div><strong>📌 Type:</strong> <?= get_the_term_list(get_the_ID(), 'service_type', '', ', ') ?></div>
        <div><strong>📍 Location:</strong> <?= get_the_term_list(get_the_ID(), 'service_location', '', ', ') ?></div>
        <div><strong>🕒 Availability:</strong> <?= esc_html(get_post_meta(get_the_ID(), '_service_schedule', true)); ?></div>
        <div><strong>💰 Price:</strong> $<?= esc_html(get_post_meta(get_the_ID(), '_service_price', true) ?: 'Contact'); ?></div>
    </div>

    <div class="service-description">
        <h2>About This Service</h2>
        <?php the_content(); ?>
    </div>

    <?php
    $gallery = get_post_meta(get_the_ID(), '_service_gallery', true);
    if (!empty($gallery) && is_array($gallery)) :
    ?>
        <div class="gallery-grid">
            <?php foreach ($gallery as $image_id) :
                echo wp_get_attachment_image($image_id, 'medium');
            endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="booking-button">
        <button id="book-now-btn">Book This Service</button>
    </div>

    <?php
    $current_user = wp_get_current_user();
    $customer_name = is_user_logged_in() ? $current_user->display_name : '';
    $customer_email = is_user_logged_in() ? $current_user->user_email : '';
    $customer_phone = is_user_logged_in() ? get_user_meta($current_user->ID, 'phone', true) : '';
    $customer_address = is_user_logged_in() ? get_user_meta($current_user->ID, 'street_address', true) : '';
    ?>

    <div id="service-booking-container" class="booking-form d-none">
        <form id="service-booking-form" method="post" action="<?= admin_url('admin-ajax.php'); ?>">
            <?php wp_nonce_field('service_booking_action', 'service_booking_nonce'); ?>

            <input type="text" name="name" required placeholder="Full Name" value="<?= esc_attr($customer_name); ?>">
            <input type="email" name="email" required placeholder="Email" value="<?= esc_attr($customer_email); ?>">
            <input type="text" name="phone" required placeholder="Phone" value="<?= esc_attr($customer_phone); ?>">
            <textarea name="address" placeholder="Address" rows="2"><?= esc_textarea($customer_address); ?></textarea>
            <input type="date" name="date" value="<?= date('Y-m-d'); ?>" required>
            <textarea name="message" placeholder="Additional Message" rows="3"></textarea>

            <input type="hidden" name="service_id" value="<?= get_the_ID(); ?>">
            <input type="hidden" name="vendor_id" value="<?= $vendor_id; ?>">

            <button type="submit">Submit Booking</button>
        </form>

        <div id="booking-success" class="success-message">
            ✅ Booking submitted successfully!
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const bookBtn = document.getElementById('book-now-btn');
        const formContainer = document.getElementById('service-booking-container');
        const form = document.getElementById('service-booking-form');
        const successMsg = document.getElementById('booking-success');

        bookBtn.addEventListener('click', () => {
            formContainer.classList.toggle('d-none');
            successMsg.classList.add('d-none');
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'servicehub_mvm_book_service');

            fetch("<?= admin_url('admin-ajax.php'); ?>", {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    form.classList.add('d-none');
                    successMsg.classList.remove('d-none');
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
