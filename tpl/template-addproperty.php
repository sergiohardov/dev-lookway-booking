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

            <div class="add_form">
                <form method="post" id="add_property" enctype="multipart/form-data">
                    <p>
                        <input type="text" name="property_title" id="property_title" placeholder="Add the Title" value="" required tabindex="1">
                    </p>
                    <p>
                        <textarea name="property_desc" id="property_desc" placeholder="Add the Description" required tabindex="2"></textarea>
                    </p>
                    <p>
                        <input type="file" name="property_image" id="property_image" required tabindex="3">
                    </p>
                    <p>
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

                </form>
            </div>

    <?php }
    }
    ?>

</div>

<?php get_footer();
