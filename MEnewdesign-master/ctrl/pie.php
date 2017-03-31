<?php
	session_start();
	
	include_once("MT/cGlobal.php");
	$Global = new cGlobal();
    include ("jpgraph/src/jpgraph.php");
    include ("jpgraph/src/jpgraph_pie.php");
 $hyd=$_REQUEST['hyd'];
 $mum=$_REQUEST['mum'];
 $pune=$_REQUEST['pune'];
 $bang=$_REQUEST['bang'];
 $chen=$_REQUEST['chen'];
 $del=$_REQUEST['del'];
 $oth=$_REQUEST['oth'];
 $reptype=$_REQUEST['rep'];
 
 if($reptype=="tamt")
 $title="Total Transaction Amount";
 elseif($reptype=="tevents")
 $title="Total Events Added";
 elseif($reptype=="deleg")
 $title="Total Delegates Signed up for Tickets";
 
// Some data  
$data = array( $hyd,$mum,$pune,$bang,$chen,$del,$oth);
 
// A new graph
$graph = new PieGraph(400,420);
$graph->SetAntiAliasing();
 
$graph->title->SetFont(FF_ARIAL, FS_BOLD, 14);
$graph->title->Set($title);
$graph->title->SetMargin(10);
 
$graph->subtitle->SetFont(FF_ARIAL, FS_BOLD, 10);
$graph->subtitle->Set('(Percentages by city)');
 
// The pie plot
$p1 = new PiePlot($data);
$p1->SetSliceColors(array('darkred','navy','lightblue','orange','gray','teal','green','pink'));
 
// Move center of pie to the left to make better room
// for the legend
$p1->SetSize(0.3);
$p1->SetCenter(0.5,0.47);
$p1->value->Show();
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,10);
 
// Legends
$p1->SetLegends(array("Hyd (%1.1f%%)","Mum (%1.1f%%)","Pune (%1.1f%%)","Bang (%1.1f%%)",
"Chen (%1.1f%%)", "Del (%1.1f%%)", "Oth (%1.1f%%)"));
$graph->legend->SetPos(0.5,0.97,'center','bottom');
$graph->legend->SetColumns(3);

$graph->Add($p1);
$graph->Stroke();
?>
