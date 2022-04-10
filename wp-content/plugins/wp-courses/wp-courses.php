<?php
/**
 * Plugin Name: WP Courses LMS
 * Description: Create unlimited courses on your WordPress website with WP Courses LMS.
 * Version: 3.0.73
 * Author: Myles English
 * Plugin URI: https://wpcoursesplugin.com
 * Author URI: https://stratospheredigital.ca
 * Text Domain: wp-courses
 * Domain Path: /lang
 * License: GPL2
 */

defined('ABSPATH') or die("No script kiddies please!");
define("WPC_PLUGIN_URL", plugin_dir_url( __FILE__ ));

include 'functions/functions.php';
include 'functions/security.php';
include 'functions/requirements.php';
include 'functions/tracking.php';
include 'functions/connections.php';
include 'functions/output.php';
include 'legacy/update.php';
include 'legacy/depricated.php';
include 'db/db-tables.php';
include 'init/cp-types.php';
include 'init/taxonomies.php';
include 'init/enqueue.php';
include 'classes/class-wp-courses.php';
include 'classes/class-wpc-admin.php';
include 'init/templates.php';
include 'init/style-options.php';
include 'admin/wpc-options.php';
include 'admin/lesson-meta.php';
include 'admin/course-meta.php';
include 'admin/requirements-meta.php';
include 'admin/admin-menu.php';
include 'admin/columns.php';
include 'admin/front-end-editor.php';
include 'shortcodes.php';
include 'admin/widgets.php';
include 'cron/cron.php';
include 'ajax/ajax.php';
include 'ajax/lesson-order.php';
include 'ajax/lesson-change-restriction.php';
include 'ajax/course-order.php';
include 'ajax/course-change.php';
include 'ajax/complete-lesson.php';

// Redirect on Activation to Welcome Page
register_activation_hook(__FILE__, 'wpc_plugin_activate');
add_action('admin_init', 'wpc_plugin_redirect');

function wpc_plugin_activate() {
    add_option('wpc_plugin_do_activation_redirect', true);
}

function wpc_plugin_redirect() {
    if (get_option('wpc_plugin_do_activation_redirect', false)) {
        delete_option('wpc_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=wpc_help");
        }
    }
}

function wpc_old_woo_admin_notice_failure() {
    // Check if PMPro is active
    if(is_plugin_active( 'wp-courses-woocommerce/wp-courses-woocommerce.php' )){ ?>
        <div class="notice notice-error is-dismissible">
            <p>WP Courses WooCommerce Integration is not fully compatible with your version of WP Courses.  All add-ons for WP Courses now reside in one add-on called WP Courses Premium.  <a href="https://wpcoursesplugin.com/lesson/upgrading-wp-courses-woocommerce-integration-for-3-0/?course_id=958">Update instructions can be found here</a>.</p>
        </div>
    <?php
    }
}
add_action( 'admin_notices', 'wpc_old_woo_admin_notice_failure' );

// Append course_id GET to post edit link
function wpc_append_query_string( $url, $post ) {
    if(is_admin() == true){
        $post_type = get_post_type( $post->ID );
        if ( 'lesson' == $post_type || 'wpc-quiz' == $post_type ) {
            $last_viewed_course_id = get_user_meta(get_current_user_id(), 'wpc-last-viewed-course', true);
            if(isset($_GET['course_id'])) {
                $course_id = $_GET['course_id'];
            } else {
                $course_id = wpc_get_first_connected_course($post->ID);
            }

            if(!empty($course_id)) {
                return add_query_arg( array('course_id' => $course_id ), $url );
            } else {
                return $url;
            }
        }
        return $url;
    } else {
        return $url;
    }

}
add_filter( 'post_type_link', 'wpc_append_query_string', 10, 2 );

