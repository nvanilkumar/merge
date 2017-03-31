<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/sortpagi.min.js.gz"></script>
<style type="text/css">
<!--
.atten {
	color: #FF0000;
	font-weight: bold;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
}
.style1 {
	color: #000000;
	font-weight: bold;
}
-->
</style>
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div >
<p align="left" class="atten">Attention Admin! The following transactions are pending! Please check with your Payment Gateway and change the status to Successful or Failed.</p>
</div>

<div >
<p align="center" ><strong>Pending User Event Registrations</strong></p>
</div>

<div align="center">
	<table width="60%" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
		<thead><tr>
		  <td width="24%" align="center" class="tblcont1"><div align="center"><strong>User Name</strong></div></td>
			<td width="16%" align="center" class="tblcont1"><div align="center"><strong>City</strong></div></td>
		  <td width="38%" align="center" class="tblcont1"><div align="center"><strong>Events Registered</strong></div></td>
		  <td width="22%" align="center" ts_nosort="ts_nosort" class="tblcont1"><div align="center" class="style1">Status</div></td>
	  </tr></thead>
		<?php
			for($i=0;$i<count($arr_pending['uid']);$i++)
			{
		?>
		<tr>
			<td align="left" class="helpBod">
			  <div align="left" style="width:100%">
			    <?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$arr_pending['name'][$i]?><?php */?>
	          </div></td>
			  <td align="left" class="helpBod">
			  <div align="left" style="width:100%">
			    <?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$arr_pending['city'][$i]?><?php */?>
	          </div></td>
			<td align="left" class="helpBod">
			  <div align="left" style="width:100%;">
			    <?php /**************************commented on 17082009 need to remove afterwords**************************?><?=$arr_pending['details'][$i]?><?php */?>
	          </div></td>
			<td align="center" class="helpBod"><div align="center"><strong>Pending</strong></div></td>
		</tr>
		<?php
			}
		?>
	</table>
</div>
</div>