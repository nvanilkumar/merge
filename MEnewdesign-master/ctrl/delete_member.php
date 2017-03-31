<?php 
		include_once("includes/application_top.php");
		include('includes/functions.php');
		include('includes/logincheck.php');
		include('includes/paginator.php');
		
		$child_id=$_GET['id'];
/**************************commented on 17082009 need to remove afterwords**************************
		$sql_get_child_id = "SELECT * FROM team_member WHERE child_id=".$child_id;
		$sql_res = mysql_query($sql_get_child_id) or die(mysql_error());
		$sql_row = mysql_fetch_array($sql_res);
	
		$nid = $sql_row['nid'];
	
		 //----DELETE FROM team_member TABLE--- //
		 $sql_delete = "DELETE FROM team_member WHERE child_id=".$child_id;
		//mysql_query($sql_delete);
		
		//----DELETE FROM users TABLE--- //
			$delete_users="DELETE FROM users WHERE uid = '".$child_id."' ";
			//mysql_query($delete_users);
		//------------------------------------------//
		
		//----DELETE FROM users_role TABLE--- //
			$delete_users="DELETE FROM users_roles WHERE uid = '".$child_id."' ";
			//mysql_query($delete_users);
		//------------------------------------------//
		
		// GET GID TO DELETE Edit ACCESS OF THIS USER FOR THIS PARTICULAR NODE
		 $select_node_access = "SELECT * FROM node_access WHERE grant_view='1' AND grant_update='1' AND grant_delete='0' AND nid='".$nid."'";
		$sql_node=mysql_query($select_node_access);
		$r_node=mysql_fetch_array($sql_node);
		$n_gid=$r_node['gid'];
		// DELETE FROM COHERENT ACCESS USER SO THAT THE USER CAN NO LONGER ACCESS NODE FOE EDITING
		 $delete_coherent_access_user="DELETE FROM coherent_access_user WHERE uid= '".$child_id."' and gid='".$n_gid."'";
		//mysql_query($delete_coherent_access_user);
****************************************************/	
		header('location:view_teammembers.php');
?>