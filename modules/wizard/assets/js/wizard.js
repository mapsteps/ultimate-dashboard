(()=>{var e={};function n(e){let n=document.querySelector(e);return n instanceof HTMLElement?n:null}function t(e){let n=document.querySelectorAll(e),t=[];for(let e=0;e<n.length;e++){let s=n[e];s instanceof HTMLElement&&t.push(s)}return t}function s(e){let n=document.querySelector(e);return n instanceof HTMLInputElement?n:null}function i(e){let n=document.querySelectorAll(e),t=[];for(let e=0;e<n.length;e++){let s=n[e];s instanceof HTMLInputElement&&t.push(s)}return t}e=tns,function(a){let o;let d=n(".udb-wizard-page"),c=n(".wizard-heatbox .heatbox-footer"),l=n(".wizard-heatbox .skip-button"),r=n(".wizard-heatbox .save-button"),u=n("#skip-setup-wizard"),g=n(".wizard-heatbox .subscribe-button");s("#udb_widgets__remove-all");let f=n(".udb-skip-discount a"),h=t("[data-udb-show-on='subscribe']"),b=t("[data-udb-show-on='skip-discount']"),v=n(".wizard-heatbox .for-discount"),_=s("#udb_modules__login_redirect"),m=n("#explore-settings"),p=n(".wizard-heatbox .udb-dots"),w=n("#remove_by_roles"),L=window.udbWizard,k=["modules","widgets","general_settings","custom_login_url","subscription","finished"],x="modules",E=!1,z=[],j=!1;function y(e){var n=[];e.length&&e.forEach(function(e){n.push(e.id)}),z=n}function T(e){(function(e){switch(e){case"modules":v?.classList.add("is-hidden"),c?.classList.remove("is-hidden"),S("Next",["js-save-widgets","js-save-general-settings","js-save-custom-login-url"]);break;case"widgets":S("Next",["js-save-general-settings","js-save-custom-login-url"],"js-save-widgets");break;case"general_settings":S("Next",["js-save-widgets","js-save-custom-login-url"],"js-save-general-settings");break;case"custom_login_url":S("Next",["js-save-widgets","js-save-general-settings"],"js-save-custom-login-url");break;case"subscription":v?.classList.remove("is-hidden"),c?.classList.add("is-hidden");break;case"finished":v?.classList.add("is-hidden"),c?.classList.add("is-hidden")}})(x=k[e.index]),function(e){let n=t(".tns-nav > button");Array.from(n).slice(0,e).forEach(e=>e.classList.add("completed")),n.forEach((n,t)=>{t>=e&&n.classList.remove("completed")})}(e.index),function(){let e=n(".skip-wizard");["subscription","finished"].includes(x)?e?.classList.add("is-hidden"):e?.classList.remove("is-hidden")}(),"finished"===x&&m?.classList.remove("is-hidden")}function S(e,n,t=""){r&&(n.forEach(e=>r.classList.remove(e)),t&&r.classList.add(t),r.textContent=e)}function C(e,n,t){E=!0,a.post(L?.ajaxUrl??"",e,function(e){E=!1,N(t),e.success?n():alert(e.data)}).fail(H).always(function(){N(t)})}function H(e){var n="Something went wrong";e.responseJSON&&e.responseJSON.data&&(n=e.responseJSON.data),alert(n)}function M(e){e?.classList.add("is-loading")}function N(e){e?.classList.remove("is-loading")}function A(){q(h),q(v,!1)}function q(e,n=!0){e&&(Array.isArray(e)?e.forEach(e=>e.classList.toggle("is-hidden",!n)):e.classList.toggle("is-hidden",!n))}function U(){q(h,!1),q(v,!1),q(b),o.goTo("next")}d&&c&&l&&r&&v&&g&&f&&(o=(0,e.tns)({container:".udb-wizard-slides",items:1,loop:!1,autoHeight:!0,controls:!1,navPosition:"bottom",onInit:function(e){o.events.on("indexChanged",T),p&&e.navContainer&&(p.appendChild(e.navContainer),function(e){if(p&&e.navContainer){let n=e.navContainer.children;if(n.length>2){let e=n[n.length-1],t=n[n.length-2];e instanceof HTMLElement&&(e.style.display="none"),t instanceof HTMLElement&&(t.style.display="none")}}else console.error("dotsWrapper is not defined.")}(e))}}),w&&(a(w).select2(),y(a(w).select2("data")),a(w).on("select2:select",function(e){let n=a(w).select2("data");var t=[];"all"===e.params.data.id?(a(w).val("all"),a(w).trigger("change")):n.length&&(n.forEach(function(e){"all"!==e.id&&t.push(e.id)}),a(w).val(t),a(w).trigger("change")),y(a(w).select2("data"))}),a(w).on("select2:unselect",function(e){y(a(w).select2("data"))})),l?.addEventListener("click",function(){switch(x){case"modules":(function(){if(p){let e=p.children;_&&!_.checked?(j=!0,e[3]?.classList.add("is-hidden")):e[3]?.classList.remove("is-hidden")}o.goTo("next")})();break;case"widgets":case"general_settings":case"custom_login_url":o.goTo("next");break;case"subscription":window.location.href=L?.adminUrl??""}}),u?.addEventListener("click",function(){o.goTo(4)}),r?.addEventListener("click",function(){if(!E){let e;M(r),C((e={action:"udb_onboarding_wizard_save_modules",nonce:L?.nonces.saveModules,modules:function(){let e=i('.udb-modules-slide .module-toggle input[type="checkbox"]');if(!e.length)return[];let n=[];return e.forEach(function(e){var t=e.id.replace("udb_modules__","");e.checked&&n.push(t)}),n}()},r?.classList.contains("js-save-widgets")?e={action:"udb_onboarding_wizard_save_widgets",nonce:L?.nonces.saveWidgets,widgets:function(){let e=i('.udb-widgets-slide .widget-toggle input[type="checkbox"]');if(!e.length)return[];let n=[];return e.forEach(function(e){var t=e.id.replace("udb_widgets__","");e.checked&&n.push(t)}),n}()}:r?.classList.contains("js-save-general-settings")?e={action:"udb_onboarding_wizard_save_general_settings",nonce:L?.nonces.saveGeneralSettings,settings:function(){var e=i('.udb-general-settings-slide .setting-toggle input[type="checkbox"]');if(!e.length)return[];let n=[];return e.forEach(function(e){var t=e.id.replace("udb_settings__","");e.checked&&n.push(t)}),n}(),selected_roles:z}:r?.classList.contains("js-save-custom-login-url")&&(e={action:"udb_onboarding_wizard_save_custom_login_url",nonce:L?.nonces.saveCustomLoginUrl,loginUrl:function(){let e=s("#udb_login_redirect");return e?.value??""}()}),e),()=>o.goTo(j?4:"next"),r)}}),g?.addEventListener("click",function(){if(E)return;M(g);let e=s("#udb-subscription-name")?.value??"",n=s("#udb-subscription-email")?.value??"";C({action:"udb_onboarding_wizard_subscribe",nonce:L?.nonces.subscribe,name:e,email:n},A,g)}),f?.addEventListener("click",function(e){e.preventDefault(),E||(M(f?.parentElement),C({action:"udb_onboarding_wizard_skip_discount",nonce:L?.nonces.skipDiscount,referrer:d?.dataset.udbReferrer},U,f?.parentElement))}),_?.addEventListener("change",function(){let e=n(".wizard-heatbox .udb-dots .tns-nav");if(!e)return;let t=e.children;_?.checked?t.length>=4&&t[3].classList.remove("is-hidden"):t.length>=4&&t[3].classList.add("is-hidden")}))}(jQuery)})();
//# sourceMappingURL=wizard.js.map
