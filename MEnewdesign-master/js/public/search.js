$(function () {
    $("#scrollnavtoggle").on("click", function () {
        $(this).toggleClass("active");
    });

    $('.dropdown-inverse li > a').click(function (e) {
        var selText = $(this).text();
        $(this).parents('.btn-group').find('.eventsClass').text(selText);

    });
    $('.countryList li > a').click(function (e) {
        $('.status').html(this.innerHTML);
    });

    $('.CloseFilter').click(function(){
        $("#locationContainerMobile").hide();
        $("#locationContainerMobile").css('position', 'relative');
        $('body').css({overflow: 'auto'});
    });


    $('#datepicker').datepicker({
        onSelect: function () {
            var dateVal = $(this).val();
            $('.time').html(dateVal).append('<span class="icon-downArrowH"></span>').prepend('<span class="icon_date hidden-lg hidden-md"></span>');
            $('.filterDate').show().contents().last()[0].textContent = dateVal;
            $('.filterdiv').slideUp();
            $('.CloseFilter').click();
        }
    });
    $('#datepicker2').datepicker({
        onSelect: function () {
            var dateVal = $(this).val();
            $('.time').html(dateVal).append('<span class="icon-downArrowH"></span>');
            $('.filterdiv').slideUp('slow');
            $('.filterDate').show().contents().last()[0].textContent = dateVal;
        }
    });

    $('.tags span').click(function () {
        $(this).parent().hide();
    });

    var counter = 0;
    $("#datepicker").datepicker();
    $("#datepicker2").datepicker();

    $("#viewMoreResults").click(function () {
        counter++;
        $(".eventThumbs:first").clone().insertAfter(".eventThumbs:last").hide();
        $(".eventThumbs:last").fadeIn(500);
        if (counter >= 2) {
            $(this).css('display', 'none');
            $('#noMoreEvents').show();
        }
        return false;
    });
    $('#returnToTop').click(function () {

        $('html,body').animate({
            scrollTop: 0
        }, 1000);
    });
    $("#viewMoreCat").click(function () {
        if ($(this).attr("aria-expanded") === "false")
            $(this).text("View Less");
        else
            $(this).text("View More");
    });
    
    $("#settingsBtn").click(function () {
        if ($('.navbar-collapse').is(':visible')) {
            $('.navbar-collapse').hide();
            $('.navbar-collapse').removeClass('collapse in');
        }
        if ($("#locationContainerMobile").is(':hidden')) {
            $("#locationContainerMobile").slideDown('2500');
            $("#locationContainerMobile").css('position', 'fixed');
            $("#locationContainerMobile").css('background', '#f5f6f7');
        } else {
            $("#locationContainerMobile").hide();
            $("#locationContainerMobile").css('position', 'relative');
            $('body').css({overflow: 'auto'});
        }
    });
    

    /*$("#settingsBtn").click(function () {
        if ($('.navbar-collapse').is(':visible')) {
            $('.navbar-collapse').hide();
            $('.navbar-collapse').removeClass('collapse in');
        }
        if ($("#locationContainer").is(':hidden')) {
            $("#locationContainer").slideDown('2500');
            $("#locationContainer").css('position', 'fixed');
            $("#locationContainer").css('background', '#f5f6f7');
        } else {
            $("#locationContainer").hide();
            $("#locationContainer").css('position', 'relative');
            $('body').css({overflow: 'auto'});
        }
    });*/
    
    document.querySelector("#nav-toggle2").addEventListener("click", function () {
        this.classList.toggle("active");

        if ($('#locationContainer').is(':visible')) {
            $("#locationContainer").hide();
        }
        $('.navbar-collapse').show();
    });
});