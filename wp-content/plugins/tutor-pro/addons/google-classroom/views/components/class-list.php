<?php
    $classes = $classroom->get_class_list();
?>
<div class="tutor-gc-filter-container">
    <div>
        &nbsp;<br/>
        <select class="regular-text" style="max-width:130px">
            <option value="import">Import</option>
            <option value="publish">Publish</option>
            <option value="trash">Trash</option>
            <option value="delete" title="Only trashed classes can be deleted."><?php esc_html_e( 'Delete Permanently', 'tutor-pro' ); ?></option>
            <option value="restore">Restore</option>
        </select>
        <button class="button button-primary" id="tutor_gc_bulk_action_button"><?php esc_html_e( 'Apply', 'tutor-pro' ); ?></button>
    </div>
    <div>
        <div>
            Search<br/>
            <input type="text" class="regular-text" id="tutor_gc_search_class"/>
        </div>
    </div>
</div>

<br/>
<table class="tutor-ui-table tutor-ui-table-responsive google-classroom-class-list">
    <thead>
        <tr>
            <th>
                <div class="tutor-d-flex tutor-option-field-row">
                    <input type="checkbox" id="tutor-bulk-checkbox-all" class="tutor-form-check-input">
                </div>
            </th>
            <th><?php _e( 'Class Name', 'tutor-pro' ); ?></th>
            <th><?php _e( 'Import Date', 'tutor-pro' ); ?></th>
            <th><?php _e( 'Status', 'tutor-pro' ); ?></th>
            <th><?php _e( 'Class Code', 'tutor-pro' ); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ( $classes as $class ) {
                
                $is_imported = property_exists( $class, 'local_class_post' );
                $permalink = $is_imported ? get_permalink( $class->local_class_post->ID ) : '';
                $edit_link = $is_imported ? get_edit_post_link( $class->local_class_post->ID ) : '';
                $post_id = $is_imported ? $class->local_class_post->ID : '';
                
                ?>
                <tr>
                    <td data-th="<?php _e('Check', 'tutor-pro') ?>">
                        <div class="td-checkbox tutor-d-flex tutor-option-field-row">
                            <input type="checkbox" class="tutor-form-check-input tutor-bulk-checkbox" name="tutor-bulk-checkbox-all" value="<?php echo esc_attr( $class->id ); ?>">
                        </div>
                    </td>
                    <td data-th="<?php _e('Class Name', 'tutor-pro'); ?>" class="tutor-gc-title">
                        <a href="<?php echo esc_url( $class->alternateLink ); ?>" target="_blank">
                            <?php echo esc_html( $class->name ); ?>
                        </a>
                    </td>
                    <td data-th="<?php _e('Import Date', 'tutor-pro'); ?>">
                        <?php 
                            if ( $is_imported ) {
                                echo get_post_meta( $post_id, 'tutor_gc_post_time', true );
                            }
                        ?>
                    </td>
                    <td data-th="<?php _e('Status', 'tutor-pro'); ?>">
                        <?php 
                            $status = $is_imported ? $class->local_class_post->post_status : 'Not Imported'; 
                            $status = ucfirst( $status );
                            $class_ = str_replace( ' ', '-', strtolower( $status ) );

                            echo '<span class="tutor-status tutor-status-' . $class_ . '">' . $status . '</span>'; 
                        ?>
                    </td>
                    <td data-th="<?php _e('Class Code', 'tutor-pro'); ?>" class="tutor-gc-code">
                        <?php echo esc_html( $class->enrollmentCode ); ?>  <span class="tutor-icon-copy-filled tutor-copy-text" data-text="<?php echo esc_attr( $class->enrollmentCode ); ?>"></span>
                    </td>
                    <td data-th="<?php _e('Action', 'tutor-pro'); ?>" data-class_actions="" class="<?php echo 'class-status-' . $class_; ?>">
                        <button class="button button-primary button-small" data-action="import" data-classroom_id="<?php echo esc_attr( $class->id ); ?>">Import</button>
                        <button class="button button-primary button-small class-preview-link" data-action="publish" data-class_post_id="<?php echo esc_attr( $post_id ); ?>"><?php esc_html_e( 'Publish', 'tutor-pro' ); ?></button>
                        
                        <a href="<?php echo esc_url( $permalink ); ?>" class="button button-primary button-small class-preview-link" data-action="preview"><?php esc_html_e( 'Preview', 'tutor-pro' ); ?></a>
                        <a href="<?php echo esc_url( $edit_link ); ?>" class="button button-secondary button-small class-edit-link" data-action="edit"><?php esc_html_e( 'Edit', 'tutor-pro' ); ?></a>
                        
                        <button class="button button-primary button-small" data-action="restore" data-class_post_id="<?php echo esc_attr( $post_id ); ?>"><?php esc_html_e( 'Restore', 'tutor-pro' ); ?></button>
                        
                        <span class="tutor-icon-garbage-line" data-action="trash" data-class_post_id="<?php echo esc_attr( $post_id ); ?>"></span>
                        <span class="tutor-icon-garbage-line" data-action="delete" data-prompt="Sure to delete permanently?" data-class_post_id="<?php echo esc_attr( $post_id ); ?>"></span>
                    </td>
                </tr>
                <?php
            }
        ?>
    </tbody>
</table>