<?php
/**
 * Report Reviews
 *
 * @package Report
 */

global $wpdb;

$reviewsCount = (int) $wpdb->get_var(
	"SELECT COUNT({$wpdb->comments}.comment_ID) 
	FROM {$wpdb->comments}
	INNER JOIN {$wpdb->commentmeta} 
	ON {$wpdb->comments}.comment_ID = {$wpdb->commentmeta}.comment_id
	INNER  JOIN {$wpdb->users}
	ON {$wpdb->comments}.user_id = {$wpdb->users}.ID
	AND meta_key = 'tutor_rating'"
);

$per_page     = tutor_utils()->get_option( 'pagination_per_page' );
$total_items  = $reviewsCount;
$current_page = isset( $_GET['paged'] ) ? $_GET['paged'] : 0;
$start        = max( 0, ( $current_page - 1 ) * $per_page );

$course_query = '';
if ( ! empty( $_GET['course_id'] ) ) {
	$course_id    = sanitize_text_field( $_GET['course_id'] );
	$course_query = "AND {$wpdb->comments}.comment_post_ID =" . $course_id;
}
$user_query = '';
if ( ! empty( $_GET['user_id'] ) ) {
	$user_id    = sanitize_text_field( $_GET['user_id'] );
	$user_query = "AND {$wpdb->comments}.user_id =" . $user_id;
}

$reviews = $wpdb->get_results(
	"SELECT {$wpdb->comments}.comment_ID, 
	{$wpdb->comments}.comment_post_ID, 
	{$wpdb->comments}.comment_author, 
	{$wpdb->comments}.comment_author_email, 
	{$wpdb->comments}.comment_date, 
	{$wpdb->comments}.comment_content, 
	{$wpdb->comments}.user_id, 
	{$wpdb->commentmeta}.meta_value as rating,
	{$wpdb->users}.display_name 
	FROM {$wpdb->comments}
	INNER JOIN {$wpdb->commentmeta} 
	ON {$wpdb->comments}.comment_ID = {$wpdb->commentmeta}.comment_id {$course_query} {$user_query}
	INNER  JOIN {$wpdb->users}
	ON {$wpdb->comments}.user_id = {$wpdb->users}.ID
	AND meta_key = 'tutor_rating' ORDER BY comment_date DESC LIMIT {$start},{$per_page} ;"
);

?>
<div id="tutor-report-reviews" class="tutor-report-common tutor-pr-20">
	<div class="tutor-ui-table-wrapper">
		<table class="tutor-ui-table tutor-ui-table-responsive tutor-ui-table-report-tab-review" id="tutor-admin-reviews-table">
			<thead>
				<tr>
					<th class="tutor-table-rows-sorting">
						<div class=" tutor-align-items-center tutor-d-flex">
							<span class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Student', 'tutor-pro' ); ?>
							</span>
							<span class="tutor-icon-ordering-a-to-z-filled a-to-z-sort-icon"></span>
						</div>
					</th>
					<th>
						<span class="tutor-fs-7 tutor-color-black-60">
							<?php esc_html_e( 'Date', 'tutor-pro' ); ?>
						</span>
					</th>
					<th>
						<span class="tutor-fs-7 tutor-color-black-60">
							<?php esc_html_e( 'Course', 'tutor-pro' ); ?>
						</span>
					</th>
					<th>
						<span class="tutor-fs-7 tutor-color-black-60">
							<?php esc_html_e( 'Feedback', 'tutor-pro' ); ?>
						</span>
					</th>
					<th>
						<span></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( is_array( $reviews ) && count( $reviews ) ) : ?>
					<?php foreach ( $reviews as $review ) : ?>
					<tr>
						<td data-th="<?php esc_html_e( 'Student', 'tutor-pro' ); ?>" class="student">
							<div class="td-avatar">
									<?php echo get_avatar( $review->user_id, 50 ); ?>
									<span class="tutor-fs-6 tutor-fw-medium  tutor-color-black tutor-ws-nowrap">
										<?php echo esc_html( $review->display_name ); ?>
									</span>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=tutor_report&sub_page=students&student_id=' . $review->user_id ) ); ?>" class="tutor-d-flex">
									<span class="tutor-icon-detail-link-filled tutor-color-black"></span>
								</a>
							</div>
						</td>
						
						<td data-th="<?php esc_html_e( 'Date', 'tutor-pro' ); ?>" class="date">
							<span class="tutor-fs-7 tutor-fw-medium tutor-color-black">
								<?php echo esc_html( tutor_get_formated_date( get_option( 'date_format' ), $review->comment_date ) ); ?>, <br />
								<?php echo esc_html( tutor_get_formated_date( get_option( 'time_format' ), $review->comment_date ) ); ?>
							</span>
						</td>
						<td data-th="<?php esc_html_e( 'Course', 'tutor-pro' ); ?>" class="course">
							<span class="tutor-fs-7 tutor-fw-medium tutor-color-black">
								<?php echo esc_html( get_the_title( $review->comment_post_ID ) ); ?>
							</span>
						</td>
						<td data-th="<?php esc_html_e( 'Feedback', 'tutor-pro' ); ?>" class="feedback">
							<div class="td-feedback">
								<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
									<div class="td-tutor-rating tutor-fs-6 tutor-color-black-60">
										<?php tutor_utils()->star_rating_generator_v2( $review->rating, null, true ); ?>
									</div>
								</div>
								<sapn class="review-text tutor-color-black-60"><?php echo $review->comment_content; ?></sapn>
							</div>
						</td>
						<td data-th="<?php esc_html_e( 'Action', 'tutor-pro' ); ?>" class="review-action-btns" style="">
							<div class="inline-flex-center td-action-btns">
								<a data-tutor-modal-target="tutor-common-confirmation-modal" class="btn-outline tutor-btn tutor-danger tutor-delete-recent-reviews" data-id="<?php echo esc_attr( $review->comment_ID ); ?>">
									<?php esc_html_e( 'Delete', 'tutor-pro' ); ?>
								</a>
							</div>
							<a href="<?php echo esc_url( get_permalink( $review->comment_post_ID ) ); ?>" class="btn-text" target="_blank" style="vertical-align:middle;">
								<span class="tutor-icon-detail-link-filled"></span>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="100%" class="column-empty-state">
						<?php tutor_utils()->tutor_empty_state( tutor_utils()->not_found_text() ); ?>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table> <!-- tutor-ui-table -->
	</div>
	<div class="tutor-report-courses-data-table-pagination tutor-report-content-common-pagination">
	<?php
		$pagination_data = array(
			'base'        => str_replace( $current_page, '%#%', 'admin.php?page=tutor_report&sub_page=reviews&paged=%#%' ),
			'total_items' => $total_items,
			'paged'       => max( 1, $current_page ),
			'per_page'    => $per_page,
		);
		tutor_load_template_from_custom_path( tutor()->path . 'views/elements/pagination.php', $pagination_data );
		?>
	</div> <!-- tutor-report-overview-data-table-pagination  -->
</div> <!-- tutor-report-reviews -->
<?php tutor_load_template_from_custom_path( tutor()->path . 'views/elements/common-confirm-popup.php' ); ?>
