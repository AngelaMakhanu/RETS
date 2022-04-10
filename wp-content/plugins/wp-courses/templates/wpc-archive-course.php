<?php get_header(); ?>
<?php $wpc_course = new WPC_Courses(); ?>
<?php 

	$post_id = get_the_ID(); 
	$course_video = get_post_meta($post_id, 'course-video', true); 

	if(has_post_thumbnail($post_id)) {
		$show_wrapper = true;
	} elseif(!empty($course_video)) {
		$show_wrapper = true;
	} else {
		$show_wrapper = false;
	}

	$style = $show_wrapper === true ? 'margin-bottom:20px;' : '';

?>

<div class="wpc-container">
	<div class="wpc-row">
		<div class="wpc-sidebar wpc-left-sidebar" id="wpc-sticky-sidebar">
			<?php echo $wpc_course->get_course_category_list(); ?>
		</div>
		<div id="courses-wrapper" class="wpc-sidebar-content">
			
			<?php include 'template-parts/course-filters.php'; ?>

			<?php
				if(have_posts()){
					while(have_posts()){
						the_post();
						echo '<div id="wpc-archive-course-' . $post_id . '" class="course-container wpc-light-box">';
							echo '<div class="wpc-video-wrapper" style="' . $style . '">';
								include 'template-parts/course-video.php';
							echo '</div>';
							include 'template-parts/course-details.php';
							include 'template-parts/course-meta.php';
						echo '</div>';
					}
					wp_reset_postdata();
					echo '<br><div class="wpc-paginate-links">' . paginate_links() . '</div>';

					$wpc_enable_powered_by = get_option('wpc_enable_powered_by');

					if($wpc_enable_powered_by == 'true') {
						echo '<div id="wpc-powered-by">' . esc_html__("Courses Powered by", 'wp-courses') . ' ' . '<a href="https://wpcoursesplugin.com">WP Courses</a></div>';
					}
				}
			?>
		</div>
	</div>
</div>
<?php get_footer(); ?>