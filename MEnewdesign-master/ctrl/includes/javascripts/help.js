var _intHelpMaxWidth = 950;
var _intHelpMaxHeight = 700;

function showHelp_CardCodes(){
	showHelp("Help/cardcodes.php", 550, 470, false, false);
}	

function showHelp_Pricing(intCategoryID){
	showHelp("pricing.php?CategoryID=" + intCategoryID.toString(), _intHelpMaxWidth, _intHelpMaxHeight, true, true);
}

function showHelp_PrivacyPolicy(){
	showHelp("Help/privacypolicy.php", 550, 480, true, true);
}

function showHelp_SamplePaymentTerms(){
	showHelp("Help/samplepaymentterms.php", 490, 350, false, false);
}

function showHelp_SampleWinningBid(){
	showHelp("Help/winningbid.php", 550, 600, false, false);
}

function showHelp_ServiceAgreement(){
	showHelp("Help/serviceagreement.php", 550, 480, true, true);
}

function showHelp_Tasks(){
	showHelp("Help/tasks.php", 475, 350, false, false);
}

function showHelp_FAQAnswer(intQuestionID){
	showHelp("Help/faqanswer.php?QID=" + intQuestionID.toString(), 600, 400, false, true);
}

function showHelp(strPage, intWidth, intHeight, blnResizable, blnScroll){
var strOptions = "width=" + intWidth.toString() + ", height=" + intHeight.toString() + ", top=50, left=50, status=no, toolbar=no, menubar=no, location=no, resizable=" + toOption(blnResizable) + ", scrollbars=" + toOption(blnScroll);
	window.open(strPage, null, strOptions);
}

function toOption(blnValue){
    return (blnValue ? "yes" : "no");
}