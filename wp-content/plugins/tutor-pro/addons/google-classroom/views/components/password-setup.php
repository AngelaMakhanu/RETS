<div id="tutor_gc_student_password_set">
    <h4><?php esc_html_e( 'Set Password', 'tutor-pro' ); ?></h4>

    <label>
        <?php esc_html_e( 'Password', 'tutor-pro' ); ?>
        <input type="password" class="regular-text" name="password-1"/>
    </label>
    
    <label>
        <?php esc_html_e( 'Re-type Password', 'tutor-pro' ); ?>
        <input type="password" class="regular-text" name="password-2"/>
    </label>
    
    <input type="hidden" name="token" value="<?php echo esc_attr( $_GET['token'] ); ?>"/>
    
    <div>
        <button class="button button-primary"> 
            <?php esc_html_e( 'Set Password', 'tutor-pro' ); ?>
        </button>
    </div>
</div>