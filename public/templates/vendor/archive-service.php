<?php get_header(); ?>

<?php
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish'
);

$query = new WP_Query($args);
global $wp_query;
$query = $wp_query;
?>

<style>
    body {
        background: #f9fafa;
        font-family: 'Inter', sans-serif;
    }

    .archive-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 4rem 1rem;
    }

    .hero {
        text-align: center;
        padding-bottom: 2rem;
    }

    .hero h1 {
        font-size: 2.5rem;
        color: #111;
        margin-bottom: 0.5rem;
    }

    .hero p {
        color: #666;
        font-size: 1.1rem;
    }

    .filter-panel {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .filter-panel .row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .filter-panel .row > * {
        flex: 1;
        min-width: 160px;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .service-card {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
}

.service-card:hover {
    transform: translateY(-4px);
}

.service-card img {
    width: 100%;
    height: 240px; /* Increased height */
    object-fit: cover;
}

.service-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.service-content h5 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.4rem;
}

.service-content p {
    font-size: 0.95rem;
    color: #444;
    margin-bottom: 1rem;
    flex-grow: 1;
    line-height: 1.5;
}

.taxonomy-section {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.taxonomy-pill {
    background-color: #f0f4ff;
    color: #0a58ca;
    font-weight: 500;
    font-size: 0.8rem;
    padding: 6px 12px;
    border-radius: 20px;
}

.price {
    display: inline-block;
    background: #e6f5ea;
    color: #2e7d32;
    font-size: 1rem;
    font-weight: 700;
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    align-self: flex-start;
    box-shadow: inset 0 0 0 1px #c2e3cf;
}


.vendor-profile {
    display: flex;
    align-items: center;
    background: #f9fafb;
    border: 1px solid #e4e7ec;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-top: auto;
    gap: 12px;
}

.vendor-profile img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
}

.vendor-meta {
    flex-grow: 1;
}

.vendor-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #111;
}

.vendor-link {
    font-size: 0.8rem;
    color: #007bff;
    text-decoration: none;
}

.vendor-link:hover {
    text-decoration: underline;
}

.view-btn {
    display: block;
    width: 100%;
    text-align: center;
    background: #007bff;
    color: white;
    padding: 0.85rem;
    font-weight: 600;
    border-radius: 0 0 14px 14px;
    text-decoration: none;
    transition: background 0.2s ease;
    margin-top: 0;
}

.view-btn:hover {
    background: #0056b3;
    color : white;
}


    .reset-link {
        text-align: right;
        margin-top: 1rem;
    }

    .reset-link a {
        color: #888;
        font-size: 0.9rem;
        text-decoration: underline;
    }
</style>

<div class="archive-wrapper">

    <div class="hero">
        <h1>Explore Services</h1>
        <p>Discover trusted services tailored to your needs.</p>
    </div>

    <form method="GET" id="service-filter-form" class="filter-panel">
        <div class="row">
            <input type="text" name="s" class="form-control" placeholder="Search..." value="<?php echo get_search_query(); ?>">

            <select class="form-select" name="service_type">
                <option value="">All Types</option>
                <?php
                    $types = get_terms(['taxonomy' => 'service_type', 'hide_empty' => false]);
                    foreach ($types as $type) {
                        $selected = (isset($_GET['service_type']) && $_GET['service_type'] === $type->slug) ? 'selected' : '';
                        echo "<option value='{$type->slug}' $selected>{$type->name}</option>";
                    }
                ?>
            </select>

            <select class="form-select" name="service_location">
                <option value="">All Locations</option>
                <?php
                    $locations = get_terms(['taxonomy' => 'service_location', 'hide_empty' => false]);
                    foreach ($locations as $location) {
                        $selected = (isset($_GET['service_location']) && $_GET['service_location'] === $location->slug) ? 'selected' : '';
                        echo "<option value='{$location->slug}' $selected>{$location->name}</option>";
                    }
                ?>
            </select>

            <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?php echo esc_attr($_GET['min_price'] ?? ''); ?>">
            <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?php echo esc_attr($_GET['max_price'] ?? ''); ?>">

            <button type="submit" class="btn btn-primary">Filter</button>
        </div>

        <?php if (!empty($_GET)) : ?>
        <div class="reset-link">
            <a href="<?php echo get_post_type_archive_link('service'); ?>">Reset filters</a>
        </div>
        <?php endif; ?>
    </form>

    <div class="service-grid">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post();
                $author_id = get_the_author_meta('ID');
                $profile_picture = get_user_meta($author_id, 'profile_picture', true);
                $vendor_name = get_the_author();
                $vendor_profile_url = site_url('/vendor/' . get_the_author_meta('user_login'));
            ?>
            <div class="service-card">
    <?php if (has_post_thumbnail()) : ?>
        <img src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
    <?php endif; ?>

    <div class="service-content">
        <h5><?php the_title(); ?></h5>
        <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>

        <div class="taxonomy-section">
            <?php
                $types = get_the_terms(get_the_ID(), 'service_type');
                $locations = get_the_terms(get_the_ID(), 'service_location');
                if ($types) {
                    foreach ($types as $type) {
                        echo "<span class='taxonomy-pill'>{$type->name}</span>";
                    }
                }
                if ($locations) {
                    foreach ($locations as $location) {
                        echo "<span class='taxonomy-pill'>{$location->name}</span>";
                    }
                }
            ?>
        </div>

        <div class="price">
    <?php
        $price = get_post_meta(get_the_ID(), '_service_price', true);
        echo $price ? 'Starting at $' . $price : 'Contact for quote';
    ?>
</div>


        <div class="vendor-profile">
            <img src="<?php echo esc_url($profile_picture ?: 'https://ui-avatars.com/api/?name=' . urlencode($vendor_name)); ?>" alt="<?php echo esc_attr($vendor_name); ?>">
            <div class="vendor-meta">
                <div class="vendor-name"><?php echo esc_html($vendor_name); ?></div>
                <a href="<?php echo esc_url($vendor_profile_url); ?>" class="vendor-link">View Profile</a>
            </div>
        </div>
    </div>

    <a href="<?php the_permalink(); ?>" class="view-btn">View Service</a>
</div>


            <?php endwhile; ?>
        <?php else : ?>
            <p>No services found.</p>
        <?php endif; ?>
    </div>
</div>

<?php wp_reset_postdata(); ?>
