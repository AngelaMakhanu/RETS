<?php
// create custom plugin settings menu
add_action('admin_menu', 'wpc_create_menu');
function wpc_create_menu() {
	//create new top-level menu
	add_menu_page('WP Courses', 'WP Courses', 'administrator', 'wpc_settings', 'wpc_settings_page', "https://wpcoursesplugin.com/wp-content/plugins/wp-courses/images/wpc-icon-sm-white.png");
	//call register settings function
	//add_action( 'admin_init', 'wpc_register_settings' );
}
function wpc_settings_page(){ ?>
	<?php include 'admin-nav-menu.php'; ?>
	<div class="wpc-main-admin-wrapper wrap">
		<?php include 'dashboard.php'; ?>
	</div>
<?php }
function wpc_register_submenu(){
	// add_submenu_page( 'wpc_settings', 'Add-Ons', 'Add-Ons', 'manage_options', 'admin.php?page=wpc_settings' );
	add_submenu_page( 'wpc_settings', 'Options', 'Options', 'manage_options', 'wpc_options', 'wpc_options_page' );
	add_submenu_page( 'wpc_settings', 'Help', 'Help', 'manage_options', 'wpc_help', 'wpc_help_page' );
    add_submenu_page( 'wpc_settings', 'Teachers', 'Teachers', 'manage_options', 'edit.php?post_type=teacher' );
    add_submenu_page( 'wpc_settings', 'Course Categories', 'Course Categories', 'manage_options', 'edit-tags.php?taxonomy=course-category&post_type=course' );
    add_submenu_page( 'wpc_settings', 'Course Difficulties', 'Course Difficulties', 'manage_options', 'edit-tags.php?taxonomy=course-difficulty&post_type=course' );
    add_submenu_page( 'wpc_settings', 'Courses', 'Courses', 'manage_options', 'edit.php?post_type=course' );
    add_submenu_page( 'wpc_settings', 'Lessons', 'Lessons', 'manage_options', 'edit.php?post_type=lesson' );
    add_submenu_page( 'wpc_settings', 'Order Lessons and Manage Modules', 'Order Lessons and Manage Modules', 'manage_options', 'order_lessons', 'wpc_order_lessons_page' );
    add_submenu_page( 'wpc_settings', 'Order Courses', 'Order Courses', 'manage_options', 'order_courses', 'wpc_order_courses_page' );
    add_submenu_page( 'wpc_settings', 'Students and Progress', 'Student Progress', 'manage_options', 'manage_students', 'wpc_manage_students_page' );

    // don't show this menu if premium is active
    if( is_plugin_active( 'wp-courses-premium/wp-courses-premium.php' ) == false ) {
    	add_submenu_page( 'wpc_settings', 'Premium', 'Premium', 'manage_options', 'wpc_premium', 'wpc_premium_page' );
    }

    do_action( 'wpc_after_register_submenu' );
}

add_action('admin_menu', 'wpc_register_submenu');

