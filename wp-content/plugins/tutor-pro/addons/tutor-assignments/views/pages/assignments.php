<?php
use \TUTOR_ASSIGNMENTS\Assignments_List;
$assignments = new \TUTOR_ASSIGNMENTS\Assignments_List();

/**
 * @since 1.8.0
 */

$course_id = isset( $_GET['course-id'] ) ? $_GET['course-id'] : '';
$user_id   = current_user_can( 'administrator' ) ? 0 : get_current_user_id();
$order     = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
$date      = isset( $_GET['date'] ) && '' !== $_GET['date'] ? tutor_get_formated_date( 'Y-m-d', $_GET['date'] ) : '';
$search    = isset( $_GET['search'] ) ? $_GET['search'] : '';

/**
 * Determine active tab
 */
$active_tab = isset( $_GET['data'] ) && $_GET['data'] !== '' ? esc_html__( $_GET['data'] ) : 'all';

/**
 * Pagination data
 */
$paged    = ( isset( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) && $_GET['paged'] >= 1 ) ? $_GET['paged'] : 1;
$per_page = tutor_utils()->get_option( 'pagination_per_page' );
$offset   = ( $per_page * $paged ) - $per_page;

/**
 * Navbar data to make nav menu
 */
$assignments_list = array();
if ( 'all' === $active_tab ) {
	$assignments_list = Assignments_List::assignment_list_all( $active_tab, $course_id, $date, $search, $offset, $per_page, $order, $user_id );
}

if ( 'pending' === $active_tab ) {
	$assignments_list = Assignments_List::assignment_list_pending( $active_tab, $course_id, $date, $search, $offset, $per_page, $order, $user_id );
}

if ( 'pass' === $active_tab || 'fail' === $active_tab ) {
	$assignments_list = Assignments_List::assignment_list_pass_fail( $active_tab, $course_id, $date, $search, $offset, $per_page, $order, $user_id );
}

$total = 0;
if ( 'all' === $active_tab ) {
	$total = $assignments::assignment_list_all( $active_tab, $course_id, $date, $search, $user_id );
	$total = is_array( $total ) ? count( $total ) : 0;
}
if ( 'pending' === $active_tab ) {
	$total = $assignments::assignment_list_pending( $active_tab, $course_id, $date, $search, $user_id );
	$total = is_array( $total ) ? count( $total ) : 0;
}
if ( 'pass' === $active_tab || 'fail' === $active_tab ) {
	$total = $assignments::assignment_list_pass_fail( $active_tab, $course_id, $date, $search, $user_id );
	$total = is_array( $total ) ? count( $total ) : 0;
}

$navbar_data = array(
	'page_title' => $assignments->page_title,
	'tabs'       => $assignments->tabs_key_value( $course_id, $date, $search ),
	'active'     => $active_tab,
);

