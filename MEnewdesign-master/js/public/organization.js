$(document).on('click','.eventtypes',function(){
	$('.eventtypes').removeClass('eventsactive');
	var pageType = $(this).attr('pagetype');
	$("#pageType").val(pageType);
	  var pageVal = $('#page').val();
      $('#page').val(0);
	$(this).addClass('eventsactive');
	$('#upcoming_past_events').html('');
	 var data = {};
     data.page = $('#page').val();
     data.callType = 'ajax';
     data.type=$("#pageType").val();
     getEvents(data);
});

function buildEventHtml(site_url, EventData) {
    eventhtml = '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock" itemscope="" itemtype="http://schema.org/Event">'
		+'<a href="'+EventData.url+'" class="thumbnail">'
		+'<div class="eventImg">'
		+'<img src="'+EventData.logoPath+'" width="" height="" alt="'+EventData.title+','+EventData.startDateTime+'" title="'+EventData.title+','+EventData.startDateTime+'" errimg="'+EventData.defaultlogoPath+'" onerror="setimage(this)" >'
			+'</div>'
			+'<h6>'
			+'<span class="eveHeadWrap" >'+EventData.title+','+EventData.startDateTime+' </span>'
			+'</h6>'
			+'<div class="info">'
			+'<span content="'+EventData.startDateTime+'">'+EventData.startDateTime+'</span>'
			+'</div>'
			+'<div class="overlay">'
			+'<div class="overlayButt">'
			+'<div class="overlaySocial">'
			+'<span class="icon-fb"></span> <span class="icon-tweet"></span>'
			+'<span class="icon-google"></span>'
			+'</div>'
			+'</div>'
			+'</div>'
			+'</a>'
			+'<a href="'+EventData.url+'" class="category"> <span class="icon1-'+EventData.categoryName+' col'+EventData.categoryName+'"></span>'
			+'<span class="catName"><em>'+EventData.categoryName+'</em></span>'
			+'</a>'
			+'</li>';
    return eventhtml;
}
var eventtotal = 0;
var organizationId = document.getElementById('organization').value;
function getEvents(data) {
	$('#no-events').hide();
	 $('#viewMore').hide();
	 $('#dvLoading').show();
    data.id = organizationId;
    var pageUrl = api_organizationEvents, method = 'POST', input = data, dataFormat = 'JSON', callbackSuccess = function (result) {
        var eventsData = '';
        if (result.response.total > 0) {
            eventtotal = result.response.totalcount;
       /*     var searkeyword = data.keyword;
            if (searkeyword.length > 0) {
                $('#eventscount').html(eventtotal);
                $('#no-events').css('display', 'block');
            } else {
                $('#no-events').css('display', 'none');
            }*/
            var edata = result.response.OrganizationEventsData;
            var edataarray = $.map(edata, function (val, index) {
                return [val];
            });
            $.each(edataarray, function (k, value) {
                eventsData = buildEventHtml(site_url,value);
                $('#upcoming_past_events').append(eventsData);
            });
        }else{
        	$('#no-events').show();
        }
        if ($('#upcoming_past_events').html() != '') {
            //$('#searchkeyword').html('');
            var pageCount = parseInt($('#page').val()) + 1;
            $('#page').val(pageCount);
            if ($('.thumbBlock').length >= result.response.totalcount) {
                $('#viewMore').hide();
            } else {
                $('#viewMore').show();
            }
        }
    },
            callbackFailure = function (result) {
                if (result.readyState == 4 && result.responseJSON.response.total == 0) {
                    $('#upcoming-eventview').html('');
                    $('#eventscount').html('no ');
                    $('#no-events').css('display', 'block');
                    $('#viewMore').hide();
                    eventtotal = result.responseJSON.response.totalcount;

                }

            };
    getPagesearchResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);

}

$(document).ready(function () {
    $('#viewMore').click(function (e) {
    	var postop = $(this).offset().top;
        $(this).html('Loading...');
        var data = {};
        data.page = $('#page').val();
        data.callType = 'ajax';
        data.type=$("#pageType").val();
        getEvents(data);
        $('html,body').animate({
            scrollTop: postop
        }, 800);
        $(this).html('View More');
    });
});

var currentRequest = null;

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
            $('#dvLoading').hide();
        },
        error: function (response) {
            callbackFailure(response);
            $('#dvLoading').hide();
        }
    });
}

