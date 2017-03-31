setScreenWidthCookie();

function toggleVisibility(ctl){
	if(ctl.style.display.toLowerCase() == "none")
		ctl.style.display = "block";
	else
		ctl.style.display = "none";
}

function setScreenWidthCookie(){
  var dteExpire = new Date();
  dteExpire.setFullYear(dteExpire.getFullYear() + 1);
  setCookie("ifscr", screen.width.toString(), dteExpire, "/", null);
}

function setCookie(strName, strValue, dteExpires, strPath, strDomain)
{
  document.cookie = strName + "=" + escape(strValue) +
    ((dteExpires != null) ? "; expires=" + dteExpires.toUTCString() : "") +
    ((strPath != null) ? "; path=" + strPath : "") +
    ((strDomain != null) ? "; domain=" + strDomain : "");
}

function showMessage(msgMessage, ctlToHide){
	msgMessage.style.display = "";
	ctlToHide.style.display = "none";	
}

function hideMessage(msgMessage, ctlToShow){
	msgMessage.style.display = "none";
	ctlToShow.style.display = "";
}

function checkAll(aryChk)
{
	for(var i = 0; i < aryChk.length; i++)
		document.getElementById(aryChk[i]).checked = true;
}

function checkNone(aryChk)
{
	for(var i = 0; i < aryChk.length; i++)
		document.getElementById(aryChk[i]).checked = false;
}

function setBackgroundColor(aryCtl, strColor)
{
	for(var i = 0; i < aryCtl.length; i++)
		document.getElementById(aryCtl[i]).style.backgroundColor = strColor;
}

function setParentBackgroundColor(aryCtl, strColor)
{
	for(var i = 0; i < aryCtl.length; i++)
		document.getElementById(aryCtl[i]).parentNode.style.backgroundColor = strColor;
}

function getCheckedValue(aryCtl){
	for(var i = 0; i < aryCtl.length; i++){
		var ctl = document.getElementById(aryCtl[i]);
		if(ctl.checked)
			return ctl.value;
	}
}

function getCheckedCount(aryCtl){
	var intCount = 0;
	
	for(var i = 0; i < aryCtl.length; i++){
		var ctl = document.getElementById(aryCtl[i]);
		if(ctl.checked)
			intCount++;
	}
	
	return intCount;
}

//returns count of controls with a value
function getValueCount(aryCtl){
	var intCount = 0;
	
	for(var i = 0; i < aryCtl.length; i++){
		var ctl = document.getElementById(aryCtl[i]);
		if(ctl.value.length > 0)
			intCount++;
	}
	
	return intCount;
}

function openSample(strURL){			
	window.open(strURL, null, "width=750, height=550, top=50, left=50, status=yes, toolbar=yes, menubar=no, location=yes, resizable=yes, scrollbars=yes");
}

function setDaysInMonth(selMonth, selDay, selYear){
	var intMonth = parseInt(selMonth.value);
	var intDay = parseInt(selDay.value);
	var intYear = parseInt(selYear.value);

	var aryDays = new Array(31, ((intYear % 4 == 0 && intYear % 100 != 0) || intYear % 400 == 0 ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var intDaysInMonth = aryDays[intMonth - 1];
	
	if(intDaysInMonth != selDay.options.length){
		selDay.options.length = 0;
		
		for(i = 0; i < intDaysInMonth; i++)
			selDay.options[i] = new Option((i + 1).toString(), (i + 1).toString());

		selDay.selectedIndex = ((intDay <= intDaysInMonth) ? (intDay - 1) : (intDaysInMonth - 1));
	}
}

function copyToClipboard(txtBox, blnAlert){
	txtBox.createTextRange().execCommand("copy");

	if(blnAlert)
		alert("The text has been copied to your clipboard and is ready to be pasted.")
}

function confirmFileDelete(strFileName){
	return confirm("Are you sure you want to delete the attached file '" + strFileName + "'?");
}

function positionToolTip(e, divToolTip){
	var vp = new Viewport();
	
	if((getRelativeMouseX(e) - vp.scrollLeft + divToolTip.clientWidth) > vp.width)
		 divToolTip.style.left = (vp.width - divToolTip.clientWidth + vp.scrollLeft - 2).toString() + "px";
	else
		 divToolTip.style.left = (getRelativeMouseX(e) + 10).toString() + "px";

	divToolTip.style.top = (getRelativeMouseY(e) + 10).toString() + "px";
}

function getRelativeMouseY(e){
	if(document.documentElement.scrollTop)
		return e.clientY + document.documentElement.scrollTop;
	else
		return e.clientY;
}

function getRelativeMouseX(e){
	if(document.documentElement.scrollLeft)
		return e.clientX + document.documentElement.scrollLeft;
	else
		return e.clientX
}