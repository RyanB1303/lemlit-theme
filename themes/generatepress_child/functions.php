<?php

/**
 * GeneratePress child theme functions and definitions.
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 *
 * @package GeneratePress
 */

/**
 * Menambahkan bootstrap
 */
function get_bootstrap_css_js()
{
  wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), 1);
  wp_enqueue_style('font-awesome-css', get_template_directory_uri() . '/font-awesome-4.7.0/css/font-awesome.min.css', array(), 1);
  wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array('jquery'), 1, true);
}
/**
 * Function wpse221640_back_buttonn
 * Membuat back button.
 */
function wpse221640_back_button()
{

  if (wp_get_referer()) {
    $back_text = __('&laquo; Back');
    $button    = "\n<button id='back-button' class='btn btn-secondary back-button mb-3' onclick='javascript:history.back()'>$back_text</button>";
    echo $button;
  }
}

/**
 * Mengembalikan user ke halaman login setelah register
 * Dapat juga dipasang dimana pun.
 */
function redirect_to_login()
{
  echo '<meta http-equiv="refresh" content="1;url=' . esc_url(site_url('/login')) . '">';
}

/**
 * Mencari ROle user berdasarkan user yang login / current user
 */
function get_user_role()
{
  global $current_user;

  $user_roles = $current_user->roles;
  $user_role  = array_shift($user_roles);

  return $user_role;
}
add_filter('wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2);
/**
 * Function wti_loginout_menu_link
 *
 * @param items $items items.
 * @param args  $args arguments.
 * @return $items
 */
function wti_loginout_menu_link($items, $args)
{
  if ('primary' === $args->theme_location) {
    if (is_user_logged_in()) {
      global $current_user;
      if (in_array('administrator', $current_user->roles, true)) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/create-role') . ' ">' . __('Create Role') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
      }
      if (in_array('peneliti', $current_user->roles, true) || $current_user->has_cap('Peneliti') || $current_user->has_cap('peneliti')) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/penelitian-terakhir') . ' ">' . __('Penelitian Terakhir') . '</a></li>';
      }
      if (in_array('drf', $current_user->roles, true) || $current_user->has_cap('DRF') || $current_user->has_cap('drf')) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/pilih-reviewer') . ' ">' . __('Pilih Reviewer') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/nilai-drf') . ' ">' . __('Nilai DRF') . '</a></li>';
      }
      if (in_array('reviewer', $current_user->roles, true) || $current_user->has_cap('Reviewer') || $current_user->has_cap('reviewer')) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/nilai-reviewer') . ' ">' . __('Nilai Reviewer') . '</a></li>';
      }
      if (in_array('dekan', $current_user->roles, true) || $current_user->has_cap('Dekan') || $current_user->has_cap('dekan')) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/report') . ' ">' . __('Report') . '</a></li>';
      }
      if (in_array('lemlit', $current_user->roles, true) || $current_user->has_cap('Lemlit') || $current_user->has_cap('lemlit')) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/report') . ' ">' . __('Report') . '</a></li>';
      }
      if (in_array('jurusan', $current_user->roles, true) || $current_user->has_cap('Jurusan') || $current_user->has_cap('jurusan')) {
        $items .= '<li ><a href="' . site_url('/profile') . ' ">' . __('Profile') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/report') . ' ">' . __('Report') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Pencairan Proposal') . '</a></li>'; // add archive page later.
      }
      $items .= '<li class="ml-5">' . get_user_role() . '</li>';
      // $items .= '<li class="right">' . $current_user->user_nicename . '</li>';
      $items .= '<li class="right"><a href="' . wp_logout_url('/login') . '">' . __('Log Out') . '</a></li>';
    } else {
      $items .= '<li class="right"><a href="' . site_url('/login') . '">' . __('Log In') . '</a></li>';
    }
  }
  return $items;
}

/**
 * Function create_lemlit_role
 * create role for initial setup.
 */

/**
 * Membuat page page yang digunakan di Lemlit Proposal
 */
