<?php
function fromMySqlDate($MySqlDate)#YYYY-MM-DD
{
	$MySqlDate=explode(" ",$MySqlDate);
	$arrDt=explode("-",$MySqlDate[0]);
	$months = array('Month','Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
											'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
	for($i=0;$i<count($months);$i++)
	{
	  if($arrDt[1] == $i)
	  {
		$new_month=$months[$i];
	  }
	}
	return $arrDt[2]."-".$new_month."-".$arrDt[0];#DD/MM/YYYY
}

function fromMySqlDate_digit($MySqlDate)#YYYY-MM-DD
{
	$MySqlDate=explode(" ",$MySqlDate);
	$arrDt=explode("-",$MySqlDate[0]);
	return $arrDt[1]."-".$arrDt[2]."-".$arrDt[0];#MM/DD/YYYY
}


function toSqlDate_digit($MySqlDate)
{
$MySqlDate=explode(" - ",$MySqlDate);
$arrDt=explode("-",$MySqlDate[0]);
return $arrDt[2]."-".$arrDt[0]."-".$arrDt[1];#YYYY-MM-DD
}


?>