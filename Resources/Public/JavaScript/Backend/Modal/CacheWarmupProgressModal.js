/*! For license information please see CacheWarmupProgressModal.js.LICENSE.txt */
define("TYPO3/CMS/Warming/Backend/Modal/CacheWarmupProgressModal",["TYPO3/CMS/Backend/Modal","jquery"],((r,e)=>(()=>{"use strict";var t={659:(r,e,t)=>{function o(r,e){if(!(r instanceof e))throw new TypeError("Cannot call a class as a function")}t.d(e,{Z:()=>o})},730:(r,e,t)=>{function o(r,e){for(var t=0;t<e.length;t++){var o=e[t];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(r,o.key,o)}}function a(r,e,t){return e&&o(r.prototype,e),t&&o(r,t),r}t.d(e,{Z:()=>a})},119:(r,e,t)=>{function o(r,e,t){return e in r?Object.defineProperty(r,e,{value:t,enumerable:!0,configurable:!0,writable:!0}):r[e]=t,r}t.d(e,{Z:()=>o})},513:(r,e,t)=>{function o(r,e){(null==e||e>r.length)&&(e=r.length);for(var t=0,o=new Array(e);t<e;t++)o[t]=r[t];return o}function a(r,e){return function(r){if(Array.isArray(r))return r}(r)||function(r,e){var t=null==r?null:"undefined"!=typeof Symbol&&r[Symbol.iterator]||r["@@iterator"];if(null!=t){var o,a,n=[],i=!0,l=!1;try{for(t=t.call(r);!(i=(o=t.next()).done)&&(n.push(o.value),!e||n.length!==e);i=!0);}catch(r){l=!0,a=r}finally{try{i||null==t.return||t.return()}finally{if(l)throw a}}return n}}(r,e)||function(r,e){if(r){if("string"==typeof r)return o(r,e);var t=Object.prototype.toString.call(r).slice(8,-1);return"Object"===t&&r.constructor&&(t=r.constructor.name),"Map"===t||"Set"===t?Array.from(r):"Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t)?o(r,e):void 0}}(r,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}t.d(e,{Z:()=>a})},508:(r,e,t)=>{var o;t.d(e,{Z:()=>a}),function(r){r.check="actions-check",r.info="content-info",r.listAlternative="actions-list-alternative",r.refresh="actions-refresh",r.spinner="spinner-circle-light",r.viewPage="actions-view-page"}(o||(o={}));const a=o},298:(r,e,t)=>{var o;t.d(e,{Z:()=>a}),function(r){r.toolbarSitemapMissing="cacheWarmup.toolbar.sitemap.missing",r.toolbarSitemapPlaceholder="cacheWarmup.toolbar.sitemap.placeholder",r.toolbarCopySuccessful="cacheWarmup.toolbar.copy.successful",r.notificationShowReport="cacheWarmup.notification.action.showReport",r.notificationErrorTitle="cacheWarmup.notification.error.title",r.notificationErrorMessage="cacheWarmup.notification.error.message",r.modalReportTitle="cacheWarmup.modal.report.title",r.modalReportPanelFailed="cacheWarmup.modal.report.panel.failed",r.modalReportPanelSuccessful="cacheWarmup.modal.report.panel.successful",r.modalReportActionView="cacheWarmup.modal.report.action.view",r.modalReportTotal="cacheWarmup.modal.report.message.total",r.modalReportNoUrlsCrawled="cacheWarmup.modal.report.message.noUrlsCrawled",r.modalProgressTitle="cacheWarmup.modal.progress.title",r.modalProgressButtonReport="cacheWarmup.modal.progress.button.report",r.modalProgressButtonRetry="cacheWarmup.modal.progress.button.retry",r.modalProgressButtonClose="cacheWarmup.modal.progress.button.close",r.modalProgressFailedCounter="cacheWarmup.modal.progress.failedCounter",r.modalProgressAllCounter="cacheWarmup.modal.progress.allCounter",r.modalProgressPlaceholder="cacheWarmup.modal.progress.placeholder"}(o||(o={}));const a=o},656:(r,e,t)=>{t.d(e,{Z:()=>l});var o=t(513),a=t(659),n=t(730);function i(r,e){(null==e||e>r.length)&&(e=r.length);for(var t=0,o=new Array(e);t<e;t++)o[t]=r[t];return o}var l=function(){function r(){(0,a.Z)(this,r)}return(0,n.Z)(r,null,[{key:"mergeUrlWithQueryParams",value:function(r,e){var t,a=function(r,e){var t="undefined"!=typeof Symbol&&r[Symbol.iterator]||r["@@iterator"];if(!t){if(Array.isArray(r)||(t=function(r,e){if(r){if("string"==typeof r)return i(r,e);var t=Object.prototype.toString.call(r).slice(8,-1);return"Object"===t&&r.constructor&&(t=r.constructor.name),"Map"===t||"Set"===t?Array.from(r):"Arguments"===t||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t)?i(r,e):void 0}}(r))||e&&r&&"number"==typeof r.length){t&&(r=t);var o=0,a=function(){};return{s:a,n:function(){return o>=r.length?{done:!0}:{done:!1,value:r[o++]}},e:function(r){throw r},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var n,l=!0,s=!1;return{s:function(){t=t.call(r)},n:function(){var r=t.next();return l=r.done,r},e:function(r){s=!0,n=r},f:function(){try{l||null==t.return||t.return()}finally{if(s)throw n}}}}(e.entries());try{for(a.s();!(t=a.n()).done;){var n=(0,o.Z)(t.value,2),l=n[0],s=n[1];r.searchParams.append(l,s)}}catch(r){a.e(r)}finally{a.f()}return r}},{key:"formatString",value:function(r){for(var e=arguments.length,t=new Array(e>1?e-1:0),o=1;o<e;o++)t[o-1]=arguments[o];return t.reduce((function(r,e,t){return r.replace(new RegExp("\\{".concat(t,"}")),e)}),r)}}]),r}()},140:e=>{e.exports=r},273:r=>{r.exports=e}},o={};function a(r){var e=o[r];if(void 0!==e)return e.exports;var n=o[r]={exports:{}};return t[r](n,n.exports,a),n.exports}a.n=r=>{var e=r&&r.__esModule?()=>r.default:()=>r;return a.d(e,{a:e}),e},a.d=(r,e)=>{for(var t in e)a.o(e,t)&&!a.o(r,t)&&Object.defineProperty(r,t,{enumerable:!0,get:e[t]})},a.o=(r,e)=>Object.prototype.hasOwnProperty.call(r,e),a.r=r=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(r,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(r,"__esModule",{value:!0})};var n={};return(()=>{a.r(n),a.d(n,{default:()=>m});var r,e=a(659),t=a(730),o=a(119),i=a(508),l=a(298),s=a(656),u=a(273),c=a.n(u),d=a(140),p=a.n(d);!function(r){r.reportButton="tx-warming-open-report",r.retryButton="tx-warming-retry"}(r||(r={}));const m=new(function(){function a(){(0,e.Z)(this,a),(0,o.Z)(this,"$modal",void 0),(0,o.Z)(this,"$progressBar",void 0),(0,o.Z)(this,"$allCounter",void 0),(0,o.Z)(this,"$failedCounter",void 0)}return(0,t.Z)(a,[{key:"createModal",value:function(){var r=this.buildInitialModalContent();p().currentModal?(this.$modal=p().currentModal,this.$modal.show(),this.$modal.find(".modal-body").empty().append(r)):this.$modal=this.createModalWithContent(r),this.$modal.find(".modal-footer").hide()}},{key:"updateProgress",value:function(r){var e=r.getProgressInPercent(),t=r.getNumberOfFailedUrls(),o=r.progress,a=o.current,n=o.total;this.$progressBar.addClass("active"),this.$progressBar.attr("aria-valuenow",a),this.$progressBar.attr("aria-valuemax",n),this.$progressBar.css("width","".concat(e,"%")),this.$progressBar.html("".concat(e.toFixed(2),"%")),t>0&&(this.$progressBar.addClass("progress-bar-warning"),this.$failedCounter.show().html(s.Z.formatString(TYPO3.lang[l.Z.modalProgressFailedCounter],t.toString()))),this.$allCounter.html(s.Z.formatString(TYPO3.lang[l.Z.modalProgressAllCounter],a.toString(),n.toString())),r.isFinished()&&this.$progressBar.removeClass("active").removeClass("progress-bar-warning").addClass(t>0?"progress-bar-danger":"progress-bar-success")}},{key:"getModal",value:function(){return this.$modal}},{key:"getReportButton",value:function(){return this.$modal.find("button[name=".concat(r.reportButton,"]"))}},{key:"getRetryButton",value:function(){return this.$modal.find("button[name=".concat(r.retryButton,"]"))}},{key:"dismiss",value:function(){p().dismiss()}},{key:"buildInitialModalContent",value:function(){var r=c()("<div>");return this.$progressBar=c()('<div class="progress-bar progress-bar-striped">').attr("role","progressbar").attr("aria-valuemin",0).attr("aria-valuemax",0).attr("aria-valuenow",0),this.$allCounter=c()("<div>").html(TYPO3.lang[l.Z.modalProgressPlaceholder]),this.$failedCounter=c()('<div class="badge badge-danger">'),this.$failedCounter.hide(),r.append(c()('<div class="tx-warming-progress progress">').append(this.$progressBar)).append(c()('<div class="tx-warming-counter">').append(this.$allCounter,this.$failedCounter)),r}},{key:"createModalWithContent",value:function(e){return p().advanced({title:TYPO3.lang[l.Z.modalProgressTitle],content:e,size:p().sizes.small,buttons:[{text:TYPO3.lang[l.Z.modalProgressButtonReport],icon:i.Z.listAlternative,btnClass:"btn-primary hidden",name:r.reportButton},{text:TYPO3.lang[l.Z.modalProgressButtonRetry],icon:i.Z.refresh,btnClass:"btn-default hidden",name:r.retryButton},{text:TYPO3.lang[l.Z.modalProgressButtonClose],btnClass:"btn-default",trigger:function(){return p().dismiss()}}]})}}]),a}())})(),n})()));
//# sourceMappingURL=CacheWarmupProgressModal.js.map