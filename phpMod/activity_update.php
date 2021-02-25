<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$name = addslashes($_POST["name"]);
@$sno_option_strategies = $_POST["sno_option_strategies"];
@$activate_yyy = $_POST["activate_yyy"];
@$activate_date = $_POST["activate_date"];
@$supervisor_project = $_POST["supervisor_project"];
@$sno_member = $_POST["sno_member"];
@$insert_sno_edu_management = $_POST["insert_sno_edu_management"];
@$insert_question = $_POST["insert_question"];
@$insert_sno_option_strategies = $_POST["insert_sno_option_strategies"];
//@$insert_sno_members = $_POST["insert_sno_members"];
@$insert_type = $_POST["insert_type"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$question = $_POST["question"];
@$sno_edu_management = $_POST["sno_edu_management"];
@$sno_members = $_POST["sno_members"];
@$flow_status = $_POST["flow_status"];
@$type = $_POST["type"];
@$sno_project_summary = $_POST["sno_project_summary"];
@$delete_sno_project_summary = $_POST["delete_sno_project_summary"];
include('fig.php');
if($token_time==0){
	
	//審核人員
	//先刪除全部
	$sql ="DELETE FROM `hesprrs_flow` WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sql) ;
	//再新增全部人員順序
	$num=count($sno_members);//審核人員
	for($i=0;$i<$num;$i++){
		$flow_number=$i+1;
		$sql ="INSERT INTO `hesprrs_flow`(`sno_option_projects`, `flow_number`, `sno_members`, `flow_status`) VALUES ('".$sno_option_projects."','".$flow_number."','".$sno_members[$i]."','".$flow_status[$i]."')";
		$conn->query($sql);
	}
	
	
	
	$sql ="UPDATE `hesprrs_option_projects` SET 
	`name`='".$name."',`sno_option_strategies`='".$sno_option_strategies."',`activate_yyy`='".$activate_yyy."',`activate_date`='".$activate_date."',`supervisor_project`='".$supervisor_project."'
	WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sql);
	
	$sql ="DELETE FROM `hesprrs_projects_editor` WHERE `sno_option_projects` =".$sno_option_projects;
	$conn->query($sql);
	
	$sql ="INSERT INTO `hesprrs_projects_editor`(`sno_option_projects`, `sno_members`) 
	VALUES  ('".$sno_option_projects."', '".$supervisor_project."')";
	$conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_projects_editor`(`sno_option_projects`, `sno_members`) 
		VALUES ('".$sno_option_projects."', '".$sno_member[$i]."')";
		$result = $conn->query($sql);
	}
	// 2018 12 10 不用迴圈
	$sql ="UPDATE `hesprrs_projects_summary` SET `sno_project` = 0, `question`=''  WHERE `sno_project` ='".$sno_option_projects."'";
	$conn->query($sql);
	//指定回答題目
	//新增
	$num=count($insert_sno_edu_management);
	for($i=0;$i<$num;$i++){
		$insert_question[$i]=addslashes($insert_question[$i]);
		$sql ="INSERT INTO `hesprrs_projects_summary`(`sno_edu_management`,  `question`, `sno_project`, `sno_option_strategies`, `type`) 
		VALUES ('".$insert_sno_edu_management[$i]."', '".$insert_question[$i]."', '".$sno_option_projects."', '".$sno_option_strategies."', '".$insert_type[$i]."');";
		$conn->query($sql);
	}
	//更新的
	$num=count($sno_project_summary);
	for($i=0;$i<$num;$i++){
		$question[$i]=addslashes($question[$i]);
		$sql ="UPDATE `hesprrs_projects_summary` SET `sno_edu_management`= '".$sno_edu_management[$i]."',`question`='".$question[$i]."',`sno_project`='".$sno_option_projects."',
		`type`='".$type[$i]."' WHERE `sno_project_summary` ='".$sno_project_summary[$i]."'";
		$conn->query($sql);
	}
	//刪除
	$num=count($delete_sno_project_summary);
	for($i=0;$i<$num;$i++){
		$sql ="DELETE FROM `hesprrs_projects_summary` WHERE `hesprrs_projects_summary`.`sno_project_summary` = '".$delete_sno_project_summary[$i]."'";
		$conn->query($sql);
	}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>