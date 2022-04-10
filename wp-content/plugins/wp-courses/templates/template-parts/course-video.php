<?php 
$course_id = get_the_ID();
$course_video = wpc_get_video( $course_id, 'course' );
echo $course_video != false ? $course_video : get_the_post_thumbnail($course_id, 'large');
?>