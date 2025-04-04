<?php
$current_user_id = get_current_user_id();

$args = [
    'post_type'      => 'service_orders',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query'     => [
        [
            'key'     => '_customer_email',
            'value'   => wp_get_current_user()->user_email,
            'compare' => '='
        ]
    ]
];

$orders_query = new WP_Query($args);
?>

<h4 class="mb-4">📦 My Orders</h4>

<?php if ($orders_query->have_posts()) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Vendor</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1; while ($orders_query->have_posts()) : $orders_query->the_post(); 
                    $service_id    = get_post_meta(get_the_ID(), '_service_id', true);
                    $preferred_date = get_post_meta(get_the_ID(), '_preferred_date', true);
                    $status        = get_post_meta(get_the_ID(), '_order_status', true);
                    $notes         = get_post_meta(get_the_ID(), '_order_notes', true);
                    $vendor_id     = get_post_meta(get_the_ID(), '_vendor_id', true);
                    $vendor        = get_userdata($vendor_id);
                ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo esc_html(get_the_title($service_id)); ?></td>
                        <td><?php echo esc_html($preferred_date); ?></td>
                        <td>
                            <?php
                            $badge_class = match($status) {
                                'pending'   => 'warning',
                                'approved'  => 'success',
                                'completed' => 'primary',
                                'canceled'  => 'danger',
                                default     => 'secondary'
                            };
                            ?>
                            <span class="badge bg-<?php echo $badge_class; ?>">
                                <?php echo ucfirst($status); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo esc_html($vendor->display_name ?? 'Unknown'); ?><br>
                            <small><?php echo esc_html($vendor->user_email ?? 'N/A'); ?></small>
                        </td>
                        <td><?php echo nl2br(esc_html($notes)); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <div class="alert alert-info">You have not placed any orders yet.</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
