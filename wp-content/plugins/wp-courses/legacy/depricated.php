<?php

	function wpc_is_clone( $lesson_id ) {

		$orig_id = (int) get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true );

		if( !empty( $orig_id ) && $orig_id != 'none' ){
			return $orig_id;
		} else {
			return false;
		}

	}

	function wpc_get_alias_and_orig_ids($lesson_id){
		global $wpdb;

		$actual_id = $lesson_id;

		$orig_id = (int) get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

		if(!empty($orig_id) && $orig_id != 'none'){
			$lesson_id = $orig_id;
		} 

		// 
		$sql = "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'wpc-lesson-alias-id' AND meta_value = {$lesson_id} AND meta_value != 'none' OR meta_key = 'wpc-lesson-alias-id' AND post_id = {$lesson_id} AND meta_value != 'none'";

		$results = $wpdb->get_results($sql);

		if( empty( $results ) ){

			return array($lesson_id);

		} else {

			$ids = array();

			$ids[] = $actual_id;

			foreach( $results as $result ) {
				$ids[] = $result->meta_value;
				$ids[] = $result->post_id;
			}

			$ids = array_unique($ids);

			return array_reverse($ids);
		}
	}

	class WPC_Tracking{
		public function get_lesson_tracking(){
			return '';
		}
	}

?>