<?php
/******************************************************************************************************************************************
 *	File deatils:
 *	It displays the city list as per the states list.
 *	
 *	Created / Updated on:
 *	1.	Using the MT the file is updated on 22nd Aug 2009
******************************************************************************************************************************************/
	session_start();
		
	include_once("MT/cGlobal.php");
	
	$Global = new cGlobal();
        include 'loginchk.php';	
	
	if($_POST['submit'] == "Submit" || $_POST['submit'] == "Upload")
	{
		
	$data='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upcoming Events Happening in India</title>
</head>

<body style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51); background-color: rgb(222, 222, 222); margin:0px;">
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="750" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
      <tr>
        <td align="center" valign="top" bgcolor="#DEDEDE"><table width="700" cellspacing="0" cellpadding="0" border="0" align="center">
            <tbody>
              <tr>
                <td align="center" style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51); font-size: 11px; line-height: 18px;"><table border="0" bordercolor="#dddddd"  bordercolordark="#FFFFFF" width="99%" id="Salutation" cellpadding="0" cellspacing="0" style="font: 12px verdana">
                    <tr>
                      <td align="left" valign="bottom" style="color: #a84040; font: bold 12px verdana"></td>
                      <td align="right" width="100" id="PoweredBy"></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51);"><table width="723" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                      <tr>
                        <td align="center" valign="top" style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51);"><font face="Calibri, Trebuchet MS, sans-serif, Arial" size="2px" color="#666666">If you are unable to see the newsletter below, please <a href="http://www.meraevents.com/emailer/'.$_REQUEST[clickhere].'" target="_blank">Click here.</a></font></td>
                      </tr>
                      <tr>
                        <td valign="top" style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51);"><img src="http://www.meraevents.com/new_emailer/top2.jpg" alt="Logo" width="775" height="165" border="0" usemap="#Map3" />
                          <map name="Map3" id="Map3">
                            <area shape="rect" coords="19,14,360,149" href="#" alt="Meraevents.com" />
                          </map></td>
                      </tr>
                    </tbody>
                </table></td>
              </tr>
              <tr>
                <td valign="top" bgcolor="#ffffff" style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51);"><table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" align="center">
                    <tbody>
                      <tr></tr>
                      <tr>
                        <td height="250" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                            <tr></tr>
                            <tr>
                              <td valign="top" style="padding:1px;"></td>
                            </tr>
                            <tr>
                              <td valign="top" style="padding:1px;"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-bottom: 2px solid rgb(0, 73, 129); font-family: arial;">
                                  <tbody>';
								  if($_REQUEST['Webinarbanner']!=""){						  
		$sql_eventWebinar="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['Webinarbanner']."'";
$res_eventWebinar=$Global->SelectQuery($sql_eventWebinar);
if(count($res_eventWebinar)>0){
$WebinarurlWebinar="http://www.meraevents.com/event/".$res_eventWebinar[0][URL];
$WebinarbannerWebinar="http://content.meraevents.com/".$res_eventWebinar[0][Banner];
$WebinartitleWebinar=stripslashes($res_eventWebinar[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventWebinar[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventWebinar[0][EndDt]));
  $timeWebinar=$StartDt;
  if($StartDt!=$EndDt){
  $timeWebinar.=" - ".$EndDt; 
  }  
			   
  $timeWebinar.=" Time: ".date('g:i a',strtotime($res_eventWebinar[0][StartDt]))."-".date('g:i a',strtotime($res_eventWebinar[0][EndDt])); 
}									
									
$data.='
 <tr>
                                    <td colspan="4" valign="middle" bgcolor="#007797" style="border-bottom: 1px solid rgb(238, 238, 238);"><span style="font-size: 15px; color: #FFFFFF"><strong>Webinar </strong> <a name="Chennai" id="Chennai"></a></span></td>
                                  </tr>
<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$WebinarurlWebinar.'" target="_blank"><img src="'.$WebinarbannerWebinar.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$WebinarurlWebinar.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$WebinartitleWebinar.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timeWebinar.'</strong></div></td>
                                    </tr>';							  							  
                               
$sql_Webinarevent1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Webinarevent1']."'";
$res_Webinarevent1=$Global->SelectQuery($sql_Webinarevent1);
if(count($res_Webinarevent1)>0){
$Webinarurl1="http://www.meraevents.com/event/".$res_Webinarevent1[0][URL];
$Webinarlogo1="http://content.meraevents.com/".$res_Webinarevent1[0][Logo];
$Webinartitle1=stripslashes($res_Webinarevent1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_Webinarevent1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Webinarevent1[0][EndDt]));
  $Webinartime1=$StartDt;
  if($StartDt!=$EndDt){
  $Webinartime1.=" - ".$EndDt; 
  }  
			   
  $Webinartime1.=" Time: ".date('g:i a',strtotime($res_Webinarevent1[0][StartDt]))."-".date('g:i a',strtotime($res_Webinarevent1[0][EndDt])); 
}
$sql_Webinarevent2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Webinarevent2']."'";
$res_Webinarevent2=$Global->SelectQuery($sql_Webinarevent2);
if(count($res_Webinarevent2)>0){
$Webinarurl2="http://www.meraevents.com/event/".$res_Webinarevent2[0][URL];
$Webinarlogo2="http://content.meraevents.com/".$res_Webinarevent2[0][Logo];
$Webinartitle2=stripslashes($res_Webinarevent2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Webinarevent2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Webinarevent2[0][EndDt]));
  $Webinartime2=$StartDt;
  if($StartDt!=$EndDt){
  $Webinartime2.=" - ".$EndDt; 
  } 		   
  $Webinartime2.=" Time: ".date('g:i a',strtotime($res_Webinarevent2[0][StartDt]))."-".date('g:i a',strtotime($res_Webinarevent2[0][EndDt])); 
}
$sql_Webinarevent3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Webinarevent3']."'";
$res_Webinarevent3=$Global->SelectQuery($sql_Webinarevent3);
if(count($res_Webinarevent3)>0){
$Webinarurl3="http://www.meraevents.com/event/".$res_Webinarevent3[0][URL];
$Webinarlogo3="http://content.meraevents.com/".$res_Webinarevent3[0][Logo];
$Webinartitle3=stripslashes($res_Webinarevent3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Webinarevent3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Webinarevent3[0][EndDt]));
  $Webinartime3=$StartDt;
  if($StartDt!=$EndDt){
  $Webinartime3.=" - ".$EndDt; 
  } 		   
  $Webinartime3.=" Time: ".date('g:i a',strtotime($res_Webinarevent3[0][StartDt]))."-".date('g:i a',strtotime($res_Webinarevent3[0][EndDt])); 
}
$sql_Webinarevent4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Webinarevent4']."'";
$res_Webinarevent4=$Global->SelectQuery($sql_Webinarevent4);
if(count($res_Webinarevent4)>0){
$Webinarurl4="http://www.meraevents.com/event/".$res_Webinarevent4[0][URL];
$Webinarlogo4="http://content.meraevents.com/".$res_Webinarevent4[0][Logo];
$Webinartitle4=stripslashes($res_Webinarevent4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Webinarevent4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Webinarevent4[0][EndDt]));
  $Webinartime4=$StartDt;
  if($StartDt!=$EndDt){
  $Webinartime4.=" - ".$EndDt; 
  } 		   
  $Webinartime4.=" Time: ".date('g:i a',strtotime($res_Webinarevent4[0][StartDt]))."-".date('g:i a',strtotime($res_Webinarevent4[0][EndDt])); 
}

