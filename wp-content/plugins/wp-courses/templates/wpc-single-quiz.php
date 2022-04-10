<?php get_header(); ?>
<div class="wpc-container">
	<div class="wpc-row">
		<div class="wpc-sidebar-content wpc-light-box">
			<h1 class="wpc-h1"><?php the_title(); ?></h1>
			<?php
			while(have_posts()){
				the_post();
				$lesson_id = get_the_ID();
				$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($lesson_id, 'quiz-to-course');
				echo wpc_get_breadcrumb( $lesson_id, $course_id );
		        $lesson_nav = wpc_get_lesson_navigation( $course_id );

			} ?>
			<div class="wpc-lesson-content"><?php the_content(); ?></div>
    	</div>
    	<div class="wpc-sidebar wpc-right-sidebar">
    		<div id="lesson-nav-wrapper">
    			<?php echo $lesson_nav; ?>
    		</div>
    	</div>
	</div>
</div>
<?php get_footer(); ?>