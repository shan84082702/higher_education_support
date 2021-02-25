<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators = $_POST["sno_edu_indicators"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_edu_indicators` WHERE `sno_edu_indicators` in (".$sno_edu_indicators.")";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>