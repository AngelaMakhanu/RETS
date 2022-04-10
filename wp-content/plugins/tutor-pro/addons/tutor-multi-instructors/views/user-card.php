<div id="added-instructor-id-<?php echo $instructor->ID; ?>" class="added-instructor-item added-instructor-item-<?php echo $instructor->ID; ?>" data-instructor-id="<?php echo $instructor->ID; ?>">
    <span class="instructor-icon">
        <img src="<?php echo get_avatar_url($instructor->ID, 30); ?>" style="border-radius:50%;"/>
    </span>
    <span class="instructor-name"> 
        <div class="instructor-intro">
            <div class="tutor-text-btn-xlarge tutor-color-black"><?php echo $instructor->display_name; ?></div>
            <?php echo isset($authorTag) ? $authorTag : ''; ?>
        </div>
        <div class="instructor-email tutor-d-block tutor-fs-7 tutor-color-black-60">
            <?php echo $instructor->user_email; ?>
        </div>
    </span>
    <?php if(get_current_user_id()!=$instructor->ID): ?>
        <span class="instructor-control">
            <a href="javascript:void(0)" class="<?php echo isset($delete_class) ? $delete_class : ''; ?> tutor-action-icon tutor-btn tutor-is-circle tutor-is-outline tutor-btn-ghost">
                <i class="tutor-icon-line-cross-line tutor-icon-24 "></i>
            </a>
        </span>
    <?php endif; ?>
    <?php echo isset($inner_content) ? $inner_content : ''; ?>
</div>