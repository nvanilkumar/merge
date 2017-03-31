var serviceCharge = $('#serviceCharge').val();
var gatewayFee = $('#gatewayFee').val();
var serviceTax = $('#serviceTax').val();
var charges = (parseFloat(gatewayFee) + parseFloat(serviceCharge)).toFixed(2);
var deductedAmnt = parseFloat(charges) + parseFloat((serviceTax * (charges) / 100).toFixed(2));
$('.dropdown-inverse li > a').click(function(e){
    $('.status').html(this.innerHTML);
});
$('.ticketAmount').focus();
$('#Caluclate').click(function(){
    var orginalAmnt= $('.ticketAmount').val();
    if(orginalAmnt == ""){
        $('.error').html("Please enter amount");
    }else if(isNaN(orginalAmnt)){
         $('.error').html("Please enter only digits");
    }else{
        $('.error').html("");
        var changedAmt = parseFloat(orginalAmnt);
        var taxamount = (deductedAmnt * changedAmt / 100).toFixed(2);
        changedAmt = (changedAmt - taxamount).toFixed(2);
        $('.ticketAmount').val("₹" + changedAmt).addClass('ticketAmountChange');
    }
});
    
$('.showmenu').click(function(e) {
    //e.preventDefault();
    $(this).find('.menu').slideToggle("fast");

});

$('.selectedPlan').hover(function(){
    var bg=$(this).css('color');
    $(this).css('background',bg).css('color','white').text('Yes,i am interested');
},
function(){
    var colr=$(this).css('background');
    $(this).css('background','white').css('color',colr).text('Take this plan');
}
);
$('#checkRequest').click(function(){
    $('.submitrquest').slideUp();
    $('.checkExist').slideDown();

});

$('.submitRequest').click(function(){
    $('.checkExist,.demoReqest').slideUp();
    $('.submitrquest').slideDown();

});
$('.viewRequest').click(function () {
    $('.submitrquest,.checkExist').slideUp();
    $('.demoReqest').slideDown();
});
$('div.locSearchContainer').hide();
var counter = 0;
$( "#datepicker" ).datepicker();
$( "#datepicker2" ).datepicker();
$("#viewMoreEvents").click(function(){
    counter++;
    $(".eventThumbs:first").clone().insertAfter(".eventThumbs:last").hide();
    $(".eventThumbs:last").fadeIn(500);
    if(counter >= 2 ){
				$(this).hide();
				$('#noMoreEvents').show();
			}
    return false;
});
$("#viewMoreCat").click(function(){
    console.log($(this).attr("aria-expanded"));
    if($(this).attr("aria-expanded") === "false")
        $(this).text("View Less");
    else
        $(this).text("View More");
});
	
$("#settingsBtn").click(function() {
    //$("html, body").animate({scrollTop:0},500)
    if($("#locationContainer").is(':hidden')) {
        $("#locationContainer").show();
        $("#locationContainer").css('position' , 'fixed');
        $("#locationContainer").css('background' , '#f5f6f7');
        //$(window).off('scroll', onwindowscroll)
        $('body').css({
            overflow: 'hidden'
        }); 

    } else {
        $("#locationContainer").hide();
        $("#locationContainer").css('position' , 'relative');
        $('body').css({
            overflow: ''
        });
    //$(window).on('scroll', onwindowscroll)
    }
   });
   
   $('.countryList li > a').click(function(e){
        $('.status').html(this.innerHTML);
	
    });
    $('.categoryList li > a').click(function(e){

        if($(this).attr("aria-expanded") === "false"){
        //do nothig
        }else{
            $('.filterdiv').slideUp();
            //var categoryName =$(this).find('label').text().split(" ");
            var catefori=$(this).find('label').contents().get(0).nodeValue;
            $('.categories').html(catefori).append('<span class="icon-downArrowH"></span>').prepend('<span class="icon_cat hidden-lg hidden-md"></span>');
            $('.filterCat ').show().contents().last()[0].textContent=catefori;
            $('.CloseFilter').click();
            $('.categoryChange').html($(this).find('label').contents().get(0).nodeValue);
            $('.icon-downArrowH').show();
        //$("#locationContainer").removeClass('mobileFilter').hide();
        }
    });


    $('.timeList li > a').click(function(e){
        $('.filterdiv').slideUp();
        //var time =$(this).find('label').text().split(" ");
        var time=$(this).find('label').contents().get(0).nodeValue;
        $('.time').html(time).append('<span class="icon-downArrowH"></span>').prepend('<span class="icon_date hidden-lg hidden-md"></span>');
        $('.filterDate').show().contents().last()[0].textContent=time;
        $('.CloseFilter').click();
    //$("#locationContainer").removeClass('mobileFilter').hide();
    });
    var citiesList = [
    "Agra",
    "Bombay",
    "Bihar",
    "Ahemdabad",
    "Delhi",
    "Punjab"
    ];
    $('#searchId').autocomplete({
        source : citiesList
    });
    var eventsList = [
    "Food Events",
    "Music Events",
    "Dance Events",
    "Kids Events",
    "Professional Events",
    "Filmy Events"
    ];
    $('#searchId').autocomplete({
        source : eventsList
    });
          
    $('#resetInput').click(function(){
        $('.city').text('Hyderabad  ').append('<span class="icon-downArrowH"></span>');
        $('.categories').text('All  ').append('<span class="icon-downArrowH"></span>');
        $('.time').text('Today  ').append('<span class="icon-downArrowH"></span>');
    });

    $('.tags span').click(function(){
        $(this).parent().hide();
    });      
    
function onwindowscroll() {
    console.log('scrolled:'+$(document).scrollTop() );
    var top = $(document).scrollTop();
    if(top >= 180) {
        $("#locationContainer").hide();
        $("#settingsBtn").show();
    } else {
        $("#settingsBtn").hide();
        $("#locationContainer").show();
    }
}
if ($('#pricetab').val() == 1) {
    $('li a[href="#pricing"]').tab('show');
}