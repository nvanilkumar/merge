 <?php                          
	include('../ctrl/includes/commondbdetails.php');
 
include("../ctrl/MT/cGlobali.php");
$Global=new cGlobali();
include_once '../ctrl/includes/common_functions.php';
$commonFunctions = new functions();
/* 	mysql_pconnect($DBServerName,$DBUserName,$DBPassword) or die("cannot connect to db : ".mysql_error());
     mysql_select_db($DBIniCatalog) or die("DB not found :".mysql_error()) */;
	
 function cName($id)
 {
     $Global=new cGlobali();
 $sqlct=$Global->SelectQuery("select `name` from city where id=$id");    
  $ctname=$sqlct[0]['name'];
  return $ctname;
 }
 function cLoc($id)
 {
     $Global=new cGlobali();
 if($id!=0){
 $sqlloc=$Global->SelectQuery("select `name` from locality where id=$id");    
  $locname=$sqlloc[0]['name'];
  return $locname;
  }
 }
$file= fopen("../results.xml", "w");
 $xml="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
  $xml.=" <events>
    <last-updated>".date('Y-m-d H:i:s')."</last-updated> 
    <provider>
        <icon>http://static.meraevents.com/images/static/me-logo.svg</icon> 
        <logo>http://static.meraevents.com/images/static/me-logo.svg</logo> 
        <link>http://www.meraevents.com</link> 
        <support>
            <contact type=\"phone\">91-40-40404160</contact>
                <contact type=\"email\">support@meraevents.com</contact>
                    </support>
    </provider>"; 
            //and CategoryId=1    
                $query="SELECT * FROM event where  startdatetime >=now() and status=1 and eventmode=0 and deleted=0  ORDER BY startdatetime ASC";
                $result = $Global->justSelectQuery($query);

                while($r=$result->fetch_assoc())
                {
                $Title=stripslashes(strip_tags($r["title"])); 
                
				 $CategoriesQuery = "SELECT name FROM category where id=".$r["categoryid"]; 
                            $CategoriesRES = $Global->SelectQuery($CategoriesQuery);
                 
                   $Cate=$CategoriesRES[0]['name'];
                                  
                $sqltck="select `name`,quantity,price from ticket where eventid=".$r["id"];
                 $restck=$Global->justSelectQuery($sqltck);
                 $tck='';
                 while($rowtck=$restck->fetch_assoc()){
                
                 $tck.='<category name="'.preg_replace("/[^A-Za-z0-9]/","", stripslashes(strip_tags($rowtck[name]))).'">INR '.$rowtck[price].'</category> 
         <inventory>'.$rowtck[quantity].'</inventory> 
         <convenience>'.$st.'</convenience>'; 
             }
                
                $Logo1=$r["thumbnailfileid"];
                //$Logo1 = preg_replace('/([^\.]*).([^|S]*)/', '$1_t.$2', $r["Logo"]);

        if ($Logo1 = 0) {
        $sql_logo = $Global->SelectQuery("select logofileid from organizer where userid=" . $r["ownerid"]);
        if ($sel_logo[0][logofileid] > 0) {
            $img = $Global->SelectQuery("select path from file where id =" . $sel_logo[0][logofileid]);
            $Logo = "http://static.meraevents.com/content/logo/" . $img[0][path];
        } else {
             $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0][path];
        }
    } else {
        $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
        $Logo = "http://static.meraevents.com/content/" . $img[0][path];
    }

    if(strlen(stripslashes(strip_tags($r["description"])))>500){
                        $pos = strpos(stripslashes(strip_tags($r["description"])), '.',500);
                        $IntendedFor=substr(stripslashes(strip_tags($r["description"])),0,$pos+1);
                        }else{
                        $IntendedFor= stripslashes(strip_tags($r["description"]));
                        }
                
                $Id=$r["id"];
                $LocId=$r["localityid"];
                $CityId=$r["cityid"];
                if($r["registrationtype"]==1)
                {
                $yes="No";
                }else{
                $yes="Yes";
                }
                
