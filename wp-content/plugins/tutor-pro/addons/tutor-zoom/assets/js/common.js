(()=>{"use strict";window.jQuery(document).ready(function(s){var c=wp.i18n.__;s(".tutor_zoom_datepicker").datepicker({dateFormat:_tutorobject.wp_date_format}),s(".tutor_zoom_timepicker").timepicker({timeFormat:"hh:mm TT"}),s(document).on("click",".update_zoom_meeting_modal_btn",function(t){t.preventDefault();var n=s(this),i=n.closest(".tutor-modal");i.find("[data-name]").each(function(){s(this).attr("name",s(this).attr("data-name"))});var e,o=i.find(":input").serializeObject();for(e in o.timezone=Intl.DateTimeFormat().resolvedOptions().timeZone,i.find("[data-name]").removeAttr("name"),o)if(!o[e])return alert(c("Please fill all the fields","tutor-pro")),void console.log(e);s.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:o,beforeSend:function(){n.addClass("tutor-updating-message")},success:function(t){var e=t||{},o=(e.success,e.data),r=void 0===o?{}:o,a=r.selector,e=r.replace_selector,o=r.course_contents,r=r.editor_modal_html;t.success?(console.log(t),tutor_toast(c("Success","tutor-pro"),c("Meeting Updated","tutor-pro"),"success"),o?("course-builder"==a?(e?s(e).replaceWith(o):n.closest(".tutor-topics-body").find(".tutor-lessons").append(o),enable_sorting_topic_lesson()):s(t.data.selector).html(t.data.course_contents),r&&(console.log("Called repl"),i.replaceWith(r)),s(".tutor_zoom_timepicker").timepicker({timeFormat:"hh:mm TT"}),s(".tutor_zoom_datepicker").datepicker({dateFormat:_tutorobject.wp_date_format,minDate:0}),i.removeClass("tutor-is-active"),window.dispatchEvent(new Event(_tutorobject.content_change_event))):window.location.reload()):tutor_toast("Error!",((t,e)=>{const o=wp.i18n["__"];var{data:t={}}=t||{},{message:e=e||o("Something Went Wrong!","tutor")}=t;return e})(t),"error")},complete:function(){n.removeClass("tutor-updating-message")}})})})})();