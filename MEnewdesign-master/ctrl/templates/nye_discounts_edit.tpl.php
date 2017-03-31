<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents - <?php echo ucfirst($btype); ?> Discounts</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
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


                                    <!-------------------------------CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans68').style.display = 'block';


                                        function Trim(str)
                                        {
                                            while (str.charAt(0) == (" "))
                                            {
                                                str = str.substring(1);
                                            }
                                            while (str.charAt(str.length - 1) == " ")
                                            {
                                                str = str.substring(0, str.length - 1);
                                            }
                                            return str;
                                        }




                                        function validateNYEdiscountsForm(f)
                                        {
                                            // alert("yeah");
                                            var title = f.title.value;
                                            var promo_code = f.promo_code.value;
                                            //var eventid = f.eventid.value;



                                            if (Trim(title).length == 0)
                                            {
                                                alert("Please enter discount Title");
                                                document.getElementById('title').focus();
                                                return false;
                                            }
                                            if (Trim(promo_code).length == 0)
                                            {
                                                alert("Please enter discount code");
                                                document.getElementById('promo_code').focus();
                                                return false;
                                            }
                                            /*if (Trim(eventid).length == 0)
                                            {
                                                alert("Please enter Event Id");
                                                document.getElementById('eventid').focus();
                                                return false;
                                            }
                                            else if (isNaN(eventid))
                                            {
                                                alert("Please enter valid Event Id");
                                                document.getElementById('eventid').focus();
                                                return false;
                                            }*/

                                            return true;


                                        }



                                    </script>
                                    <div style="width:100%" align="center">
                                        <table width="50%">
                                            <tr>
                                                <td colspan="2" class="headtitle" align="center"><strong><?php echo ucfirst($returntype); ?> Discounts and Promotions</strong> </td>
                                            </tr>
                                            <!--<tr>
                                              <td colspan="2" align="left"><a href="admin.php" class="menuhead" title="Master management Home">Master Management Home</a></td>
                                            </tr>-->
                                            <tr>
                                                <td colspan="2">
                                                    <form method="post" action="" onsubmit="return validateNYEdiscountsForm(this)">
                                                        <table cellpadding="5" cellspacing="5">
                                                            <tr>
                                                                <td>Discount Title</td>
                                                                <td><input type="text" name="title" id="title" value="<?php echo $title; ?>" size="50" /></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Discount/Promo Code</td>
                                                                <td><input type="text" name="promo_code" id="promo_code" value="<?php echo $promo_code; ?>" size="50" /></td>
                                                            </tr>
                                                            <!--<tr>
                                                                <td>Event Id</td>
                                                                <td><input type="text" name="eventid" id="eventid" value="<?php echo $eventid; ?>" size="50"  /></td>
                                                            </tr>-->
                                                            <input type="hidden" name="eventid" id="eventid" value="<?php echo $eventid; ?>"  />
                                                            
                                                              <tr>
                                                                  <td> City Name</td> 
                                                                <td>
                                                                    <select name="searchCT" id="searchCT">
                                                                        
                                                                        <?php if (isset($cityList) && !empty($cityList)) { ?>
                                                                            <option value="0" <?php if ($_REQUEST['searchCT'] == 'All') { ?> selected="selected" <?php } ?>>All cities</option>
                                                                            <?php foreach ($cityList as $city) { ?>
                                                                                <option value="<?php echo $city['id']; ?>" <?php if ($cityid == $city['id']) { ?> selected="selected" <?php } ?>><?php echo $city['name']; ?></option>
                                                                            <?php }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </td>                   
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2" align="center">
                                                                    <input type="submit" value="Update" />
                                                                    <input type="hidden" name="editid" value="<?php echo $id; ?>" />
                                                                    <input type="hidden" name="nye_discounts_form" value="1" />
                                                                    <input type="hidden" name="returntype" value="<?php echo $returntype; ?>" />
                                                                    <input type="button" value="Cancel" onclick="javascript:window.location = 'newyeardiscounts.php?btype=<?php echo $returntype; ?>';" />
                                                                </td>
                                                            </tr>
                                                            
                                                          
                                                            
                                                            
                                                        </table>
                                                    </form></td>
                                            </tr>

                                        </table>
                                        <div align="center" style="width:100%">&nbsp;</div>
                                    </div>
                                    <!-------------------------------CONTENT PAGE ENDS HERE--------------------------------------------------------------->



                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>