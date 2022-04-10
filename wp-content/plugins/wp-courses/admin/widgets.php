<?php

// The Widget
Class WPC_New_Stuff_Widget extends WP_Widget {
	function __construct(){
		parent::__construct(
			// Base ID
			'WPC_New_Stuff_Widget',
			__('WP Courses Widget', 'wp-courses'),
			// Widget description
			array( 'description' => __('Displays a list of specific courses, lessons or teachers', 'wp-courses') )
		);
	}
	public function widget($args, $instance){

			$title = apply_filters('widget_title', $instance['title']);

			echo $args['before_widget'];

			if(!empty($title)){
				echo $args['before_title'] . esc_textarea( $title ) . $args['after_title'];
			}

			$query_args = array(
				'posts_per_page'	=> $instance['posts_per_page'],
				'post_type' 		=> $instance['post_type'],
				'orderby'			=> $instance['orderby'],
				'order'				=> $instance['order'],
				'post_status'		=> 'publish',
			);


			$query = new WP_Query($query_args);
			echo '<ul class="wpc-widget-ul">';

			if($query->have_posts()){

				$logged_in = is_user_logged_in();
				$user_id = get_current_user_id();
				$viewed_tracking = wpc_get_tracked_lessons_by_user($user_id, 0);
				$completed_tracking = wpc_get_tracked_lessons_by_user($user_id, 1);

				while($query->have_posts()){

					$query->the_post();

					$class = '';
					$icon = '';

					if($instance['post_type'] == 'lesson'){
						$pid = get_the_ID();

						if( wpc_has_done( $pid, $viewed_tracking ) ) {
							$icon = '<i class="fa fa-eye"></i>';
						}

						if( wpc_has_done( $pid, $completed_tracking) ) {
							$icon = '<i class="fa fa-check"></i>';
						}
					}

					echo '<li class="wpc-widget-li"><a href="'. get_the_permalink() . '" class="' . $class . '">' . $icon . ' ' . get_the_title() . '</a></li>';
				}
			}
			echo '</ul>';
			wp_reset_postdata();
			echo $args['after_widget'];
	
	}
	// Widget Backend
	public function form( $instance ) {

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'wpb_widget_domain' );
		}

		if( isset( $instance['posts_per_page'])) {
			$posts_per_page = $instance['posts_per_page'];
		} else {
			$posts_per_page = 10;
		}

		if( isset( $instance['post_type'])) {
			$post_type = $instance['post_type'];
		} else {
			$post_type = 'course';
		}

		if(isset($instance['order'])){
			$order = $instance['order'];
		} else {
			$order = 'ASC';
		}

		if(isset($instance['orderby'])){
			$orderby = $instance['orderby'];
		} else {
			$orderby = 'none';
		}

		if(isset($instance['course_id'])){
			$course_id = $instance['course_id'];
		} else {
			$course_id = 'none';
		}

		// Widget admin form
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e( 'Number of Lessons, Courses or Teachers to Show:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="number" value="<?php echo esc_attr( $posts_per_page ); ?>" />

		<?php 

			$style = ($post_type != 'lesson') ? 'display: none;' : '';

		?>

		<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Type:' ); ?></label>
		<select class="widefat wpc-widget-post-type-select" id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
			<option value="course" <?php if($post_type == 'course'){ echo 'selected'; } ?>><?php _e('Course'); ?></option>
			<option value="lesson" <?php if($post_type == 'lesson'){ echo 'selected'; } ?>><?php _e('Lesson'); ?></option>
			<option value="teacher" <?php if($post_type == 'teacher'){ echo 'selected'; } ?>><?php _e('Teacher'); ?></option>
		</select>

		<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order:' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
			<option value="ASC" <?php if($order == 'ASC'){ echo 'selected'; } ?>><?php _e('Ascending'); ?></option>
			<option value="DESC" <?php if($order == 'DESC'){ echo 'selected'; } ?>><?php _e('Descending'); ?></option>
		</select>

		<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By:' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
			<option value="none" <?php if($orderby == 'none'){ echo 'selected'; } ?>><?php _e('None'); ?></option>
			<option value="author" <?php if($orderby == 'author'){ echo 'selected'; } ?>><?php _e('Author'); ?></option>
			<option value="date" <?php if($orderby == 'date'){ echo 'selected'; } ?>><?php _e('Date'); ?></option>
			<option value="ID" <?php if($orderby == 'ID'){ echo 'selected'; } ?>><?php _e('ID'); ?></option>
			<option value="menu_order" <?php if($orderby == 'menu_order'){ echo 'selected'; } ?>><?php _e('Menu Order'); ?></option>
			<option value="name" <?php if($orderby == 'name'){ echo 'selected'; } ?>><?php _e('Name'); ?></option>
			<option value="rand" <?php if($orderby == 'rand'){ echo 'selected'; } ?>><?php _e('Random'); ?></option>
			<option value="title" <?php if($orderby == 'title'){ echo 'selected'; } ?>><?php _e('Title'); ?></option>
		</select>

		</p>
	<?php
	}
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	$instance['posts_per_page'] = ( ! empty( $new_instance['posts_per_page'] ) ) ? strip_tags( $new_instance['posts_per_page'] ) : '';
	$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
	$instance['order'] = ( ! empty( $new_instance['order'] ) ) ? strip_tags( $new_instance['order'] ) : '';
	$instance['orderby'] = ( ! empty( $new_instance['orderby'] ) ) ? strip_tags( $new_instance['orderby'] ) : '';
	$instance['course_id'] = ( ! empty( $new_instance['course_id'] ) ) ? strip_tags( $new_instance['course_id'] ) : '';
	return $instance;
	}
}
function wpc_new_widget() {
    register_widget( 'WPC_New_Stuff_Widget' );
}
add_action( 'widgets_init', 'wpc_new_widget' );
?>