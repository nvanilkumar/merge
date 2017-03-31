// JavaScript Document
function checkSignupForm(){
	
	var fl = true;
	
	var firstName = document.signupForm.txtFirstName.value;
	var lastName =  document.signupForm.txtLastName.value;
	var displayName = document.signupForm.txtDisplayName.value;
	var streetAddress = document.signupForm.txtStreetAddress.value;
	var city = document.getElementById('selCity');
	var state = document.getElementById('selState');
	var county = document.getElementById('selCounty');
	var phoneOffice1 = document.signupForm.txtPhoneOffice1.value;
	var phoneOffice2 = document.signupForm.txtPhoneOffice2.value;
	var phoneOffice3 = document.signupForm.txtPhoneOffice3.value;
	var phoneExt = document.signupForm.txtPhoneExt.value;
	var phoneHome1 = document.signupForm.txtPhoneHome1.value;
	var phoneHome2 = document.signupForm.txtPhoneHome2.value;
	var phoneHome3 = document.signupForm.txtPhoneHome3.value;
	var phoneMobile1 = document.signupForm.txtPhoneMobile1.value;
	var phoneMobile2 = document.signupForm.txtPhoneMobile2.value;
	var phoneMobile3 = document.signupForm.txtPhoneMobile3.value;
	var phoneOp1 = document.signupForm.txtPhoneOp1.value;
	var phoneOp2 = document.signupForm.txtPhoneOp2.value;
	var phoneOp3 = document.signupForm.txtPhoneOp3.value;
	var zipCode = document.signupForm.txtZipCode.value;
	var email = document.signupForm.txtEmailAddress.value; 
	var web = document.signupForm.txtWebsite.value; 
	var uname = document.signupForm.txtLoginName.value; 
	var password = document.signupForm.txtPassword.value; 
	var cpassword = document.signupForm.txtConfirmPassword.value;
	
	var fail = "<ul>";	
	
	if(isEmpty(firstName)){
		fail += getFail("First Name","This value cannot be empty.");
		fl = false;
	}
	if(isEmpty(lastName)){
		fail += getFail("Last Name","This value cannot be empty.");
		fl = false;
	}	
	if(isEmpty(displayName)){
		fail += getFail("Display Name","This value cannot be empty.");
		fl = false;
	}
	if(isEmpty(streetAddress)){
		fail += getFail("Street Address","This value cannot be empty.");
		fl = false;
	}
	if(state.value==0){
		fail += getFail("State / Province","Select State / Province from list.");
		fl = false;
	}
	if(city.value==0){
		fail += getFail("City","Select City from list.");
		fl = false;
	}	
	if(county.value==0){
		fail += getFail("County","Select County from list.");
		fl = false;
	}
	
	if((isEmpty(phoneOffice1) || isEmpty(phoneOffice2) || isEmpty(phoneOffice3)) && (isEmpty(phoneHome1) || isEmpty(phoneHome2) || isEmpty(phoneHome3)) && (isEmpty(phoneMobile1) || isEmpty(phoneMobile2) || isEmpty(phoneMobile3)) && (isEmpty(phoneOp1) || isEmpty(phoneOp2) || isEmpty(phoneOp3)))
	{
		fail += getFail("Phone Number", "You have to put atleast one phone number and it should be in valid format.");
		f1 = false;
	}
	else
	{
		if(!isEmpty(phoneOffice1) || !isEmpty(phoneOffice2) || !isEmpty(phoneOffice3))
		{
			if(isEmpty(phoneOffice1) || (!isEmpty(phoneOffice1) && !isValidPattern_Digits(phoneOffice1)) )
			{
				fail += getFail("Phone Number- Office:", "You have to put the area code and it must be in valid format.");
				f1 = false;
			}		
			else if(isEmpty(phoneOffice2) || isEmpty(phoneOffice3) || (!isEmpty(phoneOffice2) && !isValidPattern_Digits(phoneOffice2)) || (!isEmpty(phoneOffice3) && !isValidPattern_Digits(phoneOffice3)))
			{
				fail += getFail("Phone Number- Office:", "The value must be in valid format.");
				f1 = false;
			}
		}
		
		if(!isEmpty(phoneHome1) || !isEmpty(phoneHome2) || !isEmpty(phoneHome3))
		{
			if(isEmpty(phoneHome1) || (!isEmpty(phoneHome1) && !isValidPattern_Digits(phoneHome1)) )
			{
				fail += getFail("Phone Number- Home:", "You have to put the area code and it must be in valid format.");
				f1 = false;
			}		
			else if(isEmpty(phoneHome2) || isEmpty(phoneHome3) || (!isEmpty(phoneHome2) && !isValidPattern_Digits(phoneHome2)) || (!isEmpty(phoneHome3) && !isValidPattern_Digits(phoneHome3)))
			{
				fail += getFail("Phone Number- Home:", "The value must be in valid format.");
				f1 = false;
			}
		}
		
		if(!isEmpty(phoneMobile1) || !isEmpty(phoneMobile2) || !isEmpty(phoneMobile3))
		{
			if(isEmpty(phoneMobile1) || (!isEmpty(phoneMobile1) && !isValidPattern_Digits(phoneMobile1)) )
			{
				fail += getFail("Phone Number- Mobile:", "You have to put the area code and it must be in valid format.");
				f1 = false;
			}		
			else if(isEmpty(phoneMobile2) || isEmpty(phoneMobile3) || (!isEmpty(phoneMobile2) && !isValidPattern_Digits(phoneMobile2)) || (!isEmpty(phoneMobile3) && !isValidPattern_Digits(phoneMobile3)))
			{
				fail += getFail("Phone Number- Mobile:", "The value must be in valid format.");
				f1 = false;
			}
		}
		
		if(!isEmpty(phoneOp1) || !isEmpty(phoneOp2) || !isEmpty(phoneOp3))
		{
			if(isEmpty(phoneOp1) || (!isEmpty(phoneOp1) && !isValidPattern_Digits(phoneOp1)) )
			{
				fail += getFail("Phone Number- Optional:", "You have to put the area code and it must be in valid format.");
				f1 = false;
			}		
			else if(isEmpty(phoneOp2) || isEmpty(phoneOp3) || (!isEmpty(phoneOp2) && !isValidPattern_Digits(phoneOp2)) || (!isEmpty(phoneOp3) && !isValidPattern_Digits(phoneOp3)))
			{
				fail += getFail("Phone Number- Optional:", "The value must be in valid format.");
				f1 = false;
			}
		}
	}
	
	/*if(!isEmpty(phoneOffice1) && !isEmpty(phoneOffice2) && !isEmpty(phoneOffice3))
	{
		if(isEmpty(phoneExt))
		{
			fail += getFail("Phone Extension", "This value can not be empty.");
			f1 = false;
		}
	}*/
	
	if(isEmpty(zipCode)){
		fail += getFail("Zip / Postal Code","This value cannot be empty.");
		fl = false;
	}
	if(isEmpty(email) || !isValidPattern_Email(email)){
		fail += getFail("Email Address","This value is required and must be in a valid format.");
		fl = false;
	}
	
	if(web!='')
	{
		if(!isValidPattern_WebsiteURL(web)){		
			fail += getFail("Website","This value must be preceeded by http:// or https://.");
			fl = false;
		}
	}
	
	if(isEmpty(uname) || uname.indexOf(" ")>=0 || uname.length<6||uname.length>25){
		fail += getFail("Login Name ","This value is required and must be between 6 and 25 characters (no spaces).");
		fl = false;
	}
	if(isEmpty(password) || password.indexOf(" ")>=0 || password.length<6||password.length>15){
		fail += getFail("Password ","This value is required and must be between 6 and 15 characters (no spaces).");
		fl = false;
	}	
	if(cpassword != password){
		fail += getFail("Confirm Password ","This value must match the \"Password\" field.");
		fl = false;
	}
	var acc = document.signupForm.chkServiceAgreement;	
	if(!acc.checked){		
		fail += getFail("Service Agreement ","You must agree to the license agreement to register.");
		fl = false;			
	}
	
	var byr = document.signupForm.chkbuyer;	
	var prv = document.signupForm.chkprovider;
	if(!byr.checked && !prv.checked){		
		fail += getFail("Buyer/Provider ","You must be Buyer or Provider or both.");
		fl = false;			
	}
	
	fail += "</ul>";

	if(!fl)
	{
		document.getElementById("divFailMsg").style.display="block";
		document.getElementById("divFailures").innerHTML = fail;
		return false;		
	}
	else
	{
		//document.getElementById("divButtons").innerHTML = "Please wait...";
		document.signupForm.submit();
	}
	return fl;
}

