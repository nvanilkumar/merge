<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Manage SEO Data</title>
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<script>
function validateEventIDForm(form)
{
       if(form=='eventid')
        {
            var eventid=document.getElementById('eventid').value;
            if(Trim(eventid).length==0)
	{
		alert("Please enter a Event ID");
		document.getElementById('eventid').focus();
		return false;
	}
	else
	{
		$.get('includes/ajaxSeoTags.php',{eventIDChk:0,eventid:eventid}, function(data){
			if(data=="error")
			{
				alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
				document.getElementById('eventid').focus();
				return false;
				
			}
			else
			{
				window.location=data;
			}
		});
		
	}
            
        }
        else if(form=="url")
            {
               
                var url =document.getElementById('url').value;
                
                if(Trim(url).length==0)
                {
		alert("Please enter a URL");
		document.getElementById('url').focus();
		return false;
                 }
	else
	{
         //   alert("in side else");
		$.get('includes/ajaxSeoTags.php',{eventIDChk:1,url:url}, function(data){
                    
			
		//	alert(data);
			window.location=data;
			
		});
             //   alert("after gety ajax");
		
	}
            }
	
        
	
        
	return false;
}


function Trim(str)
{   
      while(str.charAt(0) == (" ") )
      {  
           str = str.substring(1);
      }
      while(str.charAt(str.length-1) == " " )
      {  
           str = str.substring(0,str.length-1);
      }
      return str;
}

function Remove(val)
{
		
		if(window.confirm("Are you sure want to delete this record..?"))
		{
			/*$.ajax({
				  url: "includes/ajaxSeoTags.php",
				  type:"post",
				  data: {"delid":val,"delSEO":"remove"},
				  success: function(data){
						window.location.reload();
						}
					});
			*/	
              window.location.href = 'addSEO.php?delid='+val;			
		}
   
}


function validateEventAddForm(f)
{
   // alert("yeah");
	var mtitle=f.mtitle.value;
	var mkeywords=f.mkeywords.value;
	var mdescription=f.mdescription.value;
        var URL=f.sURL.value;
        var pdescription=f.pdescription.value;
        var addType=f.addType.value;
        
        if(addType=="url")
            {
                      	if(Trim(URL).length==0)
                {
		alert("Please enter a URL");
		document.getElementById('sURL').focus();
		return false;
                }
           	if(Trim(pdescription).length==0)
                {
		alert("Please enter a page description");
		document.getElementById('pdescription').focus();
		return false;
                }
            }
	
	/*if(Trim(mtitle).length==0)
	{
		alert("Please enter a Meta Title");
		document.getElementById('mtitle').focus();
		return false;
	}
	if(Trim(mkeywords).length==0)
	{
		alert("Please enter a Meta Keywords");
		document.getElementById('mkeywords').focus();
		return false;
	}
	if(Trim(mdescription).length==0)
	{
		alert("Please enter a Meta Description");
		document.getElementById('mdescription').focus();
		return false;
	}*/
  
	return true;
	
	
}

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
                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------SEO types PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans21').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">SEO Data</div>
<div align="center" style="width:100%" ><?php echo $msg;?></div>
<div>

<form action="#"  onsubmit="return validateEventIDForm('eventid')">
	<table>
    	<td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventid; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
<form action="#"  onsubmit="return validateEventIDForm('url')">
	<table>
        <td>URL : <?php echo $_SERVER["HTTP_HOST"].'/'; ?></td><td><input type="text" name="url" id="url" value="<?php echo $URL; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
</div>


<div id="seodataform" style="display:<?php if(strlen($eventid)>0 || isset($_REQUEST['edit']) || strlen($URL)>0 )echo 'block';else echo 'none'; ?>">
<table style="display:<?php if(strlen($eventid)>0)echo 'block';else echo 'none'; ?>">
	<tr><th><br />Event Details</th>
	<tr>
    	<td>Event Title</td><td><?php echo stripslashes($eventdata[0]['Title']); ?></td>
    </tr>
    <tr>
    	<td>Event Date</td><td>From: <?php echo date('Y-m-d H:i:s',strtotime($eventdata[0]['StartDt']))." To: ".date('Y-m-d H:i:s',strtotime($eventdata[0]['EndDt'])); ?></td>
    </tr>
    <tr>
    	<td>Event URL</td><td><a href="<?=_HTTP_SITE_ROOT;?>/event/<?php echo $eventdata[0]['URL']; ?>" target="_blank"><?=_HTTP_SITE_ROOT;?>/event/<?= $eventdata[0]['URL']; ?></a></td>
    </tr>
    <tr><th><br /></th>
</table>

    <div> </br> </div>
