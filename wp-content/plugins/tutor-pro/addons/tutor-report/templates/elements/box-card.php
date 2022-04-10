<?php if ( is_array( $data ) && count( $data ) ): ?>
    <div class="tutor-analytics-info-cards">
        <?php foreach( $data as $key => $value ): ?>
            <div class="tutor-dashboard-info-card">
                <div>
                    <span class="tutor-dashboard-round-icon">
                        <i class="<?php echo $value['icon']; ?>"></i>
                    </span>
                    <?php if ( $value['price'] ): ?>
                        <span class="tutor-dashboard-info-val tutor-fs-4 tutor-fw-bold tutor-color-black">
                            <?php echo $value['title'] ? wp_kses_post(tutor_utils()->tutor_price( $value['title'] )) : '-'; ?>
                        </span>
                    <?php else: ?>
                        <span class="tutor-dashboard-info-val tutor-fs-4 tutor-fw-bold tutor-color-black">
                            <?php echo $value['title'] ? esc_html($value['title']) : '-'; ?>
                        </span>
                    <?php endif; ?>    
                    <span class="tutor-fs-7 tutor-color-black-60 tutor-mt-2">
                        <?php echo esc_html($value['sub_title']); ?> 
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div> 
<?php endif; ?>       