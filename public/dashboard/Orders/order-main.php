<?php 

$current_user = wp_get_current_user();  
$user_id = $current_user->ID;  


// PENDING ORDERS
$pending_args = array(
    'post_type'      => 'service_orders',
    'author'         => $user_id,
    'post_status'    => 'pending',   
    'posts_per_page' => -1  
);

$pending_query = new WP_Query($pending_args);

// FINISHED ORDERSS
$published_args = array(
    'post_type'      => 'service_orders',
    'author'         => $user_id,
    'post_status'    => 'publish',
    'posts_per_page' => -1  
);

$published_query = new WP_Query($published_args);
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
                        // Get post ID
                        $post_id = get_the_ID();
                        
                        // Retrieve meta data
                        $service_id     = get_post_meta($post_id, '_service_id', true);
                        $customer_name  = get_post_meta($post_id, '_customer_name', true);
                        $customer_email = get_post_meta($post_id, '_customer_email', true);
                        $customer_phone = get_post_meta($post_id, '_customer_phone', true);
                        $order_status   = get_post_meta($post_id, '_order_status', true);
                    ?>
                        <tr>
                            
                            <td><?php echo esc_html($service_id ?: 'N/A'); ?></td>
                            <td><?php echo esc_html($customer_name ?: 'N/A'); ?></td>
                            <td><?php echo esc_html($customer_email ?: 'N/A'); ?></td>
                            <td><?php echo esc_html($customer_phone ?: 'N/A'); ?></td>
                            <td class="text-warning fw-bold">PENDING</td>
                        </tr>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">No pending orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PUBLISHED ORDERS SECTION -->
    <h2 class="d-flex align-items-center text-success mt-4">
        Published Orders <span class="badge bg-success ms-2"><?php echo $published_query->found_posts; ?></span>
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
                <?php if ($published_query->have_posts()): ?>
                    <?php while ($published_query->have_posts()): $published_query->the_post(); 
                        // Get post ID
                        $post_id = get_the_ID();
                        
                        // Retrieve meta data
                        $service_id     = get_post_meta($post_id, '_service_id', true);
                        $customer_name  = get_post_meta($post_id, '_customer_name', true);
                        $customer_email = get_post_meta($post_id, '_customer_email', true);
                        $customer_phone = get_post_meta($post_id, '_customer_phone', true);
                        $order_status   = get_post_meta($post_id, '_order_status', true);
                    ?>
                        <tr>
                            <td><?php echo esc_html($service_id ?: 'N/A'); ?></td>
                            <td><?php echo esc_html($customer_name ?: 'N/A'); ?></td>
                            <td><?php echo esc_html($customer_email ?: 'N/A'); ?></td>
                            <td><?php echo esc_html($customer_phone ?: 'N/A'); ?></td>
                            <td class="text-success fw-bold">PUBLISHED</td>
                        </tr>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">No published orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
