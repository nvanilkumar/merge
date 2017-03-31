"use strict";

// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Plugins
jQuery(document).ready(function($) {

	/* Portfolio Gallery - Isotope Filtering */    
	var container = $('#i-portfolio');	
	container.isotope({
		animationEngine : 'best-available',
		animationOptions: {
			duration: 200,
			queue: false
		},
		layoutMode: 'fitRows'
	});	
	
	function splitColumns() { 
	var winWidth = $(window).width(), 
		columnNumb = 1;
	if (winWidth > 1800) {
		columnNumb = 6;
	}
	else if (winWidth > 1300) {
		columnNumb = 6;
	} else if (winWidth > 900) {
		columnNumb = 5;
	} else if (winWidth > 600) {
		columnNumb = 4;
	} else if (winWidth > 300) {
		columnNumb = 2;
	}
	return columnNumb;
	}		
	
	function setColumns() { 
	var winWidth = $(window).width(), 
		columnNumb = splitColumns(), 
		postWidth = Math.floor(winWidth / columnNumb);
	container.find('.element').each(function () { 
		$(this).css( { 
			width : postWidth + 'px' 
		});
	});
	}		
	function setProjects() { 
	setColumns();
	container.isotope('reLayout');
	}
	function loadIsotope(){
	container.imagesLoaded(function () {setProjects();});
	setProjects();
	}
	// Resize portfolio grid when window size changes
	var doit;
	$(window).bind('resize', function () { 
		clearTimeout(doit);
		doit = setTimeout(resizedw, 1000);
	});
	function resizedw(){
		setProjects();
	}
	loadIsotope();// end Portfolio Isotope
	
	/* Call HoverDir Portfolio RollOver */
	/* function loadHoverDir(){
		$(' #i-portfolio > .ch-grid').each( function() { $(this).hoverdir({
			hoverDelay : 5
		}); } );
	}
	loadHoverDir() */
	
	/* Main Menu Section Selector */
	function loadMenuSelector(){
		$('.main-menu').onePageNav({
			begin: function() {
			console.log('start');
			},
			end: function() {
			console.log('stop');
			},
		scrollOffset: 150 // header Height
		});
	}
	loadMenuSelector()
	
	/* function loadsuperslides(){
      $('#slides-1').superslides({
        hashchange: false,
        animation: 'fade',
		play: 10000,
		pagination: false
      });
	}
	loadsuperslides(); */
	
	/* FancyBox */
	$(".fancybox").fancybox();
	/*  Media helper. Group items, disable animations, hide arrows, enable media and button helpers */
	$('.fancybox-media')
		.attr('rel', 'media-gallery')
		.fancybox({
			openEffect : 'none',
			closeEffect : 'none',
			prevEffect : 'none',
			nextEffect : 'none',

			arrows : false,
			helpers : {
				media : {},
				buttons : {}
			}
		});
	
	/* Audio Player */     
	
	/* Parallax */
	function loadParallax(){
	$('.parallax').scrolly({bgParallax: true});
	};
	//loadParallax()
	
});// end Plugins
