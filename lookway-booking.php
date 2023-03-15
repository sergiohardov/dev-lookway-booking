<?php

/**
 * Plugin Name: Lookway Booking
 * Description: Plugin for booking.
 * Version: 1.0
 * Author: Sergio Hardov
 * Author URI: https://t.me/sergiohardov
 * License: GPLv2 or later
 * Text Domain: lookway-booking
 */

if (!defined('ABSPATH')) {
    die;
}

define('LOOKWAY_BOOKING_PATH', plugin_dir_path(__FILE__));

if (!class_exists('LookwayBookingCpt')) {
    require LOOKWAY_BOOKING_PATH . 'inc/cpt.php';
}

class LookwayBooking
{

    function register()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
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
