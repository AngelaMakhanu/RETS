(()=>{"use strict";window.jQuery(document).ready(function(i){var t=wp.i18n,a=t.__;t._x,t._n,t._nx;function s(t,n,o){var e=new Date;e.setTime(e.getTime()+24*o*60*60*1e3);e="expires="+e.toUTCString();document.cookie=t+"="+n+";"+e+";path="+window._tutorobject.base_path}function c(t){for(var n=t+"=",o=document.cookie.split(";"),e=0;e<o.length;e++){for(var r=o[e];" "==r.charAt(0);)r=r.substring(1);if(0==r.indexOf(n))return r.substring(n.length,r.length)}return""}function o(t){var o,e,r;o=function(t){for(var t=(t+"=".repeat((4-t.length%4)%4)).replace(/\-/g,"+").replace(/_/g,"/"),n=window.atob(t),o=new Uint8Array(n.length),e=0;e<n.length;++e)o[e]=n.charCodeAt(e);return o}(window._tutorobject.tutor_pn_vapid_key),e=t,r=function(t,n,o){n.active.postMessage(JSON.stringify({client_id:window._tutorobject.tutor_pn_client_id,browser_key:c("tutor_pn_browser_key")})),0==window._tutorobject.tutor_pn_client_id||!o&&"yes"==window._tutorobject.tutor_pn_subscription_saved||i.ajax({url:window._tutorobject.ajaxurl,type:"POST",async:!0,data:{action:"tutor_pn_save_subscription",subscription:JSON.stringify(t)}})},navigator.serviceWorker.ready.then(function(n){return n.pushManager?void n.pushManager.getSubscription().then(function(t){null===t?n.pushManager.subscribe({applicationServerKey:o,userVisibleOnly:!0}).then(function(t){setTimeout(function(){navigator.userAgent.indexOf("Mac OS X")&&e&&alert(a("Thanks! Please make sure browser notification is enbled in notification settings.","tutor-pro"))},1),r(t,n,!0)}).catch(function(t){console.warn("granted"!==Notification.permission?"PN Permission denied":"PN subscription error")}):r(t,n)}):(s("tutor_pn_dont_ask","yes",365),void alert(a("This browser does not support push notification","tutor-pro")))}).catch(function(t){console.error("Service Worker error",t)})}"serviceWorker"in navigator?navigator.serviceWorker.register(window._tutorobject.home_url+"/tutor-push-notification.js").then(function(t){var n;"denied"!=Notification.permission&&(window._tutorobject.tutor_pn_vapid_key?"granted"!=Notification.permission?(n=i("#tutor-pn-permission")).length&&0<window._tutorobject.tutor_pn_client_id&&!c("tutor_pn_dont_ask")&&(n.show().css({display:"block"}).animate({bottom:"0px"},1e3),n.find("#tutor-pn-enable").click(function(){o(!0)}),n.find("#tutor-pn-dont-ask").click(function(){s("tutor_pn_dont_ask","yes",365)}),n.find("#tutor-pn-enable, #tutor-pn-close, #tutor-pn-dont-ask").click(function(){n.hide()})):o():console.warn("Vapid key could not be generated."))}).catch(function(t){console.warn("Tutor PN Service Worker registration failed",t)}):console.warn("Service Worker not supported")})})();