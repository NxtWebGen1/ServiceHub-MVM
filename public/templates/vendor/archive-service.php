<!-- Add in <head> section -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


<?php
// Template for displaying service archive
// Get all services
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => -1, // Display all services
    'orderby'        => 'date',
    'order'          => 'DESC'
);

$query = new WP_Query($args);
?>

<div class="container py-5">
    <h1 class="text-center mb-4">Our Services</h1>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden service-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="service-image-wrapper">
                                <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2"><?php the_title(); ?></h5>
                            <p class="card-text text-secondary mb-3"><?php echo wp_trim_words(get_the_content(), 15); ?></p>

                            <div class="mb-3">
                                <span class="badge bg-primary me-1"><strong>Type:</strong> <?php echo strip_tags(get_the_term_list(get_the_ID(), 'service_type', '', ', ')); ?></span>
                                <span class="badge bg-secondary"><strong>Location:</strong> <?php echo strip_tags(get_the_term_list(get_the_ID(), 'service_location', '', ', ')); ?></span>
                            </div>

                            <p class="fw-bold mb-0 text-success">
                                <?php echo get_post_meta(get_the_ID(), '_service_price', true) ? '$' . get_post_meta(get_the_ID(), '_service_price', true) : 'Contact for quote'; ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white text-center border-0 pb-4">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary w-75">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12 text-center">
                <p class="text-muted fs-5">No services found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php wp_reset_postdata(); ?>

<style>
    .service-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .service-image-wrapper {
        height: 200px;
        overflow: hidden;
    }

    .service-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