$sql_Webinarevent5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Webinarevent5']."'";
$res_Webinarevent5=$Global->SelectQuery($sql_Webinarevent5);
if(count($res_Webinarevent5)>0){
$Webinarurl5="http://www.meraevents.com/event/".$res_Webinarevent5[0][URL];
$Webinarlogo5="http://content.meraevents.com/".$res_Webinarevent5[0][Logo];
$Webinartitle5=stripslashes($res_Webinarevent5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Webinarevent5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Webinarevent5[0][EndDt]));
  $Webinartime5=$StartDt;
  if($StartDt!=$EndDt){
  $Webinartime5.=" - ".$EndDt; 
  } 		   
  $Webinartime5.=" Time: ".date('g:i a',strtotime($res_Webinarevent5[0][StartDt]))."-".date('g:i a',strtotime($res_Webinarevent5[0][EndDt])); 
}
$sql_Webinarevent6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Webinarevent6']."'";
$res_Webinarevent6=$Global->SelectQuery($sql_Webinarevent6);
if(count($res_Webinarevent6)>0){
$Webinarurl6="http://www.meraevents.com/event/".$res_Webinarevent6[0][URL];
$Webinarlogo6="http://content.meraevents.com/".$res_Webinarevent6[0][Logo];
$Webinartitle6=stripslashes($res_Webinarevent6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Webinarevent6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Webinarevent6[0][EndDt]));
  $Webinartime6=$StartDt;
  if($StartDt!=$EndDt){
  $Webinartime6.=" - ".$EndDt; 
  } 		   
  $Webinartime6.=" Time: ".date('g:i a',strtotime($res_Webinarevent6[0][StartDt]))."-".date('g:i a',strtotime($res_Webinarevent6[0][EndDt])); 
}
									
									
if($Webinarurl1!="") {								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Webinarurl1.'" target="_blank"><img src="'.$Webinarlogo1.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Webinarurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Webinartitle1.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Webinartime1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Webinarurl2.'" target="_blank"><img src="'.$Webinarlogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Webinarurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Webinartitle2.' </a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Webinartime2.'</strong><br />
                                        </div></td>
                                    </tr>';
} if($Webinarurl3!="") {								
 $data.='<tr><td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Webinarurl3.'" target="_blank"><img src="'.$Webinarlogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Webinarurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Webinartitle3.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Webinartime3.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Webinarurl4.'" target="_blank"><img src="'.$Webinarlogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Webinarurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Webinartitle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong> '.$Webinartime4.'</strong><br />
                                        </div></td>
                                    </tr>';
}if($Webinarurl5!="") {								
 $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Webinarurl5.'" target="_blank"><img src="'.$Webinarlogo5.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Webinarurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Webinartitle5.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Webinartime5.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Webinarurl6.'"><img src="'.$Webinarlogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Webinarurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Webinartitle6.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Webinartime6.'</strong><br />
                                        </div></td>
                                    </tr>';
}
}
                                    $data.='<tr>
                                      <td colspan="4" valign="middle" bgcolor="#007797"><span style="font-size: 15px; color: #FFFFFF"><b>Hyderabad / Secunderabad</b></span></td>
                                    </tr>';

$sql_eventhyd="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['hydbanner']."'";
$res_eventhyd=$Global->SelectQuery($sql_eventhyd);
if(count($res_eventhyd)>0){
$hydurlhyd="http://www.meraevents.com/event/".$res_eventhyd[0][URL];
$hydbannerhyd="http://content.meraevents.com/".$res_eventhyd[0][Banner];
$hydtitlehyd=stripslashes($res_eventhyd[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventhyd[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventhyd[0][EndDt]));
  $timehyd=$StartDt;
  if($StartDt!=$EndDt){
  $timehyd.=" - ".$EndDt; 
  }  
			   
  $timehyd.=" Time: ".date('g:i a',strtotime($res_eventhyd[0][StartDt]))."-".date('g:i a',strtotime($res_eventhyd[0][EndDt])); 
}									
									
$data.='<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$hydurlhyd.'" target="_blank"><img src="'.$hydbannerhyd.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$hydurlhyd.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$hydtitlehyd.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timehyd.'</strong></div></td>
                                    </tr>';
									  
									  
									  
$sql_event1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['hydevent1']."'";
$res_event1=$Global->SelectQuery($sql_event1);
if(count($res_event1)>0){
$hydurl1="http://www.meraevents.com/event/".$res_event1[0][URL];
$hydlogo1="http://content.meraevents.com/".$res_event1[0][Logo];
$hydtitle1=stripslashes($res_event1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_event1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_event1[0][EndDt]));
  $time1=$StartDt;
  if($StartDt!=$EndDt){
  $time1.=" - ".$EndDt; 
  }  
			   
  $time1.=" Time: ".date('g:i a',strtotime($res_event1[0][StartDt]))."-".date('g:i a',strtotime($res_event1[0][EndDt])); 
}
$sql_event2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['hydevent2']."'";
$res_event2=$Global->SelectQuery($sql_event2);
if(count($res_event2)>0){
$hydurl2="http://www.meraevents.com/event/".$res_event2[0][URL];
$hydlogo2="http://content.meraevents.com/".$res_event2[0][Logo];
$hydtitle2=stripslashes($res_event2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_event2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_event2[0][EndDt]));
  $time2=$StartDt;
  if($StartDt!=$EndDt){
  $time2.=" - ".$EndDt; 
  } 		   
  $time2.=" Time: ".date('g:i a',strtotime($res_event2[0][StartDt]))."-".date('g:i a',strtotime($res_event2[0][EndDt])); 
}
$sql_event3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['hydevent3']."'";
$res_event3=$Global->SelectQuery($sql_event3);
if(count($res_event3)>0){
$hydurl3="http://www.meraevents.com/event/".$res_event3[0][URL];
$hydlogo3="http://content.meraevents.com/".$res_event3[0][Logo];
$hydtitle3=stripslashes($res_event3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_event3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_event3[0][EndDt]));
  $time3=$StartDt;
  if($StartDt!=$EndDt){
  $time3.=" - ".$EndDt; 
  } 		   
  $time3.=" Time: ".date('g:i a',strtotime($res_event3[0][StartDt]))."-".date('g:i a',strtotime($res_event3[0][EndDt])); 
}
$sql_event4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['hydevent4']."'";
$res_event4=$Global->SelectQuery($sql_event4);
if(count($res_event4)>0){
$hydurl4="http://www.meraevents.com/event/".$res_event4[0][URL];
$hydlogo4="http://content.meraevents.com/".$res_event4[0][Logo];
$hydtitle4=stripslashes($res_event4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_event4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_event4[0][EndDt]));
  $time4=$StartDt;
  if($StartDt!=$EndDt){
  $time4.=" - ".$EndDt; 
  } 		   
  $time4.=" Time: ".date('g:i a',strtotime($res_event4[0][StartDt]))."-".date('g:i a',strtotime($res_event4[0][EndDt])); 
}

$sql_event5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['hydevent5']."'";
$res_event5=$Global->SelectQuery($sql_event5);
if(count($res_event5)>0){
$hydurl5="http://www.meraevents.com/event/".$res_event5[0][URL];
$hydlogo5="http://content.meraevents.com/".$res_event5[0][Logo];
$hydtitle5=stripslashes($res_event5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_event5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_event5[0][EndDt]));
  $time5=$StartDt;
  if($StartDt!=$EndDt){
  $time5.=" - ".$EndDt; 
  } 		   
  $time5.=" Time: ".date('g:i a',strtotime($res_event5[0][StartDt]))."-".date('g:i a',strtotime($res_event5[0][EndDt])); 
}
$sql_event6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['hydevent6']."'";
$res_event6=$Global->SelectQuery($sql_event6);
if(count($res_event6)>0){
$hydurl6="http://www.meraevents.com/event/".$res_event6[0][URL];
$hydlogo6="http://content.meraevents.com/".$res_event6[0][Logo];
$hydtitle6=stripslashes($res_event6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_event6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_event6[0][EndDt]));
  $time6=$StartDt;
  if($StartDt!=$EndDt){
  $time6.=" - ".$EndDt; 
  } 		   
  $time6.=" Time: ".date('g:i a',strtotime($res_event6[0][StartDt]))."-".date('g:i a',strtotime($res_event6[0][EndDt])); 
}
									
									
		if($hydurl1!=""){							
 $data.='<tr>
         <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$hydurl1.'" target="_blank"><img src="'.$hydlogo1.'" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$hydurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$hydtitle1.'</strong></a></b>
                                        <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$time1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$hydurl2.'" target="_blank"><img src="'.$hydlogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$hydurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$hydtitle2.'</strong></a></b>
                                        <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$time2.'</strong></div></td>
                                    </tr>';
	}
    if($hydurl3!=""){                               
$data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$hydurl3.'" target="_blank"><img src="'.$hydlogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$hydurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$hydtitle3.'</strong></a><br />
                                       </b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; " >'.$time3.'<br />
                                          </div>
                                        </div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$hydurl4.'" target="_blank"><img src="'.$hydlogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$hydurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$hydtitle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$time4.'</strong></div></td>
                                    </tr>';
	} if($hydurl5!="") {
 $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$hydurl5.'" target="_blank"><img src="'.$hydlogo5.'" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$hydurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$hydtitle5.' </strong></a><br />
                                        </b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; " > <strong>'.$time5.'</strong><br />
                                          </div>
                                        </div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$hydurl6.'" target="_blank"><img src="'.$hydlogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$hydurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$hydtitle6.'  </strong></a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; " > <strong>'.$time6.'</strong><br />
                                          </div>
                                        </div></td>
                                    </tr>';
        }                           
