<?php

if (!class_exists('LookwayBookingCpt')) {

    class LookwayBookingCpt
    {
        public function register()
        {
            add_action('init', [$this, 'custom_post_type']);

            add_action('add_meta_boxes', [$this, 'add_meta_box_property']);

            add_action('save_post', [$this, 'save_metabox'], 10, 2);
        }

        public function add_meta_box_property()
        {
            add_meta_box('lookway_booking_settings', 'Property Settings', [$this, 'metabox_property_html'], 'property', 'normal', 'default');
        }


        public function save_metabox($post_id, $post)
        {

            if (!isset($_POST['_lookway_booking']) || !wp_verify_nonce($_POST['_lookway_booking'], 'lookway_booking_fields')) {
                return $post_id;
            }

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            if ($post->post_type != 'property') {
                return $post_id;
            }

            $post_type = get_post_type_object($post->post_type);
            if (!current_user_can($post_type->cap->edit_post, $post_id)) {
                return $post_id;
            }

            if (is_null($_POST['lookway_booking_price'])) {
                delete_post_meta($post_id, 'lookway_booking_price');
            } else {
                update_post_meta($post_id, 'lookway_booking_price', sanitize_text_field(intval($_POST['lookway_booking_price'])));
            }

            if (is_null($_POST['lookway_booking_period'])) {
                delete_post_meta($post_id, 'lookway_booking_period');
            } else {
                update_post_meta($post_id, 'lookway_booking_period', sanitize_text_field($_POST['lookway_booking_period']));
            }

            if (is_null($_POST['lookway_booking_type'])) {
                delete_post_meta($post_id, 'lookway_booking_type');
            } else {
                update_post_meta($post_id, 'lookway_booking_type', sanitize_text_field($_POST['lookway_booking_type']));
            }

            if (is_null($_POST['lookway_booking_agent'])) {
                delete_post_meta($post_id, 'lookway_booking_agent');
            } else {
                update_post_meta($post_id, 'lookway_booking_agent', sanitize_text_field($_POST['lookway_booking_agent']));
            }

            return $post_id;
        }

        public function metabox_property_html($post)
        {
            $price = get_post_meta($post->ID, 'lookway_booking_price', true);
            $period = get_post_meta($post->ID, 'lookway_booking_period', true);
            $type = get_post_meta($post->ID, 'lookway_booking_type', true);
            $agent_meta = get_post_meta($post->ID, 'lookway_booking_agent', true);

            wp_nonce_field('lookway_booking_fields', '_lookway_booking');


            echo '
            <p>
                <label for="lookway_booking_price">' . esc_html__('Price', 'lookway-booking') . '</label>
                <input type="number" id="lookway_booking_price" name="lookway_booking_price" value="' . esc_html($price) . '">
            </p>

            <p>
                <label for="lookway_booking_period">' . esc_html__('Period', 'lookway-booking') . '</label>
                <input type="text" id="lookway_booking_period" name="lookway_booking_period" value="' . esc_html($period) . '">
            </p>

            <p>
                <label for="lookway_booking_type">' . esc_html__('Type', 'lookway-booking') . '</label>
                <select id="lookway_booking_type" name="lookway_booking_type">
                    <option value="">Select Type</option>
                    <option value="sale"' . selected('sale', $type, false) . '>' . esc_html__('For Sale', 'lookway-booking') . '</option>
                    <option value="rent"' . selected('rent', $type, false) . '>' . esc_html__('For Rent', 'lookway-booking') . '</option>
                    <option value="sold"' . selected('sold', $type, false) . '>' . esc_html__('Sold', 'lookway-booking') . '</option>
                </select>
            </p>

            ';

            $agents = get_posts([
                'post_type' => 'agent',
                'numberposts' => -1
            ]);

            if ($agents) {
                echo '
                <p>
                <label for="lookway_booking_agent">' . esc_html__('Agents', 'lookway-booking') . '</label>
                <select id="lookway_booking_agent" name="lookway_booking_agent">
                <option value="">' . esc_html__('Select Agent', 'lookway-booking') . '</option>
                ';


                foreach ($agents as $agent) { ?>
                    <option value="<?php echo esc_html($agent->ID); ?>" <?php if ($agent->ID == $agent_meta) {
                                                                            echo 'selected';
                                                                        } ?>><?php echo esc_html($agent->post_title); ?></option>
<?php }

                echo '</select></p>';
            }
        }

        public function custom_post_type()
        {
            register_post_type('property', [
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => 'properties'],
                'label' => esc_html__('Property', 'lookway-booking'),
                'supports' => ['title', 'editor', 'thumbnail']
            ]);

            register_post_type('agent', [
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => 'agents'],
                'label' => esc_html__('Agents', 'lookway-booking'),
                'supports' => ['title', 'editor', 'thumbnail'],
                'show_in_rest' => true
            ]);

            $labels = [
                'name'              => esc_html_x('Locations', 'taxonomy general name', 'lookway-booking'),
                'singular_name'     => esc_html_x('Location', 'taxonomy singular name', 'lookway-booking'),
                'search_items'      => esc_html__('Search Locations', 'lookway-booking'),
                'all_items'         => esc_html__('All Locations', 'lookway-booking'),
                'parent_item'       => esc_html__('Parent Location', 'lookway-booking'),
                'parent_item_colon' => esc_html__('Parent Location:', 'lookway-booking'),
                'edit_item'         => esc_html__('Edit Location', 'lookway-booking'),
                'update_item'       => esc_html__('Update Location', 'lookway-booking'),
                'add_new_item'      => esc_html__('Add New Location', 'lookway-booking'),
                'new_item_name'     => esc_html__('New Location Name', 'lookway-booking'),
                'menu_name'         => esc_html__('Location', 'lookway-booking'),
            ];

            $args = [
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => 'properties/location'],
                'labels' => $labels,
            ];

            register_taxonomy('location', 'property', $args);
            unset($labels);
            unset($args);

            $labels = [
                'name'              => esc_html_x('Types', 'taxonomy general name', 'lookway-booking'),
                'singular_name'     => esc_html_x('Type', 'taxonomy singular name', 'lookway-booking'),
                'search_items'      => esc_html__('Search Types', 'lookway-booking'),
                'all_items'         => esc_html__('All Types', 'lookway-booking'),
                'parent_item'       => esc_html__('Parent Type', 'lookway-booking'),
                'parent_item_colon' => esc_html__('Parent Type:', 'lookway-booking'),
                'edit_item'         => esc_html__('Edit Type', 'lookway-booking'),
                'update_item'       => esc_html__('Update Type', 'lookway-booking'),
                'add_new_item'      => esc_html__('Add New Type', 'lookway-booking'),
                'new_item_name'     => esc_html__('New Type Name', 'lookway-booking'),
                'menu_name'         => esc_html__('Type', 'lookway-booking'),
            ];

            $args = [
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'rewrite' => ['slug' => 'properties/type'],
                'labels' => $labels,
            ];

            register_taxonomy('property-type', 'property', $args);
            unset($labels);
            unset($args);
        }
    }
}

if (class_exists('LookwayBookingCpt')) {
    $lookwayBookingCpt = new LookwayBookingCpt();
    $lookwayBookingCpt->register();
}
