<?php
/**
 * Tutor Report Overview page
 *
 * @package Tutor Report
 */

use \TUTOR_REPORT\Analytics;
global $wpdb;

$course_post_type = tutor()->course_post_type;
$lesson_type      = tutor()->lesson_post_type;

$totalCourse = $wpdb->get_var(
	"SELECT COUNT(ID)
    FROM {$wpdb->posts}
    WHERE post_type ='{$course_post_type}'
    AND post_status = 'publish' "
);

$totalCourseEnrolled = $wpdb->get_var(
	"SELECT COUNT(ID)
    FROM {$wpdb->posts}
    WHERE post_type ='tutor_enrolled'
    AND post_status = 'completed' "
);

$totalLesson = $wpdb->get_var(
	"SELECT COUNT(lesson.ID)
    FROM {$wpdb->posts} lesson
    INNER JOIN {$wpdb->posts} topic ON lesson.post_parent=topic.ID
    INNER JOIN {$wpdb->posts} course ON topic.post_parent=course.ID
    WHERE lesson.post_type ='{$lesson_type}'
    AND lesson.post_status = 'publish'
    AND course.post_status = 'publish'
    AND topic.post_status = 'publish'"
);

$totalQuiz = $wpdb->get_var(
	"SELECT COUNT(ID)
    FROM {$wpdb->posts}
    WHERE post_type ='tutor_quiz'
    AND post_status = 'publish' "
);

$totalQuestion = $wpdb->get_var(
	"SELECT COUNT(question_id)
    FROM {$wpdb->tutor_quiz_questions} "
);

$totalInstructor = $wpdb->get_var(
	"SELECT COUNT(umeta_id)
    FROM {$wpdb->usermeta}
    WHERE meta_key ='_is_tutor_instructor' "
);

$totalStudents = $wpdb->get_var(
	"SELECT COUNT(umeta_id)
    FROM {$wpdb->usermeta}
    WHERE meta_key ='_is_tutor_student' "
);

$totalReviews = $wpdb->get_var(
	"SELECT COUNT(comment_ID)
    FROM {$wpdb->comments}
    WHERE comment_type ='tutor_course_rating'
    AND comment_approved = 'approved' "
);

/* $most_popular_courses = $wpdb->get_results(
	"SELECT COUNT(enrolled.ID) as total_enrolled, enrolled.post_parent as course_id, course.*
    FROM {$wpdb->posts} enrolled
    INNER JOIN {$wpdb->posts} course ON enrolled.post_parent = course.ID
    WHERE enrolled.post_type = 'tutor_enrolled' AND enrolled.post_status = 'completed' AND course.post_type = 'courses'
    GROUP BY course_id
    ORDER BY total_enrolled DESC LIMIT 0,5 ;"
); */
$most_popular_courses = tutor_utils()->most_popular_courses( $limit = 5 );

$last_enrolled_courses = $wpdb->get_results(
	"SELECT MAX(enrolled.post_date) as enrolled_time, enrolled.guid, enrolled.post_parent, course.ID, course.post_title
    FROM {$wpdb->posts} enrolled
    LEFT JOIN {$wpdb->posts} course ON enrolled.post_parent = course.ID
    WHERE enrolled.post_type = 'tutor_enrolled' AND enrolled.post_status = 'completed' AND course.post_type = 'courses'
    GROUP BY enrolled.post_parent
    ORDER BY enrolled_time DESC LIMIT 0,5 ;"
);

$reviews = $wpdb->get_results(
	"select {$wpdb->comments}.comment_ID,
    {$wpdb->comments}.comment_post_ID,
    {$wpdb->comments}.comment_author,
    {$wpdb->comments}.comment_author_email,
    {$wpdb->comments}.comment_date,
    {$wpdb->comments}.comment_content,
    {$wpdb->comments}.user_id,
    {$wpdb->commentmeta}.meta_value as rating,
    {$wpdb->users}.display_name
    FROM {$wpdb->comments}
    INNER JOIN {$wpdb->commentmeta}
    ON {$wpdb->comments}.comment_ID = {$wpdb->commentmeta}.comment_id
    INNER  JOIN {$wpdb->users}
    ON {$wpdb->comments}.user_id = {$wpdb->users}.ID
    AND meta_key = 'tutor_rating' ORDER BY comment_ID DESC LIMIT 0,5 ;"
);