// Add links below plugin on plugins page
function wpc_action_links( $links ) {
    $links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=wpc_settings' ) ) . '">' . __( 'Dashboard', 'wp-courses' ) . '</a>',
        '<a href="' . esc_url( admin_url( '/admin.php?page=wpc_options' ) ) . '">' . __( 'Settings', 'wp-courses' ) . '</a>',
        '<a href="' . esc_url( admin_url( '/admin.php?page=wpc_help' ) ) . '">' . __( 'Help', 'wp-courses' ) . '</a>',
        '<a style="font-weight:bold; color: #e21772;" href="' . esc_url( admin_url( '/admin.php?page=wpc_premium' ) ) . '">' . __( 'Upgrade to Premium', 'wp-courses' ) . '</a>'
    ), $links );
    return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpc_action_links' );

// use ajax in the front-end
add_action('wp_head', 'wp_courses_ajaxurl'); 

function wp_courses_ajaxurl() {
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// add empty lightbox and ajax save icon to footer

add_action('wp_footer', 'wpc_lightbox');

add_action('admin_footer', 'wpc_lightbox');

function wpc_lightbox(){
    echo '<div class="wpc-lightbox-wrapper" style="display: none;">
        <div class="wpc-lightbox">
            <div class="wpc-lightbox-close-wrapper">
                <div class="wpc-lightbox-close"><i class="fa fa-times"></i></div>
            </div>
            <div class="wpc-lightbox-content">

            </div>
        </div>
    </div>';
    // ajax save icon
    echo '<div id="wpc-ajax-save" class="fa-2x" style="display: none;"><i></i></div>';
}

// admin lesson course filter

/* 
add_filter( 'parse_query', 'wpc_filter_lessons_by_course' );

function wpc_filter_lessons_by_course( $query ) {
  global $pagenow;
  // Get the post type
  $post_type = isset( $_GET['post_type'] ) ? sanitize_title_with_dashes( $_GET['post_type'] ) : '';
  if ( is_admin() && $post_type == 'lesson' && isset( $_GET['wpc-course-filter'] ) && $_GET['wpc-course-filter'] !='all' ) {
    $query->query_vars['meta_key'] = sanitize_title_with_dashes('wpc-connected-lesson-to-course');
    $query->query_vars['meta_value'] = sanitize_title_with_dashes($_GET['wpc-course-filter']);
    $query->query_vars['meta_compare'] = '=';
  }
}

add_action( 'restrict_manage_posts', 'wpc_course_filter_select' );
 
function wpc_course_filter_select() {   
    if(!empty($_GET['post_type'])){
        $post_type = sanitize_title_with_dashes($_GET['post_type']);
        if($post_type == 'lesson'){
            global $wpdb;
            $sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
            $results = $wpdb->get_results($sql);

            echo '<select name="wpc-course-filter" class="wpc-admin-select">';

                echo '<option value="all">' . __('All Courses', 'wp-courses') . '</option>';
                echo '<option value="none">' . __('None', 'wp-courses') . '</option>';

            foreach ($results as $result) {
                echo '<option value="' . (int) $result->ID . '">' . esc_html($result->post_title) . '</option>';
            }

            echo '</select>';
        }
    }
}
*/

// courses and teachers per page

function wpc_num_posts($query) {

    $wpc_teachers_per_page = (int) get_option('wpc_teachers_per_page');

    if ( is_post_type_archive( 'teacher' ) && !is_admin() && !empty($wpc_teachers_per_page)) {
            $query->set('posts_per_page', $wpc_teachers_per_page);
    }

    if( is_post_type_archive( 'course' ) || is_tax('course-category') ){
        $wpc_courses_per_page = (int) get_option('wpc_courses_per_page');
        if (!is_admin() && !empty($wpc_courses_per_page)) {
                $query->set('posts_per_page', $wpc_courses_per_page);
        }
    }

    return $query;
}
add_filter('pre_get_posts', 'wpc_num_posts', 100);

// add localization
add_action('plugins_loaded', 'wpc_load_textdomain');
function wpc_load_textdomain() {
    load_plugin_textdomain( 'wp-courses', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

function wpc_remove_lesson_rest_api_data( $data, $post, $context ) {

	$can_edit = current_user_can( 'edit_posts' ); 

	if ( $can_edit != true ) {

	    $wpc_enable_rest_lesson = get_option( 'wpc_enable_rest_lesson' );

	    if( $wpc_enable_rest_lesson != 'true' ){
	        unset ($data->data ['content']);
	        unset ($data->data ['excerpt']);
	    }
	}

	return $data;

}

add_filter( 'rest_prepare_lesson', 'wpc_remove_lesson_rest_api_data', 12, 3 );

// order courses by menu order in course archive and coures category archive unless different order selected by user

add_action( 'pre_get_posts', 'wpc_change_courses_sort_order'); 

function wpc_change_courses_sort_order($query){

    if( is_post_type_archive( 'course' ) && $query->is_main_query() || is_tax() == 'course-category' && $query->is_main_query() ) {

        if( isset( $_GET['order'] ) ){

            $value =  sanitize_title_with_dashes( $_GET['order'] );

            if( $value == 'default' ){
                $query->set( 'order', 'ASC' );
                $query->set( 'orderby', 'menu_order' );
            } elseif( $value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif( $value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif( $value == 'alphabetical' ) {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set( 'order', 'ASC' );
            $query->set( 'orderby', 'menu_order' );
        }

        $courses_per_page = (int) get_option('wpc_courses_per_page');

        if( isset( $_GET['search'] ) ) {
            $search = sanitize_text_field( $_GET['search'] );
            $query->set( 's', $search );            
        }
    }
};

// order lessons by menu order in lesson archive unless different order selected by user

add_action( 'pre_get_posts', 'wpc_change_lesson_archive_sort_order'); 

function wpc_change_lesson_archive_sort_order($query){

    if( is_post_type_archive( 'lesson' ) && $query->is_main_query() ) {

        if( isset( $_GET['order'] ) ){

            $value =  sanitize_title_with_dashes( $_GET['order'] );

            if( $value == 'default' ){
                $query->set( 'order', 'ASC' );
                $query->set( 'orderby', 'menu_order' );
            } elseif( $value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif( $value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif( $value == 'alphabetical' ) {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set( 'order', 'ASC' );
            $query->set( 'orderby', 'menu_order' );
        }

        if( isset( $_GET['search'] ) ) {
            $search = sanitize_text_field( $_GET['search'] );
            $query->set( 's', $search );            
        }
    }
};

// add video to content in single lessons.  This way PMPro can filter the lesson content including the video.
add_filter('the_content', 'wpc_add_video_to_lesson_content', 2);

function wpc_add_video_to_lesson_content($content){
    if(get_post_type() == 'lesson' && is_single()) {
        $lesson_id = get_the_ID();       
        $video = wpc_get_video($lesson_id);
        $wpc_tools = new WPC_Tools();
        $toolbar = $wpc_tools->get_toolbar();
        return '<div id="video-wrapper" class="wpc-video-wrapper">' . wpc_sanitize_video($video) . '</div>' . $toolbar . $content;
    } else {
        return $content;
    }

}

// show top admin nav menu across differen cp types

function wpc_admin_nav_menu_display_logic(){
    $show = false;

    if(isset($_GET['taxonomy'])){
        if( $_GET['taxonomy'] == 'course-difficulty' || $_GET['taxonomy'] == 'course-category'){
            $show = true;
        }
    }

    $post_type = get_post_type();

    if($post_type == 'lesson' || $post_type == 'course' || $post_type == 'wpc-quiz' || $post_type == 'teacher' || $post_type == 'wpc-badge' || $post_type == 'wpc-email' || $post_type == 'wpc-certificate'){
        if(is_archive()){
            $show = true;
        }
    }

    if(!is_admin()){
        $show = false;
    }

    return $show;
}

add_action('in_admin_header', 'wpc_admin_nav_menu');

function wpc_admin_nav_menu(){
    $show = wpc_admin_nav_menu_display_logic();
    if( $show == true ){
        include 'admin/admin-nav-menu.php';
    }
}

add_action('admin_footer', 'wpc_admin_screen_options_styling');

// shows the admin nav menu after screen options
function wpc_admin_screen_options_styling(){
    $show = wpc_admin_nav_menu_display_logic();
    if($show == true){
    echo    '<script>
                jQuery(document).ready(function($){
                    var nav = $(".wpc-admin-nav-menu");
                    var navClone = nav.clone();
                    nav.remove();
                    $("#screen-meta-links").after(nav);
                });

            </script>';
    }
}

?>