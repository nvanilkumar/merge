<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
                <script type="text/javascript" language="javascript">
                function getStates(id){
                    if (id != 0) {
                       window.location="editstate.php?country="+id;
                   }if(id==0){
                       document.getElementById("resultData").style.display="none";
                       document.getElementById("addedit").style.display="none";
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


                                    <!-------------------------------CONTENT PAGE STARTS HERE--------------------------------------------------------------->
                                    <script language="javascript">
                                        document.getElementById('ans2').style.display = 'block';
                                    </script>
                                    <div style="width:100%" align="center">
                                        <form action="" method="post" name="edit_form">
                                            <table width="50%">
                                                <tr>
                                                    <td colspan="2" align="center" class="headtitle"><strong>State Management</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" align="left"><a href="admin.php" class="menuhead" title="Master management Home">Master Management Home</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Select country: </td>
                                                    <td>
                                                        <select name="country" onchange="getStates(this.value)">
                                                            <option value="0" selected="selected">--SELECT--</option>
                                                            <?php
                                                            for ($i = 0; $i < count($countryList); $i++) {
                                                                ?>
                                                                <option value="<?php echo $countryList[$i]['id']; ?>"  <?php if($countryList[$i]['id'] == $countryId) { ?> selected="selected" <?php } ?>><?php echo $countryList[$i]['name']; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><table width="100%" id="resultData" class="sortable">
                                                            <thead>
                                                                <tr>
                                                                    <td class="tblcont1"><strong>State</strong> </td>
                                                                    <td class="tblcont1"><strong>Country</strong> </td>
                                                                    <td class="tblcont1"><strong>Status</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>Edit</strong> </td>
                                                                    <td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
                                                                </tr>
                                                            </thead>
                                                            <?php
                                                            for ($i = 0; $i < count($StatesList); $i++) {
                                                                ?>
                                                                <tr>
                                                                    <td class="helpBod"><?php echo $StatesList[$i]['name'] ?></td>
                                                                    <td class="helpBod"> <?php echo $countryName; ?></td>
                                                                    <td class="helpBod"><?php if($StatesList[$i]['status']==1){echo 'Active';}elseif($StatesList[$i]['status']==0){echo 'Inactive';}elseif($StatesList[$i]['status']==2){echo 'New';} ?></td>
                                                                    <td class="helpBod"><a href="state_edit.php?id=<?php echo $StatesList[$i]['id']; ?>">Edit</a></td>
                                                                    <td class="helpBod"><label>
                                                                            <input type="checkbox" name="state[]" value="<?php echo $StatesList[$i]['id']; ?>" />
                                                                        </label></td>
                                                                </tr>
                                                            <?php }
                                                            ?>
                                                        </table></td>
                                                </tr>
                                                <tr id="addedit">
                                                    <td><label>
                                                            <div align="right">
                                                                <input type="button" name="Add" value="Add" onClick="document.location = 'addstate.php'">
                                                            </div>
                                                        </label></td>
                                                    <td><label>
                                                            <div align="right">
                                                                <input type="submit" name="Submit" value="Delete" onClick="return confirm('Are You Sure You Want To Delete These States.\n\nThe Changes Cannot Be Undone');">
                                                            </div>
                                                        </label></td>
                                                </tr>
                                            </table>
                                        </form>
                                        <div align="center" style="width:100%">&nbsp;</div>
                                    </div>
                                    <!-------------------------------CONTENT PAGE ENDS HERE--------------------------------------------------------------->



                                </div>
                            </td>
                        </tr>
                    </table>
                    </div>	
                </body>
                </html>