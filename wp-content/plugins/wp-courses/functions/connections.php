<?php

	/*
    $args = array(
    	'post_from'			=> 953,
        'post_to'           => 321,
        'connection_type'   => array('lesson-to-course', 'module-to-course'),
        'order_by'          => 'menu_order',
        'order'             => 'asc',
        'limit'				=> 10,
        'join'              => false,
        'join_on'			=> "post_to"
    );
	*/

    function wpc_get_connected($args){
        global $wpdb;
        $table_name = $wpdb->prefix . "wpc_connections";
        $posts_table = $wpdb->prefix . "posts";

        if(isset($args['join']) && $args['join'] === true){
            $join_on = isset($args['join_on']) ? $args['join_on'] : "post_from";
            $sql = "SELECT * FROM {$table_name} LEFT JOIN {$posts_table} ON {$posts_table}.ID={$table_name}.{$join_on} ";
        } else {
            $sql = "SELECT * FROM {$table_name} ";
        }

        if(isset($args['connection_type'])) {
        	$where = " WHERE ";
            $count = 1;
            $length = count($args['connection_type']);
            foreach($args['connection_type'] as $type) {
                $where .= "{$table_name}.connection_type = '{$type}' ";
                $where .= isset($args['post_from']) ? "AND {$table_name}.post_from = {$args['post_from']} " : '';
                $where .= isset($args['post_to']) ? "AND {$table_name}.post_to = {$args['post_to']} " : '';
                $where .= $count < $length ? ' OR ' : '';
                $count++;
            }
        } elseif(isset($args['post_from']) || isset($args['post_to'])) {
        	$where = " WHERE ";
            $where .= isset($args['post_from']) ? "{$table_name}.post_from = {$args['post_from']} " : '';
            $where .= isset($args['post_from']) && isset($args['post_to']) ? ' AND ' : '';
            $where .= isset($args['post_to']) ? "{$table_name}.post_to = {$args['post_to']} " : '';
        } else {
        	$where = '';
        }

        $order = isset($args['order_by']) ? " ORDER BY {$table_name}.{$args['order_by']} " : '';
        $order .= isset($args['order']) ? $args['order'] : '';
        $order .= isset($args['limit']) ? " LIMIT " . $args['limit'] : '';
        $sql = $sql . $where . $order;
        $results = $wpdb->get_results($sql);
        return empty($results) ? false : $results;
    }

    function wpc_get_connected_course_ids($lesson_id, $connection_type = 'lesson-to-course') {
        $course_ids = array();
        global $wpdb;
        $table_name = $wpdb->prefix . "wpc_connections";
        $sql = "SELECT post_to FROM $table_name WHERE post_from = %d AND connection_type = '{$connection_type}' ";
        $results = $wpdb->get_results(
            $wpdb->prepare(
                $sql,
                $lesson_id
            ), ARRAY_N
        );
        if(!empty($results)) {
            foreach($results as $result) {
                $course_ids[] = $result[0];
            }
            return $course_ids;
        } else {
            return array();
        }
    }    

    function wpc_get_first_connected_course($lesson_id, $connection_type = 'lesson-to-course') {
    	global $wpdb;
    	$table_name = $wpdb->prefix . "wpc_connections";
    	$sql = "SELECT post_to FROM $table_name WHERE post_from = %d AND connection_type = '{$connection_type}' LIMIT 1";
        $results = $wpdb->get_results(
            $wpdb->prepare(
                $sql,
                $lesson_id
            )
        );
        if(!empty($results)) {
            return $results[0]->post_to;
        } else {
            return false;
        }
    }

    function wpc_get_current_course_id(){
        $post_type = get_post_type();
        if(get_the_ID() !== false && get_post_type() == 'course'){
            $course_id = get_the_ID();
        } elseif(isset($_GET['course_id'])) {
            $course_id = (int) $_GET['course_id'];
        } elseif($post_type == 'lesson') {
            $course_id = wpc_get_first_connected_course($lesson_id, 'lesson-to-course');
        } elseif($post_type == 'wpc-quiz') {
            $course_id = wpc_get_first_connected_course($lesson_id, 'quiz-to-course');
        } else {
            $course_id = false;
        }
        return $course_id;
    }

    function wpc_get_previous_and_next_lesson_ids($lesson_id, $course_id) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wpc_connections';
        $posts_table = $wpdb->posts;

        $sql = "SELECT {$table_name}.post_from, {$posts_table}.ID, {$posts_table}.post_status FROM $table_name INNER JOIN $posts_table ON {$posts_table}.ID={$table_name}.post_from WHERE {$table_name}.menu_order < (SELECT menu_order FROM $table_name WHERE post_from = $lesson_id AND post_to = $course_id LIMIT 1) AND {$table_name}.connection_type = 'lesson-to-course' AND {$table_name}.post_to = $course_id AND {$posts_table}.post_status = 'publish' ORDER BY {$table_name}.menu_order DESC LIMIT 1";
        $results = $wpdb->get_results($sql);
        $prev_id = empty($results) ? false : $results[0]->post_from;

        $sql = "SELECT {$table_name}.post_from, {$posts_table}.ID, {$posts_table}.post_status FROM $table_name INNER JOIN $posts_table ON {$posts_table}.ID={$table_name}.post_from WHERE {$table_name}.menu_order > (SELECT menu_order FROM $table_name WHERE post_from = $lesson_id AND post_to = $course_id ORDER BY menu_order LIMIT 1) AND {$table_name}.connection_type = 'lesson-to-course' AND {$table_name}.post_to = $course_id AND {$posts_table}.post_status = 'publish' ORDER BY {$table_name}.menu_order ASC LIMIT 1";
        $sql = "SELECT {$table_name}.post_from, {$posts_table}.ID, {$posts_table}.post_status FROM $table_name INNER JOIN $posts_table ON {$posts_table}.ID={$table_name}.post_from WHERE {$table_name}.menu_order > (SELECT menu_order FROM $table_name WHERE post_from = $lesson_id AND post_to = $course_id ORDER BY menu_order LIMIT 1) AND {$table_name}.connection_type = 'lesson-to-course' AND {$table_name}.post_to = $course_id AND {$posts_table}.post_status = 'publish' ORDER BY {$table_name}.menu_order ASC LIMIT 1";
        $results = $wpdb->get_results($sql);
        $next_id = empty($results) ? false : $results[0]->post_from;

        return array(
            'prev_id' => $prev_id,
            'next_id'   => $next_id
        );
    }

    /*
    $args = array(
		'post_from'			=> (int),
		'post_to'			=> array(),
		'connection_type'	=> 'lesson-to-course'
        'exclude_from'      => array() // optional
	);
	*/

	function wpc_create_connections($args) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'wpc_connections';

        if(count($args['post_to']) < 1) {
            $sql = "DELETE FROM $table_name WHERE connection_type = %s AND post_from = %d";
            $wpdb->query( $wpdb->prepare(
                $sql,
                $args['connection_type'],
                $args['post_from']
            ) );
            return;
        }

        // $update_to = !empty($args['exclude_from']) ? array_intersect($args['post_to'], $args['exclude_from']) : false;
        $insert_to = !empty($args['exclude_from']) ? array_diff($args['post_to'], $args['exclude_from']) : $args['post_to'];
        $delete_to = !empty($args['exclude_from']) ? array_diff($args['exclude_from'], $args['post_to']) : $args['post_to'];

        //if(empty($args['exclude_from'])){
            $del_sql = "DELETE FROM $table_name WHERE connection_type = %s AND post_from = %d AND post_to = %d";
        //} else {
            //$del_sql = "DELETE FROM $table_name WHERE connection_type = %s AND post_from = %d";
        //}

        foreach($delete_to as $delete){
            $wpdb->query( $wpdb->prepare(
                $del_sql,
                $args['connection_type'],
                $args['post_from'],
                $delete
            ) );
        }

        foreach($insert_to as $insert) {
            $wpdb->insert(
                $table_name, array(
                    "post_from"        => $args['post_from'],
                    "post_to"          => $insert,
                    "connection_type"  => $args['connection_type'], 
                ), 
                array("%d", "%d", "%s")
            );
        }
	}

    function wpc_get_course_first_lesson_id( $course_id ){
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpc_connections';
        $posts_table = $wpdb->posts;
        $sql = "SELECT {$table_name}.post_from FROM $table_name INNER JOIN $posts_table ON {$posts_table}.ID={$table_name}.post_from WHERE {$table_name}.post_to = $course_id AND {$table_name}.connection_type = 'lesson-to-course' AND {$posts_table}.post_status = 'publish' ORDER BY {$table_name}.menu_order ASC LIMIT 1";
        $result = $wpdb->get_results($sql);
        if(isset($result[0])){
            return (int) $result[0]->post_from;
        } else {
            return false;
        }
    }

    function wpc_get_connected_teachers($course_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "wpc_connections";
        $sql = "SELECT post_to FROM $table_name WHERE post_from = {$course_id} AND connection_type = 'course-to-teacher'";
        $results = $wpdb->get_results($sql, ARRAY_N);
        if(!empty($results)) {
            foreach($results as $result) {
                $course_ids[] = $result[0];
            }
            return $course_ids;
        } else {
            return array();
        }
        return $results;
    }

?>