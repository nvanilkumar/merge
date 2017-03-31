//CSS Menu
$(document).ready(function() {
    $("#cssmenu a").click(function() {
        var link = $(this);
        var closest_ul = link.closest("ul");
        var parallel_active_links = closest_ul.find(".active")
        var closest_li = link.closest("li");
        var link_status = closest_li.hasClass("active");
        var count = 0;

        closest_ul.find("ul").slideUp(function() {
            if (++count == closest_ul.find("ul").length)
                parallel_active_links.removeClass("active");
        });

        if (!link_status) {
            closest_li.children("ul").slideDown();
            closest_li.addClass("active");
        }
    })
});
(function($){
	//Responsive Menu Toggle
	$('#mobile-menu-toggle').click(function(e){
		e.preventDefault();
		$('.container .leftFixed,.sidebar-full-height-bg').toggleClass('fs-show-me-on-screen');
		$('.container .rightArea').toggleClass('fs-move-me-to-right');
	});
	
	//Header Toggle Menu System
	$('.helpScreenOverlay').show();
    $('.helpScreenOverlay').click(function () {
        $(this).hide();
        $('body').css('overflow', 'scroll');
    });

    $('#user-toggle').click( function(event){
        event.stopPropagation();
        $("#helpdropdown-menu").hide();
        $('#dropdown-menu').toggle();
    });
	$("#help-toggle").click(function (event) {
		event.stopPropagation();
		$('#dropdown-menu').hide();
        $("#helpdropdown-menu").toggle();
    });
    $(document).click( function(){
        $('#dropdown-menu').hide();
        $("#helpdropdown-menu").hide();
    });
    $(document).scroll( function(){
        $('#dropdown-menu').hide();
        $("#helpdropdown-menu").hide();
    });
    $("#selCountry-toggle").click(function () {
        $("#selCountry-menu").addClass("newClass");
        $("#selCountry-menu").toggle("none");
    });
    
    //Match Height
	$('.graphSec .Box1,.db_Eventbox').matchHeight();
})(jQuery);