$students = $wpdb->get_results(
	"SELECT SQL_CALC_FOUND_ROWS {$wpdb->users}.* ,
    {$wpdb->usermeta}.meta_value as registered_timestamp
    FROM {$wpdb->users}
    INNER JOIN {$wpdb->usermeta}
    ON ( {$wpdb->users}.ID = {$wpdb->usermeta}.user_id )
    WHERE 1=1 AND ( {$wpdb->usermeta}.meta_key = '_is_tutor_student' )
    ORDER BY {$wpdb->usermeta}.meta_value DESC
    LIMIT 0,5 "
);

$teachers = $wpdb->get_results(
	"SELECT SQL_CALC_FOUND_ROWS {$wpdb->users}.* , meta_role.meta_value as registered_timestamp
    FROM {$wpdb->users}
    INNER JOIN {$wpdb->usermeta} meta_role ON ( {$wpdb->users}.ID = meta_role.user_id )
    INNER JOIN {$wpdb->usermeta} meta_status ON ( {$wpdb->users}.ID = meta_status.user_id )
    WHERE meta_role.meta_key = '_is_tutor_instructor'
		AND meta_status.meta_key='_tutor_instructor_status'
		AND meta_status.meta_value='approved'
    ORDER BY meta_role.meta_value DESC
    LIMIT 0,5 "
);

$questions = tutor_utils()->get_qa_questions();

