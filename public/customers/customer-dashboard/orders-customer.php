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

<style>
    .order-block {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 20px 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .order-block:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
    }
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .order-id {
        font-weight: 600;
        color: #333;
    }
    .order-badge {
        font-size: 0.85rem;
    }
    .order-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }
    .order-detail {
        background: #f9fafb;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 0.95rem;
        border: 1px solid #e4e4e4;
    }
    .order-detail strong {
        display: block;
        margin-bottom: 4px;
        color: #555;
        font-weight: 600;
    }
</style>

<h4 class="fw-bold mb-4">📦 My Orders</h4>

<?php if ($orders_query->have_posts()) : ?>
    <?php $count = 1; while ($orders_query->have_posts()) : $orders_query->the_post(); 
        $service_id     = get_post_meta(get_the_ID(), '_service_id', true);
        $preferred_date = get_post_meta(get_the_ID(), '_preferred_date', true);
        $status         = get_post_meta(get_the_ID(), '_order_status', true);
        $notes          = get_post_meta(get_the_ID(), '_order_notes', true);
        $vendor_id      = get_post_meta(get_the_ID(), '_vendor_id', true);
        $vendor         = get_userdata($vendor_id);
        $badge_class = match($status) {
            'pending'   => 'warning',
            'approved'  => 'success',
            'completed' => 'primary',
            'canceled'  => 'danger',
            default     => 'secondary'
        };
    ?>
        <div class="order-block">
            <div class="order-header">
                <div class="order-id">Order #<?php echo $count++; ?> - <?php echo esc_html(get_the_title($service_id)); ?></div>
                <span class="badge bg-<?php echo $badge_class; ?> order-badge"> <?php echo ucfirst($status); ?> </span>
            </div>
            <div class="order-details-grid">
                <div class="order-detail">
                    <strong>Date & Time</strong>
                    <?php echo esc_html($preferred_date); ?>
                </div>
                <div class="order-detail">
                    <strong>Vendor</strong>
                    <?php echo esc_html($vendor->display_name ?? 'Unknown'); ?>
                </div>
                <div class="order-detail">
                    <strong>Vendor Email</strong>
                    <?php echo esc_html($vendor->user_email ?? 'N/A'); ?>
                </div>
                <div class="order-detail">
                    <strong>Additional Notes</strong>
                    <?php echo $notes ? nl2br(esc_html($notes)) : '<span class="text-muted">—</span>'; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else : ?>
    <div class="alert alert-info">You have not placed any orders yet.</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>