<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title>MeraEvents -Menu Content Management</title>
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.css" />
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/js/public/jQuery.js"></script>
<script language="javascript"> 
	/*function TransStatus(sId)
	{
            var strtdt = document.frmEofMonth.txtSDt.value;
            var CityId = document.frmEofMonth.CityId.value;
            window.location="addedevents.php?value=change&txtSDt="+strtdt+"&CityId="+CityId;
	}*/
        function submitFilter()
        {
            if (document.frmEofMonth.txtSDt.value == "") {
                alert('Please select a date.');
                document.frmEofMonth.txtSDt.focus();
                return false;
            }
            else {
                var reqTxtSDt = "";
                var reqCountry = "";
                var reqState = "";
                var reqCity = "";
                if (document.frmEofMonth.CountryId.value != 0) {
                    reqCountry = "&CountryId=" + document.frmEofMonth.CountryId.value;
                }
                if (document.frmEofMonth.StateId.value != 0) {
                    reqState = "&StateId=" + document.frmEofMonth.StateId.value;
                }
                if (document.frmEofMonth.CityId.value != 0) {
                    reqCity = "&CityId=" + document.frmEofMonth.CityId.value;
                }
                reqTxtSDt = "txtSDt=" + document.frmEofMonth.txtSDt.value;
                
                var targetPage = "addedevents.php?" + reqTxtSDt + "" + reqCountry + "" + reqState + "" + reqCity;
                window.location = targetPage;
                return false;
            }
        }
        // Change state/city values	
	function getState(country)
	{
            $.ajax({
                url: "<?php echo _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                type: "POST",
                data: "call=getStates&countryId="+country,
                success: function (msg) {
                    if (msg == "ERROR!") {
                        alert("No States!");
                    }
                    else {
                         $("#StateId").html(msg);
                     }
                }
            });
	}
        function getCity(state)
	{
            $.ajax({
                url: "<?php echo _HTTP_SITE_ROOT; ?>/ctrl/ajax.php",
                type: "POST",
                data: "call=getCities&stateId="+state,
                success: function (msg) {
                    if (msg == "ERROR!") {
                        alert("No Cities!");
                    }
                    else {
                        $("#CityId").html(msg);
                    }
                }
            });
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
	
	<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>


 
<form action="" method="post" name="frmEofMonth" >
<?php echo $msg;?>
<table width="98%" border="0" cellpadding="3" cellspacing="3" >
    <tr>
        <td colspan="6"><strong>Refine Search</strong> </td>
    </tr>  
    <tr>
        <td width="8%"> Country :</td>
        <td width="24%"><label>
                <select name="CountryId" id="CountryId" onchange="getState(this.value)" >
            <option value="">-Select-</option>
            <?php 
                    $TotalCityQueryRES = count($countryQueryRES);

                    for($i=0; $i < $TotalCityQueryRES; $i++)
                    {
                    ?>
             <option value="<?php echo $countryQueryRES[$i]['id'];?>" <?php  if($countryQueryRES[$i]['id']==$_REQUEST['CountryId']){?> selected="selected" <?php  }?>><?php echo $countryQueryRES[$i]['name'];?></option>
             <?php  }?>
          </select>
        </label>
        </td>
        <td width="10%"> State :</td>
        <td width="22%"><label>
                <select name="StateId" id="StateId" onchange="getCity(this.value)" >
            <option value="">-Select-</option>
            <?php 
                if (isset ($StateQueryRES)) {
                    $TotalStateQueryRES = count($StateQueryRES);
                    for($i=0; $i < $TotalStateQueryRES; $i++)
                    {
                    ?>
                    <option value="<?php echo $StateQueryRES[$i]['id'];?>" <?php  if($StateQueryRES[$i]['id']==$_REQUEST['StateId']){?> selected="selected" <?php  }?>><?php echo $StateQueryRES[$i]['name'];?></option>
            <?php  
                    }
                }?>
          </select>
        </label>
        </td>
        <td width="11%"> City  :</td>
        <td width="25%"><label>
          <select name="CityId" id="CityId" >
            <option value="">-Select-</option>
            <?php 
                if (isset ($CityQueryRES)) {
                    $TotalCityQueryRES = count($CityQueryRES);
                    for($i=0; $i < $TotalCityQueryRES; $i++)
                    {
                    ?>
                    <option value="<?php echo $CityQueryRES[$i]['id'];?>" <?php  if($CityQueryRES[$i]['id']==$_REQUEST['CityId']){?> selected="selected" <?php  }?>><?php echo $CityQueryRES[$i]['name'];?></option>
            <?php  
                    }
                }?>
          </select>
        </label>
        </td>
    </tr>  
    <tr>
        <td colspan="6">
            <table width="50%" align="left" class="tblcont">
              <tr>
                  <td width="35%" align="left" valign="middle">Added Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
              </tr>
          </table>
        </td>
    </tr>
    <tr>
        <td width="19%" align="left" valign="middle">
            <input type="submit" name="submit" value="Show report" onclick="return submitFilter();" />
        </td>
        <td>
            <strong>Total Events</strong> :<?php echo $rows;?>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table width="100%" border="1" cellspacing="2" cellpadding="2">
                <tr>
                    <td width="5%" align="center"><strong>S No</strong></td>
                    <td valign="middle" align="center"><strong>Events</strong></td>
                     <td width="10%" align="center"><strong>Preview</strong></td>
                     <td width="25%" align="center"><strong>Organizer</strong></td>
                </tr>
                <?php
                    $TotalEventsOfMonth = count($EventsOfMonth);

                    for($i=0; $i < $TotalEventsOfMonth; $i++)
                    {

                    /*$sqlo="SELECT `name` AS FirstName,company AS Company, email AS Email,mobile AS Mobile FROM user where Id=".$EventsOfMonth[$i]['UserID'];
                    $r=$Global->SelectQuery($sqlo);*/
                    $org=$EventsOfMonth[$i]['FirstName']."<br/>".$EventsOfMonth[$i]['Company']."<br/>".$EventsOfMonth[$i]['Email']."<br/>".$r[0][Mobile];

                    //$window_url = _HTTP_SITE_ROOT."/dashboard/event/edit/".$EventsOfMonth[$i]['Id']."/".$EventsOfMonth[$i]['UserID']; 
					$window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$EventsOfMonth[$i]['UserID']."&eventid=".$EventsOfMonth[$i]['Id'].'&adminId='.$uid;
                    $eventUrl=_HTTP_SITE_ROOT."/event/".$EventsOfMonth[$i]['URL'];
                    if(substr_count($EventsOfMonth[$i]['OEmails'],"@meraevents.com")>=1){
                        $inner='bgcolor="#66FF66"';
                    }
                    else{
                        $inner='';
                    }        
                ?>
                <tr <?php echo $inner;?>>
                    <td align="center"><?php echo $i+1;?></td>
                    <td><a href="#" onclick="window.open('<?php echo $window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');"><?php echo $EventsOfMonth[$i][Title];?></a></td>
                    <td align="center"><a href="#" onclick="window.open('<?php echo $eventUrl?>','mywindow1','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">Clickhere</a></td>
                    <td align="center">
                        <?php echo $org;?>
                    </td>
                </tr>
                <?php  }?>
                <tr>
                  <td colspan="5" align="right"><?php echo $pagination?></td>
                </tr>  
            </table>
        </td>
    </tr>
</table>
</form>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
</div>
</td>
</tr>
</table>
	</div>	
</body>
</html>