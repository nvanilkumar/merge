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
-->
</style>
<div align="center" style="width:100%">
<div align="center" style="width:100%">&nbsp;</div>
<div>
<p align="left" class="atten">Attention Admin! The following transactions are pending! Please check with your Payment Gateway and change the status to Successful or Failed.</p>
</div>

<div>
<p align="center"><strong>Pending Organiser - New Event </strong></p>
</div>
<div align="center">
	<table width="55%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-10 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
		<tr>
			<td align="center" class="tblcont1"><strong>Organiser Name</strong></td>
			<td align="center" class="tblcont1"><strong>City</strong></td>
			<td align="center" class="tblcont1"><strong>Events Posted</strong></td>
			<td align="center" class="tblcont1"><strong>Status</strong></td>
		</tr>
		<?php
			for($i=0;$i<count($arr_pending['uid']);$i++)
			{
		?>
		<tr>
			<td align="left"  class="helpBod"><?=$arr_pending['name'][$i]?></td>
			<td align="left"  class="helpBod"><?=$arr_pending['city'][$i]?></td>
			<td align="left"  class="helpBod"><?=$arr_pending['events'][$i]?></td>
			<td align="center"  class="helpBod"><strong>Pending</strong></td>
		</tr>
		<?php
			}
		?>
	</table>
</div>
</div>