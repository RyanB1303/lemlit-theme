<!DOCTYPE html>
<?php

/**
 * The template for proposal page.
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
        <table class="table table-bordered nilai-reviewer">
          <thead>
            <tr>
              <th>#</th>
              <th>NIP</th>
              <th>Nama Ketua</th>
              <th>Prodi</th>
              <th>Kategori</th>
              <th>Judul</th>
              <th>Target Capaian</th>
              <th>Nilai Reviewer</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (isset($_POST['nilai_proposal'])) {
              $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
              // langsun aja ga usah checking
              if (!empty($proposal_seleccted)) {
                $update_status = wp_update_post(array(
                  'ID'          => $proposal_seleccted,
                  'post_status' => 'reviewed',
                ));
                if ($update_status != 0) {
                  update_post_meta($post_id, 'target_nilai_proposal', $_POST['target_cap']);
                  update_post_meta($post_id, 'nilai_reviewer_proposal', $_POST['nilai_rev']);
                  update_post_meta($proposal_seleccted, 'proposal_status', 'reviewed');
                }
              }
            }
            $args = array(
              'post_type'   => 'proposal',
              'post_status' => ['pending', 'reviewing', 'reviewed'],
            );
            $i = 1;
            $wp_query = new WP_Query($args);
            if (have_posts()) : while (have_posts()) : the_post(); ?>
                <tr id="proposal- <?php the_ID(); ?>" <?php post_class() ?>>
                  <form name="nilai_proposal" method="post">
                    <td><?php esc_html_e($i) ?></td>
                    <td><?php esc_html_e(the_author()) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_ketua', true)) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_prodi', true)) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_kategori', true)) ?></td>
                    <td><?php esc_html_e(the_title()) ?></td>
                    <?php
                    if (!empty(get_post_meta(get_the_ID(), 'target_nilai_proposal', true))) {
                    ?>
                      <td><?php esc_html_e(get_post_meta(get_the_ID(), 'target_nilai_proposal', true)); ?></td>
                    <?php } else { ?>
                      <td><input type="number" name="target_cap" min="0" max="100" value="" /></td>
                    <?php } ?>
                    <?php
                    if (!empty(get_post_meta(get_the_ID(), 'nilai_reviewer_proposal', true))) {
                    ?>
                      <td><?php esc_html_e(get_post_meta(get_the_ID(), 'nilai_reviewer_proposal', true)); ?></td>
                    <?php } else { ?>
                      <td><input type="number" name="nilai_rev" min="0" max="100" value="" /></td>
                    <?php } ?>
                    <td>
                      <div class="flex">
                        <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                        <?php if ('reviewed' == get_post_status(get_the_ID())) { ?>
                          <div class="alert alert-primary"><strong>Sudah Dinilai</strong></div>
                        <?php } else { ?>
                          <input type="submit" name="nilai_proposal" value="Submit Nilai" />
                        <?php } ?>
                      </div>
                    </td>
                </tr>
                </form>
            <?php $i++;
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
