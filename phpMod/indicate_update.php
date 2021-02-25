<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$activate_yyy = $_POST["activate_yyy"];
@$name = addslashes($_POST["name"]);
@$sno_edu_indicators = $_POST["sno_edu_indicators"];
include('fig.php');
if($token_time==0){
	
	$sql ="UPDATE `hesprrs_edu_indicators` SET `activate_yyy`='".$activate_yyy."',`name`='".$name."' WHERE `sno_edu_indicators` ='".$sno_edu_indicators."'";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>