$data.='<tr>
                                      <td colspan="4" align="right" valign="middle" bgcolor="#FFFFFF"><a href="http://www.meraevents.com/Hyderabad-Events" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A88D9">More Events &gt;&gt;</a></td>
                                    </tr>
                                  </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td align="right" valign="middle" bgcolor="#CECECE" style="padding:1px;"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-bottom: 2px solid rgb(0, 73, 129); font-family: arial;">
                                  <tbody>
                                    <tr>
                                      <td colspan="4" valign="middle" bgcolor="#007797" style="border-bottom: 1px solid rgb(238, 238, 238);"><span style="font-size: 15px; color: #FFFFFF"><b>Delhi / NCR<a name="delhi" id="delhi"></a></b></span></td>
                                    </tr>';
$sql_Delhievent1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Delhievent1']."'";
$res_Delhievent1=$Global->SelectQuery($sql_Delhievent1);
if(count($res_Delhievent1)>0){
$Delhiurl1="http://www.meraevents.com/event/".$res_Delhievent1[0][URL];
$Delhilogo1="http://content.meraevents.com/".$res_Delhievent1[0][Logo];
$Delhititle1=stripslashes($res_Delhievent1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_Delhievent1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Delhievent1[0][EndDt]));
  $Delhitime1=$StartDt;
  if($StartDt!=$EndDt){
  $Delhitime1.=" - ".$EndDt; 
  }  
			   
  $Delhitime1.=" Time: ".date('g:i a',strtotime($res_Delhievent1[0][StartDt]))."-".date('g:i a',strtotime($res_Delhievent1[0][EndDt])); 
}
$sql_Delhievent2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Delhievent2']."'";
$res_Delhievent2=$Global->SelectQuery($sql_Delhievent2);
if(count($res_Delhievent2)>0){
$Delhiurl2="http://www.meraevents.com/event/".$res_Delhievent2[0][URL];
$Delhilogo2="http://content.meraevents.com/".$res_Delhievent2[0][Logo];
$Delhititle2=stripslashes($res_Delhievent2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Delhievent2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Delhievent2[0][EndDt]));
  $Delhitime2=$StartDt;
  if($StartDt!=$EndDt){
  $Delhitime2.=" - ".$EndDt; 
  } 		   
  $Delhitime2.=" Time: ".date('g:i a',strtotime($res_Delhievent2[0][StartDt]))."-".date('g:i a',strtotime($res_Delhievent2[0][EndDt])); 
}
$sql_Delhievent3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Delhievent3']."'";
$res_Delhievent3=$Global->SelectQuery($sql_Delhievent3);
if(count($res_Delhievent3)>0){
$Delhiurl3="http://www.meraevents.com/event/".$res_Delhievent3[0][URL];
$Delhilogo3="http://content.meraevents.com/".$res_Delhievent3[0][Logo];
$Delhititle3=stripslashes($res_Delhievent3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Delhievent3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Delhievent3[0][EndDt]));
  $Delhitime3=$StartDt;
  if($StartDt!=$EndDt){
  $Delhitime3.=" - ".$EndDt; 
  } 		   
  $Delhitime3.=" Time: ".date('g:i a',strtotime($res_Delhievent3[0][StartDt]))."-".date('g:i a',strtotime($res_Delhievent3[0][EndDt])); 
}
$sql_Delhievent4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Delhievent4']."'";
$res_Delhievent4=$Global->SelectQuery($sql_Delhievent4);
if(count($res_Delhievent4)>0){
$Delhiurl4="http://www.meraevents.com/event/".$res_Delhievent4[0][URL];
$Delhilogo4="http://content.meraevents.com/".$res_Delhievent4[0][Logo];
$Delhititle4=stripslashes($res_Delhievent4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Delhievent4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Delhievent4[0][EndDt]));
  $Delhitime4=$StartDt;
  if($StartDt!=$EndDt){
  $Delhitime4.=" - ".$EndDt; 
  } 		   
  $Delhitime4.=" Time: ".date('g:i a',strtotime($res_Delhievent4[0][StartDt]))."-".date('g:i a',strtotime($res_Delhievent4[0][EndDt])); 
}

$sql_Delhievent5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Delhievent5']."'";
$res_Delhievent5=$Global->SelectQuery($sql_Delhievent5);
if(count($res_Delhievent5)>0){
$Delhiurl5="http://www.meraevents.com/event/".$res_Delhievent5[0][URL];
$Delhilogo5="http://content.meraevents.com/".$res_Delhievent5[0][Logo];
$Delhititle5=stripslashes($res_Delhievent5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Delhievent5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Delhievent5[0][EndDt]));
  $Delhitime5=$StartDt;
  if($StartDt!=$EndDt){
  $Delhitime5.=" - ".$EndDt; 
  } 		   
  $Delhitime5.=" Time: ".date('g:i a',strtotime($res_Delhievent5[0][StartDt]))."-".date('g:i a',strtotime($res_Delhievent5[0][EndDt])); 
}
$sql_Delhievent6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Delhievent6']."'";
$res_Delhievent6=$Global->SelectQuery($sql_Delhievent6);
if(count($res_Delhievent6)>0){
$Delhiurl6="http://www.meraevents.com/event/".$res_Delhievent6[0][URL];
$Delhilogo6="http://content.meraevents.com/".$res_Delhievent6[0][Logo];
$Delhititle6=stripslashes($res_Delhievent6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Delhievent6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Delhievent6[0][EndDt]));
  $Delhitime6=$StartDt;
  if($StartDt!=$EndDt){
  $Delhitime6.=" - ".$EndDt; 
  } 		   
  $Delhitime6.=" Time: ".date('g:i a',strtotime($res_Delhievent6[0][StartDt]))."-".date('g:i a',strtotime($res_Delhievent6[0][EndDt])); 
}
									
									


