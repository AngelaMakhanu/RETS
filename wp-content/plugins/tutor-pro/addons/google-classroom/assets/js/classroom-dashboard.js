(()=>{function f(t){return(f="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}window.jQuery(document).ready(function(s){function c(t,o){t.prop("disabled",!o),o?t.find("img").remove():t.append('<img style="width: 13px; margin-left: 9px; vertical-align: middle; display:inline-block;" src="'+window._tutorobject.loading_icon_url+'"/>')}(new function(){this.upload_area=s("#tutor_gc_dashboard .tutor-upload-area"),this.input_field=this.upload_area.find('[type="file"]'),this.load_button=s("#tutor_gc_credential_upload>button"),this.uploaded_file=null,this.is_file_valid=null,this.init=function(){this.on_click(),this.on_change(),this.on_drop(),this.on_save()},this.load_file=function(t){t&&t[0]&&(t=t[0],this.is_file_valid=this.is_valid_file(t),this.load_button.prop("disabled",!this.is_file_valid),this.is_file_valid?(this.uploaded_file=t,this.upload_area.find("span.file_name").remove(),this.upload_area.append('<span class="file_name">'+this.uploaded_file.name+"</span>")):alert("Invalid File."))},this.is_valid_file=function(t){return"application/json"==(t.type||"").toLowerCase()},this.on_click=function(){var o=this;this.upload_area.find("button").click(function(t){t.preventDefault(),o.input_field.trigger("click")})},this.on_change=function(){var o=this;this.input_field.change(function(t){o.load_file(t.currentTarget.files),s(this).val("")})},this.on_drop=function(){var o=this;this.upload_area.on("drag dragstart dragend dragover dragenter dragleave drop",function(t){t.preventDefault(),t.stopPropagation()}).on("dragover dragenter",function(){s(this).addClass("dragover")}).on("dragleave dragend drop",function(){s(this).removeClass("dragover")}).on("drop",function(t){o.load_file(t.originalEvent.dataTransfer.files)})},this.on_save=function(){var e=this;this.load_button.click(function(){var t,o,i;e.is_file_valid&&(t=s(this),(o=new FormData).append("credential",e.uploaded_file,e.uploaded_file.name),o.append("action","tutor_gc_credential_save"),i=tutor_get_nonce_data(!0),o.append(i.key,i.value),c(t,!1),s.ajax({url:window._tutorobject.ajaxurl,type:"POST",processData:!1,contentType:!1,data:o,success:function(){window.location.reload()},error:function(){c(t,!0),alert("Request Failed.")}}))})}}).init();function l(t){var o=1==s("#tutor_gc_bulk_action_button").data("process_running");return t||(p=[]),s("#tutor_gc_bulk_action_button").text(t?"Abort":"Apply").data("process_running",t),o}function t(t,o,i,e,a){var n=s('<div class="tutor-gc-pop-up-container">                    <div>                        <img src="'+window._tutorobject.tutor_pro_url+"addons/google-classroom/assets/images/"+t+'"/>                        <h3>'+o+"</h3>                        <p>"+i+'</p>                        <button class="'+e[0].class+'" data-action="'+e[0].action+'">'+e[0].text+'</button>                        <button class="'+e[1].class+'" data-action="'+e[1].action+'">'+e[1].text+"</button>                    </div>                </div>");n.find("button").click(function(){a(s(this).data("action")),n.remove()}),n.click(function(){n.remove()}).children().click(function(t){t.stopImmediatePropagation()}),s("body").append(n)}function d(o){t("import-icon.svg","Do you want to import students from this Classroom?","This is not recommended for paid courses as importing will skip the payment procedure.",[{class:"tutor-gc-button-secondary",text:"No",action:"no"},{class:"tutor-gc-button-primary",text:"Yes, Import Student",action:"yes"}],function(t){_=t,o()})}function u(o){t("delete-icon.svg","Do you want to remove this course from the system?","This will not delete it from Google Classroom, it will only remove the connection.",[{class:"tutor-gc-button-secondary",text:"Cancel",action:"no"},{class:"tutor-gc-button-primary",text:"Yes, Delete Course",action:"yes"}],function(t){"yes"==t&&o()})}var o=s("#tutor_gc_dashboard .google-classroom-class-list"),_="no",p=[];o.find("[data-action]").not("a").click(function(t){var i=s(this),o=i.data("classroom_id"),e=i.data("class_post_id"),a=i.data("action"),n=function(t){alert("Something Went Wrong!"),c(i,!0)};function r(){c(i,!1),s.ajax({url:window._tutorobject.ajaxurl,data:{class_id:o,action:"tutor_gc_class_action",action_name:a,post_id:e,enroll_student:_},type:"POST",success:function(t){try{t=JSON.parse(t)}catch(t){}var o;"object"===f(t)?(o=i.parent().attr("class","class-status-"+t.class_status),t.edit_link&&o.find(".class-edit-link").attr("href",t.edit_link),t.preview_link&&o.find(".class-preview-link").attr("href",t.preview_link),t.post_id&&o.find("[data-action]").attr("data-class_post_id",t.post_id),o.parent().find(".tutor-status").attr("class","tutor-status tutor-status-"+t.class_status).text(t.status_text),c(i,!0),0<p.length?p.shift().trigger("click"):l(!1)):n()},error:n})}"delete"==a&&t.originalEvent?u(r):"import"===a&&t.originalEvent?d(r):r()}),s("#tutor_gc_classroom_code_privilege").change(function(){var t=s(this).prop("checked")?"yes":"no";s.ajax({url:window._tutorobject.ajaxurl,data:{action:"tutor_gc_classroom_code_privilege",enabled:t},type:"POST",success:function(){},error:function(){alert("Action Failed.")}})}),s("#tutor_gc_bulk_action_button").click(function(){var o,i;function t(){p=i.slice(1),l(!0),i[0].trigger("click")}1!=l(!1)&&(o=s(this).prev().val(),i=[],s(".google-classroom-class-list .tutor-bulk-checkbox:checked").each(function(){var t=s(this).closest("tr").find('[data-action="'+o+'"]:visible');0<t.length&&i.push(t.eq(0))}),i.length?"import"==o?d(t):"delete"==o?u(t):t():alert("Please select the correct option to take an action."))}),s("#tutor_gc_dashboard").on("input","#tutor_gc_search_class",function(){var t=s(".google-classroom-class-list>tbody"),i=s(this).val()||"";""!=(i=i.trim())?t.children().each(function(){var t=s(this).children(),o=t.filter(".tutor-gc-title").text().toLowerCase().trim(),t=t.filter(".tutor-gc-code").text().toLowerCase().trim(),t=-1<o.indexOf(i.toLowerCase())||t==i;s(this)[t?"show":"hide"]()}):t.children().show()});function i(){window.location.reload()}s("#tutor_gc_credential_upgrade").click(function(t){t.preventDefault();t=s(this).data("message");t&&!confirm(t)||(c(s(this),!1),s.ajax({url:window._tutorobject.ajaxurl,data:{action:"tutor_gc_credential_upgrade"},type:"POST",success:i,error:i}))})})})();