//                $stdate=date('Y/m/d',strtotime($r["startdatetime"]));
//                $stime=date('h:i A',strtotime($r["startdatetime"]));
//                $endate=date('Y/m/d',strtotime($r["enddatetime"]));
//                $etime=date('h:i A',strtotime($r["enddatetime"]));
                
        $stdate =$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stdate=date('Y/m/d',strtotime($stdate));
        $stime=$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stime=date('h:i A',strtotime($stime));
        $endate=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $endate=date('Y/m/d',strtotime($endate));
        $etime=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $etime=date('h:i A',strtotime($etime));
        


                 $d= strtotime($r["enddatetime"])-strtotime($r["startdatetime"]);

                 $remain=$d%86400; 
                 $diff=intval($remain/3600); 
                //$diff=date('H',(strtotime($r["EndDt"])-strtotime($r["StartDt"]))); 
                $sqluser=$Global->SelectQuery("select email,mobile from user where id=".$r["ownerid"]);
              $vowels = array(cLoc($LocId), cName($CityId),",");
 $Venue = str_replace($vowels, " ", $r["venuename"]);

                


  $xml.='<event>
      <eventid>'.$r["id"].'</eventid>
      <eventurl>http://www.meraevents.com/event/'.$r["url"].'</eventurl>
      <name><![CDATA['.$Title.']]></name>
      <description><![CDATA['.nl2br(stripslashes($IntendedFor)).']]></description>
        <language></language> 
        <category>'. $Cate.'</category> 
      <duration>'.($diff*60).'Mins</duration>
       <image><![CDATA['.$Logo.']]></image>
        <ticketed>'.$yes.'</ticketed>
      <venue>';
                
	$ven=stripslashes($r["venuename"]);
	$location=cLoc($LocId);
	$cty=cName($CityId);
      if($location!=""){
	if(substr_count($ven,$location)>=1)
       {
      
       }else{
	$ven.=" ".$location;
	}
     }
 if($cty!=""){
 if(substr_count($ven,$cty)>=1)
 {
 
 }else{
 $ven.=" ".$cty;
 }
 }					
				
				$xml.='<street-address><![CDATA['.$ven.']]></street-address> 
                <landmark></landmark>
                <locality>'.cLoc($LocId)." ".cName($CityId).'</locality> 
                <region>'.cName($CityId).'</region> 
                <postal-code>'.$r["pincode"].'</postal-code> 
      </venue>
      <contact>
       <email>support@meraevents.com</email>
        <phones>
          <number>040-40404160</number>
        </phones>
      
      </contact>
      <shows>
    <show>
      <id>'.$r["id"].'</id>
      <start-date>'.$stdate.'</start-date>
      <start-time>'.$stime.'</start-time>
      <end-date>'.$endate.'</end-date>
      <end-time>'.$etime.'</end-time>
         '.$tck.'
      
          </show>
    
      </shows>
    </event>';
    }
     $xml.='</events>';

     
    fwrite($file, $xml);
    
    
    
    
    $file1= fopen("../results1.xml", "w");
 $xml1="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
  $xml1.=" <events>
    <last-updated>".date('Y-m-d H:i:s')."</last-updated> 
    <provider>
        <icon>http://static.meraevents.com/images/static/me-logo.svg</icon> 
        <logo>http://static.meraevents.com/images/static/me-logo.svg</logo> 
        <link>http://www.meraevents.com</link> 
        <support>
            <contact type=\"phone\">91-40-40404160</contact>
                <contact type=\"email\">support@meraevents.com</contact>
                    </support>
    </provider>"; 
            //and CategoryId=1    
                $query="SELECT * FROM event where  startdatetime >=now() and status=1 and eventmode=0  ORDER BY startdatetime ASC";
                $result = $Global->justSelectQuery($query);

                while($r=$result->fetch_assoc())
                {
                $Title=stripslashes(strip_tags($r["title"])); 
                
                 $CategoriesQuery = "SELECT name FROM category where id=".$r["categoryid"]; 
                            $CategoriesRES = $Global->SelectQuery($CategoriesQuery);
                 
                   $Cate=$CategoriesRES[0]['name'];
                  
                 
              $sqltck="select `name`,quantity,price from ticket where eventid=".$r["id"];
                 $restck=$Global->justSelectQuery($sqltck);
                 $tck='';
                 while($rowtck=$restck->fetch_assoc()){
                
                 $tck.='<category name="'.preg_replace("/[^A-Za-z0-9]/","", stripslashes(strip_tags($rowtck[name]))).'">INR '.$rowtck[price].'</category> 
         <inventory>'.$rowtck[quantity].'</inventory> 
         <convenience>'.$st.'</convenience>'; 
             }
                
                 $Logo1=$r["thumbnailfileid"];
                //$Logo1 = preg_replace('/([^\.]*).([^|S]*)/', '$1_t.$2', $r["Logo"]);

        if($Logo1 = 0)
            {      
            $sql_logo=$Global->SelectQuery("select logofileid from organizer where userid=".$r["ownerid"]); 
                             if($sel_logo[0][logofileid]> 0){
								$img = $Global->SelectQuery("select path from file where id =".$sel_logo[logofileid]);
                          $Logo = "http://static.meraevents.com/content/logo/" . $img[0][path];
               } else {    
                $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0]['path'];
            }} else {
				 $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0]['path'];
            }

                                        
                        $IntendedFor= stripslashes(strip_tags($r["description"]));
                        
                
                $Id=$r["id"];
                $CityId=$r["cityid"];
                $LocId=$r["localityid"];
                if($r["registrationtype"]==1)
                {
                $yes="No";
                }else{
                $yes="Yes";
                }
				
