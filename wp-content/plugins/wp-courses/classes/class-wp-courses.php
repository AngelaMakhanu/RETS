<?php
	
	class WPC_Courses{
		public function get_progress_bar($course_id, $user_id = null, $view = 1, $text = true){
			$course_id = (int) $course_id;

			if($user_id == null){
				$user_id = get_current_user_id();
			}

			if($view == 1){
				$percent = wpc_get_percent_done($course_id, $user_id, 1);
				$text = $text == true ? esc_html__('Completed', 'wp-courses') : '';
				$class = "wpc-complete-progress";
				$icon = '<i class="fa fa-check"></i> ';
			} else {
				$percent = wpc_get_percent_done($course_id, $user_id, 0);
				$text = $text == true ? esc_html__('Viewed', 'wp-courses') : '';
				$class = "wpc-viewed-progress";
				$icon = '<i class="fa fa-eye"></i> ';
			}

			$data = '<div class="wpc-progress-bar"><div class="wpc-progress-bar-level ' . $class . '" style="width:' . (int) $percent . '%;"><div class="wpc-progress-bar-text">' . $icon . (int) $percent . '% ' . $text . '</div></div></div>';
			return $data;
		}
		public function get_course_category_list($class = ''){
			$data = '';
			$categories = get_terms('course-category');
			$cat = get_queried_object();
			$cat = isset($cat->slug) ? $cat->slug : '';
			$data .= '<div class="course-category-list">';
			$data .= '<h3 class="wpc-course-categories-header">' . esc_html( __('Course Categories', 'wp-courses') ) . '</h3>';
			$data .= '<ul class="wpc-course-categories-list">';
			$active = is_post_type_archive('course') == TRUE ? 'active' : '';
			$data .= '<li><a id="wpc-all-categories-button" href="' . get_post_type_archive_link( 'course' ) . '" class="wpc-button ' . $active . '">' . esc_html( __('All', 'wp-courses') ) . '</a></li>';
			if(!empty($categories)){
				foreach($categories as $category){

					$category->slug == $cat ? $active = 'active' : $active = '';

					$data .= '<li><a href="' . get_term_link($category) . '" class="wpc-button ' . $class . ' ' . $active . '">' . $category->name . '</a></li>';

				}
			} else {
				return 'There are no course categories.';
			}
			$data .= '</ul>';
			$data .= '</div>';

			if( has_filter( 'wpc_course_category_list' ) ){
				$data = apply_filters( 'wpc_course_category_list', $data );
			}

			return $data;
		}
		public function course_list($get = ''){
			$get = (int) $get;
			$course_args = array(
				'post_type'			=> 'course',
				'nopaging' 			=> true,
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order',
				'post_status'		=> 'publish',
			);
			$course_query = new WP_Query($course_args);
			$data = '';
			$data .= '<ul class="course-list">';
			while($course_query->have_posts()){
				$course_query->the_post();
				$data .= '<li class="lesson-button" data-id="' . get_the_ID() . '"><i class="fa fa-bars"></i> ' . get_the_title() . '</li>';
			}
			$data .= '</ul>';
			wp_reset_postdata();
			return $data;
		}
		public function get_course_difficulty($post_id){
			$data = '';
			$terms = wp_get_post_terms($post_id, 'course-difficulty');
			foreach($terms as $term){
				$data .= $term->name;
			}
			if(!empty($term)){
				return '<span class="difficulty-' . esc_attr( $term->slug ) . ' course-difficulty">' . $data . '</span>';
			} else {
				return '';
			}
		}
		public function get_start_course_button($course_id){
			$course_id = (empty($course_id)) ? get_the_ID() : $course_id;
			$first_lesson_id = (int) wpc_get_course_first_lesson_id($course_id);
			$course_link = get_the_permalink( $first_lesson_id );
			$course_link = wpc_course_id_to_url($course_link, $course_id);

			if(empty($course_link)){
				$button = '';
			} else {
				$button = '<a class="start-button wpc-button" href="' . esc_url( $course_link ) . '">' . esc_html( __('Start Course', 'wp-courses') ) . ' <i class="fa fa-arrow-right"></i></a>';
			}

			if(has_filter('wpc_start_course_button')){
				$button = apply_filters( 'wpc_start_course_button', $button );
			}

			return $button;
		}
	}
	class WPC_Lessons{
		public function __construct(){
			$this->post_id = get_the_ID();
		}
		public function get_lesson_attachments($lesson_id){

			$attachments = array();
			for($i = 1; $i<=3; $i++){
				$url = get_post_meta( $lesson_id, 'wpc-media-sections-' . $i, true );
				if(!empty($url)){
					$url = esc_url( $url );
					array_push($attachments, $url);
				}

			}
			return $attachments;
		}
	}
	class WPC_Tools{
		public function get_toolbar(){
			$user_id = get_current_user_id();
			$lesson_id = get_the_ID();
			$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($lesson_id);
			$links = wpc_get_previous_and_next_lesson_ids($lesson_id, $course_id);
			$next_link = wpc_course_id_to_url(get_permalink( $links['next_id']), $course_id);
			$prev_link = wpc_course_id_to_url(get_permalink( $links['prev_id']), $course_id);
			$show_completed_button = get_option('wpc_show_completed_lessons');
			$post_type = get_post_type();

			$data = '<div class="tools-container">';

				// show previous lesson button if exists
				if($links['prev_id'] !== false){
					$data .= '<a id="wpc-prev-lesson" class="wpc-button" href="' . $prev_link . '"><i class="fa fa-arrow-left"></i> ' . esc_html( __('Previous', 'wp-courses') ) . '</a>';
				}

				// show next lesson button if exists
				if($links['next_id'] !== false){
					$data .= '<a id="wpc-next-lesson" class="wpc-button" href="' . $next_link . '">' . esc_html( __('Next' , 'wp-courses') ) . ' <i class="fa fa-arrow-right"></i></a>';
				}

				if(is_user_logged_in() && $show_completed_button == 'true' && $post_type === 'lesson'){

					$completed = wpc_get_lesson_status($user_id, $lesson_id);
					$completed = $completed['completed'];

					if($completed == 1){
						$btn_text = esc_html__('Completed', 'wp-courses');
						$completed_icon = '<i class="fa fa-check-square-o"></i>';
					} else {
						$btn_text = esc_html__('Mark Completed', 'wp-courses');
						$completed_icon = '<i class="fa fa-square-o"></i>';
					}
					
					$data .= '<a id="wpc-completed-lesson-toggle" data-id="' . get_the_ID() . '" data-status="' . $completed . '" class="wpc-button">';
						$data .= $completed_icon . ' ';
						$data .= $btn_text;
					$data .= '</a>';

				}

				$attachments = new WPC_Lessons();
				$attachments = $attachments->get_lesson_attachments($lesson_id);
				
				if(!empty($attachments)) {
					$data .= '<div id="wpc-attachments-toggle" class="wpc-button">';
						$data .= '<i class="fa fa-file-image-o"></i>';
						$data .= ' ' . esc_html( __('Lesson Attachments', 'wp-courses') );
					$data .= '</div>';
				}

				$data .= '<div id="wpc-attachments-content" class="toolbar-content wpc-tab-content"><h3>' . esc_html( __('Lesson Attachments', 'wp-content') ) . '</h3>';
					foreach($attachments as $att){
						$data .= '<a class="toolbar-button" href="' . esc_url($att) . '">' . basename($att) . '</a>';
					}
				$data .= '</div>';

			$data .= '</div>';

			wp_reset_postdata();
			return $data;
		}
	}
?>