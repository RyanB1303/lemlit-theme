<!DOCTYPE html>
<?php
/**
 * The template for login page.
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
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/profile' ) );
	exit;
}

if ( isset( $_POST['login_lemlit'] ) ) {

	$username = $_POST['nip'];
	$password = $_POST['password'];

	$user_creds  = array(
		'user_login'    => $username,
		'user_password' => $password,
	);
	$user_lemlit = wp_signon( $user_creds, false );
	if ( ! is_wp_error( $user_lemlit ) ) {
		wp_safe_redirect( home_url( '/profile' ) );
		exit;
	}
}
get_header();?>

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
	  <h1 class="entry-title" itemprop="headline">Login</h1>
	  <div class="entry-content">
		<form method="POST" name="login_lemlit">
		  <div class="form-group">
			<label for="nip">NIP</label>
			<input type="text" class="form-control" id="nip" aria-describedby="nip" name="nip" placeholder="Masukkan NIP">
		  </div>
		  <div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
		  </div>
		  <input type="submit" class="btn btn-primary" value="Login" name="login_lemlit"/>
		  atau register<a href="<?php echo site_url( '/register' ); ?>"> disini</a>
		</form>
	  </div>
	</div>
	<?php
	/**
	 * generate_after_main_content hook.
	 *
	 * @since 0.1
	 */
	do_action( 'generate_after_main_content' );
	?>
  </main>
</div>

<?php
/**
 * generate_after_primary_content_area hook.
 *
 * @since 2.0
 */
do_action( 'generate_after_primary_content_area' );

get_footer();
