<?php 

add_shortcode('lesson_count', 'wpc_lesson_count');

function wpc_lesson_count(){
	$args = array(
		'post_type' => 'lesson',
		'posts_per_page' => -1
	);
	$query = new WP_Query($args);
	return $query->post_count;
}

add_shortcode('course_count', 'wpc_course_count');

function wpc_course_count(){
	$args = array(
		'post_type' => 'course',
		'posts_per_page' => -1
	);
	$query = new WP_Query($args);
	return $query->post_count;
}

// list all courses

function wpc_courses(){

		ob_start();

		$wp_courses = new WPC_Courses();
		$courses_per_page = (int) get_option('wpc_courses_per_page'); ?>

		<div class="wpc-shortcode-container">
			<div class="wpc-shortcode-row">

				<div class="wpc-sidebar wpc-left-sidebar">
					<?php echo $wp_courses->get_course_category_list(); ?>
				</div>

				<?php

				$args = array(
					'post_type'			=> 'course',
					'order'				=> 'ASC',
					'orderby'			=> 'menu_order',
					'post_status'		=> 'publish',
					'paged'				=> ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1,
					'posts_per_page'	=> $courses_per_page,
				);

				$query = new WP_Query($args);

				if ( $query->have_posts() ) { ?>
					<div id="courses-wrapper" class="wpc-sidebar-content">

					<?php include 'templates/template-parts/course-filters.php'; ?>

					<?php while ( $query->have_posts() ) {
						$query->the_post();
						$user_id = get_current_user_id();
						$course_id = get_the_ID();
						$excerpt = get_the_excerpt($course_id);
						$course_video = wpc_get_video($course_id, 'course');
						$teachers = wpc_get_connected_teachers($course_id);
						$teacher_text = count($teachers) < 2 ? __('Teacher', 'wp-courses') : __('Teachers', 'wp-courses'); ?>
						<div class="course-container wpc-light-box">
								<?php if($course_video != false){
										echo '<div class="wpc-video-wrapper">' . $course_video . '</div>';
								} else{
									echo '<div class="wpc-video-wrapper">' . get_the_post_thumbnail($course_id, 'large') . '</div>';
								} ?>
								<div class="course-excerpt">
									<h2 class="course-title wpc-h2">
										<a href="<?php echo get_the_permalink($course_id); ?>">
											<?php echo get_the_title($course_id); ?>	
										</a>
									</h2>
									<?php echo wp_kses( $excerpt, 'post' ); ?>
								</div>
								<?php echo wp_kses( $wp_courses->get_start_course_button($course_id), 'post' ); ?>

								<div class="course-meta-wrapper">
									<div class="cm-item">
										<span><?php echo __('Level', 'wp-courses') . ": " . sanitize_title_with_dashes( $wp_courses->get_course_difficulty($course_id) ); ?>
										</span>
								</div>

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

										<?php echo wp_kses($wp_courses->get_progress_bar($course_id), 'post'); ?>
									<?php } ?>
								</div>
							</div>

					<?php } // end while

					$page_count = (int) wpc_course_count() / $courses_per_page;

					if ( $page_count > 1) {
					    $the_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					    $pagination = array(
					        'base' => @add_query_arg('paged','%#%'),
					        'format' => '?paged=%#%',
					        'mid-size' => 1,
					        'current' => $the_paged,
					        'total' => ceil($page_count),
					        'prev_next' => True,
					        'prev_text' => __( '<< Previous' ),
					        'next_text' => __( 'Next >>' )
					    ); ?>

					   	<br>

					   	<div class="wpc-paginate-links">
					   		<?php echo paginate_links( $pagination ); ?>
					   	</div>
					    
					<?php }

					$wpc_enable_powered_by = get_option('wpc_enable_powered_by');

					if($wpc_enable_powered_by == 'true') {
						echo '<div id="wpc-powered-by">' . esc_html( __("Courses Powered by", 'wp-courses') ) . ' ' . '<a href="https://wpcoursesplugin.com">WP Courses</a></div>';
					}
					wp_reset_postdata(); ?>

					</div>
				</div>
			</div>

			<div style="clear:both;"></div>

		<?php } else {
			esc_html_e('There are no courses', 'wp-courses') . '.';
		}
	return ob_get_clean();
}
// legacy shortcode
add_shortcode( 'courses', 'wpc_courses' );
// new shortcode with prefix
add_shortcode( 'wpc_courses', 'wpc_courses' );

// user page

