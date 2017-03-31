// Browser detection for when you get desparate. A measure of last resort.
// http://rog.ie/post/9089341529/html5boilerplatejs

// var b = document.documentElement;
// b.setAttribute('data-useragent',  navigator.userAgent);
// b.setAttribute('data-platform', navigator.platform);

// sample CSS: html[data-useragent*='Chrome/13.0'] { ... }


// remap jQuery to $
(function($){


var galNr = 1,
	picNr = 1,
	vidNr = 1;

function coverFunc(){
	if(document.documentElement.clientWidth <= 459){
		$("#covers li").css({"width":document.documentElement.clientWidth -30, "margin-left":"30px", "margin-right": "0"})
		$("#covers").css({"width":$("#covers li").width() * ($("#covers li").length + 1), "padding-left": 0});
	} else{
		$("#covers li").css({			
			"float":"left",
			"font-size":"0",
			"width":"31%",
			"margin-right":"2.3%",
			"margin-left":"0"
		})
		$("#covers").css({"width":"100%", "padding-left": "2.3%"});
		$("#covers").css({"margin-left":0});
		galNr = 1;
		$("#music .gallery-no").text($("#music .gallery-no").text().replace($("#music .gallery-no").text().substring(0, $("#music .gallery-no").text().indexOf("/")),"1"));
	}
	$("#covers_container, #covers").css({"height":$("#covers li").outerHeight()});
}

function slide(obj){ //sliderul
	if(obj.parents("#live").length > 0){ //slider de jos
		var index = obj.parent().index();
		$("#events").stop().animate({"margin-left": - $("#events li").outerWidth() * index});
		$(obj).parent().parent().find(".current_active_nav_item").removeClass("current_active_nav_item");
		$(obj).addClass("current_active_nav_item");
	} else{ //slider de sus
		var index = obj.parent().index();
		$("#covers").stop().animate({"margin-top": - $("#covers li").outerWidth() * index});
		$(obj).parent().parent().find(".current_active_nav_item").removeClass("current_active_nav_item");
		$(obj).addClass("current_active_nav_item");
	}
}

function initializeCounter(obj){ //functia de initializa de numar de elemente pentru sliderele cu sageti
	var number = obj.children("li").length;
	if(obj.parents("#music").length > 0){
		obj.parents("#music").find(".gallery-no").text("1/"+number);
	}
	if(obj.parents("#photos").length > 0){
		obj.parents("#photos").find(".gallery-no").text("1/"+number);
	}
	if(obj.parents("#videos").length > 0){
		obj.parents("#videos").find(".gallery-no").text("1/"+number);
	}
}

function arrow_gallery_nav(obj){ //animatia pentru slidere cu sageti
	if(obj.parents("#gallery-nav-mobile").length > 0){
		var currentPos = parseInt($("#music .gallery-no").text().substring(0, $("#music .gallery-no").text().indexOf("/"))),
			dist = parseInt($("#covers").css("margin-left")) + $("#covers li").outerWidth() + 30;
		if(obj.hasClass("prev-gallery")){ 
			if( currentPos > 1){
				$("#covers").stop().animate({"margin-left": dist });
				galNr--;
				tempStr = $("#music .gallery-no").text().substring(0, $("#music .gallery-no").text().indexOf("/"));
				$("#music .gallery-no").text($("#music .gallery-no").text().replace(tempStr, galNr));
			}
		} else{
			if( currentPos < parseInt(obj.parents("#music").find("#covers li").length)){ 				

				$("#covers").stop().animate({"margin-left": - ($("#covers li").outerWidth()+30) * galNr});
				galNr++;
				tempStr = $("#music .gallery-no").text().substring(0, $("#music .gallery-no").text().indexOf("/"));
				$("#music .gallery-no").text($("#music .gallery-no").text().replace(tempStr, galNr));
			}
		}
	}
	if(obj.parents("#photos").length > 0){
		var currentPos = parseInt($("#photos .gallery-no").text().substring(0, $("#photos .gallery-no").text().indexOf("/"))),
		dist = parseInt($("#photos > ul:first-child").css("margin-left")) + $("#photos > ul:first-child li").outerWidth();
		if(obj.hasClass("prev-gallery")){ 
			if( currentPos > 1){
				$("#photos > ul:first-child").stop().animate({"margin-left": dist });
				picNr--;
				tempStr = $("#photos .gallery-no").text().substring(0, $("#photos .gallery-no").text().indexOf("/"));
				$("#photos .gallery-no").text($("#photos .gallery-no").text().replace(tempStr, picNr));
			}
		} else{
			if( currentPos < parseInt(obj.parents("#photos").find("ul:first-child li").length)){ 				

				$("#photos > ul:first-child").stop().animate({"margin-left": - ($("#photos > ul:first-child li").outerWidth()) * picNr});
				picNr++;
				tempStr = $("#photos .gallery-no").text().substring(0, $("#photos .gallery-no").text().indexOf("/"));
				$("#photos .gallery-no").text($("#photos .gallery-no").text().replace(tempStr, picNr));
			}
		}
	}
	if(obj.parents("#videos").length > 0){
		var currentPos = parseInt($("#videos .gallery-no").text().substring(0, $("#videos .gallery-no").text().indexOf("/"))),
		dist = parseInt($("#videos > ul:first-child").css("margin-left")) + $("#videos > ul:first-child li").outerWidth();
		if(obj.hasClass("prev-gallery")){ 
			if( currentPos > 1){
				$("#videos > ul:first-child").stop().animate({"margin-left": dist });
				vidNr--;
				tempStr = $("#videos .gallery-no").text().substring(0, $("#videos .gallery-no").text().indexOf("/"));
				$("#videos .gallery-no").text($("#videos .gallery-no").text().replace(tempStr, vidNr));
			}
		} else{
			if( currentPos < parseInt(obj.parents("#videos").find("ul:first-child li").length)){ 				

				$("#videos > ul:first-child").stop().animate({"margin-left": - ($("#videos > ul:first-child li").outerWidth()) * vidNr});
				vidNr++;
				tempStr = $("#videos .gallery-no").text().substring(0, $("#videos .gallery-no").text().indexOf("/"));
				$("#videos .gallery-no").text($("#videos .gallery-no").text().replace(tempStr, vidNr));
			}
		}
	}
}


function generateNavigation(obj, numberOf){
//alert(numberOf);
	var bNum = Math.floor(obj.children("li").length / numberOf);
	if(obj.children("li").length % numberOf != 0) bNum++;
	for(i=0 ; i<bNum ; i++){
		obj.parents(".col").siblings(".section-nav").find("ul").append("<li><a href='#' class='navigationAnchor'></a></li>");
	}
	obj.parents(".col").siblings(".section-nav").find("ul li:first-child").find("a").addClass("current_active_nav_item");
}

function eventsResizeList(){
	var	i = $("#events").find(".current_active_nav_item").parent().index(),
		n = $("#events li").parent().parent().width(),
		j = $("#music").find(".current_active_nav_item").parent().index(),
		m = $("#covers li").outerHeight(),
		picW = $("#photos > ul:first-child li").parent().parent().width(), 
		vidW = $("#videos").width();

	$("#events li").css({"width" : n });
	$("#photos > ul:first-child li").css({"width":picW});
	$("#videos> ul:first-child li").css({"width":vidW});

	var m = $("#events li").outerWidth() * ($("#events li").length + 2),
		picUlW = $("#photos > ul:first-child li").outerWidth() * ($("#photos > ul:first-child li").length + 2),
		vidUlW = $("#videos > ul:first-child li").outerWidth() * ($("#videos > ul:first-child li").length + 2);

	$("#events").css({"width": m, "margin-left": - (i+1) * n});
	$("#photos > ul:first-child").css({"width":picUlW});
	$("#videos > ul:first-child").css({"width":vidUlW});
	$("#covers").stop().animate({"margin-top": - j * m});
}

/* trigger when page is ready */
$(document).ready(function (){

	$(document).keyup(function(e){
		if($("#ajax-window").css("display") == "block"){
			if(e.keyCode == 27){
				$("#ajax-window").fadeOut(function(){
					$(this).find("#popup").remove();
				});
			}
		}
	});

	$(".prev-gallery").click(function(e){
		e.preventDefault();
		arrow_gallery_nav($(this));
	});
	$(".next-gallery").click(function(e){
		e.preventDefault();
		arrow_gallery_nav($(this));
	});

	$('body').on("click","a.navigationAnchor",function(e){

		slide($(this));

		e.preventDefault();
	});

	var offsets = 0;
	function generateListOfElements(){
		var l = $("nav ul li").length,			
			list = new Array();
			
		$("nav ul li a").each(function(index, element) {
			i = $(this).parent().index();
			if(i !== (l-0)){
				if(i !== (l-0)){
					var temp = "#"+$(this).text().toLowerCase();
					list.push($(temp).offset().top-50);
				}
			}
		});
		return list;
	}

	eventsResizeList();

	/* Align the section titles vertically */
	$.fn.vAlign = function() {
		return this.each(function(i){
			var ah = $(this).height();
			var ph = $(this).parent().height();        
			var mh = Math.ceil((ph-ah) / 2);
			$(this).css('margin-top', mh);
		});
	};

	$(window).load(function(){
	 
		function eventsResizeList(){
			var	i = $("#events").find(".current_active_nav_item").parent().index(),
				n = $("#events li").parent().parent().width(),
				j = $("#music").find(".current_active_nav_item").parent().index(),
				m = $("#covers li").outerHeight(),
				picW = $("#photos > ul:first-child li").parent().parent().width(), 
				vidW = $("#videos").width();

			$("#events li").css({"width" : n });
			$("#photos > ul:first-child li").css({"width":picW});
			$("#videos> ul:first-child li").css({"width":vidW});

			var m = $("#events li").outerWidth() * ($("#events li").length + 2),
				picUlW = $("#photos > ul:first-child li").outerWidth() * ($("#photos > ul:first-child li").length + 2),
				vidUlW = $("#videos > ul:first-child li").outerWidth() * ($("#videos > ul:first-child li").length + 2);

			$("#events").css({"width": m, "margin-left": - (i+1) * n});
			$("#photos > ul:first-child").css({"width":picUlW});
			$("#videos > ul:first-child").css({"width":vidUlW});
			$("#covers").stop().animate({"margin-top": - j * m});
		} 
	
		//COVERS
		coverFunc();
		generateNavigation($("#covers"), 6);
		generateNavigation($("#events"), 1);
		initializeCounter($("#covers"));
		initializeCounter($("#photos > ul:first-child"));
		initializeCounter($("#videos > ul:first-child"));
		eventsResizeList();

		//eventsResizeList();

		if(document.documentElement.clientWidth >= 767){
			$('.section-title').vAlign();
			$('.section-nav').vAlign();
		}
		
		function manualValidate(ev) {
			ev.target.checkValidity();				
				var email = $("#contactEmail").val(),
					name = $("#contactName").val(),
					message = $("#contactMessage").val(),
					tel = $("#contactPhone").val(),				
					that  = $("#contactForm");
					
				var	url = that.attr("action"),
					type = that.attr("method"),
					data = {};
					
				data['email'] = email;
				data['name'] = name;
				data['message'] = message;
				data['tel'] = tel;
				
				if($("#faliure").length > 0){
					$("#faliure").remove();
				}
				if($("#success").length > 0){
					$("#success").remove();
				}
				
				$.ajax({
					url: url,
					type: type,
					data: data,
					success: function(response){
						response = JSON.parse(response);
						if(response.status){
							$("#contactFormContainer").append("<div id='success'>"+response.msg+"</div>");
							document.getElementById("contactForm").reset();
							$("#success").fadeIn(500,function(){
								setTimeout(function(){
									$("#success").fadeOut(500,function(){
										$("#success").remove();
									});
								},3000);
							});							
						} else{
							$("#contactFormContainer").append("<div id='faliure'>"+response.msg+"</div>");
							$("#faliure").fadeIn(500);
						}
						console.log(data);
					}
				});
				
				//console.log(email, name, message, tel);			
				return false;
			}
			$("#contactForm").bind("submit", manualValidate);
		});		
	
	$(window).on("resize",function(){

		eventsResizeList();

		$("#slider ul").height($("#slider ul li").height());		
		if(document.documentElement.clientWidth >= 767){//
			$('.section-title').vAlign();
			$('.section-nav').vAlign();
		} else {
			$('.section-title').css("margin-top",0);
			$('.section-nav').css("margin-top",20);
		}

		/*covers*/
		coverFunc();
		
	});	

	/* Toggle contact*/
	$("#contact-tab").on("click",function(e){
		if($("#share_toggle").css("display")!=="none"){
			$("#share_toggle").slideToggle(600,"easeOutQuint",function(){
				$("#share-tab").removeClass("tab_active");
				if($("#contact_toggle").css("display") == "none" ){
					$("#contact-tab").addClass("tab_active");
				}
				$("#contact_toggle").stop().slideToggle(600,"easeOutQuint",function(){
					if($("#contact_toggle").css("display") == "none"){
						$("#contact-tab").removeClass("tab_active");
					};
				});
			});
		} else{
			if($("#contact_toggle").css("display") == "none" ){
					$("#contact-tab").addClass("tab_active");
				}				
			$("#contact_toggle").stop().slideToggle(600,"easeOutQuint",function(){
				 if($("#contact_toggle").css("display") == "none"){
					$("#contact-tab").removeClass("tab_active");
				 }
			});
		}
		e.preventDefault();	
	});
	
	/* Toggle share*/
	$("#share-tab").on("click",function(e){
		if($("#contact_toggle").css("display")!=="none"){
			$("#contact_toggle").slideToggle(600,"easeOutQuint",function(){
				$("#contact-tab").removeClass("tab_active");
				if($("#share_toggle").css("display") == "none"){
					$("#share-tab").addClass("tab_active");
				}
				$("#share_toggle").stop().slideToggle(600,"easeOutQuint",function(){
					if($("#share_toggle").css("display") == "none"){
						$("#share_tab").removeClass("tab_active");
					}
				});
			});
		} else{
			if($("#share_toggle").css("display") == "none" ){
				$("#share-tab").addClass("tab_active");
			}		
			$("#share_toggle").stop().slideToggle(600,"easeOutQuint",function(){
				if($("#share_toggle").css("display") == "none"){
					$("#share-tab").removeClass("tab_active");
				 }
			});
		}
		e.preventDefault();
	});
	
	/* Social media buttons fade effect */
	$("#social li").hover(function() {
		$(this)
			.stop().fadeTo(400, 1)
			.siblings().stop().fadeTo(400, 0.2);

	}, function() {
		$(this)
			.stop().fadeTo(400, 1)
			.siblings().stop().fadeTo(400, 1);
	});
	
	/* Split the bio in 3 columns */
	$('.bio-columns').columnize({columns:3, lastNeverTallest: true }) ;
	
	/* Album Covers Hover Effect */
	$(' #covers > li ').each( function() { $(this).hoverdir(); } );
	
	/* Main slider */
	var interval = setInterval(function(){
		if($("#slider ul").height()<=0){
			$("#slider ul").height($("#slider ul li").height());
			offsets = generateListOfElements();
		}
		else{
			clearInterval(interval);
		}
	},100);
		
	$("#slider ul li:first-child").addClass("current_slide");
	$("#next-slide").on("click",function(e){		
		if($("#slider ul").find(".current_slide").next().length){
			$(".current_slide").find(".slider-caption").stop().animate({"bottom":"-50px"},300);
			$(".current_slide").fadeOut(500,function(){
				var next = $(".current_slide").next();
				$(this).removeClass("current_slide");
				$(next).fadeIn(function(){
					$(this).addClass("current_slide");
					$(this).prev("li").find(".slider-caption").css("bottom","0");
				});
			});
		} else{
			$(".current_slide").find(".slider-caption").stop().animate({"bottom":"-50px"},300);
			$(".current_slide").fadeOut(500,function(){
				var first = $("#slider ul li:first-child");
				$(this).removeClass("current_slide");
				$(first).fadeIn(function(){
					$(this).addClass("current_slide");
					$("#slider ul li:last-child").find(".slider-caption").css("bottom","0");
				});
			});			
		}
		e.preventDefault();		
	});
	$("#prev-slide").on("click",function(e){
		if($("#slider ul").find(".current_slide").prev().length){
			$(".current_slide").fadeOut(500,function(){
				var prev = $(".current_slide").prev();
				$(this).removeClass("current_slide");
				$(prev).fadeIn(function(){
					$(this).addClass("current_slide");
					$(this).next().find(".slider-caption").css("bottom","0");
				});
			})
			$(".current_slide .slider-caption").stop().animate({"bottom":"-50px"},300);
		} else{
			$(".current_slide").fadeOut(500,function(){
				var first = $("#slider ul li:last-child");
				$(this).removeClass("current_slide");
				$(first).fadeIn(function(){
					$(this).addClass("current_slide");
					$("#slider ul li:first-child").find(".slider-caption").css("bottom","0");
				});
			});
			$(".current_slide .slider-caption").stop().animate({"bottom":"-50px"},300);
		}
		e.preventDefault();
	});
	
	setInterval(function(){
		if($("#slider ul").find(".current_slide").next().length){
			$(".current_slide").find(".slider-caption").stop().animate({"bottom":"-50px"},300);
			$(".current_slide").fadeOut(500,function(){
				var next = $(".current_slide").next();
				$(this).removeClass("current_slide");
				$(next).fadeIn(function(){
					$(this).addClass("current_slide");
					$(this).prev("li").find(".slider-caption").css("bottom","0");
				});
			});
		} else{
			$(".current_slide").find(".slider-caption").stop().animate({"bottom":"-50px"},300);
			$(".current_slide").fadeOut(500,function(){
				var first = $("#slider ul li:first-child");
				$(this).removeClass("current_slide");
				$(first).fadeIn(function(){
					$(this).addClass("current_slide");
					$("#slider ul li:last-child").find(".slider-caption").css("bottom","0");
				});
			});			
		}
	},6000);
	
	$("nav ul li a").on("click",function(e){	
//alert("1");
		var l = $("nav ul li").length,
		//alert(l);
		//console.log(l)
			i = $(this).parent().index(),
			t = "#"+$(this).text().toLowerCase(),
			th = this;
		if(i !== (l-0)){
			if(i !== (l-0)){
			
				console.log(i+" "+t);
				//console.log(l);
				var o = $("div.wrapper").find(t).offset().top;
				if(o){
					$("nav ul li").find(".active_nav_section").removeClass("active_nav_section");					
					$(th).addClass("active_nav_section");
					$("html, body").stop().animate({scrollTop:o},2000,"easeOutQuint",function(){
						
					});
				}
			}
		}
		e.preventDefault();		
	});
	
	$("#gallery-switch ul li a").on("click",function(e){
		var i = $(this).parent().index();
		$(this).addClass("active_gallery_navigation").parent().siblings("li").find("a").removeClass("active_gallery_navigation");
		if(i==0){
			$("#videos").fadeOut(1000,function(){
				$("#videos").removeClass("active_gallery_section");
				$("#photos").fadeIn(1000, function(){
					setTimeout(
						function(){
							$('#gallery .section-title').css("margin-top",0);	
							$('#gallery .section-title').vAlign()},
						100);
					eventsResizeList();
				});
			
			});
		}		
		else{
			$("#photos").fadeOut(1000,function(){
				$(this).removeClass("active_gallery_section");
				$("#videos").fadeIn(1000,function(){
					setTimeout(
						function(){
							$('#gallery .section-title').css("margin-top",0);
							$('#gallery .section-title').vAlign()},
						100);
					eventsResizeList();
				})				
			});
		}
		e.preventDefault();
	});
	
	/*news lightbox*/
	$("#news div h2 a, #news div h4 a, #news ul li a").on("click",function(e){
		var url = $(this).attr("href");
		if(url!=="#"){
			$("html,body").stop().animate({scrollTop:0},function(){
				$("#ajax-window").load(url+" #popup",function(data){
					$("#ajax-window").fadeIn();
				});
			});
		}
		e.preventDefault();
	})
	/*music lightbox*/
	$("#music ul li > a").on("click",function(e){
		var url = $(this).attr("href");
		if(url!=="#"){
			$("html,body").stop().animate({scrollTop:0},function(){
				$("#ajax-window").load(url+" #popup",function(data){
					$("#ajax-window").fadeIn();
				});
			});
		}
		e.preventDefault();
	})


	$("#ajax-window").on("click","#close_box",function(e){
		console.log("c");
		$("#ajax-window").fadeOut(function(){
			$(this).find("#popup").remove();
		});
		e.preventDefault();
	});
	$("#ajax-window").on("click",".contentbox-wrap",function(e){
		e.stopPropagation();
	});
	$("#ajax-window").click(function(){
		$("#ajax-window").fadeOut(function(){
			$(this).find("#popup").remove();
		});
	});

	/*OnScroll events*/
	$(document).scroll(function(){
		for(var i=0;i<offsets.length;i++){
			if(typeof(offsets[i+1]) != "undefined"){
				if($(document).scrollTop()>=offsets[i] && $(document).scrollTop()<=offsets[i+1]){
					$("nav ul li").find(".active_nav_section").removeClass("active_nav_section");
					$("nav ul li").each(function(index, element) {
						if($(this).index()==i){
							$(this).find("a").addClass("active_nav_section");
							//console.log($(this).find("a").text()+" "+i+" "+offsets[i]);
						}
					});
				}				
			}
			if( i == (offsets.length-1) && $(document).scrollTop()>=offsets[i]){
				$("nav ul li").find(".active_nav_section").removeClass("active_nav_section");
				$("nav ul li").each(function(index, element) {
					if($(this).index()==i){
						$(this).find("a").addClass("active_nav_section");						
					}
				});
			}
			if($(document).scrollTop()<offsets[0]){
				$("nav ul li").find(".active_nav_section").removeClass("active_nav_section");
			}
		}

	});

	/*Twitter feed*/
    //twitterFetcher.fetch('373830101911085056', 'twitter', 1, true);

	/*Custom Share Buttons*/
	/* var pinterestString = '<li><a id="share-pinterest" href="http://pinterest.com/pin/create/bookmarklet/?media='+""+'&url='+document.URL+'&is_video=false&description='+document.title+'" target="_blank">Pinterest</a></li>',
		facebookString = '<li><a id="share-facebook" href="http://www.facebook.com/share.php?u='+document.URL+'&title='+document.title+'" target="_blank">Facebook</a></li>',
		twitterString = '<li><a id="share-twitter" href="http://twitter.com/home?status='+document.title+'+'+document.URL+'" target="_blank">Twitter</a></li>',
		googleplusString = '<li><a id="share-google" href="https://plus.google.com/share?url='+document.URL+'" target="_blank">Google Plus</a></li>';
	$("#share_toggle ul").append(googleplusString);
	$("#share_toggle ul").append(twitterString);
	$("#share_toggle ul").append(facebookString);
	$("#share_toggle ul").append(pinterestString); */
});
})(window.jQuery);