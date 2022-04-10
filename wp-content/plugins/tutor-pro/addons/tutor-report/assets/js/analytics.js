(()=>{function p(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){var a=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=a){var r,n,o=[],l=!0,c=!1;try{for(a=a.call(t);!(l=(r=a.next()).done)&&(o.push(r.value),!e||o.length!==e);l=!0);}catch(t){c=!0,n=t}finally{try{l||null==a.return||a.return()}finally{if(c)throw n}}return o}}(t,e)||s(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function w(t,e){var a="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!a){if(Array.isArray(t)||(a=s(t))||e&&t&&"number"==typeof t.length){a&&(t=a);function r(){}var n=0;return{s:r,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var o,l=!0,c=!1;return{s:function(){a=a.call(t)},n:function(){var t=a.next();return l=t.done,t},e:function(t){c=!0,o=t},f:function(){try{l||null==a.return||a.return()}finally{if(c)throw o}}}}function s(t,e){if(t){if("string"==typeof t)return r(t,e);var a=Object.prototype.toString.call(t).slice(8,-1);return"Map"===(a="Object"===a&&t.constructor?t.constructor.name:a)||"Set"===a?Array.from(t):"Arguments"===a||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)?r(t,e):void 0}}function r(t,e){(null==e||e>t.length)&&(e=t.length);for(var a=0,r=new Array(e);a<e;a++)r[a]=t[a];return r}window.onload=function(){var t=document.getElementById("tutor_analytics_search_icon");t&&(t.onclick=function(){document.getElementById("tutor_analytics_search_form").submit()});var e,c=document.querySelectorAll(".tab"),s=document.querySelectorAll(".tab-content"),a=w(c);try{for(a.s();!(e=a.n()).done;)e.value.onclick=function(t){var e,t=t.target,a=w(c);try{for(a.s();!(e=a.n()).done;){var r=e.value;r.classList.contains("active")&&r.classList.remove("active")}}catch(t){a.e(t)}finally{a.f()}t.closest(".tab").classList.add("active");var n,o=w(s);try{for(o.s();!(n=o.n()).done;){var l=n.value;l.classList.contains("active")&&l.classList.remove("active")}}catch(t){o.e(t)}finally{o.f()}document.querySelector("#".concat(t.closest(".tab").dataset.toggle)).classList.add("active")}}catch(t){a.e(t)}finally{a.f()}var r,n=w(document.querySelectorAll(".tutor-admin-report-frequency"));try{for(n.s();!(r=n.n()).done;)r.value.onclick=function(t){var e,a=t.target.dataset.key;"custom"!==a&&((t=(e=new URL(window.location.href)).searchParams).has("start_date")&&t.delete("start_date"),t.has("end_date")&&t.delete("end_date"),t.set("period",a),window.location=e)}}catch(t){n.e(t)}finally{n.f()}var o,l=w(_tutor_analytics);try{for(l.s();!(o=l.n()).done;){for(var i=o.value,u=document.getElementById("".concat(i.id,"_canvas")).getContext("2d"),d=[],f=[],m=[],y=0,g=Object.entries(i.data);y<g.length;y++){var v=p(g[y],2),b=(v[0],v[1]),h=new Date(b.date_format).toLocaleDateString("en-US",{month:"short",day:"numeric"});d.push(h),f.push(b.total),b.fees&&m.push(b.fees)}var _=[];_.push({label:i.label,backgroundColor:"#3057D5",borderColor:"#3057D5",data:f,borderWidth:2,fill:!1,lineTension:0}),m.length&&_.push({label:i.label2,backgroundColor:"rgba(200, 0, 0, 1)",borderColor:"rgba(200, 0, 0, 1)",data:m,borderWidth:2,fill:!1,lineTension:0}),new Chart(u,{type:"line",data:{labels:d,datasets:_},options:{scales:{yAxes:[{ticks:{min:0,beginAtZero:!0,callback:function(t,e,a){if(Math.floor(t)===t)return t}}}]},legend:{display:!1}}})}}catch(t){l.e(t)}finally{l.f()}document.addEventListener("click",function(t){var e="data-tutor-modal-target",a="data-tutor-modal-close";(t.target.hasAttribute(e)||t.target.closest("[".concat(e,"]")))&&(t.preventDefault(),e=(t.target.hasAttribute(e)?t.target:t.target.closest("[".concat(e,"]"))).getAttribute(e),document.getElementById(e)),(t.target.hasAttribute(a)||t.target.classList.contains("tutor-modal-overlay")||t.target.closest("[".concat(a,"]")))&&(t.preventDefault(),document.querySelectorAll(".tutor-modal.tutor-is-active").forEach(function(t){t.classList.remove("tutor-is-active")}))})},jQuery(document).ready(function(e){e(".analytics_view_course_progress").on("click",function(t){(t=t).preventDefault(),e.ajax({url:window._tutorobject.ajaxurl,type:"POST",data:{course_id:t.target.dataset.course_id,total_progress:t.target.dataset.total_progress,total_lesson:t.target.dataset.total_lesson,completed_lesson:t.target.dataset.completed_lesson,total_assignment:t.target.dataset.total_assignment,completed_assignment:t.target.dataset.completed_assignment,total_quiz:t.target.dataset.total_quiz,completed_quiz:t.target.dataset.completed_quiz,student_id:t.target.dataset.student_id,action:"view_progress"},beforeSend:function(){},success:function(t){document.getElementById("tutor_progress_modal_content").innerHTML=t,document.getElementById("modal-sticky-1").classList.add("tutor-is-active")},complete:function(){tutorAccordion()}})})})})();