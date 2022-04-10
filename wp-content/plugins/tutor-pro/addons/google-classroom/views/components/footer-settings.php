<?php
	$logged_in_as = $classroom->get_who_logged_in();
?>

<div class="tutor-container-fluid">
    <div class="tutor-row tutor-gc-setting-container">
        <div class="tutor-col-12 tutor-col-xl-6">
            <div>
                <div class="tutor-row  tutor-align-items-center">
                    <div class="tutor-col-12 tutor-col-md-6">
                        <div class="tutor-gc-setting-content">
                            <h3><?php esc_html_e( 'Classroom List', 'tutor-pro' ); ?></h3>
                            <p><?php esc_html_e( 'Here is a list of Classrooms on your current Google account.', 'tutor-pro' ); ?></p>
                        </div>
                    </div>
                    <div class="tutor-col-12 tutor-col-md-6" style="text-align:right">
                        <div class="tutor-gc-setting-content">
                            <button id="tutor_gc_credential_upgrade" data-message="<?php esc_attr_e( 'Sure to use another account?', 'tutor-pro' ); ?>">
                                <?php esc_html_e( 'Use Another Account', 'tutor-pro' ); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="tutor-row  tutor-align-items-center">
                    <div class="tutor-col-12 tutor-col-md-6">
                        <div class="tutor-gc-setting-content">
                            <?php esc_html_e( 'Google Classroom Account', 'tutor-pro' ); ?>: <b><?php echo esc_html( $logged_in_as->emailAddress ); ?></b>
                        </div>
                    </div>
                    <div class="tutor-col-12 tutor-col-md-6" style="text-align:right">
                        <div class="tutor-gc-setting-content">
                            <?php esc_html_e( 'Classlist Shortcode:', 'tutor-pro' ); ?> <span><b>[tutor_gc_classes]</b> <span class="tutor-icon-copy-filled tutor-copy-text" data-text="[tutor_gc_classes]"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tutor-col-12 tutor-col-xl-6">
            <div>
                <div class="tutor-gc-setting-content">
                    <h3><?php esc_html_e( 'Classroom Access Settings', 'tutor-pro' ); ?></h3>
                    <p><?php esc_html_e( 'Control the visibility and privacy for the Google Classroom data', 'tutor-pro' ); ?></p>
                </div>
                <hr/>
                <div class="tutor-gc-setting-content">
                    <label class="tutor-switch">
                        <input type="checkbox" id="tutor_gc_classroom_code_privilege" <?php echo $is_code_for_only_logged ? 'checked="checked"' : ''; ?>>
                        <span class="slider round"></span>
                    </label>

                    &nbsp; <?php esc_html_e( 'Only logged in students can see the classroom invite code', 'tutor-pro' ); ?>
                </div>
            </div>
        </div>
    </div>
</div>