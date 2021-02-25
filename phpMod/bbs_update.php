<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$pushed = $_POST["pushed"];//push=1 上傳,push=0 下架
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