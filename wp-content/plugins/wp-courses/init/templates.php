<?php

    /*
    ** Use Custom Templates
    */

    add_filter( 'template_include', 'wpc_single_lesson_page_template', 99 );
    function wpc_single_lesson_page_template( $template )
    {
        if ( get_post_type() == 'lesson' && is_single() ) {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-single-lesson.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_archive_lesson_page_template', 99 );
    function wpc_archive_lesson_page_template( $template )
    {
        if ( is_post_type_archive( 'lesson' ) && get_post_type() == 'lesson' ) {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-archive-lesson.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_archive_course_page_template', 99 );
    function wpc_archive_course_page_template( $template )
    {
        if ( is_post_type_archive( 'course' ) && get_post_type() == 'course') {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-archive-course.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_archive_teacher_page_template', 99 );
    function wpc_archive_teacher_page_template( $template )
    {
        if ( is_post_type_archive( 'teacher' )) {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-archive-teacher.php';
            //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_single_course_page_template', 99 );
    function wpc_single_course_page_template( $template )
    {
        if ( get_post_type() == 'course' && is_single() ) {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-single-course.php';
            //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    add_filter( 'template_include', 'wpc_single_teacher_page_template', 99 );
    function wpc_single_teacher_page_template( $template )
    {
        if ( get_post_type() == 'teacher' && is_single() ) {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-single-teacher.php';
            //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }

    add_filter( 'template_include', 'wpc_course_category_page_template', 9 );
    function wpc_course_category_page_template( $template )
    {
        if ( is_tax() == 'course-category' && get_post_type() == 'course') {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/category-course-category.php';
            //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }

    // grab new single quiz template if old standalone quiz add-on is active
    add_filter( 'template_include', 'wpc_single_quiz_page_template_shim', 99 );
    function wpc_single_quiz_page_template_shim( $template )
    {
        if ( get_post_type() == 'wpc-quiz' && is_single() ) {
            $new_template = dirname_r( __FILE__, 2 ) . '/templates/wpc-single-quiz.php';
            if ( !empty( $new_template ) ) {
                return $new_template;
            } 
        } else {
            return $template;
        }
    }
    
?>