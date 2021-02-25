<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
@$sno_option_strategies = $_POST["sno_option_strategies"];
include('fig.php');
if($token_time==0){
	
	//sql kcamo edit 2018-12-07//
	/*$sql ="SELECT `hesprrs_option_strategies`.`sno_option_strategies` as 'strategies_pk',`hesprrs_option_main`.`sno_option_main` as 'main_id',`hesprrs_option_sub`.`sno_option_sub` as 'sub_id',
	`hesprrs_option_strategies`.`option_strategies_order` as 'strategies_order',`hesprrs_option_strategies`.`name` as 'name',
	`hesprrs_option_strategies`.`aims` as 'aim',
	`hesprrs_edu_indicators_detail`.`sno_edu_indicators` as 'edu_id' , `hesprrs_edu_indicators_detail`.`edu_indicators_sub` as 'edu_sub_id' ,`hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` as 'detail_id',
	`hesprrs_members`.`sno_members` as 'supervisor_id',`hesprrs_members`.`name` as 'supervisor',`hesprrs_members`.`sno_roles` as 'role_id'
	FROM `hesprrs_option_strategies` 
	LEFT JOIN `hesprrs_option_sub` ON
	`hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN `hesprrs_option_main` ON
	`hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	LEFT JOIN `hesprrs_edu_indicators_detail` ON
	`hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
	LEFT JOIN `hesprrs_edu_indicators` ON
	`hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	LEFT JOIN `hesprrs_edu_indicators_sub` ON
	`hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
	LEFT JOIN `hesprrs_members` ON
	`hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members`
	WHERE`hesprrs_option_main`.`activate_yyy` ='".$year."' and `hesprrs_option_strategies`.`sno_option_strategies` ='".$sno_option_strategies."'";*/
	// 2019/08/01 kai
	$sql ="SELECT
	`hesprrs_option_strategies`.`sno_option_strategies` AS 'strategies_pk',
	`hesprrs_option_main`.`sno_option_main` AS 'main_id',
	`hesprrs_option_sub`.`sno_option_sub` AS 'sub_id',
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
	WHERE `hesprrs_option_main`.`activate_yyy` = '".$year."' AND `hesprrs_option_strategies`.`sno_option_strategies` ='".$sno_option_strategies."'";
	$result = $conn->query($sql) ;
	$out=array();
	//data kcamo edit 2018-12-07//
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['strategies_pk']=$row['strategies_pk'];
		$out1['main_id']=$row['main_id'];
		$out1['sub_id']=$row['sub_id'];
		$out1['strategies_order']=$row['strategies_order'];
		$out1['name']=$row['name'];
		$out1['aim']=$row['aim'];
		/**///$out1['edu_id']=$row['edu_id'];
		/**///$out1['edu_sub_id']=$row['edu_sub_id'];
		/**///$out1['detail_id']=$row['detail_id'];
		$out1['supervisor_id']=$row['supervisor_id'];
		$out1['supervisor']=$row['supervisor'];
		$out1['role_id']=$row['role_id'];
		
		$sqll ="SELECT `sno_option_strategies`, `hesprrs_members`.`sno_members` as 'member_id' ,`hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id'
		FROM `hesprrs_option_strategies_editor` 
		LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies_editor`.`sno_members` = `hesprrs_members`.`sno_members`
		WHERE `hesprrs_option_strategies_editor`.`sno_option_strategies` = '".$out1['strategies_pk']."'
		And `hesprrs_members`.`sno_members` != '".$out1['supervisor_id']."' and `hesprrs_members`.`sno_roles`!=1";
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

		// 2019/08/01 kai
		$sqll ="SELECT
		`hesprrs_option_strategies_edu`.`sno_option_strategies_edu`,
		`hesprrs_edu_indicators`.`sno_edu_indicators`,
		`hesprrs_edu_indicators`.`name` AS 'indicator_name',
		`hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`,
		`hesprrs_edu_indicators_sub`.`name` AS 'sub_name',
		`hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`,
		`hesprrs_edu_indicators_detail`.`name` AS 'detail_name',
		`hesprrs_edu_management`.`sno_edu_management`,
		`hesprrs_edu_management`.`name`
		FROM `hesprrs_option_strategies_edu`
		LEFT JOIN `hesprrs_edu_management` ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_option_strategies_edu`.`sno_edu_management`
		LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` = `hesprrs_edu_management`.`sno_edu_indicator_detail`
		LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` = `hesprrs_edu_indicators_detail`.`edu_indicators_sub`
		LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators`.`sno_edu_indicators` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators`
		WHERE `hesprrs_option_strategies_edu`.`sno_option_strategies` = '".$out1['strategies_pk']."'";
		$resultt = $conn->query($sqll) ;
		$third_out=array();
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$third_out1['sno_option_strategies_edu']=$row['sno_option_strategies_edu'];
			$third_out1['sno_edu_indicators']=$row['sno_edu_indicators'];
			$third_out1['indicator_name']=$row['indicator_name'];
			$third_out1['sno_edu_indicators_sub']=$row['sno_edu_indicators_sub'];
			$third_out1['sub_name']=$row['sub_name'];
			$third_out1['sno_edu_indicators_detail']=$row['sno_edu_indicators_detail'];
			$third_out1['detail_name']=$row['detail_name'];
			$third_out1['sno_edu_management']=$row['sno_edu_management'];
			$third_out1['name']=$row['name'];
			array_push($third_out,$third_out1);
			unset($third_out1);
		}
		$out1['edu_management']=$third_out;
		
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>