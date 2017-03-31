

	 
	$('.showdropdowntop').click(	function(e){ $(this).addClass('active'); $(this).find('.dropdowntop').slideToggle();  }
        
     );
	
			$('#showdropdowntwo').click(function(e){
 
			     $(this).find('.dropdowntwo').slideToggle();
				 e.stopPropagation();
			});
 $('.navigation > ul > li:nth-child(4)').click(function(){
	  var view =$('.navigation > ul > li:nth-child(3) > ul').css('display');

	     if(view == 'block'){
			 $('.navigation > ul > li:nth-child(3)').click();
		 }
	  // $('.navigation > ul > li:nth-child(3)').click();
 });
$('.view').click(function(){
 
			     $('.viewDetail').slideToggle();
				
			});
			$('.otherdetail').click(function(){
 
			     $('.viewotherdetail').slideToggle();
				
			});  

	  

 