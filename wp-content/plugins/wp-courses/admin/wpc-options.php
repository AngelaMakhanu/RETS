<?php 

function wpc_register_settings() {

	add_option( 'wpc_enable_powered_by', 'false');

   	add_option( 'wpc_enable_rest_lesson', 'false');

   	add_option( 'wpc_show_course_search', 'true');

   	add_option( 'wpc_show_breadcrumb_trail', 'true');
   	add_option( 'wpc_show_lesson_numbers', 'true');
   	add_option( 'wpc_show_completed_lessons', 'true');
   	add_option( 'wpc_show_lesson_nav_icons', 'true');
   	add_option( 'wpc_courses_per_page', 10);
   	add_option( 'wpc_teachers_per_page', 10);
   	add_option( 'wpc_logged_out_message' );

   	add_option( 'wpc_primary_bg_color', 'transparent');

	add_option('wpc_button_border_radius', '4px');
	add_option('wpc_button_border_width', '1px');
	add_option('wpc_button_font_size', '18px');

   	add_option( 'wpc_primary_button_color', '#23d19f');
   	add_option( 'wpc_primary_button_border_color', '#12ad80');
   	add_option( 'wpc_primary_button_text_color', '#fff');

   	add_option( 'wpc_primary_button_hover_color', '#12ad80');
   	add_option( 'wpc_primary_button_hover_border_color', '#12ad80');
   	add_option( 'wpc_primary_button_hover_text_color', '#fff');

   	add_option( 'wpc_primary_button_active_color', '#009ee5');
   	add_option( 'wpc_primary_button_active_border_color', '#027fb7');
   	add_option( 'wpc_primary_button_active_text_color', '#fff');

   	add_option('wpc_row_width', '80%');
	add_option('wpc_row_max_width', '1080px');
	add_option('wpc_h1_font_size');
	add_option('wpc_h2_font_size');
	add_option('wpc_h3_font_size');
	add_option('wpc_container_padding_top', '60px');
	add_option('wpc_container_padding_bottom', '60px');
	add_option('wpc_container_padding_left');
	add_option('wpc_container_padding_right');

   	register_setting( 'wpc_options', 'wpc_enable_powered_by', array( "sanitize_callback" => 'sanitize_title' ) );

	register_setting( 'wpc_options', 'wpc_enable_rest_lesson', array( "sanitize_callback" => 'sanitize_title' ) );

	register_setting( 'wpc_options', 'wpc_show_course_search', array( "sanitize_callback" => 'sanitize_title' ) );

	register_setting( 'wpc_options', 'wpc_show_breadcrumb_trail', array( "sanitize_callback" => 'sanitize_title' ) );
   	register_setting( 'wpc_options', 'wpc_show_lesson_numbers', array( "sanitize_callback" => 'sanitize_title' ) );
   	register_setting( 'wpc_options', 'wpc_show_completed_lessons', array( "sanitize_callback" => 'sanitize_title' ) );
   	register_setting( 'wpc_options', 'wpc_show_lesson_nav_icons', array( "sanitize_callback" => 'sanitize_title' ) );
   	register_setting( 'wpc_options', 'wpc_courses_per_page', array( "sanitize_callback" => 'sanitize_title' ) );
   	register_setting( 'wpc_options', 'wpc_teachers_per_page', array( "sanitize_callback" => 'sanitize_title' ) );
   	register_setting( 'wpc_options', 'wpc_logged_out_message' );

   	register_setting('wpc_options', 'wpc_primary_bg_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );

   	register_setting('wpc_options', 'wpc_button_border_radius', array( "sanitize_callback" => 'sanitize_text_field' ));
	register_setting('wpc_options', 'wpc_button_border_width', array( "sanitize_callback" => 'sanitize_text_field' ));
	register_setting('wpc_options', 'wpc_button_font_size', array( "sanitize_callback" => 'sanitize_text_field' ));

   	register_setting( 'wpc_options', 'wpc_primary_button_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
   	register_setting( 'wpc_options', 'wpc_primary_button_border_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
    register_setting( 'wpc_options', 'wpc_primary_button_text_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );

    register_setting( 'wpc_options', 'wpc_primary_button_hover_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
   	register_setting( 'wpc_options', 'wpc_primary_button_hover_border_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
    register_setting( 'wpc_options', 'wpc_primary_button_hover_text_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );

    register_setting( 'wpc_options', 'wpc_primary_button_active_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
   	register_setting( 'wpc_options', 'wpc_primary_button_active_border_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );
    register_setting( 'wpc_options', 'wpc_primary_button_active_text_color', array( "sanitize_callback" => 'sanitize_hex_color' ) );

    register_setting('wpc_options', 'wpc_row_width', array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_row_max_width',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_h1_font_size',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_h2_font_size',  array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_h3_font_size',  array( "sanitize_callback" => 'sanitize_text_field' ) );

	register_setting('wpc_options', 'wpc_container_padding_top', array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_container_padding_bottom', array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_container_padding_left', array( "sanitize_callback" => 'sanitize_text_field' ) );
	register_setting('wpc_options', 'wpc_container_padding_right', array( "sanitize_callback" => 'sanitize_text_field' ) );
}
add_action( 'admin_init', 'wpc_register_settings' );

function wpc_options_page(){ ?>

	<?php include 'admin-nav-menu.php'; ?>

	<div class="wrap">
		<div class="wpc-row wpc-options-page-wrapper wpc-sticky-wrapper" id="wpc-options-page-wrapper">
			<div class="wpc-4 wpc-admin-options-menu-wrapper" id="wpc-sticky-sidebar">
				<div class="wpc-admin-options-menu sidebar__inner">
					<ul >
						<?php do_action( 'wpc_before_options_menu' ); ?>
						<li data-elem-id="wpc-general-options">General</li>
						<li data-elem-id="wpc-display-options">Display</li>
						<li data-elem-id="wpc-design-options">Design</li>
						<?php do_action( 'wpc_after_options_menu' ); ?>
					</ul>
				</div>
			</div>
			<div class="wpc-8 wpc-sticky-tall">
				<?php // screen_icon(); ?>

				<form method="post" action="options.php">
					<?php 

					settings_fields( 'wpc_options' );

					$wpc_enable_powered_by = get_option('wpc_enable_powered_by');

					$wpc_enable_rest_lesson = get_option('wpc_enable_rest_lesson');

					$wpc_show_course_search = get_option('wpc_show_course_search');

					$wpc_show_breadcrumb_trail = get_option('wpc_show_breadcrumb_trail');
					$wpc_show_lesson_numbers = get_option('wpc_show_lesson_numbers');
					$wpc_show_completed_lessons = get_option('wpc_show_completed_lessons');
					$wpc_show_lesson_nav_icons = get_option('wpc_show_lesson_nav_icons');
					$wpc_courses_per_page = get_option('wpc_courses_per_page');
					$wpc_teachers_per_page = get_option('wpc_teachers_per_page');
					$wpc_logged_out_message = get_option('wpc_logged_out_message');

					$wpc_primary_bg_color = get_option('wpc_primary_bg_color', 'transparent');

					$button_border_radius = wpc_esc_unit(get_option('wpc_button_border_radius'), 'px');
				    $button_border_width = wpc_esc_unit(get_option('wpc_button_border_width'), 'px');
				    $button_font_size = wpc_esc_unit(get_option('wpc_button_font_size'), 'px');

					$wpc_primary_button_color = get_option('wpc_primary_button_color', '#23d19f');
					$wpc_primary_button_border_color = get_option('wpc_primary_button_border_color', '#12ad80');
					$wpc_primary_button_text_color = get_option('wpc_primary_button_text_color', '#fff');

					$wpc_primary_button_hover_color = get_option('wpc_primary_button_hover_color', '#12ad80');
					$wpc_primary_button_hover_border_color = get_option('wpc_primary_button_hover_border_color', '#12ad80');
					$wpc_primary_button_hover_text_color = get_option('wpc_primary_button_hover_text_color', '#fff');

					$wpc_primary_button_active_color = get_option('wpc_primary_button_active_color', '#009ee5');
					$wpc_primary_button_active_border_color = get_option('wpc_primary_button_active_border_color', '#027fb7');
					$wpc_primary_button_active_text_color = get_option('wpc_primary_button_active_text_color', '#fff');

					$width = get_option('wpc_row_width');
					$max_width = get_option('wpc_row_max_width');
					$h1 = get_option('wpc_h1_font_size');
					$h2 = get_option('wpc_h2_font_size');
					$h3 = get_option('wpc_h3_font_size');
				    $container_padding_top = get_option('wpc_container_padding_top'); 
				    $container_padding_top = wpc_esc_unit($container_padding_top, 'px');
				    $container_padding_bottom = get_option('wpc_container_padding_bottom'); 
				    $container_padding_bottom = wpc_esc_unit($container_padding_bottom, 'px');
				    $container_padding_left = get_option('wpc_container_padding_left'); 
				    $container_padding_left = wpc_esc_unit($container_padding_left, 'px');
				    $container_padding_right = get_option('wpc_container_padding_right'); 
				    $container_padding_right = wpc_esc_unit($container_padding_right, 'px');

					$id = get_the_ID();

					if( $wpc_enable_powered_by == 'true' ){
						$powered_by_checked = 'checked';
					} else {
						$powered_by_checked = '';
					}

					if( $wpc_enable_rest_lesson == 'true' ){
						$rest_lesson_checked = 'checked';
					} else {
						$rest_lesson_checked = '';
					}

					if( $wpc_show_course_search == 'true' ){
						$course_search_checked = 'checked';
					} else {
						$course_search_checked = '';
					}

					if($wpc_show_breadcrumb_trail == 'true'){
						$breadcrumb_checked = 'checked';
					} else {
						$breadcrumb_checked = '';
					}

					if($wpc_show_lesson_numbers == 'true'){
						$lesson_numbers_checked = 'checked';
					} else {
						$lesson_numbers_checked = '';
					}

					if($wpc_show_completed_lessons == 'true'){
						$completed_checked = 'checked';
					} else {
						$completed_checked = '';
					}

					if($wpc_show_lesson_nav_icons == 'true'){
						$icons_checked = 'checked';
					} else {
						$icons_checked = '';
					}

					?>

					<?php do_action('wpc_before_options'); ?>

					<div id="wpc-general-options" class="wpc-admin-box">

						<h2 class="wpc-admin-box-header">General Options</h2>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Display</h2>
								<label> Show "Powered by WP Courses at the bottom of your courses page.</label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-enable-powered-by">
										<input type="checkbox" id="wpc-enable-powered-by" name="wpc_enable_powered_by" value="true" <?php echo $powered_by_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Rest API</h2>
								<label> Show lesson content in the REST API.  <b>Warning!</b>  If checked, restricted lesson content is accessable <b>TO ANYONE</b> via the REST API. </label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-enable-lesson-rest">
										<input type="checkbox" id="wpc-enable-lesson-rest" name="wpc_enable_rest_lesson" value="true" <?php echo $rest_lesson_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

					</div>

					<div id="wpc-display-options" class="wpc-admin-box">

						<h2 class="wpc-admin-box-header">Display Options</h2>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Course Search</h2>
								<label>Display the course search input on the course archive page.</label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch for="wpc-course-search">
										<input type="checkbox" id="wpc-course-search" name="wpc_show_course_search" value="true" <?php echo $course_search_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Breadcrumb Trail</h2>
								<label>Display the breadcrumb trail on lesson pages.</label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-breadcrumb-trail">
										<input type="checkbox" id="wpc-show-breadcrumb-trail" name="wpc_show_breadcrumb_trail" value="true" <?php echo $breadcrumb_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Lesson Numbers</h2>
								<label>Display lesson numbers in the lesson navigation on lesson pages.</label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-lesson-numbers">
										<input type="checkbox" id="wpc-show-lesson-numbers" name="wpc_show_lesson_numbers" value="true" <?php echo $lesson_numbers_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Completed Button</h2>
								<label>Display the completed lesson button and completed lessons progress bar on lesson pages.</label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-completed-lessons">
										<input type="checkbox" id="wpc-show-completed-lessons" name="wpc_show_completed_lessons" value="true" <?php echo $completed_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
									
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Lesson Navigation Icons</h2>
								<label>Display lesson navigation button icons (eye, play, check, lock)</label>
							</div>
							<div class="wpc-2">
								<div class="wpc-option wpc-option-toggle">
									<label class="wpc-switch" for="wpc-show-lesson_nav_icons">
										<input type="checkbox" id="wpc-show-lesson_nav_icons" name="wpc_show_lesson_nav_icons" value="true" <?php echo $icons_checked; ?>/>
										<div class="wpc-slider wpc-round"></div>
									</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Courses Per Page</h2>
								<label for="wpc-courses-per-page">Number of courses that display per page in the course archive.</label>
							</div>
							<div class="wpc-2">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-courses-per-page" type="number" value="<?php echo (int) $wpc_courses_per_page; ?>" name="wpc_courses_per_page"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-10">
								<h2>Teachers Per Page</h2>
								<label for="wpc-teachers-per-page">Number of teachers that display per page in the teacher archive.</label><br>
							</div>
							<div class="wpc-2">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-teachers-per-page" type="number" value="<?php echo (int) $wpc_teachers_per_page; ?>" name="wpc_teachers_per_page"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-4">
								<h2>Messages</h2>
								<label>Displays a custom restricted lesson message for logged out users on lesson pages.</label>
							</div>
							<div class="wpc-8">
								<?php $settings = array(
								    'teeny' => true,
								    'textarea_rows' => 6,
								    'tabindex' => 2,
								    'textarea_name'	=> 'wpc_logged_out_message',
								);
								wp_editor( wp_kses($wpc_logged_out_message, 'post'), 'wpc_logged_out_message', $settings); ?>
							</div>
						</div>
											
						<?php do_action( 'wpc_after_display_options' ); ?>

					</div>

					<div id="wpc-design-options" class="wpc-admin-box">
						<h2 class="wpc-admin-box-header">Design Options</h2>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>H1 Font Size</h2>
								<label>Sets the size of all H1 Headers in WP Courses Templates.  This includes lesson titles, course titles and more.  Set in px or em.  For example 32px or 1em.</label>
							</div>
							<div class="wpc-6">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-h1-font-size" type="text" value="<?php echo esc_textarea($h1); ?>" name="wpc_h1_font_size" placeholder="32px"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>H2 Font Size</h2>
								<label>Sets the size of all H2 Headers in WP Courses Templates.  This includes course titles in course archives.  Set in px or em.  For example 32px or 1em.</label>
							</div>
							<div class="wpc-6">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-h2-font-size" type="text" value="<?php echo esc_textarea($h2); ?>" name="wpc_h2_font_size" placeholder="26px"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>H3 Font Size</h2>
								<label>Sets the size of all H3 Headers in WP Courses Templates.  This includes lesson titles on the profile page.  Set in px or em.  For example 32px or 1em.</label>
							</div>
							<div class="wpc-6">
								<input style="margin-top:20px;" class="wpc-wide-input" id="wpc-h3-font-size" type="text" value="<?php echo esc_textarea($h3); ?>" name="wpc_h3_font_size" placeholder="22px"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>Max Page Width</h2>
								<label>Sets the maximum page width for all WP Courses templates such as the course archive, single course and single lesson templates.  Set in px or %.  For example 1080px or 80%.</label>
							</div>
							<div class="wpc-6">
								<input style="margin-top:20px;" class="wpc-wide-input" type="text" value="<?php echo esc_textarea($max_width); ?>" name="wpc_row_max_width" placeholder="1080px"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>Page Width</h2>
								<label>Sets the page width for all WP Courses templates such as the course archive, single course and single lesson templates.  Set to % to stay mobile-friendly.  For example 80%.</label>
							</div>
							<div class="wpc-6">
								<input style="margin-top:20px;" class="wpc-wide-input" type="text" value="<?php echo esc_textarea($width); ?>" name="wpc_row_width" placeholder="80%"/>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>Container Padding</h2>
								<label>Sets the padding for the main container that wraps every WP Courses template page including course archives, single lesson, single course, etc.</label>
							</div>
							<div class="wpc-6">
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_top); ?>" name="wpc_container_padding_top" placeholder="60px"/>
									<label>Top</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_bottom); ?>" name="wpc_container_padding_bottom" placeholder="60px"/>
									<label>Bottom</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_left); ?>" name="wpc_container_padding_left" placeholder="60px"/>
									<label>Left</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($container_padding_right); ?>" name="wpc_container_padding_right" placeholder="60px"/>
									<label>Right</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-6">
								<h2>Buttons</h2>
								<label>Various options for buttons across various WP Courses templates.</label>
							</div>
							<div class="wpc-6">
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($button_font_size); ?>" name="wpc_button_font_size" placeholder="18px"/>
									<label>Font Size</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($button_border_width); ?>" name="wpc_button_border_width" placeholder="1px"/>
									<label>Border Width</label>
								</div>
								<div class="wpc-spacing-wrapper">
									<input style="margin-top:20px;" class="wpc-spacing-input" type="text" value="<?php echo esc_textarea($button_border_radius); ?>" name="wpc_button_border_radius" placeholder="4px"/>
									<label>Border Radius</label>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-2">
								<h2>Background</h2>
								<label>Sets the background color for you course, lesson and teacher pages.</label>
							</div>
							<div class="wpc-10">
								<div class="wpc-color-picker-options-wrapper">
									<input class="color-field" name="wpc_primary_bg_color" value="<?php echo esc_html($wpc_primary_bg_color); ?>"/>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-2">
								<h2>Primary Button Colors</h2>
								<label>Sets the colors for buttons including the "complete" button and course categories.</label>
							</div>
							<div class="wpc-10">
								<div class="wpc-color-picker-options-wrapper">
									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_color" value="<?php echo esc_html($wpc_primary_button_color); ?>"/>
										<label>Color</label>
									</div>

									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_border_color" value="<?php echo esc_html($wpc_primary_button_border_color); ?>"/>
										<label>Border Color</label>
									</div>
									
									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_text_color" value="<?php echo esc_html($wpc_primary_button_text_color); ?>"/>
										<label>Text Color</label>
									</div>
								</div>
								<br>
							</div>
						</div>


						<div class="wpc-row wpc-option-row">
							<div class="wpc-2">
								<h2>Primary Button Hover Colors</h2>
								<label>Sets the colors on hover for buttons including the "complete" button and course categories.</label>
							</div>
							<div class="wpc-10">
								<div class="wpc-color-picker-options-wrapper">
									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_hover_color" value="<?php echo esc_html($wpc_primary_button_hover_color); ?>"/>
										<label>Color</label>
									</div>

									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_hover_border_color" value="<?php echo esc_html($wpc_primary_button_hover_border_color); ?>"/>
										<label>Border Color</label>
									</div>

									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_hover_text_color" value="<?php echo esc_html($wpc_primary_button_hover_text_color); ?>"/>
										<label>Text Color</label>
									</div>
								</div>
							</div>
						</div>

						<div class="wpc-row wpc-option-row">
							<div class="wpc-2">
								<h2>Primary Button Active Colors</h2>
								<label>Sets the active colors for buttons including the "complete" button and course categories.</label>
							</div>
							<div class="wpc-10">
								<div class="wpc-color-picker-options-wrapper">
									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_active_color" value="<?php echo esc_html($wpc_primary_button_active_color); ?>"/>
										<label>Color</label>
									</div>

									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_active_border_color" value="<?php echo esc_html($wpc_primary_button_active_border_color); ?>"/>
										<label>Border Color</label>
									</div>

									<div class="wpc-4">
										<input class="color-field" name="wpc_primary_button_active_text_color" value="<?php echo esc_html($wpc_primary_button_active_text_color); ?>"/>
										<label>Text Color</label>
									</div>
								</div>
							</div>
						</div>

						<?php do_action( 'wpc_after_design_options' ); ?>
					</div>

					<?php do_action( 'wpc_after_options' ); ?>

					<?php submit_button(); ?>

				</form>
			</div>
		</div>
	</div>

<?php } ?>