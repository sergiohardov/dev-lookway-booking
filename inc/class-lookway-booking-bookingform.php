<?php

class LookwayBookingBookingform
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
        add_action('init', [$this, 'lookway_booking_booking_shortcode']);
        add_action('wp_ajax_booking_form', [$this, 'booking_form']);
        add_action('wp_ajax_nopriv_booking_form', [$this, 'booking_form']);
    }

    public function enqueue()
    {
        wp_enqueue_script('lookway-booking-bookingform', plugins_url('lookway-booking/assets/js/front/bookingform.js'), ['jquery'], '1.0', true);
        wp_localize_script('lookway-booking-bookingform', 'lookway_booking_bookingform_var', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('_wpnonce'),
            'title' => esc_html__('Booking Form', 'lookway-booking')
        ]);
    }

    public function lookway_booking_booking_shortcode()
    {
        add_shortcode('lookway_booking_booking', [$this, 'booking_form_html']);
    }

    public function booking_form_html($atts, $content)
    {
        echo '
        <div id="lookway_booking_result"></div>
        <form method="post">
        <p>
            <input type="text" name="name" id="lookway_booking_name">
            <input type="text" name="email" id="lookway_booking_email">
            <input type="text" name="phone" id="lookway_booking_phone">
            <input type="submit" name="Submit" id="lookway_booking_booking_submit">
        </p>
        </form>
        ';
    }

    function booking_form()
    {
        check_ajax_referer('_wpnonce', 'nonce');

        if (!empty($_POST)) {
            if (isset($_POST['name'])) {
                $name = sanitize_text_field($_POST['name']);
            }
            if (isset($_POST['email'])) {
                $email = sanitize_text_field($_POST['email']);
            }
            if (isset($_POST['phone'])) {
                $phone = sanitize_text_field($_POST['phone']);
            }
            echo $name . " " . $phone;
        }

        wp_die();
    }
}

$lookwayBookingBookingform = new LookwayBookingBookingform();