//                $stdate=date('Y/m/d',strtotime($r["startdatetime"]));
//                $stime=date('h:i A',strtotime($r["startdatetime"]));
//                $endate=date('Y/m/d',strtotime($r["enddatetime"]));
//                $etime=date('h:i A',strtotime($r["enddatetime"]));
                
        $stdate =$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stdate=date('Y/m/d',strtotime($stdate));
        $stime=$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stime=date('h:i A',strtotime($stime));
        $endate=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $endate=date('Y/m/d',strtotime($endate));
        $etime=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $etime=date('h:i A',strtotime($etime));
        

                 $d= strtotime($r["enddatetime"])-strtotime($r["startdatetime"]);

                 $remain=$d%86400; 
                 $diff=intval($remain/3600); 
                //$diff=date('H',(strtotime($r["EndDt"])-strtotime($r["StartDt"]))); 
                $sqluser=$Global->SelectQuery("select email,mobile from user where id=".$r["ownerid"]);
                
              
                


  $xml1.='<event>
      <eventid>'.$r["id"].'</eventid>
      <eventurl>http://www.meraevents.com/event/'.$r["url"].'</eventurl>
      <name><![CDATA['.$Title.']]></name>
      <description><![CDATA['.nl2br(stripslashes($IntendedFor)).']]></description>
        <language></language> 
        <category>'.$Cate.'</category> 
      <duration>'.($diff*60).'Mins</duration>
       <image><![CDATA['.$Logo.']]></image>
        <ticketed>'.$yes.'</ticketed>
      <venue>
                <street-address><![CDATA['.substr(stripslashes($r["venuename"]),0,100).']]></street-address> 
                <landmark></landmark>
                <locality>'.cLoc($LocId).'</locality> 
                <region>'.cName($CityId).'</region> 
                <postal-code>'.$r["pincode"].'</postal-code> 
      </venue>
      
      <shows>
    <show>
      <id>'.$r["id"].'</id>
      <start-date>'.$stdate.'</start-date>
      <start-time>'.$stime.'</start-time>
      <end-date>'.$endate.'</end-date>
      <end-time>'.$etime.'</end-time>
         '.$tck.'
      
          </show>
    
      </shows>
    </event>';
    }
     $xml1.='</events>';

     
    fwrite($file1, $xml1);
    
    
    
    $file2= fopen("../results2.xml", "w");
 $xml2="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
 $xml2.="<Events>
  <Publisher name=\"MeraEvents.com\" publisherImage=\"http://static.meraevents.com/images/static/me-logo.svg\" />";
              //and CategoryId=1    
                $query="SELECT * FROM event where  startdatetime >=now() and status=1 and eventmode=0  ORDER BY startdatetime ASC";
                $result = $Global->justSelectQuery($query);

                while($r=$result->fetch_assoc())
                {
                $Title=stripslashes(strip_tags($r["title"])); 
                
                 $CategoriesQuery = "SELECT `name` FROM category where id=".$r["categoryid"]; 
                 $resca=$Global->SelectQuery($CategoriesQuery);
                 
                $Cat1=$resca[0]['name'];
                 
                
                
               $Logo1=$r["thumbnailfileid"];
                //$Logo1 = preg_replace('/([^\.]*).([^|S]*)/', '$1_t.$2', $r["Logo"]);

        if($Logo1 = 0)
            {      
            $sql_logo=$Global->SelectQuery("select logofileid from organizer where userid=".$r["ownerid"]); 
                             if($sel_logo[0][logofileid]> 0){
								$img =$Global->SelectQuery("select path from file where id =".$sel_logo[logofileid]);
                          $Logo="http://static.meraevents.com/content/logo/".$img[0][path];
               } else {    
                $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0]['path'];
            }} else {
				 $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0]['path'];
            }

            

                     
                         if(strlen(stripslashes(strip_tags($r["description"])))>500){
                        $pos = strpos(stripslashes(strip_tags($r["description"])), '.',500);
                        $IntendedFor=substr(stripslashes(strip_tags($r["description"])),0,$pos+1);
                        }else{
                        $IntendedFor= stripslashes(strip_tags($r["description"]));
                        }

                        
                
                $Id=$r["id"];
                $CityId=$r["cityid"];
                $LocId=$r["localityid"];
                if($r["registrationtype"]==1)
                {
                $yes="Free";
                }else{
                $yes="Paid";
                }
