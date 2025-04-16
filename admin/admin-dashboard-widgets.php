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
    wp_add_dashboard_widget('servicehub_analytics_widget', '📊 Service Marketplace Analytics', 'servicehub_mvm_render_analytics_widget');
    wp_add_dashboard_widget('servicehub_bookings_per_day_chart', '📅 Bookings Per Day (Last 7 Days)', 'servicehub_mvm_widget_bookings_per_day_chart');
    wp_add_dashboard_widget('servicehub_top_services_chart', '📈 Top 5 Most Booked Services', 'servicehub_mvm_render_top_services_chart');
    wp_add_dashboard_widget('servicehub_orders_by_type', '📚 Orders by Service Type', 'servicehub_mvm_render_orders_by_type_chart');
    wp_add_dashboard_widget('servicehub_top_vendors_widget', '🏆 Top Vendors by Bookings', 'servicehub_mvm_render_top_vendors_widget');
    wp_add_dashboard_widget('servicehub_top_service_types', '📌 Top Service Categories by Bookings', 'servicehub_mvm_render_top_service_types_widget');




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





function servicehub_mvm_render_analytics_widget() {
    $service_types = get_terms([
        'taxonomy'   => 'service_type',
        'hide_empty' => false
    ]);
    ?>
    <div>
        <canvas id="servicehub-analytics-chart" height="280" style="margin-bottom:20px;"></canvas>
        

        <label for="analytics-service-type"><strong>📊 Filter by Service Type:</strong></label>
        <select id="analytics-service-type">
            <option value="">All Services</option>
            <?php foreach ($service_types as $type) : ?>
                <option value="<?php echo esc_attr($type->term_id); ?>"><?php echo esc_html($type->name); ?></option>
            <?php endforeach; ?>
        </select>

        <p id="analytics-no-data" style="color:#dc3545; margin-top:10px; display:none;">⚠️ No data found or chart failed to render.</p>
    </div>

   <!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if (is_admin()) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('servicehub-analytics-chart');
    const ctx = canvas.getContext('2d');
    const dropdown = document.getElementById('analytics-service-type');
    const noData = document.getElementById('analytics-no-data');

    let chart;

    const loadChartData = (type = '') => {
        fetch(ajaxurl + '?action=fetch_servicehub_analytics&service_type=' + type)
            .then(res => res.json())
            .then(data => {
                if (!data || data.labels.length === 0) {
                    noData.style.display = 'block';
                    if (chart) chart.destroy();
                    return;
                }

                noData.style.display = 'none';

                if (chart) chart.destroy();

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Vendors by Service',
                            data: data.counts,
                            backgroundColor: '#4f46e5'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Vendors with Services (By Type)'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error("Chart load failed:", error);
                noData.textContent = '⚠️ Chart rendering error.';
                noData.style.display = 'block';
            });
    };

    dropdown.addEventListener('change', () => {
        loadChartData(dropdown.value);
    });

    loadChartData(); // initial load
});
</script>
<?php endif; ?>

    <?php
}






// Register AJAX handler for chart data
add_action('wp_ajax_fetch_servicehub_analytics', 'servicehub_mvm_fetch_analytics_data');
add_action('wp_ajax_nopriv_fetch_servicehub_analytics', 'servicehub_mvm_fetch_analytics_data');


function servicehub_mvm_fetch_analytics_data() {
    if (ob_get_length()) ob_clean(); // Clears previous output
    header('Content-Type: application/json');

    $service_type = isset($_GET['service_type']) ? intval($_GET['service_type']) : 0;

    $args = [
        'post_type'      => 'service',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ];

    if ($service_type) {
        $args['tax_query'] = [[
            'taxonomy' => 'service_type',
            'field'    => 'term_id',
            'terms'    => $service_type
        ]];
    }

    $query = new WP_Query($args);
    $vendor_counts = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $vendor_id = $post->post_author;
            $vendor = get_user_by('id', $vendor_id);

            if ($vendor && in_array('vendor', (array) $vendor->roles)) {
                $vendor_name = sanitize_text_field($vendor->display_name);

                if (isset($vendor_counts[$vendor_name])) {
                    $vendor_counts[$vendor_name]++;
                } else {
                    $vendor_counts[$vendor_name] = 1;
                }
            }
        }
    }

    // Final output
    wp_send_json([
        'labels' => array_keys($vendor_counts),
        'counts' => array_values($vendor_counts)
    ]);
}




