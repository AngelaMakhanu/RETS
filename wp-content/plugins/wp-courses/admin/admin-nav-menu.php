<div class="wpc-admin-nav-menu">
    <div class="wpc-alt-admin-buttons">
        <?php if( is_plugin_active( 'wp-courses-premium/wp-courses-premium.php' ) == false ) { ?>
        <a href="https://wpcoursesplugin.com/store/" class="wpc-admin-button wpc-admin-button-alt"><i class="fa fa-shopping-cart"></i>  <?php esc_html_e(
            'Upgrade to Premium', 'wp-courses'); ?></a>
        <?php } ?>
        <a href="https://wpcoursesplugin.com/affiliate-dashboard/" class="wpc-admin-button"><i class="fa fa-user-plus"></i> <?php esc_html_e('Affililate Program', 'wp-courses'); ?> </a>
        <a href="admin.php?page=wpc_help" class="wpc-admin-button"><i class="fa fa-question"></i>  <?php esc_html_e('Tutorials and Help', 'wp-courses');?></a>
        <a href="http://wpcoursesplugin.com/contact" class="wpc-admin-button"><i class="fa fa-envelope"></i> <?php esc_html_e('Contact', 'wp-courses'); ?> WP Courses</a>
        <a href="https://wordpress.org/support/plugin/wp-courses/reviews/" class="wpc-admin-button"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <?php esc_html_e('Leave a Review', 'wp-courses'); ?></a>

    </div>
    <div class="wpc-admin-menu-wrapper">
        <a href="admin.php?page=wpc_settings" id="wpc-admin-logo-sm-wrapper">
            <img src="<?php echo WPC_PLUGIN_URL; ?>images/wpc-logo-sm.png" id="wpc-admin-logo-sm" style="max-width: 150px;"/>
        </a>
        <ul class="wpc-alt-admin-menu">
            <li class="wpc-admin-menu-item"><a href="admin.php?page=wpc_settings"><?php esc_html_e('Dashboard', 'wp-courses'); ?></a></li>
            <li class="wpc-admin-menu-item"><a href="admin.php?page=wpc_options"><?php esc_html_e('Options', 'wp-courses'); ?></a></li>
            <li class="wpc-admin-menu-item wpc-submenu-toggle">
                <span><?php esc_html_e('Courses', 'wp-courses'); ?><span class="dashicons dashicons-arrow-down"></span></span>
                <ul class="wpc-admin-submenu" style="display: none;">
                    <li><a href="edit.php?post_type=course"><?php esc_html_e('Manage Courses', 'wp-courses'); ?></a></li>
                    <li><a href="edit-tags.php?taxonomy=course-category&post_type=course"><?php esc_html_e('Course Categories', 'wp-courses'); ?></a></li>
                    <li><a href="edit-tags.php?taxonomy=course-difficulty&post_type=course"><?php esc_html_e('Course Difficulties', 'wp-courses'); ?></a></li>
                    <li><a href="admin.php?page=order_courses"><?php esc_html_e('Order Courses', 'wp-courses'); ?></a></li>
                </ul>
            </li>

            <li class="wpc-admin-menu-item wpc-submenu-toggle">
                <span><?php esc_html_e('Lessons and Modules', 'wp-courses'); ?><span class="dashicons dashicons-arrow-down"></span></span>
                <ul class="wpc-admin-submenu" style="display: none;">
                    <li><a href="edit.php?post_type=lesson"><?php esc_html_e('Manage Lessons', 'wp-courses'); ?></a></li>
                    <?php do_action('wpc_after_admin_nav_menu_manage_lessons'); ?>
                    <li><a href="admin.php?page=order_lessons"><?php esc_html_e('Order Lessons and Manage Modules', 'wp-courses'); ?></a></li>
                </ul>
            </li>
        	
            <li class="wpc-admin-menu-item"><a href="edit.php?post_type=teacher"><?php esc_html_e('Teachers', 'wp-courses'); ?></a></li>
            <li class="wpc-admin-menu-item"><a href="admin.php?page=manage_students"><?php esc_html_e('Student Progress', 'wp-courses'); ?></a></li> 
            <?php do_action('wpc_after_admin_nav_menu'); ?> 
        </ul>
    </div>
</div>