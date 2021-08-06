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
        <table class="table table-bordered table-fixed proposal">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Ketua</th>
              <th>Prodi</th>
              <th>Kategori</th>
              <th>Judul</th>
              <th>Status Pencairan Dana</th>
              <th>Status Proposal</th>
              <th>Data Dukung SK Rektor</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            global $current_user, $wp_query;
            $allowed_roles = array('administrator', 'drf', 'reviewer', 'dekan');
            if (array_intersect($allowed_roles, $current_user->roles)) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => 'any'
              );
            }
            if (current_user_can('peneliti')) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => 'any',
                'author' => $current_user->ID,
              );
            }
            if (current_user_can('lemlit')) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => 'reviewed',
              );
            }
            if (current_user_can('jurusan')) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => 'pengajuan_dana',
              );
            }
            if (isset($_POST['ajukan_dana'])) {
              $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
              if (!empty($proposal_seleccted)) {
                $update_status = wp_update_post(array(
                  'ID'          => $proposal_seleccted,
                  'post_status' => 'pengajuan_dana',
                ));
                if ($update_status != 0) {
                  add_post_meta($proposal_seleccted, 'proposal_dana_I_tanggal', current_time('d-m-Y'), true);
                  update_post_meta($proposal_seleccted, 'proposal_status', 'pengajuan dana');
                }
              }
            }
            if (isset($_POST['setujui_dana'])) {
              $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
              if (!empty($proposal_seleccted)) {
                $update_status = wp_update_post(array(
                  'ID'          => $proposal_seleccted,
                  'post_status' => 'monev_I',
                ));
                if ($update_status != 0) {
                  add_post_meta($proposal_seleccted, 'proposal_monev_I', current_time('d-m-Y'), true);
                  update_post_meta($proposal_seleccted, 'proposal_status', 'monev_I');
                }
              }
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
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_pencairan_dana', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_status', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_data_dukung', true)) ?></td>
                  <?php
                  if (current_user_can('lemlit')) {
                  ?>
                    <td>
                      <div class="flex">
                        <form name="ajukan_dana" method="post">
                          <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                          <input type="submit" name="ajukan_dana" value="Ajukan Dana" />
                        </form>
                      </div>
                    </td>
                  <?php
                  }
                  if (current_user_can('jurusan')) {
                  ?>
                    <td>
                      <div class="flex">
                        <form name="setujui_dana" method="post">
                          <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                          <input type="submit" name="setujui_dana" value="Setujui Dana" />
                        </form>
                      </div>
                    </td>
                  <?php
                  } else echo '<td></td>'

                  ?>
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
