<script type="text/javascript" language="javascript">
	function validate_check(){
 
		if(document.edit_details.cheque_no.value==''){
		   alert("Please Enter Cheque No.");
		   document.edit_details.cheque_no.focus();
		   return false;
	   }
	   if(document.edit_details.name_bank.value==''){
		   alert("Please Enter Bank Name And Branch Information.");
		   document.edit_details.name_bank.focus();
		   return false;
	   }
	   if(document.edit_details.dated.value==''){
		   alert("Please Mention When The Cheque Is Dated");
		   document.edit_details.dated.focus();
		   return false;
	   }
	   var dt=document.edit_details.dated
	   if (isDate(dt.value)==false){
			dt.focus()
			return false
		}
		
	   return true;
 }// END OF FUNCTION
 
 ////////////////////////// FUNCTIONS FROR VALID DATE VALIDATION//////////////////
 ///////////////////////////////////////////////////////////////////////////////////
 
 // Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strMonth=dtStr.substring(0,pos1)
	var strDay=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		alert("The date format should be : mm/dd/yyyy")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date")
		return false
	}
return true
}


 ////////////////////////////////////////////////////////////////////////////////
</script>
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="post" name="edit_details" onSubmit="return validate_check();">
<table width="70%" align="center" cellpadding="2" cellspacing="2" style="border:thin; border-color:#006699; border-style:solid;">
  <tr>
    <td colspan="4" align="center" valign="middle" class="headtitle"><strong>Personal Details </strong></td>
  </tr>
  <tr>
    <td width="17%" align="left" valign="middle" >&nbsp;</td>
    <td width="23%" align="left" valign="middle">&nbsp;</td>
    <td width="4%" align="left" valign="middle">&nbsp;</td>
    <td width="56%" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Name</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['name'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Address</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['address'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Pincode</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['pincode'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Country</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['country'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">State</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['state'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">City </td>
    <td align="center" valign="middle" class="tblcont">: </td>
    <td align="left" valign="middle"><?php echo $row['city'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Contact Number </td>
    <td align="center" valign="middle" class="tblcont">: </td>
    <td align="left" valign="middle"><?php echo $row['contact'];?></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Email Address</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['email'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="center" class="headtitle"><strong>Purchase Details</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Plan</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['plan'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Qty</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['quantity'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Amount</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['amount'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" class="headtitle" align="center"><strong>Transaction Details</strong></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Performa No</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><?php echo $row['invoice_no'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Cheque No</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input type="text" name="cheque_no" value='<?=$row['cheque_no'];?>'>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Dated</td>
    <td align="center" valign="middle" class="tblcont"> : </td>
    <td align="left" valign="middle"><label>
      <input type="text" name="dated" value='<?=$row['dated'];?>'>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="left" valign="middle" class="tblcont">Name/Branch Of Bank </td>
    <td align="center" valign="middle" class="tblcont">:</td>
    <td align="left" valign="middle"><label>
      <input type="text" name="name_bank" value='<?=$row['name_bank'];?>'>
    </label></td>
  </tr>
  <tr>
    <td colspan="4" align="center" valign="middle"><label>
      <input type="submit" name="Submit" value="Update Details">
	  <input type="hidden" name="pay_id" value="<?=$row['pay_id'];?>">
    </label></td>
    </tr>
</table>
</form>
</div>