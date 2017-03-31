<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css" />
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css" />
        <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
    </head>	
    <body style="background-image: url(images/background.gif); background-repeat:repeat-x; margin-top: 0px; margin-left: 0px; margin-right:0px; padding:0px">
        <?php include('templates/header.tpl.php'); ?>				
        
            <table style="width:100%; height:495px;" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:150px; vertical-align:top; background-image:url(images/menugradient.jpg); background-repeat:repeat-x">
                        <?php include('templates/left.tpl.php'); ?>
                    </td>
                    <td style="vertical-align:top">
                        <div  id="divMainPage" style="margin-left: 10px; margin-right:5px">
                            <!-------------------------------ADD CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                            <script type="text/javascript" language="javascript">
                                function validate_category() {
                                    if (document.edit_category.category_name.value == '') {
                                        alert("Please Enter Category Name");
                                        document.edit_category.category_name.focus();
                                        return false;
                                    }

                                    return true;
                                }
                            </script>
							<script language="javascript">
  	document.getElementById('ans2').style.display='block';
</script>
                            <form action="" method="post" name="edit_category" onsubmit="return validate_category();">
                                <table width="50%" border="0">
                                    <tr>
                                        <td colspan="2"><strong>Edit Category</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <?php for ($i = 0; $i < count($EditCategory); $i++) { ?> 
                                    <tr>
                                        <td>Category Name : </td>
                                        <td><label>
                                                <input type="text" disabled="disabled" readonly="readonly" name="cat_name" value="<?= $EditCategory[$i]['CatName'] ?>" />  
                                           </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sub-Category Name : </td>
                                        <td><label>
                                                <input type="text" name="category_name" value="<?= $EditCategory[$i]['SubCatName'] ?>" />
                                                <input type="hidden" name="subcategory_id" value="<?= $SubCategoryId ?>" />
                                                <input type="hidden" name="category_id" value="<?= $EditCategory[$i]['CatId']; ?>" />
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sub-Category Value : </td>
                                        <td><label>
                                                <input type="text" name="category_value" value="<?= $EditCategory[$i]['SubCatValue'] ?>" />
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Status : </td>
                                        <td>
                                            
                                                <input type="radio" name="subcategory_status" value="1" <?php echo $EditCategory[$i]['subcatstatus'] == 1 ? "checked" : ""; ?> /><label>Active&nbsp;&nbsp;&nbsp; </label><input type="radio" name="subcategory_status" value="0" <?php echo $EditCategory[$i]['subcatstatus'] == 0 ? "checked" : ""; ?> /><label>Inactive
                                            </label>
                                        </td>
                                    </tr> 
                                    <?php } ?>
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
                   
    </body>
</html>