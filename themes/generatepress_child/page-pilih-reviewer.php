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
        <?php
        if (isset($_POST['submit_reviewer'])) {
          $reviewer_selected  = isset($_POST['reviewer']) ? wp_unslash($_POST['reviewer']) : '';
          $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
          // langsun aja ga usah checking
          if (!empty($proposal_seleccted)) {
            $update_status = wp_update_post(array(
              'ID'          => $proposal_seleccted,
              'post_status' => 'reviewing',
            ));
            if ($update_status != 0) {
              add_post_meta($proposal_seleccted, 'reviewer', $reviewer_selected, true);
              update_post_meta($proposal_seleccted, 'proposal_status', 'reviewing');
            }
          }
        }
        ?>
        <table class="table table-bordered pilih-reviewer">
          <thead>
            <tr>
              <th>#</th>
              <th>NIP</th>
              <th>Nama Ketua</th>
              <th>Prodi</th>
              <th>Kategori</th>
              <th>Judul</th>
              <th>Nama Reviewer</th>
              <th>Status Reviewer</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $args = array(
              'post_type'   => 'proposal',
              'post_status' => ['pending', 'reviewing'],
            );
            $wp_query = new WP_Query($args);
            $i = 1;
            if (have_posts()) : while (have_posts()) : the_post(); ?>
                <form name="submit_reviewer" method="post">
                  <tr id="proposal- <?php the_ID(); ?>" <?php post_class() ?>>
                    <td><?php esc_html_e($i) ?></td>
                    <td><?php esc_html_e(the_author()) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_ketua', true)) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_prodi', true)) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_kategori', true)) ?></td>
                    <td><?php esc_html_e(get_post_meta(get_the_ID(), 'proposal_judul', true)) ?></td>
                    <td>
                      <select name="reviewer" id="rvw">
                        <option value="" disabled selected>Pilih Reviewer</option>
                        <?php
                        $args1 = array(
                          'role'    => 'reviewer',
                          'orderby' => 'user_nicename',
                          'order'   => 'ASC',
                        );
                        $reviewers = get_users($args1);
                        foreach ($reviewers as $reviewer) {
                        ?>
                          <option value="<?php esc_html_e($reviewer->ID) ?>" <?php if ($reviewer->ID == get_post_meta(get_the_ID(), 'reviewer', true)) echo 'selected' ?>><?php esc_html_e($reviewer->display_name) ?></option>;
                        <?php
                        }
                        ?>
                        ?>
                      </select>
                    </td>
                    <td><?php echo esc_html_e(get_post_status()); ?></td>
                    <td>
                      <div class="flex">
                        <input type="hidden" name="proposal_id" value="<?php the_ID(); ?>">
                        <?php if (get_post_meta(get_the_ID(), 'reviewer', true) == '') {
                        ?>
                          <input type="submit" name="submit_reviewer" value="Setujui" />
                        <?php
                        }
                        $i++;
                        ?>
                    </td>
      </div>
      </tr>
      </form>
  <?php endwhile;
            endif;
  ?>
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

get_footer();
