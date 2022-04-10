<?php
/**
 * Gradebook List Template.
 *
 * @package Gradebook List
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use TUTOR_GB\GradeBook;
$gradebook = new GradeBook();


/**
 * Short able params
 */
$user_id   = isset( $_GET['user_id'] ) ? $_GET['user_id'] : '';
$course_id = isset( $_GET['course-id'] ) ? $_GET['course-id'] : '';
$order     = isset( $_GET['order'] ) ? $_GET['order'] : 'DESC';
$date      = isset( $_GET['date'] ) ? tutor_get_formated_date( 'Y-m-d', $_GET['date'] ) : '';
$search    = isset( $_GET['search'] ) ? $_GET['search'] : '';

/**
 * Determine active tab
 */
$active_tab = isset( $_GET['data'] ) && $_GET['data'] !== '' ? esc_html__( $_GET['data'] ) : 'all';

/**
 * Navbar data to make nav menu
 */
$url                        = get_pagenum_link();
$add_gradebook_url          = $url . '&sub_page=add_new_gradebook';
$add_gradebook_settings_url = $url . '&sub_page=gradebooks';
$navbar_data                = array(
	'page_title'   => $gradebook->page_title,
	'tabs'         => $gradebook->tabs_key_value( $course_id ),
	'active'       => $active_tab,
	'add_button'   => true,
	'button_title' => __( 'Add New', 'tutor' ),
	'button_url'   => $add_gradebook_url,
	'modal_target' => 'tutor-add-new-grade',
);

$filters = array(
	'bulk_action'   => false,
	'bulk_actions'  => $gradebook->prpare_bulk_actions(),
	'ajax_action'   => 'tutor_gradebook_bulk_action',
	'filters'       => true,
	'course_filter' => true,
	'course_filter' => true,
);

?>
<?php
	/**
	 * Load Templates with data.
	 */
	$navbar_template = tutor()->path . 'views/elements/navbar.php';
	tutor_load_template_from_custom_path( $navbar_template, $navbar_data );
?>

<div class="tutor-mt-24 tutor-pr-20">
	<div class="tutor_admin_gradebook_list tutor-ui-table-wrapper">
		<?php tutor_alert( null, 'success' ); ?>

		<?php
		$gradebooks = tutor_utils()->get_gradebooks();

		if ( tutor_utils()->count( $gradebooks ) ) {
			?>
			<table class="tutor-ui-table tutor-ui-table-responsive tutor-gradebooks-lists">
				<thead>
					<tr>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Grade Badge', 'tutor-pro' ); ?>
							</div>
						</th>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Grade Name', 'tutor-pro' ); ?>
							</div>
						</th>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Grade Point', 'tutor-pro' ); ?>
							</div>
						</th>
						<th>
							<div class="tutor-fs-7 tutor-color-black-60">
								<?php esc_html_e( 'Grade Range %', 'tutor-pro' ); ?>
							</div>
						</th>
						<th class="tutor-shrink"></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $gradebooks as $gradebook ) {
						$config = maybe_unserialize( $gradebook->grade_config );
						?>
							<tr>
								<td data-th="<?php esc_html_e( 'Grade Badge', 'tutor-pro' ); ?>">
									<span class="gradename-bg" style="background-color: <?php echo tutor_utils()->array_get( 'grade_color', $config ); ?>;" >
									<?php echo esc_html( $gradebook->grade_name ); ?>
									</span>
								</td>
								<td data-th="<?php esc_html_e( 'Grade Name', 'tutor-pro' ); ?>">
								<?php echo esc_html( $gradebook->grade_name ); ?>
								</td>
								<td data-th="<?php esc_html_e( 'Grade Point', 'tutor-pro' ); ?>"><?php echo esc_html( $gradebook->grade_point ); ?></td>
								<td data-th="<?php esc_html_e( 'Grade Range %', 'tutor-pro' ); ?>"><?php echo esc_html( $gradebook->percent_from . '-' . $gradebook->percent_to ); ?></td>
								<td data-th="<?php esc_html_e( 'Actions', 'tutor-pro' ); ?>">
									<div class="inline-flex-center td-action-btns">
										<a class="gradebook-edit-btn">
											<button data-tutor-modal-target="tutor-update-existing-grade" class="tutor-btn tutor-btn-wordpress-outline tutor-btn-sm tutor-open-grade-update-modal" data-id="<?php echo esc_attr( $gradebook->gradebook_id ); ?>" data-name="<?php echo esc_attr( $gradebook->grade_name ); ?>" data-point="<?php echo esc_attr( $gradebook->grade_point ); ?>" data-maximum="<?php echo esc_attr( $gradebook->percent_to ); ?>" data-minimum="<?php echo esc_attr( $gradebook->percent_from ); ?>" data-color="<?php echo esc_attr( tutor_utils()->array_get( 'grade_color', $config ) ); ?>"><?php esc_html_e( 'Edit', 'tutor-pro' ); ?></button> 
										</a>
			
										<a href="
										<?php
										echo add_query_arg(
											array(
												'tutor_action' => 'delete_gradebook',
												'gradebook_id' => $gradebook->gradebook_id,
											)
										);
										?>
													" class="gradebook-delete-btn" onclick="return confirm('<?php esc_html_e( 'Are you Sure?', 'tutor-pro' ); ?>')">
											<button class="tutor-btn tutor-is-outline tutor-btn-sm tutor-no-hover tutor-is-default"><?php esc_html_e( 'Delete', 'tutor-pro' ); ?></button>
										</a>
									</div>
								</td>
							</tr>
							<?php
					}
					?>
				</tbody>
			</table>
			<?php
		} else {
			$alert_template = tutor()->path . 'templates/global/alert.php';
			if ( file_exists( $alert_template ) && function_exists( 'tutor_load_template_from_custom_path' ) ) {
				$args = array(
					'alert_class'  => 'tutor-alert tutor-warning',
					'message'      => __( 'No grading system has been defined to manage student grades.' ),
					'icon'         => 'tutor-icon-circle-outline-info-filled',
					'button_text'  => 'Import Sample Grade Data',
					'button_class' => 'tutor-btn tutor-btn-sm',
					'button_id'    => 'import-gradebook-sample-data',
				);
				tutor_load_template_from_custom_path( $alert_template, $args );
			}
		}
		?>
	</div>
