<?php

	/*
	** Front End
	*/

	function wpc_get_video($id, $type = 'lesson'){

		$video = $type == 'lesson' ? get_post_meta( $id, 'lesson-video', true ) : get_post_meta( $id, 'course-video', true );
	
		if(empty($video)){
			$video = false;
		} elseif( strpos( $video, '[' ) !== false ){
			$video = do_shortcode($video);
		} elseif( strpos( $video, 'iframe' ) !== false || strpos( $video, '<video' ) !== false ){
			// it's an iframe or <video>, so return as is
			$video = $video;
		} elseif(strpos($video, 'youtu.be' )){
			// it's a YT video with shortened url
			$video = str_replace('youtube.be/', 'https://www.youtube.com/watch?v=', $video);
			$video = wp_oembed_get( $video );
		}elseif( strpos($video, 'youtube.com' ) || strpos($video, 'vimeo.com')) {
			// it's a youtube or vimeo video using a url 
			$video = wp_oembed_get( $video );
		} elseif( preg_match("/[a-z]/i", $video) || preg_match("/[A-Z]/i", $video )){
			// it's a YT video with code only (ie. CvL5Amq0e8w)
			$video = '<iframe class="wpc-video" id="video-iframe" width="560" height="315" src="https://www.youtube.com/embed/' . $video . '" frameborder="0" allowfullscreen></iframe>';
		} elseif( preg_match("/[a-z]/i", $video) == 0 || preg_match("/[A-Z]/i", $video) == 0 ){ 
			// it's not a YT video with code only (ie. CvL5Amq0e8w).  Assumed to be Vimeo.
			$video = '<iframe class="wpc-video" id="video-iframe" src="https://player.vimeo.com/video/' . $video . '" width="500" height="216" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
		}

		if(has_filter( 'wpc_lesson_video' )){
			$video = apply_filters( 'wpc_lesson_video', $video );
		}

		$video = wpc_sanitize_video($video);

		return $video;
		
	}

	function wpc_get_lesson_navigation($course_id, $user_id = 0){
		$data = '';
		$show_lesson_numbers = get_option('wpc_show_lesson_numbers');
		$count = 1;
		$args = array(
	        'post_to'           => $course_id,
	        'connection_type'   => array('lesson-to-course', 'module-to-course', 'quiz-to-course'),
	        'order_by'          => 'menu_order',
	        'order'             => 'asc',
	        'join'				=> true,
	        'join_on'			=> "post_from"
	    );
		$lessons = wpc_get_connected($args);
		if(!empty($lessons)){
			$data .= '<ul class="lesson-nav">';
			$this_post_id = get_the_ID();
			foreach($lessons as $lesson) {
				$post_id = $lesson->post_from;
				$restriction =  get_post_meta( $post_id, 'wpc-lesson-restriction', true );
				$show_lesson_nav_icons = get_option('wpc_show_lesson_nav_icons');
				$active = $this_post_id == $post_id ? 'active-lesson-button' : '';
				$display_count = $show_lesson_numbers ? $display_count = $count . ' - ' : ' ';

				if($show_lesson_nav_icons == 'true'){
					if( is_user_logged_in() ){

						$icon = '<i class="fa fa-play wpc-default-status"></i>';

						$status = wpc_get_lesson_status($user_id, $post_id);
						$viewed = (int) $status['viewed'];
						$completed = (int) $status['completed'];

						if($completed === 1){
							$icon = '<i class="fa fa-check wpc-default-status"></i>';
						} elseif($viewed === 1) {
							$icon = '<i class="fa fa-eye wpc-default-status"></i>';
						} else {
							$icon = '<i class="fa fa-play wpc-default-status"></i>';
						}

					} else {
						if($restriction == 'none'){
							$icon = '<i class="fa fa-play wpc-default-status"></i>';
						} else {
							$icon = '<i class="fa fa-lock wpc-default-status"></i>';
						}
					}
				} else {
					$icon = '';
				}
							
				if(has_filter( 'wpc_lesson_button_icon' )){
		            $icon = apply_filters( 'wpc_lesson_button_icon', $icon, $post_id );
		        }

		        $url = wpc_course_id_to_url(get_the_permalink($lesson->post_from), $course_id);

				if($lesson->connection_type == 'module-to-course' && $lesson->post_status == 'publish'){
					$data .= '<h3 class="wpc-module-title" data-module-id="' . (int) $lesson->post_from . '">' . get_the_title($lesson->post_from) . '</h3>';
				} elseif($lesson->connection_type == 'lesson-to-course' && $lesson->post_status == 'publish' || $lesson->connection_type == 'quiz-to-course' && $lesson->post_status == 'publish') {
					$data .= '<li><a class="lesson-button ' . $active . '" data-lesson-button-id="' . (int) $lesson->post_from . '" href="' . $url . '">' . $icon . $display_count . get_the_title($lesson->post_from) . '</a></li>';	
					$count++;
				}

			}
			$data .= '</ul>';

			// $module_exists = false;

			wp_reset_postdata();
		}

		return $data;
		
	}

	/*
	** Admin
	*/

	function wpc_course_multiselect($selected_ids, $name = "course-selection[]", $class = 'wpc-course-multiselect') {
		global $wpdb;
	    $sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
	    $results = $wpdb->get_results($sql); ?>

	    <select name="course-selection[]" class="<?php echo $class; ?>" multiple>
	    	<option value="-1" <?php echo in_array(-1, $selected_ids) ? 'selected' :''; ?>>None</option>
	    	<?php
	    		foreach($results as $result) {
	    			$selected = in_array($result->ID, $selected_ids) ? ' selected' : '';
	    			echo '<option value="' . (int) $result->ID . '" ' . $selected . '>' . get_the_title((int) $result->ID) . '</option>';
	    		}
	    	?>
	    </select>
	<?php }

	function wpc_course_select($course_id = null, $class = '', $none = true, $name = 'course-selection', $multiple = false) {
    	global $wpdb;
    	$sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
    	$results = $wpdb->get_results($sql);
    	$multiple = $multiple === true ? 'multiple' : '';
		$data = '<select name="' . $name . '" class="' . $class . '" ' . $multiple . '>';
		$data .= $none == true ? '<option value="-1">None</option>' : '';

		foreach($results as $result) {
			if( is_array( $course_id ) ) {

				if( in_array( $teacher_id, $course_id ) ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
			} else {
				if( $course_id == $teacher_id){
					$selected = 'selected="selected"';
				} else{
					$selected = '';
				}
			}
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

	function wpc_admin_lesson_list($course_id) {
		$args = array(
	        'post_to'           => $course_id,
	        'connection_type'   => array('lesson-to-course', 'module-to-course', 'quiz-to-course'),
	        'order_by'          => 'menu_order',
	        'order'             => 'ASC',
	        'join'				=> true,
	        'join_on'			=> 'post_from',
	    );

	    $lessons = wpc_get_connected($args);

	    echo '<ul class="lesson-list">';

	    if($lessons) {
		    foreach($lessons as $lesson) {

		    	$id = $lesson->post_from;
				$title = $lesson->post_title;
				$connection_type = $lesson->connection_type;
				$display_status = $lesson->post_status == 'draft' ? ' (draft)' : '';
				$link = get_edit_post_link($id);
				$post_type = $lesson->post_type;

				global $wpdb;
				$table_name = $wpdb->prefix . "wpc_connections";
				$sql = "SELECT post_from, post_to FROM $table_name WHERE post_to = $course_id AND connection_type = 'module-to-course'";
				$results = $wpdb->get_results($sql);

				$post_status = get_post_status($id);

				if($post_status == 'publish' || $post_status == 'draft' || $post_status == 'pending' || $post_status == 'private' || $post_status == 'future'){
					if($post_type == 'wpc-module' ) {
						echo '<li data-id="' . (int) $id . '" data-post-type="' . esc_attr($post_type) . '" data-connected-course-id="' . (int) $course_id . '" class="lesson-button wpc-order-lesson-list-lesson ui-sortable-handle wpc-module-button"><i class="fa fa-bars wpc-grab"></i><input  type="text" placeholder="Module Name" value="' . wp_kses($title, 'post') . '" class="wpc-module-title-input"><button type="button" class="wpc-delete-module button"><i class="fa fa-trash"></i></button></li>';
					} elseif($post_type == 'lesson' || $post_type == 'wpc-quiz') {
						echo '<li data-id="' . (int) $id . '"  data-post-type="' . esc_attr($post_type) . '" data-connected-course-id="' . (int) $course_id . '" class="lesson-button wpc-order-lesson-list-lesson"><i class="fa fa-bars wpc-grab"></i> ' . wp_kses($title, 'post') . $display_status . '<a style="float:right;" href="' . esc_url($link) . '"> (Edit)</a></li>';
					}
				}
		    }
		}
	   	echo '</ul>';
	}

?>