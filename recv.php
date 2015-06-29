<?php

$devid	= $_GET["devid"];
$msg	= $_GET["max"];

if(empty($devid))
{
	$postStr	= $GLOBALS["HTTP_RAW_POST_DATA"];
	$obj		= json_decode($postStr);
	$devid		= $obj->devid;
	$max		= $obj->max; 
}

if(empty($devid)){
	die(json_encode(array("ret"=>-1,"msg"=>"no devid")));
}

include("qmc.php");
$out = QMC::output($devid, $max);
echo json_encode(array("ret"=>0,"result"=>$out));

?>
