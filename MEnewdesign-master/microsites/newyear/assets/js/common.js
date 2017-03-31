/*Discount and Offers  Sections*/
(function(jQuery){

	$.fn.BreakingNews = function(settings){
			var defaults={
					background		:'#FFF',
					title			:'NEWS',
					titlecolor		:'#FFF',
					titlebgcolor	:'#5aa628',
					linkcolor		:'#333',
					linkhovercolor	:'#5aa628',
					fonttextsize	:16,
					isbold			:false,
					border			:'none',
					width			:'100%',
					autoplay		:true,
					timer			:3000,
					modulid			:'brekingnews',
					effect			:'fade'	//or slide	
				};
			var settings=$.extend(defaults,settings);
			
			return this.each(function(){
				settings.modulid="#"+$(this).attr("id");
				var timername=settings.modulid;
				var activenewsid=1;
				
				if (settings.isbold==true)
					fontw='bold';
				else
					fontw='normal';	
					
				if (settings.effect=='slide')
					$(settings.modulid+' ul li').css({'display':'block'});
				else
					$(settings.modulid+' ul li').css({'display':'none'});				
				
				$(settings.modulid+' .bn-title').html(settings.title);
				$(settings.modulid).css({'width':settings.width, 'background':settings.background, 'border':settings.border, 'font-size':settings.fonttextsize });
				$(settings.modulid+' ul').css({'left':$(settings.modulid+' .bn-title').width()+40});
				$(settings.modulid+' .bn-title').css({'background':settings.titlebgcolor,'color':settings.titlecolor,'font-weight':fontw});
				$(settings.modulid+' ul li a').css({'color':settings.linkcolor,'font-weight':fontw,'height':parseInt(settings.fonttextsize)+6});
				$(settings.modulid+' ul li').eq( parseInt(activenewsid-1) ).css({'display':'block'});
				
				// Links hover events ......
				$(settings.modulid+' ul li a').hover(function() 
					{
                    	$(this).css({'color':settings.linkhovercolor});
					},
					function ()
					{
						$(this).css({'color':settings.linkcolor});
					}
				);
				
				
				// Arrows Click Events ......
				$(settings.modulid+' .bn-arrows span').click(function(e) {
                    if ( $(this).attr('class')=="bn-arrows-left" )
						BnAutoPlay('prev');
					else
						BnAutoPlay('next');
                });
				
				// Timer events ...............
				if (settings.autoplay==true)
				{
					timername=setInterval(function(){BnAutoPlay('next')},settings.timer);					
					$(settings.modulid).hover(function()
						{
							clearInterval(timername);
						},
						function()
						{
							timername=setInterval(function(){BnAutoPlay('next')},settings.timer);
						}
					);
				}
				else
				{
					clearInterval(timername);
				}
				
				//timer and click events function ...........
				function BnAutoPlay(pos)
				{
					if ( pos=="next" )
					{
						if ( $(settings.modulid+' ul li').length>activenewsid )
							activenewsid++;
						else
							activenewsid=1;
					}
					else
					{
						if (activenewsid-2==-1)
							activenewsid=$(settings.modulid+' ul li').length;
						else
							activenewsid=activenewsid-1;						
					}
					
					if (settings.effect=='fade')
					{
						$(settings.modulid+' ul li').css({'display':'none'});
						$(settings.modulid+' ul li').eq( parseInt(activenewsid-1) ).fadeIn();
					}
					else
					{
						$(settings.modulid+' ul').animate({'marginTop':-($(settings.modulid+' ul li').height()+20)*(activenewsid-1)});
					}
				}
				
				// links size calgulating function ...........
				$(window).resize(function(e) {
                    if ( $(settings.modulid).width()<680 ) //360
					{
						$(settings.modulid+' .bn-title').html('&nbsp;');
						$(settings.modulid+' .bn-title').css({ 'width':'4px', 'padding':'10px 0px'});
						$(settings.modulid+' ul').css({'left':4});
					}else
					{
						$(settings.modulid+' .bn-title').html(settings.title);
						$(settings.modulid+' .bn-title').css({ 'width':'auto', 'padding':'10px 20px'});
						$(settings.modulid+' ul').css({'left':$(settings.modulid+' .bn-title').width()+40});
					}
                });
			});
			
		};
		
})(jQuery);





 // ----------------------------------------------------------------------------
 // Vegas – Fullscreen Backgrounds and Slideshows with jQuery.
 // v1.3.3 - released 2013-09-03 13:27
 // Licensed under the MIT license.
 // http://vegas.jaysalvat.com/
 // ----------------------------------------------------------------------------
 // Copyright (C) 2010-2013 Jay Salvat
 // http://jaysalvat.com/
 // ----------------------------------------------------------------------------