$sql_eventDelhi="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['Delhibanner']."'";
$res_eventDelhi=$Global->SelectQuery($sql_eventDelhi);
if(count($res_eventDelhi)>0){
$DelhiurlDelhi="http://www.meraevents.com/event/".$res_eventDelhi[0][URL];
$DelhibannerDelhi="http://content.meraevents.com/".$res_eventDelhi[0][Banner];
$DelhititleDelhi=stripslashes($res_eventDelhi[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventDelhi[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventDelhi[0][EndDt]));
  $timeDelhi=$StartDt;
  if($StartDt!=$EndDt){
  $timeDelhi.=" - ".$EndDt; 
  }  
			   
  $timeDelhi.=" Time: ".date('g:i a',strtotime($res_eventDelhi[0][StartDt]))."-".date('g:i a',strtotime($res_eventDelhi[0][EndDt])); 
}									
									
$data.='<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$DelhiurlDelhi.'" target="_blank"><img src="'.$DelhibannerDelhi.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$DelhiurlDelhi.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$DelhititleDelhi.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timeDelhi.'</strong></div></td>
                                    </tr>';

									
 
if($Delhiurl1!=""){									
  $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Delhiurl1.'" target="_blank"><img src="'.$Delhilogo1.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Delhiurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Delhititle1.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Delhitime1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Delhiurl2.'" target="_blank"><img src="'.$Delhilogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Delhiurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Delhititle2.' </a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Delhitime2.'</strong><br />
                                        </div></td>
                                    </tr>';
} if($Delhiurl3!="") {
   $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Delhiurl3.'" target="_blank"><img src="'.$Delhilogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Delhiurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Delhititle3.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Delhitime3.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Delhiurl4.'" target="_blank"><img src="'.$Delhilogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Delhiurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Delhititle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong> '.$Delhitime4.'</strong><br />
                                        </div></td>
                                    </tr>';
}if($Delhiurl5!="") {
   $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Delhiurl5.'" target="_blank"><img src="'.$Delhilogo5.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Delhiurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Delhititle5.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Delhitime5.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Delhiurl6.'"><img src="'.$Delhilogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Delhiurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Delhititle6.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Delhitime6.'</strong><br />
                                        </div></td>
                                    </tr>';
}									
                                    
                                    
$data.='<tr>
                                      <td colspan="4" align="right" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="http://www.meraevents.com/Delhi-Events" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A88D9">More Events &gt;&gt;</a></td>
                                    </tr>
                                  </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td valign="top" bgcolor="#CECECE" style="padding:1px;"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-bottom: 2px solid rgb(0, 73, 129); font-family: arial;">
                                  <tbody>
                                    <tr>
                                      <td colspan="4" valign="middle" bgcolor="#007797" style="border-bottom: 1px solid rgb(238, 238, 238);"><span style="font-size: 15px; color: #FFFFFF"><b>Mumbai / Pune<a name="Mumbai" id="Mumbai"></a></b></span></td>
                                    </tr>';
                                  
									
									
	$sql_eventMumbai="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['Mumbaibanner']."'";
$res_eventMumbai=$Global->SelectQuery($sql_eventMumbai);
if(count($res_eventMumbai)>0){
$MumbaiurlMumbai="http://www.meraevents.com/event/".$res_eventMumbai[0][URL];
$MumbaibannerMumbai="http://content.meraevents.com/".$res_eventMumbai[0][Banner];
$MumbaititleMumbai=stripslashes($res_eventMumbai[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventMumbai[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventMumbai[0][EndDt]));
  $timeMumbai=$StartDt;
  if($StartDt!=$EndDt){
  $timeMumbai.=" - ".$EndDt; 
  }  
			   
  $timeMumbai.=" Time: ".date('g:i a',strtotime($res_eventMumbai[0][StartDt]))."-".date('g:i a',strtotime($res_eventMumbai[0][EndDt])); 
}									
									
$data.='<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$MumbaiurlMumbai.'" target="_blank"><img src="'.$MumbaibannerMumbai.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$MumbaiurlMumbai.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$MumbaititleMumbai.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timeMumbai.'</strong></div></td>
                                    </tr>';
								
                                    
$sql_Mumbaievent1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Mumbaievent1']."'";
$res_Mumbaievent1=$Global->SelectQuery($sql_Mumbaievent1);
if(count($res_Mumbaievent1)>0){
$Mumbaiurl1="http://www.meraevents.com/event/".$res_Mumbaievent1[0][URL];
$Mumbailogo1="http://content.meraevents.com/".$res_Mumbaievent1[0][Logo];
$Mumbaititle1=stripslashes($res_Mumbaievent1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_Mumbaievent1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Mumbaievent1[0][EndDt]));
  $Mumbaitime1=$StartDt;
  if($StartDt!=$EndDt){
  $Mumbaitime1.=" - ".$EndDt; 
  }  
			   
  $Mumbaitime1.=" Time: ".date('g:i a',strtotime($res_Mumbaievent1[0][StartDt]))."-".date('g:i a',strtotime($res_Mumbaievent1[0][EndDt])); 
}
$sql_Mumbaievent2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Mumbaievent2']."'";
$res_Mumbaievent2=$Global->SelectQuery($sql_Mumbaievent2);
if(count($res_Mumbaievent2)>0){
$Mumbaiurl2="http://www.meraevents.com/event/".$res_Mumbaievent2[0][URL];
$Mumbailogo2="http://content.meraevents.com/".$res_Mumbaievent2[0][Logo];
$Mumbaititle2=stripslashes($res_Mumbaievent2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Mumbaievent2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Mumbaievent2[0][EndDt]));
  $Mumbaitime2=$StartDt;
  if($StartDt!=$EndDt){
  $Mumbaitime2.=" - ".$EndDt; 
  } 		   
  $Mumbaitime2.=" Time: ".date('g:i a',strtotime($res_Mumbaievent2[0][StartDt]))."-".date('g:i a',strtotime($res_Mumbaievent2[0][EndDt])); 
}
$sql_Mumbaievent3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Mumbaievent3']."'";
$res_Mumbaievent3=$Global->SelectQuery($sql_Mumbaievent3);
if(count($res_Mumbaievent3)>0){
$Mumbaiurl3="http://www.meraevents.com/event/".$res_Mumbaievent3[0][URL];
$Mumbailogo3="http://content.meraevents.com/".$res_Mumbaievent3[0][Logo];
$Mumbaititle3=stripslashes($res_Mumbaievent3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Mumbaievent3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Mumbaievent3[0][EndDt]));
  $Mumbaitime3=$StartDt;
  if($StartDt!=$EndDt){
  $Mumbaitime3.=" - ".$EndDt; 
  } 		   
  $Mumbaitime3.=" Time: ".date('g:i a',strtotime($res_Mumbaievent3[0][StartDt]))."-".date('g:i a',strtotime($res_Mumbaievent3[0][EndDt])); 
}
$sql_Mumbaievent4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Mumbaievent4']."'";
$res_Mumbaievent4=$Global->SelectQuery($sql_Mumbaievent4);
if(count($res_Mumbaievent4)>0){
$Mumbaiurl4="http://www.meraevents.com/event/".$res_Mumbaievent4[0][URL];
$Mumbailogo4="http://content.meraevents.com/".$res_Mumbaievent4[0][Logo];
$Mumbaititle4=stripslashes($res_Mumbaievent4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Mumbaievent4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Mumbaievent4[0][EndDt]));
  $Mumbaitime4=$StartDt;
  if($StartDt!=$EndDt){
  $Mumbaitime4.=" - ".$EndDt; 
  } 		   
  $Mumbaitime4.=" Time: ".date('g:i a',strtotime($res_Mumbaievent4[0][StartDt]))."-".date('g:i a',strtotime($res_Mumbaievent4[0][EndDt])); 
}

$sql_Mumbaievent5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Mumbaievent5']."'";
$res_Mumbaievent5=$Global->SelectQuery($sql_Mumbaievent5);
if(count($res_Mumbaievent5)>0){
$Mumbaiurl5="http://www.meraevents.com/event/".$res_Mumbaievent5[0][URL];
$Mumbailogo5="http://content.meraevents.com/".$res_Mumbaievent5[0][Logo];
$Mumbaititle5=stripslashes($res_Mumbaievent5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Mumbaievent5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Mumbaievent5[0][EndDt]));
  $Mumbaitime5=$StartDt;
  if($StartDt!=$EndDt){
  $Mumbaitime5.=" - ".$EndDt; 
  } 		   
  $Mumbaitime5.=" Time: ".date('g:i a',strtotime($res_Mumbaievent5[0][StartDt]))."-".date('g:i a',strtotime($res_Mumbaievent5[0][EndDt])); 
}
$sql_Mumbaievent6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Mumbaievent6']."'";
$res_Mumbaievent6=$Global->SelectQuery($sql_Mumbaievent6);
if(count($res_Mumbaievent6)>0){
$Mumbaiurl6="http://www.meraevents.com/event/".$res_Mumbaievent6[0][URL];
$Mumbailogo6="http://content.meraevents.com/".$res_Mumbaievent6[0][Logo];
$Mumbaititle6=stripslashes($res_Mumbaievent6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Mumbaievent6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Mumbaievent6[0][EndDt]));
  $Mumbaitime6=$StartDt;
  if($StartDt!=$EndDt){
  $Mumbaitime6.=" - ".$EndDt; 
  } 		   
  $Mumbaitime6.=" Time: ".date('g:i a',strtotime($res_Mumbaievent6[0][StartDt]))."-".date('g:i a',strtotime($res_Mumbaievent6[0][EndDt])); 
}
									
									
	if($Mumbaiurl1!=""){								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Mumbaiurl1.'" target="_blank"><img src="'.$Mumbailogo1.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Mumbaiurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Mumbaititle1.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Mumbaitime1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Mumbaiurl2.'" target="_blank"><img src="'.$Mumbailogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Mumbaiurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Mumbaititle2.' </a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Mumbaitime2.'</strong><br />
                                        </div></td>
                                    </tr>';
} if($Mumbaiurl3!=""){
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Mumbaiurl3.'" target="_blank"><img src="'.$Mumbailogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Mumbaiurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Mumbaititle3.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Mumbaitime3.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Mumbaiurl4.'" target="_blank"><img src="'.$Mumbailogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Mumbaiurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Mumbaititle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong> '.$Mumbaitime4.'</strong><br />
                                        </div></td>
                                    </tr>';
} if($Mumbaiurl5!="") {                                    
 $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Mumbaiurl5.'" target="_blank"><img src="'.$Mumbailogo5.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Mumbaiurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Mumbaititle5.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Mumbaitime5.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Mumbaiurl6.'"><img src="'.$Mumbailogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Mumbaiurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Mumbaititle6.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Mumbaitime6.'</strong><br />
                                        </div></td>
                                    </tr>';
}



 $data.='<tr>
                                      <td colspan="4" align="right" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="http://www.meraevents.com/Mumbai-Events" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A88D9">More Events &gt;&gt;</a></td>
                                    </tr>
                                  </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td valign="top" bgcolor="#CECECE" style="padding:1px;"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-bottom: 2px solid rgb(0, 73, 129); font-family: arial;">
                                  <tbody>
                                    <tr>
                                      <td colspan="4" valign="middle" bordercolor="#007797" bgcolor="#007797" style="border-bottom: 1px solid rgb(238, 238, 238);"><span style="font-size: 15px; color: #FFFFFF"><b>Bangalore <a name="Bangalore" id="Bangalore"></a></b></span></td>
                                    </tr>
                                    
                                   <tr>';
								   
	$sql_eventBangalore="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['Bangalorebanner']."'";
