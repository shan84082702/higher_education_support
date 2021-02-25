<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators_detail = $_POST["sno_edu_indicators_detail"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_edu_indicators_detail` WHERE `sno_edu_indicators_detail` in (".$sno_edu_indicators_detail.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_edu_management` WHERE `sno_edu_indicator_detail` = '".$sno_edu_indicators_detail."'";
	$conn->query($sql) ;
	
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>