<?php get_header(); ?>

<?php while( have_posts() ){
	the_post(); ?>

<div class="wpc-container">
	<div class="wpc-row">
		<?php 
			$wpc_course = new WPC_Courses();
			$lesson_id = get_the_ID();
			$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($lesson_id);
			$show_progress_bar = get_option('wpc_show_completed_lessons');

			if(is_user_logged_in() && $show_progress_bar === 'true' && $course_id != 'none'){
				echo '<div class="single-lesson-course-progress">' . wp_kses( $wpc_course->get_progress_bar($course_id), 'post' ) . '</div>'; 
			} 

			$class = ($course_id != 'none' ) ? 'wpc-sidebar-content' : '';
		?>
		<div id="wpc-single-lesson-content" class="<?php echo $class; ?> wpc-light-box">
			<h1 class="wpc-lesson-title wpc-h1"><?php the_title(); ?></h1>
			<?php echo wp_kses( wpc_get_breadcrumb($lesson_id, (int) $course_id), 'post' ); ?>
			<?php
		        $restriction = get_post_meta( get_the_ID(), 'wpc-lesson-restriction', true );
		        $custom_logged_out_message = get_option('wpc_logged_out_message');
		        if($restriction == 'free-account' && !is_user_logged_in()){ ?>
		        	<p class="wpc-content-restricted wpc-free-account-required">
			        	<?php if(!empty($custom_logged_out_message)){
			        		echo wp_kses($custom_logged_out_message, 'post');
			        	} else { ?>
			            	<a href="<?php echo wp_login_url( get_permalink() );?>"><?php esc_html_e('Log in', 'wp-courses'); ?></a> <?php esc_html_e('or', 'wp-courses'); ?> <a href="<?php echo wp_registration_url(); ?>"><?php esc_html_e('Register', 'wp-courses'); ?></a> <?php esc_html_e('to view this lesson.', 'wp-courses');
			        	} ?>
		            </p>
		        <?php } else { ?>
		           	<div class="wpc-lesson-content"><?php the_content(); ?></div>
		        <?php } ?>
    	</div>

    	<?php if ($course_id != 'none') { ?>
	    	<div class="wpc-sidebar wpc-right-sidebar">
	    		<div id="lesson-nav-wrapper">
	    			<?php echo wpc_get_lesson_navigation($course_id, get_current_user_id()); ?>
	    		</div>
	    	</div>
    	<?php } ?>


	</div>

	<?php if(comments_open() == true) { ?>
		<div class="wpc-row">
		   		<div class="wpc-comments-wrapper wpc-light-box">
		    		<?php comments_template(); ?>
		    	</div>
		</div>
	<?php } ?>

</div>



<?php } ?>

<?php get_footer(); ?>