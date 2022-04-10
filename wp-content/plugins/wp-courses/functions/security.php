<?php

	// sanitizes lesson video output
	function wpc_sanitize_video($video) {
		$video = wp_kses($video, 
			array(
				'iframe' => array(
					'src' 				=> true, 
					'width' 			=> true, 
					'height' 			=> true, 
					'title' 			=> true, 
					'frameborder' 		=> true, 
					'allow' 			=> true, 
					'allowfullscreen' 	=> true
				),
				'video' => array(
					'autopictureinpicture' 		=> true, 
					'controls' 					=> true, 
					'controlslist' 				=> true, 
					'crossorigin' 				=> true, 
					'disablepictureinpicture' 	=> true, 
					'disableremoteplayback' 	=> true, 
					'height' 					=> true, 
					'loop' 						=> true, 
					'muted' 					=> true,
					'playsinline'				=> true,
					'poster'					=> true,
					'preload'					=> true,
					'src'						=> true,
					'width'						=> true
				),
				'source' => array(
					'src'	=> true,
					'type'	=> true
				)
			)
		);

		if( strpos($video, 'javascript:') === false ){
			return $video;
		} else {
			return;
		}
		
	}

?>