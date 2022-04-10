<?php
    $meeting_host = $this->get_users_options();
    $timezone_options = require dirname(dirname(__DIR__)) . '/includes/timezone.php';
?>
<div class="tutor-modal modal-sticky-header-footer tutor-modal-is-close-inside-header tutor-zoom-meeting-modal-wrap" id="<?php echo $modal_id; ?>">
    <span class="tutor-modal-overlay"></span>
    <div class="tutor-modal-root">
        <div class="tutor-modal-inner">
            <div class="tutor-modal-header">
                <div class="tutor-modal-title tutor-fs-6 tutor-fw-bold tutor-color-black-70">
                    <?php _e('Zoom Meeting', 'tutor-pro'); ?>
                </div>
                <button data-tutor-modal-close class="tutor-modal-close">
                    <span class="tutor-icon-cross-filled"></span>
                </button>
            </div>
            <div class="tutor-modal-body-alt modal-container" style="background:#EFF1F6">
                <div id="tutor-zoom-meeting-modal-form">
                    <input type="hidden" data-name="action" value="tutor_zoom_save_meeting">
                    <input type="hidden" data-name="meeting_id" value="<?php echo $meeting_id; ?>">
                    <input type="hidden" data-name="topic_id" value="<?php echo $topic_id; ?>">
                    <input type="hidden" data-name="course_id" value="<?php echo $course_id; ?>">
                    <input type="hidden" data-name="click_form" value="<?php echo $click_form; ?>">

                    <div class="meeting-modal-form-wrap">

                        <div class="tutor-mb-16">
                            <label class="tutor-form-label"><?php _e('Meeting Name', 'tutor-pro'); ?></label>
                            <div class="tutor-input-group tutor-mb-4">
                                <input class="tutor-form-control" type="text" data-name="meeting_title" value="<?php echo $title; ?>" placeholder="Enter Meeting Name">
                            </div>
                        </div>

                        <div class="tutor-mb-16">
                            <label class="tutor-form-label"><?php _e('Meeting Summary', 'tutor-pro'); ?></label>
                            <div class="tutor-input-group tutor-mb-4">
                                <textarea class="tutor-form-control" type="text" data-name="meeting_summary" rows="4"><?php
                                    echo $summary;
                                ?></textarea>
                            </div>
                        </div>

                        <div class="tutor-mb-16 tutor-row">
                            <div class="tutor-col-6">
                                <div class="tutor-row">
                                    <div class="tutor-col-12">
                                        <label class="tutor-form-label"><?php _e('Meeting Time', 'tutor-pro'); ?></label>
                                    </div>
                                    <div class="tutor-col">
                                        <div class="tutor-input-group tutor-mb-12">
                                            <div class="tutor-v2-date-picker tutor-v2-date-picker-fd" style="width: 100%;" data-prevent_redirect="1" data-input_name="meeting_date" data-input_value="<?php echo $start_date ? tutor_get_formated_date( 'd-m-Y', $start_date ) : ''; ?>">

                                            </div>
                                        </div>
                                            <div class="tutor-input-group tutor-form-control-has-icon-right">
                                                <input type="text" data-name="meeting_time" class="tutor_zoom_timepicker tutor-form-control readonly" value="<?php echo $start_time; ?>" autocomplete="off" placeholder="08:30 PM">
                                                <span class="tutor-icon-clock-filled tutor-input-group-icon-right"></span>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tutor-col-6">
                                <div class="tutor-row">
                                    <div class="tutor-col-12">
                                        <label class="tutor-form-label"><?php _e('Meeting Duration', 'tutor-pro'); ?></label>
                                    </div>
                                    <div class="tutor-col">
                                        <input class="tutor-form-control tutor-mb-12" type="number" min="0" data-name="meeting_duration"  value="<?php echo $duration; ?>" autocomplete="off" placeholder="30"/>
                                        <select class="tutor-form-select" data-name="meeting_duration_unit">
                                            <option value="min" <?php selected($duration_unit, 'min'); ?>><?php _e('Minutes', 'tutor-pro'); ?></option>
                                            <option value="hr" <?php selected($duration_unit, 'hr'); ?>><?php _e('Hours', 'tutor-pro'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tutor-mb-16 tutor-row">
                            <div class="tutor-col-6">
                                <label class="tutor-form-label"><?php _e('Time Zone', 'tutor-pro'); ?></label>
                                <div class="tutor-input-group">
                                    <select data-name="meeting_timezone" class="tutor-form-select">
                                        <?php foreach ($timezone_options as $id => $option): ?>
                                            <option value="<?php echo $id; ?>" <?php selected($timezone, $id); ?>>
                                                <?php echo $option; ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="tutor-col-6">
                                <label class="tutor-form-label"><?php _e('Auto Recording', 'tutor-pro'); ?></label>
                                <div class="tutor-input-group tutor-mb-12" style="width: 100%;">
                                    <select class="tutor-form-select" data-name="auto_recording">
                                        <option value="none" <?php selected($auto_recording, 'none'); ?>><?php _e('No Recordings', 'tutor-pro'); ?></option>
                                        <option value="local" <?php selected($auto_recording, 'local'); ?>><?php _e('Local', 'Hours', 'tutor-pro'); ?></option>
                                        <option value="cloud" <?php selected($auto_recording, 'cloud'); ?>><?php _e('Cloud', 'Days', 'tutor-pro'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="tutor-mb-16">
                            <label class="tutor-form-label"><?php _e('Password', 'tutor-pro'); ?></label>
                            <div class="tutor-input-group tutor-form-control-has-icon-right tutor-mb-4">
                                <input type="text" data-name="meeting_password" class="tutor-form-control" value="<?php echo $password; ?>" autocomplete="off" placeholder="Create a Password" />
                                <span class="tutor-icon-lock-filled tutor-icon-30 tutor-input-group-icon-right tutor-color-black-60"></span>
                            </div>
                        </div>

                        <div class="tutor-mb-16">
                            <label class="tutor-form-label"><?php _e('Meeting Host', 'tutor-pro'); ?></label>
                            <div class="tutor-input-group">
                                <?php
                                    if(empty($host_id)) {
                                        $host_id = is_array($meeting_host) ? array_keys($meeting_host) : array();
                                        $host_id = isset($host_id[0]) ? $host_id[0] : null;
                                    }

                                    if($host_id) {
                                        $meeting_host_name = isset($meeting_host[$host_id]) ? $meeting_host[$host_id] : '';
                                        ?>
                                            <input type="hidden" data-name="meeting_host" value="<?php echo $host_id; ?>"/>
                                            <input class="tutor-form-control" type="text" disabled="disabled" value="<?php echo $meeting_host_name; ?>" style="cursor:not-allowed"/>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tutor-modal-footer">
                <div class="tutor-row">
                    <div class="tutor-col">
                        <button data-tutor-modal-close class="tutor-btn tutor-btn-disable tutor-no-hover">
                            <?php _e('Cancel', 'tutor-pro'); ?>
                        </button>
                    </div>
                    <div class="tutor-col-auto">
                        <button type="button" class="tutor-btn update_zoom_meeting_modal_btn">
                            <?php $meeting_id ? _e('Update Meeting', 'tutor-pro') : _e('Create Meeting', 'tutor-pro'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>