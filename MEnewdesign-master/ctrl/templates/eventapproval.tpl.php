<script language="javascript" type="text/javascript">
	document.getElementById('ans1').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/includes/javascripts/sortpagi.min.js.gz"></script>
<script language="javascript" type="text/javascript">
	function actv()
	{
			if (confirm('Do you want to Activate Event?') ) 
			{
					var count = 0;
					var chk = 0;
					//alert(document.forms["frmeven"]["chkbox[]"].length);
					for(i=0;i<document.forms["frmeven"]["chkbox[]"].length;i++)
					{
							if(document.forms["frmeven"]["chkbox[]"][i].checked == true)
							{
									chk = chk + 1;
									var val = document.forms["frmeven"]["chkbox[]"][i].value;
									var splt = val.split('_');
									
									if(splt[1] == 1)
									{
											alert('The Event '+splt[2]+' is already activated.. please make it unchecked!!!');
											count = count + 1;
									}
							}
					}
					if(count > 0)
					{
							return false;
					}
					if(chk == 0)
					{
							alert('Please Choose atleast one event to Activate..');
							return false;
					}
					//document.frmeven.act_id.value = id1;
					if(count == 0 && chk > 0)
					{
							document.frmeven.action.value = 'activate';
							document.frmeven.submit();
					}		
					//document.frmeven.uidd.value = uid1;
					//
			} 
			else 
			{
					return false;
			}		
	}
	function dactv()
	{
			if (confirm('Do you want to De-Activate Event?') ) 
			{
					//alert(document.forms["frmeven"]["chkbox[]"].length);
					var cnt = 0;
					var chk1 = 0;
					
					for(k=0;k<document.forms["frmeven"]["chkbox[]"].length;k++)
					{ 
							if(document.forms["frmeven"]["chkbox[]"][k].checked == true)
							{
									chk1 = chk1 + 1; 
									var val = document.forms["frmeven"]["chkbox[]"][k].value;
									var splt = val.split('_');
									
									if(splt[1] == 0)
									{
											alert('The Event '+splt[2]+' is already De-activated.. please make it unchecked!!!');
											cnt = cnt + 1;
									}
							}
					}
					if(cnt > 0)
					{
							return false;
					}
					if(chk1 == 0)
					{
							alert('Please select atleast One event to suspend..');
							return false;
					}
					//document.frmeven.act_id.value = id2;
					if(cnt == 0 && chk1 > 0)
					{
							document.frmeven.action.value = 'deactivate';
							document.frmeven.submit();
					}	
					//document.frmeven.uidd.value = uid2;
					//
			} 
			else 
			{
					return false;
			}		
	}
	function del_eve(delid)
	{
		var confm = confirm("Do You Really want to Delete this event from the site?");
		if(confm)
		{
			document.frmeven.act_id.value = delid;
			document.frmeven.action.value = 'Delete';
			document.frmeven.submit();
			return false;
		}
		else
		{
			return false;
		}
		return true;
	}
</script>	
<div>
	<form action="" method="post" name="frmeven">
	<input type="hidden" name="act_id" value="" />
	<input type="hidden" name="action" value="" />
	<input type="hidden" name="uidd" value="" />
    <div align="center" style="width:100%" class="headtitle">Event Approval</div>
        <div align="center" style="width:100%" class="headtitle">&nbsp;</div>
        <table width="80%" align="center" class="sortable-onload-3r no-arrow colstyle-alt rowstyle-alt paginate-5 max-pages-4 paginationcallback-callbackTest-calculateTotalRating sortcompletecallback-callbackTest-calculateTotalRating">
			<tr>
				<td width="23%" align="center" valign="middle" class="tblcont1">Organiser</td>
				<td width="52%" align="center" valign="middle" class="tblcont1">Event</td>
				<td width="25%" align="center" valign="middle" class="tblcont1"  ts_nosort="ts_nosort">Status</td>
			</tr>
			<?php 
				for($i=0;$i<count($arr_event['uid']);$i++)
				{
			?>
			<tr>
				<td align="center" valign="middle"  class="helpBod"><?=$arr_event['username'][$i]?></td>
				<td colspan="2"  class="helpBod">
					<table width="100%">
					<?php
						for($k=0;$k<count($arr_event['test']);$k++)
						{
							if($arr_event['test'][$k] == $arr_event['uid'][$i])
							{
					?>
						<tr>
							<td width="71%" align="left" valign="middle"  class="helpBod">
								<?php echo $arr_event['title'][$k]; ?>							</td>
							<td width="29%" align="center" valign="middle"  class="helpBod">
								<?php
									if($arr_event['approve'][$k] == 1)
									{
										echo 'ACTIVATED'; 	
									}
									else
									{
										echo 'SUSPENDED';
									}
									
								?>							</td>
							<td align="center" valign="middle"  class="helpBod">
								<input type="checkbox" name="chkbox[]" value="<?=$arr_event['nid'][$k]?>_<?=$arr_event['approve'][$k]?>_<?=$arr_event['title'][$k]?>_<?=$arr_event['test'][$k]?>" />							</td>
                                <td align="center" valign="middle"  class="helpBod">
                                <img src="images/delet.jpg" title="Delete" style="cursor:pointer" onclick="return del_eve(<?=$arr_event['nid'][$k]?>);" /> </td>
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
					<input type="button" name="activate" value="ACTIVATE" onclick="return actv();" />&nbsp;&nbsp;&nbsp;
					<input type="button" name="deactivate" value="SUSPEND" onclick="return dactv();" />
		    </div>
  </form>
</div>