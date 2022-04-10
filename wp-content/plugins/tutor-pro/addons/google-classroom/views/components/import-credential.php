<div class="consent-screen oauth-redirect-url">
    <?php
        echo sprintf( __( 'Create OAuth access data and upload Credentials JSON from %s Google Console %s. As a redirect URI set %s', 'tutor-pro' ), '<a href="https://console.developers.google.com/" target="_blank"><b>', '</b></a>', '<b>' . get_home_url() . '/'.\TUTOR_GC\init::$google_callback_string . '/</b>' );
    ?>
</div>

<div class="consent-screen" id="tutor_gc_credential_upload">
    <div class="tutor-upload-area">
        <span class="tutor-fs-1 tutor-fw-bold tutor-color-brand-wordpress tutor-icon-upload-icon-line"></span>
        <h2><?php esc_html_e( 'Drag & Drop your JSON File here', 'tutor-pro' ); ?></h2>

        <p><small><?php esc_html_e( 'or', 'tutor-pro' ); ?></small></p>
        <button class="tutor-btn tutor-btn-wordpress tutor-btn-sm"><?php esc_html_e( 'Browse File', 'tutor-pro' ); ?></button>
        <input type="file" name="credential" accept=".json"/>
    </div>
    <button type="submit" class="button button-primary button-large" disabled="disabled">
        <?php esc_html_e( 'Load Credentials', 'tutor-pro' ); ?> 
    </button>
</div>