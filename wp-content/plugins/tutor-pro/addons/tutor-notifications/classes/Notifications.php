<?php
/**
 * handles registering all notifications
 *
 * @package tutor
 *
 * @since 1.9.10
 */

namespace TUTOR_NOTIFICATIONS;

defined( 'ABSPATH' ) || exit;

/**
 * Notifications class
 */
class Notifications {

    /**
     * Construct
     */
    public function __construct() {
        add_action( 'tutor_after_approved_instructor', array( $this, 'instructor_approval' ) );
        add_action( 'tutor_after_rejected_instructor', array( $this, 'instructor_rejected' ) );

        add_action( 'tutor_new_instructor_after', array( $this, 'new_instructor_application' ) );

		add_action( 'tutor_assignment/evaluate/after', array( $this, 'tutor_after_assignment_evaluated' ), 10, 3);
        add_action( 'tutor_announcements/after/save', array( $this, 'tutor_announcements_notify_students' ), 10, 3 );
        add_action( 'tutor_after_answer_to_question', array( $this, 'tutor_after_answer_to_question' ) );
        add_action( 'tutor_quiz/attempt/submitted/feedback', array( $this, 'feedback_submitted_for_quiz_attempt' ) );

		add_action( 'tutor_after_enrolled', array( $this, 'tutor_student_course_enrolled' ), 10, 3 );
		add_action( 'tutor_enrollment/after/cancel', array( $this, 'tutor_student_remove_from_course' ), 10, 1 );
    }