$res_eventBangalore=$Global->SelectQuery($sql_eventBangalore);
if(count($res_eventBangalore)>0){
$BangaloreurlBangalore="http://www.meraevents.com/event/".$res_eventBangalore[0][URL];
$BangalorebannerBangalore="http://content.meraevents.com/".$res_eventBangalore[0][Banner];
$BangaloretitleBangalore=stripslashes($res_eventBangalore[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventBangalore[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventBangalore[0][EndDt]));
  $timeBangalore=$StartDt;
  if($StartDt!=$EndDt){
  $timeBangalore.=" - ".$EndDt; 
  }  
			   
  $timeBangalore.=" Time: ".date('g:i a',strtotime($res_eventBangalore[0][StartDt]))."-".date('g:i a',strtotime($res_eventBangalore[0][EndDt])); 
}									
									
$data.='<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$BangaloreurlBangalore.'" target="_blank"><img src="'.$BangalorebannerBangalore.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$BangaloreurlBangalore.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$BangaloretitleBangalore.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timeBangalore.'</strong></div></td>
                                    </tr>';
                                    
$sql_Bangaloreevent1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Bangaloreevent1']."'";
$res_Bangaloreevent1=$Global->SelectQuery($sql_Bangaloreevent1);
if(count($res_Bangaloreevent1)>0){
$Bangaloreurl1="http://www.meraevents.com/event/".$res_Bangaloreevent1[0][URL];
$Bangalorelogo1="http://content.meraevents.com/".$res_Bangaloreevent1[0][Logo];
$Bangaloretitle1=stripslashes($res_Bangaloreevent1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_Bangaloreevent1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Bangaloreevent1[0][EndDt]));
  $Bangaloretime1=$StartDt;
  if($StartDt!=$EndDt){
  $Bangaloretime1.=" - ".$EndDt; 
  }  
			   
  $Bangaloretime1.=" Time: ".date('g:i a',strtotime($res_Bangaloreevent1[0][StartDt]))."-".date('g:i a',strtotime($res_Bangaloreevent1[0][EndDt])); 
}
$sql_Bangaloreevent2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Bangaloreevent2']."'";
$res_Bangaloreevent2=$Global->SelectQuery($sql_Bangaloreevent2);
if(count($res_Bangaloreevent2)>0){
$Bangaloreurl2="http://www.meraevents.com/event/".$res_Bangaloreevent2[0][URL];
$Bangalorelogo2="http://content.meraevents.com/".$res_Bangaloreevent2[0][Logo];
$Bangaloretitle2=stripslashes($res_Bangaloreevent2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Bangaloreevent2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Bangaloreevent2[0][EndDt]));
  $Bangaloretime2=$StartDt;
  if($StartDt!=$EndDt){
  $Bangaloretime2.=" - ".$EndDt; 
  } 		   
  $Bangaloretime2.=" Time: ".date('g:i a',strtotime($res_Bangaloreevent2[0][StartDt]))."-".date('g:i a',strtotime($res_Bangaloreevent2[0][EndDt])); 
}
$sql_Bangaloreevent3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Bangaloreevent3']."'";
$res_Bangaloreevent3=$Global->SelectQuery($sql_Bangaloreevent3);
if(count($res_Bangaloreevent3)>0){
$Bangaloreurl3="http://www.meraevents.com/event/".$res_Bangaloreevent3[0][URL];
$Bangalorelogo3="http://content.meraevents.com/".$res_Bangaloreevent3[0][Logo];
$Bangaloretitle3=stripslashes($res_Bangaloreevent3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Bangaloreevent3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Bangaloreevent3[0][EndDt]));
  $Bangaloretime3=$StartDt;
  if($StartDt!=$EndDt){
  $Bangaloretime3.=" - ".$EndDt; 
  } 		   
  $Bangaloretime3.=" Time: ".date('g:i a',strtotime($res_Bangaloreevent3[0][StartDt]))."-".date('g:i a',strtotime($res_Bangaloreevent3[0][EndDt])); 
}
$sql_Bangaloreevent4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Bangaloreevent4']."'";
$res_Bangaloreevent4=$Global->SelectQuery($sql_Bangaloreevent4);
if(count($res_Bangaloreevent4)>0){
$Bangaloreurl4="http://www.meraevents.com/event/".$res_Bangaloreevent4[0][URL];
$Bangalorelogo4="http://content.meraevents.com/".$res_Bangaloreevent4[0][Logo];
$Bangaloretitle4=stripslashes($res_Bangaloreevent4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Bangaloreevent4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Bangaloreevent4[0][EndDt]));
  $Bangaloretime4=$StartDt;
  if($StartDt!=$EndDt){
  $Bangaloretime4.=" - ".$EndDt; 
  } 		   
  $Bangaloretime4.=" Time: ".date('g:i a',strtotime($res_Bangaloreevent4[0][StartDt]))."-".date('g:i a',strtotime($res_Bangaloreevent4[0][EndDt])); 
}

$sql_Bangaloreevent5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Bangaloreevent5']."'";
$res_Bangaloreevent5=$Global->SelectQuery($sql_Bangaloreevent5);
if(count($res_Bangaloreevent5)>0){
$Bangaloreurl5="http://www.meraevents.com/event/".$res_Bangaloreevent5[0][URL];
$Bangalorelogo5="http://content.meraevents.com/".$res_Bangaloreevent5[0][Logo];
$Bangaloretitle5=stripslashes($res_Bangaloreevent5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Bangaloreevent5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Bangaloreevent5[0][EndDt]));
  $Bangaloretime5=$StartDt;
  if($StartDt!=$EndDt){
  $Bangaloretime5.=" - ".$EndDt; 
  } 		   
  $Bangaloretime5.=" Time: ".date('g:i a',strtotime($res_Bangaloreevent5[0][StartDt]))."-".date('g:i a',strtotime($res_Bangaloreevent5[0][EndDt])); 
}
$sql_Bangaloreevent6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Bangaloreevent6']."'";
$res_Bangaloreevent6=$Global->SelectQuery($sql_Bangaloreevent6);
if(count($res_Bangaloreevent6)>0){
$Bangaloreurl6="http://www.meraevents.com/event/".$res_Bangaloreevent6[0][URL];
$Bangalorelogo6="http://content.meraevents.com/".$res_Bangaloreevent6[0][Logo];
$Bangaloretitle6=stripslashes($res_Bangaloreevent6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Bangaloreevent6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Bangaloreevent6[0][EndDt]));
  $Bangaloretime6=$StartDt;
  if($StartDt!=$EndDt){
  $Bangaloretime6.=" - ".$EndDt; 
  } 		   
  $Bangaloretime6.=" Time: ".date('g:i a',strtotime($res_Bangaloreevent6[0][StartDt]))."-".date('g:i a',strtotime($res_Bangaloreevent6[0][EndDt])); 
}
									
									
	if(Bangaloreurl1!=""){								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Bangaloreurl1.'" target="_blank"><img src="'.$Bangalorelogo1.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Bangaloreurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Bangaloretitle1.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Bangaloretime1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Bangaloreurl2.'" target="_blank"><img src="'.$Bangalorelogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Bangaloreurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Bangaloretitle2.' </a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Bangaloretime2.'</strong><br />
                                        </div></td>
                                    </tr>';
} if($Bangaloreurl3!="") {
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Bangaloreurl3.'" target="_blank"><img src="'.$Bangalorelogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Bangaloreurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Bangaloretitle3.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Bangaloretime3.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Bangaloreurl4.'" target="_blank"><img src="'.$Bangalorelogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Bangaloreurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Bangaloretitle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong> '.$Bangaloretime4.'</strong><br />
                                        </div></td>
                                    </tr>';
}	if($Bangaloreurl5!="") {								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Bangaloreurl5.'" target="_blank"><img src="'.$Bangalorelogo5.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Bangaloreurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Bangaloretitle5.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Bangaloretime5.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Bangaloreurl6.'"><img src="'.$Bangalorelogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Bangaloreurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Bangaloretitle6.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Bangaloretime6.'</strong><br />
                                        </div></td>
                                    </tr>';
}									
                                    
 $data.=' <tr>
                                      <td colspan="4" align="right" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="http://www.meraevents.com/Bangalore-Events" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A88D9">More Events &gt;&gt;</a></td>
                                    </tr>
                                  </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td valign="top" bgcolor="#CECECE" style="padding:1px;"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-bottom: 2px solid rgb(0, 73, 129); font-family: arial;">
                                <tbody>
                                  <tr>
                                    <td colspan="4" valign="middle" bgcolor="#007797" style="border-bottom: 1px solid rgb(238, 238, 238);"><span style="font-size: 15px; color: #FFFFFF"><strong>Chennai</strong> <a name="Chennai" id="Chennai2"></a></span></td>
                                  </tr>';
								  
	
	$sql_eventChennai="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['Chennaibanner']."'";
