<?php  

	function wpc_track_lesson() {
		$post_type = get_post_type();
		$logged_in = is_user_logged_in();
		if($logged_in && $post_type == 'lesson' || $logged_in && $post_type == 'wpc-quiz') {
			global $wpdb;

			$post_id = get_the_ID();
			$user_id = get_current_user_id();
			$time_now = time();

			if($post_type == 'lesson'){
				$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($post_id);
			} else {
				$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : wpc_get_first_connected_course($post_id, 'quiz-to-course');
			}

			$table_name = $wpdb->prefix . 'wpc_tracking';
			$sql = "SELECT post_id from $table_name WHERE post_id = $post_id And user_id = $user_id";
			$results = $wpdb->get_results($sql);

			if($wpdb->num_rows <= 0) {
				$wpdb->insert(
		            $table_name, array(
		                "user_id"               => $user_id,
		                "post_id"               => $post_id,
		                "course_id"				=> $course_id,
		                "completed"             => 0,
		                "viewed_timestamp"    	=> $time_now,
		                "completed_timestamp"	=> 0
		            ), 
		            array("%d", "%d", "%d", "%d", "%d")
		        );
			} else {
				$wpdb->query($wpdb->prepare("UPDATE $table_name SET viewed_timestamp = %d WHERE user_id = $user_id AND post_id = $post_id AND course_id = $course_id", $time_now));
			}
			update_user_meta($user_id, 'wpc-last-viewed-course', $course_id);
		}
	}
	add_action("wp_head", "wpc_track_lesson", 10);

	function wpc_push_completed($user_id, $post_id, $status){
	    global $wpdb;
	    $table_name = $wpdb->prefix . "wpc_tracking";
	    $sql = "UPDATE $table_name SET completed_timestamp = %d, completed = %d WHERE user_id = $user_id AND post_id = $post_id";
	    $wpdb->query($wpdb->prepare($sql, time(), $status));
	}

	function wpc_get_lesson_status($user_id, $post_id) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wpc_tracking";
		$sql = "SELECT completed FROM $table_name WHERE $post_id = post_id AND user_id = $user_id LIMIT 1";
		$result = $wpdb->get_results($sql);
		if(!empty($result)) {
			$status = array(
				'viewed'	=> 1,
				'completed'	=> $result[0]->completed
			);
		} else {
			$status = array(
				'viewed'	=> 0,
				'completed'	=> 0
			);
		}
		return $status;
	}

	function wpc_get_last_tracked_lesson($user_id, $view = 0) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wpc_tracking";
		if($view === 0){
			$sql = "SELECT post_id FROM $table_name ORDER BY viewed_timestamp DESC LIMIT 1";
		} else {
			$sql = "SELECT post_id FROM $table_name WHERE completed = 1 ORDER BY completed_timestamp DESC LIMIT 1";
		}
		$result = $wpdb->get_results($sql);
		return $result = !empty($result) ? $result[0]->post_id : null;
	}

	function wpc_get_tracked_lessons_by_user($user_id, $view = 0) {
		global $wpdb;
		$table_name = $wpdb->prefix . "wpc_tracking";
		if($view === 0) {
			$sql = "SELECT * FROM $table_name WHERE user_id = $user_id ORDER BY viewed_timestamp DESC";
		} else {
			$sql = "SELECT * FROM $table_name WHERE user_id = $user_id AND completed = 1 ORDER BY completed_timestamp DESC";
		}
		$results = $wpdb->get_results($sql);
		return $results;
	}

	function wpc_get_viewed_lessons_per_day($days = 90, $view = 0) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpc_tracking";
        $view = $view === 0 ? 0 : 1;
        $view_column_timestamp = $view === 0 ? 'viewed_timestamp' : 'completed_timestamp';
        $sql = "SELECT COUNT(FROM_UNIXTIME($view_column_timestamp, '%Y-%m-%d')) as y, FROM_UNIXTIME($view_column_timestamp, '%Y-%m-%d') as x FROM $table_name WHERE $view_column_timestamp > 0 AND completed = {$view} GROUP BY FROM_UNIXTIME($view_column_timestamp, '%Y-%m-%d') ORDER BY $view_column_timestamp DESC LIMIT {$days}";
        $results = $wpdb->get_results($sql);
        $results = array_reverse($results);
        return $results;
    }

    function wpc_get_active_users($num = 25, $view = 0, $time_length = 604800) {
    	global $wpdb;
    	$table_name = $wpdb->prefix . "wpc_tracking";
    	$table_name_2 = $wpdb->prefix . "users";

    	$time_now = time();
        $time_start = $time_now - $time_length;

    	// get viewed
    	if($view === 0) {
    		$sql = "SELECT COUNT({$table_name}.post_id) as y, {$table_name_2}.user_login as x FROM $table_name INNER JOIN $table_name_2 ON {$table_name}.user_id={$table_name_2}.ID WHERE viewed_timestamp >= $time_start GROUP BY {$table_name}.user_id ORDER BY y DESC LIMIT $num";
    	} else {
    		// get completed
    		$sql = "SELECT  COUNT({$table_name}.post_id) as y, {$table_name_2}.user_login as x FROM $table_name INNER JOIN $table_name_2 ON {$table_name}.user_id={$table_name_2}.ID WHERE completed = {$view} AND completed_timestamp >= $time_start GROUP BY {$table_name}.user_id ORDER BY y DESC LIMIT $num";
    	}

    	$results = $wpdb->get_results($sql);
    	return $results;
    }

    function wpc_has_done($post_id, $tracking, $view = 0) {
		$done = false;
		foreach($tracking as $tracked){
			if($view === 1) {
				if($tracked->post_id == $post_id && $tracked->completed == $view){
					$done = true;
					break;
				} else {
					$done = false;
				}
			} else {
				if($tracked->post_id == $post_id){
					$done = true;
					break;
				} else {
					$done = false;
				}
			}
			
		}
		return $done;
	}

	/*
	** Calculations
	*/

	function wpc_get_percent_done($course_id, $user_id = null, $view = 0){

		if($course_id == -1) {
			return 0;
		}

		if($user_id == null){
			$user_id = get_current_user_id();
		}

		$args = array(
			'post_to'			=> $course_id,
			'connection_type'	=> array('lesson-to-course', 'quiz-to-course'),
			'join'				=> false,
		);

		$lessons = wpc_get_connected($args);

		$all_lessons_count = 0;

		if(!empty($lessons)){
			foreach($lessons as $lesson) {
				if(get_post_status($lesson->post_from) == 'publish'){
					$all_lessons_count++;
				}
			}
		}

		$count = 0;

		if($all_lessons_count === 0) {
			return 0;
		}

		foreach($lessons as $lesson) {
			$status = wpc_get_lesson_status($user_id, $lesson->post_from);
			$status = $view === 0 ? (int) $status['viewed'] : (int) $status['completed'];
			$status === 1 && get_post_status($lesson->post_from) == 'publish' ? $count++ : '';
		}

		$percent_done = $count > 0 ? ($count / $all_lessons_count) * 100 : 0;
		return (int) $percent_done;
	}

	function wpc_get_module_percent_done($module_id, $user_id, $view = 0) {
            global $wpdb;
            $table_name = $wpdb->prefix . "wpc_connections";
            $sql = "SELECT post_from FROM $table_name WHERE connection_type = 'lesson-to-module' AND post_to = $module_id";
            $results = $wpdb->get_results($sql, ARRAY_N);
            $all_lesson_ids_count = $wpdb->num_rows;
            $lesson_ids = array_map('end', $results);
            $lesson_ids = "'" . implode("', '", $lesson_ids) . "'";

            $table_name = $wpdb->prefix . "wpc_tracking";
            if($view === 0) {
				$sql = "SELECT post_id FROM $table_name WHERE post_id IN ( $lesson_ids ) AND user_id = $user_id";
            } else {
            	$sql = "SELECT post_id FROM $table_name WHERE post_id IN ( $lesson_ids ) AND user_id = $user_id AND completed = $view";
            }

            $results = $wpdb->get_results($sql);
            $tracked_lesson_ids = $wpdb->num_rows;

            return $tracked_lesson_ids === 0 || $all_lesson_ids_count === 0 ? 0 : ( $tracked_lesson_ids / $all_lesson_ids_count ) * 100;
	}

	// returns either the average percent completed or viewed depending upon the tracking data that's passed
	function wpc_get_average_percent( $view = 0, $count = 5, $time_length = 7889229 ) {
    	$time_now = time();
        $time_start = $time_now - $time_length;

		global $wpdb;
		$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
		$table_name = $wpdb->prefix . "wpc_connections";
		$tracking_table = $wpdb->prefix . "wpc_tracking";

		$course_titles = array();
		$all_percent = array();

	    $course_args = array(
	        'post_type'         => 'course',
	        'nopaging'          => true,
	        'post_status'       => 'publish',
	        'posts_per_page'    => -1,
	    );

	    $course_query = new WP_Query($course_args);

	    while($course_query->have_posts()) {
	    	$course_query->the_post();
	    	$id = get_the_ID();
	    	$course_titles[] = get_the_title();

	    	$sql = "SELECT post_from FROM $table_name WHERE connection_type = 'lesson-to-course' AND post_to = $id";
	    	$wpdb->get_results($sql);
	    	$total_connected_count = $wpdb->num_rows;

	    	$sql = $view === 0 ? "SELECT user_id FROM $tracking_table WHERE course_id = $id" : "SELECT user_id FROM $tracking_table WHERE course_id = $id AND completed = 1";
	    	$wpdb->get_results($sql);
	    	$total_tracked = $wpdb->num_rows;

	    	if($total_connected_count !== 0 && $total_tracked !== 0) {
	    		$avg_lessons_viewed = $total_tracked / $user_count;
	    		$avg_percent = $avg_lessons_viewed / $total_connected_count;
	    	} else {
	    		$avg_percent = 0;
	    	}

	        $all_percent[] = $avg_percent * 100;
	    }

	    array_multisort($all_percent, SORT_DESC, SORT_NUMERIC, $course_titles);
	    // limit number of courses
	    $all_percent = array_slice($all_percent, 0, 5);
	    $course_titles = array_slice($course_titles, 0, 5);
	    return array($course_titles, $all_percent);
	}

?>