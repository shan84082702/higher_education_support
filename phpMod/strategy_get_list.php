<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	/*
	$sql ="SELECT `hesprrs_option_strategies`.`sno_option_strategies` as 'strategies_pk',CONCAT(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`) as 'main_sub_order',
	`hesprrs_option_strategies`.`option_strategies_order` as 'strategies_order',`hesprrs_option_strategies`.`name` as 'name',
	`hesprrs_option_strategies`.`aims` as 'aim', `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` as 'indicators_detail_id',CONCAT(`hesprrs_edu_indicators`.`name`,'/',`hesprrs_edu_indicators_sub`.`name`,'/',`hesprrs_edu_indicators_detail`.`name`) as 'edu',
	`hesprrs_members`.`sno_members` as 'supervisor_id',`hesprrs_members`.`name` as 'supervisor',`hesprrs_members`.`sno_roles` as 'role_id'
	FROM `hesprrs_option_strategies` 
	LEFT JOIN `hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
	LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
	LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members`
	WHERE `hesprrs_option_main`.`activate_yyy` ='".$year."'";*/
	// 2019/08/01 kai
	$sql ="SELECT
	`hesprrs_option_strategies`.`sno_option_strategies` AS 'strategies_pk',
	CONCAT(`hesprrs_option_main`.`option_main_order`, '-', `hesprrs_option_sub`.`option_sub_order`) AS 'main_sub_order',
	`hesprrs_option_strategies`.`option_strategies_order` AS 'strategies_order',
	`hesprrs_option_strategies`.`name` AS 'name',
	`hesprrs_option_strategies`.`aims` AS 'aim',
	`hesprrs_members`.`sno_members` AS 'supervisor_id',
	`hesprrs_members`.`name` AS 'supervisor',
	`hesprrs_members`.`sno_roles` AS 'role_id'
	FROM `hesprrs_option_strategies`
	LEFT JOIN `hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members`
	WHERE `hesprrs_option_main`.`activate_yyy` = '".$year."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['strategies_pk']=$row['strategies_pk'];
		$out1['main_sub_order']=$row['main_sub_order'];
		$out1['strategies_order']=$row['strategies_order'];
		$out1['name']=$row['name'];
		$out1['aim']=$row['aim'];
		/*2019/08/01 should be deleted*/ //$out1['indicators_detail_id']=$row['indicators_detail_id'];
		/*2019/08/01 should be deleted*/ //$out1['edu']=$row['edu'];
		$out1['supervisor_id']=$row['supervisor_id'];
		$out1['supervisor']=$row['supervisor'];
		$out1['role_id']=$row['role_id'];
		
		$sqll ="SELECT `sno_option_strategies`, `hesprrs_members`.`sno_members` as 'member_id' ,`hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id'
		FROM `hesprrs_option_strategies_editor` 
		LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies_editor`.`sno_members` = `hesprrs_members`.`sno_members`
		WHERE `hesprrs_option_strategies_editor`.`sno_option_strategies` = '".$out1['strategies_pk']."'
		And `hesprrs_members`.`sno_members` != '".$out1['supervisor_id']."' and `hesprrs_members`.`sno_roles` != 1";
		$resultt = $conn->query($sqll) ;
		$sec_out=array();
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$sec_out1['sno_option_strategies']=$row['sno_option_strategies'];
			$sec_out1['member_id']=$row['member_id'];
			$sec_out1['name']=$row['name'];
			$sec_out1['role_id']=$row['role_id'];
			array_push($sec_out,$sec_out1);
			unset($sec_out1);
		}
		$out1['edit_name']=$sec_out;
		/*$sqlll ="SELECT `hesprrs_members`.`sno_members` 
		FROM `hesprrs_option_strategies`
		LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
		LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators`.`sno_edu_indicators` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators`
		LEFT JOIN `hesprrs_edu_management` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` = `hesprrs_edu_management`.`sno_edu_indicator_detail`
		LEFT JOIN `hesprrs_strategies_summary` ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_strategies_summary`.`sno_edu_management`
        AND `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_strategies_summary`.`sno_option_strategies`
		LEFT JOIN `hesprrs_members` ON `hesprrs_strategies_summary`.`sno_members` = `hesprrs_members`.`sno_members`
		WHERE `hesprrs_option_strategies`.`sno_option_strategies` = '".$out1['strategies_pk']."'";
		$resulttt = $conn->query($sqlll);
		$out1['set']=1;
		$t=0;
		while($row = $resulttt->fetch_array(MYSQLI_ASSOC)){
			$t=1;
			if($row['sno_members']==null)
				$out1['set']=0;
		}
		if($t==0){
			$out1['set']=0;
		}*/
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>