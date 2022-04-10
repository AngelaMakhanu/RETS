<?php

	function wpc_change_lesson_restriction_javascript() { 
		$ajax_nonce = wp_create_nonce( "request-is-good-wpc" ); ?>
		<script type="text/javascript" >
		jQuery(document).ready(function() {
			jQuery('.lesson-restriction-radio').click(function(){
				var parent = jQuery(this).parent().parent();
				var data = {
					'security': "<?php echo $ajax_nonce; ?>",
					'action': 'wpc_change_restriction',
					'lesson_id': parent.attr('id').replace('post-', ''),
					'restriction': jQuery(this).val(),
				}
				wpcShowAjaxIcon();
				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
				});
				
			});
		});
		</script> <?php
	}

	add_action( 'wp_ajax_wpc_change_restriction', 'wpc_change_restriction_action_callback' );
	function wpc_change_restriction_action_callback(){
		check_ajax_referer( 'request-is-good-wpc', 'security' );
		$lesson_id = (int) $_POST['lesson_id'];
		$restriction = sanitize_title_with_dashes($_POST['restriction']);
		update_post_meta($lesson_id, 'wpc-lesson-restriction', $restriction);
		wp_die(); // required
	}

?>