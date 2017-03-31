<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Currency Conversion</title>
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<script>
function validateEventSignupIDForm(form)
{ 
    var esid=document.getElementById('esid').value;
    if(Trim(esid).length==0)
	{
		alert("Please enter Event Signup/Registration ID");
		document.getElementById('esid').focus();
		return false;
	}
	else
	{
		$.get('<?php echo _HTTP_SITE_ROOT; ?>/ctrl/processAjaxRequests.php',{currConversionESIDchk:1,esid:esid}, function(data){
			if(data==1)
			{
				window.location='<?php echo _HTTP_SITE_ROOT; ?>/ctrl/currencyConversion.php?esid='+esid+'&convert=true';
			}
			else if(data==2)
			{
				if(window.confirm("Conversion value already updated for this Registration.\n Would you like to update again...?"))
				{
					window.location='<?php echo _HTTP_SITE_ROOT; ?>/ctrl/currencyConversion.php?esid='+esid+'&convert=true&again=true';
				}
				else
				{
					document.getElementById('esid').focus();
					return false;
				}
			}
			else if(data==3 || data==4)
			{
				alert("Sorry, You can't convert INR/FREE transactions");
				document.getElementById('esid').focus();
				return false;
			}
			else if(data==5)
			{
				alert("Sorry, we did not find this Event Signup/Registration ID.");
				document.getElementById('esid').focus();
				return false;
			}
                        else if(data==6)
			{
				alert("Sorry, you cannot convert incomplete Event Signup/Registration ID.");
				document.getElementById('esid').focus();
				return false;
			}
		});
		
	}
        
	
        
	return false;
}


function Trim(str)
{   
      while(str.charAt(0) == (" ") )
      {  
           str = str.substring(1);
      }
      while(str.charAt(str.length-1) == " " )
      {  
           str = str.substring(0,str.length-1);
      }
      return str;
}

function calculateAmount(rate,amount)
{
	if(rate=="" || rate==null){document.getElementById("covertedValue").innerHTML="";}
	else if(isNaN(rate)){document.getElementById("covertedValue").innerHTML="";}
	else
	{
		var result=eval(rate)*eval(amount);
		document.getElementById("covertedValue").innerHTML=result.toFixed(2)+" INR";
	}
   
}

function validateCurrConversionForm()
{
	var rate=document.getElementById("crate").value;
	if(rate=="" || rate==null)
	{
		alert("Please enter conversion rate");
		document.getElementById("crate").focus();
		return false;
	}
	else if(isNaN(rate))
	{
		alert("Please enter valid conversion rate");
		document.getElementById("crate").focus();
		return false;
	}
	return true;
}




</script>   
</head>    
<body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
    <?php include('templates/header.tpl.php'); ?>                
    </div>
    <table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                <?php include('templates/left.tpl.php'); ?>
            </td>
            <td style="vertical-align:top">
                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------SEO types PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans4').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Currency Conversion</div>
<div>

<?php
if(isset($_SESSION['currConversion']))
{
	?><p><b style="color:#090">Currency conversion rate updated successfully..</b></p><?php
	unset($_SESSION['currConversion']);
}
?>
<form action="#"  onsubmit="return validateEventSignupIDForm()">
	<table>
    	<td>Event Signup/Registration ID</td><td><input type="text" name="esid" id="esid" value="<?php echo $esid; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
</div>


<div id="seodataform" style="display:<?php if(strlen($esid)>0 || isset($_REQUEST['convert']))echo 'block';else echo 'none'; ?>">
<table style="display:<?php if(strlen($esid)>0  )echo 'block';else echo 'none'; ?>">
	<tr><th><br />Event Signup Details</th>
	<tr>
    	<td>Event Title</td><td><?php echo stripslashes($edidESdata[0]['Title']); ?></td>
    </tr>
    <tr>
    	<td>Event Signup Date</td><td><?php echo date('d-M-Y, h:i:s A',strtotime($edidESdata[0]['SignupDt'])); ?></td>
    </tr>
    <tr>
    	<td>Event URL</td><td><a href="<?=_HTTP_SITE_ROOT;?>/event/<?php echo $edidESdata[0]['URL']; ?>" target="_blank"><?=_HTTP_SITE_ROOT;?>/event/<?= $edidESdata[0]['URL']; ?></a></td>
    </tr>
    <tr><th><br /></th>
</table>


<form method="post" action="" onsubmit="return validateCurrConversionForm()">
<table>
    <tr>
    	<td>Total Paid </td>
        <td>
		<?php 
		if($edidESdata[0]['paypal_converted_amount']>0)
		{
			$totalAmt= $edidESdata[0]['paypal_converted_amount']*$edidESdata[0]['Qty'];
			$currency="USD";
			echo $totalAmt." ".$currency;
		}
		else
		{
			$totalAmt= $edidESdata[0]['Fees']*$edidESdata[0]['Qty'];
			$currency=$edidESdata[0]['currencyCode'];
			echo $totalAmt." ".$currency;
		}
		
		 
		?>
        </td>
    </tr>
	<tr>
    	<td>Conversion Rate (per unit)</td><td><input type="text" name="crate" id="crate"  value="<?php if($_GET['again']){echo $edidESdata[0]['conversionRate']; } ?>" onkeyup="calculateAmount(this.value,<?php echo $totalAmt; ?>)" /></td>
    </tr>
    <tr><td>Converted Value</td><td><b id="covertedValue" style="color:#069">
	<?php 
	if($_GET['again'])
	{ 
		if($edidESdata[0]['paypal_converted_amount']>0){ echo $edidESdata[0]['paypal_converted_amount']*$edidESdata[0]['Qty']*$edidESdata[0]['conversionRate']." INR"; }
		else{ echo $edidESdata[0]['Fees']*$edidESdata[0]['Qty']*$edidESdata[0]['conversionRate']." INR"; }
		
	}?></b>
    
    </td></tr>
    <tr>
    	<td colspan="2">
            <input type="hidden" name="updateConversion" value="1" />
            <input type="hidden" name="esid" value="<?php echo $_REQUEST['esid']; ?>" />
            <input type="submit" value="UPDATE" />
        </td>
    </tr>
</table>
</form>

</div>





<div align="center" style="width:100%">&nbsp;</div>

<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------SEO types PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
