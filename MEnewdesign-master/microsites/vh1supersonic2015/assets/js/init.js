// StudioO1 V.1 //
// Copyright 2014, Mandar Shirke //

// --------- INIT JS ---------  //

// -- 1. SCROLL TO | PAGE NAVIGATION --  //
// -- 2. RESPONSIVE MENU TRIGGER --  //
// -- 3. CAROUSEL/SLIDER --  //
// -- 4. PORTFOLIO --  //
// -- 5. BLOG SORTING --  //
// -- 6. SKILLS --  //
// -- 7. TABS --  //
// -- 8. TIPSY --  //
// -- 9. FITVIDS --  //
// -- 10. SOCIAL --  //
// -- 11. PRETTYPHOTO --  //
// -- 12. PRELOADER --  //
// -- 13. CONTACT FORM --  //
// -- 14. GOOGLE MAPS --  //

// --------------------------  //

$(document).ready(function() {
var winWidth = $('body').width();
	if (winWidth <= 767) {
		
		// Add attribs to menu
		$('#Navigation li a').attr('data-toggle', 'collapse');
		$('#Navigation li a').attr('data-target', '#Navigation');
		
	} else {
	} 
 /* 
$('#primary-nav li').click(function(e){ 

var d=$('#qcMenuTrig a').css('display');
if(d=='block')
{
	$('#primary-nav').fadeOut();
}

});   */
 
   $('#qcMenuTrig').click(function(){

 var d=$('#primary-nav').css('display');
 if(d=='none')
 {
 
$('#primary-nav').fadeIn();
 }	
 });
 
$('#primary-nav li').click(function(e){ 

var d=$('#qcMenuTrig a').css('display');
if(d=='block')
{
	$('#primary-nav').fadeOut();
}

});  
 
 
 
 // --------------------  //


// --------- 1. SCROLL TO - PAGE/SITE NAVIGATION ----------  //

if (jQuery.isFunction(jQuery.fn.onePageNav)) {
jQuery('#primary-nav').onePageNav({
	currentClass: 'selected',
	changeHash: false,
	easing: 'jswing',
	scrollSpeed: 500,
	scrollOffset: 92,
	scrollThreshold: 0.1,
	filter: ':not(.external)',
	begin: false,
	end: false,
    scrollChange: false
});
//style="display: none;"

}

// --------- 2. RESPONSIVE MENU TRIGGER ----------  //

 /* $('#qcMenuTrig').hover(function(){
		$(this).find('a:visible').next('#primary-nav').fadeIn();
}, function() {
		$(this).find('a:visible').next('#primary-nav').fadeOut();
}); */
$(window).resize(function() {
	if ($(window).width() > 920) {
		$('#primary-nav').show();
	} else {
		$('#primary-nav').hide();
	}
}); 

// --------- 3. CAROUSEL/SLIDER ----------  //

$(window).load(function() {
	var owlSingle = $(".single-carousel");
	owlSingle.owlCarousel({
		singleItem: true,
		navigation: false,
		autoHeight: true,
		transitionStyle : "fadeUp"
	});
	// Custom Navigation Events
	$(".qcNext").click(function(){
		owlSingle.trigger('owl.next');
	});
	$(".qcPrev").click(function(){
		owlSingle.trigger('owl.prev');
	});
});

// 3 Column Carousel
var owl = $(".3-col-carousel");
owl.owlCarousel({
	items : 3, //10 items above 1000px browser width
	itemsDesktop : [1080,2], //5 items between 1000px and 901px
	itemsDesktopSmall : [900,2], // betweem 900px and 601px
	itemsTablet: [600,1], //2 items between 600 and 0
	itemsMobile : [600,1], // itemsMobile disabled - inherit from itemsTablet option
	navigation: true,
	navigationText : false,
	rewindNav: false
});

// About Slide
function InOut(elem) {
	elem.delay().fadeIn(500).delay(3000).fadeOut(
	function(){
		if(elem.next().length > 0) {
			InOut( elem.next() );
		} else {
			InOut( elem.siblings(':first'));
		}
	});
}
InOut($('#aboutCount li:first'));



// --------- 4. PORTFOLIO ----------  //

if (jQuery.isFunction(jQuery.fn.wookmark)) {
var loadedImages = 0; // Counter for loaded images
var handler = $('#tiles li'); // Get a reference to your grid items.
var filters = $('#filters li').not('.all');

// Prepare layout options.
var options = {
	autoResize: true, // This will auto-update the layout when the browser window is resized.
	container: $('#qcPortfolioGrid'), // Optional, used for some extra CSS styling
	offset: 10, // Optional, the distance between grid items
	itemWidth: 283, // Optional, the width of a grid item
	fillEmptySpace: true // Optional, fill the bottom of each column with widths of flexible height
};

$('#tiles').imagesLoaded(function() {
	// Call the layout function.
	handler.wookmark(options);
	/**
	* When a filter is clicked, toggle it's active state and refresh.
	*/
	var onClickFilter = function(event) {
		var item = $(event.currentTarget),
		activeFilters = [];
		if (!item.hasClass('active')) {
			filters.removeClass('active');
			$('#filters li.active').removeClass('active');
		}
		item.toggleClass('active');

		// Filter by the currently selected filter
		if (item.hasClass('active')) {
			activeFilters.push(item.data('filter'));
		}
		handler.wookmarkInstance.filter(activeFilters);
	}
	// Capture filter click events.
	filters.click(onClickFilter);
	// Remove filter (Show All)
	$('#filters li.all').click(function() {
		$('#filters li.active').trigger('click');
		$(this).addClass('active');
	});
}).progress(function(instance, image) {
	// Update progress bar after each image load
	loadedImages++;
	if (loadedImages == handler.length)
		$('.progress-bar').hide();
	else
		$('.progress-bar').width((loadedImages / handler.length * 100) + '%');
});
}


// --------- 5. BLOG SORTING ----------  //

$('#qcBlogSort li a').click(function() {
	$('#qcBlogSort li a.active').removeClass('active');
	$(this).addClass('active');
	var sortby = $(this).attr('data-sort');
	if ( sortby === 'all' ) {
		$('#blog-list > li').css('display', 'block');
	} else {
		$('#blog-list > li').each(function() {
			if ($(this).attr('data-sort') === sortby ) {
				$(this).css('display', 'block');
			} else {
				$(this).css('display', 'none');
			}
		});
	}
	return false;
});



// --------- 6. SKILLS ----------  //

$('#qcSkills li').each(function() {
	$(this).append('<div></div><div></div><div></div><div></div><div></div>');
	$(this).find('div:first-child').html('<span>' + $(this).attr('data-skill') + '</span>');
	$(this).find('div:nth-child(-n+'+ $(this).attr('data-skill-meter') +')').css('background', $(this).attr('data-skill-color'));
	$(this).attr('title', $(this).attr('data-skill') + ' = ' + ($(this).attr('data-skill-meter') * 100) / 5 + '%');
});



// --------- 7. TABS ----------  //

$('.tabs a').click(function(){
	switch_tabs($(this));
});
switch_tabs($('.defaulttab'));
function switch_tabs(obj) {
	$('.tab-content').hide();
	$('.tabs a').removeClass("selected");
	var id = obj.attr("data-rel");
	$('#'+id).fadeIn(500);
	obj.addClass("selected");
}



// --------- 8. TIPSY ----------  //
jQuery('.tips').tipsy({gravity: 's'});
jQuery('#qcSkills li').tipsy({gravity: 'e', offset: 8});



// --------- 9. FITVIDS ----------  //

$(".qcFitVids").fitVids();
$('.qcFitVids').click(function() {
	$(this).addClass('hide');
});



// --------- 10. SOCIAL ----------  //

// Socialite
Socialite.load();

// Flickr
$('#flickr').jflickrfeed({
	limit: 12,
	qstrings: {
	id: '52617155@N08' // Define Flickr ID //
	},
	itemTemplate: '<li><a href="{{image_b}}" rel="prettyPhoto[pp_gal]"><img class="flickr" src="{{image_s}}" alt="{{title}}"></a></li>'
	}, function(data) {
	$('#flickr a').prettyPhoto();
});


// --------- 11. PRETTYPHOTO ----------  //

if (jQuery.isFunction(jQuery.fn.prettyPhoto)) {
	$("a[data-rel^='prettyPhoto']").prettyPhoto({theme:'light_square'});
}





// --------- 12. PRELOADER ----------  //

$(window).load(function() {
	$("#qcPreLoader").fadeOut(500);
});


// --------- 13. CONTACT FORM ----------  //

$('.qcForm').submit(function() {
	$(this).find('.error').remove();
	var hasError = false;
	$(this).find('.requiredField').each(function() {
		if($.trim($(this).val()) == '') {
			var labelText = $(this).prev( 'label').text();
			$(this).parent().append( '<span class="error">You forgot to enter your '+labelText+'</span>' );
			$(this).addClass( 'inputError' );
			hasError = true;
		} else if($(this).hasClass( 'email')) {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if(!emailReg.test($.trim($(this).val()))) {
				var labelText = $(this).prev( 'label').text();
				$(this).parent().append( '<span class="error">You have entered an invalid '+labelText+'</span>' );
				$(this).addClass( 'inputError' );
				hasError = true;
			}
		}
	});
	if(!hasError) {
		var formInput = $(this).serialize();
		var hideForm = $(this);
		$.post($(this).attr('action'),formInput, function(data){
			$(hideForm).slideUp( "fast", function() {				   
				$(this).before( '<br/><p class="info">Thanks! Your email was successfully sent.</p>' );
			});
		});
	}
	return false;
});


// --------- 14. GOOGLE MAPS ----------  //

$(window).load(function() {
if (document.getElementById('qcContactMap')) {
	var myLatlng = new google.maps.LatLng($('#qcMapAddress').attr('data-lat'),$('#qcMapAddress').attr('data-lng'));
	var mapOptions = {
		zoom: 13,
		center: myLatlng,
		scrollwheel: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	var map = new google.maps.Map(document.getElementById('qcContactMap'), mapOptions);
	var contentString = '<div id="content">'+
	'<div id="siteNotice">'+
	'</div>'+
	'<div id="bodyContent">'+ $('#qcMapAddress').attr('data-add') +
	'</div>'+
	'</div>';
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	var marker = new google.maps.Marker({
		position: myLatlng,
		map: map,
		title: ''
	});
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}
});



// --------------------  //

});