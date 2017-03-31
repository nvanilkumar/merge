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

            $("#edit_category").validate({
                rules: {
                    

                    category_name: {
                        required: true
                    },
                    color: {
                        required: true
                    },
                    logofile: { 
                                  accept: "png|jpe?g|gif", 
                                  filesize: 1048576  },
                               defaultbanner: { 
                                  accept: "png|jpe?g|gif", 
                                  filesize: 1048576  },
                                defaultthumbnail: { 
                                  accept: "png|jpe?g|gif", 
                                  filesize: 1048576  }
                },
                messages: {
                     category_name: { required: "Please enter the Category Name"},
                     color: { required: "Please enter the category name"},
                     logofile:{ required:"File must be JPG, GIF or PNG, less than 1MB"},
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

<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
<form action="" method="post" name="edit_category" id="edit_category" enctype="multipart/form-data" >
<table width="50%" border="0">
  <tr>
    <td colspan="2"><strong>Edit Category</strong> </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 <tr>
    <td>Category Name : </td>
    <td><label>
      <input name="category_name" type="text" id="category_name" value="<?php echo $EditCategory[0]['name'] ;?>"/>
	  <input type="hidden" name="category_id" id="category_id" value="<?php echo $EditCategory[0]['id'];?>" />
	  <input type="hidden" name="iconid" value="<?php echo $EditCategory[0]['iconimagefileid'];?>" />
	   <input type="hidden" name="imageid" value="<?php echo $EditCategory[0]['imagefileid'];?>" />
    </label></td>
  </tr>
  <tr>
			<td>Order </td>
			<td><label>
			<input type="text" name="order" maxlength="5" value="<?php echo $EditCategory[0]['order'] ;?>" />
			</label></td>
		</tr>
		<tr>
			<td>Featured </td>
			<td><label>
			<input type="checkbox" <?php echo $EditCategory[0]['featured'] == 1 ? "checked" : ""; ?>  name="featured" value="1" />
			</label></td>
		</tr>
		<tr>
			<td>Status </td>
			<td>
		
			 <input type="radio" name="status" value="1"  <?php echo $EditCategory[0]['status'] == 1 ? "checked" : ""; ?> /><label>Active&nbsp;&nbsp;&nbsp;</label>
			 <input type="radio" name="status" value="0" <?php echo $EditCategory[0]['status'] == 0 ? "checked" : ""; ?>  /><label>Inactive
			</label></td>
		</tr>
<!--		 <tr><td>Icon </td>
		  <td>
		   <?php if(isset($EditCategory[0]['iconimagefile']) && !empty($EditCategory[0]['iconimagefile'])) { ?>
                                                            <img src="<?php echo $EditCategory[0]['iconimagefile'] ?>" />
                                                        <?php } ?>
		  <label>
		  <input type="file" name="iconfile" />
          </label></td></tr>  -->
		  <tr><td>Image </td>
		  <td>
		  <?php if(isset($EditCategory[0]['imagefile']) && !empty($EditCategory[0]['imagefile'])) { ?>
                   <img src="<?php echo CONTENT_CLOUD_PATH.$EditCategory[0]['imagefile'] ?>" />
                   <input type="hidden" name="categoryFileId" id="categoryFileId" value="<?php 
                   if ($EditCategory[0]['imagefileid'] != "" && $EditCategory[0]['imagefileid'] != "0") {
                        echo $EditCategory[0]['imagefileid'];
                   }else{echo 0;}      
                   ?>"/>
                   <?php } ?>
		  <label>
		  <input type="file" name="logofile" id="logofile" />
          </label></td></tr>  
      <tr><td>Default Banner </td>
		  <td>
		  <?php if(isset($EditCategory[0]['categorydefaultbanner']) && !empty($EditCategory[0]['categorydefaultbanner'])) { ?>
                   <img src="<?php echo CONTENT_CLOUD_PATH.$EditCategory[0]['categorydefaultbanner'] ?>" />
                   <input type="hidden" name="defaultBannerFileId" id="defaultBannerFileId" value="<?php 
                   if ($EditCategory[0]['categorydefaultbannerid'] != "" && $EditCategory[0]['categorydefaultbannerid'] != "0") {
                        echo $EditCategory[0]['categorydefaultbannerid'];
                   }else{echo 0;}        
                   ?>"/>
                     <?php } ?>
		  <label>
		  <input type="file" name="defaultbanner" id="defaultbanner" />
          </label></td></tr>  
      <tr><td>Default Thumbnail </td>
		  <td>
		  <?php if(isset($EditCategory[0]['categorydefaultthumbnail']) && !empty($EditCategory[0]['categorydefaultthumbnail'])) { ?>
                   <img src="<?php echo CONTENT_CLOUD_PATH.$EditCategory[0]['categorydefaultthumbnail'] ?>" />
                   <input type="hidden" name="defaultthumbnailFileId" id="defaultthumbnailFileId" value="<?php 
                   if ($EditCategory[0]['categorydefaultthumbnailid'] != "" && $EditCategory[0]['categorydefaultthumbnailid'] != "0") {
                        echo $EditCategory[0]['categorydefaultthumbnailid'];
                   }else{echo 0;}       ?>"/>
                    <?php } ?>
		  <label>
		  <input type="file" name="defaultthumbnail" id="defaultthumbnail" />
          </label></td></tr>  
		   <tr><td>Color</td>
		  <td><label>
		  <input type="text" name="color" id="color" value="<?php echo $EditCategory[0]['themecolor'] ;?>" />
          </label></td></tr>  
		  <tr>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="Submit" value="Update">
    </label></td>
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