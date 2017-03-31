<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="css/menus.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="css/sortable.js"></script>	
        <script language="javascript" src="css/sortpagi.js"></script>	
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script>
            function validateEventIDForm()
            {
                var EventId=document.getElementById('EventId').value;

                if(Trim(EventId).length==0)                    {
                    alert("Please enter a Event ID");
                    document.getElementById('EventId').focus();
                    return false;
                }
                if (isNaN(EventId)) {
                    alert('Please enter a valid Event ID');
                    return false;
                }
                if(Trim(EventId).length!=0){
                    $.get('salesres.php',{eventIDChk:true,EventId:EventId}, function(data){
                        if(data=="error"){
                            alert("Sorry, we did not find the Event ID or Event is deleted, Please Re-enter");
                            document.getElementById('EventId').focus();
                            return false;
                        }
                        else{
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
            function validateEventForm(){

                    var eventid=document.getElementById('EventId').value;
                    var salesid=document.getElementById('SalesId').value;

                    if(Trim(salesid).length==0)
                    {

                    alert("Please Select Sales Person");
                            document.getElementById('SalesId').focus();
                            return false;	
                    }

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
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans13').style.display='block';
</script>

 
<form action="" method="post" name="add_category" onsubmit="return validateEventIDForm()">
<?php
if($_REQUEST['msg']!='')
    echo $_REQUEST['msg'];
else
    echo $msg;

$edit_condition = false;
if($_REQUEST['aSalesId'] > 0 && $action != 'Update') {
    $edit_condition = true;
}
?>

<table width="90%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="3"><strong>Add Sales Person</strong> </td>
  </tr>
  <tr>
    <td width="21%" >Select an Event: </td>
    <td width="21%">
    <input type="text" name="EventId" id="EventId" value="<?php echo $_REQUEST['EventId']; ?>" <?php if($edit_condition) {echo "readonly";};?>/>
     <div id="salesinfo"></div>
      </td>
      <td width="58%"><input type="submit" name="Submit" id="ShowSales" <?php if($_REQUEST['edit']=="yes"){?>  style="display:none" <?php }?> value="SUBMIT" /></td>
   
  </tr>
  </table>
  </form>
  
  <form action="" method="post" name="add_category" onsubmit="return validateEventForm()" <?php if($_REQUEST['edit']!="yes"){?>  style="display:none" <?php }?>>

<table width="90%" border="0" cellpadding="3" cellspacing="3">
  <tr>
     <td width="21%" > Name : </td>
     <td width="79%" ><label>
      <select name="SalesId" id="SalesId" <?php if($edit_condition) {echo "disabled";};?>>
        <option value="">Select</option>
        <?php
		$TotalSalesQueryRES = count($SalesQueryRES);

		for($i=0; $i < $TotalSalesQueryRES; $i++)
		{
		?>
         <option value="<?php echo $SalesQueryRES[$i]['SalesId'];?>" <?php if($SalesQueryRES[$i]['SalesId']==$_REQUEST['aSalesId']){?> selected="selected" <?php }?>><?php echo $SalesQueryRES[$i]['SalesName'];?></option>
         <?php }?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td> EventType : </td>
    <td><label>
      <select name="EventType" id="EventType" <?php if($edit_condition) { echo "disabled";}?>>
        <option value="">Select</option>
        
         <option value="small" <?php if($EventType=="small"){?> selected="selected" <?php }?>>Small</option>
         <option value="medium" <?php if($EventType=="medium"){?> selected="selected" <?php }?>>Medium</option>
         <option value="large" <?php if($EventType=="large"){?> selected="selected" <?php }?>>Large</option>
        
      </select>
    </label> (Small upto 100, Medium 100 to 500, Large more than 500)</td>
  </tr>
  <tr>
    <td> Tckwdz : </td>
    <td><label>
      <select name="Tckwdz" id="Tckwdz" <?php if($edit_condition) {echo "disabled";};?>>
        <option value="">Select</option>
       
       <option value=1 <?php if($Tckwdz==1){?> selected="selected" <?php }?>>Yes</option>
       <option value=0 <?php if($Tckwdz==0){?> selected="selected" <?php }?>>No</option>
        
      </select>
    </label></td>
  </tr>
    <tr>
    <td> Payment Type : </td>
    <td><label>
            <input type="radio" name="Payment" value="complete" <?php if($Payment=="complete"){?> checked="checked" <?php }?> <?php if($edit_condition) {echo "disabled";};?>> Full Payment&nbsp;&nbsp;
            <input type="radio" name="Payment" value="partial" <?php if($Payment=="partial"){?> checked="checked" <?php }?>  <?php if($edit_condition) {echo "disabled";};?>> Partial Payment
    </label></td>
  </tr>
    <tr>
    <td> Payment goes in : </td>
    <td><label>
      <select name="iDays" id="iDays" <?php if($edit_condition) {echo "disabled";};?>>
        <option value="">Select</option>
       
        <?php for($d=1; $d<31; $d++) { ?>
        <option value="<?php echo $d;?>" <?php if($iDays == $d){?>selected="selected"<?php }?>><?php echo $d;?></option>
        <?php } ?>
        
      </select>&nbsp;&nbsp;days
    </label></td>
  </tr>
   <tr>
    <td> Description : </td>
    <td><label>
      <textarea name="Description" id="Description" rows="5" cols="50" <?php if($edit_condition) {echo "readonly";};?>><?php echo $Description;?></textarea>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="<?php if($edit_condition) {echo "Edit";} elseif($_REQUEST['aSalesId'] > 0 && $action == 'Update'){echo "Update";}else {echo "Add";};?>" />
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo $MsgcategoryExist; ?></td>
  </tr>
</table>
</form>

<!-------------------------------ADD CONTENT PAGE ENDS HERE--------------------------------------------------------------->
	
	
	
	</div>
	</td>
  </tr>
</table>
	</div>	
</body>
</html>
