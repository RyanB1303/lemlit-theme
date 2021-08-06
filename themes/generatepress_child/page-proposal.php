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
      <?php if (current_user_can('jurusan')) {
        echo '<br> <br> <h3 style="color:blue;">Pencairan Dana</h3>';
      } ?>
      <div class="entry-content table-responsive">
        <table class="table table-bordered table-fixed proposal">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Ketua</th>
              <th>Prodi</th>
              <th>Kategori</th>
              <th>Judul</th>
              <?php current_user_can('jurusan') ? print('<th>Dana Diajukan</th>') : '' ?>
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
                'post_status' => array('reviewed', 'laporan_lpj_i', 'laporan_lpj_ii'),
              );
            }
            if (current_user_can('jurusan')) {
              $args = array(
                'post_type' => 'proposal',
                'post_status' => array('pengajuan_dana', 'pengajuan_dana_ii'),
              );
            }
            $i = 1;
            $wp_query = new WP_Query($args);

            if (have_posts()) : while (have_posts()) : the_post(); ?>
                <tr id="proposal- <?php the_ID(); ?>" <?php post_class() ?>>
                  <td><?php esc_html_e($i) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_ketua', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_prodi', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_kategori', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_judul', true)) ?></td>
                  <?php current_user_can('jurusan') ? print('<td>' . esc_html(get_post_meta(get_the_ID(), 'proposal_dana', true)) . '</td>') : '' ?>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_pencairan_dana', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_status', true)) ?></td>
                  <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_data_dukung', true)) ?></td>
                  <?php
                  if (current_user_can('peneliti')) { ?>
                    <?php
                    if ('laporan_lpj_i' == get_post_meta(get_the_ID(), 'proposal_status', true)) { ?>
                      <td>
                        <div class="alert alert-secondary"><strong>LPJ I Di upload</strong></div>
                      </td>
                    <?php }
                    if ('laporan_lpj_ii' == get_post_meta(get_the_ID(), 'proposal_status', true)) { ?>
                      <td>
                        <div class="alert alert-secondary"><strong>LPJ II Di upload</strong></div>
                      </td>
                    <?php } else { ?>
                      <td>
                        <div class="flex">
                          <form name="upload_lpj" method="post" enctype="multipart/form-data">
                            <input type="file" name="file_lpj" hidden />
                            <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                            <?php
                            if ('monev_i' == get_post_status(get_the_ID())) {
                            ?>
                              <!-- <button class="btn btn-primary" id="upload-lpj-i"><strong>Upload Laporan LPJ I</strong></button> -->
                              <input type="file" name="file_lpj" />
                              <input type="submit" name="upload_lpj" value="Submit LPJ I" class="mt-3" />
                            <?php
                            }
                            if ('monev_ii' == get_post_status(get_the_ID())) {
                            ?>
                              <input type="file" name="file_lpj" />
                              <input type="submit" name="upload_lpj" value="Submit LPJ II" class="mt-3" />
                            <?php
                            }
                            ?>
                          </form>
                        </div>
                      </td>
                    <?php
                    }
                  }
                  if (current_user_can('lemlit')) {
                    $attachment_id = get_post_meta(get_the_ID(), 'lpj_i_file_id', true);
                    ?>
                    <td>
                      <div class="flex">
                        <form name="ajukan_dana" method="post">
                          <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                          <?php if ('reviewed' == get_post_status(get_the_ID())) { ?>
                            <input type="submit" name="ajukan_dana" value="Ajukan Dana I" />
                          <?php } ?>
                          <?php
                          if ('laporan_lpj_i' == get_post_status(get_the_ID())) {
                          ?>
                            <input type="submit" name="ajukan_dana" value="Ajukan Dana II" />
                            <div><?php if (!empty(wp_get_attachment_url($attachment_id))) print('<a href="' . (wp_get_attachment_url($attachment_id)) . '" target="_blank" download>File LPJ I</a>');
                                  else print('Belum Ada FIle LPJ') ?></div>
                          <?php } ?>
                          <?php
                          if ('laporan_lpj_ii' == get_post_status(get_the_ID())) {
                            $attachment_id = get_post_meta(get_the_ID(), 'lpj_ii_file_id', true);
                          ?>
                            <input type="submit" name="ajukan_dana" value="Laporan Selesai" />
                            <div><?php if (!empty(wp_get_attachment_url($attachment_id))) print('<a href="' . (wp_get_attachment_url($attachment_id)) . '" target="_blank" download>File LPJ II</a>');
                                  else print('Belum Ada FIle LPJ II') ?></div>
                          <?php } ?>
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
                          <?php
                          if ('pengajuan_dana' == get_post_status(get_the_ID())) {
                          ?>
                            <input type="submit" name="setujui_dana" value="Setujui Dana" />
                          <?php } ?>
                          <?php
                          if ('pengajuan_dana_ii' == get_post_status(get_the_ID())) {
                          ?>
                            <input type="submit" name="setujui_dana" value="Setujui Dana II" />
                          <?php } ?>
                        </form>
                      </div>
                    </td>
                  <?php
                  }
                  if (current_user_can('drf')) {
                  ?>
                    <td>
                    </td>
                  <?php
                  }
                  if (current_user_can('reviewer')) {
                  ?>
                    <td>
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