function create_lemlit_page()
{
  $page_login = array(
    'post_title'     => 'Login',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_register = array(
    'post_title'     => 'Register',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_create_role = array(
    'post_title'     => 'Create Role',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_profile = array(
    'post_title'     => 'Profile',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_report = array(
    'post_title'     => 'Report',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_nilai_drf = array(
    'post_title'     => 'Nilai DRF',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_pilih_reviewer = array(
    'post_title'     => 'Pilih Reviewer',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_penelitian_terakhir = array(
    'post_title'     => 'Penelitian Terakhir',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_nilai_reviewer = array(
    'post_title'     => 'Nilai Reviewer',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );
  $page_proposal = array(
    'post_title'     => 'Proposal',
    'post_type'      => 'page',
    'post_status' => 'publish',
  );

  wp_insert_post($page_login, FALSE);
  wp_insert_post($page_register, FALSE);
  wp_insert_post($page_create_role, FALSE);
  wp_insert_post($page_profile, FALSE);
  wp_insert_post($page_report, FALSE);
  wp_insert_post($page_nilai_drf, FALSE);
  wp_insert_post($page_pilih_reviewer, FALSE);
  wp_insert_post($page_penelitian_terakhir, FALSE);
  wp_insert_post($page_nilai_reviewer, FALSE);
  wp_insert_post($page_proposal, FALSE);
}

function create_lemlit_menu()
{
  $menuname = 'lemlit-menu';
  $bpmenulocation = 'primary';
  // Does the menu exist already?
  $menu_exists = wp_get_nav_menu_object($menuname);

  // If it doesn't exist, let's create it.
  if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menuname);
    // Grab the theme locations and assign our newly-created menu
    // to the BuddyPress menu location.
    if (!has_nav_menu($bpmenulocation)) {
      $locations = get_theme_mod('nav_menu_locations');
      $locations[$bpmenulocation] = $menu_id;
      set_theme_mod('nav_menu_locations', $locations);
    }
  }
}

function set_login_as_homepage()
{
  $my_page = get_page_by_title('Login');
  update_option('page_on_front', $my_page->ID);
  update_option('show_on_front', 'page');
}

add_action('back_button', 'wpse221640_back_button');
add_action('wp_enqueue_scripts', 'get_bootstrap_css_js');

// add_action('init', 'create_lemlit_role');
add_action('after_switch_theme', 'create_lemlit_page', 5);
add_action('after_switch_theme', 'create_lemlit_menu');
add_action('after_switch_theme', 'set_login_as_homepage', 30);

// proposal post handler

/**
 * Upload LPJ
 * Peneliti
 */
if (isset($_POST['upload_lpj']) && isset($_FILES['file_lpj'])) {

  if (!function_exists('wp_handle_upload')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
  }

  $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
  $file = $_FILES['file_lpj'];
  $upload_overrides = array('test_form' => false);
  $file_attr = wp_handle_upload($file, $upload_overrides);

  if ($file_attr) {
    $filename = $file['name'];
    $filetype = wp_check_filetype(basename($filename), null);
    $wp_upload_dir = wp_upload_dir();

    $attachment = array(
      'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
      'post_mime_type' => $filetype['type'],
      'post_title' => preg_replace('/\\.[^.]+$/', '', basename($filename)),
      'post_content' => '',
      'post_status' => 'inherit'
    );
    $insert_attach = wp_insert_attachment($attachment, $filename, $proposal_seleccted);

    if (0 != $insert_attach) {
      if ('monev_I' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
        $update_status = wp_update_post(array(
          'ID'          => $proposal_seleccted,
          'post_status' => 'laporan_lpj_i',
        ));
        add_post_meta($proposal_seleccted, 'lpj_i_file_id', $insert_attach, true);
        update_post_meta($proposal_seleccted, 'proposal_status', 'laporan_lpj_i');
      }
      if ('monev_II' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
        $update_status = wp_update_post(array(
          'ID'          => $proposal_seleccted,
          'post_status' => 'laporan_lpj_ii',
        ));
        add_post_meta($proposal_seleccted, 'lpj_ii_file_id', $insert_attach, true);
        update_post_meta($proposal_seleccted, 'proposal_status', 'laporan_lpj_ii');
      }
    }
  }
}

/**
 * Ajukan Dana
 * Pengajuan Dana
 */
if (isset($_POST['ajukan_dana'])) {
  $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
  if (!empty($proposal_seleccted)) {

    if ('reviewed' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'pengajuan_dana',
      ));
      if ($update_status != 0) {
        update_post_meta($proposal_seleccted, 'proposal_status', 'pengajuan_dana');
      }
    }

    if ('laporan_lpj_i' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'pengajuan_dana_ii',
      ));
      if ($update_status != 0) {
        update_post_meta($proposal_seleccted, 'proposal_status', 'pengajuan_dana_ii');
      }
    }
    if ('laporan_lpj_ii' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'publish',
      ));
      if ($update_status != 0) {
        update_post_meta($proposal_seleccted, 'proposal_status', 'laporan_selesai');
      }
    }
  }
}

/**
 * Setujui Dana
 */
if (isset($_POST['setujui_dana'])) {
  $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
  if (!empty($proposal_seleccted)) {

    if ('pengajuan_dana' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'dana_i_disetujui',
      ));
      if ($update_status != 0) {
        update_post_meta($proposal_seleccted, 'proposal_status', 'dana_i_disetujui');
      }
    }

    if ('pengajuan_dana_ii' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'dana_ii_disetujui',
      ));
      if ($update_status != 0) {
        update_post_meta($proposal_seleccted, 'proposal_status', 'dana_ii_disetujui');
      }
    }
  }
}


// page report post handler
if (isset($_POST['cairkan_dana'])) {
  $proposal_seleccted = isset($_POST['proposal_id']) ? wp_unslash($_POST['proposal_id']) : '';
  if (!empty($proposal_seleccted)) {
    if ('dana_i_disetujui' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'monev_I',
      ));
      if ($update_status != 0) {
        add_post_meta($proposal_seleccted, 'proposal_dana_I_tanggal', current_time('d-m-Y'), true);
        update_post_meta($proposal_seleccted, 'proposal_status', 'monev_I');
        update_post_meta($proposal_seleccted, 'proposal_pencairan_dana', 'monev_I_dicairkan');
      }
    }

    if ('dana_ii_disetujui' == get_post_meta($proposal_seleccted, 'proposal_status', true)) {
      $update_status = wp_update_post(array(
        'ID'          => $proposal_seleccted,
        'post_status' => 'monev_II',
      ));
      if ($update_status != 0) {
        add_post_meta($proposal_seleccted, 'proposal_dana_II_tanggal', current_time('d-m-Y'), true);
        update_post_meta($proposal_seleccted, 'proposal_status', 'monev_II');
        update_post_meta($proposal_seleccted, 'proposal_pencairan_dana', 'monev_II_dicairkan');
      }
    }
    // if ('monev_I' == get_post_status($proposal_seleccted)) {
    //   $update_status = wp_update_post(array(
    //     'ID'          => $proposal_seleccted,
    //     'post_status' => 'monev_II',
    //   ));
    //   if ($update_status != 0) {
    //     add_post_meta($proposal_seleccted, 'proposal_dana_II_tanggal', current_time('d-m-Y'), true);
    //     update_post_meta($proposal_seleccted, 'proposal_status', 'monev_II');
    //     update_post_meta($proposal_seleccted, 'proposal_pencairan_dana', 'monev_II_dicairkan');
    //   }
    // }

  }
}
