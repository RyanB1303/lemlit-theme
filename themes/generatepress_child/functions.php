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
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('Proposal') . '</a></li>';
        $items .= '<li ><a href="' . site_url('/proposal') . ' ">' . __('View Proposal') . '</a></li>'; // add archive page later.
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
function create_lemlit_role()
{
  add_role('peneliti', 'Peneliti');
  add_role('drf', 'DRF');
  add_role('reviewer', 'Reviewer');
  add_role('dekan', 'Dekan');
  add_role('jurusan', 'Jurusan');
  add_role('lemlit', 'Lemlit');

  $peneliti = get_role('peneliti');
  $peneliti->add_cap('read ');
  $peneliti->add_cap('delete_posts ');
  $peneliti->add_cap('edit_posts');
  $peneliti->add_cap('edit_publish_posts ');
  $peneliti->add_cap('upload_files');

  $reviewer = get_role('reviewer');
  $drf      = get_role('drf');
  $dekan    = get_role('dekan');
  $drf->add_cap('read');
  $reviewer->add_cap('read');
  $dekan->add_cap('read');
}

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

add_action('init', 'create_lemlit_role');
add_action('after_switch_theme', 'create_lemlit_page', 5);
add_action('after_switch_theme', 'create_lemlit_menu');
add_action('after_switch_theme', 'set_login_as_homepage', 30);
