<script language="javascript" type="text/javascript">
	document.getElementById('ans1').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/sortpagi.min.js.gz"></script>
<script language="javascript" type="text/javascript">
	function actv()
	{
			if (confirm('Do you want to make selected event as popular?') ) 
			{
				var count = 0;
				var chk = 0;
							
					for(i=0;i<document.forms["frmeven"]["chkbox[]"].length;i++)
					{
							if(document.forms["frmeven"]["chkbox[]"][i].checked == true)
							{
									chk = chk + 1;
							}
					}
					if(chk == 0)
					{
							alert('Please Choose atleast one event to Mark as Popular..');
							return false;
					}
					if(count == 0 && chk > 0)
					{
							document.frmeven.action.value = 'popular';
							document.frmeven.submit();
					}	
			} 
			else 
			{
					return false;
			}
		
							
	}
</script>	
<div>
	<form action="" method="post" name="frmeven">
	<input type="hidden" name="act_id" value="" />
	<input type="hidden" name="action" value="" />
	<input type="hidden" name="uidd" value="" />
    <div align="center" style="width:100%" class="headtitle">Popular Events</div>
        <div align="center" style="width:100%" class="headtitle">&nbsp;</div>
        <table width="80%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-3 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
			<tr>
				<td width="15%" align="center" valign="middle" class="tblcont1">Organiser</td>
				<td width="43%" align="center" valign="middle" class="tblcont1">Event</td>
				<td width="14%" align="center" valign="middle" class="tblcont1"  ts_nosort="ts_nosort">Status</td>
				<td width="13%" align="center" valign="middle" class="tblcont1"  ts_nosort="ts_nosort">Logo Image</td>
				<td width="15%" align="center" valign="middle" class="tblcont1"  ts_nosort="ts_nosort">Mark as popular event</td>
			</tr>
			<?php 
				for($i=0;$i<count($arr_event['uid']);$i++)
				{
			?>
			<tr>
				<td align="center" valign="middle"  class="helpBod"><?=$arr_event['username'][$i]?></td>

				<td colspan="4"  class="helpBod">
					<table width="100%">
					<?php
						for($k=0;$k<count($arr_event['test']);$k++)
						{
							if($arr_event['test'][$k] == $arr_event['uid'][$i])
							{
					?>
						<tr>
							<td width="50%" align="left" valign="middle"  class="helpBod">
								<?php echo $arr_event['title'][$k]; ?>							</td>
							<td width="17%" align="center" valign="middle"  class="helpBod"><?php
									if($arr_event['approve'][$k] == 1)
									{
										echo 'ACTIVATED'; 	
									}
									else
									{
										echo 'SUSPENDED';
									}
									
								?></td>
							<td width="16%" align="center" valign="middle"  class="helpBod">
					<img src="<?=_HTTP_SITE_ROOT;?>/event_lightbox/logo_image/<?=$arr_event['nid'][$k]?>.jpg" 
					height="50px" width="50px" title="<?php echo $arr_event['title'][$k]; ?>" style="cursor:pointer">
							</td>
							<td width="17%" align="center" valign="middle"  class="helpBod">
<?php /**************************commented on 17082009 need to remove afterwords**************************?>								<?php 
									$sel_pop = "SELECT * FROM popular_events WHERE nid = '".$arr_event['nid'][$k]."' ";
									$qry_pop = mysql_fetch_array(mysql_query($sel_pop));
									$checked = $qry_pop['status'];
								?>
                                <input type="checkbox" name="chkbox[]"
                                 value="<?=$arr_event['nid'][$k]?>_<?=$arr_event['approve'][$k]?>_<?=$arr_event['title'][$k]?>_<?=$arr_event['test'][$k]?>" 
								 <?php if($arr_event['approve'][$k] != 1){?> disabled="disabled" <?php } ?> 
								 <?php if($checked == '1') { ?> checked="checked" <?php } ?>   />
									                              <?php */?>
																  </td>
                        </tr>
					<?php	
							}	
						}
					?>
					</table>				</td>
			</tr>
			<?php		
				}
			?>
		</table>
        <div align="center" style="width:100%;">
					<input type="button" name="activate" value="Submit" onClick="return actv();" />
      </div>
  </form>
</div>