function checkLoginForm(){
	var uname = document.loginForm.txtUsername.value;
	var password = document.loginForm.txtPassword.value;
	var fl = true;
	var fail = "<ul>";
	if(isEmpty(uname) || uname.indexOf(" ")>=0 || uname.length<6||uname>25){
		fail += getFail("Login Name ","This value is required and must be between 6 and 25 characters (no spaces).");
		fl = false;
	}
	if(isEmpty(password) || password.indexOf(" ")>=0 || password.length<6||password>15){
		fail += getFail("Password ","This value is required and must be between 6 and 15 characters (no spaces).");
		fl = false;
	}
	
	fail += "</ul>";

	if(!fl){
		document.getElementById("divFailMsg").style.display="block";
		document.getElementById("divFailures").innerHTML = fail;
		return false;		
	} else{
		document.getElementById("tdSubmit").innerHTML = "Please wait...";
		document.loginForm.submit();	
	}
	return fl;	
}

function checkContactForm(){
	var fl = true;
	var email = document.contactForm.txtEmailAddress.value;
	var subject = document.contactForm.txtSubject.value;
	var message = document.contactForm.txtMessage.value;
	var fail = "<ul>";
	if(isEmpty(email) || !isValidPattern_Email(email)){
		fail += getFail("Email Address","This value is required and must be in a valid format.");
		fl = false;
	}
	if(isEmpty(subject)){
		fail += getFail("Subject","This value cannot be empty.");
		fl = false;		
	}
	if(isEmpty(message)){
		fail += getFail("Message","This value cannot be empty.");
		fl = false;		
	}	
	
	fail += "</ul>";
	if(!fl){
		document.getElementById("divFailMsg").style.display="block";
		document.getElementById("divFailures").innerHTML = fail;

		window.location = "#";
	} else{
		document.getElementById("divButtons").innerHTML = "Please wait...";
		document.contactForm.submit();	
	}
	return fl;
}

