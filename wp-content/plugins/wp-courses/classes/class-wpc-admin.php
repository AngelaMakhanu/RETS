<?php
class WPC_Admin{
	public function lesson_restriction_radio_buttons($lesson_id, $name = 'wpc-lesson-restriction', $class = 'lesson-restriction-radio'){

		$lesson_id = (int) $lesson_id;

		$data = '';
		$lesson_restriction = sanitize_title_with_dashes( get_post_meta($lesson_id, 'wpc-lesson-restriction', true) );

		if(empty($lesson_restriction)){
			$lesson_restriction = 'none';
		}

		$data .= ' <button type="button" class="wpc-question-btn button" data-content="Anyone visiting your website can view this lesson.">?</button>';

		$data .= '<input id="wpc-none-radio" class="' . $class . '" type="radio" name="' . $name . '" value="none" ';
		$data .= checked($lesson_restriction, 'none', false);
		$data .= '/> None<br>';

		$data .= ' <button type="button" class="wpc-question-btn button" data-content="Users must have an account on your WordPress website and must be logged in to view the contents of this lesson.">?</button>';
		$data .= '<input id="wpc-free-account-radio" class="' . $class . '" type="radio" name="' . $name . '" value="free-account" ';

		$data .= checked($lesson_restriction, 'free-account', false);

		$data .= '/> Free Account';

		if(has_filter('wpc_lesson_restriction_radio_buttons')){
			$data = apply_filters( 'wpc_lesson_restriction_radio_buttons', $data, $lesson_id );
		}

		// Hidden membership field
		$data .= '<input style="display:none;" id="wpc-membership-radio" class="' . $class . '" type="radio" name="' . $name . '" value="membership" ';
		$data .= checked($lesson_restriction, 'membership', false);
		$data .= '/>';

		return $data;
	}
	public function get_course_dropdown($course_id = null, $class = '', $none = true, $name = 'course-selection'){

		$course_id = (int) $course_id;
		$name = sanitize_title_with_dashes($name);

    	global $wpdb;
    	$sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
    	$results = $wpdb->get_results($sql);

    	$data = '';

		$data .= '<select name="' . $name . '" class="' . $class . '">';
		$data .= $none == true ? '<option value="-1">None</option>' : '';

		foreach($results as $result) {
			if($result->ID == $course_id){
				$selected = 'selected';
			} else {
				$selected = '';
			}
			if(!empty($result->ID)){
				$data .= '<option value="' . (int) $result->ID . '" ' . $selected . '>' . $result->post_title . '</option>';
			}
		}

		$data .= '</select>';
		return $data;
	}
	public function get_course_list($get = ''){
		$course_args = array(
			'post_type'			=> 'course',
			'nopaging' 			=> true,
			'order'				=> 'ASC',
			'orderby'			=> 'menu_order',
			'post_status'		=> array('publish', 'draft'),
		);
		$course_query = new WP_Query($course_args);
		$data = '';
		$data .= '<ul class="course-list">';
		while($course_query->have_posts()){
			$course_query->the_post();
			$post_id = get_the_ID();
			$display_status = (get_post_status( $post_id ) == 'draft') ? ' (draft)' : '';
			$data .= '<li class="lesson-button" data-id="' . $post_id . '"><i class="fa fa-bars"></i> ' . get_the_title() . $display_status . '</li>';
		}
		$data .= '</ul>';
		wp_reset_postdata();
		return $data;
	}
}
?>