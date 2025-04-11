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
function get_orders_by_status($status, $vendor_id)
{
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
    'pending' => ['title' => 'Pending Orders', 'color' => 'warning'],
    'approved' => ['title' => 'Approved Orders', 'color' => 'success'],
    'completed' => ['title' => 'Completed Orders', 'color' => 'primary'],
    'canceled' => ['title' => 'Canceled Orders', 'color' => 'danger']
];
?>

<!-- Redesigned Orders Section with Stylish Layout and Modal -->
<!-- Redesigned Orders Section with Stylish Layout and Modal -->
<div class="container py-4">
  <h3 class="fw-bold mb-4">Manage Your Orders</h3>

  <div class="accordion" id="orderStatusAccordion">
    <?php foreach ($statuses as $status_key => $status_data):
      $orders = get_orders_by_status($status_key, $user_id);
    ?>
      <div class="accordion-item mb-4 border border-light-subtle shadow-sm rounded-3 overflow-hidden">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed bg-light text-dark rounded-top-3 fw-semibold" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo esc_attr($status_key); ?>">
            <i class="fa-solid fa-circle me-2 text-<?php echo esc_attr($status_data['color']); ?>"></i>
            <?php echo esc_html($status_data['title']); ?>
            <span class="badge rounded-pill ms-2 bg-<?php echo esc_attr($status_data['color']); ?> text-white"><?php echo $orders->found_posts; ?></span>
          </button>
        </h2>
        <div id="collapse-<?php echo esc_attr($status_key); ?>" class="accordion-collapse collapse">
          <div class="accordion-body bg-white rounded-bottom-3">
            <div class="table-responsive">
              <table class="table align-middle table-borderless">
                <thead class="table-light">
                  <tr class="text-muted text-uppercase small">
                    <th>Service ID</th>
                    <th>Customer</th>
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
                      $date = get_post_meta($post_id, '_preferred_date', true) ?: 'Not specified';
                      $notes = get_post_meta($post_id, '_order_notes', true) ?: 'No additional notes';
                      $is_disabled = ($order_status === 'canceled' || $order_status === 'completed') ? 'disabled' : '';
                    ?>
                    <tr>
                      <td><span class="fw-semibold text-dark"><?php echo esc_html($service_id); ?></span></td>
                      <td><?php echo esc_html($customer_name); ?></td>
                      <td><?php echo esc_html($customer_email); ?></td>
                      <td><?php echo esc_html($customer_phone); ?></td>
                      <td>
                        <form method="post" id="statusForm_<?php echo esc_attr($post_id); ?>">
                          <input type="hidden" name="order_id" value="<?php echo esc_attr($post_id); ?>">
                          <select name="new_status" class="form-select form-select-sm rounded-pill px-3"
                            onchange="confirmStatusChange(this, '<?php echo esc_attr($post_id); ?>')"
                            <?php echo $is_disabled; ?>>
                            <option value="pending" <?php selected($order_status, 'pending'); ?>>Pending</option>
                            <option value="approved" <?php selected($order_status, 'approved'); ?>>Approved</option>
                            <option value="completed" <?php selected($order_status, 'completed'); ?>>Completed</option>
                            <option value="canceled" <?php selected($order_status, 'canceled'); ?>>Canceled</option>
                          </select>
                        </form>
                      </td>
                      <td>
                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3 view-more" data-bs-toggle="modal"
                          data-bs-target="#detailsModal_<?php echo $post_id; ?>">
                          <i class="fa fa-eye me-1"></i> View
                        </button>
                      </td>
                    </tr>

                    <!-- MODAL -->
                    <div class="modal fade" id="detailsModal_<?php echo $post_id; ?>" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 shadow border-0">
                          <div class="modal-header bg-primary text-white rounded-top-4">
                            <h5 class="modal-title">Order Details #<?php echo $post_id; ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body px-4 py-3">
                            <div class="row g-3">
                              <div class="col-md-6"><strong>Service Name:</strong><br><?php echo esc_html(get_the_title($service_id)); ?></div>
                              <div class="col-md-6"><strong>Customer:</strong><br><?php echo esc_html($customer_name); ?></div>
                              <div class="col-md-6"><strong>Email:</strong><br><?php echo esc_html($customer_email); ?></div>
                              <div class="col-md-6"><strong>Phone:</strong><br><?php echo esc_html($customer_phone); ?></div>
                              <div class="col-md-6"><strong>Location:</strong><br><?php echo esc_html($location); ?></div>
                              <div class="col-md-6"><strong>Date & Time:</strong><br><?php echo esc_html($date); ?></div>
                              <div class="col-md-12"><strong>Notes:</strong><br><?php echo nl2br(esc_html($notes)); ?></div>
                            </div>
                          </div>
                          <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <?php endwhile; wp_reset_postdata(); ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center text-muted">No <?php echo esc_html($status_data['title']); ?> found.</td>
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

<style>
    .accordion-button {
        background-color: #FFFFFF1A;
        color: #000 !important
    }

    /* down arrow  */
    .accordion-button::after {
        filter: invert(28%) sepia(48%) saturate(2588%) hue-rotate(182deg);
    }

    /* VIEW MORE BUTTON */
    .view-more {
        border-color: #0d6efd;
        color: #0d6efd;
    }

    .view-more:hover {
        background-color: #0d6efd;
        color: #fff
    }

    .form#statusForm_29 {
        margin-bottom: 0;
    }

    /* Styling the Select Dropdown */


    .form-select:focus,
    .form-select:hover {
        border-color: #0d6efd;
        /* Change border color on focus (Bootstrap Primary) */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        /* Subtle focus shadow */
    }

    .accordion-button:focus {
        background-color: #d8e8ff;
    }

    /* down arrow on active */
    .accordion-button:focus .accordion-button::after {
        filter: invert(100%) brightness(100%) !important;
    }

    td form {
        margin-bottom: 0;
    }

    button.btn-close {
        filter: invert(100%) brightness(100%) !important;
    }

    button.btn.btn-outline-danger.btn-lg.px-4{
        border-color: #0d6efd;
        color:#0d6efd;
        border-radius: 10px;
        
    }
    button.btn.btn-outline-danger.btn-lg.px-4:hover{
        background-color: #0d6efd;
        border-color:#0d6efd;
        color:#fff

    }
    .badge{
        background-color: #0d6efd !important;
        color:#fff !important
    }

/* Apply striped rows to the table */
.table-responsive .table tbody tr:nth-child(odd) {
    background-color: #f8f9fa;  /* Light grey color for odd rows */
}

.table-responsive .table tbody tr:nth-child(even) {
    background-color: #ffffff;  /* White color for even rows */
}

.table-responsive .table tbody tr:hover {
    background-color: #e2e6ea;  /* Subtle hover effect for rows */
}

</style>