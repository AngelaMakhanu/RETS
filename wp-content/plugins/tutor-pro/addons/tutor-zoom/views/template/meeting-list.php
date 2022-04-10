<?php
	$page_key             = 'meeting-table';
	is_admin() ? $context = 'backend-dashboard' : '';

	$table_columns = include __DIR__ . '/contexts.php';
	$zoom_object   = new \TUTOR_ZOOM\Zoom( false );
?>
<div class="tutor-ui-table-wrapper tutor-mb-24">
	<table class="tutor-ui-table tutor-ui-table-responsive tutor-ui-table-zoom">
		<thead>
			<tr>
				<?php
				foreach ( $table_columns as $key => $column ) {
					if ( $key == 'start_time' ) {
						?>
						<th class="tutor-table-rows-sorting">
							<div class="tutor-d-flex tutor- tutor-align-items-center tutor-fs-7 tutor-color-black-60">
								<span class="tutor-fs-7">
									<?php echo $column; ?>								
								</span>
								<span class="a-to-z-sort-icon tutor-icon-ordering-z-to-a-filled tutor-icon-22"></span>
							</div>
						</th>
						<?php
						continue;
					}

					echo '<th>
						<span class="tutor-fs-7 tutor-color-black-60">' . $column . '</span>
					</th>';
				}
				?>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $meetings as $key => $meeting ) {

			$tzm_start    = get_post_meta( $meeting->ID, '_tutor_zm_start_datetime', true );
			$meeting_data = get_post_meta( $meeting->ID, $this->zoom_meeting_post_meta, true );
			$meeting_data = json_decode( $meeting_data, true );
			$input_date   = \DateTime::createFromFormat( 'Y-m-d H:i:s', $tzm_start );
			$start_date   = $input_date->format( 'j M, Y, h:i A' );
			$course_id    = get_post_meta( $meeting->ID, '_tutor_zm_for_course', true );
			$topic_id     = get_post_meta( $meeting->ID, '_tutor_zm_for_topic', true );

			$row_id              = 'tutor-zoom-meeting-' . $meeting->ID;
			$id_string_delete    = 'tutor-zoom-delete-modal-' . $meeting->ID;
			$id_string_edit      = 'tutor-zoom-edit-modal-' . $meeting->ID;
			$popup_action_string = 'tutor-zoom-popup-action-' . $meeting->ID;
			$popover_id          = 'tutor-zoom-popupover-action-' . $meeting->ID;
			$copy_target_id      = 'tutor-zoom-popupover-copy-' . $meeting->ID;

			if ( ! is_null( $meeting_data ) ) {
				?>
				<tr id="<?php echo $row_id; ?>" class="tutor-zoom-meeting-item">
					<?php
					foreach ( $table_columns as $column_key => $column_name ) {
						switch ( $column_key ) {
							case 'start_time':
								?>
									<td class="tutor-valign-top" data-th="<?php echo $column_name; ?>">
										<div class="tutor-fs-7 tutor-fw-medium tutor-color-black">
											<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $tzm_start ) ) ); ?>,
											<?php echo esc_html( date_i18n( get_option( 'time_format' ), strtotime( $tzm_start ) ) ); ?>
										</div>
									</td>
								<?php
								break;

							case 'meeting_name':
								?>
									<td data-th="<?php echo $column_name; ?>" class="column-fullwidth">
										<div class="tutor-color-black td-course tutor-fs-6 tutor-fw-medium">
											<a><?php esc_html_e( $meeting->post_title ); ?></a>
											<div class="course-meta">
												<span class="tutor-color-black-60 tutor-fs-7">
													<strong class="tutor-fs-7 tutor-fw-medium"><?php _e( 'Course', 'tutor-pro' ); ?>: </strong>
											<?php echo get_the_title( $course_id ); ?>
												</span>
											</div>
										</div>
									</td>
								<?php
								break;

							case 'meeting_token':
								?>
									<td data-th="<?php echo $column_name; ?>">
										<span class="tutor-color-black tutor-fs-7 tutor-fw-medium">
											<?php echo $meeting_data['id']; ?>
										</span>
									</td>
								<?php
								break;

							case 'password':
								?>
									<td data-th="<?php echo $column_name; ?>">
										<span class="tutor-color-black tutor-fs-7 tutor-fw-medium">
											<?php echo $meeting_data['password']; ?>
										</span>
									</td>
								<?php
								break;

							case 'hostmail':
								?>
									<td data-th="<?php echo $column_name; ?>">
										<span class="tutor-color-black tutor-fs-7 tutor-fw-medium">
											<?php echo $meeting_data['host_email']; ?>
										</span>
									</td>
								<?php
								break;

							case 'action_frontend':
								$button_text  = 'Start Meeting';
								$button_class = 'tutor-btn tutor-is-xs tutor-mr-12';
								if ( $meeting->is_expired ) {
									$button_text  = 'Expired';
									$button_class = 'tutor-btn tutor-btn-disable-outline tutor-btn-outline-fd tutor-btn-sm tutor-mr-12';
								} elseif ( $meeting->is_running ) {
									$button_text  = 'Join Now';
									$button_class = 'btn-outline tutor-btn tutor-is-default tutor-is-xs tutor-mr-12';
								}
								?>
								<td data-th="<?php _e( 'Action', 'tutor-pro' ); ?>">
									<div class="tutor-d-flex tutor- tutor-align-items-center tutor-justify-content-end">
										<div class="inline-flex-center td-action-btns">
											<a href="<?php echo ! $meeting->is_expired ? $meeting_data['start_url'] : 'javascript:void(0)'; ?>" class="<?php esc_attr_e( $button_class ); ?>" target="<?php echo ! $meeting->is_expired ? '_blank' : ''; ?>">
												<i class="tutor-icon-zoom tutor-mr-2 tutor-icon-20"></i> <?php echo $button_text; ?>
											</a>
	
											<div class="tutor-popover-wrapper">
												<button class="tutor-popover-toggle-btn tutor-btn tutor-btn-icon tutor-btn-disable-outline tutor-no-hover tutor-btn-sm" data-tutor-popover-target="<?php echo $popover_id; ?>">
													<span><?php _e( 'Info', 'tutor' ); ?></span>
													<span class="btn-icon tutor-icon-angle-down-filled"></span>
												</button>
												<div id="<?php echo $popover_id; ?>" class="tutor-popover">
													<div class="tutor-popover-backdrop" data-tutor-popover-backdrop></div>
														<ul class="tutor-meeting-list tutor-border-sl20 tutor-radius-6">
															<li>
																<div>
																	<span class="tutor-fs-7 tutor-color-muted"><?php _e( 'Meeting ID', 'tutor-pro' ); ?></span>
																	<br />
																	<span class="tutor-fs-6 tutor-fw-medium tutor-color-black" id="<?php echo $copy_target_id; ?>"><?php esc_html_e( $meeting_data['id'] ); ?>
																	</span>
																</div>
																<div>
																	<button class="tutor-btn tutor-is-circle tutor-is-outline tutor-btn-ghost" data-tutor-copy-target="<?php echo $copy_target_id; ?>">
																		<span class="btn-icon tutor-icon-24 tutor-icon-copy-text"></span>
																	</button>
																</div>
															</li>
															<li>
																<div>
																	<span class="tutor-fs-7 tutor-color-muted"><?php _e( 'Password', 'tutor-pro' ); ?></span>
																	<br />
																	<span class="tutor-fs-6 tutor-fw-medium tutor-color-black" id="<?php echo $copy_target_id; ?>-2"> <?php esc_html_e( $meeting_data['password'] ); ?>
																	</span>
																</div>
																<div>
																	<button class="tutor-btn tutor-is-circle tutor-is-outline tutor-btn-ghost" data-tutor-copy-target="<?php echo $copy_target_id; ?>-2">
																		<span class="btn-icon tutor-icon-24 tutor-icon-copy-text"></span>
																	</button>
																</div>
															</li>
															<li>
																<div class="overflow-hidden">
																	<span class="tutor-fs-7 tutor-color-muted"><?php _e( 'Host Email', 'tutor-pro' ); ?></span>
																	<br />
																	<span class="tutor-fs-6 tutor-fw-medium tutor-color-black tutor-text-nowrap tutor-d-block tutor-w-100" title="<?php esc_html_e( $meeting_data['host_email'] ); ?>">
																	<?php esc_html_e( $meeting_data['host_email'] ); ?>
																	</span>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div>
	
											<div class="tutor-popup-opener tutor-ml-8">
												<button type="button" class="popup-btn" data-tutor-popup-target="<?php echo $popup_action_string; ?>">
													<span class="toggle-icon"></span>
												</button>
												<ul class="popup-menu" id="<?php echo $popup_action_string; ?>">
													<li>
														<a href="#" data-tutor-modal-target="tutor-zoom-meeting-modal-<?php echo $meeting->ID; ?>">
															<i class="tutor-icon-edit-filled tutor-color-white"></i>
															<span class="tutor-fs-6 color-text-white">Edit</span>
														</a>
													</li>
													<li>
														<a href="#" data-tutor-modal-target="<?php echo $id_string_delete; ?>">
															<i class="tutor-icon-delete-fill-filled tutor-color-white"></i>
															<span class="tutor-fs-6 color-text-white">Delete</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
	
									<?php
									// Meeting update modal
									$zoom_object->tutor_zoom_meeting_modal_content( $meeting->ID, $topic_id, $course_id, '0' );

									// Delete confirmation modal
									include __DIR__ . '/delete-modal.php';
									?>
								</td>
								<?php
								break;

							case 'action_backend':
								$button_text  = 'Start Meeting';
								$button_class = 'tutor-btn tutor-is-xs tutor-mr-12';
								if ( $meeting->is_expired ) {
									$button_text  = 'Expired';
									$button_class = 'tutor-btn tutor-btn-icon tutor-btn-tertiary tutor-is-outline tutor-is-xs tutor-mr-12 zoom-expired-btn';
									// $button_class = 'tutor-btnd tutor-btn-smd tutor-mr-12 zoom-expired-btn';
								} elseif ( $meeting->is_running ) {
									$button_text  = 'Join Now';
									$button_class = 'tutor-btn tutor-btn-tertiary tutor-is-outline tutor-is-xs tutor-btn-icon tutor-mr-12';
								}
								?>
								<td data-th="<?php _e( 'Action', 'tutor-pro' ); ?>">
									<div class="tutor-d-flex tutor- tutor-align-items-center tutor-justify-content-end">
										<div class="zoom-expired-btn-wrapper inline-flex-center td-action-btns">
											<a href="<?php echo ! $meeting->is_expired ? $meeting_data['start_url'] : 'javascript:void(0)'; ?>" class="<?php esc_attr_e( $button_class ); ?>" target="<?php echo ! $meeting->is_expired ? '_blank' : ''; ?>">
												<i class="tutor-icon-zoom tutor-mr-2 tutor-icon-20"></i> <?php echo $button_text; ?>
											</a>
	
											<a href="#" class="tutor-btn tutor-btn-wordpress-outline tutor-is-xs" data-tutor-modal-target="tutor-zoom-meeting-modal-<?php echo $meeting->ID; ?>">
												Edit
											</a>
	
											<a href="#" data-tutor-modal-target="<?php echo $id_string_delete; ?>">
												<i class="tutor-icon-delete-stroke-filled tutor-icon-24 tutor-color-black-60"></i>
											</a>
										</div>
									</div>
									<?php
										// Meeting update modal
										$zoom_object->tutor_zoom_meeting_modal_content( $meeting->ID, $topic_id, $course_id, '0' );

										// Delete confirmation modal
										include __DIR__ . '/delete-modal.php';
									?>
								</td>
								<?php
								break;
						}
					}
					?>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
