<!DOCTYPE html>
<?php
/**
 * The template for profile page.
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
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/login' ) );
	exit;
}
global $current_user;

get_header(); ?>

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

	  <header class="entry-header">
		<?php
		/**
		 * Generate_before_page_title hook.
		 *
		 * @since 2.4
		 */
		do_action( 'generate_before_page_title' );

		if ( generate_show_title() ) {
			$params = generate_get_the_title_parameters();

			the_title( $params['before'], $params['after'] );
		}

		/**
		 * Generate_after_page_title hook.
		 *
		 * @since 2.4
		 */
		do_action( 'generate_after_page_title' );
		?>
	  </header>
	  <div class="entry-content">
		<form>
		  <div class="form-group row">
			<label for="nip" class="col-sm-2 col-form-label">NIP</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="nip" value="<?php echo esc_html( $current_user->user_login ); ?>" disabled>
			</div>
		  </div>
		  <div class="form-group row">
			<label for="nama" class="col-sm-2 col-form-label">Nama</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="nama" value="<?php echo esc_html( $current_user->user_nicename ); ?>" disabled>
			</div>
		  </div>
		  <!-- <div class="form-group row">
			<label for="password" class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-10">
			  <input type="password" class="form-control" id="password" placeholder="********">
			</div>
		  </div> -->
		  <div class="form-group row">
			<label for="role" class="col-sm-2 col-form-label">Role</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="role" value="<?php echo esc_html( get_user_role() ); ?>" disabled >
			</div>
		  </div>
		  <div class="form-group row">
			<label for="jurusan" class="col-sm-2 col-form-label">Program Studi</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="jurusan" value="<?php echo esc_html( get_user_meta( $current_user->ID, 'jurusan', true ) ); ?>" disabled>
			</div>
		  </div>
		  <div class="form-group row">
			<label for="email" class="col-sm-2 col-form-label">Email</label>
			<div class="col-sm-10">
			  <input type="email" class="form-control" id="email" value="<?php echo esc_html( $current_user->user_email ); ?>">
			</div>
		  </div>
		  <div class="form-group row">
			<label for="whatsapp" class="col-sm-2 col-form-label">Whatsapp</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="whatsapp" value="<?php echo esc_html( get_user_meta( $current_user->ID, 'telepon', true ) ); ?>">
			</div>
		  </div>
		  <div class="form-group row">
			<label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
			<div class="col-sm-10">
			  <input type="text" class="form-control" id="alamat" value="<?php echo esc_html( get_user_meta( $current_user->ID, 'alamat', true ) ); ?>">
			</div>
		  </div>
		  <!-- <button type="submit" class="btn btn-success btn-block mt-5">Edit</button> -->
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

generate_construct_sidebars();

get_footer();
