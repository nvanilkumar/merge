<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
            <title>MeraEvents -Menu Content Management</title>
                <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
                <link href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortable.js"></script>	
                <script language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.js"></script>	
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/CalendarControl.css" />
                <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/includes/javascripts/CalendarControl.js"></script>
                <script type="text/javascript" language="javascript" src="<?php echo  _HTTP_CF_ROOT; ?>/ctrl/css/jquery.min.js"></script>
            <script language="javascript">
                function SEdt_validate()
                {
                        var strtdt = document.frmEofMonth.txtSDt.value;
                        var enddt = document.frmEofMonth.txtEDt.value;
                        if(strtdt == '')
                        {
                                alert('Please select Start Date');
                                document.frmEofMonth.txtSDt.focus();
                                return false;
                        }
                        else if(enddt == '')
                        {
                                alert('Please select End Date');
                                document.frmEofMonth.txtEDt.focus();
                                return false;
                        }
                        else //if(strtdt != '' && enddt != '')
                        {   
                                var startdate=strtdt.split('/');
                                var startdatecon=startdate[2] + '/' + startdate[1]+ '/' + startdate[0];

                                var enddate=enddt.split('/');
                                var enddatecon=enddate[2] + '/' + enddate[1]+ '/' + enddate[0];

                                if(Date.parse(enddatecon) < Date.parse(startdatecon))
                                {
                                        alert('End Date must be greater then Start Date.');
                                        document.frmEofMonth.txtEDt.focus();
                                        return false;
                                }
                        }
                }
	</script>
    <link href="<?php echo _HTTP_CF_ROOT; ?>/ctrl/css/jq.css" rel="stylesheet">

	<!-- jQuery: required (tablesorter works with jQuery 1.2.3+) -->
	<script src="css/jquery.min.js"></script>

	<!-- Pick a theme, load the plugin & initialize plugin -->
	<link href="css/theme.default.css" rel="stylesheet">
	<script src="css/jquery.tablesorter.min.js"></script>
	<script src="css/jquery.tablesorter.widgets.min.js"></script>
	<script>
	$(function(){
		$('table').tablesorter({
			widgets        : ['zebra', 'columns'],
			usNumberFormat : false,
			sortReset      : true,
			sortRestart    : true
		});
	});
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
    <form action="" method="post" name="frmEofMonth">
    <table width="60%" align="center" class="tblcont">
	<tr>
	  <td width="33%" align="left" valign="middle">Event Added Start Date:&nbsp;
	    <input type="text" name="txtSDt" id="txtSDt" value="<?php echo $SDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
	  <td width="28%" align="left" valign="middle">Event Added End Date:&nbsp;
	    <input type="text" name="txtEDt" id="txtEDt" value="<?php echo $EDt; ?>" size="8" onfocus="showCalendarControl(this);" /></td>
    	  
	</tr>
    

        <tr><td width="25%" align="center" colspan="2" valign="middle"><input type="submit" name="submit" value="Show Report" onclick="return SEdt_validate();" /></td></tr>
    </table>
</form>
	<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans22').style.display='block';
</script>
<table align="center" class="tablesorter" style="margin: 10px 0 -1px; width:90%"  border='1' cellpadding='0' cellspacing='0' >
			<thead>
                            <?php if(isset($_SESSION['nodata'])){?>
                            <div style="color:green;">No data found.</div>
                            <?php }?>
            <tr bgcolor='#94D2F3'>
		  	<td class='tblinner' valign='middle' width='5%' align='center'>Sr. No.</td>
			<td class='tblinner' valign='middle' width='5%' align='center'>Event Organizer</td>
			<td class='tblinner' valign='middle' width='5%' align='center'>User City</td>
            <td class='tblinner' valign='middle' width='25%' align='center'>Event Name & ID</td>  
            <td class='tblinner' valign='middle' width='5%' align='center'>Event City</td>                     
			<td class='tblinner' valign='middle' width='10%' align='center'>Category</td>
			<td class='tblinner' valign='middle' width='10%' align='center'>Status</td>
           <td class='tblinner' valign='middle' width='10%' align='center'>Organizer</td>
            
          
          </tr>
        </thead>
		
                    <?php $cnt = 0; if(count($ResOrg)>0){	foreach($ResOrg as $key=>$value)   // iterating grouped array for displaying data
	{ ?><tr>
			<td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?php echo ++$cnt;?></font></td>                     
			<td class='tblinner' valign='middle'  align='left'><font color='#000000'><?php echo $value['Organizer']."<br/>".$value['OrgEmail'];?></font></td>   
            <td class='tblinner' valign='middle'  align='left'><font color='#000000'><?php echo $value['UserCity'];?></font></td>	
            <td class='tblinner' valign='middle'  align='center' ><font color='#000000'><?php echo $value['EventName']."(".$value['EventId'].")";?></font></td>			
            <td class='tblinner' valign='middle' width='5%' align='center'><?php echo $value['City'];?></td>
			<td class='tblinner' valign='middle' width='5%' align='center'><?php echo $value['Category'];?></td>
			<td class='tblinner' valign='middle' width='5%' align='center'><?php echo ($value['status']==1)?'Published':'UnPublished';?></td>
                        
            <td class='tblinner' valign='middle'  align='center'><font color='#000000'><?php echo get_org_status($value['ownerid'],$fromDt);?></font></td>
             </tr>
          <!--  (minus) 2 is done to remove the extra totalqty and totalfees index while counting items in array-->
                       
                    <?php	}}else{?>
          <tr>
                        <td class='tblinner' valign='middle'  colspan=6 align='center' ><font color='#000000'>No records found</font></td>
                         </tr>
                 <?php }?>
           
         


		 
		

	


  <tr>


  
  
  </table>
  <br /><br /><br />
<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
  
</table>
	</div>	
</body>
</html>
