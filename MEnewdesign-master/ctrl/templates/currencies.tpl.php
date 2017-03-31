<!doctype html>
<html>
<head>
        <meta charset="utf-8" />
	<title>MeraEvents -Master Management - Currency Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
    <script src="<?=_HTTP_CF_ROOT;?>/js/public/jQuery.js"></script>
    <script>
	function validateForm(f)
	{
		var currName=jsTrim(f.currName.value);
		var currCode=jsTrim(f.currCode.value);
		
		var errCount=0;
		
		if(currName.length==0 || currName==null)
		{
			errCount++;
			alert("Please enter currency name");
			f.currName.focus();
			return false;
		}
		if(currCode.length==0 || currCode==null)
		{
			errCount++;
			alert("Please enter currency code");
			f.currCode.focus();
			return false;
		}
		
		
		
		if(errCount==0)
		{
			var res=0;
			var inputString='checkCurrency=1&currCode='+currCode;
                          url: "<?=_HTTP_SITE_ROOT;?>/processAjaxRequests.php";
			  $.ajax({
			  url:url,
			  type:"post",
			  data:inputString,
			  cache: false,
			  async: false,
			  success: function(result){
				  if(result=='success')
				  {
					  return true;
				  }
				  else
				  {
					  res=1;
					  
				  }
			  }
			});
		}
		if(res>0)
		{
			alert("Invalid/Duplicate Currency code");
			f.currCode.focus();
			return false;
		}
		
		
		//return true;
	}
	
	
	function jsTrim(str) {
		str = str.toString();
		var begin = 0;
		var end = str.length - 1;
		while (begin <= end && str.charCodeAt(begin) < 33) { ++begin; }
		while (end > begin && str.charCodeAt(end) < 33) { --end; }
		return str.substr(begin, end - begin + 1);
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">

<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<div align="center" style="width:100%">



<table width="60%" border="0" cellpadding="3" cellspacing="3">
	<tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>Currencies</strong> </td>
    </tr>
    
    
    <?php
	if(isset($_SESSION['currencyUp']))
	{
		?><tr><td colspan="2" valign="middle" class="headtitle"><b style="color:#090">Currency details updated successfully..</b> </td></tr><?php
		unset($_SESSION['currencyUp']);
	}
	if(isset($_SESSION['currencyDel']))
	{
		?><tr><td colspan="2" valign="middle" class="headtitle"><b style="color:#090">Currency details deleted successfully..</b> </td></tr><?php
		unset($_SESSION['currencyDel']);
	}
	?>
    
    <tr>
    	<td colspan="2">
        <form method="post" action="" onsubmit="return validateForm(this)">
        <fieldset>
        	<legend>Add/Update Currency</legend>
        	<table>
            	<tr><td>Currency Name</td><td>Currency Code</td><td>Currency Symbol</td></tr>
                <tr>
                    <td><input type="text" name="currName" id="currName" value="<?php echo $currName; ?>" /></td>
                    <td><input type="text" name="currCode" id="currCode" value="<?php echo $currCode; ?>" /></td>
                    <td><input type="text" name="currSymbol" id="currSymbol" value="<?php echo $currSymbol; ?>" /></td>
                    <td><input type="hidden" name="currId"  value="<?php echo $currId; ?>" /><input type="submit" name="currFrmSub"  value="Submit" /></td>
                </tr>
            </table>
        </form>
        </fieldset>
        
        </td>
    </tr>
    
    
    <tr><td colspan="2"><br /></td></tr>
    <tr>
    	<td colspan="2">
        	
                <table width="60%" border="0" cellpadding="3" cellspacing="3">
                      <tr>
                        <td  colspan="2" valign="middle" class="headtitle"><strong>Available Currencies</strong> </td>
                      </tr>
                      
                      <tr>
                        <td colspan="2"><table width="100%" class="sortable" >
                          <thead>
                          <tr>
                            <td class="tblcont1">Currency</td>
                            <td class="tblcont1">Code</td>
                            <td class="tblcont1">Symbol</td>
                            <td class="tblcont1" ts_nosort="ts_nosort">Edit </td>
                            
                          </tr></thead>
                        <?php	
                        $flag=0;							  
                        for($i = 0; $i < count($CurrData); $i++)
                        {
                        ?>
                          <tr>
                            <td class="helpBod"><?php echo $CurrData[$i]['name']; ?></td>
                            <td class="helpBod"><?php echo $CurrData[$i]['code']; ?></td>
                            <td class="helpBod"><?php echo $CurrData[$i]['symbol']; ?></td>
                            <td class="helpBod"><a href="currencies.php?id=<?php echo $CurrData[$i]['id']; ?>">Edit</a></td>
                            
                          </tr>
                        <?php 
                        }
                        ?>
                        </table></td>
                      </tr>
                      
                    </table>
             </form>
        </td>
    </tr>
    
</table>





<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>