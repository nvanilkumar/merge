/*Breaking News*/
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


/*js.js*/
$(window).on("load", function() {
    "use strict";

    // Page Load Animation
    $("#preloaderV").delay(300).fadeOut("slow");
});// END LOAD

jQuery(document).ready(function($) {
    "use strict";

    // ScrollTo annimation
    $('.scrollTo').on('click',function (e) {
        e.preventDefault();
        var target = this.hash,
        $target = $(target);
        $('body, html').stop().animate({
            'scrollTop': $(target).offset().top-0
        }, 1000, 'swing', 
        function() {
            window.location.hash = target;
        });
    }); // End Click  

    // Scroll Top Animation
    $('#scrollTop').on('click',function (e) {
        $('html, body').animate({scrollTop : 0},800);
        return false;
    }); // End Click  

    // Form Validation
    $('#contact-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        }, //end rules
        messages: {
            email: {
                required: "Please type a e-mail address.",
                email: "This is not a valid email address."
            }
        }
    }); // end validate 

    // Masonry
    var $masonryC = $('#masonry');
    $masonryC.imagesLoaded( function(){
        $masonryC.masonry({
            columnWidth: '.grid-sizer',
            itemSelector : '.item'
        });
    });

    // Gallery LightBox
    /*$('.popup-link').magnificPopup({ 
      type: 'image',
      gallery:{enabled:true}
        // other options
    });*/

    // Shop Items Count
    $(".numbers-row").append('<div class="inc button">+</div><div class="dec button">-</div>');
    $(".button").on("click", function() {
        var $button = $(this);
        var oldValue = $button.parent().find("input").val();
        if ($button.text() == "+") {
          var newVal = parseFloat(oldValue) + 1;
        } else {
           // Don't allow decrementing below zero
          if (oldValue > 0) {
            var newVal = parseFloat(oldValue) - 1;
            } else {
            newVal = 0;
          }
        }
        $button.parent().find("input").val(newVal);
    }); // End Click

    // Open Close Social Media on scroll
    $(window).on("scroll", function () {
        if ($(this).scrollTop() > 100) {
            $("#social-sidebar").addClass("scrolled");
        }
        else {
            $("#social-sidebar").removeClass("scrolled");
        }
    });

    // Stellar.js Parallax
    /*$(function(){
        $(window).stellar({ 
            horizontalScrolling: false,
            responsive: true,
            parallaxBackgrounds: true
        });
    });*/

});// END READY


    


/*js cookie*/
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};