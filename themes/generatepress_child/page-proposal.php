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
              <th width="20%">Action</th>
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
                'post_status' => array('reviewed', 'pending'),
              );
            }
            if (current_user_can('jurusan')) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => 'pengajuan_dana',
              );
            }

            // $content_directory = $wp_filesystem->wp_content_dir() . 'uploads/';
            // $wp_filesystem->mkdir($content_directory . 'LPJ_I');
            // $target_dir_location = $content_directory . 'LPJ_I/';
            if (isset($_POST['upload_lpj']) && isset($_FILES['file_lpj'])) {
              if (!function_exists('wp_handle_upload')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
              }
              $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
              if (!empty($proposal_seleccted) && !empty($file_lpj)) {
                $file_lpj = $_FILES['file_lpj'];
                $file_attr = wp_handle_upload($file_lpj);

                $attachment = array(
                  'post_mime_type' => $wp

                );

                $update_status = wp_update_post(array(
                  'ID'          => $proposal_seleccted,
                  'post_status' => 'laporan_lpj_i',
                ));
                if ($update_status != 0) {
                  update_post_meta($proposal_seleccted, 'proposal_status', 'laporan_lpj_i');
                }
              }
            }
            if (isset($_POST['ajukan_dana'])) {
              $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
              if (!empty($proposal_seleccted)) {
                $update_status = wp_update_post(array(
                  'ID'          => $proposal_seleccted,
                  'post_status' => 'pengajuan_dana',
                ));
                if ($update_status != 0) {
                  update_post_meta($proposal_seleccted, 'proposal_status', 'pengajuan dana');
                }
              }
            }
            if (isset($_POST['setujui_dana'])) {
              $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
              if (!empty($proposal_seleccted)) {
                $update_status = wp_update_post(array(
                  'ID'          => $proposal_seleccted,
                  'post_status' => 'dana_I_disetujui',
                ));
                if ($update_status != 0) {
                  update_post_meta($proposal_seleccted, 'proposal_status', 'dana_I_disetujui');
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
                  <?php if (current_user_can('peneliti')) { ?>
                    <td>
                      <div class="flex">
                        <form name="upload_lpj" method="post" enctype="multipart/form-data">
                          <input type="file" id="file_lpj" name="file_lpj" hidden />
                          <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                          <?php
                          if ('monev_i' == get_post_status(get_the_ID())) {
                          ?>
                            <button class="btn btn-primary" id="upload-lpj-i"><strong>Upload Laporan LPJ I</strong></button>
                            <input type="submit" name="upload_lpj" value="Submit LPJ I" class="mt-3" />
                            <script>
                              document.getElementById('upload-lpj-i').addEventListener('click', openDialog);

                              function openDialog() {
                                document.getElementById('file_lpj').click();
                              }
                            </script>
                          <?php
                          }
                          if ('monev_ii' == get_post_status(get_the_ID())) {
                          ?>
                            <button class="btn btn-primary" id="upload-lpj-ii"><strong>Upload Laporan LPJ II</strong></button>
                            <script>
                              document.getElementById('upload-lpj-ii').addEventListener('click', openDialog);

                              function openDialog() {
                                document.getElementById('file_lpj').click();
                              }
                            </script>
                          <?php
                          }
                          ?>
                        </form>
                      </div>
                    </td>
                  <?php
                  }
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
                  }

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
