<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$activate_yyy = $_POST["activate_yyy"];
@$name = addslashes($_POST["name"]);
include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_edu_indicators_sub`(`activate_yyy`, `name`) VALUES ('".$activate_yyy."', '".$name."')";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>