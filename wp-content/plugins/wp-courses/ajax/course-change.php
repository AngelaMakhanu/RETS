<?php

	add_action( 'admin_footer', 'wpc_change_course_javascript' );
	function wpc_change_course_javascript() { 
		$ajax_nonce = wp_create_nonce( "request-is-good-wpc" ); ?>
		<script type="text/javascript" >
		jQuery(document).ready(function() {
			jQuery('.wpc-course-multiselect').change(function(){
				var parent = jQuery(this).parent().parent();
				var data = {
					'security'		: "<?php echo $ajax_nonce; ?>",
					'action'		: 'change_course',
					'course_id'		: jQuery(this).val(),
					'lesson_id'		: parent.attr('id').replace('post-', '')
				}
				wpcShowAjaxIcon();
				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
				});
				
			});
		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_course', 'wpc_change_course_action_callback' );
	function wpc_change_course_action_callback(){
		check_ajax_referer( 'request-is-good-wpc', 'security' );

		$lesson_id = (int) $_POST['lesson_id'];
		$course_ids = !empty($_POST['course_id']) ? array_map('intval', $_POST['course_id']) : array(-1);

		// don't allow selection of "none" as well as other connected courses
		if(count($course_ids) > 1	){
			$course_ids = array_diff($course_ids, array(-1));
		}

		$exclude_ids = wpc_get_connected_course_ids((int) $lesson_id);
		
		$args = array(
			'post_from'			=> $lesson_id,
			'post_to'			=> $course_ids,
			'connection_type'	=> 'lesson-to-course',
			'exclude_from'		=> $exclude_ids
		);
		
		wpc_create_connections($args);

		wp_die(); // required
	}

?>