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

    .service-hero p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 1rem;
    }

    /* ✨ Your adjusted vendor-info section */
    .vendor-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        justify-content: flex-start;
        padding: 10px 16px;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
        width: 30%;
        margin-left: auto;
        margin-right: auto;
        animation: fadeInUp 0.6s ease forwards;
    }

    .vendor-info img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 0;
    }

    .vendor-info:hover img {
        transform: scale(1.1) rotate(2deg);
    }

    .vendor-info a {
        font-weight: 600;
        font-size: 0.95rem;
        color: #0077cc;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .vendor-info a:hover {
        color: #005fa3;
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

    /* 🔥 Booking Form Glow-Up */
.booking-form {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(12px);
    padding: 2rem;
    margin-top: 40px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
    transition: all 01s ease-in-out;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    animation: fadeInUp 0.6s ease;
    display: flex;
    flex-direction: column;
}

.booking-form .form-group {
    margin-bottom: 1.2rem;
    position: relative;
}

.booking-form input,
.booking-form textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    border-radius: 10px;
    border: 1px solid #ddd;
    background: rgba(255, 255, 255, 0.85);
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    color: #333;
}

.booking-form input:focus,
.booking-form textarea:focus {
    outline: none;
    border-color: #007bff;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.glow-btn {
    background: 	linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
   float: right;

}

.glow-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.45);
}

.success-message {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #d4edda;
    border-radius: 10px;
    color: #155724;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

/* 🔁 Animation Reuse */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


    .d-none {
        display: none;
    }

    /* 🧊 SERVICE META CONTAINER */
    .service-meta {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        margin-top: 40px;
        transition: all 0.3s ease-in-out;
    }

    .meta-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 16px;
        padding: 1.3rem 1rem;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        height: 100%;
    }

    .meta-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        background-color: #ffffff;
    }

    .meta-card .icon {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
        animation: popIn 0.4s ease-in-out;
    }

    .meta-card .label {
        font-weight: 600;
        font-size: 0.95rem;
        color: #444;
        margin-bottom: 4px;
    }

    .meta-card .value {
        font-size: 0.95rem;
        color: #222;
    }

    /* 🔁 Animations */
    @keyframes popIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 576px) {
        .meta-card {
            margin-bottom: 1rem;
        }

        .vendor-info {
            width: 90%;
            justify-content: center;
        }
    }
</style>




<div class="service-container">

    <div class="service-hero">
        <?php if (has_post_thumbnail()) : ?>
            <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
        <?php endif; ?>

        <h1><?php the_title(); ?></h1>

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

       <!-- META CONTAINER -->
       <div class="service-meta row gx-4 gy-4">
    <div class="col-md-3 col-sm-6">
        <div class="meta-card text-center">
            <div class="icon">📌</div>
            <div class="label">Type</div>
            <div class="value"><?= get_the_term_list(get_the_ID(), 'service_type', '', ', ') ?></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="meta-card text-center">
            <div class="icon">📍</div>
            <div class="label">Location</div>
            <div class="value"><?= get_the_term_list(get_the_ID(), 'service_location', '', ', ') ?></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="meta-card text-center">
            <div class="icon">🕒</div>
            <div class="label">Availability</div>
            <div class="value"><?= esc_html(get_post_meta(get_the_ID(), '_service_schedule', true)); ?></div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="meta-card text-center">
            <div class="icon">💰</div>
            <div class="label">Price</div>
            <div class="value">$<?= esc_html(get_post_meta(get_the_ID(), '_service_price', true) ?: 'Contact'); ?></div>
        </div>
    </div>
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

            <!-- pop up form  -->
<div id="service-booking-container" class="booking-form d-none">
    <form id="service-booking-form" method="post" action="<?= admin_url('admin-ajax.php'); ?>">
        <?php wp_nonce_field('service_booking_action', 'service_booking_nonce'); ?>

        <div class="form-group">
            <input type="text" name="name" required placeholder="Full Name" value="<?= esc_attr($customer_name); ?>">
        </div>

        <div class="form-group">
            <input type="email" name="email" required placeholder="Email" value="<?= esc_attr($customer_email); ?>">
        </div>

        <div class="form-group">
            <input type="text" name="phone" required placeholder="Phone" value="<?= esc_attr($customer_phone); ?>">
        </div>

        <div class="form-group">
            <textarea name="address" placeholder="Address" rows="2"><?= esc_textarea($customer_address); ?></textarea>
        </div>

        <div class="form-group">
            <input type="date" name="date" value="<?= date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <textarea name="message" placeholder="Additional Message" rows="3"></textarea>
        </div>

        <input type="hidden" name="service_id" value="<?= get_the_ID(); ?>">
        <input type="hidden" name="vendor_id" value="<?= $vendor_id; ?>">

        <button type="submit" class="glow-btn bg-primary py-2">Submit Booking</button>
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
