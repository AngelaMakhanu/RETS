<?php 
	if(is_tax('course-category')) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
		$url = get_term_link($term);
	} else {
		$url = get_post_type_archive_link( 'course' );
	}
?>

<form id="wpc-course-filters" method="get" action="<?php echo esc_url( $url ); ?>" style="margin-bottom: 20px;" >
	<label for="wpc-course-order-select"><?php _e('Sort by', 'wp-courses'); ?>: </label>
	<select id="wpc-course-order-select" name="order" onchange="this.form.submit()" class="wpc-input wpc-course-filter" style="padding: 4px;">
		<?php $value = sanitize_title_with_dashes($_GET['order']); ?>
		<option value="default" <?php echo $value == '' || $value == 'default' ? 'selected' : ''; ?>><?php _e('Default', 'wp-courses'); ?></option>
		<option value="newest" <?php echo $value == 'newest' ? 'selected' : ''; ?>><?php esc_html_e('Newest', 'wp-courses'); ?></option>
		<option value="oldest" <?php echo $value == 'oldest' ? 'selected' : ''; ?>><?php esc_html_e('Oldest', 'wp-courses'); ?></option>
		<option value="alphabetical" <?php echo $value == 'alphabetical' ? 'selected' : ''; ?>><?php esc_html_e('Alphabetical', 'wp-courses'); ?></option>
		<?php do_action('wpc-after-course-order-options'); ?>
	</select>

	<div class="wpc-course-filter-sep" style="display: none;"></div>

	<?php $show_course_search = get_option('wpc_show_course_search'); ?>

	<?php if( $show_course_search == 'true' ) { ?>

		<?php $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : ''; ?>
		<input type="search" name="search" placeholder="<?php _e('Search', 'wp-courses'); ?>" value="<?php echo isset( $search ) ? esc_textarea( $search ) : ''; ?>" class="wpc-input wpc-course-filter" id="wpc-course-search"/>
		<input type="submit" value="<?php esc_html_e('Search', 'wp-courses'); ?>" class="wpc-button" />

	<?php } ?>

	<?php if( isset( $_GET['course-category'] ) ) { ?>
		<input type="hidden" value="<?php echo esc_attr($_GET['course-category']); ?>" name="course-category"/>
	<?php } ?>

	<?php if( isset( $_GET['post_type'] ) ) { ?>
		<input type="hidden" value="<?php echo esc_attr($_GET['post_type']); ?>" name="post_type"/>
	<?php } ?>

	<?php do_action('wpc-after-course-archive-filters'); ?>
</form>