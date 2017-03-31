<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/sortpagi.min.js.gz"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/CalendarControl.min.js.gz"></script>
<div>
<div align="center" style="width:100%" class="headtitle">Payment Approval</div>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<table align="center" width="60%" style="border:thin; border-color:#006699; border-style:solid;">
	<tr>
	  <td align="center">&nbsp;</td>
    </tr>
	<tr>
    	<td>
        <form action="" method="POST">
			<table width="90%" align="center" cellpadding="3" cellspacing="3">
	<tr>
		<td width="21%" align="left" valign="middle">
			View By :      </td>
		<td width="31%" align="left" valign="middle">
		  <label></label>	    <select name="view">
            <option value="All">All</option>
            <option value="Successfull" <?php if($_POST['view'] == "Successfull") { echo "selected=selected";}?> >Successfull</option>
            <option value="Pending" <?php if($_POST['view'] == "Pending") { echo "selected=selected";}?>>Pending</option>
            <option value="Failed" <?php if($_POST['view'] == "Failed") { echo "selected=selected";}?>>Failed</option>
        </select></td>
	    <td width="48%" align="left" valign="middle"><input type="Submit" name="Submit" value="List"/></td>
	</tr>
</table>
		</form>		</td>
    </tr>
     <tr>
     	<td>
		<form action="" method="POST" >
			<table width="90%" align="center" cellpadding="3" cellspacing="3">
	<tr>
	  <td width="28%" align="left" valign="middle">Name</td>
		<td width="5%" align="center" valign="middle"> : </td>
	    <td width="24%" align="left" valign="middle"><label>
	      <input name="name" type="text" id="name" />
	    </label></td>
	    <td width="19%" align="left" valign="middle">Email : </td>
	    <td width="24%" align="left" valign="middle"><label>
	      <input name="email" type="text" id="email" />
	    </label></td>
	</tr>
	<tr>
	  <td align="left" valign="middle">Performa No</td>
	  <td align="center" valign="middle"> : </td>
	  <td align="left" valign="middle"><label>
	    <input name="invoice_no" type="text" id="invoice_no" />
	  </label></td>
	  <td align="left" valign="middle">Cheque No : </td>
	  <td align="left" valign="middle"><label>
	    <input name="cheque" type="text" id="cheque" />
	  </label></td>
    </tr>
	<tr>
	  <td align="left" valign="middle">Date Range(mm/dd/yyyy)</td>
	  <td align="center" valign="middle"> : <br></td>
	  <td align="left" valign="middle"><label>
	    <input name="date_from" type="text" id="date_from" onfocus="showCalendarControl(this);"/>
	 </label></td>
	  <td colspan="2" align="left" valign="middle"><input name="date_to" type="text" id="date_to" onfocus="showCalendarControl(this);"/></td>
	  </tr>
	<tr>
	  <td colspan="5" align="center" valign="middle"><label>
	  <input type="submit" name="Submit" value="Search" />
	  </label></td>
    </tr>
</table>
		</form>        </td>
    </tr>
</table>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Approval For Cheque Payments</div>
<div align="center" style="width:100%" class="headtitle">&nbsp;</div>
<table width="80%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-3 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
<thead>
  <tr>
    <td width="10%" height="28" class="tblcont1"><div align="center"><strong>Name </strong></div></td>
    <td width="30%"class="tblcont1"><div align="center"><strong>Details</strong></div></td>
    <td width="7%" class="tblcont1"><div align="center"><strong>Qty</strong></div></td>
    <td width="9%" class="tblcont1"><div align="center"><strong>Amount</strong></div></td>
	<td width="16%" class="tblcont1"><div align="center"><strong>Current Status</strong></div></td>
    <td width="28%" ts_nosort="ts_nosort" class="tblcont1"><div align="center" style="color: #333333; font-weight:bold">Mark Status As</div></td>
  </tr>
  </thead>
<?php /**************************commented on 17082009 need to remove afterwords**************************?>  <?php while($row = mysql_fetch_array($sql_res)){?>
<?php */?>  <tr>
    <td height="25" align="left" valign="middle" class="helpBod"><div align="left"><a href="payment_details.php?invoice=<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['invoice_no']?><?php */?>"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['name'];?><?php */?></a></div></td>
    <td align="left" valign="middle" class="helpBod"><div align="left"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['details'];?><?php */?></div></td>
    <td class="helpBod"><div align="center"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['quantity'];?><?php */?></div></td>
    <td class="helpBod"><div align="left"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['amount'];?><?php */?></div></td>
	<td class="helpBod"><div align="center"><i><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $row['status'];?><?php */?></i></div></td>
    <td class="helpBod"><div align="center">
		<a href="status_payment_approval.php?task=success&&tid=1<?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$row['tid']?><?php */?>">Successfull</a>&nbsp;|&nbsp;
		<a href="status_payment_approval.php?task=fail&&tid=1<?php /*?><?=$row['tid']?><?php */?>">Failed</a>&nbsp;|&nbsp;
		<a href="status_payment_approval.php?task=pending&&tid=1<?php /*?><?=$row['tid']?><?php */?>">Pending</a>
	</div></td>
  </tr>
<?php /**************************commented on 17082009 need to remove afterwords**************************?>  <?php }// end of while?>
<?php */?></table>
<div style="width:100%" align="center"><?php /**************************commented on 17082009 need to remove afterwords**************************?><?php echo $projectpage->getPageLinks(); ?><?php */?></div>
</div>