function wpc_premium_page() { ?>
	<?php include 'admin-nav-menu.php'; ?>
		<div class="wrap">
			<div class="wpc-admin-section wpc-admin-box-left">
				<h1 class="wpc-admin-h1">Included Premium Add-Ons</h1>
			    <div class="wpc-admin-box-wrapper">
			        <div class="wpc-admin-box wpc-6">
			            <h2 class="wpc-admin-box-header">WooCommerce E-Commerce Integration</h2>
			            <div class="wpc-video-wrapper">
			            	<iframe width="560" height="315" src="https://www.youtube.com/embed/-tVLqheACJs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			            </div>
			            <p>An integration between WP Courses and WooCommerce that allows you to sell courses in your WooCommerce store.  Accept PayPal, Stripe, credit cards and more!</p>
			            <a href="https://wpcoursesplugin.com/wp-courses-premium/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
			        </div>
			    	<div class="wpc-admin-box wpc-6">
			    		<h2 class="wpc-admin-box-header">Paid Memberships Pro Integration</h2>
			    		<div class="wpc-video-wrapper">
			    			<iframe width="560" height="315" src="https://www.youtube.com/embed/vxzf-GrpF8w" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			    		</div>
			    		<p>An integration between WP Courses and Paid Memberships Pro that allows you to sell and restrict access to your course content.</p>
			    		<a href="https://wpcoursesplugin.com/wp-courses-premium/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
			    	</div>
			    </div>
			    <div class="wpc-admin-box-wrapper">
			    	<div class="wpc-admin-box wpc-6">
			            <h2 class="wpc-admin-box-header">Automated Emails</h2>
			            <div class="wpc-video-wrapper">
			            	<iframe width="560" height="315" src="https://www.youtube.com/embed/C6zkvOyhDDc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			            </div>
			            <p>Boost your course engagement and up your marketing game by triggering automated emails based on your student's actions.</p>
			            <a href="https://wpcoursesplugin.com/wp-courses-premium/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
			        </div>
			        <div class="wpc-admin-box wpc-6">
			            <h2 class="wpc-admin-box-header">Badges</h2>
			            <div class="wpc-video-wrapper">
			            	<iframe width="560" height="315" src="https://www.youtube.com/embed/IRZEImL4UPs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			            </div>
			            <p>Award badges to students when they complete certain actions such as completing a course or scoring a certain percentage on a quiz.</p>
			            <a href="https://wpcoursesplugin.com/wp-courses-premium/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
			        </div>
			    </div>
			    <div class="wpc-admin-box-wrapper">
		        <div class="wpc-admin-box wpc-6">
		            <h2 class="wpc-admin-box-header">Quizzes</h2>
		            <div class="wpc-video-wrapper">
		            	<iframe width="560" height="315" src="https://www.youtube.com/embed/hIFc9WLX3uU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		            </div>
		            <p>Integrates with WP Courses so you can add multiple choice quizzes to your courses!  Automatically scores quizzes and saves results.</p>
		            <a href="https://wpcoursesplugin.com/wp-courses-premium/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
		        </div>
		        <div class="wpc-admin-box wpc-6">
		            <h2 class="wpc-admin-box-header">Lesson Attachments</h2>
		            <div class="wpc-video-wrapper">
		            	<iframe width="560" height="315" src="https://www.youtube.com/embed/b2FEmDEfIgs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		            </div>
		            <p>Integrates with WP Courses so you can attach PDF's, media and more to specific lessons.</p>
		            <a href="https://wpcoursesplugin.com/wp-courses-premium/" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
		        </div>
		    </div>
		</div>
		<div class="wpc-admin-section wpc-admin-box-right">
			<h1 class="wpc-admin-h1">Details</h1>
			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header">WP Courses Premium</h2>
				<div id="wpc-premium-box-wrapper">
					<img src="<?php echo WPC_PLUGIN_URL; ?>images/premium.png" id="wpc-premium-box-img"/>
				</div>
				<div style="text-align: center;">
				<a href="https://wpcoursesplugin.com/wp-courses-premium/" style="margin: 0 auto;" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
				</div>
			</div>
			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header">Comparison</h2>
				<p></p>
				<table class="wpc-premium-table" style="background: white;" cellspacing="0">
				  <thead>
				  	<tr>
				      <th></th>
				      <th style="text-align:center;">WP Courses</th>
				      <th style="text-align:center;">WP Courses Premium</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				    	<td>Unlimited Courses and Lessons</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>User Profiles</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Progress Tracking</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Teacher Pages</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Multimedia Lessons</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Display and Design Options</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Lesson Restriction</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Video Tutorials</td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Unlimited Support</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Badges</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Automated Emails</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>WooCommerce Integration</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Paid Memberships Pro Integration</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Quizzes</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				    <tr>
				    	<td>Lesson Attachments</td>
				      <td style="text-align:center;"><i class="fa fa-times"></i></td>
				      <td style="text-align:center;"><i class="fa fa-check"></i></td>
				    </tr>
				  </tbody>
				</table>
			</div>
		</div>
<?php }

