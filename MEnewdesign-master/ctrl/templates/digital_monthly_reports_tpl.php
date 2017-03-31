<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents Menu Content Management</title>
     
                 
    <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">

   <link rel="stylesheet" type="text/css" media="all" href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
   <script type="text/javascript" language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
    
    <script type="text/javascript" language="javascript"  src="<?php echo _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
    <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT;?>/js/public/jquery.validate.js"></script>

    <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT;?>/dashboard/js/plugins/jquery-ui-1.8.16.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT;?>/dashboard/js/custom/general.js"></script>
    <link href="data_table_excel/css/dataTables.tableTools.css" rel="stylesheet" />
    <link href="data_table_excel/css/jquery.dataTables.css" rel="stylesheet" />

    <script src="data_table_excel/jquery.dataTables.js"></script>
    <script src="data_table_excel/dataTables.tableTools.js"></script> 
    <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jquery.oLoader.js"></script>
    
    </head>	
    <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
    <?php include('templates/header.tpl.php'); ?>				
    </div>
    
    <table width="103%" cellpadding="0" cellspacing="0" style="width:100%; height:495px;">
       
    <tr>
    <td width="150" style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
    <?php include('templates/left.tpl.php'); ?>	</td>
    <td width="848" style="vertical-align:top">
        <div class="isa_success" id="sucess_div" style="display:none; margin-left: 100px; font-size: 14px;">Successfully booked the tickets.
                <span style="float:right;"><a style=" color:#F00; font-weight:bold; cursor:pointer" onclick="closeAlert('isa_success')">X</a></span>
        </div>
            
<!--        <form action="" method="post" name="report_form" id="report_form">-->
    <table width="70%" align="center" class="tblcont">
        <tr><td align="center" colspan="4">Transaction Reports </td></tr>
        <tr>
            <td >Start Date:&nbsp;</td>
            <td><input type="text" name="start_date" id="start_date" value="<?php echo $start_date; ?>" onfocus="showCalendarControl(this);" />
                <span id="start_date_msg"> </span>
                
            </td>
            <td >End Date:&nbsp;</td>
            <td><input type="text" name="end_date" id="end_date" value="<?php echo $end_date; ?>"  onfocus="showCalendarControl(this);" />
                <span id="end_date_msg"> </span>
                
            </td>
            <td >Report Type:&nbsp;</td>
            <td><select name="report_type" id="report_type" >
                  <option value="Transacted Users" selected="selected" >Transacted Users</option>
                  <option value="Only Registered Users" >Only Registered Users</option>
                </select>
             </td>
            
        </tr>
         
        <tr> 
             <td width="30%" colspan="2"   align="center" valign="middle">
             <input type="submit" name="submit" value="Submit"  id="submitButton" /></td>
         </tr>
      </table>
                 
                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
         
                                         
                                            <table width="100%" border="1" cellpadding="0" cellspacing="0" id="data-table" >
                                        <thead>
                                            <tr bgcolor="#94D2F3">
<!--                                                <td class="tblinner" valign="middle" width="3%" align="center">Sr. No.</td>-->
                                                <td class="tblinner" valign="middle"   align="center">Name</td>
                                                <td class="tblinner" valign="middle"   align="center">Email</td>
                                                <td class="tblinner" valign="middle"   align="center">Phone</td>
                                                <td class="tblinner" valign="middle"   align="center">Ticket Qty </td>
                                                <td class="tblinner" valign="middle"   align="center">Paid Amount </td>
                                                <td class="tblinner" valign="middle"   align="center">Event City </td>
                                                <td class="tblinner" valign="middle"   align="center">User City </td>
                                                <td class="tblinner" valign="middle"   align="center">Event State </td>
                                                <td class="tblinner" valign="middle"   align="center">User State </td>
                                                <td class="tblinner" valign="middle"   align="center">Event Category </td>
                                            </tr>
                                        </thead>
 
                                    </table>
                                            
                                     


                    <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->

                    <script>
                    $(function() {
                         $("#ans25").show(); 
//                         $("#data-table").hide();
                          $("#submitButton2").click(function(e) {
                                           
 
                            post_url = '<?= _HTTP_SITE_ROOT ?>/ctrl/digital_monthly_reports.php';
                            post_data = {call:'get_reports',
                                         start_date: $('#start_date').val(), 
                                         end_date: $("#end_date").val(),
                                         report_type: $("#report_type").val() 
                                        };
                            $.ajax({
                                url: post_url,
                                type: "POST",
                                data: post_data,
                                async: false,
                                beforeSend:function(){ 
                                  //  showLoadingOverlay(); 
                                },
                                success: function(responseText) {

                                    console.log(responseText);
                                }

                            });
                    });
                         
                         
                        var oTable2 = $('#data-table').DataTable( {
                        dom: 'T<"clear">lfrtip',
                        "oTableTools": {
                                        "sSwfPath" : "data_table_excel/swf/copy_csv_xls_pdf.swf"
                                        },
                        "lengthMenu": [[10, 25, 50, 100, 10000], [10, 25, 50, 100, 10000]],                
                        "bProcessing": true,
                        "bServerSide": true,
                        "sAjaxSource": "<?= _HTTP_SITE_ROOT ?>/ctrl/digital_monthly_reports.php" ,
                        "fnServerParams": function ( aoData ) {
                            aoData.push( { "name": "start_date", "value": $("#start_date").val() },{ "name": "end_date", "value": $("#end_date").val() },{ "name": "report_type", "value": $("#report_type").val() } );
}
                        });
                        $("#data-table_filter").hide();
                        $("#submitButton").click(function(e) {
                           oTable2.search(1).draw();
                        });
                        
                    });
                         
                    </script>	
                </div>
                </td>
            </tr>
    </table>
    </div>	
    </body>
</html>