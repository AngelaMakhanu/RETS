<?php

add_action('wp_head', 'wpc_front_end_options');

function wpc_front_end_options(){ 

    $is_admin = current_user_can('administrator');
    $post_type = get_post_type();

    if(
        $is_admin && $post_type == 'course'
        || $is_admin && $post_type == 'lesson'
        || $is_admin && $post_type == 'wpc-quiz'
        || $is_admin && $post_type == 'teacher'
        || $is_admin && $post_type == 'wpc-certificate'
    ){ ?>

        <script>
            jQuery(document).ready(function($){

                $( "#wpc-fe-options-accordion" ).accordion({
                    heightStyle: "content",
                    icons: {
                        activeHeader: "wpc-arrow-up",
                        header: "wpc-arrow-down"
                    }
                });

                $(document).on('input change', '.wpc-range', function(){
                    var val = $(this).val();
                    var unit = $(this).next().attr('data-unit');

                    var elem = $(this).next().data('class');
                    var style = $(this).next().data('style');
                    var option = $(this).next().data('option');
                    elem = $('.' + elem);
                    elem.css(style, val + unit);

                    $(this).next().val(val + unit);
                });

                $(".wpc-fe-color-field").spectrum({
                  type: "flat",
                  showAlpha: false,
                  showInput: true,
                  move : function(e, color){
                    var value = $(this).val();
                    var style = $(this).data('style');
                    var elem = $(this).data('class');
                    console.log(value);
                    $('.' + elem).css(style, value);
                  },
                });

                $('.wpc-fe-color-field').on("dragstop.spectrum", function(e, color) {
                    var value = $(this).val();
                    var style = $(this).data('style');
                    var option = $(this).data('option');

                    var data = {
                        'type'      : 'POST',
                        'action'    : 'save_fe_option',
                        'style'     : style,
                        'value'     : color.toHexString(),
                        'option'    : option,
                    };

                    wpcShowAjaxIcon();

                    jQuery.post(ajaxurl, data, function(response) {
                        wpcHideAjaxIcon();
                    });
                });

                // save the value from the text input
                $(document).on('change', '.wpc-range-ajax', function(){
                    var value = $(this).val();
                    var unit = $(this).next().attr('data-unit');

                    var elem = $(this).next().data('class');
                    var style = $(this).next().data('style');
                    var option = $(this).next().data('option');
                    elem = $('.' + elem);
                    elem.css(style, value + unit);

                    var data = {
                        'type'      : 'POST',
                        'action'    : 'save_fe_option',
                        'style'     : style,
                        'value'     : value + unit,
                        'option'    : option,
                    };

                    wpcShowAjaxIcon();

                    jQuery.post(ajaxurl, data, function(response) {
                        wpcHideAjaxIcon();
                    });

                });

                var typingTimer;
                var doneTypingInterval = 250;

                $(document).on('keyup', '.wpc-feo-text', function(){

                    clicked = jQuery(this);
                    clearTimeout(typingTimer);
                    if (clicked.val()) {
                        typingTimer = setTimeout(wpcDoneOptionTyping, doneTypingInterval);
                    }

                    value = $(this).val();
                    textInput = $(this).prev();

                    // set unit of measurement
                    if(value.indexOf('%') != -1) {
                       $(this).attr('data-unit', '%');
                       $(this).prev().attr('max', 100);
                    } else if(value.indexOf('px') != -1){
                        $(this).attr('data-unit', 'px');
                    } else if(value.indexOf('em') != -1) {
                        $(this).attr('data-unit', 'em');
                    }

                    unit = $(this).attr('data-unit');

                    intValue = value.replace(/[^\d.-]/g, '');
                    $(this).prev().val(intValue);

                    if(intValue > 100){
                        textInput.attr('max', intValue);
                    } else {
                        textInput.attr('max', 100);
                    }

                    textInput.val(intValue + unit);

                    elem = $(this).data('class');
                    style = $(this).data('style');
                    option = $(this).data('option');
                    elem = $('.' + elem);
                    elem.css(style, intValue + unit);

                });

                function wpcDoneOptionTyping(){
                    var data = {
                        'type'      : 'POST',
                        'action'    : 'save_fe_option',
                        'style'     : style,
                        'value'     : intValue + unit,
                        'option'    : option,
                    };

                    wpcShowAjaxIcon();

                    jQuery.post(ajaxurl, data, function(response) {
                        wpcHideAjaxIcon();
                    });
                }

                $('#wpc-fe-setting-icon').click(function(){
                    var status = $(this).attr('data-open');

                    $(this).animate({
                        padding: '10px',
                        bottom: '30px',
                        left: '30px',
                    }, 150).animate({
                        padding: '20px',
                        bottom: '20px',
                        left: '20px',
                    }, 150);

                    if(status == 'false'){
                        $('.wpc-fe-options-wrapper').animate({
                            left: '0px',
                        }, 750);
                        $(this).attr('data-open', 'true');
                        $(this).css('background-color', '#de3c62');
                        $(this).children('i').removeClass('fa-cog');
                        $(this).children('i').addClass('fa-times');
                    } else {
                        $('.wpc-fe-options-wrapper').animate({
                            left: '-438px',
                        }, 750);
                        $(this).attr('data-open', 'false');
                        $(this).css('background-color', 'rgb(18 199 149)');
                        $(this).children('i').removeClass('fa-times');
                        $(this).children('i').addClass('fa-cog');
                    }
                });

                $('.wpc-option-tab-content').hide();
                $('.wpc-option-tab-content').first().show();
                $('.wpc-option-tabs li').click(function(){
                    $('.wpc-option-tabs li').removeClass('wpc-option-tab-active');
                    $(this).addClass('wpc-option-tab-active');
                    var id = $(this).data('tab');
                    $(this).parent().siblings('.wpc-option-tab-content').hide();
                    $('#' + id).show();
                });

            });
        </script>
        <div class="wpc-fe-options-wrapper">
            <div id="wpc-fe-options-accordion">
                <h3 class="wpc-accordion-option-header">General Settings</h3>
                <div class="wpc-accordion-option-content">
                    <div class="wpc-accordion-single-option wpc-range-option">
                        <?php 
                            $width = get_option('wpc_row_width'); 
                            $width = wpc_esc_unit($width, 'px');
                        ?>
                        <label>Page Width</label><br>
                        <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($width, 100); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $width); ?>"/> 
                        <input class="wpc-feo-text wpc-range-input" data-class="wpc-row" data-style="width" data-unit="<?php echo wpc_get_unit($width, '%'); ?>" data-option="wpc_row_width" type="text" placeholder="80" value="<?php echo $width; ?>"/>
                    </div>
                    <div class="wpc-accordion-single-option">
                        <?php 
                            $max_width = get_option('wpc_row_max_width'); 
                            $max_width = wpc_esc_unit($max_width, 'px');
                        ?>
                         <label>Max Page Width</label><br>
                        <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($max_width, 1080); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $max_width); ?>"/> 
                        <input class="wpc-feo-text wpc-range-input" data-class="wpc-row" data-style="max-width" data-unit="<?php echo wpc_get_unit($max_width, 'px'); ?>" data-option="wpc_row_max_width" type="text" placeholder="1080px" value="<?php echo $max_width; ?>"/>
                    </div>
                    <div class="wpc-accordion-single-option">
                        <?php 
                            $container_padding_top = get_option('wpc_container_padding_top'); 
                            $container_padding_top = wpc_esc_unit($container_padding_top, 'px');
                        ?>
                        <label>Page Container Padding</label><br>
                        <div class="wpc-spacing-wrapper">
                            <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-container" data-style="padding-top" data-unit="<?php echo wpc_get_unit($container_padding_top, 'px'); ?>" data-option="wpc_container_padding_top" type="text" placeholder="60px" value="<?php echo $container_padding_top; ?>"/>
                            <label class="wpcb-center-label">Top</label>
                        </div>
                        <?php 
                            $container_padding_bottom = get_option('wpc_container_padding_bottom'); 
                            $container_padding_bottom = wpc_esc_unit($container_padding_bottom, 'px');
                        ?>
                        <div class="wpc-spacing-wrapper">
                            <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-container" data-style="padding-bottom" data-unit="<?php echo wpc_get_unit($container_padding_bottom, 'px'); ?>" data-option="wpc_container_padding_bottom" type="text" placeholder="60px" value="<?php echo $container_padding_bottom; ?>"/>
                            <label class="wpcb-center-label">Bottom</label>
                        </div>
                        <?php 
                            $container_padding_left = get_option('wpc_container_padding_left'); 
                            $container_padding_left = wpc_esc_unit($container_padding_left, 'px');
                        ?>
                        <div class="wpc-spacing-wrapper">
                            <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-container" data-style="padding-left" data-unit="<?php echo wpc_get_unit($container_padding_left, 'px'); ?>" data-option="wpc_container_padding_left" type="text" placeholder="0px" value="<?php echo $container_padding_left; ?>"/>
                            <label class="wpcb-center-label">Left</label>
                        </div>
                        <?php 
                            $container_padding_right = get_option('wpc_container_padding_right'); 
                            $container_padding_right = wpc_esc_unit($container_padding_right, 'px');
                        ?>
                        <div class="wpc-spacing-wrapper">
                            <input class="wpc-feo-text wpc-spacing-input" data-class="wpc-container" data-style="padding-right" data-unit="<?php echo wpc_get_unit($container_padding_right, 'px'); ?>" data-option="wpc_container_padding_right" type="text" placeholder="0px" value="<?php echo $container_padding_right; ?>"/>
                            <label class="wpcb-center-label">Right</label>
                        </div>
                    </div>
                    <div class="wpc-accordion-single-option">
                        <?php
                            $container_bg_color = get_option('wpc_primary_bg_color'); 
                        ?>
                        <label>Page Container Background Color</label><br>
                        <input type="color" class="wpc-fe-color-field" data-style="background-color" data-option="wpc_primary_bg_color" data-class="wpc-container" value="<?php echo $container_bg_color; ?>"/>
                    </div>
                </div>
                <h3 class="wpc-accordion-option-header">General Typography</h3>
                <div class="wpc-accordion-option-content">
                    <?php $h1 = get_option('wpc_h1_font_size'); ?>
                    <div class="wpc-accordion-single-option">
                        <label>H1 Font Size</label><br>
                        <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($h1, 96); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $h1); ?>"/> 
                        <input class="wpc-feo-text wpc-range-input" data-class="wpc-h1" data-style="font-size" data-unit="<?php echo wpc_get_unit($h1, 'px'); ?>" data-option="wpc_h1_font_size" type="text" placeholder="32px" value="<?php echo $h1; ?>"/>
                    </div>
                    <?php $h2 = get_option('wpc_h2_font_size'); ?>
                    <div class="wpc-accordion-single-option">
                        <label>H2 Font Size</label><br>
                        <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($h2, 96); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $h2); ?>"/> 
                        <input class="wpc-feo-text wpc-range-input" data-class="wpc-h2" data-style="font-size" data-unit="<?php echo wpc_get_unit($h2, 'px'); ?>" data-option="wpc_h2_font_size" type="text" placeholder="26px" value="<?php echo $h2; ?>"/>
                    </div>
                    <?php $h3 = get_option('wpc_h3_font_size'); ?>
                    <div class="wpc-accordion-single-option">
                        <label>H3 Font Size</label><br>
                        <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($h3, 96); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $h3); ?>"/> 
                        <input class="wpc-feo-text wpc-range-input" data-class="wpc-h3" data-style="font-size" data-unit="<?php echo wpc_get_unit($h3, 'px'); ?>"data-option="wpc_h3_font_size" type="text" placeholder="22px" value="<?php echo $h3; ?>"/>
                    </div>
                </div>
                <h3 class="wpc-accordion-option-header">Buttons</h3>
                <div class="wpc-accordion-option-content">
                    <ul class="wpc-option-tabs">
                        <li data-tab="wpc-general" class="wpc-option-tab-active">General</li>
                        <li data-tab="wpc-border">Border</li>
                        <li data-tab="wpc-typography">Typography</li>
                    </ul>
                    <div id="wpc-general" class="wpc-option-tab-content">
                        <div class="wpc-accordion-single-option">
                            
                            <?php
                                $button_bg_color = get_option('wpc_primary_bg_color'); 
                            ?>
                            <label>Background Color</label><br>
                            <input type="color" class="wpc-fe-color-field" data-style="background-color" data-option="wpc_primary_button_color" data-class="wpc-button" value="<?php echo $button_bg_color; ?>"/>
                        </div>
                    </div>
                    <div id="wpc-border" class="wpc-option-tab-content">
                        <?php $button_border_width = get_option('wpc_button_border_width'); ?>
                        <div class="wpc-accordion-single-option">
                            <label>Border Width</label><br>
                            <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($button_border_width, 50); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $button_border_width); ?>"/> 
                            <input class="wpc-feo-text wpc-range-input" data-class="wpc-button" data-style="border-width" data-unit="<?php echo wpc_get_unit($button_border_width, 'px'); ?>" data-option="wpc_button_border_width" type="text" placeholder="1px" value="<?php echo $button_border_width; ?>"/>
                        </div>

                        <?php $button_border_radius = get_option('wpc_button_border_radius'); ?>
                        <div class="wpc-accordion-single-option">
                            <label>Border Radius</label><br>
                            <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($button_border_radius, 100); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $button_border_radius); ?>"/> 
                            <input class="wpc-feo-text wpc-range-input" data-class="wpc-button" data-style="border-radius" data-unit="<?php echo wpc_get_unit($button_border_radius, 'px'); ?>" data-option="wpc_button_border_radius" type="text" placeholder="4px" value="<?php echo $button_border_radius; ?>"/>
                        </div>

                        <div class="wpc-accordion-single-option">
                            <?php
                                $button_border_color = get_option('wpc_primary_button_border_color'); 
                            ?>
                            <label>Border Color</label><br>
                            <input type="color" class="wpc-fe-color-field" data-style="border-color" data-option="wpc_primary_button_border_color" data-class="wpc-button" value="<?php echo $button_border_color; ?>"/>
                        </div>
                        
                    </div>
                    <div id="wpc-typography" class="wpc-option-tab-content">
                        <?php $button_font_size = get_option('wpc_button_font_size'); ?>
                        <div class="wpc-accordion-single-option">
                            <label>Font Size</label><br>
                            <input class="wpc-range wpc-range-ajax" type="range" min="0" max="<?php echo wpc_get_max_slider_value($button_font_size, 96); ?>" value="<?php echo preg_replace("/[^0-9.]/", "", $button_font_size); ?>"/> 
                            <input class="wpc-feo-text wpc-range-input" data-class="wpc-button" data-style="font-size" data-unit="<?php echo wpc_get_unit($button_font_size, 'px'); ?>"data-option="wpc_button_font_size" type="text" placeholder="14px" value="<?php echo $button_font_size; ?>"/>
                        </div>
                        <div class="wpc-accordion-single-option">
                            <?php
                                $button_text_color = get_option('wpc_primary_button_text_color'); 
                            ?>
                            <label>Text Color</label><br>
                            <input type="color" class="wpc-fe-color-field" data-style="color" data-option="wpc_primary_button_text_color" data-class="wpc-button" value="<?php echo $button_text_color; ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="wpc-fe-setting-icon" data-open="false"><i class="fa fa-cog"></i></div>
<?php } // end if
}

add_action( 'wp_ajax_save_fe_option', 'wpc_save_fe_option', 12 );

function wpc_save_fe_option() {
    update_option(sanitize_text_field($_POST['option']), sanitize_text_field($_POST['value']));
    wp_die();
}

?>