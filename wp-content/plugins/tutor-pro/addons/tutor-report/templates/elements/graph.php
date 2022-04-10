<div class="tutor-analytics-graph">
	
	<?php if ( $data ) : ?>
		<div class="tabs">
			<?php foreach ( $data as $key => $value ) : ?>
				<?php $active = $value['active']; ?>
				<div class="tab <?php echo esc_attr( $active ); ?>" data-toggle="<?php echo esc_attr( $value['data_attr'] ); ?>">
					<div class="tutor-fs-7 tutor-color-black-60">
						<?php echo esc_html( $value['tab_title'] ); ?>
					</div>
					<div class="tutor-fs-5 tutor-fw-bold tutor-color-black tutor-mt-4">
						<?php if ( $value['price'] ) : ?>
							<?php echo $value['tab_value'] ? wp_kses_post( tutor_utils()->tutor_price( $value['tab_value'] ) ) : '-'; ?>
						<?php else : ?>
							<?php esc_html_e( $value['tab_value'] ? $value['tab_value'] : '-' ); ?>
						<?php endif; ?>    
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<!--tab content -->
		<div class="chart-wrapper">
 
			<?php foreach ( $data as $key => $value ) : ?>

				<?php $active = $value['active']; ?>
				<div class="tab-content <?php echo esc_attr( $active ); ?>" id="<?php echo esc_attr( $value['data_attr'] ); ?>">
					<div class="chart-title tutor-fs-6 tutor-fw-medium tutor-color-black">
						<?php echo esc_html( $value['content_title'] ); ?>
					</div>
					<canvas id="<?php echo esc_attr( $value['data_attr'] . '_canvas' ); ?>"></canvas>
				</div>
			<?php endforeach; ?>
		</div>
		<!--tab content end -->

	<?php endif; ?>

</div>
