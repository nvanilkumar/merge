<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <title>MeraEvents -Menu Content Management</title>
        <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
            <link href="<?= _HTTP_CF_ROOT; ?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortable.min.js.gz"></script>	
                <script language="javascript" src="<?= _HTTP_CF_ROOT; ?>/ctrl/css/sortpagi.min.js.gz"></script>	
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

                        <script language="javascript">
                            document.getElementById('ans2').style.display = 'block';
                        </script>
                        <div style="width:100%" align="center">
                            <form action="auto_suggest_review.php" method="post" name="edit_form">
                                <table width="60%" align="center" class="tblcont">
    <tbody><tr><td align="center" style="line-height:30px; padding:11px 0 0 141px; text-decoration:underline">Auto Suggestion Review</td></tr>

            <tr>
				<td align="left" valign="middle" class="tblcont">Select Review Type :
					<select name="review_type" id="review_type">
                        <option value="">Select</option>
                        <option value="country">Country Review</option>
                        <option value="state">State Review</option>
                        <option value="city">City Review</option>
                        <!--option value="location">Location Review</option>
                        <option value="designation">Designation Review</option-->
				  </select>
				</td>
                <td align="left" valign="middle" class="tblcont">
                    <input type="checkbox" name="event_records" id="event_records" 
                           value="event_records" /> Event Related Records<br/>
                    <input type="checkbox" name="published" id="published" 
                           value="published" /> Published Events<br/>
                </td>
                <td align="left" valign="middle" class="tblcont">
                    <input type="submit" name="submit" value="Show Records"/>
                </td>
			</tr>

      <tr id="success_tr" ><td colspan="2" valign="middle" class="headtitle">
              <b style="color:#090">  <?=$status_message ?>
                                                 
                                                    </b>
                                                
                                                </td></tr> 
</tbody></table>
                            </form>
                            <div align="center" style="width:100%">&nbsp;</div>
                            <?php $recourds_count=count($record_list);
                                    if($recourds_count > 0){
                                ?>
                              <table width="95%">
                               
                               
                                <tr>
                                    <td colspan="2"><table style="width:100%;" class="sortable">
                                            <thead>
                                                <tr>
                                                    <?php foreach($table_head as $head_value){ ?>
                                                         <td class="tblcont1"><strong><?=$head_value;?></strong> </td>
                                                    <?php }?>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php  for ($i = 0; $i < $recourds_count; $i++) { ?>
                                                <tr>
                                                     <?php foreach($table_body_field_names as $key_name => $field_name){ 
                                                         if($key_name == "Edit"){ ?>
             <td class="helpBod"><a href="<?=$field_name.$record_list[$i]['id']?>">Edit</a></td>  
                                                     <?php    }else if($key_name == "Status"){ ?>
             <td class="helpBod">     <a href="auto_suggest_review.php?&edit_record=<?=$record_type?>&id=<?=$record_list[$i]['id']?>">Add to list</a>
             <br/><a href="auto_suggest_review.php?&edit_record=<?=$record_type?>&id=<?=$record_list[$i]['id']?>&ignore_type=yes">
                     Ignore the List</a>
                    
             </td>      
                                                      
                                                     <?php    }else if($key_name == "Event_Details"){  
//		$window_url =  "../dashboard-aevent?EventId=".$record_list[$i]["eId"]."&admin=Yes";
                $window_url =  _HTTP_SITE_ROOT."/api/user/adminSession?organizerId=".$record_list[$i]['ownerid']."&eventid=".$record_list[$i]["eId"]."&adminId=".$uid."&userType=".$_SESSION['adminUserType'];
            
		?>
             <td width="580" align="left" valign="middle" class="helpBod"><a href="#" onclick="window.open('<?=$window_url?>','mywindow','menubar=1,width=900,height=600,resizable=yes,scrollbars=yes');">
                 <?= ($record_list[$i]["eId"] > 0)?$record_list[$i]["title"]."(". $record_list[$i]["eId"].")":""?>
                 </a></td>
             
     <?php    }else if($key_name == "Event Status"){  
		 
         ?><td class="helpBod" style="color: #<?= ($record_list[$i][$field_name]==1)?"0DE410":"FA061B";?>;"> 
             <?php 
                    if($record_list[$i][$field_name]==1){
                        echo "Published";
                    }else if($record_list[$i][$field_name]==0 && $record_list[$i]["eId"] > 0){
                        echo " UnPublished";
                    }
             
             ?>
             
            
         </td>
                                                      <?php   }else { ?>
                                     <td class="helpBod"><?= $record_list[$i][$field_name];	?></td>                                                       <?php }    
 
                                                   } //end of foreach?>
                                                   
                                                </tr>
                                            <?php }  ?>
                                            </tbody>     
                                        </table></td>
                                </tr>
                               
                            </table>
                                    <?php } ?>
                          
                        </div>

                    </div>
                </td>
            </tr>
        </table>
        </div>	
        <script src="<?= _HTTP_CF_ROOT; ?>/js/public/jQuery.js"></script>
        <?php 
        $record_type="";
        if($_POST["review_type"]){
            $record_type=$_POST["review_type"];
        }else if($_GET["edit_record"]){
             $record_type=$_GET["edit_record"];
        }else if($_GET["update_record"]){
             $record_type=$_GET["update_record"];
        }
        
        $check_box_status="false";
        if($_SESSION["event_records"]==="event_records"){
            $check_box_status="true";
        }
        $check_box_published="false";
        if($_SESSION["published"]==="published"){
            $check_box_published="true";
        }
        
        ?>
        <script>
            $(function(){
                var record_type="<?=($record_type)?$record_type:''; ?>";
                
                $("#review_type").val(record_type);
                $("#event_records").prop('checked', <?=$check_box_status?>); 
                 $("#published").prop('checked', <?=$check_box_published?>); 
            });
        </script>    
    </body>
</html>
