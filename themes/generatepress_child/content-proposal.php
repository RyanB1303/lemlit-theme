<!DOCTYPE html>
<?php

/**
 * The template for displaying single posts.
 *
 * @package GeneratePress
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php generate_do_microdata('article'); ?>>
    <?php do_action('back_button'); ?>
    <div class="inside-article">
        <?php
        /**
         * generate_before_content hook.
         *
         * @since 0.1
         *
         * @hooked generate_featured_page_header_inside_single - 10
         */
        do_action('generate_before_content');

        if (generate_show_entry_header()) :
        ?>
            <header class="entry-header">
                <?php
                /**
                 * generate_before_entry_title hook.
                 *
                 * @since 0.1
                 */
                do_action('generate_before_entry_title');

                if (generate_show_title()) {
                    $params = generate_get_the_title_parameters();

                    the_title($params['before'], $params['after']); // title post
                }

                /**
                 * generate_after_entry_title hook.
                 *
                 * @since 0.1
                 *
                 * @hooked generate_post_meta - 10
                 */
                do_action('generate_after_entry_title');
                echo "oleh <b>" . get_the_author() . "</b>";
                ?>
            </header>
        <?php
        endif;

        /**
         * generate_after_entry_header hook.
         *
         * @since 0.1
         *
         * @hooked generate_post_image - 10
         */
        do_action('generate_after_entry_header');
        $itemprop = '';

        if ('microdata' === generate_get_schema_type()) {
            $itemprop = ' itemprop="text"';
        }
        global $post;
        $proposal = get_post($post->ID);
        ?>

        <div class="entry-content" <?php echo $itemprop; ?>>
            <table class="form-table" role="presentation">
                <tr>
                    <th><label for="judul_proposal">Judul Proposal</label></th>
                    <td><?php echo esc_html($proposal->post_title) ?></td>

                </tr>
                <tr>
                    <th><label for="kategori_proposal">Kategori Proposal</label></th>
                    <td><?php echo esc_html(get_post_meta($post->ID, 'proposal_kategori', true)) ?></td>
                </tr>
                <tr>
                    <th><label for="nama_ketua">Ketua Penelitian</label></th>
                    <td><?php echo esc_html(get_post_meta($post->ID, 'proposal_ketua', true)) ?></td>
                </tr>
                <tr>
                    <th><label for="prodi_ketua">Prodi</label></th>
                    <td><?php echo esc_html(get_post_meta($post->ID, 'proposal_prodi', true)) ?></td>
                </tr>
                <tr>
                    <th><label for="dana_proposal">Dana Proposal</label></th>
                    <td><?php echo esc_html(get_post_meta($post->ID, 'proposal_dana', true)) ?></td>
                </tr>
                <tr>
                    <th><label for="data_dukung_proposal">Data Dukung SK Rektor</label></th>
                    <td><?php echo esc_html(get_post_meta($post->ID, 'proposal_data_dukung', true)) ?></td>
                </tr>
            </table>
        </div>

        <?php
        /**
         * generate_after_entry_content hook.
         *
         * @since 0.1
         *
         * @hooked generate_footer_meta - 10
         */
        do_action('generate_after_entry_content');

        /**
         * generate_after_content hook.
         *
         * @since 0.1
         */
        do_action('generate_after_content');
        ?>
    </div>
</article>