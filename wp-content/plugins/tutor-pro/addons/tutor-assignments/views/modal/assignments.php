<?php
$assignment_id = $post->ID;
?>

<form class="tutor_assignment_modal_form">
	<input type="hidden" name="action" value="tutor_modal_create_or_update_assignment">
	<input type="hidden" name="assignment_id" value="<?php echo $post->ID; ?>">
	<input type="hidden" name="current_topic_id" value="<?php echo $topic_id; ?>">

	<div class="assignment-modal-form-wrap">
		<!--	<div class="tutor-option-field-row">-->

		<?php do_action( 'tutor_assignment_edit_modal_form_before', $post ); ?>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Assignment Title', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<input type="text" name="assignment_title" class="tutor-form-control" value="<?php echo stripslashes( $post->post_title ); ?>"/>
			</div>
		</div>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Summary', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<?php
					wp_editor( $post->post_content, 'tutor_assignments_modal_editor', array( 'editor_height' => 150 ) );
				?>
			</div>
		</div>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Attachments', 'tutor-pro' ); ?></label>
			<div class="">
				<?php
					$attachments = tutor_utils()->get_attachments( $post->ID, '_tutor_assignment_attachments' );
					tutor_load_template_from_custom_path(
						tutor()->path . '/views/fragments/attachments.php',
						array(
							'name'        => 'tutor_assignment_attachments[]',
							'attachments' => $attachments,
							'size_below'  => false,
							'add_button'  => true,
						)
					);
					?>
			</div>
		</div>

		<?php do_action( 'tutor_assignment_edit_modal_form_after_attachments', $assignment_id ); ?>
		
		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Time Limit', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<div class="tutor-row">
					<div class="tutor-col-3">
						<input class="tutor-form-control" type="number" min="0" name="assignment_option[time_duration][value]" value="<?php echo tutor_utils()->get_assignment_option( $assignment_id, 'time_duration.value', 0 ); ?>">
					</div>
					<div class="tutor-col-6">
						<select class="tutor-form-select" name="assignment_option[time_duration][time]">
							<option value="weeks" <?php selected( 'weeks', tutor_utils()->get_assignment_option( $assignment_id, 'time_duration.time' ) ); ?>><?php _e( 'Weeks', 'tutor-pro' ); ?></option>
							<option value="days"  <?php selected( 'days', tutor_utils()->get_assignment_option( $assignment_id, 'time_duration.time' ) ); ?>><?php _e( 'Days', 'tutor-pro' ); ?></option>
							<option value="hours"  <?php selected( 'hours', tutor_utils()->get_assignment_option( $assignment_id, 'time_duration.time' ) ); ?>><?php _e( 'Hours', 'tutor-pro' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Total Points', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<input type="number" name="assignment_option[total_mark]" min="0" class="tutor-form-control" value="<?php echo tutor_utils()->get_assignment_option( $assignment_id, 'total_mark', 10 ); ?>">
				<p class="tutor-input-feedback tutor-has-icon">
					<i class="tutor-icon-info-circle-outline-filled tutor-input-feedback-icon"></i>
					<?php _e( 'Maximum points a student can score', 'tutor-pro' ); ?>
				</p>
			</div>
		</div>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Minimum Pass Points', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<input type="number" min="0" name="assignment_option[pass_mark]" class="tutor-form-control" value="<?php echo tutor_utils()->get_assignment_option( $assignment_id, 'pass_mark', 5 ); ?>">
				<p class="tutor-input-feedback tutor-has-icon">
					<i class="tutor-icon-info-circle-outline-filled tutor-input-feedback-icon"></i>
					<?php _e( 'Minimum points required for the student to pass this assignment.', 'tutor-pro' ); ?>
				</p>
			</div>
		</div>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Allow to upload files', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<input type="number" min="0" name="assignment_option[upload_files_limit]" class="tutor-form-control" value="<?php echo tutor_utils()->get_assignment_option( $assignment_id, 'upload_files_limit', 1 ); ?>">
				<p class="tutor-input-feedback tutor-has-icon">
					<i class="tutor-icon-info-circle-outline-filled tutor-input-feedback-icon"></i>
					<?php _e( 'Define the number of files that a student can upload in this assignment. Input 0 to disable the option to upload.', 'tutor-pro' ); ?>
				</p>
			</div>
		</div>

		<div class="tutor-mb-16">
			<label class="tutor-form-label"><?php _e( 'Maximum file size limit', 'tutor-pro' ); ?></label>
			<div class="tutor-input-group">
				<input type="number" min="0" name="assignment_option[upload_file_size_limit]" class="tutor-form-control" value="<?php echo tutor_utils()->get_assignment_option( $assignment_id, 'upload_file_size_limit', 2 ); ?>">
				<p class="tutor-input-feedback tutor-has-icon">
					<i class="tutor-icon-info-circle-outline-filled tutor-input-feedback-icon"></i>
					<?php _e( 'Define maximum file size attachment in MB', 'tutor-pro' ); ?>
				</p>
			</div>
		</div>

		<?php do_action( 'tutor_assignment_edit_modal_form_after', $assignment_id ); ?>
	</div>
</form>
