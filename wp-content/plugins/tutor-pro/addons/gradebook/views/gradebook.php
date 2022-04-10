<?php

/**
 * Grade Book
 *
 * @since v.1.4.2
 * @author themeum
 * @url https://themeum.com
 */

$gradebooks = tutor_utils()->get_gradebooks();
if ( ! tutor_utils()->count( $gradebooks ) ) {
	?>
	<div class="tutor-no-announcements">
		<div class="tutor-fs-6 tutor-fw-medium tutor-color-black"><?php _e( 'No grading system found.', 'tutor-pro' ); ?></div>
		<div class="tutor-fs-6 tutor-color-black-60 tutor-mt-12"> <?php _e( 'No grading system has been defined to manage student grades. Please contact instructor or site administrator.', 'tutor-pro' ); ?> </div>
	</div>
	<?php
	return;
}

$grades           = get_generated_gradebook( 'all', $course_id );
$final_grade      = get_generated_gradebook( 'final', $course_id );
$assignment_grade = get_assignment_gradebook_by_course( $course_id );
$quiz_grade       = get_quiz_gradebook_by_course( $course_id );
$final_stat       = tutor_generate_grade_html( $final_grade, null );

$icon_mapping = array(
	'quiz'       => 'tutor-icon-question-mark-circle-filled',
	'assignment' => 'tutor-icon-clipboard-line',
);

if ( ! $quiz_grade || ! tutor_utils()->count( $grades ) ) {
	tutor_utils()->tutor_empty_state( __( 'No Gradebook Data', 'tutor-pro' ) );
	return;
}
?>

<div class="tutor-gradebook">
	<div class="tutor-gradebook-finalgrade bg-primary-30 tutor-radius-6 tutor-border-p40">
		<div>
			<button class="tutor-btn-circle tutor-is-lg">
				<?php echo $final_stat['gradename']; ?>
			</button>
		</div>
		<div>
			<span class="tutor-fs-6 tutor-color-muted">Final Grade</span>
			<br />
			<span class="tutor-fs-6 tutor-fw-bold tutor-color-black">
				<?php echo $final_stat['gradepoint_only']; ?> <span class="tutor-fs-6">out of</span> <?php echo $final_stat['gradescale']; ?>
			</span>
		</div>
	</div>
</div>
	
<div class="tutor-gradebook-allgrades tutor-mt-24">
	<div class="tutor-gradebook-allgrades-head tutor-fs-6 tutor-color-black-60 tutor-py-3 tutor-px-8">
		<span>Title</span>
		<!-- <span>No of </span> -->
		<span>Total Grade</span>
		<span>Result</span>
	</div>
	<div class="tutor-gradebook-allgrades-body">
		<?php foreach ( $grades as $grade ) : ?>
			<?php $stat = tutor_generate_grade_html( $grade, null ); ?>
			<div class="tutor-gradebook-allgrades-item tutor-radius-6 tutor-border-sl30 bg-white tutor-py-3 tutor-px-8">
				<span class="tutor-fs-6 tutor-fw-medium  tutor-color-black" data-grade-title="Title">
					<?php
						$for       = strtolower( $grade->result_for );
						$permalink = get_permalink( $for === 'quiz' ? $grade->quiz_id : $grade->assignment_id );
						$title     = get_the_title( $for === 'quiz' ? $grade->quiz_id : $grade->assignment_id );

						echo '<a href="' . $permalink . '" target="_blank">' .
								( isset( $icon_mapping[ $for ] ) ? '<i class="' . $icon_mapping[ $for ] . ' tutor-mr-1"></i>' : '' ) .
								get_the_title( $grade->assignment_id )
							. '</a>';
					?>
				</span>

				<!-- <span class="tutor-fs-7 tutor-color-black-60" data-grade-title="No of">
					<span class="tutor-fs-7 tutor-fw-medium" data-grade-title="Total Grade">
						8
					</span>
					/100
				</span> -->
				<span class="tutor-fs-7 tutor-fw-medium tutor-color-black-60" data-grade-title="Total Grade">
					<span><?php echo $stat['gradepoint_only']; ?></span> out of <?php echo $stat['gradescale']; ?>
				</span>
				<span data-grade-title="Result">
					<button class="tutor-btn-circle tutor-is-outline tutor-no-hover tutor-is-sm">
						<?php echo $stat['gradename']; ?>
					</button>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
</div>