function wpc_help_page(){ ?>
	<?php include 'admin-nav-menu.php'; ?>
	<div class="wrap">
		<div class="wpc-admin-box-left">
			<h1 class="wpc-admin-h1">Welcome</h1>
			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header">Setup Instructions</h2>
				<p>After you’ve installed and activated WP Courses, you’ll create a custom menu link to your course archive as well as a new page with the shortcode [wpc_profile] in it.  You’ll then create another menu link to your profile page.</p>
				<ol>
					<li>Go to “Appearance->Menus” and create a new custom link to <a href="<?php echo home_url(); ?>/?post_type=course"><?php echo home_url(); ?>/?post_type=course</a>.</li>
					<li>Click “Save Menu.”</li>
					<li>Create a new page called “my profile” or whatever else you’d like to call it.</li>
					<li>Include the shortcode [wpc_profile] in the profile page you just created.</li>
					<li>Go to “Appearance->Menus” and create a menu item which links to the profile page you just created.</li>
					<li>Click “Save Menu.</li>
				</ol>
			</div>
			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header">Creating Courses</h2>
				<p>Below you'll find links to detailed video tutorials on how to create courses using WP Courses.</p>
				<ul class="lesson-list">
					<h3 class="wpc-module-title">Overview</h3>
					<li><a href="https://wpcoursesplugin.com/lesson/how-courses-are-structured/?course_id=321">How Courses are Structured</a></li>
					<h3 class="wpc-module-title">Theme Integration</h3>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-integrate-wp-courses-with-your-theme/?course_id=321">How to Integrate WP Courses with Your Theme</a></li>
					<h3 class="wpc-module-title">Teachers</h3>
					<li><a href="https://wpcoursesplugin.com/lesson/creating-teachers-in-wp-courses/">How to Create and Manage Teachers</a></li>
					<h3 class="wpc-module-title">Courses</h3>
					<li><a href="https://wpcoursesplugin.com/lesson/course-difficulties-in-wp-courses/">Course Difficulties</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/course-categories-in-wp-courses/">Course Categories</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-create-courses-in-wp-courses/">Creating Your First Course</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-order-courses-in-wp-courses/">How to Order Courses</a></li>
					<h3 class="wpc-module-title">Lessons and Modules</h3>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-create-lessons-and-connect-them-to-courses/">How to Create Lessons and Connect Them to Courses</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-order-lessons-in-wp-courses/">How to Order Lessons</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-create-and-manage-modules/">How to Create and Manage Modules</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/quickly-managing-lessons-with-wp-courses/">Quick Lesson Management</a></li>
					<li><a href="https://wpcoursesplugin.com/lesson/lessons-with-no-home-in-wp-courses/">Lessons without a Home</a></li>
					<h3 class="wpc-module-title">Translations</h3>
					<li><a href="https://wpcoursesplugin.com/lesson/how-to-translate-wp-courses/">How to Translate WP Courses</a></li>
				</ul>
			</div>
		</div>
		<div class="wpc-admin-box-right">
			<h1 class="wpc-admin-h1">Upgrade</h1>
			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header">WP Courses Premium</h2>
				<div id="wpc-premium-box-wrapper">
					<img src="<?php echo WPC_PLUGIN_URL; ?>images/premium.png" id="wpc-premium-box-img"/>
				</div>
				<div style="text-align: center;">
					<a href="https://wpcoursesplugin.com/wp-courses-premium/" style="margin: 0 auto;" class="wpc-admin-button wpc-admin-button-lg">Learn More</a>
				</div>
			</div>
		</div>
	</div>
<?php }

