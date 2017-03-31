<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
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
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	function validate_location(){
		if(document.add_location.category_name.value == ''){
			alert("Please Enter A Location");
			document.add_location.location_name.focus();
			return false;
		}
		
		return true;
	}
</script>
<script language="javascript">
function getXMLHTTP()
	 { //fuction to return the xml http object
		var xmlhttp=false;	
		try
			{
			xmlhttp=new XMLHttpRequest();
			}
		catch(e)
			{		
				try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
					}
			catch(e){
				
						try{
							req = new ActiveXObject("Msxml2.XMLHTTP");
							}
						catch(e1){
							xmlhttp=false;
								}
					}
			}
		 	
		return xmlhttp;
	}
	function Cityselect(value, Citytd) {
	var req = getXMLHTTP();
	
	if (req) {
			var url= 'CitySelect.php?id=' + value;
			
			req.onreadystatechange = function() 
			{
				if (req.readyState == 4) 
				{
					
					if (req.status == 200) 
					{
						selectField = document.getElementById(Citytd);
						var splitValue = req.responseText;							
						selectField.innerHTML = splitValue;				
					} 
					else 
					{
						alert("Error connecting to network:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", url, true);
			req.send(null);
		}
			
	
	
}
	</script>
<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<form action="" method="post" name="add_location" onsubmit="return validate_location();">
<table width="50%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Add Location</strong> </td>
  </tr>
  <tr>
    <td width="20%">State : </td>
    <td width="80%"><label>
      <select name="StateId" id="StateId" onchange="Cityselect(this.value,'Citytd')" style="width:200px">
      	<?php 
			foreach($editState as $id => $value) {
		?>
        	<option value="<?=$value['Id'];?>"><?=$value['State'];?></option>
        <?php
			}
		?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>City : </td>
    <td id="Citytd">
      <select name="CityId" id="CityId" style="width:200px">
      	<?php 
			foreach($editCity as $id => $value) {
		?>
        	<option value="<?=$value['Id'];?>"><?=$value['City'];?></option>
        <?php
			}
		?>
      </select>
   </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>Location Name : </td>
    <td><label>
      <input name="location_name" type="text" id="location_name" style="width:200px" />
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Add" />
    </label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php echo $MsgLocationExist; ?></td>
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