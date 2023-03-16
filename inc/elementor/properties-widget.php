<?php

class ElementorLookwayBookingWidget extends \Elementor\Widget_Base
{

    protected $lookwayBookingTemplateLoader;
    protected $lookwayBookingLocations = ['' => 'Select Location'];

    public function get_name()
    {
        return 'lookway-booking';
    }

    public function get_title()
    {
        return esc_html__('Properties List', 'lookway-booking');
    }

    public function get_icon()
    {
        return 'eicon-code';
    }

    public function get_categories()
    {
        return ['lookway-booking'];
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'lookway-booking'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'count',
            [
                'label' => esc_html__('Post Count', 'lookway-booking'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 3,
            ]
        );

        $temp_locations = get_terms('location');

        foreach ($temp_locations as $location) {
            $this->lookwayBookingLocations[$location->term_id] = $location->name;
        }

        $this->add_control(
            'offer',
            [
                'label' => esc_html__('Offer', 'lookway-booking'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Select Offer', 'lookway-booking'),
                    'sale' => esc_html__('For Sale', 'lookway-booking'),
                    'rent' => esc_html__('For Rent', 'lookway-booking'),
                    'sold'  => esc_html__('Sold', 'lookway-booking'),
                ],
            ]
        );

        $this->add_control(
            'location',
            [
                'label' => esc_html__('Location', 'lookway-booking'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->lookwayBookingLocations,
            ]
        );

        $this->end_controls_section();
    }


    protected function render()
    {

        $settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'property',
            'posts_per_page' => $settings['count'],
            'meta_query' => ['relation' => 'AND'],
            'tax_query' => ['relation' => 'AND'],
        ];

        if (isset($settings['offer']) && $settings['offer'] != '') {
            array_push($args['meta_query'], [
                'key' => 'lookway_booking_type',
                'value' => esc_attr($settings['offer']),
            ]);
        }

        if (isset($settings['location']) && $settings['location'] != '') {
            array_push($args['tax_query'], [
                'taxonomy' => 'location',
                'terms' => esc_attr($settings['location']),
            ]);
        }

        $properties = new WP_Query($args);

        $this->lookwayBookingTemplateLoader = new LookwayBookingTemplateLoader();

        if ($properties->have_posts()) {
            echo '<div class="wrapper archive_property">';
            while ($properties->have_posts()) {
                $properties->the_post();

                $this->lookwayBookingTemplateLoader->get_template_part('partials/content');
            }
            echo '</div>';
        }
        wp_reset_postdata();
    }
}
