<?php 

$course_id = get_the_ID();

$teachers = wpc_get_connected_teachers($course_id);
$difficulty = $wpc_course->get_course_difficulty($course_id);
$user_id = get_current_user_id();
$teacher_text = count($teachers) < 2 ? __('Teacher', 'wp-courses') : __('Teachers', 'wp-courses');

?>

<div class="course-meta-wrapper">
	<?php if( $difficulty != '-1' && !empty( $difficulty ) ){ ?>
	<div class="cm-item">
		<span><?php echo esc_html__('Level', 'wp-courses') . ": " . $difficulty; ?>
		</span>
	</div>
	<?php } ?>
	<div class="cm-item teacher-meta-wrapper">
		<?php if( $teachers != '-1' && !empty($teachers)) { ?>
			<?php 

				if( is_array( $teachers ) ) { 
					$length = count($teachers);
					$count = 1;
					if(count($teachers) > 0 && $teachers[0] != -1){
						echo $teacher_text . ': ';
						foreach( $teachers as $teacher ) { ?>

							<?php $teacher_link = get_the_permalink( $teacher, false ); ?>
							
								<a href="<?php echo esc_url($teacher_link); ?>"><?php echo get_the_title( $teacher ); ?></a><?php echo $count < $length ? ', ' : ''; ?>

							<?php $count++; ?>

						<?php } // end foreach
					} // end if
				} else { ?>

					<?php $teacher_link = get_the_permalink( (int) $teachers, false ); ?>

						<a href="<?php echo esc_url( $teacher_link ); ?>">
							<?php echo get_the_title( $teachers ); ?>
						</a>

				<?php } ?>

			<?php } ?>
	</div>
	<?php if(is_user_logged_in()){ ?>
		<div class="cm-item">
			<span>
				<?php echo esc_html__('Viewed', 'wp-courses') . ": " . (int) wpc_get_percent_done($course_id, $user_id, $view = 0); ?>%
			</span>
		</div>
		<div class="cm-item">
			<span>
				<?php 
					$show_completed_button = get_option('wpc_show_completed_lessons');
					if($show_completed_button == 'true') {
						echo wp_kses($wpc_course->get_progress_bar($course_id), 'post');
					}
				?>
			</span>
		</div>
	<?php } ?>
</div>