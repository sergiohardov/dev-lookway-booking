<?php

/**
 * Template Name: Add Property
 */

get_header(); ?>

<div class="wrapper">

    <?php
    if (have_posts()) {

        // Load posts loop.
        while (have_posts()) {
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2><?php the_title(); ?></h2>
                <div class="description"><?php the_content(); ?></div>
            </article>

            <?php if (is_user_logged_in()) { ?>

                <div class="add_form">
                    <form method="post" id="add_property" enctype="multipart/form-data">
                        <p>
                            <label for="property_title">Title</label>
                            <input type="text" name="property_title" id="property_title" placeholder="Add the Title" value="" required tabindex="1">
                        </p>
                        <p>
                            <label for="property_desc">Description</label>
                            <textarea name="property_desc" id="property_desc" placeholder="Add the Description" required tabindex="2"></textarea>
                        </p>
                        <p>
                            <label for="property_image">Image</label>
                            <input type="file" name="property_image" id="property_image" required tabindex="3">
                        </p>
                        <p>
                            <label for="property_location">Location</label>
                            <select name="property_location" id="property_location" tabindex="4">
                                <?php
                                $locations = get_terms(['location'], ['hide_empty' => false]);
                                if (!empty($locations)) {
                                    foreach ($locations as $location) {
                                        echo '<option value="' . $location->term_id . '">' . $location->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </p>
                        <p>
                            <label for="property_type">Type</label>
                            <select name="property_type" id="property_type" tabindex="5">
                                <?php
                                $types = get_terms(['property-type'], ['hide_empty' => false]);
                                if (!empty($types)) {
                                    foreach ($types as $type) {
                                        echo '<option value="' . $type->term_id . '">' . $type->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </p>
                        <p>
                            <label for="property_offer">Offer</label>
                            <select name="property_offer" id="property_offer" tabindex="6">
                                <option value="" selected>Select Offer</option>
                                <option value="rent">For Rent</option>
                                <option value="sale">For Sale</option>
                                <option value="sold">Sold</option>
                            </select>
                        </p>
                        <p>
                            <label for="property_price">Price</label>
                            <input type="text" name="property_price" id="property_price" placeholder="Change Price" value="" tabindex="7">
                        </p>
                        <p>
                            <label for="property_period">Period</label>
                            <input type="text" name="property_period" id="property_period" placeholder="Change Period" value="" tabindex="8">
                        </p>
                        <p>
                            <?php
                            global $current_user;
                            wp_get_current_user();
                            ?>
                            <label for="property_agent">Agent</label>
                            <select name="property_agent" id="property_agent" tabindex="9">
                                <option value="<?php echo $current_user->ID; ?>" selected>Use Me</option>
                                <?php
                                $agents = get_posts(['post_type' => 'agent', 'post_per_page' => -1]);
                                if (!empty($agents)) {
                                    foreach ($agents as $agent) {
                                        echo '<option value="' . $agent->ID . '">' . $agent->post_title . '</option>';
                                    }
                                }

                                ?>
                            </select>
                        </p>
                        <p>
                            <?php wp_nonce_field('submit_property', 'property_nonce'); ?>
                            <input type="submit" name="submit" tabindex="10" value="Add New Property">
                            <input type="hidden" name="action" value="lookway_booking_add_property">
                        </p>
                    </form>
                </div>

            <?php } ?>


    <?php }
    }
    ?>

</div>

<?php get_footer();
