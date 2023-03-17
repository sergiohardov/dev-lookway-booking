<?php

/**
 * Plugin Name: Lookway Booking
 * Description: Plugin for booking.
 * Version: 1.0
 * Author: Sergio Hardov
 * Author URI: https://t.me/sergiohardov
 * License: GPLv2 or later
 * Text Domain: lookway-booking
 * Domain Path: /lang
 */

if (!defined('ABSPATH')) {
    die;
}

define('LOOKWAY_BOOKING_PATH', plugin_dir_path(__FILE__));

if (!class_exists('LookwayBookingCpt')) {
    require LOOKWAY_BOOKING_PATH . 'inc/class-lookway-booking-cpt.php';
}
if (!class_exists('Gamajo_Template_Loader')) {
    require LOOKWAY_BOOKING_PATH . 'inc/class-gamajo-template-loader.php';
}

require LOOKWAY_BOOKING_PATH . 'inc/class-lookway-booking-template-loader.php';
require LOOKWAY_BOOKING_PATH . 'inc/class-lookway-booking-shortcodes.php';
require LOOKWAY_BOOKING_PATH . 'inc/class-lookway-booking-filter-widget.php';
require LOOKWAY_BOOKING_PATH . 'inc/class-lookway-booking-elementor.php';
require LOOKWAY_BOOKING_PATH . 'inc/class-lookway-booking-bookingform.php';

class LookwayBooking
{

    function register()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
        add_action('plugins_loaded', [$this, 'load_text_domain']);
        add_action('widgets_init', [$this, 'register_widget']);
        add_action('admin_menu', [$this, 'add_menu_item']);

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_plugin_setting_link']);

        add_action('admin_init', [$this, 'settings_init']);
    }

    public function settings_init()
    {
        register_setting('lookway_booking_settings', 'lookway_booking_settings_options');
        add_settings_section('lookway_booking_settings_section', 'Settings', [$this, 'lookway_booking_settings_section_html'], 'lookway_booking_settings_page');
        add_settings_field('filter_title', 'Title for Filter', [$this, 'filter_title_html'], 'lookway_booking_settings_page', 'lookway_booking_settings_section');
        add_settings_field('archive_title', 'Title for Archive Page', [$this, 'archive_title_html'], 'lookway_booking_settings_page', 'lookway_booking_settings_section');
    }

    public function lookway_booking_settings_section_html()
    {
        esc_html_e('Settings for Lookway Booking Plugin');
    }

    public function filter_title_html()
    {
        $options = get_option('lookway_booking_settings_options'); ?>
        <input type="text" name="lookway_booking_settings_options[filter_title]" value="<?php echo isset($options['filter_title']) ? $options['filter_title'] : ''; ?>">
    <?php
    }

    public function archive_title_html()
    {
        $options = get_option('lookway_booking_settings_options'); ?>
        <input type="text" name="lookway_booking_settings_options[archive_title]" value="<?php echo isset($options['archive_title']) ? $options['archive_title'] : ''; ?>">
<?php
    }

    public function add_menu_item()
    {
        add_menu_page(
            esc_html__('Lookway Booking Settings Page', 'lookway-booking'),
            'Lookway Booking',
            'manage_options',
            'lookway_booking_settings_page',
            [$this, 'main_admin_page'],
            'dashicons-admin-plugins',
            100
        );
    }

    public function add_plugin_setting_link($link)
    {
        $lookway_booking_link = '<a href="admin.php?page=lookway_booking_settings">' . esc_html__('Settings Page', 'lookway-booking') . '</a>';
        array_push($link, $lookway_booking_link);
        return $link;
    }

    public function main_admin_page()
    {
        require_once LOOKWAY_BOOKING_PATH . 'admin/welcome.php';
    }

    public function register_widget()
    {
        register_widget('LookwayBookingFilterWidget');
    }

    public function get_terms_hierarchical($tax_name, $current_term)
    {
        $taxonomy_terms = get_terms($tax_name, ['hide_empty' => false, 'parent' => 0]);

        $html = '';

        if (!empty($taxonomy_terms)) {
            foreach ($taxonomy_terms as $term) {
                if ($current_term == $term->term_id) {
                    $html .= '<option value="' . $term->term_id . '"selected>' . $term->name . '</option>';
                } else {
                    $html .= '<option value="' . $term->term_id . '">' . $term->name . '</option>';
                }

                $child_terms = get_terms($tax_name, ['hide_empty' => false, 'parent' => $term->term_id]);

                if (!empty($child_terms)) {
                    foreach ($child_terms as $child) {
                        if ($current_term == $child->term_id) {
                            $html .= '<option value="' . $child->term_id . '"selected> - ' . $child->name . '</option>';
                        } else {
                            $html .= '<option value="' . $child->term_id . '"> - ' . $child->name . '</option>';
                        }
                    }
                }
            }
        }
        return $html;
    }

    function load_text_domain()
    {
        load_plugin_textdomain('lookway-booking', false, dirname(plugin_basename(__FILE__)) . '/lang');
    }

    public function enqueue_admin()
    {
        wp_enqueue_style('lookway-booking-style-admin', plugins_url('/assets/css/admin/style.css', __FILE__));
        wp_enqueue_script('lookway-booking-script-admin', plugins_url('/assets/js/admin/script.js', __FILE__), ['jquery'], '1.0', true);
    }

    public function enqueue_front()
    {
        wp_enqueue_style('lookway-booking-style', plugins_url('/assets/css/front/style.css', __FILE__));
        wp_enqueue_script('lookway-booking-script', plugins_url('/assets/js/front/script.js', __FILE__), ['jquery'], '1.0', true);
    }

    static function activation()
    {
        flush_rewrite_rules();
    }

    static function deactivation()
    {
        flush_rewrite_rules();
    }
}

if (class_exists('LookwayBooking')) {
    $lookwayBooking = new LookwayBooking();
    $lookwayBooking->register();
}


register_activation_hook(__FILE__, [$lookwayBooking, 'activation']);
register_deactivation_hook(__FILE__, [$lookwayBooking, 'deactivation']);
