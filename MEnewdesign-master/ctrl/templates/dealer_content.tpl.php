<?php
if(!isset($_SESSION["user_id"]))
{
	 ?>
	 <script language="javascript">
	 	window.location="http://www.brandsdirect.com/administration/index.php";
	 </script>
	 <?
	 die();
} 
?>
<script language="javascript" type="text/javascript">
function validateFrm()
{
	document.frmContent.submit();
}
function validate()
{
	if(document.frmContent.dlr_name.value == '1')
	{
		alert('Please Select the Dealer Name.');
		document.frmContent.dlr_name.focus();
		return false;
	}
	
/*	if(document.frmContent.MyTextarea.value == "")
	{
		alert('Please Enter the Content.');
		document.frmContent.MyTextarea.focus();
		return false;
	}*/
}
//Select Dealer Name to Insert / Edit Dealer Content :<td class="page_sub_header">: </td>
</script>
<div style="border-bottom:#999999 1px solid; border-left:#999999 1px solid; border-right:#999999 1px solid; border-top:#999999 1px solid;" >
<br />
<div align="left" style="padding-left:20px;" class="page_sub_header">
Select Dealer Name to Insert / Edit Dealer Content 
</div>
<br />
<form name="frmContent" action="" method="post" id="frmContent">
<table width="100%" border="0">
	<!--<tr>
		<td>&nbsp;</td> 
	</tr>-->
	<tr>
		<td>
			 <link href="<?=_HTTP_SITE_ROOT?>/administration/fckeditor/editor/skins/default/fck_editor.css" type="text/css" />
			<script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/administration/fckeditor/fckconfig.js"></script>
			<script type="text/javascript" src="<?=_HTTP_SITE_ROOT?>/administration/fckeditor/fckeditor.js"></script>
			<script type="text/javascript">
				window.onload = function()
				{
					//alert("hi");
					var oFCKeditor = new FCKeditor( 'MyTextarea' ) ;
					oFCKeditor.BasePath = "fckeditor/";
					oFCKeditor.ReplaceTextarea() ;
				}
			</script>
			<textarea id="MyTextarea" name="MyTextarea" style="width:100%"><?php echo $content_qry['dlr_content']; ?></textarea>
		</td>
	</tr>
	<tr>
	
		<td align="center">
			<input type="submit" name="save" value="GO" onclick="return validate()"/>
		</td>
	</tr>
</table>
</form>
</div>