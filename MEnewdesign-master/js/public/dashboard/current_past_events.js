$(window).load(function () {
    if ($('#dashboard_search').val().length > 0) {
        $('#searchicon').click();
    }
});
var pageType = $('#pageType').val();
function buildEventHtml(e, site_url, collaborativeEventData) {
    var eventPublishStatus = "Publish";
    var titleColorClass = ' fs-unpublished-event';
    var eventunpublishStatus = "UnPublished";
    if (e.eventStatus == 1) {
        eventPublishStatus = "Unpublish";
        titleColorClass = '';
        eventunpublishStatus = "published";
    }
    var allaccess = 1;
    var collaboratorEvent = '';
    var collaborativeData = $.map(collaborativeEventData, function (val, index) {
        return [val];
    });
    if (collaborativeEventData[e.eventId] != 'manage' && typeof (collaborativeEventData[e.eventId]) != 'undefined') {
        allaccess = 0;
    }
    if (collaborativeEventData[e.eventId] == 'promote' && typeof (collaborativeEventData[e.eventId]) != 'undefined') {
       e.eventManageurl =   site_url+"dashboard/promote/discount/"+e.eventId;
       e.eventsummaryUrl = 'javascript:;';
    }else if(collaborativeEventData[e.eventId] == 'report' && typeof (collaborativeEventData[e.eventId]) != 'undefined'){
    	  e.eventManageurl =   site_url + "dashboard/reports/"+e.eventId+"/summary/all/1";
    }
  
    if (typeof (collaborativeEventData[e.eventId]) != 'undefined') {
        collaboratorEvent = '(Collaborative Event)';
    }

    eventhtml = '<div class="db_Eventbox' + titleColorClass + '" eventid="' + e.eventId + '" eventtitle="bannercheck" eventmon="' + e.eventMonth + '">'
            + '<h4 class="fs-event-title showeventbox">'
            + '<a class="showeventbox" href="' + e.eventPreviewUrl + '" target="_blank" title="banner check">'
            + e.eventName + collaboratorEvent + '</a>'
            + '</h4>'
            + '<div class="fs-db_Eventbox-content showeventbox">'
            + '<div class="fs-event-place-time showeventbox">'
            + '<div class="fs-event-start-date showeventbox"> '
            + '<span class="icon2-clock-o showeventbox"></span>'
            + '<span class="showeventbox">' + e.eventStartDate + ' - '+ e.eventEndDate+'</span>'
            + '</div>'
            + '<div class="fs-event-city-name showeventbox">'
            + '<span class="icon-locator showeventbox"></span>'
            + '<span class="showeventbox">' + e.eventCityName + '</span>'
            + '</div>'
            + '</div>'
            + '<div class="db_Eventbox_section showeventbox">'
            + '<div class="fs-ticket-management-buttons showeventbox">'
            + '<a class="ticketsId fs-ticket-preview-button fs-btn showeventbox" href="' + e.eventPreviewUrl + '" target="_blank">'
            + 'Event ID: <strong>' + e.eventId + '</strong>'
            + '</a>'
            + '<a class="fs-ticket-manage-button fs-btn showeventbox" href="' + e.eventManageurl + '">'
            + '<span class="icon-configer showeventbox"></span>Manage'
            + '</a>'
            + '     <input type="text" hidden="hidden" value="' + e.ActualeventStartDate + '" id="' + e.eventId + 'eventStartDate">';
    if (allaccess == 1 ) {
        var eeditUrl = "0";
        var onchangeUrl = "onclick='changeEventStatus(" + eeditUrl + "," + e.eventId + ");'";
        if(pageType == 'upcoming'){
        eventhtml += '	<a class="fs-event-publish-button fs-btn showeventbox" href="javascript:void(0);" ' + onchangeUrl + '>'
                + '      	<span class="icon-publish showeventbox"></span>'
                + '  	<span class="showeventbox" id="' + e.eventId + 'publishStatusText">' + eventPublishStatus + '</span>'
                + '  </a>';
        }else {
            eventhtml += ' <a class="fs-event-publish-button fs-btn showeventbox" href="javascript:void(0);" data-disabled="disabled" style="cursor: default !important;"><span class="icon-publish "></span><span id=' + e.eventId + 'publishStatusText>' + eventunpublishStatus + '</span></a>';
        }
        eventhtml += '   <a class="fs-ticket-manage-button fs-btn showeventbox" href="javascript:void(0);" onclick="copyEvent(' + e.eventId + ')"><span class="icon-manage showeventbox"></span>Copy</a>';
    } 

    eventhtml += ' </div>'
            + '<div class="ticketsBooked showeventbox">'
            + '<h5 class="showeventbox">Tickets Booked</h5>'
            + '<a class="showeventbox" href="' + e.eventsummaryUrl + '">' + e.soldOutTickets + '</a>'
            + '<p style="padding: 25px 0;font-size: 16px;">Page Views :' + e.viewcount+'</p>'
            + '</div>'
            + '</div>'
//            + '<div class="db_Eventbox_footer showeventbox">'
//            + '<p class="fs-promote-event showeventbox">'
//            + 'Few more days to go. Find more ways to <a href="#" class="fs-inline-btn fs-btn showeventbox">Promote Event</a>'
//            + '</p> '
//            + '</div>'
            + '</div>'
            + '</div>';
    return eventhtml;
}
var eventtotal = 0;
function getEvents(data) {
    $('#viewMore').hide();
    $('#searchkeyword').html(data.keyword);
    var pageUrl = api_getEvents, method = 'POST', input = data, dataFormat = 'JSON', callbackSuccess = function (result) {
        var eventsData = '';
        if (result.response.total > 0) {
            eventtotal = result.response.totalcount;
            var searkeyword = data.keyword;
            if (searkeyword.length > 0) {
                $('#eventscount').html(eventtotal);
                $('#no-events').css('display', 'block');
            } else {
                $('#no-events').css('display', 'none');
            }

            var edata = result.response.eventList;
            var edataarray = $.map(edata, function (val, index) {
                return [val];
            });
           // edataarray = edataarray.reverse();
            $.each(edataarray, function (k, value) {
                value.eventManageurl = site_url + 'dashboard/home/' + value.eventId;
                value.eventsummaryUrl = site_url + 'dashboard/reports/' + value.eventId + '/summary/all/1';
                value.eventEditurl = site_url + 'dashboard/event/edit/' + value.eventId;
                	if(value.eventStatus == 1){
					value.eventPreviewUrl = site_url + 'event/' + value.url + '?ucode=organizer';
				}else{
                value.eventPreviewUrl = site_url + 'previewevent?view=preview&eventId=' + value.eventId;
				}
                if ($('.' + value.eventMonth).length <= 0) {
                    $('#upcoming-eventview').append('<h6 class="' + value.eventMonth + ' event-months" id= ' + value.eventMonth + '>' + value.eventMonth + '</h6>');
                }
                eventsData = buildEventHtml(value, site_url, result.response.collaborativeEventData);
                $('#upcoming-eventview').append(eventsData);
            });
        }
		else
		{
            $('#eventscount').html('no ');
			$('#no-events').css('display', 'block');
		}

        if ($('#upcoming-eventview').html() != '') {
            //$('#searchkeyword').html('');
            var pageCount = parseInt($('#page').val()) + 1;
            $('#page').val(pageCount);
            if ($('.db_Eventbox').length >= result.response.totalcount) {
                $('#viewMore').hide();
            } else {
                $('#viewMore').show();
            }
        }
        $('.graphSec .Box1,.db_Eventbox').matchHeight();

    },
            callbackFailure = function (result) {
                if (result.readyState == 4 && result.responseJSON.response.total == 0) {
                    $('#upcoming-eventview').html('');
                    $('#eventscount').html('no ');
                    $('#no-events').css('display', 'block');
                    $('#searchkeyword').html(data.keyword);
                    $('#viewMore').hide();
                    eventtotal = result.responseJSON.response.total;

                }

            };
    getPagesearchResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

}

