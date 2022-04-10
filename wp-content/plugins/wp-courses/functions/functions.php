<?php

// dirname that's compatible with PHP 5.6
function dirname_r($path, $count=1){
    if ($count > 1){
       return dirname(dirname_r($path, --$count));
    }else{
       return dirname($path);
    }
}

function wpc_course_id_to_url($url, $course_id) {
    if(strpos($url, '?')) {
		$url = $url . '&course_id=' . $course_id;
	} else {
		$url = $url . '?course_id=' . $course_id;
	}

	return $url;
}

function wpc_get_unit($str, $default = '%'){
	if(strpos($str, '%') !== false){
		return '%';
	} elseif(strpos($str, 'px') !== false){
		return 'px';
	} elseif(strpos($str, 'em') !== false){
		return 'em';
	} else {
		return $default;
	}
}

function wpc_get_max_slider_value($str, $default = 100){
	$str = (int) preg_replace("/[^0-9.]/", "", $str);
	if($str >= $default){
		return $str;
	} else {
		return $default;
	}
}

function wpc_esc_unit($str, $default = '%'){
	$unit = $default;
	if(empty($str) || $str == 0) {
		return $str;
	}
	if(strpos($str, '%') !== false){
		$unit = '%';
	} elseif(strpos($str, 'px') !== false){
		$unit = 'px';
	} elseif(strpos($str, 'em') !== false){
		$unit = 'em';
	}
	$str = (int) preg_replace("/[^0-9.]/", "", $str);
	return $str . $unit;
}

function wpc_get_breadcrumb($lesson_id, $course_id = null){
	$show_breadcrumb = get_option('wpc_show_breadcrumb_trail');
	$user_id = get_current_user_id();
	$terms = get_the_terms($course_id, 'course-category');

	if($show_breadcrumb != true || empty($terms) && $course_id == 'none'){
		return;
	} 

	$term_link = empty($terms) ? '' : '<a href="' . get_term_link($terms[0]->term_id) . '">' . $terms[0]->name . '</a> > ';
	$course_link = $course_id != 'none' || $course_id != -1 ? '<a href="' . get_the_permalink($course_id) . '">' . get_the_title($course_id) . '</a> > ' : '';

	$lesson_link = wpc_course_id_to_url(get_the_permalink($lesson_id), $course_id);

	return '<div class="wpc-breadcrumb">' . $term_link . $course_link . '<a href="' . $lesson_link . '">' . get_the_title($lesson_id) . '</a></div>';
}

function wpc_get_course_progress_table($user_id){

	$page_link = get_the_permalink(get_the_ID());

	$user_id = (int) $user_id;

	$data = '';

	$wpc_courses = new WPC_Courses();

	$args = array(
		'post_type'			=> 'course',
		'posts_per_page'	=> -1,
		'paged'				=> false,
	);

	$query = new WP_Query($args);

	$data .= '<table class="widefat fixed wpc-sortable-table">';
	$data .= 	'<thead>
				<tr><th>' . esc_html__('Title', 'wp-courses') . '</th><th>' . esc_html__('Viewed Percent', 'wp-courses') . '</th><th>' . esc_html__('Completed Percent', 'wp-courses') . '</th></tr>
			</thead>';

		while($query->have_posts()){
			$query->the_post();

			$course_id = get_the_id();



			$link = is_admin() ? '?page=manage_students&student_id=' . $user_id . '&course_id=' . $course_id : add_query_arg( array('course_id' => $course_id, 'wpc_view'	=> 'course_progress' ), $page_link );

			$data .= '<tr>';
				$data .= '<td class="column-columnname">';
				$data .= '<strong>' . get_the_title() . '</strong><br><a href="' . esc_url($link) . '">(' . esc_html( __('view details') ) . ')</a>';
				$data .= '</td>';
				$data .= '<td class="column-columnname">' . $wpc_courses->get_progress_bar($course_id, $user_id, false, false) . '</td>';
				$data .= '<td class="column-columnname">' . $wpc_courses->get_progress_bar($course_id, $user_id, true, false) . '</td>';
			$data .= '</tr>';
		}

		$data .= 	'<tfoot>
				<tr><th>' . esc_html__('Title', 'wp-courses') . '</th><th>' . esc_html__('Viewed Percent', 'wp-courses') . '</th><th>' . esc_html__('Completed Percent', 'wp-courses') . '</th></tr>
		</tfoot>';


	$data .= '</table>';

	wp_reset_postdata(); 

	return $data;
}

function wpc_get_lesson_tracking_table($user_id, $view = 0){
	$data = '';
	$user = get_userdata($user_id);
	$tracked_lessons = wpc_get_tracked_lessons_by_user($user_id, $view);

	if($view == 0){
		$text = esc_html__('viewed', 'wp-courses');
	} else {
		$text = esc_html__('completed', 'wp-courses');
	}

	if(!empty($tracked_lessons)) {

			$data .= '<table class="widefat fixed wpc-sortable-table" cellspacing="0">';
		 		$data .= '<thead>
		 				<tr><th class="manage-column column-columnname" scope="col">' . esc_html__('Lesson Name', 'wp-courses') . '</th>
		 				<th class="manage-column column-columnname" scope="col">' . esc_html__('Course Name', 'wp-courses') . '</th>
		 				<th class="manage-column column-columnname" scope="col">' . esc_html__('Time', 'wp-courses') . '</th></tr></thead>';
		 		$data .= '<tbody>';

		 			$count = 0;

					foreach( $tracked_lessons as $viewed ){

						$lesson_id = $viewed->post_id;
						$connected_course_ids =  wpc_get_connected_course_ids($lesson_id);
						$post_status = get_post_status( $lesson_id );
						$display = $post_status === 'publish' ? 'true' : 'false';

						$class = $count % 2 == 1 ? ' alternate' : '';

						if( get_post_type( $lesson_id ) != 'wpc-quiz' && $post_status == 'publish' ){
							$data .= '<tr class="' . $class . '" data-id="' . $lesson_id . '">';
								$data .= '<td class="column-columnname">';

									$data .= '<a href="' . add_query_arg( array('course_id' => $viewed->course_id ), get_the_permalink($lesson_id) ) . '">' . get_the_title($lesson_id) . '</a>';

								$data .= '</td>';

								$data .= '<td class="column-columnname">';

								$data .= get_the_title( $viewed->course_id );

								$data .= '</td>';

								$data .= '<td class="column-columnname">';
									$data .= $view === 0 ? date('l jS F Y H:i', $viewed->viewed_timestamp) : date('l jS F Y H:i', $viewed->completed_timestamp);
								$data .= '</td>';

							$data .= '</tr>';
						}

						$count++;
					}

				$data .= '<tbody>';
				$data .= '<tfoot><tr><th class="manage-column column-columnname" scope="col">' . esc_html__('Lesson Name', 'wp-courses') . '</th>
						<th class="manage-column column-columnname" scope="col">' . esc_html__('Course Name', 'wp-courses') . '</th>
		 				<th class="manage-column column-columnname" scope="col">' . esc_html__('Time', 'wp-courses') . '</th></tr></tfoot>';
			$data .= '</table>';

	} else {
		$data .= $user->display_name . ' ' . esc_html__("hasn't", 'wp-courses') . ' ' . $text . ' ' . esc_html__("any lessons yet", 'wp-courses') . '.';
	}

	return $data;

}

?>