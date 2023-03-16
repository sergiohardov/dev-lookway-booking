<?php

class LookwayBookingTemplateLoader extends Gamajo_Template_Loader
{
    protected $filter_prefix = 'lookway-booking';
    protected $theme_template_directory = 'lookway-booking';
    protected $plugin_directory = LOOKWAY_BOOKING_PATH;
    protected $plugin_template_directory = 'templates';

    public function register()
    {
        add_filter('template_include', [$this, 'lookway_booking_templates']);
    }

    public function lookway_booking_templates($template)
    {

        if (is_post_type_archive('property')) {
            $theme_files = ['archive-property.php', 'lookway-booking/archive-property.php'];
            $exist = locate_template($theme_files, false);

            if ($exist != '') {
                return $exist;
            } else {
                return plugin_dir_path(__DIR__) . 'templates/archive-property.php';
            }
        } else if (is_post_type_archive('agent')) {
            $theme_files = ['archive-agent.php', 'lookway-booking/archive-agent.php'];
            $exist = locate_template($theme_files, false);

            if ($exist != '') {
                return $exist;
            } else {
                return plugin_dir_path(__DIR__) . 'templates/archive-agent.php';
            }
        } else if (is_singular('property')) {
            $theme_files = ['single-property.php', 'lookway-booking/single-property.php'];
            $exist = locate_template($theme_files, false);

            if ($exist != '') {
                return $exist;
            } else {
                return plugin_dir_path(__DIR__) . 'templates/single-property.php';
            }
        } else if (is_singular('agent')) {
            $theme_files = ['single-agent.php', 'lookway-booking/single-agent.php'];
            $exist = locate_template($theme_files, false);

            if ($exist != '') {
                return $exist;
            } else {
                return plugin_dir_path(__DIR__) . 'templates/single-agent.php';
            }
        }

        return $template;
    }
}

$lookwayBookingTemplateLoader = new LookwayBookingTemplateLoader();
$lookwayBookingTemplateLoader->register();
