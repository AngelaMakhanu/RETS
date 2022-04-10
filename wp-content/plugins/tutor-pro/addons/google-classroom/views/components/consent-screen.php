<div class="consent-screen google-consent-screen-redirect">
    <h3><?php esc_html_e( 'Please complete the authorization process', 'tutor-pro' ); ?></h3>
    <p><?php esc_html_e( 'Press the button to grant permissions to your Google Classroom. Please allow all required permissions.', 'tutor-pro' ); ?></p>
    
    <br/>
    <div>
        <img src="<?php echo esc_url( TUTOR_GC()->url . '/assets/images/classroom.svg' ); ?>"/>
    </div>
    <br/>

    <a class="button button-primary button-large" href="<?php echo esc_url( $classroom->get_consent_screen_url() ); ?>">
        <?php esc_html_e( 'Allow Permissions', 'tutor-pro' ); ?>
    </a>
    <p>
        <a href="#" id="tutor_gc_credential_upgrade">
            <?php esc_html_e( 'Change Credential', 'tutor-pro' ); ?>
        </a>
    </p>
</div>