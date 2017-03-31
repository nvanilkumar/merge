<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>MeraEvents - Admin Panel - Manage Popular Events</title>
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
    <link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/pagi_sort.min.css.gz" rel="stylesheet" type="text/css" media="all" />    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>    
    <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>    
    <script type="text/javascript" language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/includes/javascripts/sortpagi.min.js.gz"></script>
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
                <div id="divMainPage" style="margin-left:10px; margin-right:5px;">
<!-------------------------------Manage PopEvents PAGE STARTS HERE--------------------------------------------------------------->
<script language="javascript">
  	document.getElementById('ans11').style.display='block';
</script>
                    <div align="center" style="width:100%">&nbsp;</div>
                    <div align="center" style="width:100%" class="headtitle">Manage Gallery Images - Edit  Images Information</div>
                    <div align="center" style="width:100%">&nbsp;</div>
                    <div><?=$msgActionStatus?></div>
                    <form name="frmEditImages" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="Id" value="<?=$GalleryImagesList[0]['Id']?>" />
                        <input type="hidden" name="Active" value="<?=$GalleryImagesList[0]['Active']?>" />
                        <table width="100%" cellpadding="1" cellspacing="2">
                            <tr>
                                <td width="100%" colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    <b>Title &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                </td>
                                <td width="75%">
                                    <input type="text" name="txtTitle" id="txtTitle" value="<?=$GalleryImagesList[0]['Title']?>" maxlength="50" size="50"  />
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    <b>Image &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                </td>
                                <td width="75%">
                                    <img src="..<?=$GalleryImagesList[0]['FileName']?>" /><br /><br />
                                    <input type="file" name="fileGalleryAdd" id="fileGalleryAdd" maxlength="100" title="Size should be 772 px X 200 px" value="" size="50"  />
                                </td>
                            </tr>
                            <!--<tr>
                                <td width="25%">
                                    <b>URL &nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                </td>
                                <td width="75%">
                                    <input type="text" name="txtURL" id="txtURL" value="<?=$PopEventsList[0]['URL']?>" maxlength="100" size="50"  />
                                </td>
                            </tr>
                            <tr>
                                <td width="25%">
                                    <b>Sequence No.&nbsp;<font color="#FF0000">*</font>&nbsp;:</b>
                                </td>
                                <td width="75%">
                                    <input type="text" name="txtSeqNo" id="txtSeqNo" value="<?=$PopEventsList[0]['SeqNo']?>" maxlength="3" size="5"  />
                                </td>
                            </tr>                                                            -->
                            <tr>
                                <td width="25%">&nbsp;</td>
                                <td width="55%">
                                    <input type="Submit" name="Update" value="UpdateGallery" onclick="return val_editImages();" />&nbsp;<input type="button" name="Cancel" value="Cancel" onclick="javascript:window.location='manage_PopEvents.php'" />
                                </td>
                            </tr>
                        </table>
                        <script language="javascript" type="text/javascript">
                            function val_editImages()
                            {
                                var Title = document.getElementById('txtTitle').value;
                                var GalleryImage = document.getElementById('fileGalleryAdd').value;
                                                                
                                if(Title == '')
                                {
                                    alert("Please Enter the Title");
                                    document.getElementById('txtTitle').focus();
                                    return false;
                                }
                                //else if(PopEventsImage == '')
                                //{
                                //    alert("Please Select PopEvents Image");
                                //    document.getElementById('filePopEventsImage').focus();
                                //    return false;                                
                                //}
                                return true;
                            }
                        </script>
                    </form>
<!-------------------------------Manage PopEvents PAGE ENDS HERE--------------------------------------------------------------->
                </div>
            </td>
          </tr>
        </table>
    </div>    
</body>
</html>