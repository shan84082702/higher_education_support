<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	// 更新狀態
	$sql ="UPDATE `hesprrs_projects_data` SET `pushed` = '".$pushed."' WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sql) ;
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>