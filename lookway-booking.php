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

class LookwayBooking
{
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
}


register_activation_hook(__FILE__, [$lookwayBooking, 'activation']);
register_deactivation_hook(__FILE__, [$lookwayBooking, 'deactivation']);
