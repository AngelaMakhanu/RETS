<div class="tutor-course-instructors-metabox-wrap <?php echo is_admin() ? 'tutor-p-16' : ''; ?>">
	<?php $instructors = tutor_utils()->get_instructors_by_course(isset($course_id) ? $course_id : 0); ?>

    <div class="tutor-course-available-instructors">
		<?php
        global $post;

        $instructor_crown_src = TUTOR_MT()->url.'assets/images/crown.svg';
        $delete_class = 'tutor-instructor-delete-btn';
        
		if (is_array($instructors) && count($instructors)){
			foreach ($instructors as $instructor){
                $authorTag = '';
				if ($post->post_author == $instructor->ID){
					$authorTag = '<img src="'.$instructor_crown_src.'"/>';
				}
                
                include TUTOR_MT()->path . '/views/user-card.php';
			}
		}
		?>
    </div>

    <button 
        data-tutor-modal-target="tutor_course_instructor_modal"
        type="button" 
        class="tutor-mt-32 tutor-btn tutor-btn-tertiary tutor-is-outline tutor-btn-md tutor-add-instructor-btn"> 
        <i class="tutor-icon-add-group-filled tutor-icon-24 tutor-mr-12"></i>
        <?php _e('Add Instructor', 'tutor'); ?> 
    </button>
</div>

<div class="tutor-modal" id="tutor_course_instructor_modal" data-course_id="<?php echo get_the_ID(); ?>">
    <span class="tutor-modal-overlay"></span>
    <div class="tutor-modal-root">
        <div class="tutor-modal-inner">
            <div class="tutor-modal-body tutor-text-center">
                <div>
                    <div class="instructor-modal-title">
                        <?php _e('Add Instructor', 'tutor'); ?>
                        <button data-tutor-modal-close class="tutor-modal-close">
                            <span class="tutor-icon-cross-filled" area-hidden="true"></span>
                        </button>
                    </div>
                </div>
                
                <div class="tutor-modal-body-alt modal-container tutor-bg-white">
                    <input type="text" class="tutor-form-control" placeholder="<?php _e( 'Search instructors...', 'tutor' ); ?>">
                    <div class="tutor-search-result tutor-mt-12"></div>
                    <div class="tutor-selected-result tutor-mt-16"></div>
                </div>

                <div class="tutor-instructor-buttons">
                    <button data-tutor-modal-close type="button" data-action="back" class="tutor-modal-close-btn tutor-btn tutor-btn-disable tutor-is-default">
                        <?php _e('Cancel', 'tutor'); ?>
                    </button>
                    <button type="submit" data-action="next" class="tutor-btn tutor-is-primary add_instructor_to_course_btn" disabled="disabled">
                        <?php _e('Save Changes', 'tutor'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>