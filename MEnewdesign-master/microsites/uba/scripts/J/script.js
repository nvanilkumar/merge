/*
	Project Name : Night Club
	Author Company : Ewebcraft
	Project Date: 02 April, 2015
	Author Website : http://www.ewebcraft.com
	Author Email : ewebcraft@gmail.com
*/

$(window).load(function(){
            $('#loader').fadeOut("slow");
        });

(function($) {
  "use strict";

		new WOW().init();
		
		/* Main-Slider */

	/*$('#header-slider').owlCarousel({        
         autoPlay: true,
         navigation : true, // Show next and prev buttons
         slideSpeed : 300,
         singleItem:true,
         loop:true,
         navigationText: [
        "<i class='fa fa-chevron-left'></i>",
        "<i class='fa fa-chevron-right'></i>"],
         transitionStyle:"fade"
        });*/

		/* Main-Slider */

        		 
	// artist carousel
	/*$('#artist .carousel .item').each(function(){
	  var next = $(this).next();
	  if (!next.length) {
		next = $(this).siblings(':first');
	  }
	  next.children(':first-child').clone().appendTo($(this));
	  
	  for (var i=0;i<2;i++) {
		next=next.next();
		if (!next.length) {
			next = $(this).siblings(':first');
		}
		
		next.children(':first-child').clone().appendTo($(this));
	  }
	});*/
	
	// pretty photo function call
	$("a[data-gal^='prettyPhoto']").prettyPhoto
	({//hook: 'data-gal'});

		
		var minNumber,
            maxNumber,
            width = $(window).width();

        if (width < 1000) {
            minNumber = 1;
            maxNumber = 1;
        } else if (width > 1000) {
            minNumber = 2;
            maxNumber = 2;
        } 

	 // Events slider
	/*$('#events .bxslider').bxSlider({
	  auto: true, //  alteration
	  autoControls: false,
	  pause:4000,
	  speed:500,
	  slideWidth: 550,
	  minSlides: minNumber,
	  maxSlides: minNumber,
	  moveSlides: 1,
	  controls: false,
	  slideMargin: 20
	});
	
	$('#blogs .bxslider').bxSlider({
	  mode: 'vertical',
	  minSlides: 3,
	  maxSlides: 3,
	  moveSlides: 1,
	  prevSelector: '#slider-prev',
	  nextSelector: '#slider-next',
      nextText: '<i class="fa fa-angle-up hover fa-3x"></i>',
      prevText: '<i class="fa fa-angle-down hover fa-3x"></i>', 
	});*/
	
	$('#myTab a').click(function (e) {
	  e.preventDefault()
	  $(this).tab('show');
	});
	 $(function () {
    $('#myTab a:first').tab('show');
  });

	/* Mix-It-Up */

	$(".tabs-style-linebox nav li").click(function(){
		$(this).addClass('active');
		$(".tabs-style-linebox nav li").removeClass('active');
	});
	$('.gallery-photos-thumb').mixItUp();

    /* Mix-It-Up */

	/* Equalizer */

	function fluctuate(bar) {
    var height = Math.floor(Math.random() * 40) + 1;
    //Animate the equalizer bar repeatedly
    bar.animate({
        height: height
    }, function() {
        fluctuate($(this));
    	});
	}

		$(".bar").each(function(i) {
		    fluctuate($(this));
		});

	/* Equalizer */

		/* Map */

	function initialize() {
		    var mapCanvas = document.getElementById('map-canvas');
		    var mapOptions = {
		      center: new google.maps.LatLng(17.4468, 78.3447),
		      zoom: 17,
		      scrollwheel: false,
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    }
		    var map = new google.maps.Map(mapCanvas, mapOptions);
		}
		 google.maps.event.addDomListener(window, 'load', initialize);

		 /* Map */

		/* Smooth-Scroll */

		$(".scroll").click(function(event){
			
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top}, 2000);
		});

		/* Smooth-Scroll */
		$(function () {
	    var austDay = new Date();
	    austDay = new Date(austDay.getFullYear() + 1, 3 - 1, 10);
	    $('#defaultCountdown').countdown({until: austDay});
	    $('#year').text(austDay.getFullYear());
    });
	
	
function checkField(){
	$('.reservation-form .form-control').blur(function(){
		if($(this).val==''){
			$(this).css('background-position', 'right bottom');
		}
		else{
			$(this).css('background-position', 'left bottom');
		}
	});
}

$(function() {
      			$(' .gallery-photos-thumb > .gallery-photo-item ').each( function() { $(this).hoverdir(); } );	      
            });
})(jQuery);



 