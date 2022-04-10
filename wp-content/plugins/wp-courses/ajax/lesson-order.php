<?php

	// Function for saving lesson order and modules
	function update_lesson_order_and_meta($posts){
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpc_connections';

		foreach($posts as $post) {
		    $sql3 = "DELETE FROM $table_name WHERE connection_type = 'lesson-to-module' AND post_from = {$post['postID']}";
			$wpdb->query($sql3);
		}

		$current_module = null;
	    foreach($posts as $post){
	    	$id = (int) $post['postID'];
	    	$course_id = (int) $post['courseID'];
	    	$order = (int) $post['menuOrder'];
	    	$postType = sanitize_title_with_dashes( $post['postType'] );
	    	if($postType == 'lesson' || $postType == 'wpc-quiz') {
				$wpdb->query(
				    $wpdb->prepare(
				        "UPDATE $table_name SET menu_order = %d WHERE connection_type IN ('lesson-to-course', 'quiz-to-course') AND post_from = %d AND post_to = %d",
				        $order,
				        $id,
				        $course_id
				    )
				);
				// connect lessons to module
				$sql = "INSERT INTO $table_name (post_from, post_to, connection_type) VALUES (%d, %d, 'lesson-to-module')";
				$wpdb->query(
					$wpdb->prepare(
						$sql,
						$id,
						$current_module
					)
				);
	    	} elseif($postType == 'wpc-module'){
				$current_module = $id;
				// update module to course order
				$sql = "UPDATE $table_name SET menu_order = %d WHERE connection_type = 'module-to-course' AND post_from = %d AND post_to = %d";
				$wpdb->query(
					$wpdb->prepare(
						$sql,
						$order,
						$current_module,
						$course_id
					)
				);
			}
	    }
	}
	add_action( 'admin_footer', 'wpc_action_order_lessons_javascript' );

	// Ajax for saving lesson ordering
	function wpc_action_order_lessons_javascript() { ?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			jQuery( ".lesson-list" ).sortable({
			    axis: 'y',
			    update: function (event, ui) {
					var data = {
						'action': 'order_lessons',
						'posts': wpcLessonTableData(),
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
	add_action( 'wp_ajax_order_lessons', 'wpc_order_lessons_action_callback' );

	function wpc_order_lessons_action_callback(){

		update_lesson_order_and_meta($_POST['posts']);

	    wp_die(); // required
	}

	// Ajax for adding modules
	add_action( 'admin_footer', 'wpc_action_add_module_javascript' );

	function wpc_action_add_module_javascript() { 
			if( isset($_GET['course-selection']) ){
				$course_id = (int) $_GET['course-selection'];
			} elseif(isset($_GET['post'])){
				$course_id = (int) $_GET['post'];
			} else {
				$course_id = 'null';
			}
		?>
		<script type="text/javascript" >
		<?php $ajax_nonce = wp_create_nonce( "wpc-add-module-nonce" ); ?>
		jQuery(document).ready(function($) {
			jQuery('#wpc-add-module').click(function(){

				var data = {
					'security'	: "<?php echo $ajax_nonce; ?>",
					'action'	: 'add_module',
					'course_id'	: "<?php echo (int) $course_id; ?>",
					'posts'		: wpcLessonTableData(),
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					// Add the module.
					jQuery('.lesson-list').prepend('<li data-id="' + response + '" data-post-type="wpc-module" data-connected-course-id="<?php echo (int) $course_id; ?>" class="lesson-button wpc-order-lesson-list-lesson ui-sortable-handle wpc-module-button"><i class="fa fa-bars wpc-grab"></i><input type="text" placeholder="Module Name" class="wpc-module-title-input"><button type="button" class="wpc-delete-module button"><i class="fa fa-trash"></i></button></li>');
					// Trigger save action in order to save new module and its meta.  This must be done because the module's ID is in the response and thus the module's order and meta cannot be saved in the first AJAX call.
					jQuery('#wpc-save-order').click();
					wpcHideAjaxIcon();
				});
				
			});
		});
		</script> <?php
	}

	add_action( 'wp_ajax_add_module', 'wpc_add_module_action_callback' );

	function wpc_add_module_action_callback(){
		check_ajax_referer( 'wpc-add-module-nonce', 'security' );
	    $post_ID = wp_insert_post(array(
	    	'post_title'	=> '',
	    	'post_type'		=> 'wpc-module',
	    	'post_status'	=> 'publish',
	    ));

	    $args = array(
			'post_from'			=> $post_ID,
			'post_to'			=> array((int) $_POST['course_id']),
			'connection_type'	=> 'module-to-course'
		);
	
		wpc_create_connections($args);
		update_lesson_order_and_meta( $_POST['posts'] );
	    echo json_encode( $post_ID );
	    wp_die(); // required
	}

	// ajax for saving lesson and module order and meta
	add_action( 'admin_footer', 'wpc_action_save_lesson_order_javascript' );

	function wpc_action_save_lesson_order_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-save-lesson-order-nonce" ); ?>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {
			jQuery('#wpc-save-order').click(function(){
				var data = {
					'security'	: "<?php echo $ajax_nonce; ?>",
					'action'	: 'save_lesson_order',
					'posts'		: wpcLessonTableData(),
				}
				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
				});
				
			});
		});
		</script> <?php
	}

	add_action( 'wp_ajax_save_lesson_order', 'wpc_save_lesson_order_action_callback' );

	function wpc_save_lesson_order_action_callback(){
		check_ajax_referer( 'wpc-save-lesson-order-nonce', 'security' );
		update_lesson_order_and_meta($_POST['posts']);
	    wp_die(); // required
	}


	// Ajax for deleting modules
	add_action( 'admin_footer', 'wpc_action_delete_module_javascript' );

	function wpc_action_delete_module_javascript() { 

		if(isset($_GET['course-selection'])){
			$course_id = isset($_GET['course-selection']);
		} if(get_the_ID() !== false) {
			$course_id = get_the_ID();
		} else {
			$course_id = 'null';
		}

		?>
		<script type="text/javascript" >
		<?php $ajax_nonce = wp_create_nonce( "wpc-delete-module-nonce" ); ?>
		jQuery(document).ready(function($) {
			jQuery(document).on('click', '.wpc-delete-module', function(){

				var clicked = jQuery(this);
				clicked.parent().remove();

				var data = {
					'security'	: "<?php echo $ajax_nonce; ?>",
					'action'	: 'delete_module',
					'module_id'	: clicked.parent().attr('data-id'),
					'course_id'	: <?php echo $course_id; ?>,
					'posts'		: wpcLessonTableData(),
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					// remove module from list
					wpcHideAjaxIcon();
				});
				
			});
		});
		</script> <?php
	}

	add_action( 'wp_ajax_delete_module', 'wpc_delete_module_action_callback' );

	function wpc_delete_module_action_callback(){
		check_ajax_referer( 'wpc-delete-module-nonce', 'security' );
		$module_id = (int) $_POST['module_id'];
		$course_id =  (int)	$_POST['course_id'];
		wp_delete_post($module_id);
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpc_connections';
		$sql = "DELETE FROM $table_name WHERE post_from = %d AND post_to = %d AND connection_type = 'module-to-course'";
		$wpdb->query(
			$wpdb->prepare(
				$sql,
				$module_id,
				$course_id
			)
		);
		$sql = "DELETE FROM $table_name WHERE connection_type = 'lesson-to-module' AND post_to = $module_id";
		$wpdb->query($sql);
		update_lesson_order_and_meta($_POST['posts']);
	    wp_die(); // required
	}

	// Ajax for renaming modules
	add_action( 'admin_footer', 'wpc_action_rename_module_javascript' );

	function wpc_action_rename_module_javascript() { ?>
		<script type="text/javascript" >
		<?php $ajax_nonce = wp_create_nonce( "wpc-module-title-nonce" ); ?>
		jQuery(document).ready(function($) {
			var typingTimer;
			var doneTypingInterval = 1000;

			jQuery(document).on('keyup', '.wpc-module-title-input', function(){
				clicked = jQuery(this);
			    clearTimeout(typingTimer);
			    if (clicked.val()) {
			        typingTimer = setTimeout(wpcDoneTyping, doneTypingInterval);
			    }
			});

			//finshed typing... Save
			function wpcDoneTyping () {
				var data = {
					'security'		: "<?php echo $ajax_nonce; ?>",
					'action'		: 'rename_module',
					'module_id'		: clicked.parent().attr('data-id'),
					'module_title'	: clicked.val(),
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
				});
			}

		});
		</script> <?php
	}

	add_action( 'wp_ajax_rename_module', 'wpc_rename_module_action_callback' );

	function wpc_rename_module_action_callback(){
		check_ajax_referer( 'wpc-module-title-nonce', 'security' );
		$my_post = array(
		  'ID'           => (int)$_POST['module_id'],
		  'post_title'   => sanitize_text_field( $_POST['module_title'] ),
		);
		wp_update_post( $my_post );
	    wp_die(); // required
	}

?>