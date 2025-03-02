<?php
// Register Meta Box for Service Gallery
function servicehub_mvm_register_gallery_meta_box() {
    add_meta_box('service_gallery_meta_box', 'Service Gallery', 'servicehub_mvm_gallery_meta_box_callback', 'service', 'normal', 'high');
}
add_action('add_meta_boxes', 'servicehub_mvm_register_gallery_meta_box');

// Meta Box Callback
function servicehub_mvm_gallery_meta_box_callback($post) {
    $gallery_images = get_post_meta($post->ID, '_service_gallery', true);
    ?>
    <div id="service-gallery-container">
        <ul id="service-gallery-list">
            <?php
            if (!empty($gallery_images)) {
                foreach ($gallery_images as $image) {
                    echo '<li>
                            <img src="' . esc_url($image) . '" style="max-width:100px; margin-right:10px;">
                            <input type="hidden" name="service_gallery[]" value="' . esc_url($image) . '">
                            <button class="remove-image button">Remove</button>
                          </li>';
                }
            }
            ?>
        </ul>
        <input type="button" class="button service-gallery-upload" value="Add Images">
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('.service-gallery-upload').click(function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select Images for Gallery',
                    multiple: true,
                    library: { type: 'image' }
                });

                frame.on('select', function() {
                    var images = frame.state().get('selection').toJSON();
                    var container = $('#service-gallery-list');

                    images.forEach(function(image) {
                        container.append('<li><img src="' + image.url + '" style="max-width:100px; margin-right:10px;">' +
                            '<input type="hidden" name="service_gallery[]" value="' + image.url + '">' +
                            '<button class="remove-image button">Remove</button></li>');
                    });
                });

                frame.open();
            });

            $(document).on('click', '.remove-image', function(e) {
                e.preventDefault();
                $(this).parent().remove();
            });
        });
    </script>
    <?php
}

// Save Gallery Meta Box Data
function servicehub_mvm_save_gallery_meta_box($post_id) {
    if (isset($_POST['service_gallery'])) {
        update_post_meta($post_id, '_service_gallery', $_POST['service_gallery']);
    } else {
        delete_post_meta($post_id, '_service_gallery');
    }
}
add_action('save_post', 'servicehub_mvm_save_gallery_meta_box');

// Enqueue scripts for media uploader
function servicehub_mvm_gallery_enqueue($hook) {
    global $post;
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if ('service' === get_post_type($post)) {
            wp_enqueue_media();
            wp_enqueue_script('servicehub-mvm-gallery-script', plugin_dir_url(__FILE__) . 'gallery-script.js', array('jquery'), null, true);
        }
    }
}
add_action('admin_enqueue_scripts', 'servicehub_mvm_gallery_enqueue');
