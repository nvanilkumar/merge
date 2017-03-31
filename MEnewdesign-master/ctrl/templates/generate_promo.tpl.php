<script language="javascript" type="text/javascript">
	document.getElementById('ans4').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/CalendarControl.min.js.gz"></script>
<script language="javascript">
function valid()
{
	if(document.frmGen.coupan_name.value == '')
	{
		alert("Please enter Coupan Name");
		document.frmGen.coupan_name.focus();
		return false;
	}
	if(document.frmGen.discount_rate.value == '')
	{
		alert("Please enter discount rate. ");
		document.frmGen.discount_rate.focus();
		return false;
	}
	else if(!isValidPattern_Digits(document.frmGen.discount_rate.value))
	{
		alert("Please enter Digits in INR");
		document.frmGen.discount_rate.focus();
		return false;
	}
	if(document.frmGen.strt_date.value == '')
	{
		alert("Please enter Start Date. ");
		document.frmGen.strt_date.focus();
		return false;
	}
	if(document.frmGen.end_date.value == '')
	{
		alert("Please enter End Date. ");
		document.frmGen.end_date.focus();
		return false;
	}
	if(document.frmGen.num_code.value == '')
	{
		alert("Please enter No of Promotion Code. ");
		document.frmGen.num_code.focus();
		return false;
	}
	else if(!isValidPattern_Digits(document.frmGen.num_code.value))
	{
		alert("Please enter Valid Pattern Digits");
		document.frmGen.num_code.focus();
		return false;
	}
	
	return true;
}
/*function check()
{
	var cop_nm = document.frmGen.coupan_name.value;
	var inr = document.frmGen.inr.value;
	var num_code = document.frmGen.num_code.value;
	
	if(inr=="")
	{
		alert("Please enter INR");
		document.frmGen.inr.focus();
		return false;
	}
	
	if(num_code=="")
	{
		alert("Please enter no.of Promotion codes you have to generate. ");
		document.frmGen.num_code.focus();
		return false;
	}
	else if(!isValidPattern_Digits(num_code))
	{
		alert("Please enter Valid Pattern Digits");
		document.frmGen.num_code.focus();
		return false;
	}
	return true;
}*/

function isValidPattern_Digits(str){
	var regEx = new RegExp("[^0-9]","ig");
	return !regEx.test(str);
}
</script>
<div style="padding-left:25px;">
<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Manage Promotion Code</div>
<div align="center" style="width:100%">&nbsp;</div>
<form name="frmGen" method="post" action="" onsubmit="return valid();">
<input type="hidden"  name="checkdownload" />
	<table width="60%" align="center" cellpadding="0" cellspacing="0" style="border:thin; border-color:#006699; border-style:solid;">
		<tr>
		  <td width="4%">&nbsp;</td>
			<td width="96%">&nbsp;</td>
		</tr>
		<tr>
		  <td align="left">&nbsp;</td>
		  <td align="left" class="headtitle"><strong>Generate Promotion Code</strong></td>
	  </tr>
		<tr>
		  <td align="left">&nbsp;</td>
			<td align="left">&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>
		  <table width="100%" border="0">

<tr>
			    <td width="3%">&nbsp;</td>

				<td width="34%" align="left" valign="middle" class="tblcont">Coupon Name</td>
				<td width="6%" align="center" valign="middle" class="tblcont">:</td>

			  <td width="57%" align="left" valign="middle"><input type="text" name="coupan_name" maxlength="16" /></td>
		    </tr>

			  

			  <tr>
			    <td>&nbsp;</td>

				<td align="left" valign="middle"  class="tblcont">INR / %</td>
				<td align="center" valign="middle" class="tblcont">:</td>

				<td align="left" valign="middle"><input name="discount_rate" type="text" id="discount_rate" />	

				

				<select name="selINR">

				<option value="INR">INR</option>

				<option value="percent">%</option>
				</select>				</td>
		    </tr>

			  <tr>
			    <td>&nbsp;</td>

				<td align="left" valign="middle" class="tblcont">Validity Date</td>
				<td align="center" valign="middle" class="tblcont">:</td>

				<td align="left" valign="middle"><input type="text" name="strt_date" size="8" onfocus="showCalendarControl(this);" /> To <input type="text" name="end_date"  size="8" onfocus="showCalendarControl(this);" /></td>
		    </tr>

			  <tr>
			    <td>&nbsp;</td>

			    <td align="left" valign="middle" class="tblcont">No. Of Promotion Code</td>
			    <td align="center" valign="middle" class="tblcont">:</td>

			    <td align="left" valign="middle"><input type="text" size="3" name="num_code" maxlength="2" /></td>
		    </tr>

			  <tr>
			    <td colspan="4" align="center" valign="middle"><input type="submit" name="btnGenerate" value="Generate" /></td>
			  </tr>
		  </table>		  </td>
		</tr>
	</table>
</form>
	<hr />
	 <strong>Generated Codes</strong>
	<br />
	<br />
	<div id="code">
	<? echo $code; ?>
	</div>
		<input  type="button" name="print" value="Print" onclick="javascript:window.open('print.php'); return false;" />
	<form name="frmCodes" method="post" action="download.php">	
		<input type="hidden"  name="checkdownload" />
		<input type="hidden" name="code" value="<?=$for_csv;?>"  />
		<input type="hidden" name="valid_from" value="<?=$valid_from_date;?>"  />
		<input type="hidden" name="valid_to" value="<?=$valid_to_date;?>"  />
		<input type="hidden" name="coupon_name" value="<?=$coupon_name;?>"  />
		<input type="submit" name="exportcsv" value="Export To CSV"  onclick="dowmloademail();" />
	</form>
</div>