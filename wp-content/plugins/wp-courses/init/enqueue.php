<?php

    // enqueue scripts
    function wpc_enqueue_scripts(){
        wp_enqueue_script('jQuery');
        wp_enqueue_style( 'font-awesome-icons', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
        wp_enqueue_style( 'wpc-data-tables-style', plugins_url('../css/datatables.min.css',  __FILE__ ) );
        wp_enqueue_script('wpc-data-tables-js', plugins_url('../js/datatables.min.js', __FILE__ ), 'jquery', null, true);
        wp_enqueue_script('jquery-ui-js');
        wp_enqueue_script('jquery-ui-css');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_script("jquery-ui-tabs");
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_style('wpc-spectrum-css', plugins_url('../css/spectrum.min.css', __FILE__));
        wp_enqueue_script('wpc-spectrum', plugins_url('../js/spectrum/spectrum.min.js', __FILE__ ), 'jquery', null, true);
        wp_enqueue_style( 'wpc-style', plugins_url('../css/stylesheet.css',  __FILE__ ) );
        wp_enqueue_script('wpc-script', plugins_url('../js/wpc-js.js',  __FILE__ ), 'jQuery', null, false);

        // Localize the script with new data
        $translation_array = array(
            'completed'             => esc_html(__( 'Completed', 'wp-courses' )),
            'notCompleted'          => esc_html(__( 'Mark Completed', 'wp-courses' )),
            'emptyTable'            => esc_html(__( 'No data available in table', 'wp-courses' )),
            'infoEmpty'             => esc_html(__( 'There are 0 entries', 'wp-courses' )),
            'infoFiltered'          => esc_html(__( 'Filtered from a total entry count of', 'wp-courses' )),
            'lengthMenu'            => esc_html(__( 'Entries', 'wp-courses' )),
            'loadingRecords'        => esc_html(__( 'Loading...', 'wp-courses' )),
            'processing'            => esc_html(__( 'Processing...', 'wp-courses' )),
            'search'                => esc_html(__( 'Search', 'wp-courses' )),
            'zeroRecords'           => esc_html(__( 'No matching records found', 'wp-courses' )),
            'first'                 => esc_html(__( 'First', 'wp-courses' )),
            'last'                  => esc_html(__( 'Last', 'wp-courses' )),
            'next'                  => esc_html(__( 'Next', 'wp-courses' )),
            'previous'              => esc_html(__( 'Previous', 'wp-courses' )),
            'sortAscending'         => esc_html(__( 'activate to sort column ascending', 'wp-courses' )),
            'sortDescending'        => esc_html(__( 'activate to sort column descending', 'wp-courses' )),
        );

        wp_localize_script( 'wpc-script', 'WPCTranslations', $translation_array );

    }
    add_action( 'wp_enqueue_scripts', 'wpc_enqueue_scripts');

    function wpc_enqueue_admin_scripts(){
        wp_enqueue_script('wpc-charts', plugins_url('../js/chartjs/dist/chart.js',  __FILE__ ));
        wp_enqueue_style( 'wpc-data-tables-style', plugins_url('../css/datatables.min.css',  __FILE__ ));
        wp_enqueue_script('wpc-admin-js', plugins_url('../js/wpc-admin.js', __FILE__), 'jQuery');
        wp_enqueue_style('wpc-style', plugins_url('../css/stylesheet.css', __FILE__));
        wp_enqueue_script('wpc-data-tables-js', plugins_url('../js/datatables.min.js', __FILE__ ), 'jQuery', null, false);
        wp_enqueue_style( 'font-awesome-icons', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
        wp_enqueue_script('wpc-select2-js', plugins_url('../js/select2.min.js', __FILE__ ), 'jQuery', null, true);
        wp_enqueue_style('wpc-select2-css', plugins_url('../css/select2.min.css', __FILE__));
        wp_enqueue_script('jquery-ui-js');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script("jquery-ui-tabs");
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_script( 'wp-color-picker' ); 
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_style('wpc-spectrum-css', plugins_url('../css/spectrum.min.css', __FILE__));
        wp_enqueue_script('wpc-spectrum', plugins_url('../js/spectrum/spectrum.min.js', __FILE__ ), 'jquery', null, true);

        // Localize the script with new data
        $translation_array = array(
            'whenSomeone'           => esc_html(__( 'When Someone', 'wp-courses' )),
            'views'                 => esc_html(__( 'Views', 'wp-courses' )),
            'completes'             => esc_html(__( 'Completes', 'wp-courses' )),
            'scores'                => esc_html(__( 'Scores', 'wp-courses' )),
            'anyCourse'             => esc_html(__( 'Any Course', 'wp-courses' )),
            'aSpecificCourse'       => esc_html(__( 'A Specific Course', 'wp-courses' )),
            'anyLesson'             => esc_html(__( 'Any Lesson', 'wp-courses' )),
            'aSpecificLesson'       => esc_html(__( 'A Specific Lesson', 'wp-courses' )),
            'anyModule'             => esc_html(__( 'Any Module', 'wp-courses' )),
            'aSpecificModule'       => esc_html(__( 'A Specific Module', 'wp-courses' )),
            'anyQuiz'               => esc_html(__( 'Any Quiz', 'wp-courses' )),
            'aSpecificQuiz'         => esc_html(__( 'A Specific Quiz', 'wp-courses' )),
            'none'                  => esc_html(__( 'none', 'wp-courses' )),
            'percent'               => esc_html(__( 'Percent', 'wp-courses' )),
            'times'                 => esc_html(__( 'Times', 'wp-courses' )),
            'deleteRequirement'     => esc_html(__( 'Delete Requirement', 'wp-courses' )),
        );

        wp_localize_script( 'wpc-admin-js', 'WPCAdminTranslations', $translation_array );

    }
    add_action( 'admin_enqueue_scripts', 'wpc_enqueue_admin_scripts' );

?>