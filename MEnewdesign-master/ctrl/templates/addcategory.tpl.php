<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <script src="<?php echo  _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
        <script type="text/javascript" src="<?php echo _HTTP_CF_ROOT;?>/js/public/jquery.validate.js"></script>
        <script>
           $(function () {
               
               $.validator.addMethod('filesize', function(value, element, param) {
                    return this.optional(element) || (element.files[0].size <= param); 
               });

            $("#add_category").validate({
                rules: {
                    

                    category_name: {
                        required: true
                    },
                    color: {
                        required: true
                    },
                    logofile: { required: true, 
                                  accept: "png|jpe?g|gif", 
                                  filesize: 1048576  },
                   defaultbanner: { 
                                    required: true,
                                  accept: "png|jpe?g|gif", 
                                  filesize: 1048576  },
                    defaultthumbnail: { 
                        required: true,
                                  accept: "png|jpe?g|gif", 
                                  filesize: 1048576  }
                },
                messages: {
                     category_name: { required: "Please enter the Category Name"},
                     color: { required: "Please enter the category name"},
                     logofile:{ required:"File must be JPG, GIF or PNG, less than 1MB"},
                     defaultbanner:{ required:"File must be JPG, GIF or PNG, less than 1MB"},
                     defaultthumbnail:{ required:"File must be JPG, GIF or PNG, less than 1MB"}

                },
                errorPlacement: function (error, element) {
                                 error.insertAfter(element);
                               }

                     });
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
	
	
<!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
<script type="text/javascript" language="javascript">
	document.getElementById('ans2').style.display='block';

</script>

<form action="" method="post" name="add_category" id="add_category" enctype="multipart/form-data" >
<table width="50%" border="0" cellpadding="3" cellspacing="3">
  <tr>
    <td colspan="2"><strong>Add Category</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Category Name : </td>
    <td><label>
      <input name="category_name" type="text" id="category_name" />
    </label></td>
  </tr>
  <tr>
			<td>Order </td>
			<td><label>
			<input type="text" name="order" maxlength="5" />
			</label></td>
		</tr>
		<tr>
			<td>Featured </td>
			<td><label>
			<input type="checkbox" name="featured" value="1" />
			</label></td>
		</tr>
		<tr>
			<td>Status </td>
			<td><label>
		
			 <input type="radio" checked name="status" value="1" />Active&nbsp;&nbsp;&nbsp;
			 <input type="radio" name="status" value="0" />Inactive
			</label></td>
		</tr>
<!--		 <tr><td>Icon </td>
		  <td><label>
		  <input type="file" name="iconfile" />
          </label></td></tr>  -->
		  <tr><td>Image </td>
		  <td><label>
		  <input type="file" name="logofile"  id="logofile"/>
          </label></td></tr>  
    <tr><td>Default Banner </td>
		  <td><label>
		  <input type="file" name="defaultbanner"  id="defaultbanner"/>
          </label></td></tr>  
    <tr><td>Default Thumbnail </td>
		  <td><label>
		  <input type="file" name="defaultthumbnail"  id="defaultthumbnail"/>
          </label></td></tr>  
		   <tr><td>Color</td>
		  <td><label>
		  <input type="text" name="color"  id="color"/>
          </label></td></tr>  
  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Add" />
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