(function(e){function t(a,n){var r={align:"center",valign:"center"};if(e.extend(r,n),0===a.height())return a.load(function(){t(e(this),n)}),void 0;var i,s,g,d=o(),l=d.width,u=d.height,v=a.width(),c=a.height(),p=u/l,f=c/v;p>f?(i=u/f,s=u):(i=l,s=l*f),g={width:i+"px",height:s+"px",top:"auto",bottom:"auto",left:"auto",right:"auto"},isNaN(parseInt(r.valign,10))?"top"==r.valign?g.top=0:"bottom"==r.valign?g.bottom=0:g.top=(u-s)/2:g.top=0-(s-u)/100*parseInt(r.valign,10)+"px",isNaN(parseInt(r.align,10))?"left"==r.align?g.left=0:"right"==r.align?g.right=0:g.left=(l-i)/2:g.left=0-(i-l)/100*parseInt(r.align,10)+"px",a.css(g)}function a(){d.prependTo("body").fadeIn()}function n(){d.fadeOut("fast",function(){e(this).remove()})}function r(){return e("body").css("backgroundImage")?e("body").css("backgroundImage").replace(/url\("?(.*?)"?\)/i,"$1"):void 0}function o(){var e=window,t="inner";return"innerWidth"in window||(e=document.documentElement||document.body,t="client"),{width:e[t+"Width"],height:e[t+"Height"]}}var i,s=e("<img />").addClass("vegas-background"),g=e("<div />").addClass("vegas-overlay"),d=e("<div />").addClass("vegas-loading"),l=e(),u=null,v=[],c=0,p=5e3,f=function(){},h={init:function(o){var i={src:r(),align:"center",valign:"center",fade:0,loading:!0,load:function(){},complete:function(){}};e.extend(i,e.vegas.defaults.background,o),i.loading&&a();var g=s.clone();return g.css({position:"fixed",left:"0px",top:"0px"}).bind("load",function(){g!=l&&(e(window).bind("load resize.vegas",function(){t(g,i)}),l.is("img")?(l.stop(),g.hide().insertAfter(l).fadeIn(i.fade,function(){e(".vegas-background").not(this).remove(),e("body").trigger("vegascomplete",[this,c-1]),i.complete.apply(g,[c-1])})):g.hide().prependTo("body").fadeIn(i.fade,function(){e("body").trigger("vegascomplete",[this,c-1]),i.complete.apply(this,[c-1])}),l=g,t(l,i),i.loading&&n(),e("body").trigger("vegasload",[l.get(0),c-1]),i.load.apply(l.get(0),[c-1]),c&&(e("body").trigger("vegaswalk",[l.get(0),c-1]),i.walk.apply(l.get(0),[c-1])))}).attr("src",i.src),e.vegas},destroy:function(t){return t&&"background"!=t||(e(".vegas-background, .vegas-loading").remove(),e(window).unbind("*.vegas"),l=e()),t&&"overlay"!=t||e(".vegas-overlay").remove(),clearInterval(i),e.vegas},overlay:function(t){var a={src:null,opacity:null};return e.extend(a,e.vegas.defaults.overlay,t),g.remove(),g.css({margin:"0",padding:"0",position:"fixed",left:"0px",top:"0px",width:"100%",height:"100%"}),a.src&&g.css("backgroundImage","url("+a.src+")"),a.opacity&&g.css("opacity",a.opacity),g.prependTo("body"),e.vegas},slideshow:function(t,a){var n={step:c,delay:p,preload:!1,backgrounds:v,walk:f};if(e.extend(n,e.vegas.defaults.slideshow,t),n.backgrounds!=v&&(t.step||(n.step=0),t.walk||(n.walk=function(){}),n.preload&&e.vegas("preload",n.backgrounds)),v=n.backgrounds,p=n.delay,c=n.step,f=n.walk,clearInterval(i),!v.length)return e.vegas;var r=function(){0>c&&(c=v.length-1),(c>=v.length||!v[c-1])&&(c=0);var t=v[c++];t.walk=n.walk,t.fade===void 0&&(t.fade=n.fade),t.fade>n.delay&&(t.fade=n.delay),e.vegas(t)};return r(),a||(u=!1,e("body").trigger("vegasstart",[l.get(0),c-1])),u||(i=setInterval(r,n.delay)),e.vegas},next:function(){var t=c;return c&&(e.vegas("slideshow",{step:c},!0),e("body").trigger("vegasnext",[l.get(0),c-1,t-1])),e.vegas},previous:function(){var t=c;return c&&(e.vegas("slideshow",{step:c-2},!0),e("body").trigger("vegasprevious",[l.get(0),c-1,t-1])),e.vegas},jump:function(t){var a=c;return c&&(e.vegas("slideshow",{step:t},!0),e("body").trigger("vegasjump",[l.get(0),c-1,a-1])),e.vegas},stop:function(){var t=c;return c=0,u=null,clearInterval(i),e("body").trigger("vegasstop",[l.get(0),t-1]),e.vegas},pause:function(){return u=!0,clearInterval(i),e("body").trigger("vegaspause",[l.get(0),c-1]),e.vegas},get:function(e){return null===e||"background"==e?l.get(0):"overlay"==e?g.get(0):"step"==e?c-1:"paused"==e?u:void 0},preload:function(t){var a=[];for(var n in t)if(t[n].src){var r=document.createElement("img");r.src=t[n].src,a.push(r)}return e.vegas}};e.vegas=function(t){return h[t]?h[t].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof t&&t?(e.error("Method "+t+" does not exist"),void 0):h.init.apply(this,arguments)},e.vegas.defaults={background:{},slideshow:{},overlay:{}}})(jQuery);


