<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$name = addslashes($_POST["name"]);
@$sno_option_strategies = $_POST["sno_option_strategies"];
@$activate_yyy = $_POST["activate_yyy"];
@$activate_date = $_POST["activate_date"];
@$supervisor_project = $_POST["supervisor_project"];
@$sno_member = $_POST["sno_member"];
@$sno_edu_management = $_POST["sno_edu_management"];//所屬的管考欄位流水號
@$question = $_POST["question"];//回答
@$sno_roles = $_POST["sno_roles"];//腳色
@$type = $_POST["type"];//類型
@$sno_members = $_POST["sno_members"];//審核人員

include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_option_projects`(`name`, `sno_option_strategies`, `activate_yyy`, `activate_date`, `supervisor_project`) 
	VALUES('".$name."', '".$sno_option_strategies."', '".$activate_yyy."', '".$activate_date."', '".$supervisor_project."');
	SELECT LAST_INSERT_ID() as 'project_pk';";
	
	$conn->multi_query($sql);
	$conn->store_result();
	$conn->next_result();
	$result=$conn->store_result();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$project_pk=$row['project_pk'];
	}
	$sql ="INSERT INTO `hesprrs_projects_data`(`sno_option_projects`) 
	VALUES ('".$project_pk."')";
	$conn->query($sql);
	
	$sql ="INSERT INTO `hesprrs_projects_editor`(`sno_option_projects`, `sno_members`) 
	VALUES ('".$project_pk."', '".$supervisor_project."')";
	$conn->query($sql);
	
	$num=count($sno_member);//可編輯人員
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_projects_editor`(`sno_option_projects`, `sno_members`) 
		VALUES ('".$project_pk."', '".$sno_member[$i]."')";
		$conn->query($sql);
	}
	//審核人員順序
	$num=count($sno_members);//審核人員
	for($i=0;$i<$num;$i++){
		$flow_number=$i+1;
		$sql ="INSERT INTO `hesprrs_flow`(`sno_option_projects`, `flow_number`, `sno_members`, `flow_status`) VALUES ('".$project_pk."','".$flow_number."','".$sno_members[$i]."','0')";
		$conn->query($sql);
	}
	//將報告書顯示狀態0
	$sql ="UPDATE hesprrs_projects_data SET review_state = '0'
	WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$project_pk."'";
	$conn->query($sql);
	
	
	//指定回答題目
	$num=count($sno_edu_management);
	for($i=0;$i<$num;$i++){
		$question[$i]=addslashes($question[$i]);
		$sql ="INSERT INTO `hesprrs_projects_summary`(`sno_edu_management`,  `question`, `sno_project`, `sno_option_strategies`, `type`) 
		VALUES ('".$sno_edu_management[$i]."', '".$question[$i]."', '".$project_pk."', '".$sno_option_strategies."', '".$type[$i]."');";
		$conn->query($sql);
	}
	
	
	/*$num=count($sno_project_summary);
	for($i=0;$i<$num;$i++){
		$sql ="UPDATE `hesprrs_projects_summary` SET `sno_project`='".$project_pk."',`question`='".$question[$i]."'
		WHERE `sno_project_summary`='".$sno_project_summary[$i]."'";
		$conn->query($sql);
		$sql ="UPDATE `hesprrs_strategies_summary` SET `question`='".$question[$i]."'
		WHERE `sno_strategies_summary`='".$sno_project_summary[$i]."'";
		$conn->query($sql);
	}*/
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>