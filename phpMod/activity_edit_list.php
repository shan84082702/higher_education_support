<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
@$sno_option_projects = $_POST["sno_option_projects"];
include('fig.php');
if($token_time==0){
	//sql kcamo edit 2018-12-07//
	$sql ="SELECT `hesprrs_option_projects`.`sno_option_projects` as 'project_pk',`hesprrs_option_projects`.`activate_yyy` as 'year',
    `hesprrs_option_main`.`sno_option_main` as 'main_id',
    `hesprrs_option_sub`.`sno_option_sub` as 'sub_id',
    `hesprrs_option_strategies`.`sno_option_strategies` as 'strategies_id',
	`hesprrs_option_projects`.`name` as name,`hesprrs_option_projects`.`activate_date` as 'act_date'
	,`hesprrs_members`.`sno_members` as 'supervisor',`hesprrs_members`.`sno_roles` as 'role_id'
	FROM `hesprrs_option_projects` 
	LEFT JOIN `hesprrs_option_strategies`
	ON `hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	LEFT JOIN `hesprrs_option_sub`
	ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN `hesprrs_option_main`
	ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	LEFT JOIN `hesprrs_members`
	ON `hesprrs_option_projects`.`supervisor_project` = `hesprrs_members`.`sno_members`
	WHERE `hesprrs_option_projects`.`activate_yyy` ='".$year."' and `sno_option_projects` ='".$sno_option_projects."'";
	$result = $conn->query($sql);
	$out=array();
	//data kcamo edit 2018-12-07 增加主軸分項策略ID//
	//data kcamo edit 2018-12-08 增加負責人ID 腳色ID//
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$out1['project_pk']=$row['project_pk'];
	$out1['year']=$row['year'];
	$out1['main_id']=$row['main_id'];
	$out1['sub_id']=$row['sub_id'];
	$out1['strategies_id']=$row['strategies_id'];
	$out1['name']=$row['name'];
	//$out1['edu']=$row['edu'];
	$out1['act_date']=$row['act_date'];
	$out1['supervisor']=$row['supervisor'];
	$out1['role_id']=$row['role_id'];
	
	//sql kcamo edit 2018-12-07 修改可編輯人員//
	$sqll ="SELECT `sno_option_projects`,`hesprrs_members`.`sno_members` as 'member_id' ,`hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id'
	FROM `hesprrs_projects_editor` LEFT JOIN `hesprrs_members` ON
	`hesprrs_projects_editor`.`sno_members` = `hesprrs_members`.`sno_members`
	WHERE `hesprrs_projects_editor`.`sno_option_projects` ='".$out1['project_pk']."' and `hesprrs_members`.`sno_members` != '". $out1['supervisor']."' and `hesprrs_members`.`sno_roles` !=1";
	$resultt = $conn->query($sqll) ;
	$sec_out=array();
	while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
		$sec_out1['sno_option_projects']=$row['sno_option_projects'];
		$sec_out1['member_id']=$row['member_id'];
		$sec_out1['name']=$row['name'];
		$sec_out1['role_id']=$row['role_id'];
		array_push($sec_out,$sec_out1);
		unset($sec_out1);
	}
	$out1['edit_name']=$sec_out;
	
	
	//指定回答題目
	$third_out=array();
	
	$sqll="SELECT
		`hesprrs_projects_summary`.`sno_project_summary`,
		`hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`,`hesprrs_edu_indicators_sub`.`name` AS 'sub_name',
		`hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`,`hesprrs_edu_indicators_detail`.`name` AS 'detail_name',
		`hesprrs_edu_indicators`.`sno_edu_indicators`,`hesprrs_edu_indicators`.`name` AS 'edu_name',
		`hesprrs_projects_summary`.`sno_edu_management`,`hesprrs_edu_management`.`name`,`hesprrs_projects_summary`.`question`,`hesprrs_projects_summary`.`type`
		FROM `hesprrs_projects_summary`
		LEFT JOIN`hesprrs_edu_management` ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_projects_summary`.`sno_edu_management`
		LEFT JOIN`hesprrs_edu_indicators_detail` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` = `hesprrs_edu_management`.`sno_edu_indicator_detail`
		LEFT JOIN`hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` = `hesprrs_edu_indicators_detail`.`edu_indicators_sub`
		LEFT JOIN`hesprrs_edu_indicators` ON `hesprrs_edu_indicators`.`sno_edu_indicators` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators`
		WHERE`sno_project` = '".$out1['project_pk']."'";
	$resultt = $conn->query($sqll);
	while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
		$third_out1['sno_project_summary']=$row['sno_project_summary'];
		$third_out1['sno_edu_indicators_sub']=$row['sno_edu_indicators_sub'];
		$third_out1['sub_name']=$row['sub_name'];
		$third_out1['sno_edu_indicators_detail']=$row['sno_edu_indicators_detail'];
		$third_out1['detail_name']=$row['detail_name'];
		$third_out1['sno_edu_indicators']=$row['sno_edu_indicators'];
		$third_out1['edu_name']=$row['edu_name'];
		$third_out1['sno_edu_management']=$row['sno_edu_management'];
		$third_out1['name']=$row['name'];
		$third_out1['question']=$row['question'];
		$third_out1['type']=$row['type'];
		array_push($third_out,$third_out1);
		unset($third_out1);
	}
	$out1['topic']=$third_out;
	
	
	//審核流程人員
	$fourth_out=array();
	$flag=0;
	$sqll="SELECT `sno_flow`, `sno_option_projects`, `flow_number`, `hesprrs_flow`.`sno_members` as sno_members, `flow_status`,`hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id' FROM `hesprrs_flow` LEFT JOIN `hesprrs_members` ON
	`hesprrs_flow`.`sno_members` = `hesprrs_members`.`sno_members` WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$resultt = $conn->query($sqll);
	while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
		$flag=1;
		$fourth_out1['sno_flow']=$row['sno_flow'];
		$fourth_out1['flow_number']=$row['flow_number'];
		$fourth_out1['role_id']=$row['role_id'];
		$fourth_out1['sno_members']=$row['sno_members'];
		$fourth_out1['name']=$row['name'];
		$fourth_out1['flow_status']=$row['flow_status'];
		array_push($fourth_out,$fourth_out1);
		unset($fourth_out1);
	}
	if($flag==0){
		$fourth_out1['flow_number']=1;
		$fourth_out1['role_id']=2;
		$fourth_out1['sno_members']=24;
		$fourth_out1['flow_status']=0;
		array_push($fourth_out,$fourth_out1);
	}
		
	$out1['flow']=$fourth_out;
	
	//edit要可以編輯
	$sqll = "SELECT IF( 
		'0' = (SELECT `hesprrs_projects_data`.`review_state` FROM `hesprrs_projects_data` WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."') OR
		'管理員' = (SELECT `hesprrs_roles`.`name` FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles` WHERE `sno_members` = '".$id."') 
					 , 1, 0) AS 'edit'";
	$resultt = $conn->query($sqll);
	$row = $resultt->fetch_array(MYSQLI_ASSOC);
	$out1['edit']=$row['edit'];
	
	
	array_push($out,$out1);
	unset($out1);

	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>