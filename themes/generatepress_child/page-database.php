<!DOCTYPE html>
<?php

/**
 * The template for database page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<div id="primary" <?php generate_do_element_classes('content'); ?>>
    <main id="main" <?php generate_do_element_classes('main'); ?>>

        <?php
        /**
         * generate_before_main_content hook.
         *
         * @since 0.1
         */
        do_action('generate_before_main_content');

        ?>
        <div class="inside-article">

            <header class="entry-header">
                <?php
                /**
                 * generate_before_page_title hook.
                 *
                 * @since 2.4
                 */
                do_action('generate_before_page_title');

                if (generate_show_title()) {
                    $params = generate_get_the_title_parameters();

                    the_title($params['before'], $params['after']);
                }

                /**
                 * generate_after_page_title hook.
                 *
                 * @since 2.4
                 */
                do_action('generate_after_page_title');
                ?>
            </header>
            <div class="entry-content">
                <ul>
                    <?php
                    $all_proposal = new WP_Query([
                        'posts_per_page' => -1,
                        'post_type' => 'proposal'
                    ]);
                    if ($all_proposal->have_posts()) { //check apakah ada proposal yang di upload
                        while ($all_proposal->have_posts()) { // loop proposal
                            $all_proposal->the_post();
                    ?>
                            <li><?php the_title(); ?> - <?php the_author(); ?> / <?php the_meta() ?> | time : <?php the_time('F j, Y') ?></li>
                            <?php the_shortlink($text = 'Lihat Proposal') ?>
                    <?php    } //endloop
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php
        /**
         * generate_after_main_content hook.
         *
         * @since 0.1
         */
        do_action('generate_after_main_content');
        ?>
    </main>
</div>

<?php
/**
 * generate_after_primary_content_area hook.
 *
 * @since 2.0
 */
do_action('generate_after_primary_content_area');

generate_construct_sidebars();

get_footer();
