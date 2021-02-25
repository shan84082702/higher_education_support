<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_questionnarie_category = $_POST["sno_questionnarie_category"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_questionnarie_category` WHERE `sno_questionnarie_category` in (".$sno_questionnarie_category.")";
	$conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>