<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$sno_option_strategies = $_POST["sno_option_strategies"];
// @$sno_option_strategies = $_POST["sno_option_strategies"]; //給策略
include('fig.php');
if($token_time==0){
	// $out=array();
	// $sql ="SELECT `hesprrs_edu_indicators`.`name` AS 'edu_name', `hesprrs_edu_indicators_sub`.`name` AS 'sub_name', `hesprrs_edu_indicators_detail`.`name` AS 'detail_name' 
	// FROM `hesprrs_edu_indicators_detail` LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` = `hesprrs_edu_indicators_detail`.`edu_indicators_sub` 
	// LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators`.`sno_edu_indicators` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators` 
	// WHERE `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` = '".$sno_edu_indicators_detail."'";
	// $result = $conn->query($sql) ;
	
	// $row = $result->fetch_array(MYSQLI_ASSOC);
	// $out['edu_class_name']=$row['edu_name'];//指標類型
	// $out['edu_detail']=$row['sub_name'];//指標說明
	// $out['edu_opt_name']=$row['detail_name'];//指標項目
	
	// //給指標 列策略
	// $out['strategy']=array();
	// $sql ="SELECT CONCAT( `hesprrs_option_main`.`option_main_order`, '-', `hesprrs_option_sub`.`option_sub_order`, '-', `hesprrs_option_strategies`.`option_strategies_order` ) AS 'order', 
	// `hesprrs_option_strategies`.`name` FROM `hesprrs_option_strategies` LEFT JOIN `hesprrs_option_sub` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_strategies`.`sno_option_sub` 
	// LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main` 
	// WHERE `hesprrs_option_strategies`.`sno_edu_indicators_detail` = '".$sno_edu_indicators_detail."'";
	// $result = $conn->query($sql) ;
	// while($row = $result->fetch_array(MYSQLI_ASSOC)){
	// 	$out2['order']=$row['order'];
	// 	$out2['name']=$row['name'];
	// 	array_push($out['strategy'],$out2);
	// 	unset($out2);
	// }
	
	// //給指標 列出管考
	// $sql ="SELECT `hesprrs_edu_management`.`sno_edu_management`, `hesprrs_edu_management`.`name`, `hesprrs_edu_management`.`edu_indicators_detail_rule` 
	// FROM `hesprrs_edu_management` 
	// WHERE `hesprrs_edu_management`.`sno_edu_indicator_detail` = '".$sno_edu_indicators_detail."'";
	// $result = $conn->query($sql) ;
	// $out['management']=array();
	// while($row = $result->fetch_array(MYSQLI_ASSOC)){
	// 	$out3['sno_edu_management']=$row['sno_edu_management'];
	// 	$out3['name']=$row['name'];
	// 	$out3['edu_indicators_detail_rule']=$row['edu_indicators_detail_rule'];
		
	// 	//每個館考裡面的回答題目
	// 	$sqll ="SELECT `hesprrs_option_projects`.`name`, `hesprrs_projects_summary`.`question`, `hesprrs_projects_summary`.`results`, `hesprrs_projects_summary`.`type` 
	// 	FROM `hesprrs_projects_summary` INNER JOIN `hesprrs_option_projects` on `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_projects_summary`.`sno_project` 
	// 	WHERE `hesprrs_projects_summary`.`sno_edu_management` = '".$out3['sno_edu_management']."'";
	// 	$resultt = $conn->query($sqll) ;
	// 	$out3['question']=array();
	// 	while($roww = $resultt->fetch_array(MYSQLI_ASSOC)){
	// 		$out4['name']=$roww['name'];
	// 		$out4['question']=$roww['question'];
	// 		$out4['results']=$roww['results'];
	// 		$out4['type']=$roww['type'];
	// 		array_push($out3['question'],$out4);
	// 		unset($out4);
	// 	}
		
	// 	array_push($out['management'],$out3);
	// 	unset($out3);
	// }

	// 20190808
	// 策略資料
    $out=array();
    $out['strategies_detail']=array();
    $out['management_detail']=array();
	$sql = "SELECT 
	`hesprrs_option_strategies`.`sno_option_strategies`, 
	CONCAT( `hesprrs_option_main`.`option_main_order`, '-', `hesprrs_option_sub`.`option_sub_order`, '-', `hesprrs_option_strategies`.`option_strategies_order` ) AS 'order', 
	`hesprrs_option_strategies`.`name` AS 'strategies_name', 
	`hesprrs_option_strategies`.`aims`,
	`hesprrs_members`.`name` AS 'supervior_name' 
	FROM `hesprrs_option_strategies` 
	LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members` 
	LEFT JOIN `hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub` 
	LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main` 
	WHERE `hesprrs_option_strategies`.`sno_option_strategies` = '".$sno_option_strategies."'";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_option_strategies']=$row['sno_option_strategies'];
		$out1['order']=$row['order'];
		$out1['strategies_name']=$row['strategies_name'];
		$out1['aims']=$row['aims'];
		$out1['supervior_name']=$row['supervior_name'];
		array_push($out['strategies_detail'],$out1);
		unset($out1);
	}
	// 列一堆管考
	$sql = "SELECT
	`hesprrs_option_strategies_edu`.`sno_edu_management`,
	`hesprrs_edu_indicators`.`name` AS 'indicator_name',
	`hesprrs_edu_indicators_sub`.`name`AS 'sub_name',
	`hesprrs_edu_indicators_detail`.`name` AS 'detail_name',
	`hesprrs_edu_management`.`name` AS 'edu_name'
	FROM `hesprrs_option_strategies_edu`
	LEFT JOIN `hesprrs_edu_management` ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_option_strategies_edu`.`sno_edu_management`
	LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` = `hesprrs_edu_management`.`sno_edu_indicator_detail`
	LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` = `hesprrs_edu_indicators_detail`.`edu_indicators_sub`
	LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators`.`sno_edu_indicators` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators`
	WHERE `hesprrs_option_strategies_edu`.`sno_option_strategies` = '".$sno_option_strategies."'";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out2['sno_edu_management']=$row['sno_edu_management'];
		$out2['indicator_name']=$row['indicator_name'];
		$out2['sub_name']=$row['sub_name'];
		$out2['detail_name']=$row['detail_name'];
        $out2['edu_name']=$row['edu_name'];
        $out2['answer']=array();
		// foreach策略規定的題目 列出活動回答
		$sqll = "SELECT
		`hesprrs_option_projects`.`name`,
		`hesprrs_projects_summary`.`question`,
		`hesprrs_projects_summary`.`results`,
		`hesprrs_projects_summary`.`type`
	  	FROM `hesprrs_option_projects`
	  	LEFT JOIN `hesprrs_projects_summary` ON `hesprrs_projects_summary`.`sno_project` = `hesprrs_option_projects`.`sno_option_projects`
	  	WHERE `hesprrs_projects_summary`.`sno_edu_management` = '".$row['sno_edu_management']."' 
		AND `hesprrs_option_projects`.`sno_option_strategies` = '".$sno_option_strategies."'";
		$resultt = $conn->query($sqll) ;
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$out3['name']=$row['name'];
			$out3['question']=$row['question'];
			$out3['results']=$row['results'];
			$out3['type']=$row['type'];
			array_push($out2['answer'],$out3);
			unset($out3);
		}
		array_push($out['management_detail'],$out2);
		unset($out2);
	}

	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>
