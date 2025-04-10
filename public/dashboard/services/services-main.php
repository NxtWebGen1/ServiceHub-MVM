<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    .service-card {
        border: 1px solid #dee2e6;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
</style>

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

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="fw-bold">My Services</h2>
        <a href="?page=vendor-dashboard&tab=add-service" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Add New Service
        </a>
    </div>

    <div class="row g-4">
        <?php if ($query->have_posts()) : ?>
            <?php while ($query->have_posts()) : $query->the_post();
                $id = get_the_ID();
                $price = get_post_meta($id, '_service_price', true);
                $discount = get_post_meta($id, '_service_discount_price', true);
                $duration = get_post_meta($id, '_service_duration', true);
                $status = get_post_meta($id, '_service_status', true);
                $schedule = get_post_meta($id, '_service_schedule', true);
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 service-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title mb-2"><?php the_title(); ?></h5>
                            <span class="badge bg-<?php echo ($status === 'Active') ? 'success' : 'secondary'; ?>">
                                <?php echo esc_html($status ?: 'Inactive'); ?>
                            </span>
                            <ul class="list-unstyled mt-3 mb-0">
                                <li><i class="bi bi-cash-coin me-2"></i><strong>Price:</strong> <?php echo $price ? '$' . esc_html($price) : 'Contact for quote'; ?></li>
                                <?php if ($discount): ?>
                                    <li><i class="bi bi-tag me-2"></i><strong>Discount:</strong> $<?php echo esc_html($discount); ?></li>
                                <?php endif; ?>
                                <?php if ($duration): ?>
                                    <li><i class="bi bi-clock me-2"></i><strong>Duration:</strong> <?php echo esc_html($duration); ?></li>
                                <?php endif; ?>
                                <li><i class="bi bi-geo-alt me-2"></i><strong>Location:</strong> <?php echo get_the_term_list($id, 'service_location', '', ', '); ?></li>
                                <li><i class="bi bi-calendar-check me-2"></i><strong>Availability:</strong> <?php echo esc_html($schedule ?: 'Not specified'); ?></li>
                            </ul>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex flex-column gap-2">
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            <a href="?page=vendor-dashboard&tab=edit-service&edit_service=<?php echo $id; ?>" class="btn btn-outline-warning w-100">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                <input type="hidden" name="delete_service_id" value="<?php echo $id; ?>">
                                <button type="submit" name="delete_service" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> No services found.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php wp_reset_postdata(); ?>
