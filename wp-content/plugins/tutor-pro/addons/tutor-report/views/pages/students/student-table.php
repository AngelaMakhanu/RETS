<?php
/**
 * Student list template
 *
 * @package Report
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TUTOR_REPORT\Report;

$report    = new Report();
$course_id = isset( $_GET['course-id'] ) ? $_GET['course-id'] : '';
$order     = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
$date      = isset( $_GET['date'] ) ? $_GET['date'] : '';
$search    = isset( $_GET['search'] ) ? $_GET['search'] : '';


$current_page  = ( isset( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) && $_GET['paged'] >= 1 ) ? $_GET['paged'] : 1;
$item_per_page = tutor_utils()->get_option( 'pagination_per_page' );
$offset        = ( $item_per_page * $current_page ) - $item_per_page;

$sales_list  = tutor_utils()->get_students_by_instructor( 0, $offset, $item_per_page, $search, $course_id, $date, $order_by = '', $order );
$lists       = $sales_list['students'];
$total_items = $sales_list['total_students'];

$filters          = array(
	'bulk_action'   => true,
	'bulk_actions'  => $report->student_list_bulk_actions(),
	'ajax_action'   => 'tutor_admin_student_list_bulk_action',
	'filters'       => true,
	'course_filter' => true,
);
$filters_template = tutor()->path . 'views/elements/filters.php';
?>



<div id="tutor-report-students" class="tutor-report-common">
	<?php
		// Bulk action and filters.
		tutor_load_template_from_custom_path( $filters_template, $filters );
	?>
	<div class="tutor-report-students-data-table tutor-mt-24 tutor-pr-20">
		<div class="tutor-ui-table-wrapper">
			<table class="tutor-ui-table tutor-ui-table-responsive table-dashboard-course-list td-align-middle">
				<thead>
					<tr>
						<th>
							<div class="tutor-d-flex">
								<input type="checkbox" id="tutor-bulk-checkbox-all" class="tutor-form-check-input" />
							</div>
						</th>
						<th class="tutor-table-rows-sorting">
							<div class="tutor-align-items-center tutor-d-flex">
								<span class="tutor-fs-7 tutor-color-black-60">
									<?php esc_html_e( 'Name', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon tutor-color-black-60"></span>
							</div>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Email', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Registration Date', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Course Taken', 'tutor-pro' ); ?>
							</span>
						</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( is_array( $lists ) && count( $lists ) ) : ?>
						<?php foreach ( $lists as $student ) : ?>
							<tr>
								<td data-th="<?php esc_html_e( 'Checkbox', 'tutor-pro' ); ?>">
									<div class="td-checkbox tutor-d-flex ">
										<input type="checkbox" class="tutor-form-check-input tutor-bulk-checkbox" value="<?php echo esc_attr( $student->ID ); ?>"/>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Name', 'tutor-pro' ); ?>" class="name">
									<div class="td-avatar">
										<?php echo get_avatar( $student->ID ); ?>
										<p class="tutor-fs-6 tutor-fw-medium  tutor-color-black">
											<?php echo esc_html( $student->display_name ); ?>
										</p>
										<a href="<?php echo esc_url( tutor_utils()->profile_url( $student->ID, false ) ); ?>" class="btn-text btn-detail-link" target="_blank">
											<span class="tutor-icon-detail-link-filled tutor-color-design-dark"></span>
										</a>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Email', 'tutor-pro' ); ?>" class="email tutor-fs-7 tutor-fw-medium tutor-color-black-70">
									<?php echo esc_html( $student->user_email ); ?>
								</td>
								<td data-th="<?php esc_html_e( 'Registration Date', 'tutor-pro' ); ?>">
									<div class="registration-date td-datetime tutor-fs-7 tutor-fw-medium tutor-color-black-70">
										<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $student->user_registered ) ); ?>, 
										<?php echo esc_html( tutor_get_formated_date( get_option( 'time_format' ), $student->user_registered ) ); ?>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Course Taken', 'tutor-pro' ); ?>" class="course-taken">
									<div class="tutor-fs-7 tutor-fw-medium tutor-color-black-70">
										<?php echo esc_html( $student->course_taken ); ?>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'URL', 'tutor-pro' ); ?>" class="url">
									<div class="inline-flex-center td-action-btns">
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=tutor_report&sub_page=students&student_id=' . $student->ID ) ); ?>" class="btn-outline tutor-btn">
											Details
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="100%" class="column-empty-state">
							<?php tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() ); ?>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div> <!-- tutor-report-overview-data-table  -->
	<div class="tutor-report-students-data-table-pagination tutor-report-content-common-pagination">
		<?php
		if($total_items > $item_per_page) {
			$pagination_data     = array(
				'base'        => str_replace( 1, '%#%', 'admin.php?page=tutor_report&sub_page=students&paged=%#%' ),
				'per_page'    => $item_per_page,
				'paged'       => $current_page,
				'total_items' => $total_items,
			);
			$pagination_template = tutor()->path . 'views/elements/pagination.php';
			tutor_load_template_from_custom_path( $pagination_template, $pagination_data );
		}
		?>
	</div> <!-- tutor-report-overview-data-table-pagination  -->
</div> <!-- tutor-report-sales -->