    /**
     * Instructor Approval
     */
    public function instructor_approval( $instructor_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_instructors.instructor_application_accepted' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $user_data       = get_userdata( $instructor_id );
        $display_name    = $user_data->display_name;

        $message_type    = 'Instructorship';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Instructorship', 'tutor-pro' );
        $message_content = sprintf( _x( '<span class="tutor-color-black-70">Congratulations</span> %s, <span class="tutor-color-black-70">your application to be an instructor has been approved.</span>', 'instructorship-approved-text', 'tutor-pro' ), ucfirst( $display_name ) );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $instructor_id,
            'post_id'     => null,
            'topic_url'   => null
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Instructor Rejected
     */
    public function instructor_rejected( $instructor_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_instructors.instructor_application_rejected' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $user_data       = get_userdata( $instructor_id );
        $display_name    = $user_data->display_name;

        $message_type    = 'Instructorship';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Instructorship', 'tutor-pro' );
        $message_content = sprintf( _x( '%s, <span class="tutor-color-black-70">your instructorship application has been declined.</span>', 'instructorship-rejected-text', 'tutor-pro' ), ucfirst( $display_name ) );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $instructor_id,
            'post_id'     => null,
            'topic_url'   => null
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * New Instructor Application
     */
    public function new_instructor_application( $instructor_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_admin.instructor_application_received' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $admin_users     = get_users( array( 'role__in' => array( 'administrator' ) ) );
        $user_data       = get_userdata( $instructor_id );
        $display_name    = $user_data->display_name;

        $message_type    = 'Instructorship';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Instructorship', 'tutor-pro' );

        $admin_records = array();
        foreach ( $admin_users as $admin ) {
            $data = array(
                'type'        => $message_type,
                'title'       => $message_title,
                'status'      => $message_status,
                'receiver_id' => (int) $admin->ID,
                'post_id'     => null,
                'topic_url'   => null
            );

            $data['content'] = sprintf( _x( '%s, <span class="tutor-color-black-70">you have received a new application from</span> %s <span class="tutor-color-black-70">for Instructorship.</span>', 'instructor-application-received', 'tutor-pro' ), ucfirst( $admin->display_name ), ucfirst( $display_name ) );

            array_push( $admin_records, $data );
        }

        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        foreach ( $admin_records as $admin_record ) {
            if ( ! is_wp_error( $data ) ) {
                $notification_created = $wpdb->insert( $tablename, $admin_record, $format );
            }
        }
    }

    /**
     * Assignment Graded
     */
    public function tutor_after_assignment_evaluated( $assignment_submission_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.assignment_graded' );

        if ( ! $notification_enabled ) return;

        $tablename            = $wpdb->prefix . 'tutor_notifications';

        $submitted_assignment = tutor_utils()->get_assignment_submit_info( $assignment_submission_id );
		$assignment_name      = get_the_title( $submitted_assignment->comment_post_ID );
		$assignment_comment   = get_comment_meta( $assignment_submission_id, 'instructor_note', true );
        $assignment_url       = get_permalink( $submitted_assignment->comment_post_ID );

        $user_data            = get_userdata( $submitted_assignment->user_id );
        $display_name         = $user_data->display_name;

        $message_type         = 'Assignments';
        $message_status       = 'UNREAD';
        $message_title        = __( 'Assignments', 'tutor-pro' );
        $message_content      = sprintf( _x( '<span class="tutor-color-black-70">Hi</span> %s, <span class="tutor-color-black-70">your</span> %s <span class="tutor-color-black-70">has been graded. Check it out.</span>', 'grades-submitted-text', 'tutor-pro' ), ucfirst( $display_name ), $assignment_name );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $submitted_assignment->user_id,
            'post_id'     => (int) $submitted_assignment->comment_post_ID,
            'topic_url'   => $assignment_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Announcement Notifications
     */
    public function tutor_announcements_notify_students( $announcement_id, $announcement, $action_type ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.new_announcement_posted' );
		if ( ! isset( $_POST['tutor_notify_all_students'] ) || ! $_POST['tutor_notify_all_students'] || ! $notification_enabled ){
            return;
        }

        $tablename        = $wpdb->prefix . 'tutor_notifications';
        $student_ids      = tutor_utils()->get_students_data_by_course_id( $announcement->post_parent, 'ID' );
		$course_name      = get_the_title( $announcement->post_parent );
        $author           = get_userdata( $announcement->post_author );
        $author_name      = $author->display_name;

        $ann_title        = $action_type === 'create' ? __( 'A new announcement has been posted by', 'tutor-pro' ) : __( 'An announcement has been updated by', 'tutor-pro' );
        $message_type     = 'Announcements';
        $message_status   = 'UNREAD';
        $message_title    = __( 'Announcements', 'tutor-pro' );
        $message_content  = sprintf( _x( '<span class="tutor-color-black-70">%s</span> %s <span class="tutor-color-black-70">of</span> %s.', 'announcement-text', 'tutor-pro' ), $ann_title, ucfirst( $author_name ), $course_name );
        $announcement_url = get_permalink( $announcement->post_parent ) . 'announcements/';

        $announcement_records = array();

        // Loop through $student_ids to send announcements for each of them.
        foreach ( $student_ids as $key => $value ) {
            $data = array(
                'type'        => $message_type,
                'title'       => $message_title,
                'content'     => $message_content,
                'status'      => $message_status,
                'receiver_id' => (int) $value,
                'post_id'     => (int) $announcement->post_parent,
                'topic_url'   => $announcement_url,
            );

            array_push( $announcement_records, $data );
        }

        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        // Insert announcement for every student enrolled in the course in question.
        foreach ( $announcement_records as $announcement ) {
            if ( ! is_wp_error( $announcement ) ) {
                $wpdb->insert( $tablename, $announcement, $format );
            }
        }
    }

    /**
     * After Answering Questions
     */
    public function tutor_after_answer_to_question( $answer_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.after_question_answered' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';
        $answer          = tutor_utils()->get_qa_answer_by_answer_id( $answer_id );
		$course_name     = get_the_title( $answer->comment_post_ID );
        $comment_author  = 'tutor_q_and_a' === get_comment_type( $answer_id ) ? get_comment_author( $answer_id ) : 0;
		$question_author = $answer->question_by;

        $message_type    = 'Q&A';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Q&A', 'tutor-pro' );
        $message_content = sprintf( _x( '<span class="tutor-color-black-70">A new answer has been posted by</span> %s <span class="tutor-color-black-70">in</span> %s\'s Q&A.', 'qa-answer-posted', 'tutor-pro' ), ucfirst( $comment_author ), $course_name );
        $qa_url          = get_permalink( $answer->comment_post_ID ) . 'questions/';

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $question_author,
            'post_id'     => (int) $answer->comment_post_ID,
            'topic_url'   => $qa_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Feedback submitted for quizes
     */
    public function feedback_submitted_for_quiz_attempt( $attempt_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.feedback_submitted_for_quiz' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $attempt         = tutor_utils()->get_attempt( $attempt_id );
		$quiz_title      = get_post_field( 'post_title', $attempt->quiz_id );
		$course          = get_post( $attempt->course_id );
		$feedback        = get_post_meta( $attempt_id, 'instructor_feedback', true );

        $message_type    = 'Quiz';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Quiz', 'tutor-pro' );
        $message_content = sprintf( _x( '<span class="tutor-color-black-70">Your quiz result for</span> %s <span class="tutor-color-black-70">of</span> %s <span class="tutor-color-black-70">has been published.</span>', 'quiz-attempt-text', 'tutor-pro' ), $quiz_title, $course->post_title );
        $quiz_url        = tutor_utils()->get_tutor_dashboard_page_permalink( 'my-quiz-attempts' );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $attempt->user_id,
            'post_id'     => (int) $course->ID,
            'topic_url'   => $quiz_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Course enrolled
     */
    public function tutor_student_course_enrolled( $course_id, $user_id, $enrollment_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.course_enrolled' );

        if ( ! $notification_enabled ) return;

        $tablename       = $wpdb->prefix . 'tutor_notifications';

        $user_data       = get_userdata( $user_id );
        $display_name    = $user_data->display_name;
        $course          = tutor_utils()->get_course_by_enrol_id( $enrollment_id );
        $course_title    = $course->post_title;
        $course_url      = get_permalink( $course_id );

        $message_type    = 'Enrollments';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Enrollments', 'tutor-pro' );
        $message_content = sprintf( _x( '<span class="tutor-color-black-70">Congratulations, you have been successfully enrolled in</span> %s.', 'got-enrolled-text', 'tutor-pro' ), $course_title );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $user_id,
            'post_id'     => (int) $course_id,
            'topic_url'   => $course_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }

    /**
     * Enrollment Cancelled
     */
    public function tutor_student_remove_from_course( $enrollment_id ) {
        global $wpdb;

        $notification_enabled = tutor_utils()->get_option( 'tutor_notifications_to_students.remove_from_course' );

        if ( ! $notification_enabled ) return;

        $tablename = $wpdb->prefix . 'tutor_notifications';
        $course    = tutor_utils()->get_enrolment_by_enrol_id( $enrollment_id );

        if ( ! $course ) return;

        $display_name    = $course->display_name;
        $course_title    = $course->course_title;
        $course_url      = get_permalink( $course->course_id );

        $message_type    = 'Enrollments';
        $message_status  = 'UNREAD';
        $message_title   = __( 'Enrollments', 'tutor-pro' );
        $message_content = sprintf( _x( '%s, <span class="tutor-color-black-70">your enrollment request for</span> %s <span class="tutor-color-black-70">has been declined.</span>', 'enrollment-cancelled-text', 'tutor-pro' ), ucfirst( $display_name ), $course_title );

        $data = array(
            'type'        => $message_type,
            'title'       => $message_title,
            'content'     => $message_content,
            'status'      => $message_status,
            'receiver_id' => (int) $course->ID,
            'post_id'     => (int) $course->course_id,
            'topic_url'   => $course_url
        );
        $format = array( '%s', '%s', '%s', '%s', '%d', '%d', '%s' );

        if ( ! is_wp_error( $data ) ) {
            $notification_created = $wpdb->insert( $tablename, $data, $format );
        }
    }
}