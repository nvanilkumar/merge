function getFail(field,msg){
	return "<li><b>"+field+" : </b>"+msg+"</li>";
}

function getErrorMessage(strName, strMessage){
	return "<li><b>" + strName + ":&nbsp;&nbsp;</b>" + strMessage + "</li>";
}

function getRequiredErrorMessage(strName){
	return getErrorMessage(strName, "This value cannot be empty.");
}

function getMinLengthErrorMessage(strName, strValue, intMinLength){
	return getErrorMessage(strName, "This value must be at least " + intMinLength.toString() + " characters (currently " + strValue.length + " characters).");
}

function getMaxLengthErrorMessage(strName, strValue, intMaxLength){
	return getErrorMessage(strName, "This value can be no greater than " + intMaxLength.toString() + " characters (currently " + strValue.length + " characters).");
}

function getInvalidFileExtMessage(strName, strExts){
	return getErrorMessage(strName, "The file must have one of the allowed extentions (" + strExts + ").");
}

function isEmpty(str){
	var regEx = /\w+/;

	return !regEx.test(str);
}

function isValidPattern_Email(str){
	var regEx = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/i;
	
	return regEx.test(str);
}

function isValidPattern_URL(str){
	var regEx = new RegExp("^(http|https|ftp)://", "i");	
	return regEx.test(str);
}

function isValidPattern_WebsiteURL(str){
	var regEx = new RegExp("^(http|https)://", "i");	
	return regEx.test(str);
}

function isValidPattern_LoginName(str){
	var regEx = /^\S{6,25}$/i;
	
	return regEx.test(str);
}

function isValidPattern_Password(str){
	var regEx = /^\S{6,15}$/i;
	
	return regEx.test(str);
}

function isValidPattern_Decimal(str){
	var regEx = /(^\d+$)|(^\d+\.\d{0,2}$)/;
	
	return regEx.test(str);
}

function isValidPattern_Digits(str){
	var regEx = new RegExp("[^0-9]","ig");

	return !regEx.test(str);
}

function isRadioButtonSelected(strGroupName){
	var colButtons = document.getElementsByName(strGroupName);

	for(i = 0; i < colButtons.length; i++){
		if(colButtons[i].checked == true){
			return true;
		}
	}
	
	return false;
}