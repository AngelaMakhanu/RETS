<?php
/**
 * Overview tempate 
 * 
 * @since 1.9.9
 */
use \TUTOR_REPORT\Analytics;
use \TUTOR_REPORT\CourseAnalytics;

//global variables
$user           = wp_get_current_user();
$course_id      = isset( $_GET['course_id'] ) ? $_GET['course_id'] : 0;
$course_details = '';
if ( $course_id ) {
    $course_details = get_post( $course_id, OBJECT );
}
//if not valid course or not author of this course the return
if ( '' === $course_details || is_null( $course_details ) ) {
    return _e( 'Invalid course', 'tutor-pro' );
}
if ( $course_details->post_author != $user->ID ) {
    return _e( 'Invalid course', 'tutor-pro' );
}

$time_period  = $active = isset( $_GET['period'] ) ? $_GET['period'] : '';
$start_date   = isset( $_GET['start_date']) ? sanitize_text_field( $_GET['start_date'] ) : '';
$end_date     = isset( $_GET['end_date']) ? sanitize_text_field( $_GET['end_date'] ) : '';
if ( '' !== $start_date ) {
    $start_date = tutor_get_formated_date( 'Y-m-d', $start_date);
} 
if ( '' !== $end_date ) {
    $end_date = tutor_get_formated_date( 'Y-m-d', $end_date);
} 
$previous_url = esc_url( tutor_utils()->tutor_dashboard_url().'courses' );

