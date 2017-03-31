<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - Admin Panel - Events of the Month</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script type="text/javascript" language="javascript"  src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>                                            
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
                                    <!-------------------------------Events of the Month PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans6').style.display = 'block';
                                    </script>
                                    <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.min.css.gz" />
                                    <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
                                    <script language="javascript">
                                        function getXMLHTTP()
                                        {
                                            //fuction to return the xml http object
                                            var xmlhttp = false;
                                            try
                                            {
                                                xmlhttp = new XMLHttpRequest();
                                            }
                                            catch (e)
                                            {
                                                try
                                                {
                                                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                                }
                                                catch (e)
                                                {
                                                    try
                                                    {
                                                        req = new ActiveXObject("Msxml2.XMLHTTP");
                                                    }
                                                    catch (e1)
                                                    {
                                                        xmlhttp = false;
                                                    }
                                                }
                                            }
                                            return xmlhttp;
                                        }

                                        function submitseat(Id)
                                        {
                                            var req = getXMLHTTP();

                                            var Seatno = document.getElementById('Seatno' + Id).value;
                                            var Price = document.getElementById('Price' + Id).value;
                                            var Type = document.getElementById('Type' + Id).value;
                                            var Status = document.getElementById('Status' + Id).value;


                                            strURL = '<?php echo _HTTP_SITE_ROOT; ?>/ctrl/updateseats.php?Id=' + Id + '&Status=' + Status + '&Type=' + Type + '&Price=' + Price + '&Seatno=' + Seatno;
                                            //alert(strURL);
                                            if (req)
                                            {
                                                req.onreadystatechange = function ()
                                                {
                                                    if (req.readyState == 4)
                                                    {
                                                        // only if "OK"
                                                        if (req.status == 200)
                                                        {
                                                            alert("Successfully Saved!!!!");
                                                        }
                                                        else
                                                        {
                                                            alert("Error connecting to network:\n" + req.statusText);
                                                        }
                                                    }
                                                }
                                                req.open("GET", strURL, true);
                                                req.send(null);
                                            }

                                        }
                                        function validateForm() {
                                            var eventid = document.getElementById('EventId').value;
                                            if (eventid>0)
                                            {
                                                $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: eventid}, function (data) {
                                                    if (data == "error")
                                                    {
                                                        alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                                        document.getElementById('EventId').focus();
                                                        return false;

                                                    }
                                                });
                                            }
                                        }

                                    </script>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <div align="center" style="width:100%" class="headtitle">Events Seats</div>
                                    <div align="center" style="width:100%">&nbsp;</div>
                                    <form action="" method="post" name="frmEofMonth" enctype="multipart/form-data">
                                        <table width="500" align="left" class="tblcont">
                                            <tr>
                                                <td width="35%" align="left" valign="middle">Event-Id:&nbsp;<input type="text" name="EventId" id="EventId" value="<?php echo $_REQUEST['EventId']; ?>" size="8"  /></td>
                                                <td>Type:-  <input type="text" name="Type" id="Type" value="<?php echo $_REQUEST['Type']; ?>" size="8"  /> </td>
                                                <td width="30%" align="left" valign="middle"><input type="submit" name="submit" value="Show Events" onclick="return validateForm();" /></td>
                                            </tr>
                                        </table>





                                        <?php if (count($EventsOfMonth) > 0) { ?>
                                            <table width="100%" align="center" >
                                                <tr>

                                                    <?php
                                                    $cnt = 0;
                                                    for ($i = 0; $i < count($EventsOfMonth); $i++) {
                                                        $cnt++;
                                                        ?>
                                                        <td>
                                                            <table width="10" border="1" cellspacing="0" cellpadding="0">

                                                                <input type="hidden" name="VenueSeatsId" value="<?php echo $EventsOfMonth[$i]['Id']; ?>"  />
                                                                <input type="hidden" name="EventId" value="<?php echo $EventsOfMonth[$i]['EventId']; ?>"  />

                                                                <tr height="5px" bgcolor="#CCCCCC">

                                                                    <td width="5" align="left"  valign="middle"  >GP</td><td align="left" valign="middle"  ><?php echo $EventsOfMonth[$i]['GridPosition'] ?></td></tr>
                                                                <tr height="5px">
                                                                    <td width="5" align="left" valign="middle" >SN</td><td align="left" valign="middle" ><input size="2" type="text" name="Seatno<?php echo $EventsOfMonth[$i]['Id']; ?>" value="<?php echo $EventsOfMonth[$i]['Seatno']; ?>" id="Seatno<?php echo $EventsOfMonth[$i]['Id']; ?>"  /></td></tr>
                                                                <tr height="5px"> 	
                                                                    <td width="5" align="left" valign="middle" >P</td><td align="left" valign="middle" ><input size="2"  type="text" name="Price<?php echo $EventsOfMonth[$i]['Id']; ?>" value="<?php echo $EventsOfMonth[$i]['Price']; ?>" id="Price<?php echo $EventsOfMonth[$i]['Id']; ?>"  /></td> 	
                                                                </tr><tr height="5px">
                                                                    <td width="5" align="left" valign="middle" >T</td><td align="left" valign="middle" >
                                                                        <input type="text" name="Type<?php echo $EventsOfMonth[$i]['Id']; ?>" id="Type<?php echo $EventsOfMonth[$i]['Id']; ?>" value="<?php echo $EventsOfMonth[$i]['Type']; ?>" size="8"  />

                                                                    </td>
                                                                </tr><tr height="5px">
                                                                    <td width="5" align="left" valign="middle" >St</td>  <td align="left" valign="middle" >
                                                                        <select name="Status<?php echo $EventsOfMonth[$i]['Id']; ?>" id="Status<?php echo $EventsOfMonth[$i]['Id']; ?>" style="width:50px;">
                                                                            <option value="Booked" <?php if ($EventsOfMonth[$i]['Status'] == "Booked") { ?> selected="selected" <?php } ?>>Booked</option>
                                                                            <option value="Reserved" <?php if ($EventsOfMonth[$i]['Status'] == "Reserved") { ?> selected="selected" <?php } ?>>Reserved</option>
                                                                            <option value="Available" <?php if ($EventsOfMonth[$i]['Status'] == "Available") { ?> selected="selected" <?php } ?>>Available</option>
                                                                            <option value="InProcess" <?php if ($EventsOfMonth[$i]['Status'] == "InProcess") { ?> selected="selected" <?php } ?>>InProcess</option>
                                                                        </select> 
                                                                    </td>
                                                                </tr><tr height="5px">
                                                                    <td width="5" align="left" valign="middle" >Act</td><td align="left" valign="middle"><input type="button" onclick="submitseat(<?php echo $EventsOfMonth[$i]['Id']; ?>);" name="Save" value="Save" /> </td>

                                                                </tr>

                                                            </table>

                                                        </td>
        <?php
        if ($cnt == $EventsCtn[0][ct]) {
            echo "</tr><tr>";
            $cnt = 0;
        }
    } //ends for loop
    ?>
                                                </tr>
                                            </table>
                                                    <?php
                                                } //ends if condition
                                                else if (count($EventsOfMonth) == 0) {
                                                    ?>
                                            <table width="90%" align="center">
                                                <tr>
                                                    <td width="100%" align="left" valign="middle">No match record found.</td>
                                                </tr>
                                            </table>
    <?php
}
?>

                                    </form>

                                    <!-------------------------------Events of the Month PAGE ENDS HERE--------------------------------------------------------------->

                                </div>
                            </td>
                        </tr>
                    </table>

                </body>
                </html>
