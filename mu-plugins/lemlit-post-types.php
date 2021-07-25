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
		add_action('add_meta_boxes', [$this, 'proposal_meta_box']);
		add_action('save_post', [$this, 'save_proposal']);
		add_action('admin_init', [$this, 'proposal_admin_menu'], 999);
		add_action('admin_bar_menu', [$this, 'proposal_admin_bar'], 999);
		add_filter('pre_get_posts', [$this, 'posts_for_current_author']);
		add_filter('enter_title_here', [$this, 'my_title_place_holder'], 20, 2);
	}

	public function proposal_admin_menu()
	{
		if (current_user_can('peneliti')) {

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
			'author'
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
				<td><input type="text" name="nama_ketua" id="nama_ketua" value="<?php echo esc_html($current_user->display_name); ?>" class="regular-text" /></td>
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
				<td><input type="number" min="0" step="50000" name="dana_proposal" id="dana_proposal" class="regular-text" value="" /></td>
			</tr>
			<tr>
				<th><label for="data_dukung_proposal">Data Dukung SK Rektor</label></th>
				<td><input type="date" name="data_dukung_proposal" id="data_dukung_proposal" class="regular-text" /></td>
			</tr>
		</table>
<?php
	}
}

new proposal_post_editor();
// add_action('add_meta_boxes', 'proposal_add_metabox');
