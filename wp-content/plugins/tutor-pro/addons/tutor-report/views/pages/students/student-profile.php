<?php
/**
 * Student details template
 *
 * @package Report
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TUTOR_REPORT\Analytics;

$student_id = isset( $_GET['student_id'] ) ? $_GET['student_id'] : '';
if ( '' === $student_id ) {
	die( esc_html_e( 'Invalid student id', 'tutor-pro' ) );
}

$user_info       = get_userdata( $student_id );
$enrolled_course = tutor_utils()->get_enrolled_courses_by_user( $user_info->ID );

// Review List.
$count         = 0;
$item_per_page = tutor_utils()->get_option( 'pagination_per_page' );
$review_page   = isset( $_GET['rp'] ) ? $_GET['rp'] : 0;
$offset        = max( 0, ( $review_page - 1 ) * $item_per_page );
$reviews       = tutor_utils()->get_reviews_by_user( $user_info->ID );

?>

<div id="tutor-report-student-details" class="tutor-report-common tutor-pr-20">
	<div class="tutor-report-student-details-header tutor-d-sm-flex tutor-align-items-center tutor-mb-20">
		<div class="tutor-details-student-profile-pic tutor-mr-32">
			<?php echo get_avatar( $user_info->ID, 50 ); ?>
		</div>
		<div class="tutor-details-student-profile-details tutor-d-flex flex-column">
			<div class="tutor-details-student-profile-name tutor-fs-4 tutor-fw-medium tutor-color-black">
				<?php echo esc_html( $user_info->display_name ); ?>
			</div>
			<div class="tutor-details-student-profile-desc-area tutor-d-flex tutor-justify-content-between">
				<div class="tutor-details-student-profile-desc tutor-d-flex  tutor-align-items-end">
					<div class="tutor-details-student-email tutor-fs-7 tutor-color-black-60">
						<span><?php esc_html_e( 'Email: ', 'tutor-pro' ); ?></span>
						<?php echo esc_html( $user_info->user_email ); ?>
					</div>
					<div class="tutor-details-student-username tutor-fs-7 tutor-color-black-60">
						<span><?php echo esc_html_e( 'User Name: ', 'tutor-pro' ); ?></span>
						<?php echo esc_html( $user_info->user_login ); ?>
					</div>
					<div class="tutor-details-student-username tutor-fs-7 tutor-color-black-60">
						<span><?php esc_html_e( 'User ID: ', 'tutor-pro' ); ?></span>
						<?php echo esc_html( $user_info->ID ); ?>
					</div>
					<div class="tutor-details-student-username tutor-fs-7 tutor-color-black-60">
						<span><?php esc_html_e( 'Registered at: ', 'tutor-pro' ); ?></span>
						<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $user_info->user_registered ) ); ?>
					</div>
				</div>
				<div class="tutor-report-student-profile-btn tutor-report-student-details-header-right tutor-d-flex  tutor-align-items-center tutor-justify-content-end">
					<a href="<?php echo esc_url( tutor_utils()->profile_url( $user_info->ID, false ) ); ?>" class="tutor-btn tutor-btn-wordpress tutor-btn-sm" target="_blank">
						<?php esc_html_e( 'View Profile', 'tutor-pro' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div id="student-report-stats" class="report-stats tutor-mb-48">
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-college-graduation-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
						<?php echo esc_html( $enrolled_course->found_posts ); ?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php _e( 'Enrolled Courses', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-award-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
					<?php
						$completed_course = tutor_utils()->get_completed_courses_ids_by_user( $user_info->ID );
						echo esc_html( count( $completed_course ) );
					?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php esc_html_e( 'Completed Courses', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-in-progress-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
						<?php echo esc_html( ( $enrolled_course->found_posts - count( $completed_course ) ) ); ?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php esc_html_e( 'In Progress Course', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-star-full-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
						<?php
							$review_items = count( tutor_utils()->get_reviews_by_user( $user_info->ID ) );
							echo esc_html( $review_items );
						?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php esc_html_e( 'Reviews Placed', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-document-alt-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
						<?php
							$lesson     = 0;
							$courses_id = tutor_utils()->get_enrolled_courses_ids_by_user( $user_info->ID );
						foreach ( $courses_id as $course ) {
							$lesson += tutor_utils()->get_lesson_count_by_course( $course );
						}
							echo esc_html( $lesson );
						?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php esc_html_e( 'Total Lesson', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-quiz-1-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
						<?php echo esc_html( tutor_utils()->get_total_quiz_attempts( $user_info->user_email ) ); ?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60">
						<?php esc_html_e( 'Take Quiz', 'tutor-pro' ); ?>
					</p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-assignment-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
					<?php
						global $wpdb;
						$total_assignments = 0;
						$assignment_ids    = array();
					if ( ! empty( $courses_id ) ) {
						$assignment_ids    = tutor_utils()->get_course_content_ids_by( 'tutor_assignments', tutor()->course_post_type, $courses_id );
						$total_assignments = count( $assignment_ids );
					}
						echo esc_html( $total_assignments );
					?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php esc_html_e( 'Assignments', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
		<div class="report-stat-box">
			<div class="report-stat-box-body">
				<div class="box-icon">
					<span class="tutor-color-brand-wordpress tutor-icon-question-filled"></span>
				</div>
				<div class="box-stats-text">
					<h4 class="tutor-fs-4 tutor-fw-bold tutor-color-black">
					<?php
						global $wpdb;
						$total_discussion = $wpdb->get_var(
							$wpdb->prepare(
								"SELECT COUNT(comment_ID) FROM {$wpdb->comments}
                            WHERE comment_author = %s AND comment_type = 'tutor_q_and_a'",
								$user_info->user_login
							)
						);
						echo esc_html( $total_discussion );
						?>
					</h4>
					<p class="tutor-fs-7 tutor-color-black-60"><?php esc_html_e( 'Questions', 'tutor-pro' ); ?></p>
				</div>
			</div>
		</div>
	</div>

	<div id="tutor-course-details-list" class="tutor-mb-48">
		<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
			<?php esc_html_e( 'Course', 'tutor-pro' ); ?>
		</div>
		<div class="tutor-course-details-student-list-table">
			<table class="tutor-ui-table tutor-ui-table-responsive tutor-ui-table-data-td-target">
				<thead>
					<tr>
						<th class="tutor-table-rows-sorting">
							<div class="inline-flex-center tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php esc_html_e( 'Course', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
							</div>
						</th>
						<th class="tutor-table-rows-sorting">
							<div class="inline-flex-center tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php esc_html_e( 'Enroll Date', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-order-down-filled up-down-icon"></span>
							</div>
						</th>
						<th class="tutor-table-rows-sorting">
							<div class="inline-flex-center tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php esc_html_e( 'Lesson', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-order-down-filled up-down-icon"></span>
							</div>
						</th>
						<th class="tutor-table-rows-sorting">
							<div class="inline-flex-center tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php esc_html_e( 'Quiz', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-order-down-filled up-down-icon"></span>
							</div>
						</th>
						<th class="tutor-table-rows-sorting">
							<div class="inline-flex-center tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php esc_html_e( 'Assignment', 'tutor-pro' ); ?>
								</span>
								<span class="tutor-icon-order-down-filled up-down-icon"></span>
							</div>
						</th>
						<th class="v-align-middle">
							<div class="inline-flex-center tutor-color-black-60 tutor-fs-7">
								<?php esc_html_e( 'Progress', 'tutor-pro' ); ?>
							</div>
						</th>
						<th class="tutor-shrink"></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( is_array( $enrolled_course->posts ) && count( $enrolled_course->posts ) ) : ?>
						<?php
						foreach ( $enrolled_course->posts as $course ) :
							$lessons     = tutor_utils()->get_course_content_list( tutor()->lesson_post_type, tutor()->course_post_type, $course->ID );
							$assignments = tutor_utils()->get_course_content_list( 'tutor_assignments', tutor()->course_post_type, $course->ID );
							$quizzes     = tutor_utils()->get_course_content_list( 'tutor_quiz', tutor()->course_post_type, $course->ID );

							$course_progress      = tutor_utils()->get_course_completed_percent( $course->ID, $student_id );
							$total_lessons        = is_array( $lessons ) ? count( $lessons ) : 0;
							$completed_lessons    = tutor_utils()->get_completed_lesson_count_by_course( $course->ID, $student_id );
							$total_assignments    = is_array( $assignments ) ? count( $assignments ) : 0;
							$completed_assignment = tutor_utils()->get_completed_assignment( $course->ID, $student_id );
							$total_quiz           = is_array( $quizzes ) ? count( $quizzes ) : 0;
							$completed_quiz       = tutor_utils()->get_completed_quiz( $course->ID, $student_id );

							?>

						<tr>
							<td data-th="<?php esc_html_e( 'Course', 'tutor-pro' ); ?>" class="course">
								<div class="tutor-fs-7 tutor-fw-medium tutor-color-black">
									<?php echo esc_html( $course->post_title ); ?>
								</div>
							</td>
							<td data-th="<?php esc_html_e( 'Enroll Date', 'tutor-pro' ); ?>" class="enroll-date">
								<div class="tutor-color-black tutor-fs-7">
								   <?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $course->post_date ) ); ?>
								</div>
							</td>
							<td data-th="<?php esc_html_e( 'Lesson', 'tutor-pro' ); ?>" class="lesson">
								<span class="tutor-color-black tutor-fs-7"><?php echo esc_html( $completed_lessons ); ?></span><span class="color-black-fill-40 tutor-fs-7"><?php echo esc_html( '/' . $total_lessons ); ?></span>
							</td>
							<td data-th="<?php esc_html_e( 'Quiz', 'tutor-pro' ); ?>" class="quiz">
								<span class="tutor-color-black tutor-fs-7"><?php echo esc_html( $completed_quiz ); ?></span><span class="color-black-fill-40 tutor-fs-7"><?php echo esc_html( '/' . $total_quiz ); ?></span>
							</td>
							<td data-th="<?php esc_html_e( 'Assignment', 'tutor-pro' ); ?>" class="assignment">
								<span class="tutor-color-black tutor-fs-7"><?php echo esc_html( $completed_assignment ); ?></span><span class="color-black-fill-40 tutor-fs-7"><?php echo esc_html( '/' . $total_assignments ); ?></span>
							</td>
							<td data-th="<?php esc_html_e( 'Progress', 'tutor-pro' ); ?>" class="progress">
								<div class="td-progress">
									<div class="progress-bar" style="--progress-value:<?php echo esc_attr( $course_progress ); ?>%;">
										<div class="progress-value"></div>
									</div>
									<div class="progress-text tutor-fs-7 tutor-fw-medium tutor-color-black">
										<?php echo esc_attr( $course_progress ); ?>%
									</div>
								</div>
							</td>
							<td class="expand-btn" data-th="Collapse" data-td-target="<?php echo esc_attr( "tutor-student-course-$course->ID" ); ?>">
								<div class="tutor-icon-angle-down-filled tutor-color-brand-wordpress has-data-td-target"></div>
							</td>
						</tr>
						<tr>
							<td colspan="100%" class="column-empty-state data-td-content" id="<?php echo esc_attr( "tutor-student-course-$course->ID" ); ?>">
								<div class="td-toggle-content tutor-container-fluid">
									<div class="td-list-item-wrapper tutor-row">
										<div class="tutor-col-md-4">
											<div class="list-item-title tutor-fs-6 tutor-color-black tutor-py-12">
												<?php esc_html_e( 'Lesson', 'tutor-pro' ); ?>
											</div>
											<?php if ( is_array( $lessons ) && count( $lessons ) ) : ?>
												<?php foreach ( $lessons as $lesson ) : ?>
												<div class="list-item-checklist">
													<div class="tutor-form-check">
														<?php
															$is_lesson_completed = tutor_utils()->is_completed_lesson( $lesson->ID, $user_info->ID );
														?>
														<input name="item-checklist-11" type="checkbox" class="tutor-form-check-input tutor-form-check-circle" readonly <?php echo esc_attr( $is_lesson_completed ? 'checked' : '' ); ?> onclick="return false;"/>
														<span class="tutor-fs-7 tutor-color-black-60">
															<?php echo esc_html( $lesson->post_title ); ?>
														</span>
													</div>
												</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
										<div class="tutor-col-md-4">
											<div class="list-item-title tutor-fs-6 tutor-color-black tutor-py-12">
												<?php esc_html_e( 'Quiz', 'tutor-pro' ); ?>
											</div>
											<?php if ( is_array( $quizzes ) && count( $quizzes ) ) : ?>
												<?php foreach ( $quizzes as $quiz ) : ?>
												<div class="list-item-checklist">
													<div class="tutor-form-check">
														<?php
															$has_attempted = tutor_utils()->has_attempted_quiz( $user_info->ID, $quiz->ID );
														?>
														<input name="item-checklist-11" type="checkbox" class="tutor-form-check-input tutor-form-check-circle" readonly <?php echo esc_attr( $has_attempted ? 'checked' : '' ); ?> onclick="return false;"/>
														<span class="tutor-fs-7 tutor-color-black-60">
															<?php echo esc_html( $quiz->post_title ); ?>
														</span>
													</div>
												</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
										<div class="tutor-col-md-4">
											<div class="list-item-title tutor-fs-6 tutor-color-black tutor-py-12">
												<?php esc_html_e( 'Assignment', 'tutor-pro' ); ?>
											</div>
											<?php if ( is_array( $assignments ) && count( $assignments ) ) : ?>
												<?php foreach ( $assignments as $assignment ) : ?>
												<div class="list-item-checklist">
													<div class="tutor-form-check">
														<?php
															$is_submitted = tutor_utils()->is_assignment_submitted( $assignment->ID, $user_info->ID );
														?>
														<input name="item-checklist-11" type="checkbox" class="tutor-form-check-input tutor-form-check-circle" readonly <?php echo esc_attr( $is_submitted ? 'checked' : '' ); ?> onclick="return false;"/>
														<span class="tutor-fs-7 tutor-color-black-60">
															<?php echo esc_html( $assignment->post_title ); ?>
														</span>
													</div>
												</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
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

	<div id="tutor-report-reviews">
		<div class="tutor-list-header tutor-fs-5 tutor-fw-medium tutor-color-black">
			<?php esc_html_e( 'Reviews', 'tutor-pro' ); ?>
		</div>
		<div class="tutor-ui-table-wrapper">
			<table class="tutor-ui-table tutor-ui-table-responsive">
				<thead>
					<tr>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Date', 'tutor-pro' ); ?>
							</div>
						</th>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Course', 'tutor-pro' ); ?>
							</div>
						</th>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Feedback', 'tutor-pro' ); ?>
							</div>
						</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( is_array( $reviews ) && count( $reviews ) ) : ?>
						<?php foreach ( $reviews as $review ) : ?>
							<tr>
								<td data-th="<?php esc_html_e( 'Date', 'tutor-pro' ); ?>" class="date">
									<div class="tutor-fs-7 tutor-fw-medium tutor-color-black">
										<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $review->comment_date ) ); ?><br />
										<?php echo esc_html( tutor_get_formated_date( get_option( 'time_format' ), $review->comment_date ) ); ?><br />
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Course', 'tutor-pro' ); ?>" class="course">
									<div class="tutor-fs-7 tutor-fw-medium tutor-color-black">
										<?php echo esc_html( get_the_title( $review->comment_post_ID ) ); ?>
									</div>
								</td>
								<td data-th="<?php esc_html_e( 'Feedback', 'tutor-pro' ); ?>" class="feedback">
									<div class="td-feedback">
										<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
											<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
												<?php tutor_utils()->star_rating_generator_v2( $review->rating, null, true ); ?>
											</div>
										</div>
										<p class="review-text tutor-color-black-60">
											<?php echo wp_kses_post( $review->comment_content ); ?>
										</p>
									</div>
								</td>
								<td class="action-btns">
									<a href="<?php echo esc_url( get_the_permalink( $review->comment_post_ID ) ); ?>" class="btn-text" target="_blank">
										<span class="tutor-icon-detail-link-filled"></span>
									</a>
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

</div> <!-- tutor-report-student-details -->
