<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
        <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
        <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>
        <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
        <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT;?>/js/public/jquery.validate.js"></script>
        <script>
            $(function(){
                 var select_box_status="<?php echo $select_box_status ?>";
                 if(select_box_status === "disabled"){
                     $("#payment_status_select_box").hide();
                 }else{
                     $("#PaymentStatus").hide();
                     form_validation();
                     
                 }
                 $("#payment_status_select_box").change(function(){
                     $("#PaymentStatus").val($(this).val());
                 });

                 
                 
                 
                
            });
        function form_validation(){
                             $("#frmEofMonth").validate({
                rules: {
                    PaymentTransId: {
                        required: true
                    },
                    payment_status_select_box: {
                        required: true
                    }
                },
                 messages: {
                     PaymentTransId: {
                         required: "Please enter the transaction id"
                     },
                     payment_status_select_box: {
                         required: "Please select the Payment Status"
                     }

                 },
                 errorPlacement: function(error, element) {
                     error.appendTo(element.next());
                 }

           });
            
        }    
            
        
        function cancelp()
        {
            window.location = "CheckReg.php?regid=<?php echo  $_REQUEST[regid]; ?>&email=<?php echo  $_REQUEST[email]; ?>&recptno=<?php echo  $_REQUEST[recptno]; ?>&transid=<?php echo  $_REQUEST[transid]; ?>";
        }
        </script>
        <style>
         span label {color:#EC0C0C}
        </style>

<!--        <link rel="stylesheet" type="text/css" media="all" href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
        <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>-->

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
                    <?php 
                    if(!in_array($TransactionRES[0]['event_id'],$event_ids)){?>
                        <form action="" method="post" name="frmEofMonth" id="frmEofMonth" onsubmit="false;">
                        <table width="50%" align="center" class="tblcont" cellpadding="3" cellspacing="3">

                            <tr><td width="35%">Event Details. <?php echo  $TransactionRES[0][Title]; ?></td></tr>
                            <tr><td width="35%">Receipt No. <?php echo  $TransactionRES[0][Id]; ?></td></tr>
                            <tr><td width="35%">Signup date. <?php echo  $TransactionRES[0][SignupDt]; ?></td></tr>
                            <tr><td width="35%">Qty. <?php echo  $TransactionRES[0][Qty]; ?></td></tr>
                            <tr><td width="35%">Fees. <?php echo $TransactionRES[0]['currencyCode'] . ' ' . ($TransactionRES[0][Qty] * $TransactionRES[0][Fees]); ?></td></tr>
                            <tr><td width="35%">PaymentTransId. 
                                    <input type="text" name="PaymentTransId" id="PaymentTransId" value="<?php echo  $TransactionRES[0][PaymentTransId]; ?>"  <?php echo  $input_status ?>/> <span> </span>   </td></tr>
                            <tr><td width="35%">PaymentStatus. 
                                    <input type="text" name="PaymentStatus" id="PaymentStatus" value="<?php echo  $TransactionRES[0][PaymentStatus]; ?>"  <?php echo  $input_status ?>/>    
                                 
                                    <select name="payment_status_select_box" id="payment_status_select_box"> 
                                        <option value="">Select Option</option>
                                        <option value="success"  >Successful</option>
                                    </select>
                                <span> </span>
                                </td></tr>
                            <tr><td width="35%">PaymentGateway. <select name="PaymentGateway" id="PaymentGateway" <?php echo  $select_box_status ?> >
                                        <?php for($p = 0; $p < count($querypaymentGatewaysRes); $p++)
                                        {  ?>
                                        <option value="<?php echo $querypaymentGatewaysRes[$p]['id']; ?>" <?php if ($TransactionRES[0]['PaymentGateway'] == $querypaymentGatewaysRes[$p]['id']) { ?> selected="selected" <?php } ?>><?php echo $querypaymentGatewaysRes[$p]['name']; ?></option>
                                        <?php }?>                                       
                                    </select>
                                    <tr><td colspan="3"><input type="submit" name="submit" value="Save"  <?php echo $select_box_status;?>/>&nbsp;&nbsp;<input type="button" name="cancel" value="Cancel" onclick="cancelp();"  /></td></tr>
                                    <!-- Added on 03-08-2016 by gautam to prevent updating the soldcount on saving the success transactions -->
                                    </table>
                                    </form>
                    <?php }else{?>
                    <div style="margin:10px;"> <br/><br/>You are not authorized to change this transaction,<br/>
                        Please send a mail to DevSupport (devsupport@meraevents.com) <br/><br/>
                        <input type="button" name="back" value="Back" onclick="cancelp();"  />
                    </div>
                    <?php }?>
                    

                                </td>
                            </tr>
                        </table>
                        </div>	
                        </body>
</html>