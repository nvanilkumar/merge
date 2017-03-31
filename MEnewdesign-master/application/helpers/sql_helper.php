<?php
	function sqlHelperGetSelect($input)
	{

		$response="";
		if(count($input)>0)
			foreach($input as $key=>$val)
			$response.=$val." as `$key` ,";
		else
			$response=" id ";
		$response=rtrim($response,",");
		return $response;
	}
	
?>
