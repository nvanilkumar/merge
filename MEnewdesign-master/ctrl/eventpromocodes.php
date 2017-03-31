<?php
session_start();
include 'loginchk.php';
include_once 'MT/cGlobali.php';
$Globali=new cGlobali();


//print_r($mtvXPromoCodeEvent);
$eventid=(isset($_GET['eventid']))?$_GET['eventid']:NULL;
$editId=(isset($_GET['editId']))?$_GET['editId']:0;

if(strlen($eventid) > 0)
{
	$sqlAdd= "and eventid= '".$eventid."' ";
}


    
    function delete($table,$deleteId){
        global $Globali;
        $table=$Globali->realEscape($table);
        $deleteId=$Globali->realEscape($deleteId);
       $deleteQuery="DELETE FROM ".$table."
                    WHERE Id='".$deleteId."'
                    AND `MembershipType` in('productnation-summit-2013-scale-hacking-stage-boot','productnation-summit-2013')";
     //   echo $deleteQuery;
        $deleteSucess=$Globali->ExecuteQuery($deleteQuery);
        
    };
    function update($table, $setFieldName, $seTo,$where){
        global $Globali;
        $table=$Globali->realEscape($table);
        $setFieldName=$Globali->realEscape($setFieldName);
        $seTo=$Globali->realEscape($seTo);
        $where=$Globali->realEscape($where);
        
        $updateQuery="UPDATE ".$table."
                    SET ".$setFieldName."=value1,column2=value2,...
                    WHERE some_column=some_value";
        
        
    }
    


//$tes=new updateTES();
if(isset($_POST['insertForm']))
{
	$eventid=trim($_POST['eventid']);
	$promoCode=trim($_POST['promoCode']);
	$promoCodeCount=trim($_POST['promoCodeCount']);
	
	if($editId > 0)
	{
		//$sql="update `vivo_bms` (`eventid`, `bookingid`, `alloted`) VALUES ('".$eventid."', '".$promoCode."', '".$promoCodeCount."')";
	}
	else
	{
		$sql="INSERT INTO `eventpromocodes` (`eventid`, `promocode`, `totalquantity`) VALUES ('".$eventid."', '".$promoCode."', '".$promoCodeCount."')";
	}
	
	
	$insertSucess=$Globali->ExecuteQuery($sql);
	$_SESSION['inserted']=true;
}
if(isset($_REQUEST['deleteId']))
{
    delete('onlyfor_tes', $_REQUEST['deleteId']);
}
    
