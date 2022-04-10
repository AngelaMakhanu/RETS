<?php

	// add custom styling to header
	function wpc_custom_styling(){

	    $wpc_primary_bg_color = get_option('wpc_primary_bg_color', '#f5f5f5');

	    $wpc_primary_button_color = get_option('wpc_primary_button_color', '#23d19f');
	    $wpc_primary_button_border_color = get_option('wpc_primary_button_border_color', '#12ad80');
	    $wpc_primary_button_text_color = get_option('wpc_primary_button_text_color', '#fff');

	    $wpc_primary_button_hover_color = get_option('wpc_primary_button_hover_color', '#23d19f');
	    $wpc_primary_button_hover_border_color = get_option('wpc_primary_button_hover_border_color', '#12ad80');
	    $wpc_primary_button_hover_text_color = get_option('wpc_primary_button_hover_text_color', '#fff');

	    $wpc_primary_button_active_color = get_option('wpc_primary_button_active_color', '#009ee5');
	    $wpc_primary_button_active_border_color = get_option('wpc_primary_button_active_border_color', '#027fb7');
	    $wpc_primary_button_active_text_color = get_option('wpc_primary_button_active_text_color', '#fff');

	    $button_border_radius = get_option('wpc_button_border_radius');
	    $button_border_width = get_option('wpc_button_border_width');
	    $button_font_size = get_option('wpc_button_font_size');

	    $width = get_option('wpc_row_width', '80%');
	    $max_width = get_option('wpc_row_max_width', '1080px');

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

	    echo '<style>';

	    echo '.wpc-container {
	    	padding-top: ' . $container_padding_top . ';
	    	padding-bottom: ' . $container_padding_bottom . ';
	    	padding-left: ' . $container_padding_left . ';
	    	padding-right: ' . $container_padding_right . ';
	    }';

	    echo '.wpc-h1 {
	    	font-size: ' . $h1 . ';
	    }';

	    echo '.wpc-h2 {
	    	font-size: ' . $h2 . ';
	    }';

	    echo '.wpc-h3 {
	    	font-size: ' . $h3 . ';
	    }';

	    echo '.wpc-row {
	    	width: ' . $width . ';
	    	max-width: ' . $max_width . ';
	    }';

	    echo '.wpc-container {
	        background-color: ' . esc_html( $wpc_primary_bg_color ) . ';
	    }';

	    echo '.wpc-button, .paginate_button, .paginate_button.current {
	    	font-size: ' . esc_html($button_font_size). '; 
	    	border-radius: ' . esc_html($button_border_radius). '; 
	    	border-width: ' . esc_html($button_border_width). '; 
	        background: ' . esc_html($wpc_primary_button_color). '; 
	        border-color: ' . esc_html($wpc_primary_button_border_color) . '; 
	        color: ' . esc_html( $wpc_primary_button_text_color) . '; 
	    }';

	    echo '.paginate_button.current {
	        background: ' . esc_html($wpc_primary_button_active_color) . '; 
	        border-color: ' . esc_html($wpc_primary_button_active_border_color) . '; 
	        color: ' . esc_html($wpc_primary_button_active_text_color) . '; 
	    }';

	    echo '.paginate_button:hover {
	        background: ' . esc_html($wpc_primary_button_hover_color) . '; 
	        border-color: ' . esc_html($wpc_primary_button_hover_border_color) . '; 
	        color: ' . esc_html($wpc_primary_button_hover_text_color) . '; 
	    }';

	    echo '.wpc-button a { 
	        color: ' . esc_html($wpc_primary_button_text_color) . '; 
	    }';

	    echo 'a.wpc-button:hover, .wpc-button:hover { 
	        background-color: ' . esc_html($wpc_primary_button_hover_color) . '; 
	        border-color: ' . esc_html($wpc_primary_button_hover_border_color) . '; 
	        color: ' . esc_html($wpc_primary_button_hover_text_color) . '; 
	    }';

	    echo '.wpc-button:hover a { 
	        color: ' . esc_html($wpc_primary_button_hover_text_color) . '!important; 
	    }';

	    echo '.wpc-button.active { 
	        background-color: ' . esc_html($wpc_primary_button_active_color) . '!important; 
	        border-color: ' . esc_html($wpc_primary_button_active_border_color) . '!important; 
	        color: ' . esc_html($wpc_primary_button_active_text_color) . '!important; 
	    }';

	    echo '.wpc-button.active a{ 
	        color: ' . esc_html($wpc_primary_button_active_text_color) . '; 
	    }';

	    echo '.course-category-list ul a { color: ' . esc_html($wpc_primary_button_text_color) . '; }';

	    echo '.course-category-list ul a:hover { color: ' . esc_html($wpc_primary_button_hover_text_color) . '; }';

	    echo '.course-category-list ul a.active { color: ' . esc_html($wpc_primary_button_active_text_color) . '; }';

	    echo '</style>';
	}
	add_action('wp_head', 'wpc_custom_styling');

?>