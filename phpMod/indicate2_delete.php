<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators_sub = $_POST["sno_edu_indicators_sub"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_edu_indicators_sub` WHERE `sno_edu_indicators_sub` in (".$sno_edu_indicators_sub.")";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>