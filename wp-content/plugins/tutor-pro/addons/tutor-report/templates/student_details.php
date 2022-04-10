<?php
/**
 * Student details template
 *
 * @since 1.9.9
 */
use TUTOR_REPORT\Analytics;

$user               = wp_get_current_user();
$student_id         = isset( $_GET['student_id'] ) ? sanitize_text_field( $_GET['student_id'] ) : 0;
$student_details    = get_userdata( $student_id );
if ( !$student_id || !$student_details ) {
    return _e( 'Invalid student', 'tutor-pro' );
}
$courses = tutor_utils()->get_courses_by_student_instructor_id( $student_id, $user->ID );
?>

<div class="analytics-student-details tutor-user-public-profile tutor-user-public-profile-pp-circle">
    <div class="back-wrapper">
        <a href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url().'analytics/students' );?>">
            <i class="tutor-icon-previous-line tutor-icon-30 tutor-color-design-dark"></i> <?php _e( 'Back', 'tutor-pro' ); ?>
        </a>
    </div>
    <div class="photo-area">
        <div class="cover-area">
            <div style="background-image:url(<?php echo esc_url( tutor_utils()->get_cover_photo_url($student_id) ); ?>); height: 268px"></div>
            <div></div>
        </div>
        <div class="pp-area">
            <div class="profile-pic" style="background-image:url(<?php echo esc_url( get_avatar_url($student_id, array('size' => 150)) ); ?>)">
            </div>
            <div class="profile-name tutor-color-white">
                <h3 class="analytics-profile-name">
                   <?php esc_html_e( $student_details->display_name ); ?>
                </h3>
                <div class="analytics-profile-authormeta">
                    <span class="tutor-fs-7 ">
                        <span class="">
                           <?php _e( 'Email: ', 'tutor-pro'); ?>
                        </span>
                        <span class="tutor-fs-7 tutor-fw-medium">
                            <?php esc_html_e( $student_details->user_email ); ?>
                        </span>
                    </span>
                    <span  class="tutor-fs-7">
                        <span>
                            <?php _e( 'Registration Date: '); ?>
                        </span>
                        <span class="tutor-fs-7 tutor-fw-medium">
                            <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $student_details->user_registered ) ); ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="student-details-table-wrapper">
        <div class="tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-16">
            <?php _e( 'Course Overview', 'tutor-pro' );?>
        </div>
        <div class="tutor-ui-table-wrapper">
            <table class="tutor-ui-table tutor-ui-table-responsive tutor-ui-table-analytics-student-details">
                <thead>
                    <th>
                        <span class="tutor-color-black-60 tutor-fs-7">
                            <?php _e( 'Date', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th>
                        <span class="tutor-color-black-60 tutor-fs-7">
                            <?php _e( 'Course', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th>
                        <span class="tutor-color-black-60 tutor-fs-7">
                            <?php _e( 'Progress', 'tutor-pro' ); ?>
                        </span>
                    </th>
                    <th class="tutor-shrink"></th>
                </thead>
                <tbody>
                    <?php if ( count($courses) ): ?>
                            <?php foreach( $courses as $course): ?>
                                <?php
                                    $completed_count      = tutor_utils()->get_course_completed_percent( $course->ID, $student_id );
                                    $total_lessons        = tutor_utils()->get_lesson_count_by_course( $course->ID );
                                    $completed_lessons    = tutor_utils()->get_completed_lesson_count_by_course( $course->ID, $student_id );
                                    $total_assignments    = tutor_utils()->get_assignments_by_course( $course->ID )->count;
                                    $completed_assignment = tutor_utils()->get_completed_assignment( $course->ID, $student_id );
                                    $total_quiz           = Analytics::get_all_quiz_by_course( $course->ID );
                                    $completed_quiz       = tutor_utils()->get_completed_quiz( $course->ID, $student_id );
                                ?>
                                <tr>
                                    <td data-th="<?php _e( 'Date', 'tutor-pro' ); ?>">
                                        <span class="tutor-fs-7 tutor-color-black">
                                            <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $course->post_date ) ); ?>
                                        </span>
                                    </td>
                                    <td data-th="<?php _e( 'Course', 'tutor-pro' ); ?>">
                                        <div class="tutor-color-black td-course tutor-fs-6 tutor-fw-medium">
                                            <span>
                                                <?php esc_html_e( $course->post_title ); ?>
                                            </span>
                                            <div class="course-meta">
                                                <span class="tutor-color-black-60 tutor-fs-7">
                                                    <?php _e( 'Lesson: ', 'tutor-pro' ); ?>
                                                    <strong class="tutor-fs-7 tutor-fw-medium">
                                                        <?php esc_html_e( $completed_lessons.'/'.$total_lessons); ?>
                                                    </strong>
                                                </span>
                                                <span class="tutor-color-black-60 tutor-fs-7">
                                                    <?php _e( 'Assignment: ', 'tutor-pro' ); ?>
                                                    <strong class="tutor-fs-7 tutor-fw-medium">
                                                        <?php esc_html_e( $completed_assignment.'/'.$total_assignments); ?>
                                                    </strong>
                                                </span>
                                                <span class="tutor-color-black-60 tutor-fs-7">
                                                    <?php _e( 'Quiz: ', 'tutor-pro' ); ?>
                                                    <strong class="tutor-fs-7 tutor-fw-medium">
                                                        <?php esc_html_e( $completed_quiz.'/'.$total_quiz); ?>
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-th="<?php _e( 'Progress', 'tutor-pro' ); ?>">
                                        <div class="td-progress inline-flex-center">
                                            <div class="progress-bar" style="--progress-value: <?php esc_attr_e( $completed_count );?>%">
                                                <div class="progress-value"></div>
                                            </div>
                                            <div class="progress-text tutor-fs-7 tutor-fw-medium tutor-color-black">
                                                <?php esc_html_e( $completed_count ); ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="td-action-btns" data-th="<?php _e( '-', 'tutor-pro' ); ?>">
                                        <button type="button" id="open_progress_modal" data-tutor-modal-target="modal-sticky-1" class="analytics_view_course_progress tutor-btn tutor-btn-disable-outline tutor-btn-outline-fd tutor-btn-sm" data-course_id="<?php echo esc_attr_e( $course->ID ); ?>" data-total_progress="<?php echo esc_attr_e( $completed_count ); ?>" data-total_lesson="<?php echo esc_attr_e( $total_lessons ); ?>" data-completed_lesson="<?php echo esc_attr_e( $completed_lessons ); ?>" data-total_assignment="<?php echo esc_attr_e( $total_assignments ); ?>" data-completed_assignment="<?php echo esc_attr_e( $completed_assignment ); ?>" data-total_quiz="<?php echo esc_attr_e( $total_quiz ); ?>" data-completed_quiz="<?php echo esc_attr_e( $completed_quiz ); ?>" data-student_id="<?php esc_attr_e( $student_id ); ?>">
                                            <?php _e( 'View Progress', 'tutor-pro' ); ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach?>
                        <?php else: ?>
                            <tr>
                            <td colspan="100%" class="tutor-empty-state">
                                <?php tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() ); ?>
                            </td>
                            </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--modal -->
<div id="modal-sticky-1" class="modal-course-overview tutor-modal">
    <span class="tutor-modal-overlay"></span>
    <div class="tutor-modal-root">
        <div class="tutor-modal-inner">
            <button data-tutor-modal-close="" class="tutor-modal-close">
                <span class="tutor-icon-56 tutor-icon-line-cross-line"></span>
            </button>
            <div class="tutor-modal-body" id="tutor_progress_modal_content">

            </div>
        </div>
    </div>
</div>
<!--modal end-->