$res_eventChennai=$Global->SelectQuery($sql_eventChennai);
if(count($res_eventChennai)>0){
$ChennaiurlChennai="http://www.meraevents.com/event/".$res_eventChennai[0][URL];
$ChennaibannerChennai="http://content.meraevents.com/".$res_eventChennai[0][Banner];
$ChennaititleChennai=stripslashes($res_eventChennai[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventChennai[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventChennai[0][EndDt]));
  $timeChennai=$StartDt;
  if($StartDt!=$EndDt){
  $timeChennai.=" - ".$EndDt; 
  }  
			   
  $timeChennai.=" Time: ".date('g:i a',strtotime($res_eventChennai[0][StartDt]))."-".date('g:i a',strtotime($res_eventChennai[0][EndDt])); 
}									
									
$data.='<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$ChennaiurlChennai.'" target="_blank"><img src="'.$ChennaibannerChennai.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$ChennaiurlChennai.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$ChennaititleChennai.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timeChennai.'</strong></div></td>
                                    </tr>';							  
								  
								  
                              
$sql_Chennaievent1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Chennaievent1']."'";
$res_Chennaievent1=$Global->SelectQuery($sql_Chennaievent1);
if(count($res_Chennaievent1)>0){
$Chennaiurl1="http://www.meraevents.com/event/".$res_Chennaievent1[0][URL];
$Chennailogo1="http://content.meraevents.com/".$res_Chennaievent1[0][Logo];
$Chennaititle1=stripslashes($res_Chennaievent1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_Chennaievent1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Chennaievent1[0][EndDt]));
  $Chennaitime1=$StartDt;
  if($StartDt!=$EndDt){
  $Chennaitime1.=" - ".$EndDt; 
  }  
			   
  $Chennaitime1.=" Time: ".date('g:i a',strtotime($res_Chennaievent1[0][StartDt]))."-".date('g:i a',strtotime($res_Chennaievent1[0][EndDt])); 
}
$sql_Chennaievent2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Chennaievent2']."'";
$res_Chennaievent2=$Global->SelectQuery($sql_Chennaievent2);
if(count($res_Chennaievent2)>0){
$Chennaiurl2="http://www.meraevents.com/event/".$res_Chennaievent2[0][URL];
$Chennailogo2="http://content.meraevents.com/".$res_Chennaievent2[0][Logo];
$Chennaititle2=stripslashes($res_Chennaievent2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Chennaievent2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Chennaievent2[0][EndDt]));
  $Chennaitime2=$StartDt;
  if($StartDt!=$EndDt){
  $Chennaitime2.=" - ".$EndDt; 
  } 		   
  $Chennaitime2.=" Time: ".date('g:i a',strtotime($res_Chennaievent2[0][StartDt]))."-".date('g:i a',strtotime($res_Chennaievent2[0][EndDt])); 
}
$sql_Chennaievent3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Chennaievent3']."'";
$res_Chennaievent3=$Global->SelectQuery($sql_Chennaievent3);
if(count($res_Chennaievent3)>0){
$Chennaiurl3="http://www.meraevents.com/event/".$res_Chennaievent3[0][URL];
$Chennailogo3="http://content.meraevents.com/".$res_Chennaievent3[0][Logo];
$Chennaititle3=stripslashes($res_Chennaievent3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Chennaievent3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Chennaievent3[0][EndDt]));
  $Chennaitime3=$StartDt;
  if($StartDt!=$EndDt){
  $Chennaitime3.=" - ".$EndDt; 
  } 		   
  $Chennaitime3.=" Time: ".date('g:i a',strtotime($res_Chennaievent3[0][StartDt]))."-".date('g:i a',strtotime($res_Chennaievent3[0][EndDt])); 
}
$sql_Chennaievent4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Chennaievent4']."'";
$res_Chennaievent4=$Global->SelectQuery($sql_Chennaievent4);
if(count($res_Chennaievent4)>0){
$Chennaiurl4="http://www.meraevents.com/event/".$res_Chennaievent4[0][URL];
$Chennailogo4="http://content.meraevents.com/".$res_Chennaievent4[0][Logo];
$Chennaititle4=stripslashes($res_Chennaievent4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Chennaievent4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Chennaievent4[0][EndDt]));
  $Chennaitime4=$StartDt;
  if($StartDt!=$EndDt){
  $Chennaitime4.=" - ".$EndDt; 
  } 		   
  $Chennaitime4.=" Time: ".date('g:i a',strtotime($res_Chennaievent4[0][StartDt]))."-".date('g:i a',strtotime($res_Chennaievent4[0][EndDt])); 
}

