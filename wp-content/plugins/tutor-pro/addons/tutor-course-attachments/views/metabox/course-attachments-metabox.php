<div class="tutor-attachments-metabox">
    <?php 
        $attachments = tutor_utils()->get_attachments();
        tutor_load_template_from_custom_path(tutor()->path.'/views/fragments/attachments.php', array(
            'name' => 'tutor_attachments[]',
            'attachments' => $attachments,
            'add_button' => true,
            'size_below' => true,
            'is_responsive' => is_admin() ? true : false
        ), false);
    ?>
    <input type="hidden" name="_tutor_attachments_main_edit" value="true" />  
</div>