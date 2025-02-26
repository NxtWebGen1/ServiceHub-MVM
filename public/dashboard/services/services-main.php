<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php


//HANDLE DELETE BUTTON
if (isset($_POST['delete_service']) && !empty($_POST['delete_service_id'])) {
    $service_id = intval($_POST['delete_service_id']);
    $current_user_id = get_current_user_id();

    // Verify the user owns the post before deleting
    $service_post = get_post($service_id);
    if ($service_post && $service_post->post_author == $current_user_id) {
        wp_delete_post($service_id, true); // 'true' forces permanent deletion
        echo '<script>alert("Service deleted successfully!"); window.location.href="?page=vendor-dashboard&tab=services";</script>';
        exit;
    } else {
        echo '<script>alert("You do not have permission to delete this service!");</script>';
    }
}



// Get current user ID
$current_user_id = get_current_user_id();

// Query services created by the current vendor
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => -1, // Display all services
    'orderby'        => 'date',
    'order'          => 'DESC',
    'author'         => $current_user_id // Filter by current user
);

$query = new WP_Query($args);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Your Services</h2>
        <a href="?page=vendor-dashboard&tab=add-service" class="btn btn-primary">ADD NEW</a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text"><strong>Price:</strong> <?php echo get_post_meta(get_the_ID(), '_service_price', true) ? '$' . get_post_meta(get_the_ID(), '_service_price', true) : 'Contact for quote'; ?></p>
                            <p class="card-text"><strong>Location:</strong> <?php echo get_the_term_list(get_the_ID(), 'service_location', '', ', '); ?></p>
                            <p class="card-text"><strong>Availability:</strong> <?php echo get_post_meta(get_the_ID(), '_service_schedule', true) ?: 'Not specified'; ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary w-100">View Details</a>
                            <a href="?page=vendor-dashboard&tab=edit-service&edit_service=<?php echo get_the_ID(); ?>" class="btn btn-outline-warning w-100">Edit</a>
                            
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this service?');" style="display:inline;">
                                <input type="hidden" name="delete_service_id" value="<?php echo get_the_ID(); ?>">
                                <button type="submit" name="delete_service" class="btn btn-outline-danger w-100 mt-2">Delete</button>
                            </form>
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
