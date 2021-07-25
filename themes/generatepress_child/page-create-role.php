<!DOCTYPE html>
<?php
/**
 * The template for create-role page.
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
global $current_user;
if ( ! in_array( 'administrator', $current_user->roles, true ) ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}
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
			<div class="entry-content table-responsive">
			<?php
				$user_query = get_users( array( 'role' => 'subscriber' ) );

			if ( isset( $_POST['submit_role'] ) ) {
				$user_selected      = isset( $_POST['user_id'] ) ? wp_unslash( $_POST['user_id'] ) : '';
				$user_selected_role = isset( $_POST['user_role'] ) ? strtolower( wp_unslash( $_POST['user_role'] ) ) : '';

				if ( ! empty( $user_selected_role ) ) {
					$user_lemlit = get_user_by( 'id', $user_selected );

					$update_user = wp_update_user( $user_lemlit->set_role( $user_selected_role ) );
					if ( ! is_wp_error( $update_user ) ) {
						update_user_meta( $user_selected, 'role_status', 'Accepted' );
						echo "<meta http-equiv='refresh' content='0'>";
					}
				}
			}
			?>
				<table class="table table-bordered role">
					<thead>
						<tr>
							<th>#</th>
							<th>NIP</th>
							<th>Nama</th>
							<th>Prodi</th>
							<th>Role</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if ( $user_query ) {
						foreach ( $user_query as $key => $new_user ) {
							$user_id  = $new_user->ID;
							$prodi    = get_user_meta( $user_id, 'jurusan', true );
							$role_req = get_user_meta( $user_id, 'role_select', true );
							?>
						<tr>
							<td><?php echo esc_html( $key + 1 ); ?></td>
							<td><?php echo esc_html( $new_user->user_login ); ?></td>
							<td><?php echo esc_html( $new_user->display_name ); ?></td>
							<td><?php echo esc_html( $prodi ); ?></td>
							<td><?php echo esc_html( $role_req ); ?></td>
							<td>Request</td>
							<td>
								<div class="flex">
									<form name="submit_role" method="post">
									<input type="hidden" name="user_id" value="<?php echo esc_html( $new_user->ID ); ?>">
									<input type="hidden" name="user_role" value="<?php echo esc_html( $role_req ); ?>">
									<input  type="submit" name="submit_role" value="Setujui"/>
									</form>
								</div>
							</td>
						</tr>
							<?php
						}
					} else {
						echo esc_html( '<tr>Kosong</tr>' );
					}
					?>
					</tbody>
				</table>
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
