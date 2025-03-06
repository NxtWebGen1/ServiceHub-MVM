<?php 

// Logged in one's iD
$current_user = wp_get_current_user();  
$user_id = $current_user->ID;  

// Form Updating on dropdown change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    
    $order_id = (int) $_POST['order_id'];  // Ensure it's an integer
    $new_status = sanitize_text_field($_POST['new_status']); // Clean input for order status

    // Who created this Service
    $order_author = (int) get_post_field('post_author', $order_id); 

    // Allow only the SErvice's OWNER to update the status
    if ($user_id === $order_author) {
        update_post_meta($order_id, '_order_status', $new_status);
    }

    // Prevent form resubmission (refreshing the page)
    echo '<script>window.location.href = window.location.href;</script>';

    exit;
}

// QUERY: Fetch pending orders based on _order_status meta field
$pending_args = array(
    'post_type'      => 'service_orders',
    'author'         => $user_id,
    'meta_query'     => array(
        array(
            'key'     => '_order_status',
            'value'   => 'pending',
            'compare' => '='
        )
    ),
    'posts_per_page' => -1  
);
$pending_query = new WP_Query($pending_args);

// QUERY: Fetch approved orders based on _order_status meta field
$approved_args = array(
    'post_type'      => 'service_orders',
    'author'         => $user_id,
    'meta_query'     => array(
        array(
            'key'     => '_order_status',
            'value'   => 'approved',
            'compare' => '='
        )
    ),
    'posts_per_page' => -1  
);
$approved_query = new WP_Query($approved_args);

?>

<div class="container mt-4">

    <!-- PENDING ORDERS SECTION -->
    <h2 class="d-flex align-items-center text-warning">
        Pending Orders <span class="badge bg-warning text-dark ms-2"><?php echo $pending_query->found_posts; ?></span>
    </h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Service ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Customer Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($pending_query->have_posts()): ?>
                    <?php while ($pending_query->have_posts()): $pending_query->the_post(); 
                        $post_id = get_the_ID();
                        $service_id     = get_post_meta($post_id, '_service_id', true) ?: 'N/A';
                        $customer_name  = get_post_meta($post_id, '_customer_name', true) ?: 'N/A';
                        $customer_email = get_post_meta($post_id, '_customer_email', true) ?: 'N/A';
                        $customer_phone = get_post_meta($post_id, '_customer_phone', true) ?: 'N/A';
                        $order_status   = get_post_meta($post_id, '_order_status', true);
                    ?>
                        <tr>
                            <td><?php echo esc_html($service_id); ?></td>
                            <td><?php echo esc_html($customer_name); ?></td>
                            <td><?php echo esc_html($customer_email); ?></td>
                            <td><?php echo esc_html($customer_phone); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="order_id" value="<?php echo esc_attr($post_id); ?>">
                                    <select name="new_status" class="form-select" onchange="this.form.submit();"> <!-- THIS IS SUMITTING ON DROPDOWN CHANGE  -->
                                        <option value="pending" <?php selected($order_status, 'pending'); ?>>Pending</option>
                                        <option value="approved" <?php selected($order_status, 'approved'); ?>>Approved</option>
                                        <option value="completed" <?php selected($order_status, 'completed'); ?>>Completed</option>
                                        <option value="canceled" <?php selected($order_status, 'canceled'); ?>>Canceled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">No pending orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- APPROVED ORDERS SECTION -->
    <h2 class="d-flex align-items-center text-success mt-4">
        Approved Orders <span class="badge bg-success ms-2"><?php echo $approved_query->found_posts; ?></span>
    </h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Service ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Customer Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($approved_query->have_posts()): ?>
                    <?php while ($approved_query->have_posts()): $approved_query->the_post(); 
                        $post_id = get_the_ID();
                        $service_id     = get_post_meta($post_id, '_service_id', true) ?: 'N/A';
                        $customer_name  = get_post_meta($post_id, '_customer_name', true) ?: 'N/A';
                        $customer_email = get_post_meta($post_id, '_customer_email', true) ?: 'N/A';
                        $customer_phone = get_post_meta($post_id, '_customer_phone', true) ?: 'N/A';
                        $order_status   = get_post_meta($post_id, '_order_status', true);
                    ?>
                        <tr>
                            <td><?php echo esc_html($service_id); ?></td>
                            <td><?php echo esc_html($customer_name); ?></td>
                            <td><?php echo esc_html($customer_email); ?></td>
                            <td><?php echo esc_html($customer_phone); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="order_id" value="<?php echo esc_attr($post_id); ?>">
                                    <select name="new_status" class="form-select" onchange="this.form.submit();">
                                        <option value="pending" <?php selected($order_status, 'pending'); ?>>Pending</option>
                                        <option value="approved" <?php selected($order_status, 'approved'); ?>>Approved</option>
                                        <option value="completed" <?php selected($order_status, 'completed'); ?>>Completed</option>
                                        <option value="canceled" <?php selected($order_status, 'canceled'); ?>>Canceled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">No approved orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
