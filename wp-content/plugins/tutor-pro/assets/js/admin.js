jQuery(document).ready(function(o){"use strict";o(document).on("click",".install-tutor-button",function(t){t.preventDefault();var e=o(this);o.ajax({type:"POST",url:ajaxurl,data:{install_plugin:"tutor",action:"install_tutor_plugin"},beforeSend:function(){e.addClass("tutor-updating-message")},success:function(t){o(".install-tutor-button").remove(),o("#tutor_install_msg").html(t)},complete:function(){e.removeClass("tutor-updating-message")}})}),o(document).on("click","#import-gradebook-sample-data",function(t){t.preventDefault();var e=o(this);o.ajax({type:"POST",url:ajaxurl,data:{action:"import_gradebook_sample_data"},beforeSend:function(){e.addClass("tutor-updating-message")},success:function(t){t.success&&location.reload()},complete:function(){e.removeClass("tutor-updating-message")}})}),o('[name="tutor_option[tutor_email_disable_wpcron]"]').change(function(){o('[name="tutor_option[tutor_email_cron_frequency]"]').closest(".tutor-option-field-row")[o(this).prop("checked")?"hide":"show"]()}).trigger("change")});