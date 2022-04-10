<?php
$submitted_assignment = tutor_utils()->get_assignment_submit_info( $assignment_submitted_id );
$max_mark             = tutor_utils()->get_assignment_option( $submitted_assignment->comment_post_ID, 'total_mark' );
$given_mark           = get_comment_meta( $assignment_submitted_id, 'assignment_mark', true );
$instructor_note      = get_comment_meta( $assignment_submitted_id, 'instructor_note', true );

/**
 * @since 1.8.0
 */
$assignment_page_url = admin_url( '/admin.php?page=tutor-assignments' );
$assignment_id       = $submitted_assignment->comment_post_ID;
?>
<div class="tutor-assignment-wrap">
	<div class="submitted-assignment-wrap">
		<a class="back-link tutor-color-black" href="<?php echo esc_url( $assignment_page_url ); ?>"><span>&leftarrow;</span> <?php esc_html_e( 'Back', 'tutor-pro' ); ?></a>
		<!--assignment-info-->
		<div class="tutor-assignment-info">
			<h1>
				<?php esc_html_e( get_the_title( $submitted_assignment->comment_post_ID ), 'tutor-pro' ); ?>
			</h1>
			<div class="tutor-assignment-info-menu-wrap">
				<div class="tutor-assignment-info-menu">
					<span class="tutor-color-black-60"><?php esc_html_e( 'Course', 'tutor-pro' ); ?>:</span>
					<span class="tutor-color-black-60"><?php esc_html_e( get_the_title( $submitted_assignment->comment_parent ) ); ?></span>
				</div>
				<div class="tutor-assignment-info-menu">
					<span><?php esc_html_e( 'Student', 'tutor-pro' ); ?>:</span>
					<span><?php esc_html_e( $submitted_assignment->comment_author ); ?></span>
				</div>
				<div class="tutor-assignment-info-menu">
					<span><?php esc_html_e( 'Submitted Date', 'tutor-pro' ); ?>:</span>
					<span><?php esc_html_e( date( 'F j, Y, g:i a', strtotime( $submitted_assignment->comment_date ) ), 'tutor-pro' ); ?></span>
				</div>
			</div>
		</div>
		<!--assignment-info end-->
	</div> <!-- submitted-assignment-wrap -->
	<!--assignment details-->
	<div class="tutor-assignment-details-wrap">
		<div class="tutor-assignment-details">
			<div class="assignment-details">
			<!-- <div class="loading-spinner"></div> -->
				<div class="tutor-fs-6 tutor-fw-medium tutor-color-black tutor-mb-24">
					<?php esc_html_e( 'Assignment', 'tutor-pro' ); ?>
				</div>
				<div class="tutor-fs-6 tutor-color-black-60 tutor-entry-content tutor-mb-lg-60 tutor-mb-40">
					<?php
						$context      = 'post';
						$allowed_html = wp_kses_allowed_html( $context );
						echo wp_kses( wp_unslash( $submitted_assignment->comment_content ), $allowed_html );
					?>
				</div>
			</div>
			<?php
			$attached_files = get_comment_meta( $submitted_assignment->comment_ID, 'uploaded_attachments', true );
			if ( $attached_files ) {
				$attached_files = json_decode( $attached_files, true );
				if ( tutor_utils()->count( $attached_files ) ) {
					?>
				<div class="assignment-files">
					<div class="tutor-fs-6 tutor-fw-medium tutor-color-black tutor-mb-24">
						<?php esc_html_e( 'Assignment File(s)', 'tutor-pro' ); ?>
					</div>
					<div class="tutor-assignment-files">
						<?php
							$upload_dir     = wp_get_upload_dir();
							$upload_baseurl = trailingslashit( tutor_utils()->array_get( 'baseurl', $upload_dir ) );
							foreach ( $attached_files as $attached_file ) {
								?>
								<div class="uploaded-files">
									<a href="<?php echo esc_url( $upload_baseurl . tutor_utils()->array_get( 'uploaded_path', $attached_file ) ); ?>" target="_blank"><?php esc_html_e( tutor_utils()->array_get( 'name', $attached_file ) ); ?> <i class="tutor-icon-download-line"></i></a>
								</div>
								<?php
							}
						?>
					</div>
				</div>
				<?php
				}
			}
			?>
		</div>
		<div class="tutor-assignment-evaluation">
			<div class="tutor-fs-6 tutor-fw-medium tutor-color-black tutor-mb-24">
				<?php esc_html_e( 'Evaluation', 'tutor-pro' ); ?>
			</div>
			<form action="" method="post" class="tutor-form-submit-through-ajax" data-toast_success_message="<?php esc_attr_e( 'Assignment evaluated', 'tutor-pro' ); ?>">
				<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
				<input type="hidden" value="tutor_evaluate_assignment_submission" name="tutor_action"/>
				<input type="hidden" value="<?php echo esc_attr( $assignment_submitted_id ); ?>" name="assignment_submitted_id"/>
				<?php
					$assignment_post_id = isset( $_GET['post-id'] ) ? $_GET['post-id'] : 0;
				?>
				<input type="hidden" name="assignment_post_id" value="<?php echo esc_attr( $assignment_post_id ); ?>">
				<div class="tutor-form-group">
					<label for="evaluate_assignment_mark" class="tutor-form-label"><?php esc_html_e( 'Your Points', 'tutor-pro' ); ?></label>
					<div class="tutor-assignment-mark-desc">
						<input type="text" class="tutor-small-input" id="evaluate_assignment_mark" name="evaluate_assignment[assignment_mark]" value="<?php echo $given_mark ? esc_attr( $given_mark ) : 0; ?>" pattern="[0-9]+" title="<?php esc_attr_e( 'Only number is allowed', 'tutor-pro' ); ?>" required>
						<div class="desc">
							<?php echo sprintf( __( 'Evaluate this assignment out of %s', 'tutor-pro' ), "{$max_mark}" ); ?>
						</div>
					</div>
				</div>
				<div class="tutor-form-group">
					<label for="evaluate_assignment_instructor" class="tutor-form-label">
						<?php esc_html_e( 'Write a feedback', 'tutor-pro' ); ?>
					</label>
					<textarea name="evaluate_assignment[instructor_note]" id="evaluate_assignment_instructor" rows="6"><?php esc_html_e( $instructor_note ); ?></textarea>
				</div>
				<div class="tutor-form-group">
					<button type="submit" class="tutor-btn tutor-btn-wordpress tutor-btn-lg"><?php esc_html_e( 'Evaluate this submission', 'tutor-pro' ); ?></button>
				</div>
			</form>
		</div>
		<!--assignment evaluation end-->
	</div>
	<!--assignment details-->
</div> <!--wrap end -->
<style>
#wpbody-content .notice {
	margin-top: 10px !important;
	margin-bottom: 10px !important;
}
</style>