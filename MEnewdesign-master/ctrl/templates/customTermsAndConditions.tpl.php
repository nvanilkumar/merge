<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Event Custom Terms and Conditions</title>
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <script language="javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/jQuery.js"></script>    
    <script language="javascript" src="<?php echo _HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script> 
<!--    <script type="text/javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="<?=_HTTP_SITE_ROOT;?>/js/public/tinymce/editor.js"></script>-->        
    <script type="text/javascript" src="<?php echo JS_CLOUD_PATH;?>public/tinymce/tinymce.min.js"></script>
<script>
function validateEventIDForm(form)
{
    var eventid=document.getElementById('eventid').value;
    if(Trim(eventid).length==0)
	{
		alert("Please enter Event ID");
		document.getElementById('eventid').focus();
		return false;
	}
	else
	{
		$.post('processAjaxRequests.php',{eventIDChkforTC:1,eventid:eventid}, function(data){
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
			$.ajax({
				  url: "processAjaxRequests.php",//?delid="+val+"&delTCrecord=remove",
				  type:"post",
				  data: {"delid":val,"delTCrecord":"remove"},
				  success: function(data){
                                      if(data == 'true'){
                                      window.location.reload();
                                      }else{
                                          alert('ME Description should Mandatory');
                                      } }
                               
			});
		}
   
}


function validateEventAddForm(f)
{
   // alert("yeah");
	var termsandconditions=tinyMCE.get('termsandconditions').getContent();
	
	if(Trim(termsandconditions).length==0)
	{
		alert("Please enter event custom terms and conditions");
		document.getElementById('termsandconditions').focus();
		return false;
	}
  	return true;
}

function updateShowStatus(id,value){
    $.ajax({
                url: "processAjaxRequests.php",
                type:"post",
                data: {"call":"updateShowTC","updateId":id,"updateValue":value},
                async:false,
                success: function(data){
                    var newData=jQuery.parseJSON(data);
                    if(newData.status){
                        window.location.reload();
                    }
                    else
                        alert("updation failed");
                }
    });
}
tinymce.init({
    // General options
    mode: "specific_textareas",
    editor_selector: "mceEditor",
    selector: "textarea#termsandconditions",
    // General options
    width: "100%",
    // ===========================================
    // INCLUDE THE PLUGIN
    // ===========================================

    plugins: [
        "advlist autolink lists link charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime paste"
    ],
    // ===========================================
    // PUT PLUGIN'S BUTTON on the toolbar
    // ===========================================

    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
    // ===========================================
    // SET RELATIVE_URLS to FALSE (This is required for images to display properly)
    // ===========================================

    relative_urls: false
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
                <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------TnC PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
      document.getElementById('ans6').style.display='block';
</script>

<div align="center" style="width:100%">&nbsp;</div>
<div align="center" style="width:100%" class="headtitle">Custom Terms and Conditions for Events</div>
<div>

<form action="#"  onsubmit="return validateEventIDForm('eventid')">
	<table>
    	<td>Event ID</td><td><input type="text" name="eventid" id="eventid" value="<?php echo $eventid; ?>" /></td>
        <td><input type="submit" name="Sub" value="Submit" /></td>
    </table>
</form>
</div>


<div id="tncdataform" style="display:<?php if(strlen($eventid)>0 || isset($_REQUEST['edit']) )echo 'block';else echo 'none'; ?>">
<table style="display:<?php if(strlen($eventid)>0  )echo 'block';else echo 'none'; ?>">
	<tr><th><br />Event Details</th>
	<tr>
    	<td>Event Title</td><td><?php echo stripslashes($eventdata[0]['Title']); ?></td>
    </tr>
    <tr>
    	<td>Event Date</td><td>From: <?php echo date('Y-m-d H:i:s',strtotime($eventdata[0]['StartDt']))." To: ".date('Y-m-d H:i:s',strtotime($eventdata[0]['EndDt'])); ?></td>
    </tr>
    <tr>
    	<td>Event URL</td><td><a href="<?php echo _HTTP_SITE_ROOT;?>/event/<?php echo $eventdata[0]['URL']; ?>" target="_blank"><?php echo _HTTP_SITE_ROOT;?>/event/<?php echo  $eventdata[0]['URL']; ?></a></td>
    </tr>
    <tr><th><br /></th>
</table>


<form method="post" action="" onsubmit="return validateEventAddForm(this)">
<table width="800">
    <tr>
        <td>
            Show on Delegate Pass:
            <?php
            
            //Only Meraevents tnc can able to edit
            $tncdetails=  stripslashes($editTCdata[0]['ME_Description']);
            
            ?>
            <select id="showTC" name="showTC">
               <option id="meraevents<?php echo $editTCdata[0]['Id'];?>" value="meraevents" <?php if(strcmp($editTCdata[0]['showTC'],'meraevents')==0) echo "selected=selected"; ?>>Meraevents</option>
               <option id="organizer<?php echo $editTCdata[0]['Id'];?>" value="organizer" <?php if(strcmp($editTCdata[0]['showTC'],'organizer')==0) echo "selected=selected"; ?>>Organizer</option>
            </select>
        </td>
    </tr>
    <tr>
    	<td><b>Custom Terms and Conditions Added By Meraevents</b><br />
            <textarea name="termsandconditions" id="termsandconditions" style="height:250px"><?php echo $tncdetails; ?></textarea>
        </td>
    </tr>
    <tr>
    	<td colspan="2">
            <?php if($eventid>0){$addType='eventid'; $addTypeValue=$eventid;}else{$addType='url';$addTypeValue=$URL;} ?>
<!--            <input type="hidden" name="showTC" value="1" />-->
             <input type="hidden" name="addCustomTermsAndConditions" value="1" />
            <input type="hidden" name="eventid" value="<?php echo $eventid; ?>" />
            <input type="hidden" name="editid" value="<?php if($_GET['edit'])echo $_GET['edit']; ?>" />
            <input type="submit" value="<?php if($_REQUEST['edit'])echo 'MODIFY'; else echo 'ADD'; ?>" />
            <input type="button" value="CANCEL" onclick="javascript:window.location='<?php echo _HTTP_SITE_ROOT?>/ctrl/customTermsAndConditions.php'"/>
        </td>
    </tr>
</table>
</form>

</div>





<div align="center" style="width:100%">&nbsp;</div>

<form action="" method="get" name="frmEofMonth" enctype="multipart/form-data">






<?php 
 //$resArray=array();
$countDataTC=count($dataTC);

if($countDataTC>0){ ?>
<table width="100%" align="center" >
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">Event ID</td>
        <td width="35%" align="left" valign="middle" class="tblcont1">Organizer Description</td>
        <td width="35%" align="left" valign="middle" class="tblcont1">MeraEvents Description</td>
        <td width="35%" align="left" valign="middle" class="tblcont1">Show TC</td>
        <td width="10%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Action</td>
       
    </tr>
    <?php 
    $cnt=1;
    for($i=0;$i<$countDataTC;$i++){
        
        if($cnt%2==0)
              {
              $trcol="bgcolor=#D6D6D6";
              }else{
                 $trcol="bgcolor=#8C8C8C";

                }
    ?>
    <tr <?php echo $cnt%2!=0?"bgcolor=#D6D6D6":"bgcolor=#8C8C8C";?>>
        <td align="center" valign="middle"  height="25"><?php echo $cnt++?></td>
        <td align="left" valign="middle" ><?php  echo $dataTC[$i]['EventId']; ?></td>   
        <td align="left" valign="middle" ><?=substr(stripslashes(strip_tags($dataTC[$i]['Org_Description'])),0,200);?></td>
        <td align="left" valign="middle" ><?=substr(stripslashes(strip_tags($dataTC[$i]['ME_Description'])),0,200);?></td>
        <td align="left" valign="middle" >
            <select id="showTC1" name="showTC1" onchange="updateShowStatus('<?php echo $dataTC[$i]['EventId'];?>',this.value)">
                <option value="organizer"  <?php if(strcmp($dataTC[$i]['showTC'],'organizer')==0) echo "selected=selected"; ?>>Organizer</option>
                <option value="meraevents" <?php if(strcmp($dataTC[$i]['showTC'],'meraevents')==0) echo "selected=selected"; ?>>Meraevents</option>
            </select>
        </td>
        <td>
            <a href="?edit=<?php echo $dataTC[$i]['EventId']; ?>&eventid=<?php echo $dataTC[$i]['EventId']; ?>" title="Edit this record"><img src="images/edit.jpg" /></a>
            &nbsp;&nbsp;
            <a style="cursor:pointer" onClick="Remove('<?php echo $dataTC[$i]['EventId']; ?>');" title="Delete this record"><img src="images/delet.jpg" /></a></td>
    </tr>
    <?php }
    //} //ends for loop
    ?>
</table>
<?php
echo $functions->pagination($limit,$page,'customTermsAndConditions.php?page=',$rows); //call function to show pagination
 
    } //ends if condition
    else if(count($countDataTC) == 0)
    {
?>
    <table width="90%" align="center">
        <tr>
          <td width="100%" align="left" valign="middle">No records found.</td>
         </tr>
    </table>
<?php
    }
?>
</form>
<div align="center" style="width:100%">&nbsp;</div>
<!-------------------------------TnC PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
        </tr>
    </table>
</div>    
</body>
</html>
