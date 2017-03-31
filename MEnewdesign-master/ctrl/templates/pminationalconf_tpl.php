<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<head>
		<title>MeraEvents -Menu Content Management</title>
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
		<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
        <script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>	
        <script>
			function isSearchFieldEmpty(){
				var isValid=true;
				if(document.getElementById('searchKeyword').value.trim()==""){
					isValid=false;
					alert("Keyword cannot be empty")
				}
						
				return isValid;
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
<div style="width:100%" align="center">
<table>
      <tr>
        <td colspan="2" class="headtitle" align="center"><strong>PMI Chennai Data</strong> </td>
      </tr>
      
      <?php 
	  if(isset($_SESSION['pmiAdded']))
	  {
		  ?><tr><td colspan="2" style="color:#090; font-weight:bold; font-size:14px">New Member added successfully..</td></tr><?php
		  unset($_SESSION['pmiAdded']);
	  }
	  if(isset($_SESSION['duplicatePmi']))
	  {
		  ?><tr><td colspan="2" style="color:#F00; font-weight:bold; font-size:14px"><?=$_SESSION['duplicateMsg'];?></td></tr><?php
		  unset($_SESSION['duplicatePmi']);
		  unset($_SESSION['duplicateMsg']);
	  }
	  if(isset($_SESSION['pmiDeleted']))
	  {
		  ?><tr><td colspan="2" style="color:#090; font-weight:bold; font-size:14px">Member deleted successfully..</td></tr><?php
		  unset($_SESSION['pmiDeleted']);
	  }
	  ?>
      
      <tr>
        <td colspan="2">
        
        <form method="post" action="">
        	<table> 
                <tr>
                	<td>Member Id:&nbsp;</td>
                    <td><input type="text" name="memberid" id="memberid" /><br/></td>
                </tr>
                <tr>
                    <td>Certificate Id:&nbsp;</td>
                    <td><input type="text" name="certificateid" id="memberid" /><br/></td>
                    </tr>
                    <tr>
                    <td>Name:&nbsp;</td>
                    <td><input type="text" name="memname" id="memname" /><br/></td>
                    </tr>
                    <tr>
                    <td>Email:&nbsp;</td>
                    <td><input type="text" name="mememail" id="mememail" /></td>
                    </tr>
                    <tr>
                    <td>Mobile:&nbsp;</td>
                    <td><input type="text" name="memmobile" id="memmobile" /></td>
                    </tr>
                    <tr>
                    <td>Member Id Status:&nbsp;</td>
                    <td><input type="text" name="memidstatus" id="memidstatus" /></td>
                    </tr>
                    <tr>
                    <td>Certificate Id Status:&nbsp;</td>
                    <td><input type="text" name="memcertidstatus" id="memcertidstatus" /></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="center"></td>
                    </tr>
                    <tr>
                    <td colspan="2" align="center"><input type="submit" name="addMember" value="ADD" /></td>
                    </tr>
               </tr>
               <tr><td colspan="3"><br />&nbsp;</td></tr>
            </table>
        </form>
        </td>
      </tr>
      <tr>
        						<td  colspan="2" class="headtitle" align="center"><strong>Search for PMI Chennai Data</strong> </td>
     						</tr>
      	<tr>
        	<td colspan="2"><table width="100%">
            	<tr><td><form action="" name="searchForm" method="post" onsubmit="return isSearchFieldEmpty();">
            			<table>
                       		<tr>
                            	<td align="center">            	
                            		<label for="searchKeyword">Enter keyword:</label>
                                    <input type="text" name="searchKeyword" id="searchKeyword"/>
                                 </td>
                                 <td class="headtitle" align="center">
                                  	<input type="submit" name="searchSubmit" value="Search"/><br/>
                                 </td>
                              </tr>
                              <tr>
                              	 <td colspan="2">   
                                    <span id="textEx" style="margin-left:113px;">Eg:10,rahul,etc</span>
                                    <input type="hidden" name="searchForThisKeyword" value="1"/>
                                </td>
                            </tr>
                        </table>
                        </form></td></tr>
            </table>
            </td>
        </tr>
      <tr>
        <td colspan="2"><table width="100%" class="sortable">
          <thead>
          <tr>
            <td class="tblcont1"><strong>Membership Id</strong> </td>
            <td class="tblcont1"><strong>Certificate Id</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Name</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Email</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Mobile</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Member Id Status</strong></td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Certificate Id Status</strong> </td>
            <td class="tblcont1" ts_nosort="ts_nosort"><strong>Delete</strong></td>
          </tr></thead>
		  <?php 
		  $totMem=count($pmiList);
		  if($totMem>0){
			for($i = 0; $i < $totMem; $i++)
			{  
		  ?>
          <tr>
            <td class="helpBod"><?php echo $pmiList[$i]['memberId'];	?></td>
            <td class="helpBod"><?php echo $pmiList[$i]['certificateId']; ?></td>
            <td class="helpBod"><?php echo $pmiList[$i]['Name']; ?></td>
            <td class="helpBod"><?php echo $pmiList[$i]['Email']; ?></td>
            <td class="helpBod"><?php echo $pmiList[$i]['Mobile']; ?></td>
            <td class="helpBod"><?php echo ($pmiList[$i]['statusM']==1)?'Used':'Unused'; ?></td>
             <td class="helpBod"><?php echo ($pmiList[$i]['statusC']==1)?'Used':'Unused'; ?></td>
            <td class="helpBod"><a href="?delmember=<?=$pmiList[$i]['id']?>&page=<?=$page;?>"  onClick="return confirm('Are You Sure You Want To Delete this Member.\n\nThe Member Cannot Be Undone');">Delete</a></td>
          </tr>
		  <?php 
			  }
		  }else{ ?>
		  	<td colspan="8" align="center">Sorry,no records found.</td>
		<?  }
		  ?>
        </table></td>
      </tr>
      	<tr>
        	<td colspan="2"><? echo $functions->pagination($limit,$page,'pminationalconf.php?page=',$rows); //call function to show pagination?></td>
        </tr>
        	
    </table>
    
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