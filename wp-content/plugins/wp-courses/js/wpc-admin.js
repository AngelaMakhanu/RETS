/*
** General
*/

function fixIframeSize(){
    var video = jQuery('.wpc-video-wrapper iframe');
    jQuery.each(video, function(key, val){
        var w = jQuery(this).parent().width();
        var h = w * 0.5625;
        jQuery(this).width(w);
        jQuery(this).height(h);  
    });
}

jQuery(document).ready(function($){
    fixIframeSize(); 
    $(document).ready(function() {
        $('.wpc-course-multiselect, .wpc-single-lesson-course-multiselect, .wpc-teacher-multiselect').select2();
    });
});

jQuery(window).resize(function(){
    fixIframeSize();
}); 

function wpcLessonTableData(){
	var $lessonRows = jQuery('.wpc-order-lesson-list-lesson');
    var posts = [];
    $lessonRows.each(function(key, value){
    	var dataID = jQuery(this).attr('data-id');
        var postType = jQuery(this).attr('data-post-type');
        var courseID = jQuery(this).attr('data-connected-course-id');
    	posts.push({
    		'postID'      : dataID,
            'courseID'    : courseID,
    		'menuOrder'   : key,
            'postType'    : postType,
    	});			        	
    });
	return posts;
}

function wpcShowAjaxIcon(){
    $saveIconWrapper = jQuery('#wpc-ajax-save');
    $saveIcon = $saveIconWrapper.children();
    $saveIcon.removeClass();
    $saveIcon.addClass('fa fa-spin fa-spinner');
    $saveIconWrapper.fadeIn();
}

function wpcHideAjaxIcon(){
    $saveIconWrapper = jQuery('#wpc-ajax-save');
    $saveIcon = $saveIconWrapper.children();
    $saveIcon.removeClass();
    $saveIcon.addClass('fa fa-check');
    $saveIconWrapper.delay(750).fadeOut(750);
}

jQuery(document).ready(function($){
    $(".wpc-admin-options-menu li").click(function() {
        var id = $(this).attr('data-elem-id');
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#" + id).offset().top - 40
        }, 1000);
    });
});

// sticky

function WPCSticky(sidebarSelector, args = {}){
    var sidebar = jQuery(sidebarSelector);
    var parentContainer = sidebar.parent();
    var siblingContainer = sidebar.siblings();

    jQuery(document).on( 'scroll', function(){
        var scrollY = window.scrollY + args.offsetTop;
        var sidebarY = sidebar.offset().top;
        var siblingY = siblingContainer.offset().top;

        sidebar.css('position', 'relative');

        if(sidebarY < scrollY || sidebarY > siblingY ){
            sidebar.css({
                'position'  : 'relative',
                'top'       : scrollY < siblingY ? 0 + 'px' : scrollY - siblingY + 'px',
            });
        }

        

    });

}

jQuery(document).ready(function($){
    if(jQuery('#wpc-sticky-sidebar').length) {
        WPCSticky('#wpc-sticky-sidebar', {
            offsetTop: 40,
        });
    }
});

/*
** PMPRO
*/

jQuery(document).ready(function($){
    function wpc_pmpro_meta_box(){
        var $all_checkboxes = $('#pmpro_page_meta .selectit input[type="checkbox"]');

        var $radio_buttons = $('#wpc-lesson-restriction-container input[type="radio"]').not(':hidden');

        $.each( $all_checkboxes, function(key, val ){
            if($(this).prop('checked') == true){
                $('#wpc-membership-radio').prop('checked', 'checked');
                $('#wpc-lesson-restriction-container').css({
                    'pointer-events': 'none',
                });
                $radio_buttons.prop('disabled', true);
                $('#wpc-lesson-restriction-overlay').css('display', 'block');
                return false;
            } else {
                var checked = '';
                $.each($radio_buttons, function(key, val){
                    if($(this).prop('checked') == true) {
                        checked = true;
                        return false;
                    } else {
                        checked = false;
                    }
                });

                if(checked == false){
                    $('#wpc-none-radio').prop('checked', 'checked');    
                }

                $('#wpc-lesson-restriction-container').css({
                    'pointer-events': 'all',
                });     

                $radio_buttons.prop('disabled', false);
                $('#wpc-lesson-restriction-overlay').css('display', 'none');
            }
        });
    }
    $('#pmpro_page_meta .selectit input').click(function(){
        wpc_pmpro_meta_box();
    });
});

/*
** REQUIREMENTS
*/