function servicehub_mvm_widget_bookings_per_day_chart() {
    ?>
    <canvas id="servicehub-bookings-week-chart" height="280"></canvas>
    <p id="bookings-no-data" style="color:#dc3545; margin-top:10px; display:none;">⚠️ No data found or chart failed to render.</p>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('servicehub-bookings-week-chart');
        const ctx = canvas.getContext('2d');
        const noData = document.getElementById('bookings-no-data');

        fetch(ajaxurl + '?action=fetch_servicehub_bookings_per_day')
            .then(res => res.json())
            .then(data => {
                if (!data || data.labels.length === 0) {
                    noData.style.display = 'block';
                    return;
                }

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Bookings',
                            data: data.counts,
                            fill: true,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.2)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true },
                            title: {
                                display: true,
                                text: 'Bookings in Last 7 Days'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('📉 Chart load failed:', error);
                noData.textContent = '⚠️ Chart rendering error.';
                noData.style.display = 'block';
            });
    });
    </script>
    <?php
}



add_action('wp_ajax_fetch_servicehub_bookings_per_day', 'servicehub_mvm_fetch_bookings_per_day');

function servicehub_mvm_fetch_bookings_per_day() {
    // 🔒 Clean accidental output
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    global $wpdb;

    $labels = [];
    $counts = [];

    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', strtotime("-$i days"));
        $start = $day . ' 00:00:00';
        $end = $day . ' 23:59:59';

        $count = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->posts}
            WHERE post_type = 'service_orders'
            AND post_status = 'publish'
            AND post_date BETWEEN %s AND %s
        ", $start, $end));

        $labels[] = date('D', strtotime($day));
        $counts[] = (int) $count;
    }

    wp_send_json([
        'labels' => $labels,
        'counts' => $counts
    ]);
}




function servicehub_mvm_render_top_services_chart() {
    ?>
    <div>
        <canvas id="servicehub-top-services-chart" height="280" style="margin-bottom:20px;"></canvas>
        <p id="top-services-error" style="color:#dc3545; display:none;">⚠️ Chart rendering error or no data available.</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('servicehub-top-services-chart');
        const ctx = canvas.getContext('2d');
        const errorMsg = document.getElementById('top-services-error');

        fetch(ajaxurl + '?action=fetch_servicehub_top_services')
            .then(res => res.json())
            .then(data => {
                if (!data || data.labels.length === 0) {
                    errorMsg.style.display = 'block';
                    return;
                }

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Bookings',
                            data: data.counts,
                            backgroundColor: '#38bdf8'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Top 5 Most Booked Services'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Chart load failed:', error);
                errorMsg.style.display = 'block';
            });
    });
    </script>
    <?php
}


add_action('wp_ajax_fetch_servicehub_top_services', 'servicehub_mvm_fetch_top_services');

function servicehub_mvm_fetch_top_services() {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    global $wpdb;

    $results = $wpdb->get_results("
        SELECT meta_value as service_id, COUNT(*) as count
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_service_id'
        GROUP BY meta_value
        ORDER BY count DESC
        LIMIT 5
    ");

    $labels = [];
    $counts = [];

    foreach ($results as $row) {
        $title = get_the_title($row->service_id);
        if ($title) {
            $labels[] = $title;
            $counts[] = (int) $row->count;
        }
    }

    wp_send_json([
        'labels' => $labels,
        'counts' => $counts
    ]);
}




function servicehub_mvm_render_orders_by_type_chart() {
    ?>
    <div>
        <canvas id="orders-by-type-chart" height="280" style="margin-bottom:20px;"></canvas>
        <p id="orders-type-error" style="color:#dc3545; display:none;">⚠️ Chart rendering error or no data available.</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('orders-by-type-chart').getContext('2d');
        const errorBox = document.getElementById('orders-type-error');

        fetch(ajaxurl + '?action=fetch_orders_by_service_type')
            .then(res => res.json())
            .then(data => {
                if (!data || data.labels.length === 0) {
                    errorBox.style.display = 'block';
                    return;
                }

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Orders',
                            data: data.counts,
                            backgroundColor: ['#4f46e5', '#22c55e', '#f97316', '#ec4899', '#0ea5e9']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            title: {
                                display: true,
                                text: 'Orders Distribution by Service Type'
                            }
                        }
                    }
                });
            })
            .catch(err => {
                console.error('Chart load failed:', err);
                errorBox.style.display = 'block';
            });
    });
    </script>
    <?php
}