</div>

<!-- Add New Grade Modal -->
<div id="tutor-add-new-grade" class="tutor-modal tutor-modal-is-close-beside tutor-modal-gradebook">
  <span class="tutor-modal-overlay"></span>
  
  <div class="tutor-modal-root">
  <button data-tutor-modal-close class="tutor-modal-close">
	<span class="tutor-icon-56 tutor-icon-line-cross-line"></span>
  </button>
	<div class="tutor-modal-inner">
	<form action="" method="post" id="tutor-add-new-gradebook-form" autocomplete="off">
		<input type="hidden" name="action" value="add_new_gradebook">
		<?php tutor_nonce_field(); ?>
		<div class="tutor-modal-header">
			<h3 class="tutor-modal-title tutor-fs-6 tutor-fw-bold tutor-color-black-70">
				<?php esc_html_e( 'Add New Grade', 'tutor-pro' ); ?>
			</h3>
		</div>
		<div class="tutor-modal-body-alt tutor-bg-gray-10">
			<?php do_action( 'tutor_add_new_grade_form_fields_before' ); ?>
			<div class="tutor-row tutor-mx-0">
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Name', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="grade_name" class="tutor-form-control tutor-mb-12" placeholder="<?php echo esc_attr( 'Enter Name', 'tutor' ); ?>" required/>
					</div>
				</div>
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Grade Point', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="grade_point" class="tutor-form-control tutor-mb-12" placeholder="<?php echo esc_attr( 'Enter Grade Point', 'tutor' ); ?>" required/>
					</div>
				</div>
			</div>
			<div class="tutor-row tutor-mx-0">
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Maximum Percentile', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="percent_to" class="tutor-form-control tutor-mb-12" autocomplete="off" placeholder="<?php echo esc_attr( 'Maximum Percentile', 'tutor-pro' ); ?>" required/>
					</div>
				</div>
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Minimum Percentile', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="percent_from"  class="tutor-form-control tutor-mb-12" placeholder="<?php echo esc_attr( 'Minimum Percentile', 'tutor-pro' ); ?>" required/>
					</div>
				</div>
			</div>
			<div class="tutor-row tutor-mx-0">
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Color', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" class="tutor_colorpicker" name="grade_config[grade_color]">
					</div>
				</div>
			</div>
			<?php do_action( 'tutor_add_new_grade_form_fields_after' ); ?>
			<div class="tutor-row tutor-mx-0" id="tutor-add-new-grad-form-response"></div>
		</div>
		  <div class="tutor-modal-footer">
			<div class="tutor-d-flex tutor- tutor-align-items-center tutor-justify-content-between">
				<div>
					<button type="submit" class="tutor-btn tutor-btn-wordpress tutor-btn-lg tutor-btn-loading">
						<?php esc_html_e( 'Add new Grade', 'tutor-pro' ); ?>
					</button>
				</div>
				<div>
					<button data-tutor-modal-close class="tutor-btn tutor-btn-disable tutor-no-hover">
						<?php esc_html_e( 'Cancel', 'tutor-pro' ); ?>
					</button>
				</div>
			</div>
	  </div>
	</form>
	</div>
  </div>
