<script language="javascript" type="text/javascript">
	document.getElementById('ans1').style.display='block';
</script>
<script language="javascript" type="text/javascript">
		function approved(id,uid,titl)
		{
				document.frmeventcancel.action.value = 'approve';
				document.frmeventcancel.nodeid.value = id;
				document.frmeventcancel.usrid.value = uid;
				document.frmeventcancel.title.value = titl;
				document.frmeventcancel.submit();
		}		
		function declined(idd,uidd,title2)
		{
				document.frmeventcancel.action.value = 'decline';
				document.frmeventcancel.nodeid.value = idd;
				document.frmeventcancel.usrid.value = uidd;
				document.frmeventcancel.title.value = title2;
				document.frmeventcancel.submit();
		}
</script>
<div align="center" style="width:100%">
		<form name="frmeventcancel" action="" method="post">
		<input type="hidden" name="action" value="">
		<input type="hidden" name="nodeid" value="">
		<input type="hidden" name="usrid" value="">
		<input type="hidden" name="title" value="">
        <div align="center" style="width:100%">&nbsp;</div>
                <div align="center" style="width:100%" class="headtitle">Event Cancelation</div>
                <div align="center" style="width:100%">&nbsp;</div>
				<table width="80%" align="center" class="sortable">
						<tr>
								<td width="21%" height="23" align="center" class="tblcont1"><strong>Organiser</strong></td>
								<td width="38%" align="center" class="tblcont1"><strong>Evet Title</strong></td>
								<td width="26%" align="center" class="tblcont1"><strong>Registered delegates</strong></td>
								<td width="15%" align="center" class="tblcont1"><strong>Action</strong></td>
						</tr>
						<?php
						for($i=0;$i<count($arr_eve_cancl['uid']);$i++)
						{
						?>
						<tr>
								<td align="left" style="padding-left:30px;"  class="helpBod"><?=$arr_eve_cancl['org_name'][$i]?></td>	
								<td align="center" colspan="3" class="helpBod">
										<table width="100%" cellpadding="0" cellspacing="0">
												<?php
												for($m=0;$m<count($arr_eve_cancl['tst']);$m++)
												{
														if($arr_eve_cancl['tst'][$m] == $arr_eve_cancl['uid'][$i])
														{	
												?>
												<tr>
														<td width="48%" align="left" style="padding-left:30px;" class="helpBod"><?=$arr_eve_cancl['event_title'][$m]?></td>
														<td width="33%" align="center" class="helpBod"><?=$arr_eve_cancl['total-signup'][$m]?></td>
														<td width="19%" align="center" class="helpBod"><a href="#" onclick="approved('<?=$arr_eve_cancl['nid'][$m]?>','<?=$arr_eve_cancl['tst'][$m]?>','<?=$arr_eve_cancl['event_title'][$m]?>')">Approve</a>&nbsp;|&nbsp;<a href="#" onclick="declined('<?=$arr_eve_cancl['nid'][$m]?>','<?=$arr_eve_cancl['tst'][$m]?>','<?=$arr_eve_cancl['event_title'][$m]?>')">Decline</a></td>
												</tr>
												<?php
														}
												}
												?>
										</table>				
								</td>
						</tr>
						<?php
						}
						?>
				</table>
		</form>
</div>