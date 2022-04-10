<div>
	<?php
	if(is_archive()) { ?>
		<a href="<?php echo get_the_permalink(); ?>">
			<h2 class="wpc-course-title wpc-h2"><?php echo get_the_title(); ?></h2>
		</a>
	<?php } else { ?>
		<h1 class="wpc-course-title wpc-h1"><?php the_title(); ?></h1>
	<?php }
		do_action('wpc_after_course_title');
		if(is_archive()){
			echo '<div class="wpc-course-excerpt">';
			the_excerpt();
			echo '</div>';
		} else {
			the_content();
		}
	?>
</div>
<?php $wpc_course = new WPC_Courses(); ?>
<?php echo $wpc_course->get_start_course_button(get_the_ID()); ?>