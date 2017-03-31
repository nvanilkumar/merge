<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css"/>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css"/>
         <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>  
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>	
                <link rel="stylesheet" type="text/css" media="all" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script src="<?= _HTTP_SITE_ROOT ?>/ctrl/includes/javascripts/jquery.1.7.2.min.js"></script>
                <script language="javascript">
                    function SEdt_validate()
                    {
                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
                        if (strtdt != '' && enddt != '')
                        {
                            var startdate = strtdt.split('/');
                            var startdatecon = startdate[2] + '/' + startdate[1] + '/' + startdate[0];

                            var enddate = enddt.split('/');
                            var enddatecon = enddate[2] + '/' + enddate[1] + '/' + enddate[0];

                            if (Date.parse(enddatecon) < Date.parse(startdatecon))
                            {
                                alert('End Date must be greater then Start Date.');
                                document.frmEofMonth.txtEDt.focus();
                                return false;
                            }
                            var event_id =document.getElementById('eventIdSrch').value;
                            if(event_id.length==0){
                                alert('Please enter event id');
                                return false;
                            }
                            else if (isNaN(event_id)  || event_id<=0) {
                                alert("Please enter valid event id");
                                return false;
                            }else{
		$.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:event_id}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('eventIdSrch').focus();
				return false;
				
			}
		});
		
	}
                        }
                    }
                     

                </script>
                </head>	
                <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
                    <?php include('templates/header.tpl.php'); ?>				
                    </div>
                    <table width="103%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
                        <tr>
                            <td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                                <?php include('templates/left.tpl.php'); ?>	</td>
                            <td width="848" style="vertical-align:top">
                                <form action="" method="post" name="frmEofMonth">
                                    <table width="70%" align="center" class="tblcont">
                                        <tr>
                                            <td width="35%" align="left" valign="middle">Start Date:&nbsp;<input type="text" name="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
                                            <td width="35%" align="left" valign="middle">End Date:&nbsp;<input type="text" name="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>

                                        </tr>


                                        <tr><td>Event Id:&nbsp;<input type="text" name="eventIdSrch" id="eventIdSrch" value="<?= $EventId; ?>" /></td>
                                            <td>  </td></tr>


                                        <tr> <td width="30%" colspan="2" style="padding:10px;" align="center" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
                                    </table>
                                </form>
                                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">


                                    <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans4').style.display = 'block';
                                    </script>

                                    <!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
                                    
                                    <?php
                                        if(count($EventQueryRES) > 0){ ?>
                                            <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                        <thead>
                                            <tr bgcolor="#94D2F3">
                                                <td class="tblinner" valign="middle" width="3%" align="center">Sr. No.</td>
                                                <td class="tblinner" valign="middle" align="center">Event Signup Id</td>
                                                <td class="tblinner" valign="middle" align="center">Event Details</td>
                                                <td class="tblinner" valign="middle"   align="center">Qty</td>
                                                <td class="tblinner" valign="middle"   align="center">TotalTransAmount</td>
                                                <td class="tblinner" valign="middle"   align="center">SettlementDate </td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php for($j = 0; $j < count($EventQueryRES); $j++) {  ?>
                                             <tr>
                                                <td align="right"><?= $j+1; ?> </td>
                                                <td align="right"><?=$EventQueryRES[$j]['id'] ?> </td>
                                                <td align="right"><?=$EventQueryRES[$j]['eventid'].":". $EventQueryRES[$j]['Details'] ?> </td>
                                                <td align="right"><?=$EventQueryRES[$j]['Qty'] ?> </td>
                                                <td align="right"><?=$EventQueryRES[$j]['Fees'] *$EventQueryRES[$j]['Qty'] ?> </td>
                                                <td align="right"> 
                                                <?php 
                                                    echo $common->convertTime($EventQueryRES[$j]['SettlementDate'] ,DEFAULT_TIMEZONE,TRUE);        
                                                ?> 
                                                </td>
                                            </tr>
                                         
                                            <?php    }  ?>
                                           


                                        </tbody>
                                    </table>
                                            
                                    <?php    }
                                    ?>
                                    


                                </div>
                                                </td>
                                            </tr>
                                    </table>
                                    </div>	
                                    </body>
                                    </html>