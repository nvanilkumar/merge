<?php
	session_start();
	
	include_once("MT/cGlobal.php");
	$Global = new cGlobal();
    include ("jpgraph/src/jpgraph.php");
    include ("jpgraph/src/jpgraph_pie.php");
 $ent=$_REQUEST['ent'];
 $pro=$_REQUEST['pro'];
 $tra=$_REQUEST['tra'];
 $cam=$_REQUEST['cam'];
 $spi=$_REQUEST['spi'];
 $trad=$_REQUEST['trad'];
 $spo=$_REQUEST['spo'];
 $reptype=$_REQUEST['rep'];
 
 if($reptype=="tamt")
 $title="Total Transaction Amount";
 elseif($reptype=="tevents")
 $title="Total Events Added";
 elseif($reptype=="deleg")
 $title="Total Delegates Signed up for Tickets";
 
// Some data  
$data = array( $ent,$pro,$tra,$cam,$spi,$trad,$spo);
 
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
$p1->SetLegends(array("Ent (%1.1f%%)","Prof (%1.1f%%)","Tran (%1.1f%%)","Camp (%1.1f%%)",
"Spir (%1.1f%%)", "Trad (%1.1f%%)", "Sports (%1.1f%%)"));
$graph->legend->SetPos(0.5,0.97,'center','bottom');
$graph->legend->SetColumns(3);

$graph->Add($p1);
$graph->Stroke();
?>
