(()=>{function i(t){return(i="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}window.jQuery(document).ready(function(a){function r(t,o){t.prop("disabled",!o),o?t.find("img").remove():t.append('<img style="width: 13px; margin-left: 9px; vertical-align: middle; display:inline-block;" src="'+window._tutorobject.loading_icon_url+'"/>')}a(window).resize(function(){var t=a(".tutor-gc-class-shortcode .class-header");t.css("height",.5*t.eq(0).outerWidth()+"px")}).trigger("resize"),a("#tutor_gc_student_password_set button").click(function(){var t=a(this),o=t.parent().parent(),e=o.find('[name="password-1"]').val(),n=o.find('[name="password-2"]').val(),o=o.find('[name="token"]').val();e&&n&&e===n?(r(t,!1),a.ajax({url:window._tutorobject.ajaxurl,data:{action:"tutor_gc_student_set_password",token:o,password:e},type:"POST",success:function(t){window.location.replace(window._tutorobject.tutor_frontend_dashboard_url)},error:function(){r(t,!0),alert("Request Failed.")}})):alert("Invalid Password")}),a(".tutor-gc-google-thumbnail").each(function(){var t=a(this),o=t.data("thumbnail_url"),e=new Image;e.onload=function(){t.css("background-image","url("+o+")")},e.src=o}),a("#tutor_gc_stream_loader a").click(function(t){t.preventDefault();var o=a(this),e=o.parent(),n=e.find("img"),r=e.data("next_token"),t=e.data("course_id");o.add(n).toggle(),a.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{next_token:r,action:"tutor_gc_load_more_stream",course_id:t},success:function(t){try{t=JSON.parse(t)}catch(t){}o.add(n).toggle(),"object"==i(t)&&(t.html&&0!=/\S+/.test(t.html)?(e.data("next_token",t.next_token),e.before(t.html),t.next_token||e.remove()):e.remove())},error:function(){o.add(n).toggle()}})})})})();