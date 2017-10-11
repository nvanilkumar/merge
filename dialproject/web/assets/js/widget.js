var scopwidgeturl = 'http://192.168.36.56:8080/scopdial/web/app.php/widget/openurl'; //Please change this according to your requirement
var extension = document.getElementById('scopdial_widget_js').getAttribute("data-extension");
if (extension == 'undefined' || extension == '') {
    console.log('Invalid Extension!');
} else {
    var scopdial_widget_frame = '<div class="scopdial_live_widget" id="scopdial_live_widget" style="margin-top: 0px; margin-right: 0px; margin-bottom: 0px; padding: 0px; border: 0px; overflow: hidden; position: fixed; z-index: 16000001; right: 10px; bottom: 0px; border-top-left-radius: 5px; border-top-right-radius: 5px; width: 800px; height: 600px; box-shadow: rgba(0, 0, 0, 0.0980392) 0px 0px 3px 2px; background: transparent;"><iframe frameborder="0" src="" style="vertical-align: text-bottom; position: relative; width: 100%; height: 100%; min-width: 100%; min-height: 100%; max-width: 100%; max-height: 100%; margin: 0px; overflow: hidden; display: block; background-color: transparent;"></iframe></div>';
    document.body.innerHTML += scopdial_widget_frame;
    var scopdialFrame = document.getElementById('scopdial_live_widget').children[0];
    var scopdial_openurl = scopwidgeturl + '?extension=' + extension;
    scopdialFrame.addEventListener("load", function () {
        scopdialFrame.parentElement.style.display = "block";
        this.contentWindow.document.getElementById("widget-close").addEventListener("click", hideWidget);
        this.contentWindow.document.getElementById("widget-open").addEventListener("click", showWidget);
    });

    scopdialFrame.src = scopdial_openurl;
}
function hideWidget() {
    scopdialFrame.parentElement.style.width = "280px";
    scopdialFrame.parentElement.style.height = "35px";
}
function showWidget() {

    scopdialFrame.parentElement.style.width = "800px";
    scopdialFrame.parentElement.style.height = "600px";

}