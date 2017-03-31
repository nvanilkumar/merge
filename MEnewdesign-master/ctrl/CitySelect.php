<?php
session_start();


include_once("MT/cGlobal.php");
$Global = new cGlobal();
 $id = $_REQUEST['id'];

$tabindx = 'tabindex=\"2\"';



echo "<select  name=\"CityId\"  id=\"CityId\" style=\"width:200px\" >";
if($id =="---select---") {
	echo "<option value=\"---select---\">---Select---</option>";
} else {
	$galleryQuery = "SELECT Id, City FROM Cities WHERE StateId=".$id;
	$CatLoad = $Global->SelectQuery($galleryQuery);
	
	if(count($CatLoad) > 0 ) {
		foreach($CatLoad as $id => $value) {
			echo "<option value=\"" . $value['Id'] . "\">" . $value['City'] . "</option>";
		}
	} else {
			echo "<option value=\"\">No City</option>";
	}
}
echo "</select>";
 ?>