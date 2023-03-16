<?php get_header(); ?>

<?php $lookwayBookingTemplateLoader->get_template_part('partials/filter'); ?>

<div class="wrapper archive_property">

    <?php

    if (!empty($_POST['submit'])) {

        $args = [
            'post_type' => 'property',
            'posts_per_page' => -1,
            'meta_query' => ['relation' => 'AND'],
            'tax_query' => ['relation' => 'AND'],
        ];

        if (isset($_POST['lookway_booking_type']) && $_POST['lookway_booking_type'] != '') {
            array_push($args['meta_query'], [
                'key' => 'lookway_booking_type',
                'value' => esc_attr($_POST['lookway_booking_type']),
            ]);
        }

        if (isset($_POST['lookway_booking_price']) && $_POST['lookway_booking_price'] != '') {
            array_push($args['meta_query'], [
                'key' => 'lookway_booking_price',
                'value' => esc_attr($_POST['lookway_booking_price']),
                'type' => 'numeric',
                'compare' => '<='
            ]);
        }

        if (isset($_POST['lookway_booking_agent']) && $_POST['lookway_booking_agent'] != '') {
            array_push($args['meta_query'], [
                'key' => 'lookway_booking_agent',
                'value' => esc_attr($_POST['lookway_booking_agent']),
            ]);
        }

        $properties = new WP_Query($args);

        if ($properties->have_posts()) {

            // Load posts loop.
            while ($properties->have_posts()) {
                $properties->the_post();
                $lookwayBookingTemplateLoader->get_template_part('partials/content');
            }
        } else {
            echo '<p>' . esc_html__('No Properties', 'lookway-booking') . '</p>';
        }
    } else {
        if (have_posts()) {

            // Load posts loop.
            while (have_posts()) {
                the_post();
                $lookwayBookingTemplateLoader->get_template_part('partials/content');
            }
            // pagination
            posts_nav_link();
        } else {
            echo '<p>' . esc_html__('No Properties', 'lookway-booking') . '</p>';
        }
    }

    ?>
</div>

<?php get_footer(); ?>