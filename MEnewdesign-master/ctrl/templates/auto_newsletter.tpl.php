<? echo $data; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MeraEvents -Master Management - City Management</title>
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/menus.css" rel="stylesheet" type="text/css">
	<link href="<?=_HTTP_CF_ROOT;?>/ctrl/css/style.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortable.min.js.gz"></script>	
	<script language="javascript" src="<?=_HTTP_CF_ROOT;?>/ctrl/css/sortpagi.min.js.gz"></script>
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
				<div id="divMainPage" style="margin-left: 10px; margin-right:5px">
<!-------------------------------CITY LIST PAGE STARTS HERE--------------------------------------------------------------->

<div align="center" style="width:100%">
<form action="" method="post" enctype="multipart/form-data" name="newletter">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
                          <tbody>
                           <tr align="left">
                        <td align="center" valign="top" style="font-family: Arial,Helvetica,sans-serif; color: rgb(51, 51, 51);"><font face="Calibri, Trebuchet MS, sans-serif, Arial" size="2px" color="#666666"><strong>News letter name</strong> 
                        <input type="text" size="100" value="<?=$_REQUEST[clickhere];?>" name="clickhere" id="clickhere" />
                        </font></td>
                      </tr>
                            <tr></tr>
                            <tr>
                              <td valign="top"><table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tbody>
                                  <tr>
                                    <td colspan="4" bgcolor="#007797" valign="middle"><strong>Hyderabad / Secunderabad</strong></td>
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Banner Id :</strong> 
                                      <input type="text" name="hydbanner" value="<?=$_REQUEST[hydbanner];?>" id="hydbanner" /></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Event-1 Id :</strong> 
                                      <input type="text" name="hydevent1" id="hydevent1" value="<?=$_REQUEST[hydevent1];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Event-2 Id :</strong> 
                                      <input type="text" name="hydevent2" id="hydevent2" value="<?=$_REQUEST[hydevent2];?>"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Event-3 Id :</strong> 
                                      <input type="text" name="hydevent3" id="hydevent3" value="<?=$_REQUEST[hydevent3];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Event-4 Id :</strong> 
                                      <input type="text" name="hydevent4" id="hydevent4" value="<?=$_REQUEST[hydevent4];?>"/></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Event-5 Id :</strong> 
                                      <input type="text" name="hydevent5" id="hydevent5" value="<?=$_REQUEST[hydevent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Hyderabad Event-6 Id :</strong> 
                                      <input type="text" name="hydevent6" id="hydevent6" value="<?=$_REQUEST[hydevent6];?>"/></td>
                                  
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right" bgcolor="#FFFFFF" valign="middle"><a href="http://www.meraevents.com/Hyderabad-Events">More Events &gt;&gt;</a></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td align="right" bgcolor="#CECECE" valign="middle"><table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tbody>
                                  <tr>
                                    <td colspan="4" bgcolor="#007797" valign="middle"><strong>Delhi / NCR<a name="delhi" id="delhi"></a></strong></td>
                                  </tr>
                                    <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Banner Id :</strong> 
                                      <input type="text" name="Delhibanner" id="Delhibanner" value="<?=$_REQUEST[Delhibanner];?>"/></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Event-1 Id :</strong> 
                                      <input type="text" name="Delhievent1" id="Delhievent1"  value="<?=$_REQUEST[Delhievent1];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Event-2 Id :</strong> 
                                      <input type="text" name="Delhievent2" id="Delhievent2" value="<?=$_REQUEST[Delhievent2];?>"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Event-3 Id :</strong> 
                                      <input type="text" name="Delhievent3" id="Delhievent3" value="<?=$_REQUEST[Delhievent3];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Event-4 Id :</strong> 
                                      <input type="text" name="Delhievent4" id="Delhievent4" value="<?=$_REQUEST[Delhievent4];?>"/></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Event-5 Id :</strong> 
                                      <input type="text" name="Delhievent5" id="Delhievent5" value="<?=$_REQUEST[Delhievent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Delhi / NCR Event-6 Id :</strong> 
                                      <input type="text" name="Delhievent6" id="Delhievent6" value="<?=$_REQUEST[Delhievent6];?>"/></td>
                                  
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right" bgcolor="#FFFFFF" valign="middle"><a href="http://www.meraevents.com/Delhi-Events">More Events &gt;&gt;</a></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td bgcolor="#CECECE" valign="top"><table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tbody>
                                  <tr>
                                    <td colspan="4" bgcolor="#007797" valign="middle"><strong>Mumbai / Pune<a name="Mumbai" id="Mumbai"></a></strong></td>
                                  </tr>
                                    <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Banner Id :</strong> 
                                      <input type="text" name="Mumbaibanner" id="Mumbaibanner" value="<?=$_REQUEST[Mumbaibanner];?>" /></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Event-1 Id :</strong> 
                                      <input type="text" name="Mumbaievent1" id="Mumbaievent1"  value="<?=$_REQUEST[Mumbaievent1];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Event-2 Id :</strong> 
                                      <input type="text" name="Mumbaievent2" id="Mumbaievent2"  value="<?=$_REQUEST[Mumbaievent2];?>"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Event-3 Id :</strong> 
                                      <input type="text" name="Mumbaievent3" id="Mumbaievent3"  value="<?=$_REQUEST[Mumbaievent3];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Event-4 Id :</strong> 
                                      <input type="text" name="Mumbaievent4" id="Mumbaievent4"  value="<?=$_REQUEST[Mumbaievent4];?>"/></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Event-5 Id :</strong> 
                                      <input type="text" name="Mumbaievent5" id="Mumbaievent5"  value="<?=$_REQUEST[Mumbaievent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Mumbai / Pune Event-6 Id :</strong> 
                                      <input type="text" name="Mumbaievent6" id="Mumbaievent6"  value="<?=$_REQUEST[Mumbaievent6];?>"/></td>
                                  
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right" bgcolor="#FFFFFF" valign="middle"><a href="http://www.meraevents.com/Mumbai-Events">More Events &gt;&gt;</a></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td bgcolor="#CECECE" valign="top"><table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tbody>
                                  <tr>
                                    <td colspan="4" bordercolor="#007797" bgcolor="#007797" valign="middle"><strong>Bangalore <a name="Bangalore" id="Bangalore"></a></strong></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Banner Id :</strong> 
                                      <input type="text" name="Bangalorebanner" id="Bangalorebanner"  value="<?=$_REQUEST[Bangalorebanner];?>"/></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Event-1 Id :</strong> 
                                      <input type="text" name="Bangaloreevent1" id="Bangaloreevent1" value="<?=$_REQUEST[Bangaloreevent1];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Event-2 Id :</strong> 
                                      <input type="text" name="Bangaloreevent2" id="Bangaloreevent2" value="<?=$_REQUEST[Bangaloreevent2];?>"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Event-3 Id :</strong> 
                                      <input type="text" name="Bangaloreevent3" id="Bangaloreevent3" value="<?=$_REQUEST[Bangaloreevent3];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Event-4 Id :</strong> 
                                      <input type="text" name="Bangaloreevent4" id="Bangaloreevent4" value="<?=$_REQUEST[Bangaloreevent4];?>"/></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Event-5 Id :</strong> 
                                      <input type="text" name="Bangaloreevent5" id="Bangaloreevent5" value="<?=$_REQUEST[Bangaloreevent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Bangalore Event-6 Id :</strong> 
                                      <input type="text" name="Bangaloreevent6" id="Bangaloreevent6" value="<?=$_REQUEST[Bangaloreevent6];?>"/></td>
                                  
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right" bgcolor="#FFFFFF" valign="middle"><a href="http://www.meraevents.com/Bangalore-Events">More Events &gt;&gt;</a></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr>
                              <td bgcolor="#CECECE" valign="top"><table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tbody>
                                  <tr>
                                    <td colspan="4" bgcolor="#007797" valign="middle"><strong>Chennai</strong> <a name="Chennai" id="Chennai"></a></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Banner Id :</strong> 
                                      <input type="text" name="Chennaibanner" id="Chennaibanner" value="<?=$_REQUEST[Chennaibanner];?>" /></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Event-1 Id :</strong> 
                                      <input type="text" name="Chennaievent1" id="Chennaievent1" value="<?=$_REQUEST[Chennaievent1];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Event-2 Id :</strong> 
                                      <input type="text" name="Chennaievent2" id="Chennaievent2" value="<?=$_REQUEST[Chennaievent2];?>" /></td>
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Event-3 Id :</strong> 
                                      <input type="text" name="Chennaievent3" id="Chennaievent3" value="<?=$_REQUEST[Chennaievent3];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Event-4 Id :</strong> 
                                      <input type="text" name="Chennaievent4" id="Chennaievent4" value="<?=$_REQUEST[Chennaievent4];?>"/></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Event-5 Id :</strong> 
                                      <input type="text" name="Chennaievent5" id="Chennaievent5" value="<?=$_REQUEST[Chennaievent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Chennai Event-6 Id :</strong> 
                                      <input type="text" name="Chennaievent6" id="Chennaievent6" value="<?=$_REQUEST[Chennaievent6];?>" /></td>
                                  
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right" bgcolor="#FFFFFF" valign="middle"><a href="http://www.meraevents.com/Chennai-Events">More Events &gt;&gt;</a></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                           <tr>
                              <td bgcolor="#CECECE" valign="top"><table align="center" border="0" cellpadding="5" cellspacing="0" width="100%">
                                <tbody>
                                  <tr>
                                    <td colspan="4" bgcolor="#007797" valign="middle"><strong>Other Cities</strong> </a></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Other Banner Id :</strong> 
                                      <input type="text" name="Otherbanner" id="Otherbanner" value="<?=$_REQUEST[Otherbanner];?>"/></td>
                                  </tr>
                                  <tr>
                                      <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Other Event-1 Id :</strong> 
                                      <input type="text" name="Otherevent1" id="Otherevent1" value="<?=$_REQUEST[Otherevent1];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Other Event-2 Id :</strong> 
                                      <input type="text" name="Otherevent2" id="Otherevent2" value="<?=$_REQUEST[Otherevent2];?>" /></td>
                                   
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Other Event-3 Id :</strong> 
                                      <input type="text" name="Otherevent3" id="Otherevent3" value="<?=$_REQUEST[Otherevent3];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Other Event-4 Id :</strong> 
                                      <input type="text" name="Otherevent4" id="Otherevent4" value="<?=$_REQUEST[Otherevent4];?>" /></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Other Event-5 Id :</strong> 
                                      <input type="text" name="Otherevent5" id="Otherevent5" value="<?=$_REQUEST[Otherevent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Other Event-6 Id :</strong> 
                                      <input type="text" name="Otherevent6" id="Otherevent6" value="<?=$_REQUEST[Otherevent6];?>"/></td>
                                  
                                  </tr>
                                  <tr>
                                    <td colspan="4" align="right" bgcolor="#FFFFFF" valign="middle"><a href="http://www.meraevents.com/">More Events &gt;&gt;</a></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" bgcolor="#007797" valign="middle"><strong>Webinar</strong> <a name="Webinar" id="Webinar"></a></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Banner Id :</strong> 
                                      <input type="text" name="Webinarbanner" id="Webinarbanner" value="<?=$_REQUEST[Webinarbanner];?>"/></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Event-1 Id :</strong> 
                                      <input type="text" name="Webinarevent1" id="Webinarevent1" value="<?=$_REQUEST[Webinarevent1];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Event-2 Id :</strong> 
                                      <input type="text" name="Webinarevent2" id="Webinarevent2" value="<?=$_REQUEST[Webinarevent2];?>" /></td>
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Event-3 Id :</strong> 
                                      <input type="text" name="Webinarevent3" id="Webinarevent3" value="<?=$_REQUEST[Webinarevent3];?>" /></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Event-4 Id :</strong> 
                                      <input type="text" name="Webinarevent4" id="Webinarevent4" value="<?=$_REQUEST[Webinarevent4];?>" /></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Event-5 Id :</strong> 
                                      <input type="text" name="Webinarevent5" id="Webinarevent5" value="<?=$_REQUEST[Webinarevent5];?>"/></td>
                                    <td colspan="2" bgcolor="#FFFFFF" valign="middle"><strong>Webinar Event-6 Id :</strong> 
                                      <input type="text" name="Webinarevent6" id="Webinarevent6" value="<?=$_REQUEST[Webinarevent6];?>"/></td>
                                  
                                  </tr>
                                    <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-1 Path : <input type="text" name="Banner1" id="Banner1" value="<?=$_REQUEST[Banner1];?>" size="50" /> &nbsp;  Link : <input type="text" name="link1" id="link1" value="<?=$_REQUEST[link1];?>" size="50"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-2 Path : <input type="text" name="Banner2" id="Banner2" value="<?=$_REQUEST[Banner2];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link2];?>" name="link2" id="link2" size="50"/></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-3 Path : <input type="text" name="Banner3" id="Banner3" value="<?=$_REQUEST[Banner3];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link3];?>" name="link3" id="link3" size="50"/></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-4 Path : <input type="text" name="Banner4" id="Banner4" value="<?=$_REQUEST[Banner4];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link4];?>" name="link4" id="link4" size="50"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-5 Path : <input type="text" name="Banner5" id="Banner5" value="<?=$_REQUEST[Banner5];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link5];?>" name="link5" id="link5" size="50"/></td>
                                  </tr>
                                    <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-6 Path : <input type="text" name="Banner6" id="Banner6" value="<?=$_REQUEST[Banner6];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link6];?>" name="link6" id="link6" size="50"/></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-7 Path : <input type="text" name="Banner7" id="Banner7" value="<?=$_REQUEST[Banner7];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link7];?>" name="link7" id="link7" size="50"/></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle">Banner-8 Path : <input type="text" name="Banner8" id="Banner8" value="<?=$_REQUEST[Banner8];?>" size="50"/> &nbsp;  Link : <input type="text" value="<?=$_REQUEST[link8];?>" name="link8" id="link8" size="50"/></td>
                                  </tr>
                                   <tr>
                                    <td colspan="4" align="center" bgcolor="#FFFFFF" valign="middle"><input type="submit" name="submit" value="Submit" /> <input type="submit" name="submit" value="Upload" /></td>
                                  </tr>
                                </tbody>
                              </table></td>
                            </tr>
                            <tr></tr>
                          </tbody>
                        </table>

</form>
<div align="center" style="width:100%">&nbsp;</div>
</div>
<!-------------------------------CITY LIST PAGE ENDS HERE--------------------------------------------------------------->
				</div>
			</td>
		</tr>
	</table>
</div>	
</body>
</html>