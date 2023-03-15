<?php

if (!class_exists('LookwayBookingCpt')) {

    class LookwayBookingCpt
    {
        public function register()
        {
            add_action('init', [$this, 'custom_post_type']);
        }

        public function custom_post_type()
        {
            register_post_type('property', [
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => 'properties'],
                'label' => 'Property',
                'supports' => ['title', 'editor', 'thumbnail']
            ]);

            register_post_type('agent', [
                'public' => true,
                'has_archive' => true,
                'rewrite' => ['slug' => 'agents'],
                'label' => 'Agents',
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

            register_taxonomy('poperty-type', 'property', $args);
            unset($labels);
            unset($args);
        }
    }
}

if (class_exists('LookwayBookingCpt')) {
    $lookwayBookingCpt = new LookwayBookingCpt();
    $lookwayBookingCpt->register();
}
