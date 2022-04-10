<?php

	add_action( 'admin_footer', 'wpc_order_course_javascript' );
	function wpc_order_course_javascript() { ?>
		<script type="text/javascript" >
		jQuery(document).ready(function() {
			jQuery( ".course-list" ).sortable({
				    axis: 'y',
				    update: function (event, ui) {
				        var posts = [];
				        jQuery('.course-list li').each(function(key, value){
				        	posts.push({
				        		'postID': jQuery(this).attr('data-id'),
				        		'menuOrder': key,
				        	});
				        });
						var data = {
							'action': 'order_course',
							'posts': posts,
						};
						wpcShowAjaxIcon();
						jQuery.post(ajaxurl, data, function(response) {
							wpcHideAjaxIcon();
						});
				    }
			});
		});
		</script> <?php
	}
	add_action( 'wp_ajax_order_course', 'wpc_order_course_action_callback' );
	function wpc_order_course_action_callback(){
		global $wpdb;
	    foreach($_POST['posts'] as $post){
		    	$order = (int) $post['menuOrder'];
		    	$id = (int) $post['postID'];
				$wpdb->query(
				    $wpdb->prepare(
				        "UPDATE $wpdb->posts SET menu_order = %d WHERE ID = %d",
				        $order,
				        $id
				    )
				);
	    }
	    wp_die(); // required
	}

?>