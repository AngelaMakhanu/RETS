<?php 

// completed lesson ajax

add_action( 'wp_footer', 'wpc_completed_action_javascript', 12 );

function wpc_completed_action_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {

        jQuery('#wpc-completed-lesson-toggle').click(function(e){
            e.preventDefault();

            var lessonID = $(this).attr('data-id');
            var $lessonBtn = $('#wpc-completed-lesson-toggle');
            var $lessonBtnIcon = $('#wpc-completed-lesson-toggle i');
            var $lessonNavBtnIcon = $('[data-lesson-button-id="' + lessonID + '"] i');
            $lessonBtnIcon.attr('class', 'fa fa-spinner fa-spin fa-fw');
            $lessonNavBtnIcon.attr('class', 'fa fa-spinner fa-spin fa-fw');

            var data = {
                'type'      : 'POST',
                'action'    : 'get_badge_awards_lightbox',
                // 'dataType'   : 'text/html',
                'userID': <?php if(is_user_logged_in()){ echo get_current_user_ID(); } else { echo 'null'; } ?>,
                'completedStatus': $('#wpc-completed-lesson-toggle').attr('data-status'),
                'postID': lessonID,
            };
    
            jQuery.post(ajaxurl, data, function(response) {

                var res = JSON.parse(response);

                var $lessonNavBtnIcon = $('[data-lesson-button-id="' + data.postID + '"] i');

                // completed button display logic
                if($lessonBtn.attr('data-status') == 0) {
                    $lessonBtn.attr('data-status', 1);
                    $lessonBtn.html('<i class="fa fa-check-square-o"></i> ' + WPCTranslations.completed);
                    $lessonNavBtnIcon.attr('class', 'fa fa-check');
                } else {
                    $lessonBtn.attr('data-status', 0);
                    $lessonBtn.html('<i class="fa fa-square-o"></i> ' + WPCTranslations.notCompleted);
                    $lessonNavBtnIcon.attr('class', 'fa fa-eye');
                }

                // append the badge awards lightbox
                $('body').append(res.html);

                // adjust progress bar
                jQuery('.wpc-progress-bar-level').css('width', res.percent + "%");
                jQuery('.wpc-progress-bar-text').html('<i class="fa fa-check"></i> ' + res.percent + '%' + ' Completed');
                    
            });

        });

    });

    </script>
<?php } 

    add_action( 'wp_ajax_get_badge_awards_lightbox', 'wpc_get_badge_awards_lightbox_action', 12 );

    function wpc_get_badge_awards_lightbox_action() {
        $lesson_id = (int) $_POST['postID'];
        $post_type = get_post_type($lesson_id);
        $user_id = (int) $_POST['userID'];
        $status = (int) $_POST['completedStatus'];
        $status = $status === 0 ? 1 : 0;
        $last_viewed_course = (int) get_user_meta($user_id, 'wpc-last-viewed-course', true);
        $course_id = !empty($last_viewed_course) ? $last_viewed_course : wpc_get_first_connected_course($lesson_id);

        wpc_push_completed( $user_id, $lesson_id, $status );

        $lightbox = wpc_rule_evaluation_engine( $post_type, false );
        $percent = wpc_get_percent_done($course_id, $user_id, 1);
        echo json_encode(array(
            'html'      => $lightbox,
            'percent'   => $percent,
        ));
       
        wp_die(); // required
    } 

?>
