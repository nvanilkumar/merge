<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?php echo _HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
<!-------------------------------ADD CITY PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
    function validate_city()
    {
                                        var eventid = document.getElementById('EventId').value;
                                if (eventid.length == 0)
                                {
                                    alert("Please enter a Event Id");
                                    document.getElementById('EventId').focus();
                                    return false;
                                } else if (isNaN(eventid) || eventid <= 0) {
                                    alert("Please enter valid Event Id");
                                    document.getElementById('EventId').focus();
                                    return false;
                                } else {
                                    $.get('includes/ajaxSeoTags.php', {eventIDChk: 0, eventid: eventid}, function (data) {
                                        if (data == "error")
                                        {
                                            alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                                            document.getElementById('EventId').focus();
                    return false;

                }
            });
        }
            if (document.add_city.EventId.value == "")
            {
                alert("Please Enter Event-Id");
                document.add_city.EventId.focus();
                return false;
            }
            if (document.add_city.Label.value == '')
            {
                alert("Please Enter Label");
                document.add_city.Label.focus();
                return false;
            }
            if (document.add_city.Amount.value == '')
            {
                alert("Please Enter Amount");
                document.add_city.Amount.focus();
                return false;
            } else {

                var numeric = document.add_city.Amount.value;
                var regex = /^(?:[1-9]\d*|0)?(?:\.\d+)?$/;

                if (!regex.test(numeric)) {
                    alert("Please Enter Valid Amount");
                    document.add_city.Amount.focus();
                    return false;
                }
            }
            if (document.add_city.Type.value == '2')
            {
                if (document.add_city.ex_currency.value == '0') {
                    alert("Please select a currency type");
                    document.add_city.ex_currency.focus();
                    return false;
                }
            }
            $.post('<?php echo _HTTP_SITE_ROOT ?>/ctrl/processAjaxRequests.php', {call: 'isEventIdExists', event_id: document.add_city.EventId.value}, function (data) {
                var newData = jQuery.parseJSON(data);
                if (newData.eventExists === false) {
                    alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                    document.getElementById('EventId').focus();
                    return false;
                }
            });
            return true;
        }
        function getCurrency(type) {
            if (type == 1) {
                document.getElementById('tr_currency').style.display = 'none';
            }
            else if (type == 2) {
                document.getElementById('tr_currency').style.display = '';
            }
        }
</script>

<form action="" method="post" name="add_city" onSubmit="return validate_city();">
    <table width="50%" border="0" cellpadding="3" cellspacing="3">
        <tr>
            <td colspan="2"><strong>Add Extra Charges</strong></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Event-Id : </td>
            <td><label>
                    <input type="text" name="EventId" id="EventId" maxlength="80" value="<?php echo $EventId; ?>" />
                </label></td>
        </tr>
        <tr>
            <td>Label : </td>
            <td><label>
                    <input type="text" name="Label" maxlength="80" />
                </label></td>
        </tr>
        <tr>
            <td>Amount : </td>
            <td><label>
                    <input type="text" name="Amount" maxlength="80" />
                </label></td>
        </tr>
        <tr>
            <td>Type : </td>
            <td><label>
                    <select name="Type" id="Type" onchange="getCurrency(this.value)">
                        <option value="1">Percentage</option>
                        <option value="2">Flat</option>  
                    </select>
                </label></td>
        </tr>
        <tr id="tr_currency" style="display: none;">
            <td>Currency : </td>
            <td><label>
                    <select name="ex_currency" id="ex_currency">
                    <?php for ($t = 0; $t < count($currencyList); $t++) { ?>
                        <option value="<?php echo $currencyList[$t]['id']; ?>" ><?php echo $currencyList[$t]['name'] . "(" . $currencyList[$t]['code'] . ")" ?></option>
                    <?php } ?>
                    </select>
                </label></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><label>
                    <input type="submit" name="Submit" value="Add" />
                </label></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>
<div><?php echo $msgStateCityExist; ?></div>
<!-------------------------------ADD CITY PAGE ENDS HERE--------------------------------------------------------------->
		</div>
            </td>
	</tr>
    </table>
</div>	
</body>
</html>