//                $stdate=date('d/m/Y',strtotime($r["startdatetime"]));
//                $stime=date('h:i A',strtotime($r["startdatetime"]));
//                $endate=date('d/m/Y',strtotime($r["enddatetime"]));
//                $etime=date('h:i A',strtotime($r["enddatetime"]));
                
        $stdate =$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stdate=date('d/m/Y',strtotime($stdate));
        $stime=$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stime=date('h:i A',strtotime($stime));
        $endate=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $endate=date('d/m/Y',strtotime($endate));
        $etime=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $etime=date('h:i A',strtotime($etime));

                        
                


  $xml2.='<Event>
    <EventId>'.$r["id"].'</EventId>
    <Name><![CDATA['.$Title.']]></Name>
    <Description><![CDATA['.nl2br(stripslashes($IntendedFor)).']]></Description>
    <EventImagePath><![CDATA['.$Logo.']]></EventImagePath>
    <EventFromDate>'.$stdate.'</EventFromDate>
    <EventToDate>'.$endate.'</EventToDate>
    <EventFromTime>'.$stime.'</EventFromTime>
    <EventToTime>'.$etime.'</EventToTime>
    <VenueShortAddress><![CDATA['.substr(stripslashes($r["venuename"]),0,100).']]></VenueShortAddress>
    <phonenumber>+91-40-40404160</phonenumber>
    <EventTicketType>'.$yes.'</EventTicketType>
    <EventCatagory><![CDATA['.$Cat1.']]></EventCatagory>
    <VenueCompleteAddress><![CDATA['.stripslashes($r["venuename"])." ".cLoc($LocId)." ".cName($CityId).']]></VenueCompleteAddress>
    <TargetUrl>http://www.meraevents.com/event/'.$r["url"].'</TargetUrl>    
  </Event>';
    }
     $xml2.='</Events>';

     
    fwrite($file2, $xml2);
	
	
	
	/*-------------------- Fun Mango Feed---------*/
	

 $file3= fopen("../results_funmango.xml", "w");
 $xml3="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
 $xml3.="<Events>
  <Publisher name=\"MeraEvents.com\" publisherImage=\"http://static.meraevents.com/images/static/me-logo.svg\" />";
              //and CategoryId=1    
                $query="SELECT * FROM event where  startdatetime >=now() and status=1 and eventmode=0  ORDER BY startdatetime ASC";
                $result = $Global->justSelectQuery($query);

                while($r=$result->fetch_assoc())
                {
                $Title=stripslashes(strip_tags($r["title"])); 
                
                  $CategoriesQuery = "SELECT `name` FROM category where id=".$r["categoryid"]; 
                 $resca=$Global->SelectQuery($CategoriesQuery);
                 
                $Cat1=$resca[0]['name'];
                 
                
                $Logo1=$r["thumbnailfileid"];
                //$Logo1 = preg_replace('/([^\.]*).([^|S]*)/', '$1_t.$2', $r["Logo"]);

        if($Logo1 = 0)
            {      
            $sql_logo=$Global->SelectQuery("select logofileid from organizer where userid=".$r["ownerid"]); 
                             if($sel_logo[0][logofileid]> 0){
								$img = $Global->SelectQuery("select path from file where id =".$sel_logo[logofileid]);
                          $Logo="http://static.meraevents.com/content/logo/".$img[0][path];
               } else {    
                
                $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0][path];
            }} else {
			 $img = $Global->SelectQuery("select path from file where id =" . $Logo1);
            $Logo = "http://static.meraevents.com/content/" . $img[0][path];
            }

            

                     
                         if(strlen(stripslashes(strip_tags($r["description"])))>500){
                        $pos = strpos(stripslashes(strip_tags($r["description"])), '.',500);
                        $IntendedFor=substr(stripslashes(strip_tags($r["description"])),0,$pos+1);
                        }else{
                        $IntendedFor= stripslashes(strip_tags($r["description"]));
                        }

                        
               $Id=$r["id"];
                $CityId=$r["cityid"];
                $LocId=$r["localityid"];
                if($r["registrationtype"]==1)
                {
                $yes="Free";
                }else{
                $yes="Paid";
                }