function wpc_profile_page($atts){

	if(!is_user_logged_in() && !isset($atts['user_id'])){
		return '<div class="wpc-msg">' . esc_html__('You must be logged in to view your profile', 'wp-courses') . '.</div>';
	}

	$data = '';

	$user_id = get_current_user_id();
	$user = get_user_by('ID', $user_id);
	$avatar = get_avatar($user_id, 240);
	$last_viewed = wpc_get_last_tracked_lesson($user_id, $view = 0);
	$last_viewed_course_id = get_user_meta($user_id, 'wpc-last-viewed-course', true);
	$course_id = empty($last_viewed_course_id) ? wpc_get_first_connected_course($last_viewed) : $last_viewed_course_id;
	$title = get_the_title($last_viewed);
	$breadcrumb = wpc_get_breadcrumb($last_viewed, $course_id);
	$permalink = get_the_permalink($last_viewed);
	$permalink = wpc_course_id_to_url($permalink, $last_viewed_course_id);
	$post_type = get_post_type($last_viewed);
	$button_text = $post_type == 'lesson' ? esc_html__('View Lesson', 'wp-courses') : esc_html__('View Quiz', 'wp-courses');
	$heading_text = $post_type == 'lesson' ? esc_html__('Last Viewed Lesson', 'wp-courses') : esc_html__('Last Viewed Quiz', 'wp-courses');
	
	if( $last_viewed !== null ) {
		$prev_next_ids = wpc_get_previous_and_next_lesson_ids($last_viewed, $course_id);
	} else {
		$prev_next_ids = '';
	}

	$data .= '<div class="wpc-shortcode-container">';
		$data .= '<div class="wpc-shortcode-row" id="wpc-last-viewed-wrapper">';

			$data .= '<div class="wpc-sidebar wpc-sidebar-left">';
				$data .= '<div class="wpc-user-img">' . $avatar . '</div>';
				$data .= '<h3 class="wpc-username wpc-h3">' . $user->display_name . '</h3>';
			$data .= '</div>';

			$data .= '<div class="wpc-sidebar-content">';
				$data .= '<div class="wpc-tab-content">';

					if($last_viewed !== null) {

						$data .= '<h3 class="wpc-tab-content-header wpc-h3">' . $heading_text . '</h3>';
						$data .= wp_kses( $breadcrumb, 'post' );
						$data .= '<h3 class="wpc-h3">' . $title . '</h3>';
						$data .= '<div class="wpc-lesson-excerpt">' . get_the_excerpt($last_viewed) . '</div>';

						$data .= '<a class="wpc-button" href="' . $permalink . '">' . $button_text . '</a>';
						$data .= !empty((int) $prev_next_ids['prev_id']) ? '<a class="wpc-button" href="' . wpc_course_id_to_url(get_the_permalink((int) $prev_next_ids['prev_id']), $course_id) . '"><i class="fa fa-arrow-left"></i> ' . esc_html__('Previous Lesson', 'wp-courses') . '</a>' : '';
						$data .= !empty((int) $prev_next_ids['next_id']) ? '<a class="wpc-button" href="' . wpc_course_id_to_url(get_the_permalink((int) $prev_next_ids['next_id']), $course_id) . '">' . esc_html__('Next Lesson', 'wp-courses') . ' <i class="fa fa-arrow-right"></i></a>' : '';	
					} else {
						$data .= $user->display_name . ' ' . esc_html__("hasn't viewed any lessons yet.", "wp-courses");
					}

				$data .= '</div>';
			$data .= '</div>';
		$data .= '</div>';
	$data .= '</div>';

	$data .= '<div class="wpc-shortcode-container">';
		$data .= '<div class="wpc-shortcode-row">';

				$viewed_class = $completed_class = $progress_class = '';

				if(isset($_GET['wpc_view'])){
					$view = sanitize_title_with_dashes($_GET['wpc_view']);
					$viewed_class = $view == 'viewed' ? 'wpc-tab-active' : '';
					$completed_class = $view == 'completed' ? 'wpc-tab-active' : '';
					$progress_class = $view == 'course_progress' ? 'wpc-tab-active' : '';
				} else {
					$viewed_class = 'wpc-tab-active';
				}

				$id = get_the_ID();

				$tabs = '<a class="wpc-tab ' . $viewed_class . '" href="' . add_query_arg( array('wpc_view' => 'viewed' ), get_the_permalink($id) ) . '"><i class="fa fa-eye"></i> ' . esc_html( __('Viewed', 'wp-courses') ) . '</a>';

				$show_completed_lessons = get_option('wpc_show_completed_lessons');

				$tabs .= $show_completed_lessons == 'true' ? '<a class="wpc-tab ' . $completed_class . '" href="' . add_query_arg( array('wpc_view' => 'completed' ), get_the_permalink($id) ) . '"><i class="fa fa-check"></i>' .  esc_html( __('Completed', 'wp-courses') ) . '</a>' : ''
				;
				$tabs .= '<a class="wpc-tab ' . $progress_class . '" href="' . add_query_arg( array('wpc_view' => 'course_progress' ), get_the_permalink($id) ) . '"><i class="fa fa-bar-chart"></i> ' . esc_html( __('Progress', 'wp-courses') ) . '</a>';

				$tabs = apply_filters('wpc_profile_tabs', $tabs);

				$data .= $tabs;

				$data .= '<div class="wpc-tab-content">';

					$tab_content = '';

					if(isset($_GET['wpc_view'])){

						$view = sanitize_title_with_dashes($_GET['wpc_view']);

						if($view == 'viewed'){
							$tab_content .= wpc_get_lesson_tracking_table($user_id, 0);
						} elseif($view == 'completed'){
							$tab_content .= wpc_get_lesson_tracking_table($user_id, 1);
						} elseif($view == 'course_progress') {
							if(isset($_GET['course_id'])){
								$tab_content .= '<h3 class="wpc-tab-content-header wpc-h3" style="background: #fff;">' . get_the_title((int) $_GET['course_id']) . '</h3>';
								$tab_content .= '<a class="wpc-button" href="' . add_query_arg( array('wpc_view' => 'course_progress' ), get_the_permalink($id) ) . '" style="margin-bottom: 20px;"><i class="fa fa-arrow-left"></i> Back</a>';
								$tab_content .= wpc_get_lesson_navigation((int) $_GET['course_id'], $user_id);
							} else {
								$tab_content .= wpc_get_course_progress_table($user_id);
							}
						}

					} else {
						$tab_content .= wpc_get_lesson_tracking_table($user_id, 0);
					}

					$tab_content = apply_filters('wpc_profile_tab_content', $tab_content);

					$data .= $tab_content;

				$data .= '</div>';

				$content = '';

				$content = apply_filters('wpc_after_user_profile_content', $content);

				$data .= $content;

			$data .= '</div>';
		$data .= '</div>';

	$data .= '<div style="clear:both;"></div>';

	return $data;
}

add_shortcode('wpc_profile', 'wpc_profile_page');

?>