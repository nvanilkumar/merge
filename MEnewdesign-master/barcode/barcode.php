<?php

require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

require_once('class/BCGcode128.barcode.php');
header('Content-Type: image/png');
// The arguments are R, G, and B for color.
$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);
$text=$_GET['text'];
$angle=$_GET['angle'];
$font = new BCGFontFile('font/Arial.ttf',15);

$code = new BCGcode128(); // Or another class name from the manual
$code->setScale(2); // Resolution
//$code->setScaleX(0);
$code->setThickness(30); // Thickness
////$code->setForegroundColor($colorFont); // Color of bars
//$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse($text); // Text


$drawing = new BCGDrawing('', $colorBack);
$drawing->setBarcode($code);
$drawing->setRotationAngle($angle) ;
$drawing->draw();
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);

