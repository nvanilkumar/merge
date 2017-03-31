/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$('.dropdown-inverse li > a').click(function (e) {
    var selText = $(this).text();
    $(this).parents('.btn-group').find('.eventsClass').text(selText);

});
$('.countryList li > a').click(function (e) {
    $('.status').html(this.innerHTML);

});
if($('.totalCount').text()==0){
    $('.eventsCat').css('display','none');
}else{
    $('.eventsCat').css('display','block');
}
$('.cityList li > a').click(function (e) {
    $('.filterdiv').slideUp();
    $('.CloseFilter').click();

});
$('.categoryList li > a').click(function (e) {
    if ($(this).attr("aria-expanded") === "false") {
        //do nothig
    } else {
        $('.filterdiv').slideUp();
        $('.CloseFilter').click();
        $('.categoryChange').html($(this).find('label').contents().get(0).nodeValue);
        $('.icon-downArrowH').show();
    }
});


$('.timeList li > a').click(function (e) {
    $('.filterdiv').slideUp();
    $('.CloseFilter').click();
    
});


$('.tags span').click(function () {
    $(this).parent().hide();
});

document.querySelector("#nav-toggle")
        .addEventListener("click", function () {
            this.classList.toggle("active");
        });

document.querySelector("#nav-toggle2").addEventListener("click", function () {
    this.classList.toggle("active");

    if ($('#locationContainer').is(':visible')) {
        $("#locationContainer").hide();
    }
    $('.navbar-collapse').show();
});


$('#saveEvent').click(function (e) {
    e.preventDefault();
    $('#myModal').modal();


});

$('.forgetpwd').click(function () {
    $('.forgetForm ').slideToggle();
    $('.form1').hide();
});
$('.successEvent').click(function (e) {
    e.preventDefault();
    $('#myModal2').modal();
});
$('#closeModal').click(function (e) {
    $('#myModal').modal('hide');
});
$('#okId').click(function (e) {
    $('#myModal2').modal('hide');
});

function customCheckbox(checkboxName) {
    var checkBox = $('input[name="' + checkboxName + '"]');
    $(checkBox).each(function () {
        $(this).wrap("<span class='custom-checkbox'></span>");
        if ($(this).is(':checked')) {
            $(this).parent().addClass("selected");
        }
    });
    $(checkBox).click(function () {
        $(this).parent().toggleClass("selected");
    });
}

$(function () {

 

    var counter = 0;
    
    //To load the blog content (global :api_path)
//    loadBlogList(api_path);
    
 

   
    $('#returnToTop').click(function () {

        $('html,body').animate({
            scrollTop: 0
        }, 1000);
      //  $("#locationContainer").hide();
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

        if ($("#locationContainer").is(':hidden')) {
            $("#locationContainer").slideDown('2500');
            $("#locationContainer").css('position', 'fixed');
            $("#locationContainer").css('background', '#f5f6f7');


        } else {
            $("#locationContainer").hide();
            $("#locationContainer").css('position', 'relative');
            $('body').css({overflow: 'auto'});

        }

    });

    function onwindowscroll() {
        //console.log('scrolled:' + $(document).scrollTop());


        var top = $(document).scrollTop();
        if ($(window).width() > 767) {

            if (top < 70 && $('#nav-toggle').hasClass('active')) {
                $('#nav-toggle').trigger("click").removeClass('active');
            }
            if (top >= 100) {
                $('.searchABC').hide();
                $("#locationContainer").show();

            } else {
                $('.searchABC').show();
            }
            if (top >= 450) {
               // $("#locationContainer").hide(); //Hide for enable scrolling in mobie view by dilip
            $(".showSubCategories").html("Show Sub Categories");
            $("#showSubCategoriesAnchor").html("Show Sub Categories");
                //$('.filterdiv').hide();
                $('.searchABC').hide();
            } else {
               // $("#locationContainer").show(); //Hide for enable scrolling in mobie view by dilip
                $('.searchABC').show();
            }
        }
        if ($(window).width() <= 767) {
            //$("#locationContainer").hide(); //Hide for enable scrolling in mobie view by dilip
            if (top >= 500) {
                $('.navbar-collapse').hide();
                $('.navbar-collapse').removeClass('collapse in');
            }
        }
    }
    $(window).on('scroll', onwindowscroll);

    customCheckbox("sport[]");
    
    $(".carousel-inner .left").click(function(){
        $("#carousel-example-generic").carousel("prev");
    });
    
    $(".carousel-inner .right").click(function(){
        $("#carousel-example-generic").carousel("next");
    });
});

/*
 * @author Qison Dev Team
 */
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function getMoreEvents(urlTo, countryId, cityId, categoryId, day, type, page, limit) {
    var inputData = '';
            inputData += '?countryId=' + countryId;
            if (cityId != 0)
                inputData += '&cityId=' + cityId;
            if (categoryId != 0)
                inputData += '&categoryId=' + categoryId;
            if (day != 0)
                inputData += '&day=' + day;
            if (type != 0)
                inputData += '&type=' + type;
            inputData += '&page=' + page + '&limit=' + limit;
    $.ajax({
         url: urlTo+'event/list'+inputData,
         type: 'GET',
        // data: inputData, //Should come Dynamically
         headers: {"Authorization"  : "bearer 930332c8a6bf5f0850bd49c1627ced2092631250"}, //Should come Dynamically
         success: function(data) {
               //called when successful
               
               html = renderEvents(data);
               $("ul#eventThumbs").append(html);
               
               if (data.response.nextPage === false) {
                    // hiding the view more button and showing no more events text
                    $('#viewMoreEvents').hide();
                    $('#noMoreEvents').show();
               }
         },
         error: function(e) {
               //called when there is an error
               console.log(e.message);
         }
    });
    return false;
}
function renderEvents(data) {
    var html = '';
    var totalEvents = data.response.events.length;
    for (var i=0;i<totalEvents; i++)
    {
        var event = data.response.events[i];
        html += '<li class="col-xs-12 col-sm-6 col-md-4 col-lg-4 thumbBlock">';
        html += '<a href="'+event.eventUrl+'" class="thumbnail">';
        html += '<div class="eventImg">';
        html += '<img src="'+event.thumbImage+'" width="" height="" alt="events img" />';
        html += '</div><h6>';
        html += '<span class="eveHeadWrap">'+event.title+'</span>'; /*<span id="saveEvent" class="icon-fave"></span>';*/
        html += '</h6><div class="info">';
        html += '<span>'+event.startDate+'</span> <span>at</span> <span>'+event.venue+'</span>';
        html += '</div><div class="overlay"><div class="overlayButt"><div class="overlaySocial">';
        html += '<span class="icon-fb"></span> <span class="icon-tweet"></span>';
        html += '<span class="icon-google"></span></div></div></div>';
        html += '</a> <a href="'+event.eventUrl+'" class="category">'; 
        html += '<span class="icon-'+event.categoryName.toLowerCase().replace(" ", "")+' col'+event.categoryName.toLowerCase().replace(" ", "")+'"></span>';
        html += '<span class="catName"><em>'+event.categoryName+'</em></span> </a> </li>';
    }
    return html;
}

/*
 * End of Qison Dev code
 */

//To load the blog content 
/*function loadBlogList(api_path){
    url = api_blogBloglist;
    $.ajax({
        method: 'GET',
        url: url
    }).success(function (data) {
        $("#ajaxBlogData").html(data);

    }).error(function (data) {
        //alert('Something went wrong please try again');
    });
}*/