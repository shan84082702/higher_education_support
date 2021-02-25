<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_questionorder = $_POST["sno_questionorder"];
include('fig.php');
if($token_time==0){
	
	$sql ="UPDATE `hesprrs_questionorder` SET `active`= 0 WHERE `sno_questionorder` in (".$sno_questionorder.")";
	$result = $conn->query($sql);
	$sql ="UPDATE `hesprrs_subquestionorder` SET `active`=0 WHERE `sno_questionorder` in (".$sno_questionorder.")";
	$result = $conn->query($sql);
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>