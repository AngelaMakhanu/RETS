<?php
$groups = groups_get_groups(array('show_hidden' => true));
$attached_group = (array) \TUTOR_BP\BuddyPressGroups::get_group_ids_by_course(get_the_ID());
?>

<div class="tutor-row">
	<div class="tutor-col-12 tutor-col-md-5">
		<label class="tutor-course-setting-label">
			<?php _e('BuddyPress Groups', 'tutor-pro'); ?>
		</label>
	</div>
	<div class="tutor-col-12 tutor-col-md-7">
		<select name="_tutor_bp_course_attached_groups[]" class="tutor-form-select tutor_select2" multiple="multiple">
			<!--<option value="-1"><?php /*_e('Select groups', 'tutor-pro'); */?></option>-->
            <?php
            foreach ($groups['groups'] as $group){
                $selected = in_array($group->id, $attached_group) ? 'selected="selected"' : '';
                echo "<option value='{$group->id}' {$selected} > {$group->name} </option>";
            }
            ?>
		</select>
		<p class="tutor-input-feedback tutor-has-icon">
			<i class="tutor-icon-info-circle-outline-filled tutor-input-feedback-icon tutor-fs-5"></i>
			<?php _e('Assign this course to BuddyPress Groups', 'tutor-pro'); ?>		
		</p>
	</div>
</div>