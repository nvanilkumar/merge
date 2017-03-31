
if($.cookie("vmusic")) {
    $("link#link").attr("href",$.cookie("vmusic"));
}
$(document).ready(function() { 
    $(".style-switcher a").click(function() { 
        $("link#link").attr("href",$(this).attr('id'));
        $.cookie("vmusic",$(this).attr('id'), { path: '/'});
        return false;
    });
    // show-hide color pallete
    $('#toggle').click(function(e){
        e.preventDefault();
        var div = $('#style-switcher-container');
        console.log(div.css('left'));
        if (div.css('left') === '-130px') {
            $('#style-switcher-container').animate({
                left: '0px'
            }); 
        } else {
            $('#style-switcher-container').animate({
                left: '-130px'
            });
        }
    })
});
    