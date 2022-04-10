<?php
$user_id = get_current_user_id();
$zoom_object = new \TUTOR_ZOOM\Zoom(false);

$zoom_meetings = $zoom_object->get_meetings(null, null, null, array(
    'author' =>  $user_id,
    'course_id' => $course_id
), false);

?>
<div class="tutor-zoom-integration-card tutor-radius-6 tutor-border-sl30 tutor-bg-white tutor-mt-20">
    <?php if(!count($zoom_meetings)): ?>
        <div class="tutor-d-lg-flex tutor-align-items-center tutor-justify-content-sm-between tutor-zoom-no-meetings">
            <div class="tutor-d-flex tutor-align-items-center tutor-mb-lg-0 tutor-mb-16">
                <span class="btn-icon tutor-icon-zoom tutor-icon-40" style="color: #2e8cff" area-hidden="true"></span>
                <div class="tutor-fs-5 tutor-fw-medium tutor-color-black-70 tutor-ml-8">
                    <?php _e('Connect with your students using Zoom', 'tutor-pro'); ?>
                </div>
            </div>
            <div class="tutor-ml-lg-12 tutor-ml-0">
                <button class="tutor-btn tutor-btn-icon create-zoom-meeting-btn tutor-btn-md" data-tutor-modal-target="tutor-zoom-new-meeting">
                    <span class="btn-icon tutor-icon-zoom" area-hidden="true"></span>
                    <span><?php _e('Create a Zoom Meeting', 'tutor-pro'); ?></span>
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="tutor-zoom-integration-card-header tutor-py-12 tutor-px-12">
            <div class=""><?php _e('Zoom Meeting', 'tutor-pro'); ?></div>
        </div>
        <?php 
            foreach ($zoom_meetings as $meeting) { 
                $tzm_start      = get_post_meta($meeting->ID, '_tutor_zm_start_datetime', true);
                $meeting_data   = get_post_meta($meeting->ID, $this->zoom_meeting_post_meta, true);
                $meeting_data   = json_decode($meeting_data, true);

                if(!$tzm_start) {
                    continue;
                }

                $input_date     = \DateTime::createFromFormat('Y-m-d H:i:s', $tzm_start);
                $start_date     = $input_date->format('j M, Y');
                $start_time     = $input_date->format('h:i A');

                $row_id         = 'tutor-zoom-meeting-row-' . $meeting->ID;
                $id_string_delete = 'tutor-zoom-meeting-del-' . $meeting->ID;
                ?>
                <div id="<?php echo $row_id; ?>" class="tutor-zoom-integration-card-body tutor-py-24 tutor-px-12">
                    <div class="list-item">
                        <div class="tutor-fs-7 tutor-color-black-60">
                            <?php _e('Start Time', 'tutor-pro'); ?>
                        </div>
                        <div class="text-semi-caption tutor-color-black tutor-mt-6">
                            <?php echo $start_date; ?> <span class="tutor-fs-7"><?php echo $start_time; ?></span>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="tutor-fs-7 tutor-color-black-60">
                            <?php _e('Meeting Name', 'tutor-pro'); ?>
                        </div>
                        <div class="tutor-fs-7 tutor-color-black tutor-mt-6">
                            <?php echo $meeting->post_title; ?>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="tutor-fs-7 tutor-color-black-60">
                            <?php _e('Meeting Token', 'tutor-pro'); ?>
                        </div>
                        <div class="tutor-fs-7 tutor-color-black tutor-mt-6">
                            <?php echo $meeting_data['id']; ?>
                        </div>
                    </div>
                    <div class="list-item">
                        <div class="tutor-fs-7 tutor-color-black-60">
                            <?php _e('Password', 'tutor-pro'); ?>
                        </div>
                        <div class="tutor-fs-7 tutor-color-black tutor-mt-6">
                            <?php echo $meeting_data['password']; ?>
                        </div>
                    </div>
                    <div class="list-item list-item-buttons">
                        <button class="tutor-btn tutor-btn-icon tutor-btn-primary tutor-is-outline tutor-btn-sm tutor-mr-4">
                            <span class="btn-icon tutor-icon-zoom"></span>
                            <span><?php _e('Start Meeting', 'tutor-pro'); ?></span>
                        </button>
                        <button class="tutor-btn tutor-is-circle tutor-is-outline tutor-btn-ghost"  data-tutor-modal-target="tutor-zoom-meeting-modal-<?php echo $meeting->ID; ?>">
                            <span class="btn-icon tutor-icon-28 tutor-icon-pencil-line"></span>
                        </button>
                        <button class="tutor-btn tutor-is-circle tutor-is-outline tutor-btn-ghost" data-tutor-modal-target="<?php echo $id_string_delete; ?>">
                            <span class="btn-icon tutor-icon-28 tutor-icon-garbage-line"></span>
                        </button>
                    </div>

                    <!-- Edit Modal -->
                    <?php $zoom_object->tutor_zoom_meeting_modal_content($meeting->ID, $topic_id, $course_id, 'metabox'); ?>

                    <!-- Delete confirmation modal -->
                    <div id="<?php echo $id_string_delete; ?>" class="tutor-modal tutor-modal-is-close-inside-inner">
                        <span class="tutor-modal-overlay"></span>
                        <div class="tutor-modal-root">
                            <div class="tutor-modal-inner">
                                <button data-tutor-modal-close class="tutor-modal-close">
                                    <span class="tutor-icon-cross-filled"></span>
                                </button>
                                <div class="tutor-modal-body tutor-text-center">
                                    <div class="tutor-modal-icon">
                                        <img src="<?php echo tutor()->url; ?>assets/images/icon-trash.svg" />
                                    </div>
                                    <div class="tutor-modal-text-wrap">
                                        <h3 class="tutor-modal-title">
                                            <?php esc_html_e('Delete This Meeting?', 'tutor'); ?>
                                        </h3>
                                        <p>
                                            <?php esc_html_e('It can not be undone.', 'tutor'); ?>
                                        </p>
                                    </div>
                                    <div class="tutor-modal-footer">
                                        <div class="tutor-modal-btns tutor-btn-group">
                                            <button data-tutor-modal-close class="tutor-btn tutor-is-outline tutor-is-default">
                                                <?php esc_html_e('Cancel', 'tutor'); ?>
                                            </button>
                                            <button class="tutor-btn tutor-list-ajax-action" data-request_data='{"meeting_id":<?php echo $meeting->ID;?>,"action":"tutor_zoom_delete_meeting"}' data-delete_element_id="<?php echo $row_id; ?>">
                                                <?php esc_html_e('Yes, Delete This', 'tutor'); ?>
                                            </button>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>
        <div class="tutor-zoom-integration-card-footer tutor-py-20 tutor-px-12">
            <button class="tutor-btn tutor-btn-icon create-zoom-meeting-btn tutor-btn-md" data-tutor-modal-target="tutor-zoom-new-meeting">
                <span class="btn-icon tutor-icon-zoom"></span>
                <span><?php _e('Create a Zoom Meeting', 'tutor-pro'); ?></span>
            </button>
        </div>
    <?php endif; ?>
</div>

<?php
    (new \TUTOR_ZOOM\Zoom(false))->tutor_zoom_meeting_modal_content(0, 0, $course_id, 'metabox', 'tutor-zoom-new-meeting'); 
?>