?>
<div class="analytics-course-details">
    <div class="back-summary-wrapper">
        <div class="back-wrapper">
            <a class="tutor-back-btn" href="<?php echo esc_url( tutor_utils()->tutor_dashboard_url().'analytics/courses' );?>">
                <span class="tutor-icon-previous-line tutor-color-design-dark"></span>
                <span class="text text tutro-tutor-fs-7 tutor-color-black"><?php _e('Back', 'tutor'); ?></span>
            </a>
        </div>
        <div class="course-summary">
            <h4>
                <?php esc_html_e( $course_details->post_title ); ?>
            </h4>
            <div class="summary">
                <div class="label-value">
                    <label>
                        <?php _e( 'Published Date', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $course_details->post_date ) ); ?>
                    </span>
                </div>
                <div class="label-value" style="display: flex;justify-content: flex-start;">
                    <img src="<?php echo esc_url( TUTOR_REPORT()->url.'assets/images/last-update.svg'); ?>" alt="" style="width: 20px;margin-right: 3px;align-items: center;padding-top: 5px;height: 22px;">
                    <label>
                        <?php _e( 'Last Update', 'tutor-pro' ); ?>
                    </label>
                    <span>
                        <?php esc_html_e( tutor_get_formated_date( get_option( 'date_format' ), $course_details->post_modified ) ); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- box cards -->
    <?php 
        $card_template    = TUTOR_REPORT()->path.'templates/elements/box-card.php';
        $total_student    = CourseAnalytics::course_enrollments_with_student_details( $course_id );
        $total_ratings    = tutor_utils()->get_course_rating($course_id);
        $total_qa         = CourseAnalytics::course_question_answer( $course_id );
        $total_assignment = CourseAnalytics::submitted_assignment_by_course( $course_id );

        $data  = array(
            array(
                'icon'      => 'tutor-icon-book-open-filled',
                'title'     => esc_html__( $total_student['total_enrollments'] ),
                'sub_title' => __( 'Total Student', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => 'tutor-icon-college-graduation-filled',
                'title'     => esc_html__( $total_student['total_inprogress'] ),
                'sub_title' => __( 'In Progress Courses', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => 'tutor-icon-award-filled',
                'title'     => esc_html__( $total_student['total_completed'] ),
                'sub_title' => __( 'Completed Courses', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => 'tutor-icon-question-filled',
                'title'     => esc_html__( $total_qa['total_q_a'] ) ,
                'sub_title' => __( 'Questions', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => 'tutor-icon-star-full-filled',
                'title'     => esc_html__( $total_ratings->rating_avg ),
                'sub_title' => esc_html__( $total_ratings->rating_count.' Reviews ', 'tutor-pro' ),
                'price'     => false
            ),
            array(
                'icon'      => 'tutor-icon-list-filled',
                'title'     => $total_assignment['total_assignments'],
                'sub_title' => __( 'Assignment Submit', 'tutor-pro' ),
                'price'     => false
            )
        );

        tutor_load_template_from_custom_path($card_template, $data);
    ?>
    <!-- box cards end -->
    <!--filter buttons tabs-->
    <?php 
        /**
         * Prepare filter period buttons
         * 
         * Array structure is required as below
         * 
         * @since 1.9.8
         */
        $filter_period = array(
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url()."analytics/course-details?course_id=$course_id&period=today" ),
                'title' => __( 'Today', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'today'
            ),
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url()."analytics/course-details?course_id=$course_id&period=monthly" ),
                'title' => __( 'Monthly', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'monthly'
            ),
            array(
                'url'   => esc_url( tutor_utils()->tutor_dashboard_url()."analytics/course-details?course_id=$course_id&period=yearly" ),
                'title' => __( 'Yearly', 'tutor-pro' ),
                'class' => 'tutor-analytics-period-button',
                'type'  => 'yearly'
            ),
        );

        /**
         * Calendar date buttons
         * 
         * Array structure is required as below
         * 
         * @since 1.9.8
         */
        
        $filter_period_calendar = array(
            'filter_period'     => $filter_period,
            'filter_calendar'   => true
        );

        $filter_period_calendar_template = TUTOR_REPORT()->path.'templates/elements/period-calendar.php';
        tutor_load_template_from_custom_path($filter_period_calendar_template, $filter_period_calendar);
    ?>
    <!--filter button tabs end-->
    <!--analytics graph -->
    <?php
        /**
         * Get analytics data
         * 
         * @since 1.9.9
         */
        $earnings       = Analytics::get_earnings_by_user( $user->ID, $time_period, $start_date, $end_date, $course_id ); 
        $discounts      = Analytics::get_discounts_by_user( $user->ID, $time_period, $start_date, $end_date, $course_id ); 
        $refunds        = Analytics::get_refunds_by_user( $user->ID, $time_period, $start_date, $end_date, $course_id); 
        $content_title  = ''; 
        if (  'today' === $time_period ) {
            $day = date('l');
            $content_title = __( "for today ($day) ", 'tutor-pro' );
        } else if ( 'monthly' === $time_period ) {
            $month = date('F');
            $content_title = __( "for this month ($month) ", 'tutor-pro' );
        } else if ( 'yearly' === $time_period ) {
            $year = date('Y');
            $content_title = __( "for this year ($year) ", 'tutor-pro' );
        }
        $graph_tabs = array(
            array(
                'tab_title'     => __( 'Total Earning', 'tutor-pro' ),
                'tab_value'     => $earnings['total_earnings'],
                'data_attr'     => 'ta_total_earnings',
                'active'        => 'active',
                'price'         => true,
                'content_title' => __( 'Earning chart '.$content_title, 'tutor-pro' )
            ),
            array(
                'tab_title'     => __( 'Discount', 'tutor-pro' ),
                'tab_value'     => $discounts['total_discounts'],
                'data_attr'     => 'ta_total_discount',
                'active'        => '',
                'price'         => true,
                'content_title' => __( 'Discount chart '.$content_title, 'tutor-pro' )
            ),
            array(
                'tab_title'     => __( 'Refund', 'tutor-pro' ),
                'tab_value'     => $refunds['total_refunds'],
                'data_attr'     => 'ta_total_refund',
                'active'        => '',
                'price'         => true,
                'content_title' => __( 'Refund chart '.$content_title, 'tutor-pro' )
            ),
        );
        $graph_template = TUTOR_REPORT()->path.'templates/elements/graph.php';
        tutor_load_template_from_custom_path($graph_template, $graph_tabs);
    ?>
    <!--analytics graph end -->     
</div>