// This callback function is called when the content script has been 
// injected and returned its results
var opt = {
  type: "basic",
  title: "Primary Title",
  message: "Primary message to display",
  iconUrl: "http://www.google.com/favicon.ico",
};
chrome.notifications.create(opt, creationCallback);
function creationCallback(){
    console.log(5555);
}

 
 