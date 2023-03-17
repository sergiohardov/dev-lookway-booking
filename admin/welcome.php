<h1><?php echo esc_html__('Welcome to Lookway Booking', 'lookway-booking'); ?></h1>
<div class="content">
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php
        settings_fields('lookway_booking_settings');
        do_settings_sections('lookway_booking_settings_page');
        submit_button();
        ?>
    </form>
</div>