//                $stdate=date('d/m/Y',strtotime($r["startdatetime"]));
//                $stime=date('h:i A',strtotime($r["startdatetime"]));
//                $endate=date('d/m/Y',strtotime($r["enddatetime"]));
//                $etime=date('h:i A',strtotime($r["enddatetime"]));
                
        $stdate =$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stdate=date('d/m/Y',strtotime($stdate));
        $stime=$commonFunctions->convertTime($r["startdatetime"], DEFAULT_TIMEZONE);
        $stime=date('h:i A',strtotime($stime));
        $endate=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $endate=date('d/m/Y',strtotime($endate));
        $etime=$commonFunctions->convertTime($r["enddatetime"], DEFAULT_TIMEZONE);
        $etime=date('h:i A',strtotime($etime));


                        
                


  $xml3.='<Event>
    <EventId>'.$r["id"].'</EventId>
    <EventName><![CDATA['.$Title.']]></EventName>
	<EventType>'.$yes.'</EventType>
	<EventLogo><![CDATA['.$Logo.']]></EventLogo>
    <Description><![CDATA['.nl2br(stripslashes($IntendedFor)).']]></Description>
    <Location>'.cLoc($LocId).'</Location>
     <City>'.cName($CityId).'</City>
	<Address><![CDATA['.stripslashes($r["venuename"])." ".cLoc($LocId)." ".cName($CityId).']]></Address>
	<Latitude>N/A</Latitude>
	<Longitude>N/A</Longitude>
	<ContactPhoneNo>+91-40-40404160</ContactPhoneNo>
	<DirectionNotes>N/A</DirectionNotes>
    <EventFromDate>'.$stdate.'</EventFromDate>
    <EventToDate>'.$endate.'</EventToDate>
    <EventFromTime>'.$stime.'</EventFromTime>
    <EventToTime>'.$etime.'</EventToTime>
    <AdditionalNotes>N/A</AdditionalNotes>
   <EventUrl>http://www.meraevents.com/event/'.$r["url"].'</EventUrl>    
  </Event>';
    }
     $xml3.='</Events>';

     
    fwrite($file3, $xml3);
	
	/*--------------------End Fun Mango Feed---------*/



    ?> 
