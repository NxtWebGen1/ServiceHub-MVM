<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php
// Handle Delete
if (isset($_POST['delete_service']) && !empty($_POST['delete_service_id'])) {
    $service_id = intval($_POST['delete_service_id']);
    $current_user_id = get_current_user_id();
    $service_post = get_post($service_id);
    if ($service_post && $service_post->post_author == $current_user_id) {
        wp_delete_post($service_id, true);
        echo '<script>alert("Service deleted successfully!"); window.location.href="?page=vendor-dashboard&tab=services";</script>';
        exit;
    } else {
        echo '<script>alert("You do not have permission to delete this service!");</script>';
    }
}

$current_user_id = get_current_user_id();
$args = array(
    'post_type'      => 'service',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'author'         => $current_user_id
);
$query = new WP_Query($args);
?>

<div class="container-fluid mt-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Your Services</h2>
        <a href="?page=vendor-dashboard&tab=add-service" class="btn btn-primary">ADD NEW</a>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post();
                $id = get_the_ID();
                $price = get_post_meta($id, '_service_price', true);
                $discount = get_post_meta($id, '_service_discount_price', true);
                $duration = get_post_meta($id, '_service_duration', true);
                $status = get_post_meta($id, '_service_status', true);
                $schedule = get_post_meta($id, '_service_schedule', true);
            ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p><strong>Status:</strong> <span class="badge bg-<?php echo ($status === 'Active') ? 'success' : 'secondary'; ?>"><?php echo esc_html($status ?: 'Inactive'); ?></span></p>
                            <p><strong>Price:</strong> <?php echo $price ? '$' . esc_html($price) : 'Contact for quote'; ?></p>
                            <?php if ($discount): ?><p><strong>Discount Price:</strong> $<?php echo esc_html($discount); ?></p><?php endif; ?>
                            <?php if ($duration): ?><p><strong>Duration:</strong> <?php echo esc_html($duration); ?></p><?php endif; ?>
                            <p><strong>Location:</strong> <?php echo get_the_term_list($id, 'service_location', '', ', '); ?></p>
                            <p><strong>Availability:</strong> <?php echo esc_html($schedule ?: 'Not specified'); ?></p>
                        </div>
                        <div class="card-footer d-grid gap-2">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary">View Details</a>
                            <a href="?page=vendor-dashboard&tab=edit-service&edit_service=<?php echo $id; ?>" class="btn btn-outline-warning">Edit</a>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                <input type="hidden" name="delete_service_id" value="<?php echo $id; ?>">
                                <button type="submit" name="delete_service" class="btn btn-outline-danger">Delete</button>
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
