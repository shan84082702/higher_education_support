<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$name =addslashes($_POST["name"]);
include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_questionnarie_category`( `name`) VALUES ('".$name."')";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>