function checkModifyForm()
{
	var f2 = true;
	
	var firstName = document.modifyForm.txtFirstName.value;
	var lastName =  document.modifyForm.txtLastName.value;
	var displayName = document.modifyForm.txtDisplayName.value;
	var streetAddress = document.modifyForm.txtStreetAddress.value;
	var city = document.getElementById('selCity');
	var state = document.getElementById('selState');
	var county = document.getElementById('selCounty');
	var phoneOffice1 = document.modifyForm.txtPhoneOffice1.value;
	var phoneOffice2 = document.modifyForm.txtPhoneOffice2.value;
	var phoneOffice3 = document.modifyForm.txtPhoneOffice3.value;
	var phoneExt = document.modifyForm.txtPhoneExt.value;
	var phoneHome1 = document.modifyForm.txtPhoneHome1.value;
	var phoneHome2 = document.modifyForm.txtPhoneHome2.value;
	var phoneHome3 = document.modifyForm.txtPhoneHome3.value;
	var phoneMobile1 = document.modifyForm.txtPhoneMobile1.value;
	var phoneMobile2 = document.modifyForm.txtPhoneMobile2.value;
	var phoneMobile3 = document.modifyForm.txtPhoneMobile3.value;
	var phoneOp1 = document.modifyForm.txtPhoneOp1.value;
	var phoneOp2 = document.modifyForm.txtPhoneOp2.value;
	var phoneOp3 = document.modifyForm.txtPhoneOp3.value;
	var zipCode = document.modifyForm.txtZipCode.value;
	var email = document.modifyForm.txtEmailAddress.value; 
	var web = document.modifyForm.txtWebsite.value;
	
	var fail = "<ul>";	
	
	var fail = "<ul>";	
	
	if(isEmpty(firstName)){
		fail += getFail("First Name","This value cannot be empty.");
		f2 = false;
	}
	if(isEmpty(lastName)){
		fail += getFail("Last Name","This value cannot be empty.");
		f2 = false;
	}	
	if(isEmpty(displayName)){
		fail += getFail("Display Name","This value cannot be empty.");
		f2 = false;
	}
	if(isEmpty(streetAddress)){
		fail += getFail("Street Address","This value cannot be empty.");
		f2 = false;
	}
	if(state.value==0){
		fail += getFail("State / Province","Select State / Province from list.");
		f2 = false;
	}
	if(city.value==0){
		fail += getFail("City","Select City from list.");
		f2 = false;
	}	
	if(county.value==0){
		fail += getFail("County","Select County from list.");
		f2 = false;
	}
	
	if((isEmpty(phoneOffice1) || isEmpty(phoneOffice2) || isEmpty(phoneOffice3)) && (isEmpty(phoneHome1) || isEmpty(phoneHome2) || isEmpty(phoneHome3)) && (isEmpty(phoneMobile1) || isEmpty(phoneMobile2) || isEmpty(phoneMobile3)) && (isEmpty(phoneOp1) || isEmpty(phoneOp2) || isEmpty(phoneOp3)))
	{
		fail += getFail("Phone Number", "You have to put atleast one phone number and it should be in valid format.");
		f2 = false;
	}
	else
	{
		if(!isEmpty(phoneOffice1) || !isEmpty(phoneOffice2) || !isEmpty(phoneOffice3))
		{
			if(isEmpty(phoneOffice1) || (!isEmpty(phoneOffice1) && !isValidPattern_Digits(phoneOffice1)) )
			{
				fail += getFail("Phone Number- Office:", "You have to put the area code and it must be in valid format.");
				f2 = false;
			}		
			else if(isEmpty(phoneOffice2) || isEmpty(phoneOffice3) || (!isEmpty(phoneOffice2) && !isValidPattern_Digits(phoneOffice2)) || (!isEmpty(phoneOffice3) && !isValidPattern_Digits(phoneOffice3)))
			{
				fail += getFail("Phone Number- Office:", "The value must be in valid format.");
				f2 = false;
			}
		}
		
		if(!isEmpty(phoneHome1) || !isEmpty(phoneHome2) || !isEmpty(phoneHome3))
		{
			if(isEmpty(phoneHome1) || (!isEmpty(phoneHome1) && !isValidPattern_Digits(phoneHome1)) )
			{
				fail += getFail("Phone Number- Home:", "You have to put the area code and it must be in valid format.");
				f2 = false;
			}		
			else if(isEmpty(phoneHome2) || isEmpty(phoneHome3) || (!isEmpty(phoneHome2) && !isValidPattern_Digits(phoneHome2)) || (!isEmpty(phoneHome3) && !isValidPattern_Digits(phoneHome3)))
			{
				fail += getFail("Phone Number- Home:", "The value must be in valid format.");
				f2 = false;
			}
		}
		
		if(!isEmpty(phoneMobile1) || !isEmpty(phoneMobile2) || !isEmpty(phoneMobile3))
		{
			if(isEmpty(phoneMobile1) || (!isEmpty(phoneMobile1) && !isValidPattern_Digits(phoneMobile1)) )
			{
				fail += getFail("Phone Number- Mobile:", "You have to put the area code and it must be in valid format.");
				f2 = false;
			}		
			else if(isEmpty(phoneMobile2) || isEmpty(phoneMobile3) || (!isEmpty(phoneMobile2) && !isValidPattern_Digits(phoneMobile2)) || (!isEmpty(phoneMobile3) && !isValidPattern_Digits(phoneMobile3)))
			{
				fail += getFail("Phone Number- Mobile:", "The value must be in valid format.");
				f2 = false;
			}
		}
		
		if(!isEmpty(phoneOp1) || !isEmpty(phoneOp2) || !isEmpty(phoneOp3))
		{
			if(isEmpty(phoneOp1) || (!isEmpty(phoneOp1) && !isValidPattern_Digits(phoneOp1)) )
			{
				fail += getFail("Phone Number- Optional:", "You have to put the area code and it must be in valid format.");
				f2 = false;
			}		
			else if(isEmpty(phoneOp2) || isEmpty(phoneOp3) || (!isEmpty(phoneOp2) && !isValidPattern_Digits(phoneOp2)) || (!isEmpty(phoneOp3) && !isValidPattern_Digits(phoneOp3)))
			{
				fail += getFail("Phone Number- Optional:", "The value must be in valid format.");
				f2 = false;
			}
		}
	}
	
	/*if(!isEmpty(phoneOffice1) && !isEmpty(phoneOffice2) && !isEmpty(phoneOffice3))
	{
		if(isEmpty(phoneExt))
		{
			fail += getFail("Phone Extension", "This value can not be empty.");
			f2 = false;
		}
	}*/
	
	if(isEmpty(zipCode)){
		fail += getFail("Zip / Postal Code","This value cannot be empty.");
		f2 = false;
	}
	if(isEmpty(email) || !isValidPattern_Email(email)){
		fail += getFail("Email Address","This value is required and must be in a valid format.");
		f2 = false;
	}
	
	if(web!='')
	{
		if(!isValidPattern_WebsiteURL(web)){		
			fail += getFail("Website","This value must be preceeded by http:// or https://.");
			f2 = false;
		}
	}
	
	fail += "</ul>";

	if(!f2)
	{
		document.getElementById("divFailMsg").style.display="block";
		document.getElementById("divFailures").innerHTML = fail;
		window.location = "#";		
	}
	else
	{
		document.getElementById("divButtons").innerHTML = "Please wait...";		
	}
	return f2;
}