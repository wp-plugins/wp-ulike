function likeThis(e,l,s){""!=e&&(jQuery("#wp-ulike-"+e+" .counter").html('<a class="loading"></a><span class="count-box">...</span>'),jQuery.ajax({type:"POST",url:ulike_obj.ajaxurl,data:{action:"ulikeprocess",id:e},success:function(a){l+s==1?jQuery("#wp-ulike-"+e+" .counter").html("<a onclick='likeThis("+e+",1,1)' class='text'>"+ulike_obj.likeText+"</a><span class='count-box'>"+a+"</span>"):l+s==2?jQuery("#wp-ulike-"+e+" .counter").html("<a onclick='likeThis("+e+",1,0)' class='text'>"+ulike_obj.disLikeText+"</a><span class='count-box'>"+a+"</span>"):l+s==3&&jQuery("#wp-ulike-"+e+" .counter").html("<a class='text user-tooltip' title='Already Voted'>"+ulike_obj.likeText+"</a><span class='count-box'>"+a+"</span>")}}))}function likeThisComment(e,t,n){if(e!=""){jQuery("#wp-ulike-comment-"+e+" .counter").html('<a class="loading"></a><span class="count-box">...</span>');jQuery.ajax({type:"POST",url:ulike_obj.ajaxurl,data:{action:"ulikecommentprocess",id:e},success:function(r){if(t+n==1){jQuery("#wp-ulike-comment-"+e+" .counter").html("<a onclick='likeThisComment("+e+",1,1)' class='text'>"+ulike_obj.likeText+"</a><span class='count-box'>"+r+"</span>")}else if(t+n==2){jQuery("#wp-ulike-comment-"+e+" .counter").html("<a onclick='likeThisComment("+e+",1,0)' class='text'>"+ulike_obj.disLikeText+"</a><span class='count-box'>"+r+"</span>")}else if(t+n==3){jQuery("#wp-ulike-comment-"+e+" .counter").html("<a class='text user-tooltip' title='Already Voted'>"+ulike_obj.likeText+"</a><span class='count-box'>"+r+"</span>")}}})}}if(!function(t){"use strict";var e=function(t,e){this.init("tooltip",t,e)};e.prototype={constructor:e,init:function(e,i,n){var o,s,r,a,l;for(this.type=e,this.$element=t(i),this.options=this.getOptions(n),this.enabled=!0,r=this.options.trigger.split(" "),l=r.length;l--;)a=r[l],"click"==a?this.$element.on("click."+this.type,this.options.selector,t.proxy(this.toggle,this)):"manual"!=a&&(o="hover"==a?"mouseenter":"focus",s="hover"==a?"mouseleave":"blur",this.$element.on(o+"."+this.type,this.options.selector,t.proxy(this.enter,this)),this.$element.on(s+"."+this.type,this.options.selector,t.proxy(this.leave,this)));this.options.selector?this._options=t.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},getOptions:function(e){return e=t.extend({},t.fn[this.type].defaults,this.$element.data(),e),e.delay&&"number"==typeof e.delay&&(e.delay={show:e.delay,hide:e.delay}),e},enter:function(e){var i,n=t.fn[this.type].defaults,o={};return this._options&&t.each(this._options,function(t,e){n[t]!=e&&(o[t]=e)},this),i=t(e.currentTarget)[this.type](o).data(this.type),i.options.delay&&i.options.delay.show?(clearTimeout(this.timeout),i.hoverState="in",void(this.timeout=setTimeout(function(){"in"==i.hoverState&&i.show()},i.options.delay.show))):i.show()},leave:function(e){var i=t(e.currentTarget)[this.type](this._options).data(this.type);return this.timeout&&clearTimeout(this.timeout),i.options.delay&&i.options.delay.hide?(i.hoverState="out",void(this.timeout=setTimeout(function(){"out"==i.hoverState&&i.hide()},i.options.delay.hide))):i.hide()},show:function(){var e,i,n,o,s,r,a=t.Event("show");if(this.hasContent()&&this.enabled){if(this.$element.trigger(a),a.isDefaultPrevented())return;switch(e=this.tip(),this.setContent(),this.options.animation&&e.addClass("fade"),s="function"==typeof this.options.placement?this.options.placement.call(this,e[0],this.$element[0]):this.options.placement,e.detach().css({top:0,left:0,display:"block"}),this.options.container?e.appendTo(this.options.container):e.insertAfter(this.$element),i=this.getPosition(),n=e[0].offsetWidth,o=e[0].offsetHeight,s){case"bottom":r={top:i.top+i.height,left:i.left+i.width/2-n/2};break;case"top":r={top:i.top-o,left:i.left+i.width/2-n/2};break;case"left":r={top:i.top+i.height/2-o/2,left:i.left-n};break;case"right":r={top:i.top+i.height/2-o/2,left:i.left+i.width}}this.applyPlacement(r,s),this.$element.trigger("shown")}},applyPlacement:function(t,e){var i,n,o,s,r=this.tip(),a=r[0].offsetWidth,l=r[0].offsetHeight;r.offset(t).addClass(e).addClass("in"),i=r[0].offsetWidth,n=r[0].offsetHeight,"top"==e&&n!=l&&(t.top=t.top+l-n,s=!0),"bottom"==e||"top"==e?(o=0,t.left<0&&(o=-2*t.left,t.left=0,r.offset(t),i=r[0].offsetWidth,n=r[0].offsetHeight),this.replaceArrow(o-a+i,i,"left")):this.replaceArrow(n-l,n,"top"),s&&r.offset(t)},replaceArrow:function(t,e,i){this.arrow().css(i,t?50*(1-t/e)+"%":"")},setContent:function(){var t=this.tip(),e=this.getTitle();t.find(".tooltip-inner")[this.options.html?"html":"text"](e),t.removeClass("fade in top bottom left right")},hide:function(){function e(){var e=setTimeout(function(){i.off(t.support.transition.end).detach()},500);i.one(t.support.transition.end,function(){clearTimeout(e),i.detach()})}var i=this.tip(),n=t.Event("hide");return this.$element.trigger(n),n.isDefaultPrevented()?void 0:(i.removeClass("in"),t.support.transition&&this.$tip.hasClass("fade")?e():i.detach(),this.$element.trigger("hidden"),this)},fixTitle:function(){var t=this.$element;(t.attr("title")||"string"!=typeof t.attr("data-original-title"))&&t.attr("data-original-title",t.attr("title")||"").attr("title","")},hasContent:function(){return this.getTitle()},getPosition:function(){var e=this.$element[0];return t.extend({},"function"==typeof e.getBoundingClientRect?e.getBoundingClientRect():{width:e.offsetWidth,height:e.offsetHeight},this.$element.offset())},getTitle:function(){var t,e=this.$element,i=this.options;return t=e.attr("data-original-title")||("function"==typeof i.title?i.title.call(e[0]):i.title)},tip:function(){return this.$tip=this.$tip||t(this.options.template)},arrow:function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},validate:function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},enable:function(){this.enabled=!0},disable:function(){this.enabled=!1},toggleEnabled:function(){this.enabled=!this.enabled},toggle:function(e){var i=e?t(e.currentTarget)[this.type](this._options).data(this.type):this;i.tip().hasClass("in")?i.hide():i.show()},destroy:function(){this.hide().$element.off("."+this.type).removeData(this.type)}};var i=t.fn.tooltip;t.fn.tooltip=function(i){return this.each(function(){var n=t(this),o=n.data("tooltip"),s="object"==typeof i&&i;o||n.data("tooltip",o=new e(this,s)),"string"==typeof i&&o[i]()})},t.fn.tooltip.Constructor=e,t.fn.tooltip.defaults={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1},t.fn.tooltip.noConflict=function(){return t.fn.tooltip=i,this}}(window.jQuery),"undefined"==typeof jQuery)throw new Error("WP Ulike's JavaScript requires jQuery");if(+function(t){"use strict";function e(){var t=document.createElement("bootstrap"),e={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var i in e)if(void 0!==t.style[i])return{end:e[i]};return!1}t.fn.emulateTransitionEnd=function(e){var i=!1,n=this;t(this).one("bsTransitionEnd",function(){i=!0});var o=function(){i||t(n).trigger(t.support.transition.end)};return setTimeout(o,e),this},t(function(){t.support.transition=e(),t.support.transition&&(t.event.special.bsTransitionEnd={bindType:t.support.transition.end,delegateType:t.support.transition.end,handle:function(e){return t(e.target).is(this)?e.handleObj.handler.apply(this,arguments):void 0}})})}(jQuery),"undefined"==typeof jQuery)throw new Error("WP Ulike's JavaScript requires jQuery");+function(t){"use strict";function e(e){return this.each(function(){var i=t(this),o=i.data("bs.alert");o||i.data("bs.alert",o=new n(this)),"string"==typeof e&&o[e].call(i)})}var i='[data-dismiss="alert"]',n=function(e){t(e).on("click",i,this.close)};n.VERSION="3.2.0",n.prototype.close=function(e){function i(){s.detach().trigger("closed.bs.alert").remove()}var n=t(this),o=n.attr("data-target");o||(o=n.attr("href"),o=o&&o.replace(/.*(?=#[^\s]*$)/,""));var s=t(o);e&&e.preventDefault(),s.length||(s=n.hasClass("alert")?n:n.parent()),s.trigger(e=t.Event("close.bs.alert")),e.isDefaultPrevented()||(s.removeClass("in"),t.support.transition&&s.hasClass("fade")?s.one("bsTransitionEnd",i).emulateTransitionEnd(150):i())};var o=t.fn.alert;t.fn.alert=e,t.fn.alert.Constructor=n,t.fn.alert.noConflict=function(){return t.fn.alert=o,this},t(document).on("click.bs.alert.data-api",i,n.prototype.close)}(jQuery),jQuery(document).ready(function(t){t(".user-tooltip").tooltip()});