<?php get_header(); ?>

<div class="wrapper archive_agent">

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
                <div class="description"><?php the_excerpt(); ?></div>

                <a href="<?php the_permalink(); ?>">Open This Agent</a>
            </article>

    <?php }
        // pagination
        posts_nav_link();
    } else {
        echo '<p>' . esc_html__('No Agents', 'lookway-booking') . '</p>';
    }
    ?>



</div>

<?php get_footer(); ?>