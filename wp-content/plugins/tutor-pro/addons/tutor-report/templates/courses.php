<?php

	use \TUTOR_REPORT\Analytics;

	global $wp_query, $wp;
	$user     = wp_get_current_user();
	$paged    = ( isset( $_GET['current_page'] ) && is_numeric( $_GET['current_page'] ) && $_GET['current_page'] >= 1 ) ? $_GET['current_page'] : 1;
	$url      = home_url( $wp->request );
	$url_path = parse_url( $url, PHP_URL_PATH );
	$basename = pathinfo( $url_path, PATHINFO_BASENAME );
	$per_page   = tutor_utils()->get_option( 'pagination_per_page' );
	$offset     = ( $per_page * $paged ) - $per_page;
	$orderby    = ( ! isset( $_GET['orderby'] ) || $_GET['orderby'] !== 'earning' ) ? 'learner' : 'earning';
	$order      = ( ! isset( $_GET['order'] ) || $_GET['order'] !== 'desc' ) ? 'asc' : 'desc';
	$sort_order = array( 'order' => $order == 'asc' ? 'desc' : 'asc' );

	$learner_sort = http_build_query( array_merge( $_GET, ( $orderby == 'learner' ? $sort_order : array() ), array( 'orderby' => 'learner' ) ) );
	$earning_sort = http_build_query( array_merge( $_GET, ( $orderby == 'earning' ? $sort_order : array() ), array( 'orderby' => 'earning' ) ) );

	$learner_order_icon = $orderby == 'learner' ? ( strtolower( $order ) == 'asc' ? 'up' : 'down' ) : 'up';
	$earning_order_icon = $orderby == 'earning' ? ( strtolower( $order ) == 'asc' ? 'up' : 'down' ) : 'up';

	$search = isset( $_GET['search'] ) ? $_GET['search'] : '';

	$courses      = Analytics::get_courses_with_total_enroll_earning( $user->ID, $sort_order['order'], is_null( $orderby ) ? '' : $orderby, $offset, $per_page, $search );
	$total_course = Analytics::get_courses_with_search_by_user( $user->ID, $search );

?>
<div class="tutor-analytics-courses">
	<div class="search-wrapper">
		<form action="" method="get" id="tutor_analytics_search_form">
			<div class="tutor-input-group tutor-form-control-has-icon tutor-form-control-sm">
				<span class="tutor-icon-search-filled tutor-input-group-icon color-black-50"></span>
				<input type="search" class="tutor-form-control tutor-form-control-fd" autocomplete="off" name="search" placeholder="<?php esc_attr_e( 'Search...', 'tutor-pro' ); ?>">
			</div>
		</form>
	</div>
	<div class="tutor-ui-table-wrapper">
		<table class="tutor-ui-table tutor-ui-table-responsive table-all-courses" style="--column-count:4">
			<thead>
				<th>
					<span class="tutor-color-black-60 tutor-fs-7">
						<?php _e( 'Course', 'tutor-pro' ); ?>
					</span>
				</th>
				<th>
					<div class="tutor-color-black-60 tutor-fs-7">
						<?php _e( 'Total Learners', 'tutor-pro' ); ?>
					</div>
				</th>
				<th>
					<div class="tutor-color-black-60 tutor-fs-7">
						<?php _e( 'Earnings', 'tutor-pro' ); ?>
					</div>
				</th>
				<th>

				</th>
			</thead>
			<tbody>
				<?php if ( count( $courses ) ) : ?>
					<?php foreach ( $courses as $course ) : ?>
						<?php
						$course->lesson     = tutor_utils()->get_lesson_count_by_course( $course->ID );
						$course->quiz       = Analytics::get_all_quiz_by_course( $course->ID );
						$course->assignment = tutor_utils()->get_assignments_by_course( $course->ID )->count;
						?>

						<tr>
							<td data-th="<?php _e( 'Course', 'tutor-pro' ); ?>">
								<div class="tutor-color-black td-course tutor-fs-6 tutor-fw-medium">
									<span>
										<?php esc_html_e( $course->post_title ); ?>
									</span>
									<div class="course-meta">
										<span class="tutor-color-black-60 tutor-fs-6">
											<span><?php _e( 'Lesson', 'tutor-pro' ); ?>:</span>
											<span class="tutor-fw-normal">
												<?php esc_html_e( $course->lesson ); ?>
											</span>
										</span>
										<span class="tutor-color-black-60 tutor-fs-6">
											<span><?php _e( 'Assignment', 'tutor-pro' ); ?>:</span>
											<span class="tutor-fw-normal">
												<?php esc_html_e( $course->assignment ); ?>
											</span>
										</span>
										<span class="tutor-color-black-60 tutor-fs-6">
											<span><?php _e( 'Quiz', 'tutor-pro' ); ?>:</span>
											<span class="tutor-fw-normal">
												<?php esc_html_e( $course->quiz ); ?>
											</span>
										</span>
									</div>
								</div>
							</td>
							<td data-th="<?php _e( 'Total Learners', 'tutor-pro' ); ?>">
								<span class="tutor-color-black tutor-fs-7 tutor-fw-medium">
									<?php esc_html_e( $course->learner ); ?>
								</span>
							</td>
							<td data-th="<?php _e( 'Earnings', 'tutor-pro' ); ?>">
								<span class="tutor-color-black tutor-fs-7 tutor-fw-medium">
									<?php
										$earnings = Analytics::get_earnings_by_user( $user->ID, '', '', '', $course->ID );
										echo wp_kses_post( tutor_utils()->tutor_price( $earnings['total_earnings'] ) );
									?>
								</span>
							</td>
							<td data-th="<?php _e( '-', 'tutor-pro' ); ?>">
								<div class="td-action-btns tutor-flex-end">
									<a href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url() . 'analytics/course-details?course_id=' . $course->ID ); ?>" class="tutor-btn tutor-btn-disable-outline tutor-btn-outline-fd tutor-btn-sm">Details</a>
									<a href="<?php echo get_permalink( $course->ID ); ?>" class="tutor-btn-ghost tutor-btn-ghost-fd tutor-color-muted" target="_blank"><span class="tutor-icon-detail-link-filled tutor-icon-24"></span></a>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>

				<?php else : ?>
					<tr>
						<td colspan="100%" class="tutor-empty-state">
							<?php tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() ); ?>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

	<div class="tutor-mt-20">
		<?php
		if($total_course > $per_page) {
			$pagination_data = array(
				'total_items' => $total_course,
				'per_page'    => $per_page,
				'paged'       => $paged,
			);
			tutor_load_template_from_custom_path(
			tutor()->path . 'templates/dashboard/elements/pagination.php',
				$pagination_data
			);
		}
		?>
	</div>
</div>
