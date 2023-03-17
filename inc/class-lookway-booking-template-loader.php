<?php

class LookwayBookingTemplateLoader extends Gamajo_Template_Loader
{
    protected $filter_prefix = 'lookway-booking';
    protected $theme_template_directory = 'lookway-booking';
    protected $plugin_directory = LOOKWAY_BOOKING_PATH;
    protected $plugin_template_directory = 'templates';
    
    public $templates;

    public function register()
    {
        add_filter('template_include', [$this, 'lookway_booking_templates']);

        $this->templates = [
            'pages/template-custom.php' => 'Custom Template'
        ];

        add_filter('theme_page_templates', [$this, 'custom_template']);
        add_filter('template_include', [$this, 'load_template']);
    }

    public function load_template($template)
    {
        global $post;
        $template_name = get_post_meta($post->ID, '_wp_page_template', true);

        if ($this->templates[$template_name]) {
            $file = LOOKWAY_BOOKING_PATH . 'pages/template-custom.php';

            if (file_exists($file)) {
                return $file;
            }
        }

        return $template;
    }

    public function custom_template($templates)
    {
        $templates = array_merge($templates, $this->templates);
        return $templates;
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
