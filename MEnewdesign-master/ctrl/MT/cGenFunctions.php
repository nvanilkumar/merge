<?php 
class cGenFunctions
{	
	function generateSerial($length=6,$level=2)
	{
	
	   list($usec, $sec) = explode(' ', microtime());
	   srand((float) $sec + ((float) $usec * 100000));
	
	   $validchars[1] = "01234567896347563456832746583465934756932784";
	   $validchars[2] = "012345678913247129793485934597846823445737567382347328747662";
	   $validchars[3] = "01234567890129880320723473454088023423034328974";
	
	   $password  = "";
	   $counter   = 0;
	
	   while ($counter < $length) {
		 $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);
	
		 // All character must be different
		 if (!strstr($password, $actChar)) {
			$password .= $actChar;
			$counter++;
		 }
	   }
	
	   return $password;
	
	}
	function generatePassword($length=6,$level=2)
	{
	
	   list($usec, $sec) = explode(' ', microtime());
	   srand((float) $sec + ((float) $usec * 100000));
	
	   $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
	   $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	   $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";
	
	   $password  = "";
	   $counter   = 0;
	
	   while ($counter < $length) {
		 $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);
	
		 // All character must be different
		 if (!strstr($password, $actChar)) {
			$password .= $actChar;
			$counter++;
		 }
	   }
	
	   return $password;
	
	}
	
	//<< Fetch date from table<<
	
	function PrintDate($date)
	{
		//2009-07-06 17:38:30
		//mktime(hour,minute,second,month,day,year,is_dst) 
		$df1 = explode(" ",$date);
		$df2 = explode("-",$df1[0]);
				
		$time = explode(":",$df1[1]);
	$datetimestmp = mktime($time[0],$time[1],$time[2],$df2[1],$df2[2],$df2[0]);	
		 
	return date("d-m-Y h:i:s A",$datetimestmp);
	}
	
	function InsertDate($dateI)
	{
		//22-7-2009 05:38:30 PM
		
		$df1 = explode(" ",$dateI);
		//print_r($df1);
		$df2 = explode("-",$df1[0]);
		//print_r($df2);		
		$time = explode(":",$df1[1]);
		//print_r($time);
		//echo $df1[2];
		//echo $time[0];
		if($df1[2]=='AM')
		{
			 $hour1 = $time[0];
		}
		if($df1[2]=='PM')
		{
			$hour1 = $time[0]+12;
		}
		
		//echo $hour1;
		
		
	$datetimestmp1 = mktime($hour1,$time[1],$time[2],$df2[1],$df2[0],$df2[2]);	
		 
	return date("Y-m-d H:m:s A",$datetimestmp1);
	}
	//>> Fetch date from table>>	
}
?>