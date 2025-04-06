<?php

function servicehub_mvm_register_dashboard_widgets() {
    wp_add_dashboard_widget('servicehub_total_vendors', 'Total Vendors', 'servicehub_mvm_widget_total_vendors');
    wp_add_dashboard_widget('servicehub_orders_today', 'Orders Today', 'servicehub_mvm_widget_orders_today');
    wp_add_dashboard_widget('servicehub_total_services', 'Total Services', 'servicehub_mvm_widget_total_services');
    wp_add_dashboard_widget('servicehub_total_customers', 'Total Customers', 'servicehub_mvm_widget_total_customers');
    wp_add_dashboard_widget('servicehub_pending_orders', 'Pending Orders', 'servicehub_mvm_widget_pending_orders');
    wp_add_dashboard_widget('servicehub_top_service', 'Top Booked Service', 'servicehub_mvm_widget_top_service');
    wp_add_dashboard_widget('servicehub_total_orders', 'All Orders', 'servicehub_mvm_widget_total_orders');
    wp_add_dashboard_widget('servicehub_services_week', 'New Services This Week', 'servicehub_mvm_widget_services_this_week');
}

function servicehub_mvm_widget_total_vendors() {
    $vendor_count = count(get_users(['role' => 'vendor']));
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $vendor_count . '</h3>
        <p>Vendors Registered</p>
    </div>';
}

function servicehub_mvm_widget_orders_today() {
    $orders = new WP_Query([
        'post_type' => 'service_orders',
        'date_query' => [[
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d')
        ]],
        'posts_per_page' => -1,
    ]);
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $orders->found_posts . '</h3>
        <p>Orders placed today</p>
    </div>';
}

function servicehub_mvm_widget_total_services() {
    $count = wp_count_posts('service')->publish;
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $count . '</h3>
        <p>Services Published</p>
    </div>';
}

function servicehub_mvm_widget_total_customers() {
    $count = count(get_users(['role' => 'customer']));
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $count . '</h3>
        <p>Registered Customers</p>
    </div>';
}

function servicehub_mvm_widget_pending_orders() {
    $pending = new WP_Query([
        'post_type' => 'service_orders',
        'post_status' => 'publish',
        'meta_key' => '_order_status',
        'meta_value' => 'pending',
        'posts_per_page' => -1,
    ]);
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $pending->found_posts . '</h3>
        <p>Pending Orders</p>
    </div>';
}

function servicehub_mvm_widget_services_this_week() {
    $week_start = strtotime('monday this week');
    $services = new WP_Query([
        'post_type' => 'service',
        'date_query' => [['after' => date('Y-m-d', $week_start)]],
        'posts_per_page' => -1
    ]);
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $services->found_posts . '</h3>
        <p>New Services This Week</p>
    </div>';
}

function servicehub_mvm_widget_total_orders() {
    $count = wp_count_posts('service_orders')->publish;
    echo '<div class="servicehub-mvm-widget-box">
        <h3>' . $count . '</h3>
        <p>Total Orders Received</p>
    </div>';
}

function servicehub_mvm_widget_top_service() {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT meta_value as service_id, COUNT(*) as count
        FROM $wpdb->postmeta
        WHERE meta_key = '_service_id'
        GROUP BY meta_value
        ORDER BY count DESC
        LIMIT 1
    ");
    if ($results) {
        $service_title = get_the_title($results[0]->service_id);
        echo '<div class="servicehub-mvm-widget-box">
            <h3>' . $service_title . '</h3>
            <p>' . $results[0]->count . ' bookings</p>
        </div>';
    } else {
        echo '<div class="servicehub-mvm-widget-box">
            <p>No bookings yet.</p>
        </div>';
    }
}

// Enqueue custom styles for dashboard widgets
function servicehub_mvm_dashboard_widget_styles() {
    echo '<style>
        .servicehub-mvm-widget-box {
            padding: 10px;
            background: #f8f9fa;
            border-left: 5px solid #007bff;
            margin-bottom: 10px;
        }
        .servicehub-mvm-widget-box h3 {
            margin: 0;
            color: #007bff;
            font-size: 32px;
            font-weight: bold;
        }
        .servicehub-mvm-widget-box p {
            margin: 0;
            font-size: 14px;
        }

        /* Widget heading styling */
        .postbox[id*="servicehub_total_vendors"] .hndle::before { content: "👥"; }
        .postbox[id*="servicehub_orders_today"] .hndle::before { content: "📦"; }
        .postbox[id*="servicehub_total_services"] .hndle::before { content: "🛠️"; }
        .postbox[id*="servicehub_total_customers"] .hndle::before { content: "🙋‍♂️"; }
        .postbox[id*="servicehub_pending_orders"] .hndle::before { content: "⏳"; }
        .postbox[id*="servicehub_top_service"] .hndle::before { content: "⭐"; }
        .postbox[id*="servicehub_total_orders"] .hndle::before { content: "🧾"; }
        .postbox[id*="servicehub_services_week"] .hndle::before { content: "📈"; }

        .postbox .hndle {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 16px;
        }
    </style>';
}
add_action('admin_head', 'servicehub_mvm_dashboard_widget_styles');

add_action('wp_dashboard_setup', 'servicehub_mvm_register_dashboard_widgets');
