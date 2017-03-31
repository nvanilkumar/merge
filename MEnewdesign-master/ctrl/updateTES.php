<?php


//class updateTES{
//    private $table="onlyfor_tes";
// global $Global;
session_start();
include 'loginchk.php';
    include_once 'MT/cGlobal.php';
    $Global=new cGlobal();
    function insertInto($table,$membershipType,$email,$status,$jumpstart){
        global $Global;
        $table=$Global->realEscape($table);
        $membershipType=  $Global->realEscape($membershipType);
        $email=  $Global->realEscape($email);
        //$status= $Global->realEscape($status);
        $status= 0;
//        $jumpstart= $Global->realEscape($jumpstart);
        $jumpstart= 0;
        
    
        $insertionQuery="insert into `".$table."` (`Id`,`MembershipType`,`Email`,`Status`) values ('','".$membershipType."','".$email."','".$status."' )";
       //echo $insertionQuery;
        $insertSucess=$Global->ExecuteQuery($insertionQuery);
    }
    
    function delete($table,$deleteId){
        global $Global;
        $table=$Global->realEscape($table);
        $deleteId=$Global->realEscape($deleteId);
       $deleteQuery="DELETE FROM ".$table."
                    WHERE Id='".$deleteId."'
                    AND `MembershipType` in('productnation-summit-2013-scale-hacking-stage-boot','productnation-summit-2013')";
     //   echo $deleteQuery;
        $deleteSucess=$Global->ExecuteQuery($deleteQuery);
        
    };
    function update($table, $setFieldName, $seTo,$where){
        global $Global;
        $table=$Global->realEscape($table);
        $setFieldName=$Global->realEscape($setFieldName);
        $seTo=$Global->realEscape($seTo);
        $where=$Global->realEscape($where);
        
        $updateQuery="UPDATE ".$table."
                    SET ".$setFieldName."=value1,column2=value2,...
                    WHERE some_column=some_value";
        
        
    }
    
//}

//$tes=new updateTES();
    if(isset($_POST['insert']))
    {
       // print_r($_POST);
insertInto('onlyfor_tes', $_POST['membershipType'], $_POST['email'], 0, 0);
    }
    if(isset($_REQUEST['deleteId']))
    {
        delete('onlyfor_tes', $_REQUEST['deleteId']);
    }
    
    $displayQuery="select * from `onlyfor_tes` where `MembershipType` in ('productnation-summit-2013-scale-hacking-stage-boot','productnation-summit-2013') order by `Id` DESC";
    //echo $displayQuery;
    $displayRes=$Global->SelectQuery($displayQuery);
?>

<html>
    <form method="post" action="">
        
        <label for="membershipType">Event ID :</label>
        <select name="membershipType" id="membershipType">
        <option value="productnation-summit-2013-scale-hacking-stage-boot" >41471</option>
        <option value="productnation-summit-2013" >39211</option>
 
        </select>
        <label for="email">Email :</label>
        <input type="text" name="email" id="email">
        <input type="submit" name="insert" value="Insert">       
    </form>
    
    
    
    <table width="100%" align="center" >
    <tr>
        <td width="5%" align="left" valign="middle" class="tblcont1">Sr. No.</td>
        <td width="5%" align="left" valign="middle" class="tblcont1">ID</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Membership Type</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Email</td>
        <td width="20%" align="left" valign="middle" class="tblcont1" ts_nosort="ts_nosort">Status</td>
        <td width="10%" align="left" valign="middle" class="tblcont1">Action</td>
    </tr>
  <? 
  if(count($displayRes)>0)
  {
      $srNo=1;
      foreach ($displayRes as $value) {
          
          $status="Unused";
          if($value['Status']==1)
          {
              $status="Used";
          }
           echo '<tr>
                   <td> '.$srNo.'</td>
                   <td>'.$value['Id'].'</td>
                   <td>'.$value['MembershipType'].'</td>
                   <td>'.$value['Email'].'</td>
                   <td>'.$status.'</td>
                   <td><a href="updateTES.php?deleteId='.$value['Id'].'">Delete</a></td>
                </tr>';
           
           
           $srNo++;
      }
     
      
  }
  
  ?>
    
    </table>
</html>


