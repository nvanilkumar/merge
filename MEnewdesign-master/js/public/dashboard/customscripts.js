
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
    
    //applying using class name
    function customClassCheckbox(className){
        var checkBox = $('.'+className);
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
        customCheckbox("gateways[]");//payemnt gateway options
        customCheckbox("ticketIds[]");//edit editOfflinePromoter
        customCheckbox("displayamountonticket"); 
        customCheckbox("limitsingletickettype"); 
        customCheckbox("sendubermails"); 
        customCheckbox("incomplete"); 
        customCheckbox("dailytransaction"); 
        customCheckbox("dailysuccesstransaction");
        customCheckbox("ticketregistration"); 
        customCheckbox("ebs"); 
        customCheckbox("cashOnDelivery"); 
        customCheckbox("paypal");
        customCheckbox("mobikwik"); 
        customCheckbox("paytm"); 
        customCheckbox("cheque"); 
               
        customClassCheckbox("enableDisable");
        // Calling customRadio function
        customRadio("browser");
        customRadio("amountType");
        customRadio("collectmultipleattendeeinfo");
        customRadio("accountType");
        customRadio("access_level");
       
    });


 

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
    function customClassRadio(radioName){
        var radioButton = $('.'+radioName);
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
    

	
	$(function() {
  $("input[type='file'].unused");
});

	$(function() {
  $("input[type='file'].unused2");
});
			var randomScalingFactor = function(){ return Math.round(Math.random()*2000)};
		var lineChartData = {
			labels : ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],
			datasets : [
			
				{
					label: "MyDashoard",
					fillColor : "rgba(210,116,106,0.5)",
					strokeColor : "rgba(182,62,48,1)",
					pointColor : "rgba(246,246,254,1)",
					pointStrokeColor : "#b63e30",
					pointHighlightFill : "#b63e30",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
				}
			]

		}

	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, {
			responsive: true
		});
	}
