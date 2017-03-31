
/**
 *	dynamic collapse & it applicable for this scenario
 */
$(document).ready(function () { 
     $(document).scroll(function(){
          $('[data-toggle="dropdown"]').parent().removeClass('open');
     });
     $('.collapse_bt').click(function(){
          $('[data-toggle="dropdown"]').parent().removeClass('open');
     });
    if ($(".filterSearch").length > 0) {
        $(".filterSearch").each(function () {
            $(this).children().find("*").attr("disabled", "disabled");
        });
        $('.resetInput').each(function () {
            $(this).css("pointer-events", "none");
        });
    }
    if ($(".filterScrollSearch").length > 0) {
        $(".filterScrollSearch").each(function () {
            $(this).find("*").attr("disabled", "disabled");
        });
    }
//    $("body").find("a").click(function (e) {
//        e.preventDefault();
//    });
    var time = 300;

    $(".locSearchContainer .btn").click(function () {
        var div = $(".locSearchContainer div" + $(this).attr("href"));
        var parent = $(this).data("parent");
        var su = function () {
            div.slideUp(time);
        }
        var sd = function () {
            div.slideDown(time);
        }

        if (parent == undefined) {
            if (div.is(":visible")) {
                console.log(222);
                su();
            } else {
                console.log(11111);
                var visible = $(".locSearchContainer div.filterdiv:visible");
                if (visible.length > 0) {
                    if ($(".showSubCategories").html() == 'View Less' || $("#showSubCategoriesAnchor").html() == 'View Less') {
                        $(".showSubCategories").html("Show Sub Categories");
                        $("#showSubCategoriesAnchor").html("Show Sub Categories");
                    }
                    visible.slideUp(time, function () {
                        sd();
                    });
                } else {
                    sd();
                }
            }
        } else {
            if (div.is(":visible")) {
                su();
                if (!$(this).parent().hasClass('searchresult')) {
                    $(this).text('Show Sub Categories');
                    $(this).parent().parent().parent("div.accTextCont").css("border-bottom", "1px solid #EBEBEB");
                }

            } else {
                sd();
                if (!$(this).parent().hasClass('searchresult')) {
                    $(this).text('View Less');
                    $(this).parent().parent().parent("div.accTextCont").css("border-bottom", "0");
                }

            }
        }
        return false;
    });
    $('input.search').attr('placeholder', 'Search by Event Name , Event ID , Key Words');
    $('#saveEvent,#saveEvent2').click(function (e) {
        e.preventDefault();
        $('#myModal').modal();
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
    $('input[type=checkbox]').change(function () {
        $(this).prop("checked") ? $('.pwd').slideDown() : $('.pwd').slideUp();
    });
    //$('li.dropdown.open > ul li a').attr('href','Faq.html');
//	$('li.dropdown.open > ul li:first-child a').attr('href','print_ticket.html');

    $(".CloseFilter").click(function () {
        if ($(window).width() <= 768) {
            $("#locationContainer").slideUp('2500');
            $('html').css('overflow', 'scroll');
            $('.filterdiv ').slideUp('slow');
        }
    });
    /*Add Adress*/
    $('.addAdd').click(function () {
        $('.add_address').toggle('fast');
        if ($('.addAdd').html() == '+') {
            $('.addAdd').html('-');
        } else {
            $('.addAdd').html('+');
        }
    });
    /*Add Adress*/
    $(document).on('click', '.addTaxes', function () {
        var change = $(this).attr('data_value');
        $('.add_taxes_' + change).toggle('fast');
    });
    $('.pickbtn').click(function () {
        $('.theme_images').slideDown();
        $('.donebtn').show();
    });
    $('.donebtn').click(function () {
        $(this).hide();
        $('.theme_images').slideUp();
    });
    $(window).on('scroll', function () {

        var top = $(document).scrollTop();
        if (top < 70 && $('#nav-toggle').hasClass('active')) {
            $('#nav-toggle').trigger("click").removeClass('active');
        }
    });
    //$(' div.onScrollContainer  div.navbar-collapse.collapse > ul > li > ul li:nth-child(1) a').attr('href','Faq.html');
    //$(' div.onScrollContainer  div.navbar-collapse.collapse > ul > li > ul li:nth-child(2) a').attr('href','login.html');
    //$(' div.onScrollContainer  div.navbar-collapse.collapse > ul > li > ul li:nth-child(3) a').attr('href','create_event.html');
    $(".footerCategorySearch").click(function (e) {
        e.preventDefault();
        var href = $(this).attr("href");
        var input = 'cookieName[]=categoryId&cookieValue[]=' + this.id + '&footerCategory=true';
        var pageUrl = api_commonrequestsUpdateCookie;
        var method = 'POST';
        var dataFormat = 'JSON';
        getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
        function callbackFailure(result) {
            alert(result.response.messages);
        }
        function callbackSuccess(result) {
            window.location = href;
        }
    });
});
$(window).on("load", function () {
    if ($(".filterSearch").length > 0) {
        $(".filterSearch").each(function () {
            $(this).children().find("*").removeAttr("disabled");
        });
        $('.resetInput').each(function () {
            $(this).css("pointer-events", "auto");
        });
    }
    if ($(".filterScrollSearch").length > 0) {
        $(".filterScrollSearch").each(function () {
            $(this).find("*").removeAttr("disabled");
        });
    }
});
window.onbeforeunload = function () {
    if ($(".filterSearch").length > 0) {
        $(".filterSearch").each(function () {
            $(this).children().find("*").attr("disabled", "disabled");
        });
        $('.resetInput').each(function () {
            $(this).css("pointer-events", "none");
        });
    }
    if ($(".filterScrollSearch").length > 0) {
        $(".filterScrollSearch").each(function () {
            $(this).find("*").attr("disabled", "disabled");
        });
    }
};
function eventsHappeningRedirect(link, e) {
    var input = 'cookieName[]=categoryId&cookieValue[]=' + e.id;
    var subcatid = 'cookieName[]=subCategoryId&cookieValue[]=';
    var subcatname = 'cookieName[]=subCategoryName&cookieValue[]=';
    var pageUrl = api_commonrequestsUpdateCookie;
    var method = 'POST';
    var dataFormat = 'JSON';
    getPageResponse(pageUrl, method, subcatid, dataFormat, '', '');
    getPageResponse(pageUrl, method, subcatname, dataFormat, '', '');
    getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    function callbackFailure(result) {
        alert(result.response.messages);
    }
    function callbackSuccess(result) {
        window.location = link;
    }
}

function eventsHappeningSubcategoryRedirect(link, e) {
    var catid = $(e).attr('catid');
    var subcatname = $(e).attr('subcatname');
    var key = e.id.split('-');
    var input = 'cookieName[]=subCategoryId&cookieValue[]=' + e.id;
    var catinput = 'cookieName[]=categoryId&cookieValue[]=' + catid;
    var subcatname = 'cookieName[]=subCategoryName&cookieValue[]=' + subcatname;
    var pageUrl = api_commonrequestsUpdateCookie;
    var method = 'POST';
    var dataFormat = 'JSON';
    getPageResponse(pageUrl, method, catinput, dataFormat, '', '');
    getPageResponse(pageUrl, method, subcatname, dataFormat, '', '');
    getPageResponse(pageUrl, method, input, dataFormat, callbackSuccess, callbackFailure);
    function callbackFailure(result) {
        alert(result.response.messages);
    }
    function callbackSuccess(result) {
        window.location = link;
    }
}
