<?php
get_header();

$vendor_username = get_query_var('vendor_profile');
$vendor = get_user_by('login', $vendor_username);


if (!$vendor || !in_array('vendor', (array) $vendor->roles)) {
    echo '<div class="container mx-auto px-4 py-12"><div class="bg-red-100 text-red-700 p-4 rounded">Vendor not found or invalid.</div></div>';
    get_footer();
    return;
}

$vendor_id = $vendor->ID;
$business_name = get_user_meta($vendor_id, 'business_name', true);
$service_location = get_user_meta($vendor_id, 'service_location', true);
$website = get_user_meta($vendor_id, 'website', true);
$social_links = get_user_meta($vendor_id, 'social_links', true);
$years_in_business = get_user_meta($vendor_id, 'years_in_business', true);
$business_category = get_user_meta($vendor_id, 'business_category', true);
$profile_picture = get_user_meta($vendor_id, 'profile_picture', true);
?>

<style>
    .vendor-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 3rem 1rem;
        width: 100%;
        display: flex;
        gap: 2rem;
    }
    .vendor-profile {
        flex: 1;
        background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07);
    }
    .vendor-profile img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 1rem;
    }
    .vendor-profile h2 {
        font-size: 1.5rem;
        margin: 0.5rem 0;
    }
    .vendor-profile p {
        margin: 0.3rem 0;
        font-size: 0.95rem;
        color: #555;
    }
    .vendor-profile a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
    }

    .vendor-services {
        flex: 3;
    }
    .vendor-services h3 {
        margin-bottom: 1.5rem;
        font-size: 1.4rem;
    }
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .service-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    .service-card:hover {
        transform: translateY(-4px);
    }
    .service-card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
    }
    .service-card .content {
        padding: 1rem;
    }
    .service-card h4 {
        font-size: 1.1rem;
        margin: 0;
    }
    .service-card p {
        font-size: 0.9rem;
        color: #666;
        margin: 0.5rem 0;
    }
    .service-card a {
        font-size: 0.85rem;
        color: #2563eb;
        text-decoration: none;
    }
</style>

<div class="vendor-wrap">
    <div class="vendor-profile">
        <?php if ($profile_picture): ?>
            <img src="<?= esc_url($profile_picture); ?>" alt="Vendor Profile">
        <?php else: ?>
            <div style="width:120px;height:120px;border-radius:50%;background:#e5e7eb;margin:0 auto 1rem;"></div>
        <?php endif; ?>
        <h2><?= esc_html($vendor->display_name); ?></h2>
        <p><strong><?= esc_html($business_name); ?></strong></p>
        <?php if ($service_location): ?><p>📍 <?= esc_html($service_location); ?></p><?php endif; ?>
        <?php if ($website): ?><p>🌐 <a href="<?= esc_url($website); ?>" target="_blank">Website</a></p><?php endif; ?>
        <?php if ($social_links): ?><p>🔗 <a href="<?= esc_url($social_links); ?>" target="_blank">Social</a></p><?php endif; ?>
        <?php if ($years_in_business): ?><p>🕒 <?= esc_html($years_in_business); ?> Years</p><?php endif; ?>
        <?php if ($business_category): ?><p>🏷️ <?= esc_html($business_category); ?></p><?php endif; ?>
        <?php
            $phone = get_user_meta($vendor_id, 'phone', true);
            $street_address = get_user_meta($vendor_id, 'street_address', true);
            $service_radius = get_user_meta($vendor_id, 'service_radius', true);
            $gender = get_user_meta($vendor_id, 'gender', true);
            $portfolio = get_user_meta($vendor_id, 'portfolio_upload', true);
            ?>

            <?php if ($phone): ?><p>📞 <?= esc_html($phone); ?></p><?php endif; ?>
            <?php if ($street_address): ?><p>🏠 <?= esc_html($street_address); ?></p><?php endif; ?>
            <?php if ($service_radius): ?><p>📍 Service Radius: <?= esc_html($service_radius); ?> km</p><?php endif; ?>
            <?php if ($gender): ?><p>🧑 <?= ucfirst(esc_html($gender)); ?></p><?php endif; ?>
            <?php if ($portfolio): ?><p>📁 <a href="<?= esc_url($portfolio); ?>" target="_blank">View Portfolio</a></p><?php endif; ?>
    </div>

    <div class="vendor-services">
        <h3>Services by <?= esc_html($vendor->display_name); ?></h3>
        <div class="services-grid">
            <?php
            $services = new WP_Query([
                'post_type' => 'service',
                'post_status' => 'publish',
                'author' => $vendor_id,
                'posts_per_page' => -1,
            ]);

            if ($services->have_posts()) :
                while ($services->have_posts()) : $services->the_post(); ?>
                    <div class="service-card">
                        <?php if (has_post_thumbnail()): ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        <div class="content">
                            <h4><?php the_title(); ?></h4>
                            <p><?= wp_trim_words(get_the_content(), 15); ?></p>
                            <a href="<?php the_permalink(); ?>">View Service →</a>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else: ?>
                <p>This vendor hasn’t listed any services yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
