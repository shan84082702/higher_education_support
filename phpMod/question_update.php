<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$results = $_POST["result"];
@$sno_questionnaires_results = $_POST["sno_questionnaires_results"];
include('fig.php');
if($token_time==0){
	
	$num=count($results);
	for($i=0;$i<$num;$i++){
		$results[$i]=addslashes($results[$i]);
		$sql ="UPDATE `hesprrs_questionnaires_results` SET `result`='".$results[$i]."' WHERE `sno_questionnaires_results`='".$sno_questionnaires_results[$i]."'";
		$conn->query($sql);
	}
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>