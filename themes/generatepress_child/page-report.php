<!DOCTYPE html>
<?php

/**
 * The template for report page.
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
      <div class="entry-content table-responsive">
        <h4>Proposal Yang Telah Disetujui Oleh Jurusan</h4>
        <table class="table table-bordered report">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Ketua</th>
              <th>Prodi</th>
              <th>Kategori</th>
              <th>Judul</th>
              <th>Dana Diajukan</th>
              <th>Tanggal Pencairan Dana Tahap I</th>
              <th>Tanggal Pencairan Dana Tahap II</th>
              <th width="20%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            global $current_user, $wp_query;
            $allowed_roles = array('administrator', 'jurusan', 'dekan', 'lemlit');
            if (array_intersect($allowed_roles, $current_user->roles)) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => ['dana_i_disetujui', 'dana_ii_disetujui', 'monev_i', 'monev_ii', 'publish']
              );
            }

            $i = 1;
            $wp_query = new WP_Query($args);
            if (have_posts()) : while (have_posts()) : the_post();  ?>
                <tr id="proposal- <?php the_ID(); ?>" <?php post_class() ?>>
                  <td><?php esc_html_e($i) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_ketua', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_prodi', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_kategori', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_judul', true)) ?></td>
                  <td>Rp. <?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_dana', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_dana_I_tanggal', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_dana_II_tanggal', true)) ?></td>
                  <td>
                    <div class="flex">
                      <form name="cairkan_dana" method="post">
                        <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                        <?php if ('dana_i_disetujui' == get_post_status(get_the_ID())) {
                        ?>
                          <input type="submit" name="cairkan_dana" value="Cairkan Dana I" />
                        <?php
                        }
                        if ('monev_i' == get_post_status(get_the_ID())) {
                        ?>
                          <div class="alert alert-success"><strong>Monev I</strong></div>
                        <?php
                        }
                        if ('monev_ii' == get_post_status(get_the_ID())) {
                        ?>
                          <div class="alert alert-success"><strong>Monev II</strong></div>
                        <?php
                        }
                        if ('dana_ii_disetujui' == get_post_status(get_the_ID())) {
                        ?>
                          <input type="submit" name="cairkan_dana" value="Cairkan Dana II" />
                        <?php
                        }
                        ?>
                        <?php
                        if ('publish' == get_post_status(get_the_ID())) {
                        ?>
                          <div class="alert alert-secondary"><strong>Selesai</strong></div>
                        <?php
                        }
                        ?>
                      </form>
                    </div>
                  </td>
                </tr>
            <?php
                $i++;
              endwhile;
            endif;
            ?>
          </tbody>
        </table>
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



get_footer();