function wpc_manage_students_page(){
	include 'admin-nav-menu.php'; ?>
	<div class="wrap">

		<div class="wpc-admin-box">

				<?php $wpc_courses = new WPC_Courses();

				// single course progress page

				if(isset($_GET['course_id'])){ 

					$user = get_user_by( 'ID', (int) $_GET['student_id'] ); 

					?>

					<div class="wpc-admin-box-content">
						<?php echo '<h2 class="wpc-admin-box-header">' . $user->display_name . "'s " . esc_html__('Progress', 'wp-courses') . ' for: ' . get_the_title( (int) $_GET['course_id'] ) . '</h1>'; ?>
						<div class="wpc-light-box wpc-admin-course-progress">
							<?php echo wpc_get_lesson_navigation((int) $_GET['course_id'], (int) $_GET['student_id']); ?>
						</div>
						<div class="wpc-admin-box-footer">
							<a href="?page=manage_students&student_id=<?php echo (int) $_GET['student_id']; ?>" class="wpc-admin-button"><?php esc_html_e('Back', 'wp-courses'); ?></a>
						</div>
					</div>

				<?php } elseif(isset($_GET['quiz_id'])){ ?>

					<?php echo wpcq_single_quiz_result_table( (int) $_GET['quiz_id'], (int) $_GET['student_id']); ?>

				<?php } elseif(isset($_GET['student_id'])){

					$user = get_user_by( 'ID', (int) $_GET['student_id'] ); ?>

					<h2 class="wpc-admin-box-header"><?php echo esc_html( $user->display_name ); ?></h1>

					<div class="wpc-admin-box-content">

						<h2 class="nav-tab-wrapper wpc-nav-tab-wrapper">
							<a href="#" class="nav-tab wpc-nav-tab nav-tab-active"><i class="fa fa-bar-chart"></i> <?php esc_html_e('Course Progress', 'wp-courses'); ?></a>
							<a href="#" class="nav-tab wpc-nav-tab"><i class="fa fa-eye"></i> <?php esc_html_e('Viewed Lessons', 'wp-courses'); ?></a>
							<a href="#" class="nav-tab wpc-nav-tab"><i class="fa fa-check"></i> <?php esc_html_e('Completed Lessons', 'wp-courses'); ?></a>
							<?php do_action( 'wpc_admin_after_student_nav_tabs' ); ?>
						</h2>

						<div id="wpc-user-viewed-lessons" class="wpc-tab-content">
							<h2><?php _e('Course Progress', 'wp-courses'); ?></h2>
							<?php echo wpc_get_course_progress_table($user->ID); ?>
						</div>

						<div id="wpc-user-viewed-lessons" class="wpc-tab-content wpc-hide">
							<h2><?php _e('Viewed Lessons', 'wp-courses'); ?></h2>
							<?php echo wpc_get_lesson_tracking_table($user->ID, 0); ?>
						</div>

						<div id="wpc-user-completed-lessons" class="wpc-hide wpc-tab-content">

						<h2><?php _e('Completed Lessons', 'wp-courses'); ?></h2>
							<?php echo wpc_get_lesson_tracking_table($user->ID, 1); ?>
						</div>

						<?php do_action( 'wpc_admin_after_student_nav_tabs_content' ); ?>						
					</div>

					<div class="wpc-admin-box-footer">
						<a href="?page=manage_students" class="wpc-admin-button"><?php esc_html_e('Back', 'wp-courses'); ?></a>
					</div>
			</div>
		</div> <!-- wrap -->
			
		<?php } else { ?>
			<?php include 'wpc-admin-student-table.php'; ?>
		<?php } ?>



<?php }

// Order lesson page
function wpc_order_lessons_page(){
	$wpc_admin = new WPC_Admin();

	if(isset( $_GET['course-selection']) ) {
		$course_id = (int) $_GET['course-selection'];
	} else {
		$course_id = -1;
	}

	include 'admin-nav-menu.php';
	echo '<div class="wrap">';
		echo '<div class="wpc-admin-box">';
		echo '<h2 class="wpc-admin-box-header">' .esc_html__('Order Lessons and Manage Modules', 'wp-courses') . '</h2>';
		echo '<div class="wpc-admin-box-toolbar">';
			echo '<form action="" method="get">';
			echo '<div class="tablenav top">';
				echo '<div class="alignleft actions">';
					echo $wpc_admin->get_course_dropdown($course_id);
					echo '<input name="page" value="order_lessons" type="hidden"/>';
					echo '<button type="submit" class="wpc-admin-button" id="lesson-order-course-select">' . esc_html( __('Select', 'wp-courses') ) . '</button>';
				echo '</div>';

				if(isset($_GET['course-selection'])){
					echo '<button type="button" id="wpc-add-module" class="wpc-admin-button">' . esc_html( __('Add Module', 'wp-courses') ) . '</button>';
					echo '<button type="button" id="wpc-save-order" class="wpc-admin-button wpc-admin-button-green" style="margin-left: 10px;">' . esc_html( __('Save', 'wp-courses') ) . '</button>';
				}

			echo '</div>';
			echo '</form>';
		echo '</div>';

	if(isset($_GET['course-selection'])){
		if( (int) $_GET['course-selection'] != -1){
			$course_id = (int) $_GET['course-selection'];
			wpc_admin_lesson_list($course_id);
		}
	}

	echo '</div>';
	echo '</div>';
}
add_action( 'admin_footer', 'wpc_change_lesson_restriction_javascript' );

function wpc_order_courses_page(){ ?>
		<?php include 'admin-nav-menu.php'; ?>
		<div class="wrap">
			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header"><?php esc_html_e('Order Courses', 'wp-courses'); ?></h2>
				<div class="wpc-admin-box-content">
					<div id="order-course-msg-wrapper"></div>
					<div>
					<?php 
						$wpc_courses = new WPC_Admin();
						echo $wpc_courses->get_course_list();
					?>
					</div>
				</div>
			</div>
		</div>
<?php }