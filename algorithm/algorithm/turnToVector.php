<?php
/**
 * Created by PhpStorm.
 * User: janith
 * Date: 9/20/16
 * Time: 5:49 PM
 */
include 'spherical-geometry.class.php';
$file = new SplFileObject("sample_route");
$node=array();
$edge=array();
$accumulateEdge=array();
$mainNodes=array();
$nodes=array();
$count=0;
$nodeCount=0;
    while(!$file->eof()) {
            $node=explode(",",$file->fgets());
            $mainNodes[$count++]=new LatLng(floatval($node[0]),floatval($node[1]));
        }
$nodeCount=$count;
$origin=new LatLng(5.8,78);
$edgeSize=5;
$accumulateEdgeTemp=0;
$countTemp=0;
$totalNode=0;
for ($count=0;$count<$nodeCount-1;$count++){

    $edge[$count]= SphericalGeometry::computeDistanceBetween($mainNodes[$count],$mainNodes[$count+1]);
    $tempAngle=SphericalGeometry::computeHeading($mainNodes[$count],$mainNodes[$count+1]);

    $nodes[$countTemp++]=$mainNodes[$count];
    $accumulateEdge[$countTemp-1]=$accumulateEdgeTemp;
    for ($i=1;$i<$edge[$count]/$edgeSize;$i++){
        $nodes[$countTemp++]=SphericalGeometry::computeOffset($mainNodes[$count],$i*$edgeSize,$tempAngle);
        $accumulateEdgeTemp+=$edgeSize;
        $accumulateEdge[$countTemp-1]=$accumulateEdgeTemp;
    }
    $accumulateEdgeTemp+=($edge[$count]-(($i-1)*$edgeSize));

}
$totalNode=$countTemp;

var_dump(sizeof($nodes), sizeof($mainNodes));
$distanceMax=0;
$distanceMin=10000000;
$angleMax=-180;
$angleMin=180;

$distance=array_fill(0,$totalNode,0);
$angle=array_fill(0,$totalNode,0);


for ($count=0;$count<$totalNode;$count++) {

    $distance[$count]=SphericalGeometry::computeDistanceBetween($origin,$nodes[$count]);
    if($distance[$count]<=$distanceMin) $distanceMin=$distance[$count];
    if($distance[$count]>$distanceMax) $distanceMax=$distance[$count];

    $angle[$count]=SphericalGeometry::computeHeading($origin,$nodes[$count]);
    if($angle[$count]<=$angleMin) $angleMin=$angle[$count];
    if($angle[$count]>$angleMax) $angleMax=$angle[$count];
}

//hashing

$anglePartitionNo=100000;
$distancePartitionNo=100000;
$hashAngleArray=array_fill(0,$anglePartitionNo,null);
$hashDistanceArray=array_fill(0,$distancePartitionNo,null);
$hashAngleCount=array_fill(0,$anglePartitionNo+1,0);
$hashDistanceCount=array_fill(0,$distancePartitionNo+1,0);
$angleDivisor=($angleMax-$angleMin)/$anglePartitionNo;
$distanceDivisor=($distanceMax-$distanceMin)/$distancePartitionNo;

for ($count=0;$count<$totalNode;$count++) {
    $n = intval(($angle[$count] - $angleMin) / $angleDivisor);
    $m = intval(($distance[$count] - $distanceMin) / $distanceDivisor);
    $hashAngleArray[$n][$hashAngleCount[$n]++] = $count;
    $hashDistanceArray[$m][$hashDistanceCount[$m]++] = $count;
}

    $LatLng=new LatLng(6.82010000000001,80.08676000000001);
    var_dump($LatLng);
    $distance=SphericalGeometry::computeDistanceBetween($origin,$LatLng);
    $angle=SphericalGeometry::computeHeading($origin,$LatLng);
    $nodeIs=null;

    $errorDistanceMargin=20;
    $angleVal=intval(($angle-$angleMin)/$angleDivisor);
    $distanceVal=intval(($distance-$distanceMin)/$distanceDivisor);

    $errorAngleOffset=intval($errorDistanceMargin/(2*$distance*$angleDivisor)+1);
    $errorDistanceOffset=intval($errorDistanceMargin/(2*$distanceDivisor)+1);
    var_dump('Error distance and angle margin');
    var_dump($errorAngleOffset,$errorDistanceOffset);
    $decrement1=0;
    $angleIds=array();
    if($angleVal<0)$angleVal=0;
    elseif($angleVal>$anglePartitionNo)$angleVal=$anglePartitionNo;
    while($decrement1<=2*$errorAngleOffset){
        $index=$angleVal-$decrement1+$errorAngleOffset;
        if($index<0){echo "there is no route\n";break;}
        if($hashAngleArray[$index]!=null) {
            $angleIds = array_merge($angleIds, $hashAngleArray[$index]);
        }
        $decrement1+=1;
    }
    $decrement2=0;
    $distanceIds=array();
    if($distanceVal<0)$distanceVal=0;
    elseif($distanceVal>$distancePartitionNo)$distanceVal=$distancePartitionNo;
    while($decrement2<=2*$errorDistanceOffset){
        $index=$distanceVal-$decrement2+$errorDistanceOffset;
        if($index<0){echo "there is no route\n";break;}
        if($hashDistanceArray[$index]!=null) {
            $distanceIds = array_merge($distanceIds, $hashDistanceArray[$index]);
        }
        $decrement2+=1;
    }

    var_dump($angleIds);
    var_dump($distanceIds);


    $tempMinNodeDistance=10000;
    foreach ($angleIds as $angleId){
        $distanceTemp = SphericalGeometry::computeDistanceBetween($nodes[$angleId], $LatLng);
        if (($tempMinNodeDistance > $distanceTemp)&&($distanceTemp<$errorDistanceMargin)) {
            echo("distanceToNearNode:".$distanceTemp . "\n");
            $tempMinNodeDistance = $distanceTemp;
            $nodeIs = $angleId;
        }
    }
    foreach ($distanceIds as $distanceId){
        $distanceTemp = SphericalGeometry::computeDistanceBetween($nodes[$distanceId], $LatLng);
        if (($tempMinNodeDistance > $distanceTemp)&&($distanceTemp<$errorDistanceMargin)) {
            echo("distanceToNearNode:".$distanceTemp . "\n");
            $tempMinNodeDistance = $distanceTemp;
            $nodeIs = $distanceId;
        }
    }
    if ($nodeIs==null){
        echo ("can not find a node\n");
    }
    else {
        echo ("a node fund..! \ncount:".$nodeIs."\n");
        var_dump($LatLng);
        echo ("was Given.\n");
        var_dump($nodes[$nodeIs]);
        echo ("is Fund.\n");
    }
    echo ("memory Usage in Bytes:".memory_get_usage());
    //var_dump($accumulateEdge[$totalNode-1]);
?>