<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents Menu Content Management</title>
        <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">

                <link rel="stylesheet" type="text/css" media="all" href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?php echo _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script type="text/javascript" language="javascript"  src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
                <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jquery.validate.js"></script>


                <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT; ?>/dashboard/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
                <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT; ?>/dashboard/js/custom/general.js"></script>

                <script src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jquery.oLoader.js"></script>
                </head>	
                <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
                    <?php include('templates/header.tpl.php'); ?>				
                    </div>

                    <table width="103%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">

                        <tr>
                            <td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                                <?php include('templates/left.tpl.php'); ?>	</td>
                            <td width="848" style="vertical-align:top">
                                <div class="isa_success" id="sucess_div" style="display:none; margin-left: 100px; font-size: 14px;">Successfully booked the tickets (
                                    <samp id="event_sigup_id"> </samp>).
                                    <span style="float:right;"><a style=" color:#F00; font-weight:bold; cursor:pointer" onclick="closeAlert('isa_success')">X</a></span>
                                </div>
                                <script type="text/javascript" language="javascript">
                                            setTimeout(function(){$(".isa_success").fadeOut('slow')}, 600);                                </script>
                                <form action="" method="post" name="spot_form" id="spot_form" onsubmit="return false;">
                                    <table width="70%" align="center" class="tblcont">
                                        <tr><td align="center">Entry Form</td></tr>
                                        <tr>
                                            <td >Event Id:&nbsp;</td>
                                            <td><input type="text" name="event_id" id="event_id" value=""  />
                                                <span id="event_id_msg"> </span>
                                                <br/><input type="text" name="event_name" id="event_name" placeholder="Event Name" readonly  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spot Cash <input type="radio" name="spot_type" value="spotcash" checked="true"/> &nbsp;&nbsp; Spot Card <input type="radio" name="spot_type" value="spotcard"/>  

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Ticket Type &nbsp;</td>
                                            <td><select name="ticket_type" id="ticket_type" >
                                                    <option value="">Select the ticket type</option>
                                                </select> 
                                                <span> </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Qty &nbsp;</td><td>
                                                <input name="qty" id="qty"  />

                                                <span> </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="35%" align="left" valign="middle">Amount:&nbsp;</td><td>

                                                <span class="field"><b id="purchse_amount">0.00</b></span><br/>

                                                <input type="hidden" name="ticketamount" id="ticketamount" value="0.00"  readonly/>
                                                <input type="hidden" name="tot_purchse_amount" id="tot_purchse_amount" value="0.00"  readonly/>
                                                <div id="tax_div" style="display:none;">

                                                </div>

                                            </td>
                                        </tr>


                                        <tr> 
                                            <td width="30%" colspan="2"   align="center" valign="middle">
                                                <input type="submit" name="submit" value="Submit"  id="submitButton" /></td>
                                        </tr>
                                    </table>
                                </form>
                                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">


                                    <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->

                                    <script type="text/javascript">

                                                $(function() {
                                                //                      $("#ans5").show(); 

                                                $("#spot_form").validate({
                                                rules: {
                                                event_id:{required: true, number: true},
                                                        ticket_type:{required: true},
                                                        qty:{required: true}
                                                },
                                                        messages: {
                                                        event_id:{required: "Please enter the numeric event id"},
                                                                ticket_type:{required: "Please select the ticket type"},
                                                                qty:{required: "Please select the qty"}
                                                        },
                                                        errorPlacement: function(error, element) {
                                                        error.appendTo(element.next());
                                                        }
                                               });
                                                        var post_url = '<?php echo _HTTP_SITE_ROOT ?>/ctrl/spot_registration.php';
                                                        var post_data;
                                                        //alert("sasasa");
                                                        $("#event_id").focusout(function(){
                                                        var event_id=document.getElementById('event_id').value;
		$.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:event_id}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('event_id').focus();
				return false;
				
			}
		});
                                                        post_data = {call:'event_details', event_id: $(this).val()};
                                                        $.ajax({
                                                        url: post_url,
                                                                type: "POST",
                                                                data: post_data,
                                                                async: false,
                                                                dataType: "json",
                                                                success: function(responseText) {

                                                                markup_select_box("ticket_type", responseText);
                                                                }

                                                        });
                                                });
                                                        $("#ticket_type").change(function(){
                                                $("#qty").val("");
                                                        $("#purchse_amount").html("");
                                                        $("#tot_purchse_amount").val("");
                                                });
                                                        $("#qty").change(function(){
                                                var promo_code = "";
                                                        getPurchaseAmount($(this).val(), promo_code);
                                                });
                                                        $("#submitButton").click(function(e) {
                                                                var status = $("#spot_form").valid();
                        post_url = '<?php echo  _HTTP_SITE_ROOT ?>/ctrl/spot_registration.php';
                                                                post_data = {call:'spot_booking',
                                                                        bookint_type: $('input[name=spot_type]:checked').val(),
                                                                        eventid: $("#event_id").val(),
                                                                        tktid: $("#ticket_type").val(), tktQty: $("#qty").val(),
                                                                        promo_code: "",
                                                                        ticketamount:$("#ticketamount").val(),
                                                                        total_amount: $("#tot_purchse_amount").val(),
                                                                        name: "spot booking", email: "spot_booking", mobileno: ""
                                                                };
                                                                if (status) {
                                                        $.ajax({
                                                        url: post_url,
                                                                type: "POST",
                                                                data: post_data,
                                                                async: true,
                                                                //dataType: "json",
                                                                beforeSend:function(){
                                                                //showLoadingOverlay(); 
                                                                },
                                                                success: function(responseText) {
                                                                $("#spot_form")[0].reset();
                                                                        $("#purchse_amount").html("0.00");
                                                                        if (responseText != '2'){
                                                                $("#sucess_div").show();
                                                                        $("#event_sigup_id").html(responseText);
                                                                } else if (responseText == '2')
                                                                        $("#isa_error").show(100);
                                                                        //                                    $('body').oLoader('hide');
                                                                        $("#tax_div").html();
                                                                }

                                                        });
                                                        }
                               });
                                                });
                                                        function closeAlert(divclass)
                                                        {
                                                        $('.' + divclass).fadeOut('slow');
                                                        }
                                                //To markup the select box
                                                //@param element_name -- domelement name
                                                //@param class_names -- all options values
                                                function markup_select_box(element_name, ticket_names)
                                                {
                                                $('#' + element_name + '  option:gt(0)').remove().end();
                                                        $.each(ticket_names, function(index, value) {

                                                        $('#' + element_name).append($('<option/>', {
                                                        value: value.ticket_id,
                                                                text: value.Name
                                                        }));
                                                        });
                                                        $("#event_name").val(ticket_names[0].Title);
                                                }
                                                //To get the ticket qty min & max values
                                                /*function markup_qty_list_box(element_name, min_qty,max_qty)
                                                 {
                                                 $('#' + element_name + '  option:gt(0)').remove().end();
                                                 //                        var min_qty=ticket_details[0].OrderQtyMin,
                                                 //                            max_qty=ticket_details[0].OrderQtyMax;
                                                 
                                                 for (var i = min_qty; i <= max_qty; i++) {
                                                 $('#' + element_name).append($('<option/>', {
                                                 value: i,
                                                 text: i
                                                 }));
                                                 }
                                                 
                                                 }*/
                                                //To calculate the purchase amount total
                                                function getPurchaseAmount(tqty, promo_code)
                                                {

                                                var chosenTktId = $("#ticket_type").val();
                                                        var chosenEventId = $("#event_id").val();
                                                        $("#tax_div").html("");
                                                        var tickets = inputStr = "";
                                                        var opts = $('#ticket_type')[0].options;
                                                        var ticketsDB = $.map(opts, function(elem) {
                                                        return elem.value;
                                                        });
                                                        for (var t = 0; t < ticketsDB.length; t++)
                                                {
                                                var qty = 0;
                                                        if (ticketsDB[t] != "")
                                                {

                                                if (ticketsDB[t] == chosenTktId){ qty = tqty; }
                                                tickets += "ticketArray[" + ticketsDB[t] + "]=" + qty + "&";
                                                }
                                                }

                                                if (tickets.length > 0){tickets = tickets.slice(0, - 1); }


                                                inputStr = "eventId=" + chosenEventId + "&totQty=" + tqty + "&" + tickets + "&spotBooking=true";
                                                        $.ajax({
                                                        url: "<?php echo _HTTP_SITE_ROOT; ?>/api/event/getTicketCalculation",
                                                                type:"POST",
                                                                data:inputStr,
                                                                datatype:'JSON',
                                                                headers: {
                                                                'Content-Type': 'application/x-www-form-urlencoded',
                                                                        'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
                                                                },
                                                                cache: false,
                                                                async: false,
                                                                success: function(calculationObj){
                                                                var data = calculationObj.response.calculationDetails;
                                                                        var currencyCodeStr = data.currencyCode;
                                                                        if (calculationObj){

                                                                $("#tot_purchse_amount").show();
                                                                        var ticketData = data.ticketsData;
                                                                        var ticketAmount, totaltaxAmount = "";
                                                                        $.each(data.ticketsData, function (key, value) {
                                                                        ticketAmount = value.totalAmount;
                                                                                if (value.taxes){
                                                                        totaltaxAmount = getTaxData(value.taxes, ticketAmount);
                                                                                //                                        $("#tax_div").show().html(taxData);
                                                                        }

                                                                        });
                                                                        $("#tot_purchse_amount").val(data.totalTicketAmount + totaltaxAmount);
                                                                        $("#purchse_amount").text(data.totalTicketAmount + totaltaxAmount);
                                                                        $("#ticketamount").val(ticketAmount);
                                                                }
                                                                }
                                });
                                                }

                                                //To apply the over lay to the body
                                                function showLoadingOverlay(){
                                                $('body').oLoader({
                                                wholeWindow: true,
                                                        backgroundColor: '#fff',
                                                        fadeInTime: 500,
                                                        fadeLevel: 0.5,
                                                        image: '<?php echo _HTTP_CF_ROOT; ?>/images/processing.gif',
                                                        style: 3,
                                                        imagePadding: 5
                                                });
                                                }

                                                function getTaxData(data, ticketAmount){
                                                var taxAmout = 0;
                                                        $.each(data, function (key, value) {
                                                        taxAmout += (ticketAmount * value.value) / 100;
                                                                //apply the tax incremental process
                                                                ticketAmount = ticketAmount + taxAmout;
                                                        });
                                                        return taxAmout;
                                                }
                                    </script>	



                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>