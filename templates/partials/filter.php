<?php $lookwayBooking = new LookwayBooking(); ?>

<div class="wrapper filter_form">
    <form method="post" action="<?php get_post_type_archive_link('property'); ?>">

        <select name="lookway_booking_location">
            <option value="">Select Location</option>
            <?php $lookwayBooking->get_terms_hierarchical('location', $_POST['lookway_booking_location']); ?>
        </select>

        <select name="lookway_booking_property-type">
            <option value="">Select Offer</option>
            <?php $lookwayBooking->get_terms_hierarchical('property-type', $_POST['lookway_booking_property-type']); ?>
        </select>

        <input type="text" placeholder="Max Price" name="lookway_booking_price" value="<?php if (isset($_POST['lookway_booking_price'])) {
                                                                                            echo esc_attr($_POST['lookway_booking_price']);
                                                                                        } ?>">

        <select name="lookway_booking_type">
            <option value="">Select Type</option>
            <option value="sale" <?php if (isset($_POST['lookway_booking_type']) && $_POST['lookway_booking_type'] === 'sale') {
                                        echo 'selected';
                                    } ?>>For Sale</option>
            <option value="rent" <?php if (isset($_POST['lookway_booking_type']) && $_POST['lookway_booking_type'] === 'rent') {
                                        echo 'selected';
                                    } ?>>For Rent</option>
            <option value="sold" <?php if (isset($_POST['lookway_booking_type']) && $_POST['lookway_booking_type'] === 'sold') {
                                        echo 'selected';
                                    } ?>>Sold</option>
        </select>


        <select name="lookway_booking_agent">
            <option value="">Select Agent</option>
            <?php
            $agents = get_posts([
                'post_type' => 'agent', 'numberposts' => -1
            ]);

            if (isset($_POST['lookway_booking_agent'])) {
                $agent_id = $_POST['lookway_booking_agent'];
            }

            foreach ($agents as $agent) {
                echo '<option value="' . $agent->ID . '" ' . selected($agent->ID, $agent_id, false) . '>' . $agent->post_title . '</option>';
            }
            ?>
        </select>
        <input type="submit" name="submit" value="Filter">
    </form>
</div>