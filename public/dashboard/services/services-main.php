<?php
// Get all services
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => -1, // Display all services
    'orderby'        => 'date',
    'order'          => 'DESC'
);

$query = new WP_Query($args);
?>

<div class="container mt-5">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text"><?php echo wp_trim_words(get_the_content(), 15); ?></p>

                            <p class="card-text">
                                <strong>Type:</strong> 
                                <?php echo get_the_term_list(get_the_ID(), 'service_type', '', ', '); ?><br>
                                <strong>Location:</strong> 
                                <?php echo get_the_term_list(get_the_ID(), 'service_location', '', ', '); ?>
                            </p>

                            <p class="card-text">
                                <strong>Price:</strong> 
                                <?php echo get_post_meta(get_the_ID(), '_service_price', true) ? '$' . get_post_meta(get_the_ID(), '_service_price', true) : 'Contact for quote'; ?>
                            </p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12">
                <p>No services found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php wp_reset_postdata(); ?>


