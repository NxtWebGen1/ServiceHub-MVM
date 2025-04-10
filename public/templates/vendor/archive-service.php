<?php get_header(); ?>

<?php
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish'
);

global $wp_query;
$query = $wp_query;
?>

<style>
    body {
        background: #f4f7fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .modern-grid {
        max-width: 1300px;
        margin: 3rem auto;
        padding: 0 1rem;
    }

    .modern-grid h1 {
        font-size: 2.2rem;
        text-align: center;
        margin-bottom: 2.5rem;
        color: #222;
    }

    /* Filter Form Container */
    #service-filter-form {
        background-color: #ffffff;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 3rem;
        position: sticky;
        top: 0;
        z-index: 100;
        overflow-x: auto;
        white-space: nowrap;
    }

    /* Flex Layout for Filters */
    #service-filter-form .row {
        display: flex;
        gap: 30px;
        overflow-x: scroll;
        flex-wrap: nowrap;
    }

    #service-filter-form .row::-webkit-scrollbar {
        height: 8px;
        background-color: #f1f1f1;
    }

    #service-filter-form .row::-webkit-scrollbar-thumb {
        background-color: #007bff;
        border-radius: 10px;
    }

    /* Filter Inputs & Selects */
    .filter-input,
    .filter-select {
        padding: 12px;
        font-size: 1rem;
        border-radius: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
        min-width: 220px;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #007bff;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
    }

    /* Filter Button */
    .filter-btn {
        padding: 7px;
        font-size: 1.1rem;
        background-color: #007bff;
        color: white;
        border-radius: 8px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .filter-btn:hover {
        background-color: #0056b3;
    }
    #service-filter-form .row {
    -ms-overflow-style: none;  /* For Internet Explorer and Edge */
    scrollbar-width: none;      /* For Firefox */
}

#service-filter-form .row::-webkit-scrollbar {
    display: none;  /* For Chrome, Safari, and Opera */
}

    /* Responsive Design for Smaller Screens */
    @media (max-width: 767px) {
        #service-filter-form .row {
            flex-wrap: no-wrap;
        }

        .filter-input,
        .filter-select {
            min-width: 100%;
        }

        .filter-btn {
            width: 100%;
        }
    }

    /* Service Cards Styling */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .service-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid transparent;
    }

    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgb(2, 118, 233);
    }

    .service-image {
        height: 200px;
        overflow: hidden;
    }

    .service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-content {
        padding: 1.2rem 1.5rem;
        flex-grow: 1;
    }

    .service-content h5 {
        font-size: 1.5rem;
        margin-bottom: 0.6rem;
        color: #0066cc;
    }

    .service-content p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .badges {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 0.8rem;
        min-height: 25px;
    }

    .badge {
        font-size: 0.75rem;
        background: #e0ecff;
        color: #0066cc;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 500;
    }

    .price {
        font-weight: bold;
        color: #000;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .vendor-box {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: auto;
        padding: 1rem 1.5rem 0;
        border-top: 1px solid #f1f1f1;
    }

    .vendor-box img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    .vendor-box .vendor-name {
        font-size: 0.9rem;
        color: #333;
        font-weight: 600;
    }

    .vendor-box .vendor-link {
        display: block;
        font-size: 0.75rem;
        color: #0077cc;
        text-decoration: none;
    }

    .view-btn {
        margin: 1rem 1.5rem;
        padding: 0.6rem 1rem;
        background: #007bff;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        text-align: center;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .view-btn:hover {
        background: #0056b3;
    }
</style>

<div class="modern-grid">
    <h1>Explore Our Services</h1>

    <form method="GET" id="service-filter-form" class="mb-4 p-3 bg-light rounded shadow-sm">
        <div class="row g-3">
            <!-- Keyword -->
            <div class="col-md-3">
                <input type="text" name="s" class="form-control filter-input" placeholder="🔍 Search by keyword" value="<?php echo get_search_query(); ?>">
            </div>

            <!-- Service Type -->
            <div class="col-md-2">
                <select class="form-select filter-select" name="service_type">
                    <option value="">All Types</option>
                    <?php
                    $types = get_terms(['taxonomy' => 'service_type', 'hide_empty' => false]);
                    foreach ($types as $type) {
                        $selected = (isset($_GET['service_type']) && $_GET['service_type'] === $type->slug) ? 'selected' : '';
                        echo "<option value='{$type->slug}' $selected>{$type->name}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Location -->
            <div class="col-md-2">
                <select class="form-select filter-select" name="service_location">
                    <option value="">All Locations</option>
                    <?php
                    $locations = get_terms(['taxonomy' => 'service_location', 'hide_empty' => false]);
                    foreach ($locations as $location) {
                        $selected = (isset($_GET['service_location']) && $_GET['service_location'] === $location->slug) ? 'selected' : '';
                        echo "<option value='{$location->slug}' $selected>{$location->name}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Min Price -->
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control filter-input" placeholder="Min Price" value="<?php echo esc_attr($_GET['min_price'] ?? ''); ?>">
            </div>

            <!-- Max Price -->
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control filter-input" placeholder="Max Price" value="<?php echo esc_attr($_GET['max_price'] ?? ''); ?>">
            </div>

            <!-- Submit Button -->
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100 filter-btn">Filter</button>
            </div>
        </div>
    </form>

    <!-- Reset Button if Filters are Applied -->
    <?php if (!empty($_GET)) : ?>
        <div class="mt-3 text-end">
            <a href="<?php echo get_post_type_archive_link('service'); ?>" class="btn btn-outline-secondary mb-5">
                Reset Filters
            </a>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('service-filter-form');
            if (!filterForm) return;

            filterForm.addEventListener('submit', function (e) {
                const inputs = filterForm.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.disabled = true; // Prevent empty fields from being submitted
                    }
                });
            });
        });
    </script>

    <div class="grid">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post();
                $author_id = get_the_author_meta('ID');
                $profile_picture = get_user_meta($author_id, 'profile_picture', true);
                $vendor_name = get_the_author();
                $vendor_profile_url = site_url('/vendor/' . get_the_author_meta('user_login'));
                ?>
                <div class="service-card">

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="service-image">
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
                        </div>
                    <?php endif; ?>

                    <div class="service-content">
                        <h5><?php the_title(); ?></h5>
                        <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>

                        <div class="badges">
                            <?php
                            $types = get_the_term_list(get_the_ID(), 'service_type', '', ', ');
                            $locations = get_the_term_list(get_the_ID(), 'service_location', '', ', ');
                            if ($types) echo '<span class="badge">Type: ' . strip_tags($types) . '</span>';
                            if ($locations) echo '<span class="badge">Location: ' . strip_tags($locations) . '</span>';
                            ?>
                        </div>

                        <div class="price">
                            <?php echo get_post_meta(get_the_ID(), '_service_price', true)
                                ? '$' . get_post_meta(get_the_ID(), '_service_price', true)
                                : 'Contact for quote'; ?>
                        </div>
                    </div>

                    <div class="vendor-box">
                        <img src="<?php echo esc_url($profile_picture ?: 'https://ui-avatars.com/api/?name=' . urlencode($vendor_name)); ?>" alt="<?php echo esc_attr($vendor_name); ?>">
                        <div>
                            <div class="vendor-name"><?php echo esc_html($vendor_name); ?></div>
                            <a href="<?php echo esc_url($vendor_profile_url); ?>" class="vendor-link">View Profile</a>
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
