(()=>{"use strict";function e(e){return document.querySelector(e)}function a(e){return document.getElementsByName(e)}function r(e){return function(e){if(Array.isArray(e))return e}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(e){if("string"==typeof e)return o(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Map"===(n="Object"===n&&e.constructor?e.constructor.name:n)||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?o(e,t):void 0}}(e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function o(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}var n=document.getElementById("import_bulk_student");null!==n&&(n.onchange=function(e){var t=n.files;if(e.preventDefault(),t.length<=0)return!1;e=new FileReader;e.readAsText(t.item(0)),e.onload=function(e){var t=e.target.result,e=new FormData;e.append("action","import_bulk_student"),e.append(_tutorobject.nonce_key,_tutorobject._tutor_nonce),e.append("bulk_user",JSON.stringify(i(t)));var n=new XMLHttpRequest;n.open("POST",_tutorobject.ajaxurl),n.send(e),n.onreadystatechange=function(){4===n.readyState&&tutor_toast("Success","Bulk user inserted!","success")}}});function t(e,t){null!==e&&(e.checked?t.disabled=!1:t.disabled=!0)}var i=function(e){var n,e=r(e.split(/\r\n|\n/)),t=e[0],e=e.slice(1),t=t.split(","),a=[];return e.map(function(e){n=e.split(","),a.push(Object.fromEntries(t.map(function(e,t){return[e,n[t]]})))}),a},l=e("#email-custom"),c=e("[name='email-testing-email']"),u=document.querySelector(".email_template_title");null!==u&&(document.title=u.innerText+" ‹ "+_tutorobject.site_title),t(l,c),null!==l&&null!==c&&(l.onchange=function(){t(l,c)});var s=e(".loading-spinner"),d=document.getElementById("save_tutor_option");document.addEventListener("readystatechange",function(e){"complete"===e.target.readyState&&(s&&s.remove(),"undefined"!=typeof tinymce&&tinymce.activeEditor&&tinymce.activeEditor.on("change",function(e){null!==d&&(d.disabled=!1);var t=document.querySelector(".email-manage-page [data-source=".concat(e.target.id,"]"));null!==t&&((e=tinymce.activeEditor.getContent()).replace("/<[/]{0,1}div[^>]*>/i",""),t.innerHTML=e),tinymce.triggerSave()}))});var m=e("#send_a_test_email"),p=e(".template-preview"),_=(document.querySelector("[name='email-testing-email']"),e(".email_template_title"));null!==m&&(m.onclick=function(e){e.preventDefault();e=new FormData;null!==p&&e.append("email_template",p.dataset.email_template),e.append("action","send_test_email_ajax"),e.append("email_to",a("to")[0].value),e.append("email_key",a("key")[0].value),e.append(_tutorobject.nonce_key,_tutorobject._tutor_nonce);var t=m.querySelector(".tutor-icon-send-filled");t.classList.replace("tutor-icon-send-filled","tutor-icon-spinner-filled"),m.disabled=!0;var n=new XMLHttpRequest;n.open("POST",_tutorobject.ajaxurl,!0),n.send(e),n.onreadystatechange=function(e){4===n.readyState&&(tutor_toast("Success",'A test email for "'+_.innerText+'" has been sent to admin!',"success"),t.classList.replace("tutor-icon-spinner-filled","tutor-icon-send-filled"),m.disabled=!1)}}),document.querySelectorAll(".send_test_email").forEach(function(n){n.onclick=function(e){e.preventDefault();e=new FormData;e.append("email_template",n.dataset.template),e.append("action","send_test_email_ajax"),e.append("email_to",n.dataset.to),e.append("email_key",n.dataset.key),e.append(_tutorobject.nonce_key,_tutorobject._tutor_nonce);var t=new XMLHttpRequest;t.open("POST",_tutorobject.ajaxurl,!0),t.send(e),t.onreadystatechange=function(e){4===t.readyState&&tutor_toast("Success",'A test email for "'+n.dataset.label+'" has been sent to admin!',"success")}}});var u=e("#email_template_save"),f=e("#email_template_form"),y=e("#email_option_data");null!==u&&(u.onclick=function(e){var t;e.detail&&1!=e.detail||(e.preventDefault(),(e=new FormData(f)).append(y.name,y.value),e.append("action","save_email_template"),e.append(_tutorobject.nonce_key,_tutorobject._tutor_nonce),(t=new XMLHttpRequest).open("POST",_tutorobject.ajaxurl,!0),t.send(e),t.onreadystatechange=function(e){4===t.readyState&&tutor_toast("Success",'"'+_.innerText+'" email template updated!',"success")})});u=document.querySelectorAll('.email-manage-page input[type="hidden"]:not(#email_option_data), .email-manage-page input[type="text"], .email-manage-page textarea');null!==u&&u.forEach(function(e){var t=e.name,n=e.value;window.addEventListener("DOMContentLoaded",function(){var e=document.querySelector(".email-manage-page [data-source=".concat(t,"]"));null!=e&&(e.src?e.src=n:e.innerHTML=n)}),e.addEventListener("input",function(e){var t=e.target,e=t.name,t=t.value,e=document.querySelector(".email-manage-page [data-source=".concat(e,"]"));null!=e&&(e.src?e.src=t:e.innerHTML=t)})})})();