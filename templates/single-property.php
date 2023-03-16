<?php get_header(); ?>

<div class="wrapper single_property">

    <?php
    if (have_posts()) {

        // Load posts loop.
        while (have_posts()) {
            the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if (get_the_post_thumbnail(get_the_ID(), 'full')) {
                    echo get_the_post_thumbnail(get_the_ID(), 'full');
                } ?>
                <h2><?php the_title(); ?></h2>
                <div class="description"><?php the_content(); ?></div>
                <div class="property-info">
                    <span class="location">
                        <?php
                        esc_html_e('Location:', 'lookway-booking');
                        $locations = get_the_terms(get_the_ID(), 'location');
                        foreach ($locations as $location) {
                            echo ' ' . $location->name;
                        }
                        ?>
                    </span>
                    <span class="type">
                        <?php
                        esc_html_e('Type:', 'lookway-booking');
                        $types = get_the_terms(get_the_ID(), 'property-type');
                        foreach ($types as $type) {
                            echo ' ' . $type->name;
                        }
                        ?>

                    </span>
                    <span class="price"><?php esc_html_e('Price:', 'lookway-booking');
                                        echo ' ' . get_post_meta(get_the_ID(), 'lookway_booking_price', true); ?></span>
                    <span class="offer"><?php esc_html_e('Offer:', 'lookway-booking');
                                        echo ' ' . get_post_meta(get_the_ID(), 'lookway_booking_type', true); ?> </span>
                    <span class="agent">
                        <?php
                        esc_html_e('Agent:', 'lookway-booking');
                        $agent_id = get_post_meta(get_the_ID(), 'lookway_booking_agent', true);
                        $agent = get_post($agent_id);

                        echo ' ' . esc_html($agent->post_title);
                        ?>
                    </span>

                </div>
            </article>

    <?php }
    }
    ?>



</div>

<?php get_footer(); ?>