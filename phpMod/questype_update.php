<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$name = addslashes($_POST["name"]);
@$sno_questionnarie_category = $_POST["sno_questionnarie_category"];
include('fig.php');
if($token_time==0){
	
	$sql ="UPDATE `hesprrs_questionnarie_category` SET `name`='".$name."' WHERE `sno_questionnarie_category` ='".$sno_questionnarie_category."'";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>