add_action('wp_ajax_fetch_orders_by_service_type', 'servicehub_mvm_fetch_orders_by_service_type');

function servicehub_mvm_fetch_orders_by_service_type() {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    global $wpdb;

    $results = $wpdb->get_results("
        SELECT pm.meta_value as service_id
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_service_id' AND p.post_type = 'service_orders' AND p.post_status = 'publish'
    ");

    $service_type_counts = [];

    foreach ($results as $row) {
        $types = wp_get_post_terms($row->service_id, 'service_type', ['fields' => 'names']);
        foreach ($types as $type) {
            if (!isset($service_type_counts[$type])) {
                $service_type_counts[$type] = 1;
            } else {
                $service_type_counts[$type]++;
            }
        }
    }

    wp_send_json([
        'labels' => array_keys($service_type_counts),
        'counts' => array_values($service_type_counts)
    ]);
}





function servicehub_mvm_render_top_vendors_widget() {
    ?>
    <div>
        <canvas id="top-vendors-chart" height="280" style="margin-bottom:20px;"></canvas>
        <p id="top-vendors-error" style="color:#dc3545; display:none;">⚠️ Chart rendering error or no data found.</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('top-vendors-chart').getContext('2d');
        const errorMsg = document.getElementById('top-vendors-error');

        fetch(ajaxurl + '?action=fetch_top_vendors_by_bookings')
            .then(res => res.json())
            .then(data => {
                if (!data || data.labels.length === 0) {
                    errorMsg.style.display = 'block';
                    return;
                }

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Bookings',
                            data: data.counts,
                            backgroundColor: '#10b981'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Top Vendors by Number of Bookings'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            })
            .catch(err => {
                console.error("Chart load failed:", err);
                errorMsg.style.display = 'block';
            });
    });
    </script>
    <?php
}


add_action('wp_ajax_fetch_top_vendors_by_bookings', 'servicehub_mvm_fetch_top_vendors_by_bookings');

function servicehub_mvm_fetch_top_vendors_by_bookings() {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    global $wpdb;

    $results = $wpdb->get_results("
        SELECT meta_value as vendor_id, COUNT(*) as bookings
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_vendor_id'
        GROUP BY meta_value
        ORDER BY bookings DESC
        LIMIT 10
    ");

    $labels = [];
    $counts = [];

    foreach ($results as $row) {
        $vendor = get_userdata($row->vendor_id);
        if ($vendor) {
            $labels[] = $vendor->display_name;
            $counts[] = intval($row->bookings);
        }
    }

    wp_send_json([
        'labels' => $labels,
        'counts' => $counts
    ]);
}



function servicehub_mvm_render_top_service_types_widget() {
    ?>
    <div>
        <canvas id="top-service-types-chart" height="280" style="margin-bottom:20px;"></canvas>
        <p id="top-service-types-error" style="color:#dc3545; display:none;">⚠️ Chart rendering error or no data found.</p>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('top-service-types-chart').getContext('2d');
        const errorMsg = document.getElementById('top-service-types-error');

        fetch(ajaxurl + '?action=fetch_top_service_types')
            .then(res => res.json())
            .then(data => {
                if (!data || data.labels.length === 0) {
                    errorMsg.style.display = 'block';
                    return;
                }

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Bookings',
                            data: data.counts,
                            backgroundColor: '#6366f1'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Most Booked Service Categories'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            })
            .catch(err => {
                console.error("Chart load failed:", err);
                errorMsg.style.display = 'block';
            });
    });
    </script>
    <?php
}



add_action('wp_ajax_fetch_top_service_types', 'servicehub_mvm_fetch_top_service_types');

function servicehub_mvm_fetch_top_service_types() {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');

    global $wpdb;

    $results = $wpdb->get_results("
        SELECT p.ID
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'service_orders'
        AND p.post_status = 'publish'
        AND pm.meta_key = '_service_id'
    ");

    $service_type_counts = [];

    foreach ($results as $order) {
        $service_id = get_post_meta($order->ID, '_service_id', true);
        $terms = wp_get_post_terms($service_id, 'service_type');

        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $name = $term->name;
                if (isset($service_type_counts[$name])) {
                    $service_type_counts[$name]++;
                } else {
                    $service_type_counts[$name] = 1;
                }
            }
        }
    }

    wp_send_json([
        'labels' => array_keys($service_type_counts),
        'counts' => array_values($service_type_counts)
    ]);
}
