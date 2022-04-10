<div class="tutor-gc-stream-classroom-info">
    <h3><?php echo esc_html( $classroom_info->descriptionHeading ); ?></h3>
    <p><?php echo esc_html( $classroom_info->room_and_section ); ?></p>
    <div>
        <span class="tutor-gc-class-code">
            <span><?php esc_html_e( 'Code', 'tutor-pro' ); ?>: </span>
            <span><?php echo esc_html( $classroom_info->enrollmentCode ); ?></span>
            <span class="tutor-icon-copy-filled tutor-copy-text" data-text="<?php echo esc_attr( $classroom_info->enrollmentCode ); ?>"></span>
        </span>

        <a class="tutor-gc-class-go-to" href="<?php echo esc_url( $classroom_info->alternateLink ); ?>">
            <?php esc_html_e( 'Go to Classroom', 'tutor-pro' ); ?>
        </a>
    </div>
</div>

<div class="tutor-announcements-wrap">
    <?php
        if ( ! count( $classroom_stream ) ) {
            // echo '<br/><div style="text-align:center">'.__('No Post Found', 'tutor-pro').'</div>';
        }

        include 'stream-individual.php';

        if ( $stream_next_token ) {
            ?>
                <div style="text-align:center" id="tutor_gc_stream_loader" data-next_token="<?php echo esc_attr( $stream_next_token ); ?>" data-course_id="<?php echo esc_attr( $course_id ); ?>">
                    <br/>
                    <br/>
                    <a href="#"><?php esc_html_e( 'Load More', 'tutor-prop' ); ?></a>
                    <span style="display:inline-block;">
                        <img src="<?php echo esc_url( get_admin_url() . 'images/loading.gif' ); ?>" style="display:none;"/>
                    </span>
                </div>
            <?php
        }
    ?>    
</div>