/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.0.6
 * 
 * Requires: 1.2.2+
 */
(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery);



 /* jQuery Easing v1.3 */


eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('h.i[\'1a\']=h.i[\'z\'];h.O(h.i,{y:\'D\',z:9(x,t,b,c,d){6 h.i[h.i.y](x,t,b,c,d)},17:9(x,t,b,c,d){6 c*(t/=d)*t+b},D:9(x,t,b,c,d){6-c*(t/=d)*(t-2)+b},13:9(x,t,b,c,d){e((t/=d/2)<1)6 c/2*t*t+b;6-c/2*((--t)*(t-2)-1)+b},X:9(x,t,b,c,d){6 c*(t/=d)*t*t+b},U:9(x,t,b,c,d){6 c*((t=t/d-1)*t*t+1)+b},R:9(x,t,b,c,d){e((t/=d/2)<1)6 c/2*t*t*t+b;6 c/2*((t-=2)*t*t+2)+b},N:9(x,t,b,c,d){6 c*(t/=d)*t*t*t+b},M:9(x,t,b,c,d){6-c*((t=t/d-1)*t*t*t-1)+b},L:9(x,t,b,c,d){e((t/=d/2)<1)6 c/2*t*t*t*t+b;6-c/2*((t-=2)*t*t*t-2)+b},K:9(x,t,b,c,d){6 c*(t/=d)*t*t*t*t+b},J:9(x,t,b,c,d){6 c*((t=t/d-1)*t*t*t*t+1)+b},I:9(x,t,b,c,d){e((t/=d/2)<1)6 c/2*t*t*t*t*t+b;6 c/2*((t-=2)*t*t*t*t+2)+b},G:9(x,t,b,c,d){6-c*8.C(t/d*(8.g/2))+c+b},15:9(x,t,b,c,d){6 c*8.n(t/d*(8.g/2))+b},12:9(x,t,b,c,d){6-c/2*(8.C(8.g*t/d)-1)+b},Z:9(x,t,b,c,d){6(t==0)?b:c*8.j(2,10*(t/d-1))+b},Y:9(x,t,b,c,d){6(t==d)?b+c:c*(-8.j(2,-10*t/d)+1)+b},W:9(x,t,b,c,d){e(t==0)6 b;e(t==d)6 b+c;e((t/=d/2)<1)6 c/2*8.j(2,10*(t-1))+b;6 c/2*(-8.j(2,-10*--t)+2)+b},V:9(x,t,b,c,d){6-c*(8.o(1-(t/=d)*t)-1)+b},S:9(x,t,b,c,d){6 c*8.o(1-(t=t/d-1)*t)+b},Q:9(x,t,b,c,d){e((t/=d/2)<1)6-c/2*(8.o(1-t*t)-1)+b;6 c/2*(8.o(1-(t-=2)*t)+1)+b},P:9(x,t,b,c,d){f s=1.l;f p=0;f a=c;e(t==0)6 b;e((t/=d)==1)6 b+c;e(!p)p=d*.3;e(a<8.w(c)){a=c;f s=p/4}m f s=p/(2*8.g)*8.r(c/a);6-(a*8.j(2,10*(t-=1))*8.n((t*d-s)*(2*8.g)/p))+b},H:9(x,t,b,c,d){f s=1.l;f p=0;f a=c;e(t==0)6 b;e((t/=d)==1)6 b+c;e(!p)p=d*.3;e(a<8.w(c)){a=c;f s=p/4}m f s=p/(2*8.g)*8.r(c/a);6 a*8.j(2,-10*t)*8.n((t*d-s)*(2*8.g)/p)+c+b},T:9(x,t,b,c,d){f s=1.l;f p=0;f a=c;e(t==0)6 b;e((t/=d/2)==2)6 b+c;e(!p)p=d*(.3*1.5);e(a<8.w(c)){a=c;f s=p/4}m f s=p/(2*8.g)*8.r(c/a);e(t<1)6-.5*(a*8.j(2,10*(t-=1))*8.n((t*d-s)*(2*8.g)/p))+b;6 a*8.j(2,-10*(t-=1))*8.n((t*d-s)*(2*8.g)/p)*.5+c+b},F:9(x,t,b,c,d,s){e(s==u)s=1.l;6 c*(t/=d)*t*((s+1)*t-s)+b},E:9(x,t,b,c,d,s){e(s==u)s=1.l;6 c*((t=t/d-1)*t*((s+1)*t+s)+1)+b},16:9(x,t,b,c,d,s){e(s==u)s=1.l;e((t/=d/2)<1)6 c/2*(t*t*(((s*=(1.B))+1)*t-s))+b;6 c/2*((t-=2)*t*(((s*=(1.B))+1)*t+s)+2)+b},A:9(x,t,b,c,d){6 c-h.i.v(x,d-t,0,c,d)+b},v:9(x,t,b,c,d){e((t/=d)<(1/2.k)){6 c*(7.q*t*t)+b}m e(t<(2/2.k)){6 c*(7.q*(t-=(1.5/2.k))*t+.k)+b}m e(t<(2.5/2.k)){6 c*(7.q*(t-=(2.14/2.k))*t+.11)+b}m{6 c*(7.q*(t-=(2.18/2.k))*t+.19)+b}},1b:9(x,t,b,c,d){e(t<d/2)6 h.i.A(x,t*2,0,c,d)*.5+b;6 h.i.v(x,t*2-d,0,c,d)*.5+c*.5+b}});',62,74,'||||||return||Math|function|||||if|var|PI|jQuery|easing|pow|75|70158|else|sin|sqrt||5625|asin|||undefined|easeOutBounce|abs||def|swing|easeInBounce|525|cos|easeOutQuad|easeOutBack|easeInBack|easeInSine|easeOutElastic|easeInOutQuint|easeOutQuint|easeInQuint|easeInOutQuart|easeOutQuart|easeInQuart|extend|easeInElastic|easeInOutCirc|easeInOutCubic|easeOutCirc|easeInOutElastic|easeOutCubic|easeInCirc|easeInOutExpo|easeInCubic|easeOutExpo|easeInExpo||9375|easeInOutSine|easeInOutQuad|25|easeOutSine|easeInOutBack|easeInQuad|625|984375|jswing|easeInOutBounce'.split('|'),0,{}))