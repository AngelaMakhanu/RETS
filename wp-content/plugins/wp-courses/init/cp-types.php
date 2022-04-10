<?php

    // Register Custom Post Type Course
    function wpc_register_course_cp(){

        $labels = array(
            'name'               => _x( 'Courses', 'post type general name'),
            'singular_name'      => _x( 'Course', 'post type singular name'),
            'add_new'            => _x( 'Add New', 'Course'),
            'add_new_item'       => __( 'Add New Course'),
            'edit_item'          => __( 'Edit Course' ),
            'new_item'           => __( 'New Course' ),
            'all_items'          => __( 'All Courses' ),
            'view_item'          => __( 'View Course' ),
            'search_items'       => __( 'Search Courses' ),
            'not_found'          => __( 'No Courses Found' ),
            'not_found_in_trash' => __( 'No Courses Found in the Trash' ), 
            'parent_item_colon'  => '',
            'menu_name'          => __('Courses', 'wp-courses'),
        );
        $args = array(
            'labels'                => $labels,
            'show_in_admin_bar'     => true,
            'menu_icon'             => null,
            'show_in_nav_menus'     => false,
            'publicly_queryable'    => true,
            'query_var'             => true,
            'can_export'            => true,
            'rewrite'               => true,
            'show_in_menu'          => '',
            'description'           => __('Enter a Course Description Here', 'wp-courses'),
            'public'                => true,
            'show_ui'               => true,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields'),
            'has_archive'           => true,
            'show_in_rest'          => true,
        );

        register_post_type('course', $args);
        flush_rewrite_rules(false);
    }
    add_action('init', 'wpc_register_course_cp');

    //Custom Messages for Custom Post Type Course
    function wpc_course_messages( $messages ) {
        global $post;
        $post_ID = get_the_id();
        $messages['course'] = array(
            0 => '', 
            1 => sprintf( __('Course updated. <a href="%s">View Course</a>'), esc_url( get_permalink($post_ID) ) ),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Course updated.'),
            5 => isset($_GET['revision']) ? sprintf( __('Course restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Course published. <a href="%s">View Course</a>'), esc_url( get_permalink($post_ID) ) ),
            7 => __('Course saved.'),
            8 => sprintf( __('Course submitted. <a target="_blank" href="%s">Preview Course</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __('Course scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Course</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Course draft updated. <a target="_blank" href="%s">Preview Course</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );
      return $messages;
    }
    add_filter( 'post_updated_messages', 'wpc_course_messages' );

    // Register Custom Post Type Lesson
    function wpc_register_lesson_cp(){
      $labels = array(
        'name'               => _x( 'Lesson', 'post type general name' ),
        'singular_name'      => _x( 'Lesson', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Lesson' ),
        'add_new_item'       => __( 'Add New Lesson' ),
        'edit_item'          => __( 'Edit Lesson' ),
        'new_item'           => __( 'New Lesson' ),
        'all_items'          => __( 'All Lessons' ),
        'view_item'          => __( 'View Lesson' ),
        'search_items'       => __( 'Search Lessons' ),
        'not_found'          => __( 'No Lessons found' ),
        'not_found_in_trash' => __( 'No lessons found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => __('Lessons', 'wp-courses'),
      );
      $args = array(
        'show_in_admin_bar'   => true,
        'menu_icon'           => null,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => true,
        'show_in_menu'        => '',
        'has_archive'         => true,
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'labels'        => $labels,
        'description'   => __('Enter a lesson description here', 'wp-courses'),
        'supports'      => array( 'title', 'editor', 'excerpt', 'comments', 'revisions', 'author', 'custom-fields', 'page-attributes'),
      );

      register_post_type( 'lesson', $args ); 
      flush_rewrite_rules( false );
    }
    add_action( 'init', 'wpc_register_lesson_cp' );

    //Custom Messages for Custom Post Type Lesson
    function wpc_lesson_messages_cp( $messages ) {
        global $post;
        $post_ID = get_the_id();
        $messages['lesson'] = array(
            0 => '', 
            1 => sprintf( __('Lesson updated. <a href="%s">View Lesson</a>'), esc_url( get_permalink($post_ID) ) ),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Lesson updated.'),
            5 => isset($_GET['revision']) ? sprintf( __('Lesson restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Lesson published. <a href="%s">View Lesson</a>'), esc_url( get_permalink($post_ID) ) ),
            7 => __('Lesson saved.'),
            8 => sprintf( __('Lesson submitted. <a target="_blank" href="%s">Preview Lesson</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __('Lesson scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Lesson</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Lesson draft updated. <a target="_blank" href="%s">Preview Lesson</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );
        return $messages;
    }
    add_filter( 'post_updated_messages', 'wpc_lesson_messages_cp' );

    // Register Custom Post Type Module
    function wpc_register_module_cp(){

        $labels = array(
            'name'               => _x( 'Module', 'post type general name' ),
            'singular_name'      => _x( 'Module', 'post type singular name' ),
            'add_new'            => _x( 'Add New', 'Module' ),
            'add_new_item'       => __( 'Add New Module' ),
            'edit_item'          => __( 'Edit Module' ),
            'new_item'           => __( 'New Module' ),
            'all_items'          => __( 'All Modules' ),
            'view_item'          => __( 'View Module' ),
            'search_items'       => __( 'Search Modules' ),
            'not_found'          => __( 'No Modules found' ),
            'not_found_in_trash' => __( 'No Modules found in the Trash' ), 
            'parent_item_colon'  => '',
            'menu_name'          => __('Modules', 'wp-courses'),
        );

        $args = array(
            'show_in_admin_bar'   => false,
            'menu_icon'           => null,
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => true,
            'exclude_from_search' => true,
            'has_archive'         => false,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'show_in_menu'        => '',
            'has_archive'         => true,
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'labels'              => $labels,
            'description'         => __('Enter a module description here', 'wp-courses'),
            'supports'            => array( 'title' ),
        );

        register_post_type( 'wpc-module', $args ); 
        flush_rewrite_rules( false );

    }
    add_action( 'init', 'wpc_register_module_cp' );
    //Custom Messages for Custom Post Type Module
    function wpc_module_messages_cp( $messages ) {
        global $post;
        $post_ID = get_the_id();
        $messages['wpc-module'] = array(
            0 => '', 
            1 => sprintf( __('Module updated. <a href="%s">View Module</a>'), esc_url( get_permalink($post_ID) ) ),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Module updated.'),
            5 => isset($_GET['revision']) ? sprintf( __('Module restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Module published. <a href="%s">View Module</a>'), esc_url( get_permalink($post_ID) ) ),
            7 => __('Module saved.'),
            8 => sprintf( __('Module submitted. <a target="_blank" href="%s">Preview Module</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __('Module scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Module</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Module draft updated. <a target="_blank" href="%s">Preview Module</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );
        return $messages;
    }
    add_filter( 'post_updated_messages', 'wpc_module_messages_cp' );

    // Register Custom Post Type Teacher
    function wpc_register_teacher_cp(){
      $labels = array(
        'name'               => _x( 'Teachers', 'post type general name'),
        'singular_name'      => _x( 'Teacher', 'post type singular name'),
        'add_new'            => _x( 'Add New', 'Teacher'),
        'add_new_item'       => __( 'Add New Teacher'),
        'edit_item'          => __( 'Edit Teacher' ),
        'new_item'           => __( 'New Teacher' ),
        'all_items'          => __( 'All Teachers' ),
        'view_item'          => __( 'View Teacher' ),
        'search_items'       => __( 'Search Teachers' ),
        'not_found'          => __( 'No Teachers Found' ),
        'not_found_in_trash' => __( 'No Teachers Found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => __('Teachers', 'wp-courses'),
        );
      $args = array(
        'show_in_admin_bar'   => true,
        'menu_icon'           => null,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => true,
        'show_in_menu'        => '',
        'has_archive'         => true,
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'labels'              => $labels,
        'description'         => __('Enter a Teacher Description Here', 'wp-courses'),
        'supports'            => array('title', 'editor', 'excerpt', 'page-attributes', 'thumbnail'),
        ); 

        register_post_type('teacher', $args);
        flush_rewrite_rules(false);
    }
    add_action('init', 'wpc_register_teacher_cp');
    //Custom Messages for Custom Post Type Teacher
    function wpc_teacher_messages( $messages ) {
        $permalink = get_permalink(get_the_ID());
        $messages['course'] = array(
            0 => '', 
            1 => sprintf( __('Teacher updated. <a href="%s">View Teacher</a>'), esc_url( $permalink ) ),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Teacher updated.'),
            5 => isset($_GET['revision']) ? sprintf( __('Teacher restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Teacher published. <a href="%s">View Teacher</a>'), esc_url( $permalink ) ),
            7 => __('Teacher saved.'),
            8 => sprintf( __('Teacher submitted. <a target="_blank" href="%s">Preview Teacher</a>'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
            9 => sprintf( __('Teacher scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Teacher</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( get_the_ID() ) ), esc_url( $permalink ) ),
            10 => sprintf( __('Teacher draft updated. <a target="_blank" href="%s">Preview Teacher</a>'), esc_url( add_query_arg( 'preview', 'true', $permalink ) )),  
        );
        return $messages;
    }
    add_filter( 'post_updated_messages', 'wpc_teacher_messages' );

?>