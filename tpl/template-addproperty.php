<?php

/**
 * Template Name: Add Property
 */

function lookway_booking_image_validation($file_name)
{
    $valid_extentions = ['jpg', 'jpeg', 'gif', 'png'];
    $exploded_array = explode('.', $file_name);
    if (!empty($exploded_array) && is_array($exploded_array)) {
        $ext = array_pop($exploded_array);
        return in_array($ext, $valid_extentions);
    } else {
        return false;
    }
}

function lookway_booking_insert_attachment($file_handler, $post_id, $setthumb = false)
{
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
    require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
    require_once(ABSPATH . 'wp-admin' . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);

    if ($setthumb) {
        update_post_meta($post_id, '_thumbnail_id', $attach_id);
    }
    return $attach_id;
}

$success = '';

if (isset($_POST['action']) && is_user_logged_in()) {
    if (wp_verify_nonce($_POST['property_nonce'], 'submit_property')) {
        $property_item = [];
        $property_item['post_title'] = sanitize_text_field($_POST['property_title']);
        $property_item['post_type'] = 'property';
        $property_item['post_content'] = sanitize_textarea_field($_POST['property_desc']);
        global $current_user;
        wp_get_current_user();
        $property_item['post_author'] = $current_user->ID;

        $property_action = $_POST['action'];

        if ($property_action == 'lookway_booking_add_property') {
            $property_item['post_status'] = 'pending';
            $property_item_id = wp_insert_post($property_item);

            if ($property_item_id > 0) {
                do_action('wp_insert_post', 'wp_insert_post');
                $success = "Property succesfull publish";
            }
        }

        if ($property_item_id > 0) {
            // metabox
            if (isset($_POST['property_offer']) && $_POST['property_offer'] != '') {
                update_post_meta($property_item_id, 'lookway_booking_type', $_POST['property_offer']);
            }
            if (isset($_POST['property_price'])) {
                update_post_meta($property_item_id, 'lookway_booking_price', $_POST['property_price']);
            }
            if (isset($_POST['property_period'])) {
                update_post_meta($property_item_id, 'lookway_booking_period', $_POST['property_period']);
            }
            if (isset($_POST['property_agent']) && $_POST['property_agent'] != 'disable') {
                update_post_meta($property_item_id, 'lookway_booking_sgent', $_POST['property_agent']);
            }

            // taxonomy
            if (isset($_POST['property_location'])) {
                wp_set_object_terms($property_item_id, intval($_POST['property_location']), 'location');
            }
            if (isset($_POST['property_type'])) {
                wp_set_object_terms($property_item_id, intval($_POST['property_type']), 'property-type');
            }

            // image
            if ($_FILES) {
                foreach ($_FILES as $submitted_file => $file_array) {
                    if (lookway_booking_image_validation($_FILES[$submitted_file]['name'])) {
                        $size = intval($_FILES[$submitted_file]['size']);
                        if ($size > 0) {
                            lookway_booking_insert_attachment($submitted_file, $property_item_id, true);
                        }
                    }
                }
            }
        }
    }
}

get_header(); ?>

<div class="wrapper">

    <?php
    if (have_posts()) {

        // Load posts loop.
        while (have_posts()) {
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2><?php the_title(); ?></h2>
                <div class="description"><?php the_content(); ?></div>
            </article>

            <?php if (is_user_logged_in()) {
                if (!empty($success)) {
                    echo $success;
                } else { ?>
                    <div class="add_form">
                        <form method="post" id="add_property" enctype="multipart/form-data">
                            <p>
                                <label for="property_title">Title</label>
                                <input type="text" name="property_title" id="property_title" placeholder="Add the Title" value="" required tabindex="1">
                            </p>
                            <p>
                                <label for="property_desc">Description</label>
                                <textarea name="property_desc" id="property_desc" placeholder="Add the Description" required tabindex="2"></textarea>
                            </p>
                            <p>
                                <label for="property_image">Image</label>
                                <input type="file" name="property_image" id="property_image" tabindex="3">
                            </p>
                            <p>
                                <label for="property_location">Location</label>
                                <select name="property_location" id="property_location" tabindex="4">
                                    <?php
                                    $locations = get_terms(['location'], ['hide_empty' => false]);
                                    if (!empty($locations)) {
                                        foreach ($locations as $location) {
                                            echo '<option value="' . $location->term_id . '">' . $location->name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <label for="property_type">Type</label>
                                <select name="property_type" id="property_type" tabindex="5">
                                    <?php
                                    $types = get_terms(['property-type'], ['hide_empty' => false]);
                                    if (!empty($types)) {
                                        foreach ($types as $type) {
                                            echo '<option value="' . $type->term_id . '">' . $type->name . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <label for="property_offer">Offer</label>
                                <select name="property_offer" id="property_offer" tabindex="6">
                                    <option value="" selected>Select Offer</option>
                                    <option value="rent">For Rent</option>
                                    <option value="sale">For Sale</option>
                                    <option value="sold">Sold</option>
                                </select>
                            </p>
                            <p>
                                <label for="property_price">Price</label>
                                <input type="text" name="property_price" id="property_price" placeholder="Change Price" value="" tabindex="7">
                            </p>
                            <p>
                                <label for="property_period">Period</label>
                                <input type="text" name="property_period" id="property_period" placeholder="Change Period" value="" tabindex="8">
                            </p>
                            <p>
                                <?php
                                global $current_user;
                                wp_get_current_user();
                                ?>
                                <label for="property_agent">Agent</label>
                                <select name="property_agent" id="property_agent" tabindex="9">
                                    <option value="disable" selected>Use Me</option>
                                    <?php
                                    $agents = get_posts(['post_type' => 'agent', 'post_per_page' => -1]);
                                    if (!empty($agents)) {
                                        foreach ($agents as $agent) {
                                            echo '<option value="' . $agent->ID . '">' . $agent->post_title . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </p>
                            <p>
                                <?php wp_nonce_field('submit_property', 'property_nonce'); ?>
                                <input type="submit" name="submit" tabindex="10" value="Add New Property">
                                <input type="hidden" name="action" value="lookway_booking_add_property">
                            </p>
                        </form>
                    </div>
    <?php
                }
            }
        }
    }
    ?>

</div>

<?php get_footer();
