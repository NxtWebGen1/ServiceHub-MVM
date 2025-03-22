<?php 

// Get current logged-in vendor
$current_user = wp_get_current_user();  
$user_id = $current_user->ID;  

// Handle order status change submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = (int) $_POST['order_id'];
    $new_status = sanitize_text_field($_POST['new_status']);

    // Check if current vendor is the order author
    $order_author = (int) get_post_field('post_author', $order_id); 

    if ($user_id === $order_author) {
        update_post_meta($order_id, '_order_status', $new_status);

        // Get customer email from custom field
        $customer_email = get_post_meta($order_id, '_customer_email', true);

        // Validate and send email notification
        if (!empty($customer_email) && filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {

            $subject = "Order Status Updated - ServiceHub";

            $message = "Dear Customer,\n\n";
            $message .= "Your order (ID: {$order_id}) has been updated to '{$new_status}'.\n\n";
            $message .= "Thank you for using our service.\n\n";
            $message .= "Best Regards,\nServiceHub Team";

            // Use admin email as sender
            $admin_email = get_option('admin_email');
            $headers = [
                'Content-Type: text/plain; charset=UTF-8',
                'From: ServiceHub <' . $admin_email . '>'
            ];

            // Send the email and optionally log failure
            if (!wp_mail($customer_email, $subject, $message, $headers)) {
                error_log("Email failed to send to {$customer_email} for order #{$order_id}");
            }
        }

        // Refresh page via JS to reflect changes
        echo '<script>window.location.href = window.location.href;</script>';
        exit;
    }
}

// Function to fetch orders based on status
function get_orders_by_status($status, $user_id) {
    return new WP_Query([
        'post_type'      => 'service_orders',
        'author'         => $user_id,
        'meta_query'     => [[
            'key'     => '_order_status',
            'value'   => $status,
            'compare' => '='
        ]],
        'posts_per_page' => -1
    ]);
}

// Order status categories and UI colors
$statuses = [
    'pending'   => ['title' => 'Pending Orders', 'color' => 'warning'],
    'approved'  => ['title' => 'Approved Orders', 'color' => 'success'],
    'completed' => ['title' => 'Completed Orders', 'color' => 'primary'],
    'canceled'  => ['title' => 'Canceled Orders', 'color' => 'danger']
];

?>

<!-- UI: Order Status Accordion Table Layout -->
<div class="container mt-4">
    <div class="accordion" id="orderStatusAccordion">

        <?php foreach ($statuses as $status_key => $status_data): 
            $orders = get_orders_by_status($status_key, $user_id);
        ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed bg-<?php echo esc_attr($status_data['color']); ?> text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo esc_attr($status_key); ?>">
                    <?php echo esc_html($status_data['title']); ?>
                    <span class="badge bg-dark ms-2"><?php echo $orders->found_posts; ?></span>
                </button>
            </h2>

            <div id="collapse-<?php echo esc_attr($status_key); ?>" class="accordion-collapse collapse">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
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
                                <?php if ($orders->have_posts()): ?>
                                    <?php while ($orders->have_posts()): $orders->the_post(); 
                                        $post_id = get_the_ID();
                                        $service_id = get_post_meta($post_id, '_service_id', true) ?: 'N/A';
                                        $customer_name = get_post_meta($post_id, '_customer_name', true) ?: 'N/A';
                                        $customer_email = get_post_meta($post_id, '_customer_email', true) ?: 'N/A';
                                        $customer_phone = get_post_meta($post_id, '_customer_phone', true) ?: 'N/A';
                                        $order_status = get_post_meta($post_id, '_order_status', true);

                                        $is_disabled = ($order_status === 'canceled' || $order_status === 'completed') ? 'disabled' : '';
                                    ?>
                                    <tr>
                                        <td><?php echo esc_html($service_id); ?></td>
                                        <td><?php echo esc_html($customer_name); ?></td>
                                        <td><?php echo esc_html($customer_email); ?></td>
                                        <td><?php echo esc_html($customer_phone); ?></td>
                                        <td>
                                            <form method="post" id="statusForm_<?php echo esc_attr($post_id); ?>">
                                                <input type="hidden" name="order_id" value="<?php echo esc_attr($post_id); ?>">
                                                <select name="new_status" class="form-select"
                                                    onchange="confirmStatusChange(this, '<?php echo esc_attr($post_id); ?>')"
                                                    <?php echo $is_disabled; ?>>
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
                                        <td colspan="5" class="text-center text-danger">No <?php echo esc_html($status_data['title']); ?> found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>

<!-- SweetAlert JS for Confirmation Popup -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmStatusChange(selectElement, orderId) {
    let newStatus = selectElement.value;

    Swal.fire({
        title: "Confirm Status Change",
        text: "Are you sure you want to update the order status to " + newStatus + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, update it!",
        cancelButtonText: "No, cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("statusForm_" + orderId).submit();
        } else {
            selectElement.value = selectElement.dataset.previous;
        }
    });
}

// Store previously selected value in case user cancels
document.addEventListener("DOMContentLoaded", function () {
    let selects = document.querySelectorAll("select[name='new_status']");
    selects.forEach(select => {
        select.addEventListener("focus", function () {
            this.dataset.previous = this.value;
        });
    });
});
</script>