jQuery(document).ready(function($){

    $(document).on('change', '.wpc-requirement-action', function(){

        var value = $(this).val();

        if(value == 'scores') {

            $(this).siblings('.wpc-requirement-type').children('option[value="any-quiz"]').attr('selected','selected');
            $(this).parent().children('.wpc-requirement-courses-select').hide();
            $(this).parent().children('.wpc-percent').show();
            $(this).parent().children('.wpc-percent-label').show();

            $(this).parent().children('.wpc-requirement-times').show();
            $(this).parent().children('.wpc-times-label').show();

            $(this).siblings('.wpc-requirement-type').children('option[value="any-course"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-course"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-lesson"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-lesson"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-module"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-module"]').hide();

            $(this).parent().children('.wpc-requirement-lesson-select').hide();

        } else {

            $(this).siblings('.wpc-requirement-type').children('option[value="any-course"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-course"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-lesson"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-lesson"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-module"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-module"]').show();

        }

        var requirementType = jQuery(this).parent().children('.wpc-requirement-type').val();
        var requirementAction = jQuery(this).val();

        var $requirementPercent = jQuery(this).parent().children('.wpc-percent');
        var $percentLabel = jQuery(this).parent().children('.wpc-percent-label');

        if(requirementType ==  'specific-quiz' || requirementType == 'any-quiz') {
            if(requirementAction == 'completes' || requirementAction == 'views') {
                $requirementPercent.val(0);
                $requirementPercent.hide();
                $percentLabel.hide();
            } else {
                $requirementPercent.val(0);
                $requirementPercent.show();
                $percentLabel.show();
            }
        }

    });

    $(document).on('change', '.wpc-requirement-type', function(){

        var requirementType = jQuery(this).val();
        var requirementAction = jQuery(this).parent().children('.wpc-requirement-action').val();

        var $requirementTimes = jQuery(this).parent().children('.wpc-requirement-times');
        var $requirementPercent = jQuery(this).parent().children('.wpc-percent');

        var $timesLabel = jQuery(this).parent().children('.wpc-times-label');
        var $percentLabel = jQuery(this).parent().children('.wpc-percent-label');

        var $requirementCoursesSelect = jQuery(this).parent().children('.wpc-requirement-courses-select');
        var $requirementLessonSelect = jQuery(this).parent().children('.wpc-requirement-lesson-select');

        $requirementLessonSelect.hide();

        if(requirementType == 'specific-lesson' || requirementType == 'any-lesson'){
            $requirementPercent.val(0);
            $requirementPercent.hide();
            $percentLabel.hide();
        } else {
            $requirementPercent.show();
            $percentLabel.show();
        }

        if(requirementType == 'specific-course' || requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType ==  'specific-quiz'){
            $requirementCoursesSelect.show();
            $requirementCoursesSelect.val('none');
            $timesLabel.hide();
            $requirementTimes.hide();
        } else {
            $requirementCoursesSelect.hide();
            $timesLabel.show();
            $requirementTimes.show();
        }

        if(requirementType ==  'specific-quiz' || requirementType == 'any-quiz') {
            if(requirementAction == 'completes' || requirementAction == 'views') {
                $requirementPercent.val(0);
                $requirementPercent.hide();
                $percentLabel.hide();
            } else if (requirementAction == 'scores') {
                $requirementPercent.val(0);
                $requirementPercent.show();
                $percentLabel.show();
            }
        }

    });

    // Add Color Picker to all inputs that have 'color-field' class
    $('.color-field').wpColorPicker();

    $('.wpc-question-btn').click(function(){
    	$('.wpc-lightbox-wrapper').show();
    	$('.wpc-lightbox-content').html($(this).attr('data-content'));
    });

    $('.wpc-lightbox-wrapper').click(function(){
    	$('.wpc-lightbox-wrapper').hide();
    });

    // admin submenu display logic
    $(document).on('click', '.wpc-submenu-toggle', function(e){
        $(this).siblings().children('.wpc-admin-submenu').fadeOut('fast');
        $(this).children('.wpc-admin-submenu').fadeToggle('fast');
        $(this).toggleClass('wpc-admin-menu-item-active');
        $(this).siblings().removeClass('wpc-admin-menu-item-active');
    });

    // hide submenu on click outside of submenu
    $(document).on('click',function(e){
        if( !$(e.target).closest('.wpc-admin-submenu, .wpc-submenu-toggle, .wpc-submenu-toggle a').length ){
            $('.wpc-admin-submenu').fadeOut('fast');
            $('.wpc-submenu-toggle').removeClass('wpc-admin-menu-item-active');
        }
    });

    $('.wpc-nav-tab').click(function(e){
        $('.wpc-nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.wpc-tab-content').hide();
        $('.wpc-tab-content').eq($(this).index()).fadeIn('fast');
        e.preventDefault();
    });

 	if( $('.wpc-sortable-table').length ) {
	  $('.wpc-sortable-table').DataTable({
		   aaSorting: []
	   });
	}

});