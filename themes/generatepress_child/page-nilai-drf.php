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
        <table class="table table-bordered nilai-laporan-akhir">
          <thead>
            <tr>
              <th>#</th>
              <th>NIP</th>
              <th>Nama Ketua</th>
              <th>Prodi</th>
              <th>Kategori</th>
              <th>Judul</th>
              <th>Target Capaian</th>
              <th>Nilai DRF</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>0123456789</td>
              <td>Pri Agung Rakhmanto</td>
              <td>S1 - SI</td>
              <td>Kategori IV</td>
              <td>Effect of Sand Grain Size on Spontaneous Imbibiton of Surfactant Solution</td>
              <td>80%</td>
              <td>85%</td>
              <td>
                <div class="flex">
                  <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
                  <a href="#"><i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
              </td>
            </tr>
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

generate_construct_sidebars();

get_footer();