</div>

<!-- Update Grade Modal -->
<div id="tutor-update-existing-grade" class="tutor-modal tutor-modal-is-close-beside tutor-modal-gradebook">
  <span class="tutor-modal-overlay"></span>
  <div class="tutor-modal-root">
  <button data-tutor-modal-close class="tutor-modal-close">
	<span class="tutor-icon-56 tutor-icon-line-cross-line"></span>
  </button>
	<div class="tutor-modal-inner">
	<form action="" method="post" id="tutor-update-gradebook-form" autocomplete="off">
		<input type="hidden" name="action" value="update_gradebook">
		<input type="hidden" name="gradebook_id" value="">
		<?php tutor_nonce_field(); ?>
	  <div class="tutor-modal-header">
		<h3 class="tutor-modal-title tutor-fs-6 tutor-fw-bold tutor-color-black-70">
			<?php esc_html_e( 'Update Grade', 'tutor-pro' ); ?>
		</h3>
	  </div>
		  <div class="tutor-modal-body-alt tutor-bg-gray-10">
			<?php do_action( 'tutor_upate_existing_grade_form_fields_before' ); ?>
			<div class="tutor-row tutor-mx-0">
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Name', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="grade_name" class="tutor-form-control tutor-mb-12" placeholder="<?php echo esc_attr( 'Enter Name', 'tutor-pro' ); ?>" required/>
					</div>
				</div>
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Grade Point', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="grade_point" class="tutor-form-control tutor-mb-12" placeholder="<?php echo esc_attr( 'Enter Grade Point', 'tutor-pro' ); ?>" required/>
					</div>
				</div>
			</div>
			<div class="tutor-row tutor-mx-0">
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Maximum Percentile', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="percent_to" class="tutor-form-control tutor-mb-12" autocomplete="off" placeholder="<?php echo esc_attr( 'Maximum Percentile', 'tutor-pro' ); ?>" required/>
					</div>
				</div>
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Minimum Percentile', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" name="percent_from"  class="tutor-form-control tutor-mb-12" placeholder="<?php echo esc_attr( 'Minimum Percentile', 'tutor-pro' ); ?>" required/>
					</div>
				</div>
			</div>
			<div class="tutor-row tutor-mx-0">
				<div class="tutor-col-sm-6">
					<label class="tutor-form-label">
						<?php esc_html_e( 'Color', 'tutor-pro' ); ?>
					</label>
					<div class="tutor-input-group tutor-mb-4">
						<input type="text" class="tutor_colorpicker" id="tutor-update-grade-color" name="grade_config[grade_color]" value="">
					</div>
				</div>
			</div>
			<?php do_action( 'tutor_update_existing_grade_form_fields_after' ); ?>
			<div class="tutor-row tutor-mx-0" id="tutor-update-grade-form-response"></div>
		</div>
		  <div class="tutor-modal-footer">
			<div class="tutor-d-flex tutor- tutor-align-items-center tutor-justify-content-between">
				<div class="">
					<button type="submit" class="tutor-btn tutor-btn-wordpress tutor-btn-lg tutor-btn-loading">
					<?php esc_html_e( 'Update Grade', 'tutor-pro' ); ?>
					</button>
				</div>
				<div class="">
					<button data-tutor-modal-close class="tutor-btn tutor-btn-disable tutor-no-hover tutor-d-block-">
					<?php esc_html_e( 'Cancel', 'tutor-pro' ); ?>
					</button>
				</div>
			</div>
		  </div>
	</form>
	</div>
  </div>
</div>

