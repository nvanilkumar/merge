(function ($) {
    "use strict";
    /*	Table OF Contents
	==========================
	0-prettyPhoto
	1-Navigation
	2-Calander
	3-Vegas Slider
	4-Jplayer
	5-Sliders
	6-Flicker
	7-Tweet
	8-Google Maps
	9-Gllery
	*/
	
	$('.more-events').click(
        function () {
         	$('.events_showcase').slideToggle(800);
			return false;
      });
	
    /*===========	0-PrettyPhoto   ===========*/     

    $(document).on('click', '.yamm .dropdown-menu', function (e) {
        e.stopPropagation();
    });

    $('ul.nav li.dropdown').click(
        function () {
            var state = $(this).data('toggleState');
            if (state) {
                $(this).children('ul.dropdown-menu').slideUp();
            } else {
                $(this).children('ul.dropdown-menu').slideDown();
            }
            $(this).data('toggleState', !state);
        });

    /*==========  2-Calander ======*/

    /*=========  3-Vegas Slider - Page Background Images  ============*/

    $.vegas('slideshow', {
        backgrounds: [{
            src: 'assets/img/BG/bg1.jpg',
            fade: 2000
        }, {
            src: 'assets/img/BG/bg2.jpg',
            fade: 2000
        }]
    });

    /*============	4-Jplayer  ============*/
    /*============  5-Sliders  ============*/

    $('#flex-home').flexslider({
        animation: "slide",
        directionNav: false,
        controlNav: false,
        slideshowSpeed: 5000,
        slideshow: true,
        pauseOnHover: true,
        direction: "horizontal" //Direction of slides
    });

    var $flex_home = $('#flex-home');
    $('#home-next').click(function () {
        $flex_home.flexslider("next");
        return false;
    });
    $('#home-prev').click(function () {
        $flex_home.flexslider("prev");
        return false;
    });

    //code goes here

    $('.albums-carousel').carouFredSel({
        width: "100%",
        height: 300,
        circular: false,
        infinite: false,
        auto: false,
        scroll: {
            items: 1,
            easing: "linear"
        },
        prev: {
            button: "a.prev-album",
            key: "left"
        },
        next: {
            button: "a.next-album",
            key: "right"
        }
    });

    /*=========== 6-Flicker  ==========*/
    /*=========== 7-Tweet =============*/

    /*=========== 8-Google Maps =======*/
    /*=========== 9-Gllery  ===========*/
 	/*var $containerfolio = $('.photo-gallery');
    function onImagesLoaded($container, callback) {
        var $images = $container.find("img");
        var imgCount = $images.length;
        if (!imgCount) {

            callback();
        } else {
            $("img", $container).each(function () {
                $(this).one("load error", function () {
                    imgCount--;
                    if (imgCount === 0) {
                        callback();
                    }
                });
                if (this.complete) $(this).load();
            });
        }
    }

    onImagesLoaded($containerfolio, function () {
       
        $containerfolio.show();
		if ($containerfolio.length) {
			$containerfolio.isotope({
				layoutMode: 'fitRows',
				filter: '*',
				animationOptions: {
					duration: 750,
					easing: 'linear',
					queue: false
				}
			});
    }

    });*/

    /*$('.photo-filter li a').click(function () {
        $('.photo-filter li').removeClass("active");
        $(this).parent().addClass("active");
        var selector = $(this).attr('data-filter');
        $containerfolio.isotope({
            filter: selector,
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            }
        });
        return false;
    });
*/

})(jQuery);