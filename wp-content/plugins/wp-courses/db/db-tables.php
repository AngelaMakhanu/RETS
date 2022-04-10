<?php 

	function wpc_create_tracking_table() {

	    global $wpdb;
	    add_option('wpc_tracking_table_version', "1.0");
	    $table_name = $wpdb->prefix . 'wpc_tracking';
	    $charset_collate = $wpdb->get_charset_collate();

	    $sql = "CREATE TABLE $table_name (
	        id bigint(20) NOT NULL AUTO_INCREMENT,
	        user_id bigint(20),
	        post_id bigint(20) NOT NULL,
	        course_id bigint(20),
	        viewed_timestamp bigint(20),
	       	completed_timestamp bigint(20),
	        completed tinyint(1),
	        primary key (id)
	    ) $charset_collate;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );

	    // ports old usermeta to new tracking table
	    wpc_port_postmeta_tracking_to_table();
	}

	register_activation_hook( __FILE__, 'wpc_create_tracking_table');

	function wpc_update_db_tracking_table_check() {
	    $ver = get_site_option( "wpc_tracking_table_version");
	    if($ver != "1.0") {
	        wpc_create_tracking_table();
	    }
	}

	add_action( 'plugins_loaded', 'wpc_update_db_tracking_table_check' );

	function wpc_create_connections_table() {
		global $wpdb;
		add_option( "wpc_connections_table_version", "1.0");
		$table_name = $wpdb->prefix . "wpc_connections";
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			post_from bigint(20),
			post_to bigint(20),
			connection_type varchar(255),
			menu_order int(11),
			PRIMARY KEY (id)
		) $charset_collate";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );

	    wpc_port_postmeta_to_connections_table();
	}
	register_activation_hook( __FILE__, 'wpc_create_connections_table' );

	function wpc_update_connections_table_check() {
	    $ver = get_site_option( "wpc_connections_table_version" );
	    if ( $ver != "1.0" ) {
	        wpc_create_connections_table();
	    }
	}
	add_action( 'init', 'wpc_update_connections_table_check' );

	// install requirements table
	function wpc_create_requirements_table() {
	    global $wpdb;
	    add_option( "wpc_db_version", "1.0" );
	    $table_name = $wpdb->prefix . 'wpc_rules';
	    $charset_collate = $wpdb->get_charset_collate();

	    $sql = "CREATE TABLE $table_name (
	        id mediumint(10) NOT NULL AUTO_INCREMENT,
	        post_id mediumint(10) NOT NULL,
	        course_id mediumint(10),
	        lesson_id mediumint(10),
	        module_id mediumint(10),
	        action varchar(255),
	        type varchar(255),
	        percent TINYINT,
	        times mediumint(9),
	        PRIMARY KEY  (id)
	    ) $charset_collate;";

	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta( $sql );
	}
	register_activation_hook( __FILE__, 'wpc_create_requirements_table' );

	function wpc_update_db_check() {
	    $ver = get_site_option( "wpc_db_version" );
	    if ( $ver != "1.0" ) {
	        wpc_create_requirements_table();
	    }
	}
	add_action( 'plugins_loaded', 'wpc_update_db_check' );

?>