$time_period = $active = isset( $_GET['period'] ) ? $_GET['period'] : '';
$start_date  = isset( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
$end_date    = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';
if ( '' !== $start_date ) {
	$start_date = tutor_get_formated_date( 'Y-m-d', $start_date );
}
if ( '' !== $end_date ) {
	$end_date = tutor_get_formated_date( 'Y-m-d', $end_date );
}
$add_30_days  = tutor_utils()->sub_days_with_today( '30 days' );
$add_90_days  = tutor_utils()->sub_days_with_today( '90 days' );
$add_365_days = tutor_utils()->sub_days_with_today( '365 days' );

$current_frequency = isset( $_GET['period'] ) ? $_GET['period'] : 'last30days';
$frequencies       = tutor_utils()->report_frequencies();

?>

<div class="tutor-report-overview-wrap tutor-pr-20">
	<div class="report-stats">
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-college-graduation-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalCourse; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Enrolled Courses', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-add-member-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalCourseEnrolled; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Course Enrolled', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-book-open-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalLesson; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Lessons', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-quiz-1-filled"></span>
				</div>
				<div class="box-stats-text">
					<h3<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalQuiz; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Quiz', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-question-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalQuestion; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Questions', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-man-user-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalInstructor; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Instructors', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-user-graduate-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalStudents; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Students', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<div class="icon">
						<span class="tutor-icon-star-full-filled tutor-color-brand-wordpress"></span>
					</div>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold  tutor-color-black"><?php echo $totalReviews; ?></h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Reviews', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
	</div>

	<div class="tutor-analytics-wrapper tutor-analytics-graph tutor-mt-12">

		<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black tutor-d-flex tutor-align-items-center tutor-justify-content-between">
			<div>
				<?php esc_html_e( 'Earning graph', 'tutor-pro' ); ?>
			</div>
			<div class="tutor-admin-report-frequency-wrapper" style="min-width: 260px;">
				<?php tutor_load_template_from_custom_path( TUTOR_REPORT()->path . 'templates/elements/frequency.php' ); ?>
				<div class="tutor-v2-date-range-picker inactive" style="width: 305px; position:absolute; z-index: 99;"></div>
			</div>
		</div>
		<div class="tutor-overview-month-graph">
			<!--analytics graph -->
			<?php
				/**
				 * Get analytics data
				 * sending user_id 0 for getting all data
				 *
				 * @since 1.9.9
				 */
				$user_id        = get_current_user_id();
				$earnings       = Analytics::get_earnings_by_user( 0, $time_period, $start_date, $end_date );
				$enrollments    = Analytics::get_total_students_by_user( 0, $time_period, $start_date, $end_date );
				$discounts      = Analytics::get_discounts_by_user( 0, $time_period, $start_date, $end_date );
				$refunds        = Analytics::get_refunds_by_user( 0, $time_period, $start_date, $end_date );
				$content_title  = __( 'for ', 'tutor-pro' ) . $frequencies[ $current_frequency ];
				$graph_tabs     = array(
					array(
						'tab_title'     => __( 'Total Earning', 'tutor-pro' ),
						'tab_value'     => $earnings['total_earnings'],
						'data_attr'     => 'ta_total_earnings',
						'active'        => 'active',
						'price'         => true,
						'content_title' => __( 'Earnings Chart ' . $content_title, 'tutor-pro' ),
					),
					array(
						'tab_title'     => __( 'Course Enrolled', 'tutor-pro' ),
						'tab_value'     => $enrollments['total_enrollments'],
						'data_attr'     => 'ta_total_course_enrolled',
						'active'        => '',
						'price'         => false,
						'content_title' => __( 'Course Enrolled Chart ' . $content_title, 'tutor-pro' ),
					),
					array(
						'tab_title'     => __( 'Total Refund', 'tutor-pro' ),
						'tab_value'     => $refunds['total_refunds'],
						'data_attr'     => 'ta_total_refund',
						'active'        => '',
						'price'         => true,
						'content_title' => __( 'Refund Chart ' . $content_title, 'tutor-pro' ),
					),
					array(
						'tab_title'     => __( 'Total Discount', 'tutor-pro' ),
						'tab_value'     => $discounts['total_discounts'],
						'data_attr'     => 'ta_total_discount',
						'active'        => '',
						'price'         => true,
						'content_title' => __( 'Discount Chart ' . $content_title, 'tutor-pro' ),
					),
				);
				$graph_template = TUTOR_REPORT()->path . 'templates/elements/graph.php';
				tutor_load_template_from_custom_path( $graph_template, $graph_tabs );
				?>
			<!--analytics graph end -->
		</div>
	</div>

	<div class="tutor-mb-48" id="tutor-courses-overview-section">
		<div class="single-overview-section tutor-most-popular-courses">
			<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
				<?php esc_html_e( 'Most popular courses', 'tutor-pro' ); ?>
			</div>
			<div class="tutor-ui-table-wrapper">
				<table class="tutor-ui-table tutor-ui-table-responsive table-popular-courses">
					<thead>
						<tr>
							<th>
								<span class="tutor-fs-7 tutor-color-black-60">
									<?php esc_html_e( 'Course Name', 'tutor-pro' ); ?>
								</span>
							</th>
							<th class="tutor-table-rows-sorting">
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Total Enrolled', 'tutor-pro' ); ?>
									</span>
									<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
								</div>
							</th>
							<th class="tutor-table-rows-sorting">
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Rating', 'tutor-pro' ); ?>
									</span>
									<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
								</div>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php if ( is_array( $most_popular_courses ) && count( $most_popular_courses ) ) : ?>
						<?php foreach ( $most_popular_courses as $course ) : ?>
							<tr>
								<td data-th="<?php esc_html_e( 'Course Name', 'tutor-pro' ); ?>" class="course-name column-fullwidth">
									<div class="tutor-fs-6 tutor-fw-medium  tutor-color-black">
										<span>
											<?php echo esc_html( $course->post_title ); ?>
										</span>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Total Enrolled', 'tutor-pro' ); ?>" class="total-enrolled">
									<span class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
										<?php echo esc_html( $course->total_enrolled ); ?>
									</span>
								</td>
								<td data-th="<?php esc_html_e( 'Course Rating', 'tutor-pro' ); ?>" class="course-rating">
									<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
										<?php tutor_utils()->star_rating_generator_v2( isset($course_rating->rating_avg)?$course_rating->rating_avg:0, null, true ); ?>
									</div>
								</td>
								<td data-th="-">
									<div class="tutor-details-link">
										<a href="<?php echo esc_url( get_permalink( $course->course_id ) ); ?>" class="tutor-icon-detail-link-filled tutor-color-text-hint" target="_blank"></a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="100%" class="column-empty-state" >
								<?php tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() ); ?>
							</td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="single-overview-section tutor-last-enrolled-courses">
			<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
				<?php esc_attr_e( 'Last enrolled courses', 'tutor-pro' ); ?>
			</div>
			<div class="tutor-ui-table-wrapper">
				<table class="tutor-ui-table tutor-ui-table-responsive table-popular-courses">
					<thead>
						<tr>
							<th>
								<span class="tutor-fs-7 tutor-color-black-60">
									<?php esc_html_e( 'Course Name', 'tutor-pro' ); ?>
								</span>
							</th>
							<th class="tutor-table-rows-sorting">
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Date', 'tutor-pro' ); ?>
									</span>
									<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
								</div>
							</th>
							<th class="tutor-table-rows-sorting">
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Rating', 'tutor-pro' ); ?>
									</span>
									<a class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></a>
								</div>
							</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php if ( is_array( $last_enrolled_courses ) && count( $last_enrolled_courses ) ) : ?>
						<?php foreach ( $last_enrolled_courses as $course ) : ?>
							<tr>
								<td data-th="<?php esc_html_e( 'Course Name', 'tutor-pro' ); ?>" class="column-fullwidth course-name">
									<div class="tutor-fs-6 tutor-fw-medium  tutor-color-black">
										<span>
											<?php echo esc_html( $course->post_title ); ?>
										</span>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Date', 'tutor-pro' ); ?>" class="date">
									<span class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
										<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $course->enrolled_time ) ); ?>,<br>
										<?php echo esc_html( tutor_get_formated_date( get_option( 'time_format' ), $course->enrolled_time ) ); ?>
									</span>
								</td>
								<td data-th="<?php esc_html_e( 'Rating', 'tutor-pro' ); ?>" class="rating">
									<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
										<?php tutor_utils()->star_rating_generator_v2( isset($course_rating->rating_avg)?$course_rating->rating_avg:0, null, true ); ?>
									</div>
								</td>
								<td>
									<div class="tutor-details-link">
										<a href="<?php echo esc_url( get_permalink( $course->ID ) ); ?>" target="_blank" class="tutor-icon-detail-link-filled tutor-color-text-hint"></a>
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
		</div>
	</div>

	<div id="tutor-courses-review-section">
		<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
			<?php esc_html_e( 'Recent Reviews', 'tutor-pro' ); ?>
		</div>
		<div class="tutor-ui-table-wrapper">
			<table class="tutor-ui-table tutor-ui-table-responsive tutor-ui-table-report-tab-overview" id="tutor-admin-reviews-table">
				<thead>
					<tr>
						<th class="tutor-table-rows-sorting">
							<div class=" tutor-align-items-center tutor-d-flex">
								<span class="tutor-fs-7 tutor-color-black-60">
									<?php esc_html_e( 'Student', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
							</div>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Date', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Course', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Feedback', 'tutor-pro' ); ?>
							</span>
						</th>
						<th>
							<span></span>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php if ( is_array( $reviews ) && count( $reviews ) ) : ?>
						<?php foreach ( $reviews as $review ) : ?>
							<tr>
								<td data-th="<?php esc_html_e( 'Student', 'tutor-pro' ); ?>" class="student column-fullwidth">
									<div class="td-avatar">
										<?php
											echo wp_kses_post( get_avatar( $review->comment_author_email, 50 ) );
										?>
										<span class="tutor-fs-6 tutor-fw-medium  tutor-color-black">
											<?php echo esc_html( $review->display_name ); ?>
										</span>
										<a href="<?php echo esc_url( tutor_utils()->profile_url( $review->user_id, false ) ); ?>" class="tutor-d-flex"><span class="tutor-icon-detail-link-filled  tutor-color-black"></span></a>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Date', 'tutor-pro' ); ?>" class="date">
									<span class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
										<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $review->comment_date ) ); ?>,
										<br />
										<?php echo esc_html( tutor_get_formated_date( get_option( 'time_format' ), $review->comment_date ) ); ?>
									</span>
								</td>
								<td data-th="<?php esc_html_e( 'Course', 'tutor-pro' ); ?>" class="course">
									<span class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
										<?php echo esc_html( get_the_title( $review->comment_post_ID ) ); ?>
									</span>
								</td>
								<td data-th="<?php esc_html_e( 'Feedback', 'tutor-pro' ); ?>" class="feedback">
									<div class="td-feedback">
										<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
											<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
												<?php tutor_utils()->star_rating_generator_v2( $review->rating, null, true ); ?>
											</div>
										</div>
										<sapn class="review-text tutor-color-black-60"><?php echo $review->comment_content; ?></sapn>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Action', 'tutor-pro' ); ?>" class="review-action-btns" style="vertical-align:middle;">
									<div class="inline-flex-center td-action-btns">
										<a data-tutor-modal-target="tutor-common-confirmation-modal" class="btn-outline tutor-btn tutor-danger tutor-delete-recent-reviews" data-id="<?php echo esc_attr( $review->comment_ID ); ?>" style="cursor: pointer;">Delete</a>
										<a href="<?php echo esc_url( get_the_permalink( $review->comment_post_ID ) ); ?>" class="btn-text" target="_blank"  style="vertical-align:middle;">
											<span class="tutor-icon-detail-link-filled"></span>
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="100%" class="column-empty-state">
								<?php tutor_utils()->tutor_empty_state(); ?>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<div id="tutor-new-registered-section">
		<div class="single-new-registered-section">
			<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
				<?php esc_html_e( 'New Registered students', 'tutor-pro' ); ?>
			</div>
			<div class="tutor-ui-table-wrapper">
				<table class="tutor-ui-table tutor-ui-table-responsive">
					<thead>
						<tr>
							<th class="tutor-table-rows-sorting">
								<div class="tutor-d-flex  tutor-align-items-center">
									<span class="tutor-fs-7 tutor-color-black-60">
										<?php esc_html_e( 'Student', 'tutor-pro' ); ?>
									</span>
									<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
								</div>
							</th>
							<th>
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Email', 'tutor-pro' ); ?>
									</span>
								</div>
							</th>
							<th>
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Register at', 'tutor-pro' ); ?>
									</span>
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php if ( is_array( $students ) && count( $students ) ) : ?>
							<?php foreach ( $students as $student ) : ?>
								<tr>
									<td data-th="<?php esc_html_e( 'Student', 'tutor-pro' ); ?>">
										<div class="tutor-instructor-card">
											<div class="tutor-avatar tutor-is-xs">
												<?php echo wp_kses_post( get_avatar( $student->user_email, 50 ) ); ?>
											</div>
											<div class="tutor-icard-content">
												<h6 class="tutor-fs-7 tutor-fw-medium  tutor-color-black tutor-d-flex  tutor-align-items-center">
													<?php echo esc_html( $student->display_name ); ?>
												</h6>
												<a href="<?php echo esc_url( tutor_utils()->profile_url( $student->ID, true ) ); ?>" class="tutor-icon-detail-link-filled tutor-color-text-hint" target="_blank"></a>
											</div>
										</div>
									</td>
									<td data-th="<?php esc_html_e( 'Email', 'tutor-pro' ); ?>">
										<span class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
											<?php echo esc_html( $student->user_email ); ?>
										</span>
									</td>
									<td data-th="<?php esc_html_e( 'Registered at', 'tutor-pro' ); ?>">
										<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
											<div class="tutor-ratings">
												<div class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
													<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $student->user_registered ) ); ?>
												</div>
											</div>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="100%" class="column-empty-state">
									<?php tutor_utils()->tutor_empty_state(); ?>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="single-new-registered-section">
			<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
				<div class="heading">
					<?php esc_html_e( 'New Registered Teachers', 'tutor-pro' ); ?>
				</div>
			</div>
			<div class="tutor-ui-table-wrapper">
				<table class="tutor-ui-table tutor-ui-table-responsive">
					<thead>
						<tr>
							<th class="tutor-table-rows-sorting">
								<div class="tutor-d-flex  tutor-align-items-center">
									<span class="tutor-fs-7 tutor-color-black-60">
										<?php esc_html_e( 'Teacher', 'tutor-pro' ); ?>
									</span>
									<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
								</div>
							</th>
							<th>
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Email', 'tutor-pro' ); ?>
									</span>
								</div>
							</th>
							<th>
								<div class="inline-flex-center tutor-color-black-60">
									<span class="tutor-fs-7">
										<?php esc_html_e( 'Register at', 'tutor-pro' ); ?>
									</span>
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php if ( is_array( $teachers ) && count( $teachers ) ) : ?>
							<?php foreach ( $teachers as $teacher ) : ?>
								<tr>
									<td data-th="<?php esc_html_e( 'Teacher', 'tutor-pro' ); ?>">
										<div class="tutor-instructor-card">
											<div class="tutor-avatar tutor-is-xs">
											<?php echo get_avatar( $teacher->user_email ); ?>
											</div>
											<div class="tutor-icard-content">
												<h6 class="tutor-name">
												<?php echo esc_html( $teacher->display_name ); ?>
												</h6>
												<a href="<?php echo esc_url( tutor_utils()->profile_url( $teacher->ID, true ) ); ?>" class="tutor-icon-detail-link-filled tutor-color-text-hint" target="_blank"></a>
											</div>
										</div>
									</td>
									<td data-th="<?php esc_html_e( 'Email', 'tutor-pro' ); ?>">
										<span class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
										<?php echo esc_html( $teacher->user_email ); ?>
										</span>
									</td>
									<td data-th="<?php esc_html_e( 'Registered at', 'tutor-pro' ); ?>">
										<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
											<div class="tutor-ratings">
												<div class="tutor-fs-7 tutor-fw-medium  tutor-color-black">
													<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ) . ', ' . get_option( 'time_format' ), $teacher->user_registered ) ); ?>
												</div>
											</div>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="100%" class="column-empty-state">
										<?php tutor_utils()->tutor_empty_state(); ?>
									</td>
								</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php tutor_load_template_from_custom_path( tutor()->path . 'views/elements/common-confirm-popup.php' ); ?>