$filters = array(
	'filters'       => true,
	'course_filter' => true,
);
?>
<div class="tutor-admin-page-wrapper">
	<?php
		/**
		 * Load Templates with data.
		 */
		$navbar_template  = tutor()->path . 'views/elements/navbar.php';
		$filters_template = tutor()->path . 'views/elements/filters.php';
		tutor_load_template_from_custom_path( $navbar_template, $navbar_data );
		tutor_load_template_from_custom_path( $filters_template, $filters );
	?>

	<div class="tutor-admin-page-content-wrapper tutor-mt-24 tutor-pr-20 tutor-mb-4">
		<div class="tutor-ui-table-wrapper">
			<table class="tutor-ui-table tutor-ui-table-responsive">
				<thead class="tutor-text-sm tutor-text-400">
					<tr>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Assignment Name', 'tutor-pro' ); ?>
							</span>
						</th>
						<th class="tutor-table-rows-sorting">
							<div class="inline-flex-center tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php esc_html_e( 'Student', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
							</div>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Total Points', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Passing Points', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Duration', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Date', 'tutor-pro' ); ?>
							</span>
						</th>
						<th class="tutor-shrink"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $assignments_list as $index => $list ) : ?>
						<?php $assignment_row_id = 'tutor-assignment-row-' . $index; ?>
					<tr id="<?php echo $assignment_row_id; ?>">
						<td data-th="Course Name" class="column-fullwidth">
							<div class="tutor-color-black td-course tutor-fs-6 tutor-fw-medium">
								<?php $assignments->column_title( $list ); ?>
							</div>
						</td>
						<td data-th="Student">
							<div class="td-avatar">
								<?php $assignments->column_student( $list ); ?>
							</div>
						</td>
						<td data-th="Total Points">
							<span class="tutor-color-black tutor-fs-7 tutor-fw-medium"><?php $assignments->column_mark( $list ); ?></span>
						</td>
						<td data-th="Passing Points">
							<span class="tutor-color-black tutor-fs-7 tutor-fw-medium"><?php $assignments->column_passing_mark( $list ); ?></span>
						</td>
						<td data-th="Duration">
							<span class="tutor-color-black tutor-fs-7 tutor-fw-medium"><?php $assignments->column_duration( $list ); ?></span>
						</td>
						<td data-th="Date">
							<div class="tutor-color-black td-course tutor-fs-6 tutor-fw-medium">
								<?php $assignments->column_date( $list ); ?>
							</div>
						</td>
						<td data-th="URL">
							<div class="inline-flex-center td-action-btns">
							<?php $assignments->column_action_evaluate( $list, $list->comment_post_ID ); ?>
						
							<?php $assignments->column_action_delete( $list ); ?>
							</div>
							<!--Modal-->
							<div id="tutor-assignment-modal-<?php esc_attr_e( $list->comment_ID ); ?>" class="tutor-modal">
								<span class="tutor-modal-overlay"></span>
								<button data-tutor-modal-close class="tutor-modal-close">
									<span class="las la-times"></span>
								</button>
								<div class="tutor-modal-root">
									<div class="tutor-modal-inner">
										<div class="tutor-modal-body tutor-text-center">
											<div class="tutor-modal-icon">
												<img src="<?php echo esc_url( tutor()->url . 'assets/images/icon-trash.svg' ); ?>" />
											</div>
											<div class="tutor-modal-text-wrap">
												<h3 class="tutor-modal-title">
													<?php _e( 'Delete This Assignment?', 'tutor' ); ?>
												</h3>
												<p>
													<?php _e( 'Are you sure you want to delete this assignment permanently from the course? Please confirm your choice.', 'tutor' ); ?>
												</p>
											</div>
											<div class="tutor-modal-btns tutor-btn-group">
												<button data-tutor-modal-close class="tutor-btn tutor-is-outline tutor-is-default">
													<?php _e( 'Cancel', 'tutor' ); ?>
												</button>
												<button class="tutor-btn tutor-list-ajax-action" data-request_data='{"action":"delete_tutor_course_assignment_submission","assignment_id":<?php echo $list->comment_ID; ?>}' data-delete_element_id="<?php echo $assignment_row_id; ?>">
													<?php _e( 'Yes, Delete This', 'tutor' ); ?>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
					<?php if ( count( $assignments_list ) === 0 ) : ?>
						<tr>
							<td colspan="100%">
								<div class="td-empty-state">
									<?php tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() ); ?>
								</div>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="tutor-admin-page-pagination-wrapper tutor-pr-4">
		<?php
			/**
			 * Prepare pagination data & load template
			 */
		if ( $total > $per_page ) {
			$pagination_data     = array(
				'total_items' => $total,
				'per_page'    => $per_page,
				'paged'       => $paged,
			);
			$pagination_template = tutor()->path . 'views/elements/pagination.php';
			tutor_load_template_from_custom_path( $pagination_template, $pagination_data );
		}
		?>
	</div>
</div>