$(document).ready(function () {
    $('#viewMore').click(function (e) {
    	var postop = $(this).offset().top;
        $(this).text('Loading...');
        var data = {};
        data.eventtype = $('#pageType').val();
        data.page = $('#page').val();
        data.callType = 'ajax';
        var keyword = $.trim($('#dashboard_search').val()).toLowerCase();
        data.keyword = keyword;
        getEvents(data);
        $('html,body').animate({
            scrollTop: postop
        }, 800);
        $(this).text('View More');
    });
});

var currentRequest = null;

$(document).on('keyup', '#dashboard_search', function () {

    var searchcount = 0;
    var keyword = $.trim($(this).val()).toLowerCase();
    var data = {};
    data.eventtype = $('#pageType').val();
    $('#page').val(0);
    data.page = 0;
    data.keyword = keyword;
    data.callType = 'ajax';
    $('#upcoming-eventview').html('');
    getEvents(data);

});

function OnSearch(input) {
    var keyword = $.trim(input.value).toLowerCase();
    var data = {};
    data.eventtype = $('#pageType').val();
    data.page = 0;
    data.keyword = keyword;
    $('#upcoming-eventview').html('');
    getEvents(data);


}

function getPagesearchResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure)
{

    currentRequest = $.ajax({
        type: method,
        url: pageUrl,
        datatype: dataFormat,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
        },
        data: input,
        beforeSend: function () {
            if (currentRequest != null) {
                currentRequest.abort();
            }
        },
        success: function (response) {
            callbackSuccess(response);
        },
        error: function (response) {
            callbackFailure(response);
        }
    });
}

function copyEvent(eventId) {
    $("#dvLoading").show();
    var pageUrl = api_copyEvent, method = 'POST', input = {eventid: eventId}, dataFormat = 'JSON', callbackSuccess = function (result) {
        var editurl = site_url + 'dashboard/event/edit/' + result.response.id;
        // $("#dvLoading").hide();
        window.location.href = editurl;
    }, callbackFailure = function (result) {
        alert(result.responseJSON.response.messages[0]);
        $("#dvLoading").hide();
    };
    getPagesearchResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
}