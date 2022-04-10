<?php

add_filter( 'manage_edit-lesson_columns', 'wpc_lesson_columns', 9, 1 ) ;

function wpc_lesson_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => esc_html( __( 'Lesson', 'wp-courses' ) ),
		'course' => esc_html( __( 'Connected Course', 'wp-courses' ) ),
		'restriction'	=> esc_html( __( 'Restriction', 'wp-courses') ),
		'date' => esc_html( __( 'Date', 'wp-courses' ) )
	);
	return $columns;
}

add_action( 'manage_lesson_posts_custom_column', 'wpc_manage_lesson_columns', 10, 2 );

function wpc_manage_lesson_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'course' :

			if(is_plugin_active( 'wp-courses-woocommerce/wp-courses-woocommerce.php' )){
				echo "<div class='wpc-warning'>You cannot connect lessons to courses until you update to WP Courses Premium 3.0 or later.  <a href='https://wpcoursesplugin.com/lesson/upgrading-wp-courses-woocommerce-integration-for-3-0/?course_id=958'>More information can be found here</a>.<div>";
			} else {
    			$course_ids = wpc_get_connected_course_ids($post_id);
				wpc_course_multiselect($course_ids, "course-selection[]", 'wpc-course-multiselect');
			}

		break;
		case 'restriction' :
			$wpc_admin = new WPC_Admin();
			$radio_name = 'radio-' . $post_id;
			echo $wpc_admin->lesson_restriction_radio_buttons($post_id, $radio_name);
		break;
	}
}

?>