    <?php 



    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
        $order_id = (int) $_POST['order_id'];
        $new_status = sanitize_text_field($_POST['new_status']);
        $stored_vendor_id = (int) get_post_meta($order_id, '_vendor_id', true);
    
        if ($user_id === $stored_vendor_id) {
            update_post_meta($order_id, '_order_status', $new_status);
    
            $customer_email = get_post_meta($order_id, '_customer_email', true);
    
            if (!empty($customer_email) && filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
                $subject = "Order Status Updated - ServiceHub";
                $message = "Dear Customer,\n\nYour order (ID: {$order_id}) has been updated to '{$new_status}'.\n\nThank you for using our service.\n\nBest Regards,\nServiceHub Team";
    
                $admin_email = get_option('admin_email');
                $headers = [
                    'Content-Type: text/plain; charset=UTF-8',
                    'From: ServiceHub <' . $admin_email . '>'
                ];
    
                wp_mail($customer_email, $subject, $message, $headers);
            }
    
            echo '<script>window.location.href = window.location.href;</script>';
            exit;
        }
    }
        function get_orders_by_status($status, $vendor_id) {
        return new WP_Query([
            'post_type' => 'service_orders',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_order_status',
                    'value' => $status,
                    'compare' => '='
                ],
                [
                    'key' => '_vendor_id',
                    'value' => $vendor_id,
                    'compare' => '='
                ]
            ]
        ]);
    }


    $statuses = [
        'pending'   => ['title' => 'Pending Orders', 'color' => 'warning'],
        'approved'  => ['title' => 'Approved Orders', 'color' => 'success'],
        'completed' => ['title' => 'Completed Orders', 'color' => 'primary'],
        'canceled'  => ['title' => 'Canceled Orders', 'color' => 'danger']
    ];
    ?>

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
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Details</th>
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

                                            $location = get_post_meta($post_id, '_customer_address', true) ?: 'Not specified';
                                            $date     = get_post_meta($post_id, '_preferred_date', true) ?: 'Not specified';
                                            $notes    = get_post_meta($post_id, '_order_notes', true) ?: 'No additional notes';
                                            


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
                                                    <select name="new_status" class="form-select" onchange="confirmStatusChange(this, '<?php echo esc_attr($post_id); ?>')" <?php echo $is_disabled; ?>>
                                                        <option value="pending" <?php selected($order_status, 'pending'); ?>>Pending</option>
                                                        <option value="approved" <?php selected($order_status, 'approved'); ?>>Approved</option>
                                                        <option value="completed" <?php selected($order_status, 'completed'); ?>>Completed</option>
                                                        <option value="canceled" <?php selected($order_status, 'canceled'); ?>>Canceled</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal_<?php echo $post_id; ?>">View</button>
                                            </td>
                                        </tr>
                                                    <!--    FULL DETAIL POP UP -->
                                        <div class="modal fade" id="detailsModal_<?php echo $post_id; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Order Details - #<?php echo $post_id; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Service Name:</strong><br>
                                                                <?php echo esc_html(get_the_title($service_id)); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Customer Name:</strong><br>
                                                                <?php echo esc_html($customer_name); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Customer Email:</strong><br>
                                                                <?php echo esc_html($customer_email); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Customer Phone:</strong><br>
                                                                <?php echo esc_html($customer_phone); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Service Location:</strong><br>
                                                                <?php echo esc_html($location); ?>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <strong>Service Date & Time:</strong><br>
                                                                <?php echo esc_html($date); ?>
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <strong>Additional Notes:</strong><br>
                                                                <?php echo nl2br(esc_html($notes)); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php endwhile; wp_reset_postdata(); ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center text-danger">No <?php echo esc_html($status_data['title']); ?> found.</td></tr>
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


                <!-- JS LIBRARY FOR CONFIRMATION BOX ON STATUS CHANGE -->
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
        // SAVING LAST VALUE IN CASE CANCELING THE STATUS USING CONFIRMATION BOX
    document.addEventListener("DOMContentLoaded", function () {
        let selects = document.querySelectorAll("select[name='new_status']");
        selects.forEach(select => {
            select.addEventListener("focus", function () {
                this.dataset.previous = this.value;
            });
        });
    });
    </script>