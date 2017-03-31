     function customCheckbox(checkboxName){
        var checkBox = $('input[name="'+ checkboxName +'"]');
        $(checkBox).each(function(){
            $(this).wrap( "<span class='custom-checkbox'></span>" );
            if($(this).is(':checked')){
                $(this).parent().addClass("selected");
            }
        });
        $(checkBox).click(function(){
            $(this).parent().toggleClass("selected");
        });
    }

    $(document).ready(function (){
        customCheckbox("sport[]");
       
    })

    function customRadio(radioName){
        var radioButton = $('input[name="'+ radioName +'"]');
        $(radioButton).each(function(){
            $(this).wrap( "<span class='custom-radio'></span>" );
            if($(this).is(':checked')){
                $(this).parent().addClass("selected");
            }
        });
        $(radioButton).click(function(){
            if($(this).is(':checked')){
                $(this).parent().addClass("selected");
            }
            $(radioButton).not(this).each(function(){
                $(this).parent().removeClass("selected");
            });
        });
    }
    // Calling customRadio function
    $(document).ready(function (){
        customRadio("browser");
    })
	
	$(function() {
  $("input[type='file'].unused");
});

	$(function() {
  $("input[type='file'].unused2");
});
	
//
//  $(function() {
//    $( "#user-toggle" ).click(function() {
//		 
//      $( "#dropdown-menu" ).addClass( "newClass" );
//	  $( "#dropdown-menu" ).toggle( "none" );
//    });
//     $( "#help-toggle" ).click(function() {
//		//alert("hi");
//      $( "#helpdropdown-menu" ).addClass( "newClass" );
//	  $( "#helpdropdown-menu" ).toggle( "none" );
//    });
//    $( "#selCountry-toggle" ).click(function() {
//		//alert("hi");
//      $( "#selCountry-menu" ).addClass( "newClass" );
//	  $( "#selCountry-menu" ).toggle( "none" );
//    });
//  });

