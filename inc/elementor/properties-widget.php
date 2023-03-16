<?php

class ElementorLookwayBookingWidget extends \Elementor\Widget_Base
{

    protected $lookwayBookingTemplateLoader;

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
        return ['general'];
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

        $this->end_controls_section();
    }


    protected function render()
    {

        $settings = $this->get_settings_for_display();

        $properties = new WP_Query([
            'post_type' => 'property',
            'posts_per_page' => $settings['count'],
        ]);

        $this->lookwayBookingTemplateLoader = new LookwayBookingTemplateLoader();

        if ($properties->have_posts()) {
            echo '<div class="wrapper archive_property">';
            while ($properties->have_posts()) {
                $properties->the_post();

                $this->lookwayBookingTemplateLoader->get_template_part('partials/content');
            }
            echo '</div>';
        }
    }
}
