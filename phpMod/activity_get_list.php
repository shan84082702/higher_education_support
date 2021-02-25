<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `hesprrs_option_projects`.`sno_option_projects` as 'project_pk',`hesprrs_option_projects`.`activate_yyy` as 'year',
	concat(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`) as 'order',
	`hesprrs_option_projects`.`name` as name,concat(`hesprrs_edu_indicators`.`name`,'/',`hesprrs_edu_indicators_sub`.`name`,'/',`hesprrs_edu_indicators_detail`.`name`) as 'edu',`hesprrs_option_projects`.`activate_date` as 'act_date'
	,`hesprrs_members`.`name` as 'supervisor'
	FROM `hesprrs_option_projects` 
	LEFT JOIN `hesprrs_option_strategies`
	ON `hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	LEFT JOIN `hesprrs_option_sub`
	ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN `hesprrs_option_main`
	ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	LEFT JOIN `hesprrs_edu_indicators_detail`
	ON `hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
	LEFT JOIN `hesprrs_edu_indicators`
	ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	LEFT JOIN `hesprrs_edu_indicators_sub`
	ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
	LEFT JOIN `hesprrs_members`
	ON `hesprrs_option_projects`.`supervisor_project` = `hesprrs_members`.`sno_members`
	WHERE `hesprrs_option_projects`.`activate_yyy` ='".$year."' ";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$sqll ="SELECT
			IF(
				EXISTS(
				SELECT
				`hesprrs_option_projects`.`sno_option_projects`
				FROM
				`hesprrs_option_projects`
				LEFT JOIN
				`hesprrs_projects_editor` ON `hesprrs_projects_editor`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
				LEFT JOIN
				`hesprrs_option_strategies` ON `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_projects`.`sno_option_strategies`
				LEFT JOIN
				`hesprrs_option_strategies_editor` ON `hesprrs_option_strategies_editor`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
				LEFT JOIN
				`hesprrs_option_sub` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_strategies`.`sno_option_sub`
				LEFT JOIN
				`hesprrs_option_sub_editor` ON `hesprrs_option_sub_editor`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
				LEFT JOIN
				`hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main`
				LEFT JOIN
				`hesprrs_option_main_editer` ON `hesprrs_option_main_editer`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
				WHERE
				`hesprrs_option_projects`.`sno_option_projects` = '".$row['project_pk']."' AND(
				`hesprrs_option_projects`.`supervisor_project` = '".$id."' OR 
					`hesprrs_projects_editor`.`sno_members` = '".$id."' OR 
					`hesprrs_option_strategies_editor`.`sno_members` = '".$id."' OR 
					`hesprrs_option_sub_editor`.`sno_member` = '".$id."' OR 
					`hesprrs_option_main_editer`.`sno_member` = '".$id."' OR
					('管理員' = (SELECT `hesprrs_roles`.`name` FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles` WHERE `sno_members` = '".$id."'))
				)
			),1,0) AS 'editor'";
		$resultt = $conn->query($sqll) ;
		if($roww = $resultt->fetch_array(MYSQLI_ASSOC)){
			
			if (! $roww['editor'])
				continue;
		}

		$out1['project_pk']=$row['project_pk'];
		$out1['year']=$row['year'];
		$out1['order']=$row['order'];
		$out1['name']=$row['name'];
		$out1['edu']=$row['edu'];
		$out1['act_date']=$row['act_date'];
		$out1['supervisor']=$row['supervisor'];
		
		$sqll ="SELECT `sno_option_projects`,`hesprrs_members`.`sno_members` as 'member_id' ,`hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id'
		FROM `hesprrs_projects_editor` LEFT JOIN `hesprrs_members` ON
		`hesprrs_projects_editor`.`sno_members` = `hesprrs_members`.`sno_members`
		WHERE `hesprrs_projects_editor`.`sno_option_projects` ='".$out1['project_pk']."' and `hesprrs_members`.`sno_members` !='".$out1['supervisor']."'  and `hesprrs_members`.`sno_roles` !=1";
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
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>