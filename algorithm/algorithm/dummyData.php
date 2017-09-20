<?php
/**
 * Created by PhpStorm.
 * User: Janith
 * Date: 9/29/2016
 * Time: 6:31 PM
 */
include 'spherical-geometry.class.php';
$file = new SplFileObject("sample_vehicle");
$node=array();
$edge=array();
$mainNodes=array();
$hashArray=array();
$count=0;
$nodeCount=0;
while(!$file->eof()) {
    $node=explode(",",$file->fgets());
    $mainNodes[$count++]=new LatLng(floatval($node[0]),floatval($node[1]));
}
$nodeCount=$count;
//var_dump($mainNodes);
foreach ($mainNodes as $nodes){
    var_dump($nodes);
    sleep(1);
}
?>