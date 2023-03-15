<?php

$bookings = get_posts([
    'post_type' => ['booking', 'agent'], 'numberposts' => -1
]);

foreach ($bookings as $booking) {
    wp_delete_post($booking->ID, true);
}
