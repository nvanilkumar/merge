$(function () {
	    var austDay = new Date();
	    austDay = new Date(austDay.getFullYear() + 1, 3 - 1, 10);
	    $('#defaultCountdown').countdown({until: austDay});
	    $('#year').text(austDay.getFullYear());
    });
	// JavaScript Document