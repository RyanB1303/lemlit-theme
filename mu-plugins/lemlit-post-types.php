<?php

/**
 * Plugin untuk membuat post proposal.
 *
 * @package GeneratePress
 */
class proposal_post_editor
{
  public function __construct()
  {
    add_action('init', [$this, 'proposal_post_types']);
    add_action('init', [$this, 'proposal_post_status'], 20);
    add_action('add_meta_boxes', [$this, 'proposal_meta_box']);
    add_action('save_post', [$this, 'save_proposal']);
    add_action('admin_init', [$this, 'proposal_admin_menu'], 999);
    add_action('admin_bar_menu', [$this, 'proposal_admin_bar'], 999);
    // add_filter('pre_get_posts', [$this, 'posts_for_current_author']);
    add_filter('enter_title_here', [$this, 'my_title_place_holder'], 20, 2);
    add_filter('manage_proposal_posts_columns', [$this, 'custom_table_header_proposal']);
    add_action('manage_proposal_posts_custom_column', [$this, 'custom_columns_proposal'], 10, 2);
    add_filter('wp_insert_post_data', [$this, 'change_proposal_status'], 30);
  }

  public function proposal_admin_menu()
  {
    if (current_user_can('delete_private_proposals')) {

      remove_menu_page('edit.php');                   //Posts
      remove_menu_page('upload.php');                 //Media
      remove_menu_page('edit.php?post_type=page');    //Pages
      remove_menu_page('edit-comments.php');          //Comments
      remove_menu_page('tools.php');          //Comments
      remove_menu_page('index.php');          //Comments
    }
  }

  public function proposal_admin_bar($wp_admin_bar)
  {
    if (!current_user_can('delete_plugins')) {
      $wp_admin_bar->remove_menu('new-post');
      $wp_admin_bar->remove_menu('new-page');
      $wp_admin_bar->remove_menu('comments');
    }
  }

  public function posts_for_current_author($query)
  {
    global $pagenow;

    if ('edit.php' != $pagenow || !$query->is_admin)
      return $query;

    if (current_user_can('edit_posts') && !current_user_can('delete_plugins')) {
      $query->set('author', get_current_user_id());
    }
    return $query;
  }

