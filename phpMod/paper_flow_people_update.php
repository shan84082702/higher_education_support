<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$flow_number = $_POST["flow_number"];//文件在誰身上
@$sno_members = $_POST["sno_members"];
@$flow_status = $_POST["flow_status"];
include('fig.php');
if($token_time==0){
	
	
	//審核人員
	//先刪除全部
	$sql ="DELETE FROM `hesprrs_flow` WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sql) ;
	//再新增全部人員順序
	$num=count($sno_members);//審核人員
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_flow`(`sno_option_projects`, `flow_number`, `sno_members`, `flow_status`) VALUES ('".$sno_option_projects."','".$flow_number[$i]."','".$sno_members[$i]."','".$flow_status[$i]."')";
		$conn->query($sql);
	}
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>