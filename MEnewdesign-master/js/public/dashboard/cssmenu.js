$(window).load(function() {

//$('#currentMenu').addClass("");
//});
//$(document).ready(function(){
//
//$('#cssmenu > ul > li ul').each(function(index, e){
//  var count = $(e).find('li').length;
//  var content = '';
//  $(e).closest('li').children('a').append(content);
//});
//$('#cssmenu ul ul li:odd').addClass('odd');
//$('#cssmenu ul ul li:even').addClass('even');
//$('#cssmenu > ul > li > a').click(function() {
//  $('#cssmenu li').removeClass('active');
//  $(this).closest('li').addClass('active');	
//  var checkElement = $(this).next();
//  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
//    $(this).closest('li').removeClass('active');
//    checkElement.slideUp('normal');
//  } 
//  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
//    $('#cssmenu ul ul:visible').slideUp('normal');
//    checkElement.slideDown('normal');
//  }
//  if($(this).closest('li').find('ul').children().length == 0) {
//    return true;
//  } else {
//    return false;	
//  }		
//});




$("#settings").click(function(){

	$("#settingsOpctions").toggleClass("settings");
});


$('a').click(function(){ 
$('.currentMenu').removeClass("currentMenu");
});
// fadw out viral ticket messages
 $(function() {
   $('#viralTicketMessage').delay(5000).fadeOut(400)
}); // <-- time in milliseconds
// fade out bank detals messag

    });