$displayQuery="select `id`, `eventid`,`promocode` bookingid,`soldquantity` count,`totalquantity` alloted,`cts` from `eventpromocodes` where 1 $sqlAdd order by `id` DESC";
//echo $displayQuery;
$promoCodeDataDB=$Globali->SelectQuery($displayQuery);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - MTV events</title>
	<link href="<?=_HTTP_SITE_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css"/>
	<link href="<?=_HTTP_SITE_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css"/>
	<script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/ctrl/css/sortable.js"></script>	
	<script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/ctrl/css/sortpagi.js"></script>
    <script src="<?=_HTTP_SITE_ROOT;?>/js/jquery.1.7.2.min.js"></script>
    <script>
	function validateForm(f)
	{
		var promoCode=jsTrim(f.promoCode.value);
		var promoCodeCount=jsTrim(f.promoCodeCount.value);
		
		var errCount=0;
		
		if(promoCode.length==0 || promoCode==null)
		{
			errCount++;
			alert("Please enter promo code");
			f.promoCode.focus();
			return false;
		}
		else if(promoCode.length < 6)
		{
			errCount++;
			alert("Promo code length should be more than five charecters.");
			f.promoCode.focus();
			return false;
		}
		
		
		if(promoCodeCount.length==0 || promoCodeCount==null)
		{
			errCount++;
			alert("Please enter the availability of promo code");
			f.promoCodeCount.focus();
			return false;
		}
		else if(isNaN(promoCodeCount) || promoCodeCount == 0)
		{
			errCount++;
			alert("Should be a number and greater than zero");
			f.promoCodeCount.focus();
			return false;
		}
		
		
		return true;
	}
	
	
	function jsTrim(str) {
		str = str.toString();
		var begin = 0;
		var end = str.length - 1;
		while (begin <= end && str.charCodeAt(begin) < 33) { ++begin; }
		while (end > begin && str.charCodeAt(end) < 33) { --end; }
		return str.substr(begin, end - begin + 1);
	}
	
	function searchEvents(eventid)
	{
		if(eventid > 0){ window.location = '?eventid='+eventid; }
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
<!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<div align="center" style="width:100%">



<table width="60%" border="0" cellpadding="3" cellspacing="3">
	<tr>
        <td align="center" colspan="2" valign="middle" class="headtitle"><strong>MTV Events</strong> </td>
    </tr>
    
    
    <?php
	if(isset($_SESSION['inserted']))
	{
		?><tr><td colspan="2" valign="middle" class="headtitle"><b style="color:#090">New record inserted successfully..</b> </td></tr><?php
		unset($_SESSION['inserted']);
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
        	<legend><b>Add/Update Promo Code</b></legend>
        	<table>
            	<tr><td>Event ID</td><td>Promo Code</td><td>No. Of Codes</td></tr>
                <tr>
                	<td>
                    <select name="eventid" id="eventid">
                    	<?php
						foreach($mtvXPromoCodeEvent as $eventiddb=>$cfid)
						{
							?><option value="<?php echo $eventiddb; ?>" <?php if($eventiddb == $eventid){echo "selected"; } ?> ><?php echo $eventiddb; ?></option><?php
						}
						?>
                    </select>
                    </td>
                    <td><input type="text" name="promoCode" id="promoCode" value="" /></td>
                    <td><input type="text" name="promoCodeCount" id="promoCodeCount" value="" /></td>
                    <td><input type="hidden" name="editId"  value="<?php echo $editId; ?>" />
                    <input type="submit" name="insertForm"  value="Submit" /></td>
                </tr>
            </table>
            </fieldset>
        </form>
        
        
        </td>
    </tr>
    
    
    <tr><td colspan="2"><br /></td></tr>
    <tr>
    	<td colspan="2">
        	
                <table width="60%" border="0" cellpadding="3" cellspacing="3">
                      <tr>
                        <td  colspan="2" valign="middle" class="headtitle"><strong>Available Promo Codes</strong> </td>
                      </tr>
                      
                      <tr>
                      <td>Search by Event
                      <select namne="eventid" id="eventid" onchange="searchEvents(this.value)">
                      	<option value="0">Select Event</option>
                      	<?php
						foreach($mtvXPromoCodeEvent as $eventiddb=>$cfid)
						{
							?><option value="<?php echo $eventiddb; ?>" <?php if($eventiddb == $eventid){echo "selected"; } ?> ><?php echo $eventiddb; ?></option><?php
						}
						?>
                      </select>
                      </td>
                      </tr>
                      
                      <tr>
                        <td colspan="2"><table width="100%" class="sortable" >
                          <thead>
                          <tr>
                            <td class="tblcont1">Event Id</td>
                            <td class="tblcont1">Promo Code</td>
                            <td class="tblcont1">Total Allocated</td>
                            <td class="tblcont1">Used</td>
                             <!--<td class="tblcont1" ts_nosort="ts_nosort">Edit </td>
                           <td class="tblcont1" ts_nosort="ts_nosort">Delete</td>-->
                          </tr></thead>
                        <?php	
                        $flag=0;							  
                        for($i = 0; $i < count($promoCodeDataDB); $i++)
                        {
                        ?>
                          <tr>
                            <td class="helpBod"><?php echo $promoCodeDataDB[$i]['eventid']; ?></td>
                            <td class="helpBod"><?php echo $promoCodeDataDB[$i]['bookingid']; ?></td>
                            <td class="helpBod"><?php echo $promoCodeDataDB[$i]['alloted']; ?></td>
                            <td class="helpBod"><?php echo $promoCodeDataDB[$i]['count']; ?></td>
                            <!--<td class="helpBod"><a href="mtvPromoCodes.php?editId=<?php echo $promoCodeDataDB[$i]['id']; ?>">Edit</a></td>
                            <td class="helpBod">
                            	<form action="" method="post" name="edit_form">
                                <input type="hidden" name="delCurrency" value="<?php echo $promoCodeDataDB[$i]['id']; ?>" />
                                <input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete this Currency.\n\nThe Changes Cannot Be Undone');">	
                                </form>
                            </td>-->
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


