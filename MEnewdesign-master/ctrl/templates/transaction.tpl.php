<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/sortpagi.min.js.gz"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/CalendarControl.min.js.gz"></script>
<style type="text/css">
<!--
.style1 {
	font-size: 17px;
	font-weight: bold;
	color: #FF6600;
}
-->
</style>
<script type="text/javascript">
function ClickHereToPrint(){
    try{ 
        var oIframe = document.getElementById('ifrmPrint');
        var oContent = document.getElementById('report').innerHTML;
        var oDoc = (oIframe.contentWindow || oIframe.contentDocument);
        if (oDoc.document) oDoc = oDoc.document;
		oDoc.write("<html><head><title>MeraEvents</title>");
		oDoc.write("</head><body onload='this.focus(); this.print();'>");
		oDoc.write(oContent + "</body></html>");	    
		oDoc.close(); 	    
    }
    catch(e){
	    self.print();
    }
}
</script>

<div>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Transaction Report</div>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
	<table align="center" width="60%" style="border:thin; border-color:#006699; border-style:solid;">
    	<tr>
        	<td>
           	  <form action="" method="POST">
				
		
				<table width="100%" cellpadding="2" cellspacing="2" style="border:hidden">
	    <tr>
	      <td align="left" valign="middle">&nbsp;</td>
	      <td align="left" valign="middle" class="tblcont">View By</td>
	      <td height="40" align="center" valign="middle" class="tblcont">: </td>
	      <td align="left" valign="middle">
		  <select name="view">
            <option value="All">All</option>
            <option value="Successfull" <?php if($_POST['view'] == "Successfull") { echo "selected=selected";}?> >Successfull</option>
            <option value="Pending" <?php if($_POST['view'] == "Pending") { echo "selected=selected";}?>>Pending</option>
            <option value="Failed" <?php if($_POST['view'] == "Failed") { echo "selected=selected";}?>>Failed</option>
          </select></td>
	      <td align="left" valign="middle" class="tblcont">User Type </td>
	      <td align="center" valign="middle" class="tblcont">:</td>
	      <td align="left" valign="middle"><label>
	        <select name="user_types">
	        <option value="All">All</option>
<?php /**************************commented on 17082009 need to remove afterwords**************************?>			<?php while($types = mysql_fetch_array($sql_res_types)){?>
			<option value="<?=$types['user_type_id']?>"><?php echo $types['name']?></option>
			<?php } ?><?php */?>
			</select>
	      </label></td>
	      </tr>
	    <tr>
	      <td width="4%" align="left" valign="middle">&nbsp;</td>
	      <td width="16%" align="left" valign="middle" class="tblcont">Name</td>
			  <td width="3%" height="40" align="center" valign="middle" class="tblcont"> : </td>
        <td width="27%" align="left" valign="middle"><label>
				  <input name="name" type="text" id="name" />
				</label></td>
			  <td width="15%" align="left" valign="middle" class="tblcont">Email</td>
			  <td width="4%" align="center" valign="middle" class="tblcont"> : </td>
    <td width="31%" align="left" valign="middle"><label>
				  <input name="email" type="text" id="email" />
				</label></td>
			</tr>
			<tr>
			  <td align="left" valign="middle">&nbsp;</td>
			  <td align="left" valign="middle" class="tblcont">Performa No</td>
			  <td height="40" align="center" valign="middle" class="tblcont"> : </td>
			  <td align="left" valign="middle"><label>
				<input name="invoice_no" type="text" id="invoice_no" />
			  </label></td>
			  <td align="left" valign="middle" class="tblcont">Cheque No </td>
			  <td align="center" valign="middle" class="tblcont">: </td>
			  <td align="left" valign="middle"><label>
				<input name="cheque" type="text" id="cheque" />
			  </label></td>
			</tr>
			<tr>
			  <td align="left" valign="middle">&nbsp;</td>
			  <td align="left" valign="middle" class="tblcont">Event Name</td>
			  <td align="center" valign="middle" class="tblcont">:</td>
			  <td align="left" valign="middle"><input name="eve_name" type="text" id="eve_name" /></td>
			  <td align="center" valign="middle" class="tblcont">&nbsp;</td>
			  <td align="center" valign="middle">&nbsp;</td>
			  <td align="left" valign="middle">&nbsp;</td>
			  </tr>
			<tr>
			  <td align="left" valign="middle">&nbsp;</td>
			  <td align="left" valign="middle" class="tblcont">Date Range<br />
mm/dd/yyyy</td>
			  <td align="center" valign="middle" class="tblcont"> : </td>
			  <td align="left" valign="middle"><label>
				<input name="date_from" type="text" id="date_from" onfocus="showCalendarControl(this);"/>
			  </label></td>
			  <td align="center" valign="middle" class="tblcont">-</td>
			  <td align="center" valign="middle">&nbsp;</td>
			  <td align="left" valign="middle"><label>
			  <input name="date_to" type="text" id="date_to" onfocus="showCalendarControl(this);"/>
			  </label></td>
			</tr>
			<tr>
			  <td colspan="7" align="center" valign="middle"><label>
			  <input type="submit" name="Submit" value="Search" />
			  </label></td>
			</tr>
		</table>
  			</form>
            </td>
        </tr>
    </table>

<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Transaction Report</div>		
		<div id="report">
			<table width="70%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-15 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
			<thead>
			  <tr>
				<td class="tblcont1"><div align="center"><strong>Name </strong></div></td>
				<td class="tblcont1"><div align="center"><strong>Date</strong></div></td>
				<td class="tblcont1"><div align="center"><strong>Invoice No</strong></div></td>
				<td class="tblcont1"><div align="center"><strong>User Type</strong></div></td>
				<td class="tblcont1"><div align="center"><strong>Current Status</strong></div></td>
			  </tr>
			 </thead>
			 <?php /**************************commented on 17082009 need to remove afterwords**************************?> <?php while($row = mysql_fetch_array($sql_res)){
			  
					$sql_type = "SELECT * FROM user_types_type WHERE user_type_id=".$row['user_type_id'];	
					$sql_res_type = mysql_query($sql_type) or die("Error in type:".mysql_error());
					$sql_row_type = mysql_fetch_array($sql_res_type);
			  ?><?php */?>
			  <tr>
				<td height="25" class="helpBod"><div align="left"><a href="payment_details.php?invoice=<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['invoice_no']?><?php */?>"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['pname'];?><?php */?></a></div></td>
				<td class="helpBod"><div align="center"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['date'];?><?php */?></div></td>
				<td class="helpBod"><div align="center"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['invoice_no'];?><?php */?></div></td>
				<td class="helpBod"><div align="center"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $sql_row_type['name'];?><?php */?></div></td>
				<td class="helpBod"><div align="center"><i><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['status'];?><?php */?></i></div></td>
			  </tr>
			  
			  <?php /**************************commented on 17082009 need to remove afterwords**************************?><?php }// end of while?><?php */?>
			</table>
		</div>
		<table width="100%" style="border:hidden">
			</td>
		  </tr>
			<tr>
			<td align="right">
			<form action="" method="post">
			   <input type="button" name="button" value="Print" onclick="return ClickHereToPrint();">
			   &nbsp;&nbsp;&nbsp;   
			   <input type = "hidden" name="sql_query" value="<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$sql_for_csv?><?php */?>">
			   <input type="Submit" name="Submit" value="Export As CSV">
			
			   </form>			  </td>
		  </tr>
		 
		</table>


</div>