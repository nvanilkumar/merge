<?
session_start();
   
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents - Admin Panel - User Management - Manage Organizer</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
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
				<div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!------------------------------- PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans20').style.display='block';
</script>
<link rel="stylesheet" type="text/css" media="all" href="<?=_HTTP_CF_ROOT;?>/ctrl/css/CalendarControl.min.css.gz" />
<script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/CalendarControl.min.js.gz"></script>
<script language="javascript" type="text/javascript">
	function valid()
	{
		var name = document.frmorgsearch.orgname.value;
		var aboutorg = document.frmorgsearch.aboutorg.value;   
		var SeqNo=	document.frmorgsearch.seqno.value; 
		var EveNo=	document.frmorgsearch.EveNo.value;     
			
			if(name=="")
			{
				alert('Please Select OrganizerDisplay Name.');
				document.getElementById('orgname').focus();
				return false;
			}
			if(aboutorg=="")
			{
				alert('Please Write About Organizer.');
				document.getElementById('aboutorg').focus();
				return false;
			}
			if(SeqNo == '')
			{
			alert("Please Enter the Sequence Number");
			document.getElementById('seqno').focus();
			return false;								
			}
		 if(isNaN(SeqNo)){
                                    alert("Please Enter the Sequence Number");
                                    document.getElementById('seqno').focus();
                                    return false;
                            }  
			if(EveNo == '')
			{
			alert("Please Enter the No of Events");
			document.getElementById('EveNo').focus();
			return false;								
			}
		 if(isNaN(EveNo)){
                                    alert("Please Enter the Sequence Number");
                                    document.getElementById('EveNo').focus();
                                    return false;
                            }      
                 return true;
		}
	
	
</script>

<div>
	<form name="frmorgsearch" action=" " enctype="multipart/form-data" method="post" onSubmit="return valid();">
		<table width="100%" cellpadding="0" cellspacing="5" style="padding-left:20px;">
			
			<tr>
                <td colspan="4" align="center" class="headtitle">Add Organisers Info</td>
            </tr>
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr>
            	<td>
                	<table align="center" width="100%" cellpadding="2" cellspacing="5" style="border:thin; border-color:#006699; border-style:solid;">
                    	<tr>
                    	  <td width="16%">&nbsp;</td>
                       	  <td width="26%" align="left" valign="middle" class="tblcont">Organizer Display Name</td>
                       	  <td width="7%" align="center" valign="middle" class="tblcont">:</td>
				<td colspan="3" align="left" valign="middle">
                <select name="orgname" id="orgname" onchange="reload1(this.value);">
                <option value="">Select</option>
                <? for($i=0;$i<count($dtlOrgdisp);$i++) { ?>
                <option value="<?=$dtlOrgdisp[$i][Id];?>" <? if($dtlOrgdisp1[0][Id]==$dtlOrgdisp[$i][Id]){ ?> selected="selected" <? }?> ><?=$dtlOrgdisp[$i][orgDispName];?></option>
                <? }?>
                </select>
                </td>
			</tr>
			
			
		<tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">About Organizer</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><textarea rows="5" cols="50" name="aboutorg" id="aboutorg"><?=$dtlOrgdisp1[0][aboutorg];?></textarea>
                
				</td> 
			</tr>
		<tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Organizer Info</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><textarea rows="5" cols="50" name="orginfo" id="orginfo"><?=$dtlOrgdisp1[0][orginfo];?></textarea>
                
				</td> 
			</tr>
        <tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Events Intended for</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><textarea rows="5" cols="50" name="intendedfor" id="intendedfor"><?=$dtlOrgdisp1[0][intendedfor];?></textarea>
                
				</td> 
			</tr>
               <tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Upload Logo</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><input  name="logo" id="logo" size="25" value="" type="file" />
                <? if($dtlOrgdisp1[0][logopath]!="") { ?><br/>
                <input type="hidden" name="org_logo" value="<?=$dtlOrgdisp1[0][logopath];?>" />
                <img src="<?=_HTTP_CDN_ROOT;?>/<?=$dtlOrgdisp1[0][logopath];?>" border="0" />
                <? }?>
				</td> 
			</tr>
               <tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Upload Banner</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><input  name="banner" id="banner" size="25" value="" type="file" />
                 <? if($dtlOrgdisp1[0][bannerpath]!="") { ?> <br/>
                 <input type="hidden" name="org_banner" value="<?=$dtlOrgdisp1[0][bannerpath];?>" />
                <img src="<?=_HTTP_CDN_ROOT.$dtlOrgdisp1[0][bannerpath];?>" border="0" />
                <? }?>
               
                
				</td> 
			</tr>
             <tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">Display Seq no</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><input type="text" value="<?=$dtlOrgdisp1[0][seqno];?>" name="seqno" id="seqno" />
                
				</td> 
			</tr>
            <tr>
			  <td>&nbsp;</td>
				<td align="left" valign="middle" class="tblcont">No of Events to Display</td>
				<td align="center" valign="middle" class="tblcont">:</td>
				
             
                <td width="50%" align="left" valign="middle"><input type="text" value="<?=$dtlOrgdisp1[0][EveNo];?>" name="EveNo" id="EveNo" />
                
				</td> 
			</tr>
			<tr>
				<td colspan="6" align="center">
               
                <input type="submit" name="Update" value="Update">
               </td>
               
                        </tr>
                    </table>                </td>
			</tr>
		</table>
  </form>
</div>
<!------------------------------- PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>
<script>
function reload1(val)
{
window.location="manage_organizersinfo.php?orgid="+val;
}
</script>