  public function proposal_post_types()
  {
    $supports = array(
      'title',
      // 'author'
    );
    $labels   = array(
      'name'          => 'Proposal',
      'add_new_item'  => 'Buat Proposal Baru',
      'edit_item'     => 'Edit Proposal',
      'all_items'     => 'Semua Proposal',
      'add_new'       => 'Buat Proposal',
      'singular_name' => 'Proposal',
      'archives'      => 'Arsip Proposal',
    );

    $args_proposal = array(
      'supports'           => $supports,
      'has_archive'        => true,
      'public'             => true,
      'rewrite'            => array('slug' => 'proposals'),
      'publicly_queryable' => true,
      'capability_type'    => 'post',
      'show_ui'            => true,
      'show_in_menu'       => true,
      'show_in_admin_bar'  => true,
      'labels'             => $labels,
      'menu_icon'          => 'dashicons-pdf',
    );
    register_post_type('proposal', $args_proposal);
  }
  public function proposal_post_status()
  {
    register_post_status('reviewing', array(
      'label'                     => 'Reviewing ',
      'public'                    => true,
      'label_count'               => _n_noop('Reviewing s <span class="count">(%s)</span>', 'Reviewing s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('reviewed', array(
      'label'                     => 'Reviewed ',
      'public'                    => true,
      'label_count'               => _n_noop('Reviewed s <span class="count">(%s)</span>', 'Reviewed s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('pengajuan_dana', array(
      'label'                     => 'Pengajuan Dana ',
      'public'                    => true,
      'label_count'               => _n_noop('Pengajuan Dana  s <span class="count">(%s)</span>', 'Pengajuan Dana  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('monev_I', array(
      'label'                     => 'Monev I ',
      'public'                    => true,
      'label_count'               => _n_noop('Monev I  s <span class="count">(%s)</span>', 'Monev I  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('monev_II', array(
      'label'                     => 'Monev II ',
      'public'                    => true,
      'label_count'               => _n_noop('Monev II  s <span class="count">(%s)</span>', 'Monev II  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('dana_I_disetujui', array(
      'label'                     => 'Dana I Setuju',
      'public'                    => true,
      'label_count'               => _n_noop('Dana I Setuju  s <span class="count">(%s)</span>', 'Dana I Setuju  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('dana_II_disetujui', array(
      'label'                     => 'Dana II Setuju',
      'public'                    => true,
      'label_count'               => _n_noop('Dana II Setuju  s <span class="count">(%s)</span>', 'Dana II Setuju  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('laporan_lpj_i', array(
      'label'                     => 'Laporan LPJ I',
      'public'                    => true,
      'label_count'               => _n_noop('Laporan LPJ I  s <span class="count">(%s)</span>', 'Laporan LPJ I  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
    register_post_status('laporan_lpj_ii', array(
      'label'                     => 'Laporan LPJ II',
      'public'                    => true,
      'label_count'               => _n_noop('Laporan LPJ II  s <span class="count">(%s)</span>', 'Laporan LPJ II  s <span class="count">(%s)</span>', 'plugin-domain'),
      'post_type'                 => array('proposal'), // Define one or more post types the status can be applied to.
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'show_in_metabox_dropdown'  => true,
      'show_in_inline_dropdown'   => true,
    ));
  }

  public function change_proposal_status($data)
  {
    if ((current_user_can('reviewer')) && ($data['post_type'] == 'proposal')) {
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
      //then set the fields you want to update
      wp_update_post(array(
        'ID'          => $data,
        'post_status' => 'reviewed',
      ));
    }
    return $data;
  }

  public function custom_table_header_proposal($columns)
  {
    $columns['proposal_ketua'] = __('Ketua Proposal', 'Ketua Proposal');
    $columns['proposal_reviewer'] = __('Reviewer Proposal', 'Reviewer Proposal');
    $columns['proposal_nilai_reviewer'] = __('Nilai Reviewer', 'Nilai Reviewer');
    $columns['proposal_dana'] = __('Dana', 'Dana');
    $columns['proposal_status'] = __('Status', 'Status');

    return $columns;
  }

  public function custom_columns_proposal($columns, $post_id)
  {
    if ($columns == 'proposal_ketua') {
      $proposal_ketua = get_post_meta($post_id, 'proposal_ketua', true);
      echo $proposal_ketua;
    }

    if ($columns == 'proposal_reviewer') {
      $proposal_reviewer = get_post_meta($post_id, 'reviewer', true);
      $nama_reviewer = '';
      if ($proposal_reviewer !== '') {
        $nama_reviewer = get_userdata($proposal_reviewer)->display_name;
      }
      echo $nama_reviewer;
    }
    if ($columns == 'proposal_nilai_reviewer') {
      $nilai_reviewer = get_post_meta($post_id, 'nilai_reviewer_proposal', true);
      echo $nilai_reviewer;
    }
    if ($columns == 'proposal_dana') {
      $proposal_dana = get_post_meta($post_id, 'proposal_dana', true);
      echo $proposal_dana;
    }
    if ($columns == 'proposal_status') {
      $proposal_status = get_post_meta($post_id, 'proposal_status', true);
      echo $proposal_status;
    }
  }

  public function my_title_place_holder($title, $post)
  {

    if ($post->post_type == 'proposal') {
      $my_title = "Judul Proposal";
      return $my_title;
    }

    return $title;
  }

  public function proposal_meta_box()
  {
    add_meta_box('proposal_editor', 'Proposal Editor', [$this, 'proposal_editor_html'], 'proposal', 'normal', 'high');
    add_meta_box('nilai_reviewer', 'Nilai Reviewer', [$this, 'nilai_proposal_meta_html'], 'proposal', 'normal');
  }
  public function save_proposal($post_id)
  {
    if (array_key_exists('judul_proposal', $_POST)) {
      update_post_meta($post_id, 'proposal_judul', $_POST['judul_proposal']);
    }
    if (array_key_exists('nama_ketua', $_POST)) {
      update_post_meta($post_id, 'proposal_ketua', $_POST['nama_ketua']);
    }
    if (array_key_exists('kategori_proposal', $_POST)) {
      update_post_meta($post_id, 'proposal_kategori', $_POST['kategori_proposal']);
    }
    if (array_key_exists('prodi_ketua', $_POST)) {
      update_post_meta($post_id, 'proposal_prodi', $_POST['prodi_ketua']);
    }
    if (array_key_exists('dana_proposal', $_POST)) {
      update_post_meta($post_id, 'proposal_dana', $_POST['dana_proposal']);
    }
    if (array_key_exists('data_dukung_proposal', $_POST)) {
      update_post_meta($post_id, 'proposal_data_dukung', $_POST['data_dukung_proposal']);
    }
    if (array_key_exists('status_proposal', $_POST)) {
      update_post_meta($post_id, 'proposal_status', $_POST['status_proposal']);
    }
    if (array_key_exists('status_pencairan_dana', $_POST)) {
      update_post_meta($post_id, 'proposal_pencairan_dana', $_POST['status_pencairan_dana']);
    }
    if (array_key_exists('nilai_rev', $_POST)) {
      update_post_meta($post_id, 'nilai_reviewer_proposal', $_POST['nilai_rev']);
    }
    if (array_key_exists('target_cap', $_POST)) {
      update_post_meta($post_id, 'target_nilai_proposal', $_POST['target_cap']);
    }
    if (array_key_exists('status_proposal', $_POST)) {
      update_post_meta($post_id, 'proposal_status', 'reviewed');
    }
  }
  public function proposal_editor_html()
  {
    global $current_user, $post;
    $id = $post->ID;
    if ($id) {
      $output_judul = stripslashes(get_post_meta($id, 'proposal_judul', true));
      $output_ketua = stripslashes(get_post_meta($id, 'proposal_ketua', true));
      $output_prodi = stripslashes(get_post_meta($id, 'proposal_prodi', true));
      $output_kategori = stripslashes(get_post_meta($id, 'proposal_kategori', true));
      $output_dana = stripslashes(get_post_meta($id, 'proposal_dana', true));
      $output_data_dukung = stripslashes(get_post_meta($id, 'proposal_data_dukung', true));
    }
?>
    <table class="form-table" role="presentation">
      <input type="hidden" name="status_proposal" value="menunggu review">
      <input type="hidden" name="status_pencairan_dana" value=" - ">
      <tr>
        <th><label for="judul_proposal">Judul Proposal</label></th>
        <td><input type="text" name="judul_proposal" id="judul_proposal" value="<?php echo esc_html($output_judul) ?>" class="regular-text" /></td>
        <th><label for="nama_ketua">Ketua Penelitian</label></th>
        <td><input type="text" name="nama_ketua" id="nama_ketua" value="<?php if ($output_ketua) {
                                                                          echo ($output_ketua);
                                                                        } else echo esc_html_e($current_user->display_name); ?>" class="regular-text" /></td>
      </tr>
      <tr>
        <th><label for="kategori_proposal">Kategori Proposal</label></th>
        <td>
          <select name="kategori_proposal" id="kategori_proposaol">
            <?php if ($output_kategori) { ?>
              <option value="<?php echo $output_kategori ?>" selected disabled hidden><?php echo esc_html($output_kategori) ?></option>
            <?php } ?>
            <option value="Kategori I">Kategori I</option>
            <option value="Kategori II">Kategori II</option>
            <option value="Kategori III">Kategori III</option>
            <option value="Kategori IV">Kategori IV</option>
          </select>
        </td>
        <th><label for="prodi_ketua">Prodi</label></th>
        <td><input type="text" name="prodi_ketua" id="prodi_ketua" value="<?php if ($output_prodi) {
                                                                            echo ($output_prodi);
                                                                          } else echo esc_html(get_user_meta($current_user->ID, 'jurusan', true)); ?>" class="regular-text" /></td>
      </tr>
      <tr>
        <th><label for="dana_proposal">Dana Proposal</label></th>
        <td><input type="number" min="0" step="50000" name="dana_proposal" id="dana_proposal" class="regular-text" value="<?php isset($output_dana) ? esc_html_e($output_dana) : '' ?>" /></td>
      </tr>
      <tr>
        <th><label for="data_dukung_proposal">Data Dukung SK Rektor</label></th>
        <td><input type="date" name="data_dukung_proposal" id="data_dukung_proposal" class="regular-text" value="<?php isset($output_data_dukung) ? esc_html_e($output_data_dukung) : '' ?>" /></td>
      </tr>
    </table>
    <?php
  }

  public function nilai_proposal_meta_html()
  {
    global $post, $current_user;
    $id = $post->ID;
    if ($id) {
      $output_nilai = stripslashes(get_post_meta($id, 'nilai_reviewer_proposal', true));
      $output_target = stripslashes(get_post_meta($id, 'target_nilai_proposal', true));
    }
    $have_reviewer = get_post_meta($id, 'reviewer', true);
    if (isset($have_reviewer)) {
      if ($current_user->ID == $have_reviewer) {
    ?>
        <table class="form-table" role="presentation">
          <tr>
            <th><label for="nilai_rev">Nilai Reviewer</label></th>
            <td><input type="number" name="nilai_rev" id="nilai_rev" min="0" max="100" value="<?php echo esc_html_e($output_nilai); ?>" /></td>
            <th><label for="target_cap">Target Capaian</label></th>
            <td><input type="number" name="target_cap" id="target_cap" min="0" max="100" value="<?php echo esc_html_e($output_target); ?>" /></td>
          </tr>
        </table>

<?php
      }
    }
  }
}

new proposal_post_editor();
// add_action('add_meta_boxes', 'proposal_add_metabox');
