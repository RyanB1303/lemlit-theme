<?php
/**
 * The template for register page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $wpdb, $user_ID;
if ( $user_ID ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}

	get_header();
?>

<div id="primary" <?php generate_do_element_classes( 'content' ); ?>>
	<main id="main" <?php generate_do_element_classes( 'main' ); ?>>

	<?php
			/**
			 * Generate_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'generate_before_main_content' );
	?>
		<div class="inside-article">
			<?php
				global $reg_errors;
				$reg_errors = new WP_Error();
			if ( isset( $_POST['register_lemlit'] ) ) {

				$username         = $_POST['nip'];
				$nama             = $_POST['nama'];
				$fakultas         = $_POST['fakultas'];
				$jurusan          = $_POST['jurusan'];
				$role_select      = $_POST['role_req'];
				$useremail        = $_POST['email'];
				$password         = $_POST['password'];
				$password_confirm = $_POST['password_confirm'];
				$telepon          = $_POST['telepon'];
				$alamat           = $_POST['alamat'];

				if ( empty( $username ) || empty( $useremail ) || empty( $password ) || empty( $nama ) || empty( $role_select ) || empty( $fakultas ) || empty( $jurusan ) ) {
					$reg_errors->add( 'field', 'Data Harus Diisi' );
				}
				if ( username_exists( $username ) ) {
					$reg_errors->add( 'user_name', 'NIP Sudah Terdaftar!!' );
				}
				if ( ! validate_username( $username ) ) {
					$reg_errors->add( 'username_invalid', 'NIP Tidak Valid' );
				}
				if ( email_exists( $useremail ) ) {
					$reg_errors->add( 'email_exists', 'Email Sudah Terdaftar' );
				}
				if ( ! is_email( $useremail ) ) {
					$reg_errors->add( 'email_invalid', 'Email Tidak Sesuai' );
				}

				if ( 1 > count( $reg_errors->get_error_messages() ) ) {
					global $username, $useremail;
					$username    = sanitize_user( $_POST['nip'] );
					$useremail   = sanitize_email( $_POST['email'] );
					$password    = esc_attr( $_POST['password'] );
					$user_desc   = "{$nama} - {$username} - {$role_select} : {$fakultas} - {$jurusan}";
					$userbaru    = array(
						'user_login'    => $username,
						'display_name'  => $nama,
						'user_nicename' => $nama,
						'first_name'    => $nama,
						'description'   => $user_desc,
						'user_email'    => $useremail,
						'user_pass'     => $password,
						'role'          => 'subscriber',
					);
					$user_lemlit = wp_insert_user( $userbaru );
					if ( ! is_wp_error( $user_lemlit ) ) {
						add_user_meta( $user_lemlit, 'fakultas', $fakultas );
						add_user_meta( $user_lemlit, 'jurusan', $jurusan );
						add_user_meta( $user_lemlit, 'role_select', $role_select );
						add_user_meta( $user_lemlit, 'role_status', 'Request' );
						add_user_meta( $user_lemlit, 'telepon', $telepon );
						add_user_meta( $user_lemlit, 'alamat', $alamat );
					}
					redirect_to_login();
				}
			}
			?>
			<h1 class="entry-title" itemprop="headline">Register</h1>
			<div class="entry-content">
				<?php
				if ( is_wp_error( $reg_errors ) ) {
					foreach ( $reg_errors->get_error_messages() as $error_register ) {
						$register_error = '<p style="color:#FF0000; text-aling:left;"><strong>ERROR</strong>: ' . $error_register . '<br /></p>';
						echo '<div class="alert alert-danger full-width">' . $register_error . '</div>';
					}
				}
				?>
				<form method="POST" name="register_lemlit" action="">
					<div class="form-group row">
						<label for="nip" class="col-sm-2 col-form-label">NIP</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nip" placeholder="NIP" name="nip">
						</div>
					</div>
					<div class="form-group row">
						<label for="nama" class="col-sm-2 col-form-label">Nama</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="nama" placeholder="Nama" name="nama">
						</div>
					</div>
					<div class="form-group row">
						<label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
						<div class="col-sm-10">
							<select name="fakultas" id="fakultas" class="form-control" required>
								<option value="" selected disabled>Pilih Fakultas</option>
								<option value="FTI">FTI</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
						<div class="col-sm-10">
							<select name="jurusan" id="jurusan" class="form-control" required>
								<option value="" selected disabled>Pilih Jurusan</option>
								<option value="Teknik Mesin">Teknik Mesin</option>
								<option value="Teknik Elektro">Teknik Elektro</option>
								<option value="Teknik Industri">Teknik Industri</option>
								<option value="Teknik Informatika">Teknik Informatika</option>
								<option value="Sistem Informasi">Sistem Informasi</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="role_req" class="col-sm-2 col-form-label">Role</label>
						<div class="col-sm-10">
							<select name="role_req" id="role_req" class="form-control" required>
								<option value="" selected disabled>Pilih Peran Anda</option>
								<option value="Peneliti">Peneliti</option>
								<option value="Reviewer">Reviewer</option>
								<option value="Dekan">Dekan</option>
								<option value="Drf">Drf</option>
								<option value="Lemlit">Lemlit</option>
								<option value="Jurusan">Jurusan</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="email" class="col-sm-2 col-form-label">Email</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" placeholder="Email" name="email">
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-2 col-form-label">Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password" placeholder="********" name="password">
						</div>
					</div>
					<div class="form-group row">
						<label for="password_confirm" class="col-sm-2 col-form-label">Konfirmasi Password</label>
						<div class="col-sm-10">
							<input type="password" class="form-control" id="password_confirm" placeholder="********" name="password_confirm">
						</div>
					</div>
					<div class="form-group row">
						<label for="telepon" class="col-sm-2 col-form-label">No. HP / No. Telp</label>
						<div class="col-sm-10">
							<input type="tel" class="form-control" id="telepon" placeholder="Telepon" name="telepon">
						</div>
					</div>
					<div class="form-group row">
						<label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="alamat" placeholder="Alamat" name="alamat">
						</div>
					</div>
					<input name="register_lemlit" type="submit" class="btn btn-primary" />
				</form>
			</div>
		</div>
			<?php
			/**
			 * Generate_after_main_content hook.
			 *
			 * @since 0.1
			 */

			do_action( 'generate_after_main_content' );
			?>
	</main>
</div>

	<?php
	/**
	 * Generate_after_primary_content_area hook.
	 *
	 * @since 2.0
	 */
	do_action( 'generate_after_primary_content_area' );

	get_footer();
