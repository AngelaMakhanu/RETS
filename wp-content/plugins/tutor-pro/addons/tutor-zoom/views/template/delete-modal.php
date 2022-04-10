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