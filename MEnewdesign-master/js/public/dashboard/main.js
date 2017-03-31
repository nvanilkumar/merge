$('.showdropdowntop').click(function (e) {
    $(this).addClass('active');
    $(this).find('.dropdowntop').slideToggle();
}
);
$('#showdropdowntwo').click(function (e) {
    $(this).find('.dropdowntwo').slideToggle();
    e.stopPropagation();
});
$('.navigation > ul > li:nth-child(4)').click(function () {
    var view = $('.navigation > ul > li:nth-child(3) > ul').css('display');

    if (view == 'block') {
        $('.navigation > ul > li:nth-child(3)').click();
    }
// $('.navigation > ul > li:nth-child(3)').click();
});
$('.view').click(function () {
    $('.viewDetail').slideToggle();
});
$('.otherdetail').click(function () {
    $('.viewotherdetail').slideToggle();
});
// ticket options div close
//$('#ticketOptionsMessage').delay(5000).fadeOut(400);
//$('.close').click(function() {
//    $('#ticketOptionsMessage').hide();
//});
//My event related js
function convertStringToTime(inputDate) {
    var splittedDate = inputDate.split(/[- :]/);
    return new Date(splittedDate[0], splittedDate[1] - 1, splittedDate[2], splittedDate[3] || 0, splittedDate[4] || 0, splittedDate[5] || 0);
}
;
//To convert string to javascript date format
function changeEventStatus(url, eventId) {
	url = site_url+'dashboard/event/edit/'+eventId;
    var publishText = $('#' + eventId + 'publishStatusText').text();
    if (publishText == 'Publish') {
        var convertedDate = convertStringToTime($('#' + eventId + 'eventStartDate').val());
        //alert(convertedDate);
        if (new Date(convertedDate) <= new Date()) {
            $('#publishDateError').css('display', 'block');
            $('#publishDateError').show();
            $('#publishDateError').html('You are trying to publish event with Past date,Please <a href="' + site_url + 'dashboard/event/edit/' + eventId + '" style="color:#5f259f; font-style: italic;"> edit </a> the event details.For any query write to support@meraevents.com');
            $("html, body").animate({
                scrollTop: 0
            }, "fast");
            setTimeout(function () {
                $('#publishDateError').html(' ');
                $('#publishDateError').hide();
            }, 5000);
            return false;
        } else {
            var apiUrl = api_dashboardEventchangeStatus;
            $.ajax({
                url: apiUrl,
                type: "POST",
                data: {
                    eventId: eventId
                },
                headers: {
                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
                },
                cache: false,
                dataType: 'json',
                success:
                        function (data, status, x) {
                            var eventStatusValue = data.response.eventStatus.status;
                            var eventUrl =data.response.eventStatus.url;
                            if (eventStatusValue == 1) {
                                $('#' + eventId + 'publishStatusText').text('Unpublish');
                                $('#publishMsg').show();
                                var eventUrl = site_url+'event/'+eventUrl;
                                var eventhtml = '<a target="_blank" style="color:#ba36a6;" href="'+eventUrl+'">  Click Here  </a>';
                                $('#publishMsg').html('Your event has been published successfully'+eventhtml);
                                $("html, body").animate({
                                    scrollTop: 0
                                }, "fast");
                                setTimeout(function () {
                                    $('#publishMsg').hide();
                                }, 50000);
                            }
                        },
                error: function (data, x, y) {
                    console.log(data);
                }
            });
        }
    } else if (publishText == 'Unpublish') {
        var apiUrl = api_dashboardEventchangeStatus;
        $.ajax({
            url: apiUrl,
            type: "POST",
            data: {
                eventId: eventId
            },
            headers: {
                'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
            },
            cache: false,
            dataType: 'json',
            success:
                    function (data, status, x) {
                        var eventStatusValue = data.response.eventStatus.status;
                        if (eventStatusValue == 0) {
                            $('#' + eventId + 'publishStatusText').text('Publish');
                            $('#publishMsg').show();
                            $('#publishMsg').html('Your event has been unpublished successfully');
                            $("html, body").animate({
                                scrollTop: 0
                            }, "fast");
                            setTimeout(function () {
                                $('#publishMsg').hide();
                            }, 5000);
                        }
                    },
            error: function (data, x, y) {
                console.log(data);
            }
        });
    }
}
setTimeout(function () {
    $('#publishMsg').hide();
}, 5000);

$(window).load(function () {
    setTimeout(function () {
        $('.hide').slideUp('slow');
    }, 5000);
});

$(document).ready(function () {
    $('.currentsubLink').parents(".has-sub").addClass('sub-link-active active');
});