<form method="post" action="" onsubmit="return validateEventAddForm(this)">
<table>
    <tr>
    	<td>Canonical URL </td><td><input type="text" name="sURL" id="sURL" size="80" value="<?php echo stripslashes(isset($edidSEOdata[0]['conanicalurl'])?$edidSEOdata[0]['conanicalurl']:$edidSEOdata[0]['canonicalurl']); ?>" /></td>
    </tr>
	<tr>
    <td>Meta Title</td><td><input type="text" name="mtitle" id="mtitle" size="80" value="<?php echo stripslashes($edidSEOdata[0]['seotitle']); ?>" /></td>
    </tr>
    <tr>
    	<td>Meta Keywords</td><td><textarea name="mkeywords" id="mkeywords" cols="60"><?php echo stripslashes($edidSEOdata[0]['seokeywords']); ?></textarea><br /><span style="color:#000;">Enter List of Keywords seperated by a comma (,)</span></td>
    </tr>
    <tr>
    	<td>Meta Description</td><td><textarea name="mdescription" id="mdescription" cols="60"><?php echo stripslashes($edidSEOdata[0]['seodescription']); ?></textarea></td>
    </tr>
    <!--tr>
    	<td>Page Description</td><td><textarea name="pdescription" id="pdescription" cols="60"><?php //echo stripslashes($edidSEOdata[0]['pageDescription']); ?></textarea></td>
    </tr-->
    <tr>
    	<td colspan="2">
            <?php if($eventid>0){$addType='eventid'; $addTypeValue=$eventid;}else{$addType='url';$addTypeValue=$URL;} 
            
            
            ?>
            <input type="hidden" name="addTypeValue" value="<?php echo $addTypeValue; ?>" />
            <input type="hidden" name="addType" value="<?php echo $addType; ?>" />
            <input type="hidden" name="addSEO" value="1" />
            <input type="hidden" name="editid" value="<?php if($_REQUEST['edit'])echo $_REQUEST['edit']; ?>" />
            <input type="hidden" name="seoid" value="<?php echo $edidSEOdata[0]['id']; ?>" />
            <input type="submit" value="<?php  if($_REQUEST['edit'] || strlen($edidSEOdata[0]['seokeywords']) > 0)echo 'MODIFY'; else echo 'ADD'; ?>" />
        </td>
    </tr>
</table>
</form>

</div>





<div align="center" style="width:100%">&nbsp;</div>
<form action="" method="get" name="frmEofMonth" enctype="multipart/form-data">






<?php if(count($seotypesdata) > 0) { ?>
<table width="100%" align="center" >
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">URL</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Meta Title</td>
         <td width="20%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Meta Keywords</td>
        <td width="30%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Meta Description</td>
        <td width="50%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Page Description</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
       
    </tr>
    <?php 
        $cnt=1;
        for($i = 0; $i < count($seotypesdata); $i++)
        {
              if($i%2==0)
              {
              $trcol="bgcolor=#D6D6D6";
              }else{
                 $trcol="bgcolor=#8C8C8C";

                }
    ?>
    <tr <?=$trcol;?>>
        <td align="center" valign="middle"  height="25"><?=$cnt++?></td>
        <td align="left" valign="middle" >
            <?php 
            if($seotypesdata[$i]['eventId']==0)
            {
                echo $seotypesdata[$i]['eventId'];
            }else{
            ?>
            <a href="<?=_HTTP_SITE_ROOT;?>/event/<?php echo $seotypesdata[$i]['URL']; ?>" target="_blank" title="Preview this Event"><?php echo $seotypesdata[$i]['eventId']; ?></a>
            <?php } ?>
        </td>     
        <td align="left" valign="middle" ><?=stripslashes($seotypesdata[$i]['sURL']);?></td>
        <td align="left" valign="middle" ><?=stripslashes($seotypesdata[$i]['seoTitle']);?></td>
        <td align="left" valign="middle" ><?=stripslashes($seotypesdata[$i]['seoKeywords']);?></td>
        <td align="left" valign="middle" ><?=stripslashes($seotypesdata[$i]['seoDescription']);?></td>   
        <td align="left" valign="middle" ><?=substr(stripslashes($seotypesdata[$i]['pageDescription']),0,200);?></td>   
        <td>
            <a href="?edit=<?php echo $seotypesdata[$i]['eventId']; ?>&eventid=<?php echo $seotypesdata[$i]['eventId']; ?>" title="Edit this record"><img src="images/edit.jpg" /></a>
            &nbsp;&nbsp;
            <a style="cursor:pointer" onClick="Remove('<?php echo $seotypesdata[$i]['eventId']; ?>');" title="Delete this record"><img src="images/delet.jpg" /></a></td>
    </tr>
    <?php 
    } //ends for loop
    ?>
</table>
<?php
echo $functions->pagination($limit,$page,'addSEO.php?page=',$rows); //call function to show pagination
 
    } //ends if condition
    else if(count($seotypesdata) == 0)
    {
?>
    <table width="90%" align="center">
        <tr>
          <td width="100%" align="left" valign="middle">No record found.</td>
         </tr>
    </table>
<?php
    }
?>
</form>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------SEO types PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
