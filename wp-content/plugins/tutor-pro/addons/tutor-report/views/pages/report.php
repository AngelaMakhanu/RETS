<?php
/**
 * Report Navbar Template
 *
 * @package Report
 */

$current_sub_page = 'overview';
$current_name    = __( 'Overview', 'tutor-pro' );
$sub_pages       = array(
	'overview' => __( 'Overview', 'tutor-pro' ),
	'courses'  => __( 'Courses', 'tutor-pro' ),
	'reviews'  => __( 'Reviews', 'tutor-pro' ),
	'sales'    => __( 'Sales', 'tutor-pro' ),
	'students' => __( 'Students', 'tutor-pro' ),
);

if ( ! empty( $_GET['sub_page'] ) ) {
	$current_sub_page = sanitize_text_field( $_GET['sub_page'] );
	$current_name    = isset( $sub_pages[ $current_sub_page ] ) ? $sub_pages[ $current_sub_page ] : '';
}
?>
<header
  class="tutor-wp-dashboard-header tutor-d-xl-flex tutor-justify-content-between tutor-align-items-center tutor-px-24 tutor-py-20 tutor-mb-24" style="margin-left:-20px">
  <div class="header-title-wrap tutor-d-flex tutor-align-items-center tutor-flex-wrap tutor-mb-xl-0 tutor-mb-4 tutor-color-black">
		<span class="tutor-fs-5 tutor-fw-medium">
			<?php esc_html_e( 'LMS Reports', 'tutor-pro' ); ?>
		</span>
		<span class="tutor-fs-7 tutor-color-black">
			/ <?php echo esc_html( $current_name ); ?>
		</span>
  </div>
  <div class="tutor-fs-6 tutor-color-black-60">
	<div class="tutor-admin-page-navbar-tabs filter-btns ">
		<ul style="display: flex; column-gap: 15px;">
			<?php foreach ( $sub_pages as $key => $page ) : ?>
				<?php
					$is_active = $page === $current_name ? 'is-active' : '';
					$url       = add_query_arg(
						array(
							'page'     => 'tutor_report',
							'sub_page' => $key,
						),
						admin_url( 'admin.php' )
					);
				?>
				<a href="<?php echo esc_url( $url ); ?>" class="filter-btn <?php echo esc_attr( $is_active ); ?>">
					<?php echo esc_html( $page ); ?>
				</a>
			<?php endforeach; ?>
		</ul>
	</div>
  </div>
</header>

<div class="report-main-wrap">
	<div class="tutor-report-content">
		<?php
		$page = 'overview';
		if ( ! empty( $_GET['sub_page'] ) ) {
			$page = sanitize_text_field( $_GET['sub_page'] );
		}
		$view_page = TUTOR_REPORT()->path . 'views/pages/';

		if ( file_exists( $view_page . $page . "/{$page}.php" ) ) {
			include $view_page . $page . "/{$page}.php";
		} elseif ( file_exists( $view_page . "{$page}.php" ) ) {
			include $view_page . "{$page}.php";
		}
		?>
	</div>
</div>
