<?php
get_header();
?>

<div class="container my-5">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="row">
            <!-- Service Gallery or Featured Image -->
            <div class="col-lg-6">
                <?php if (has_post_thumbnail()) : ?>
                    <img src="<?php the_post_thumbnail_url('large'); ?>" class="img-fluid rounded mb-4" alt="<?php the_title(); ?>">
                <?php endif; ?>
            </div>

            <!-- Service Details -->
            <div class="col-lg-6">
                <h1><?php the_title(); ?></h1>

                <p><strong>Service Type:</strong> <?php echo get_the_term_list(get_the_ID(), 'service_type', '', ', '); ?></p>
                <p><strong>Location:</strong> <?php echo get_the_term_list(get_the_ID(), 'service_location', '', ', '); ?></p>

                <p><strong>Price:</strong> 
                    <?php echo get_post_meta(get_the_ID(), '_service_price', true) ? '$' . get_post_meta(get_the_ID(), '_service_price', true) : 'Contact for quote'; ?>
                </p>

                <p><strong>Availability:</strong> 
                    <?php echo esc_html(get_post_meta(get_the_ID(), '_service_schedule', true)); ?>
                </p>
            </div>
        </div>

        <!-- Service Description -->
        <div class="mt-5">
            <?php the_content(); ?>
        </div>

    <?php endwhile; endif; ?>
</div>

<?php
get_footer();
?>