$sql_Chennaievent5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Chennaievent5']."'";
$res_Chennaievent5=$Global->SelectQuery($sql_Chennaievent5);
if(count($res_Chennaievent5)>0){
$Chennaiurl5="http://www.meraevents.com/event/".$res_Chennaievent5[0][URL];
$Chennailogo5="http://content.meraevents.com/".$res_Chennaievent5[0][Logo];
$Chennaititle5=stripslashes($res_Chennaievent5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Chennaievent5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Chennaievent5[0][EndDt]));
  $Chennaitime5=$StartDt;
  if($StartDt!=$EndDt){
  $Chennaitime5.=" - ".$EndDt; 
  } 		   
  $Chennaitime5.=" Time: ".date('g:i a',strtotime($res_Chennaievent5[0][StartDt]))."-".date('g:i a',strtotime($res_Chennaievent5[0][EndDt])); 
}
$sql_Chennaievent6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Chennaievent6']."'";
$res_Chennaievent6=$Global->SelectQuery($sql_Chennaievent6);
if(count($res_Chennaievent6)>0){
$Chennaiurl6="http://www.meraevents.com/event/".$res_Chennaievent6[0][URL];
$Chennailogo6="http://content.meraevents.com/".$res_Chennaievent6[0][Logo];
$Chennaititle6=stripslashes($res_Chennaievent6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Chennaievent6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Chennaievent6[0][EndDt]));
  $Chennaitime6=$StartDt;
  if($StartDt!=$EndDt){
  $Chennaitime6.=" - ".$EndDt; 
  } 		   
  $Chennaitime6.=" Time: ".date('g:i a',strtotime($res_Chennaievent6[0][StartDt]))."-".date('g:i a',strtotime($res_Chennaievent6[0][EndDt])); 
}
									
									
	if($Chennaiurl1!="") {								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Chennaiurl1.'" target="_blank"><img src="'.$Chennailogo1.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Chennaiurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Chennaititle1.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Chennaitime1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Chennaiurl2.'" target="_blank"><img src="'.$Chennailogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Chennaiurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Chennaititle2.' </a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Chennaitime2.'</strong><br />
                                        </div></td>
                                    </tr>';
}if($Chennaiurl3!="") {								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Chennaiurl3.'" target="_blank"><img src="'.$Chennailogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Chennaiurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Chennaititle3.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Chennaitime3.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Chennaiurl4.'" target="_blank"><img src="'.$Chennailogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Chennaiurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Chennaititle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong> '.$Chennaitime4.'</strong><br />
                                        </div></td>
                                    </tr>';
}if($Chennaiurl5!="") {								
 $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Chennaiurl5.'" target="_blank"><img src="'.$Chennailogo5.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Chennaiurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Chennaititle5.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Chennaitime5.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Chennaiurl6.'"><img src="'.$Chennailogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Chennaiurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Chennaititle6.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Chennaitime6.'</strong><br />
                                        </div></td>
                                    </tr>';
}							
 $data.=' <tr>
                                    <td colspan="4" align="right" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="http://www.meraevents.com/Chennai-Events" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A88D9">More Events &gt;&gt;</a></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td valign="top" bgcolor="#CECECE" style="padding:1px;"><table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" style="border-bottom: 2px solid rgb(0, 73, 129); font-family: arial;">
                                <tbody>';
								  
		if($_REQUEST['Otherbanner']!=""){						  
		$sql_eventOther="select URL,Title,StartDt,EndDt,Banner from events where Id='".$_REQUEST['Otherbanner']."'";
$res_eventOther=$Global->SelectQuery($sql_eventOther);
if(count($res_eventOther)>0){
$OtherurlOther="http://www.meraevents.com/event/".$res_eventOther[0][URL];
$OtherbannerOther="http://content.meraevents.com/".$res_eventOther[0][Banner];
$OthertitleOther=stripslashes($res_eventOther[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_eventOther[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_eventOther[0][EndDt]));
  $timeOther=$StartDt;
  if($StartDt!=$EndDt){
  $timeOther.=" - ".$EndDt; 
  }  
			   
  $timeOther.=" Time: ".date('g:i a',strtotime($res_eventOther[0][StartDt]))."-".date('g:i a',strtotime($res_eventOther[0][EndDt])); 
}									
									
$data.='
 <tr>
                                    <td colspan="4" valign="middle" bgcolor="#007797" style="border-bottom: 1px solid rgb(238, 238, 238);"><span style="font-size: 15px; color: #FFFFFF"><strong>Other Cities</strong> <a name="Chennai" id="Chennai"></a></span></td>
                                  </tr>
<tr>
                                      <td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$OtherurlOther.'" target="_blank"><img src="'.$OtherbannerOther.'" width="725" height="180" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td></tr>
<tr><td colspan="4" align="center" valign="middle" bgcolor="#FFFFFF"><a href="'.$OtherurlOther.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;"><strong>'.$OthertitleOther.'</strong> </a>  <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$timeOther.'</strong></div></td>
                                    </tr>';							  							  
                               
$sql_Otherevent1="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Otherevent1']."'";
$res_Otherevent1=$Global->SelectQuery($sql_Otherevent1);
if(count($res_Otherevent1)>0){
$Otherurl1="http://www.meraevents.com/event/".$res_Otherevent1[0][URL];
$Otherlogo1="http://content.meraevents.com/".$res_Otherevent1[0][Logo];
$Othertitle1=stripslashes($res_Otherevent1[0][Title]);
 $StartDt = date('F j, Y',strtotime($res_Otherevent1[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Otherevent1[0][EndDt]));
  $Othertime1=$StartDt;
  if($StartDt!=$EndDt){
  $Othertime1.=" - ".$EndDt; 
  }  
			   
  $Othertime1.=" Time: ".date('g:i a',strtotime($res_Otherevent1[0][StartDt]))."-".date('g:i a',strtotime($res_Otherevent1[0][EndDt])); 
}
$sql_Otherevent2="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Otherevent2']."'";
$res_Otherevent2=$Global->SelectQuery($sql_Otherevent2);
if(count($res_Otherevent2)>0){
$Otherurl2="http://www.meraevents.com/event/".$res_Otherevent2[0][URL];
$Otherlogo2="http://content.meraevents.com/".$res_Otherevent2[0][Logo];
$Othertitle2=stripslashes($res_Otherevent2[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Otherevent2[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Otherevent2[0][EndDt]));
  $Othertime2=$StartDt;
  if($StartDt!=$EndDt){
  $Othertime2.=" - ".$EndDt; 
  } 		   
  $Othertime2.=" Time: ".date('g:i a',strtotime($res_Otherevent2[0][StartDt]))."-".date('g:i a',strtotime($res_Otherevent2[0][EndDt])); 
}
$sql_Otherevent3="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Otherevent3']."'";
$res_Otherevent3=$Global->SelectQuery($sql_Otherevent3);
if(count($res_Otherevent3)>0){
$Otherurl3="http://www.meraevents.com/event/".$res_Otherevent3[0][URL];
$Otherlogo3="http://content.meraevents.com/".$res_Otherevent3[0][Logo];
$Othertitle3=stripslashes($res_Otherevent3[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Otherevent3[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Otherevent3[0][EndDt]));
  $Othertime3=$StartDt;
  if($StartDt!=$EndDt){
  $Othertime3.=" - ".$EndDt; 
  } 		   
  $Othertime3.=" Time: ".date('g:i a',strtotime($res_Otherevent3[0][StartDt]))."-".date('g:i a',strtotime($res_Otherevent3[0][EndDt])); 
}
$sql_Otherevent4="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Otherevent4']."'";
$res_Otherevent4=$Global->SelectQuery($sql_Otherevent4);
if(count($res_Otherevent4)>0){
$Otherurl4="http://www.meraevents.com/event/".$res_Otherevent4[0][URL];
$Otherlogo4="http://content.meraevents.com/".$res_Otherevent4[0][Logo];
$Othertitle4=stripslashes($res_Otherevent4[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Otherevent4[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Otherevent4[0][EndDt]));
  $Othertime4=$StartDt;
  if($StartDt!=$EndDt){
  $Othertime4.=" - ".$EndDt; 
  } 		   
  $Othertime4.=" Time: ".date('g:i a',strtotime($res_Otherevent4[0][StartDt]))."-".date('g:i a',strtotime($res_Otherevent4[0][EndDt])); 
}

$sql_Otherevent5="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Otherevent5']."'";
$res_Otherevent5=$Global->SelectQuery($sql_Otherevent5);
if(count($res_Otherevent5)>0){
$Otherurl5="http://www.meraevents.com/event/".$res_Otherevent5[0][URL];
$Otherlogo5="http://content.meraevents.com/".$res_Otherevent5[0][Logo];
$Othertitle5=stripslashes($res_Otherevent5[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Otherevent5[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Otherevent5[0][EndDt]));
  $Othertime5=$StartDt;
  if($StartDt!=$EndDt){
  $Othertime5.=" - ".$EndDt; 
  } 		   
  $Othertime5.=" Time: ".date('g:i a',strtotime($res_Otherevent5[0][StartDt]))."-".date('g:i a',strtotime($res_Otherevent5[0][EndDt])); 
}
$sql_Otherevent6="select URL,Logo,Title,StartDt,EndDt from events where Id='".$_REQUEST['Otherevent6']."'";
$res_Otherevent6=$Global->SelectQuery($sql_Otherevent6);
if(count($res_Otherevent6)>0){
$Otherurl6="http://www.meraevents.com/event/".$res_Otherevent6[0][URL];
$Otherlogo6="http://content.meraevents.com/".$res_Otherevent6[0][Logo];
$Othertitle6=stripslashes($res_Otherevent6[0][Title]);
$StartDt = date('F j, Y',strtotime($res_Otherevent6[0][StartDt]));
 $EndDt = date('F j, Y',strtotime($res_Otherevent6[0][EndDt]));
  $Othertime6=$StartDt;
  if($StartDt!=$EndDt){
  $Othertime6.=" - ".$EndDt; 
  } 		   
  $Othertime6.=" Time: ".date('g:i a',strtotime($res_Otherevent6[0][StartDt]))."-".date('g:i a',strtotime($res_Otherevent6[0][EndDt])); 
}
									
									
if($Otherurl1!="") {								
 $data.=' <tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Otherurl1.'" target="_blank"><img src="'.$Otherlogo1.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Otherurl1.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Othertitle1.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Othertime1.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Otherurl2.'" target="_blank"><img src="'.$Otherlogo2.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Otherurl2.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Othertitle2.' </a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Othertime2.'</strong><br />
                                        </div></td>
                                    </tr>';
} if($Otherurl3!="") {								
 $data.='<tr><td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Otherurl3.'" target="_blank"><img src="'.$Otherlogo3.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Otherurl3.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Othertitle3.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Othertime3.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Otherurl4.'" target="_blank"><img src="'.$Otherlogo4.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Otherurl4.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Othertitle4.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong> '.$Othertime4.'</strong><br />
                                        </div></td>
                                    </tr>';
}if($Otherurl5!="") {								
 $data.='<tr>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Otherurl5.'" target="_blank"><img src="'.$Otherlogo5.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Otherurl5.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Othertitle5.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px; line-height:10px; margin-bottom:10px;" > <strong>'.$Othertime5.'</strong></div></td>
                                      <td valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="'.$Otherurl6.'"><img src="'.$Otherlogo6.'" alt="" width="75" height="75" border="0" style="border: 1px solid rgb(238, 238, 238);" /></a></td>
                                      <td width="280" align="left" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><b><a href="'.$Otherurl6.'" target="_blank" style="color: #3366CC; font-size:13px; text-decoration: none;">'.$Othertitle6.'</a></b>
                                          <div style="font-size: 12px; color:#000000; font-family:Calibri,Trebuchet MS, sans-serif, Arial; margin-top:5px;" ><strong>'.$Othertime6.'</strong><br />
                                        </div></td>
                                    </tr>';
}
$data.='<tr>
                                    <td colspan="4" align="right" valign="middle" bgcolor="#FFFFFF" style="border-bottom: 1px solid rgb(238, 238, 238);"><a href="http://www.meraevents.com/" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#1A88D9">More Events &gt;&gt;</a></td>
                                  </tr>';
}


$sidebanner='<table width="95%" border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td>&nbsp;</td>
      </tr>';
	  if($_REQUEST[Banner1]!="") {
      $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link1].'" target="_blank"><img src="'.$_REQUEST[Banner1].'" width="200" border="0" /></a></td>
      </tr>';
	  }if($_REQUEST[Banner2]!="") {
      $sidebanner.=' <tr>
        <td align="center"><a href="'.$_REQUEST[link2].'" target="_blank"><img src="'.$_REQUEST[Banner2].'" width="200" border="0" /></a></td>
      </tr>';
	  } if($_REQUEST[Banner3]!="") {
       $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link3].'" target="_blank"><img src="'.$_REQUEST[Banner3].'" width="200" border="0" /></a></td>
      </tr>';
	  } if($_REQUEST[Banner4]!="") {
       $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link4].'" target="_blank"><img src="'.$_REQUEST[Banner4].'" width="200" border="0" /></a></td>
      </tr>';
	  } if($_REQUEST[Banner5]!="") {
       $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link5].'" target="_blank"><img src="'.$_REQUEST[Banner5].'" width="200" border="0" /></a></td>
      </tr>';
	  } if($_REQUEST[Banner6]!="") {
      $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link6].'" target="_blank"><img src="'.$_REQUEST[Banner6].'" width="200" border="0" /></a></td>
      </tr>';
	   }
	    if($_REQUEST[Banner7]!="") {
      $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link7].'" target="_blank"><img src="'.$_REQUEST[Banner7].'" width="200" border="0" /></a></td>
      </tr>';
	   }
	    if($_REQUEST[Banner8]!="") {
      $sidebanner.='<tr>
        <td align="center"><a href="'.$_REQUEST[link8].'" target="_blank"><img src="'.$_REQUEST[Banner8].'" width="200" border="0" /></a></td>
      </tr>';
	   }


       
     $sidebanner.='</table>';



                                $data.='</tbody>
                              </table></td>
                            </tr>
                            <tr></tr>
                            <tr></tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td align="center" valign="top" bgcolor="#EDEDED"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                          <tbody>
                            <tr>
                              <td valign="top" bgcolor="#ededed" align="left"><table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr></tr>
                                    <tr>
                                      <td width="62" valign="top" height="54" bgcolor="#ededed" align="left"><a href="http://www.meraevents.com/register_events_online" target="_blank" rel="nofollow"><font face="Verdana, Times New Roman, serif" color="#6e6e6e" size="1" style="font-size: 9px; line-height: 9px; text-decoration: none;"><img width="76" height="54" border="0" alt="Register Now" title="Register Now" src="http://versanttechnologies.com/images/Meraeventssmall.jpg" /></font></a></td>
                                      <td width="80" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="http://www.meraevents.com/register_events_online" target="_blank" rel="nofollow" style="color:#333333; text-decoration:none; font-size:12px;">Register To <br />
                                        Meraevents.com</a></td>
                                      <td width="5" valign="middle" height="54" align="left">&nbsp;</td>
                                      <td width="76" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="http://www.facebook.com/meraevents" target="_blank" rel="nofollow"><font face="Verdana, Times New Roman, serif" color="#6e6e6e" size="1" style="font-size: 9px; line-height: 9px; text-decoration: none;"><img width="76" height="54" border="0" alt="Become a fan on Facebook" title="Become a fan on Facebook" src="http://versanttechnologies.com/images/facebook_icn.jpg" /></font></a></td>
                                      <td width="80" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="http://www.facebook.com/meraevents" target="_blank" rel="nofollow" style="color:#333333; text-decoration:none; font-size:12px;">Become a fan on Facebook</a></td>
                                      <td width="63" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="http://twitter.com/meraevents_com" target="_blank" rel="nofollow"><font face="Verdana, Times New Roman, serif" color="#6e6e6e" size="1" style="font-size: 9px; line-height: 9px; text-decoration: none;"><img width="63" height="54" border="0" alt="FOLLOW US ON TWITTER" title="FOLLOW US ON TWITTER" src="http://versanttechnologies.com/images/twitter_icn.jpg" /></font></a></td>
                                      <td width="85" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="http://twitter.com/meraevents_com" target="_blank" rel="nofollow" style="color:#333333; text-decoration:none; font-size:12px;">Follow on <br />
                                        Twitter</a></td>
                                      <td width="5" valign="middle" height="54" align="left">&nbsp;</td>
                                      <td width="74" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="*|FORWARD|*"><img width="74" height="54" border="0" alt="TELL A FRIEND" title="TELL A FRIEND" src="http://versanttechnologies.com/images/mail.jpg" /></a></td>
                                      <td width="70" valign="middle" height="54" bgcolor="#ededed" align="left"><a href="*|FORWARD|*" target="_blank" rel="nofollow" style="color:#333333; text-decoration:none; font-size:12px;">Tell A<br />
                                        Friend</a></td>
                                    </tr>
                                </table></td>
                            </tr>
                            </tbody>
                        </table></td>
                      </tr>
                    </tbody>
                </table></td>
              </tr>
              <tr>
                <td height="45" valign="middle" bgcolor="#007797" style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51);"><div align="center" style="color:#FFFFFF; font-size:12px;">Get in touch with us for any queries at 040-40404160 or <a href="mailto:%20sales@meraevents.com" style="color:#FFFFFF;">sales@meraevents.com<br />
                </a>Visit us at <a href="http://www.meraevents.com" target="_blank" style="color:#FFFFFF;">www.meraevents.com</a></div></td>
              </tr>
            </tbody>
        </table>
            <map name="Map" id="Map">
              <area shape="rect" coords="7,9,183,46" href="http://www.meraevents.com/registration_new.php" alt="Register Now" title="Register Now" />
              <area shape="rect" coords="7,58,174,96" href="http://www.meraevents.com/recommend.php" alt="Recommend Event" title="Recommend Event" />
              <area shape="rect" coords="6,101,182,147" href="mailto:naidu@meraevents.com" alt="Promote Your Event" title="Promote Your Event" />
              <area shape="rect" coords="5,159,179,194" href="http://www.meraevents.com/video.php" alt="Watch Video" title="Watch Video" />
          </map></td>
      </tr>
    </table></td>
    <td width="300" valign="top">'.$sidebanner.'</td>
  </tr>
</table>
</body>
</html>



';
if($_POST['submit'] == "Upload"){

   $fp2 = @fopen($_REQUEST[clickhere],"w"); 
   fwrite($fp2,$data); 
   fclose($fp2); 
   $file=$_REQUEST[clickhere];
  $path=$_SERVER['DOCUMENT_ROOT']."/ctrl/";
  $complete=$_SERVER['DOCUMENT_ROOT']."/emailer/";
  rename("$path/$file", "$complete/$file"); 

//wwwcopy("http://www.meraevents.com/ctrl/auto_newsletter.php",$_REQUEST[clickhere]);

 }
//echo $data; 
//exit;
		
	}// END IF delete
		
	

	include 'templates/auto_newsletter.tpl.php';
?>
