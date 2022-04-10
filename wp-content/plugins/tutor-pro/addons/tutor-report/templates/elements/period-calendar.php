<div class="analytics-title tutor-fs-5 tutor-fw-medium tutor-color-black tutor-mb-8 tutor-mt-20">
    <?php _e( 'Earnings Graph', 'tutor-pro' ); ?>
</div>
<div class="tutor-analytics-filter-tabs">
    <?php 
        $active     = isset( $_GET['period'] ) ? sanitize_text_field( $_GET['period'] ) : '';
        $start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
        $end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';
    ?>

    <?php if( count( $data['filter_period'] ) ): ?>

        <div class="periods-filter tutor-mt-4">
            <?php foreach( $data['filter_period'] as $key => $value ): ?>
                <?php 
                    $active_class = $active === $value['type'] ? 'active' : '';    
                ?>
                <a href="<?php echo $value['url']; ?>" class="<?php esc_attr_e($value['class'].' '.$active_class); ?>">
                    <?php esc_html_e( $value['title'] ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if ( $data['filter_calendar'] ): ?>
        <div class="tutor-v2-date-range-picker" style="flex-basis: 40%;"></div>
    <?php endif; ?>
    
</div>