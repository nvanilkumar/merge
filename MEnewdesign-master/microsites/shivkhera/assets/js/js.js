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
    $('.popup-link').magnificPopup({ 
      type: 'image',
      gallery:{enabled:true}
        // other options
    });

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
    $(function(){
        $(window).stellar({ 
            horizontalScrolling: false,
            responsive: true,
            parallaxBackgrounds: true
        });
    });

});// END READY


    