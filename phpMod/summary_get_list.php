<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	// $sql ="SELECT DISTINCT    `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` AS 'sno_edu_indicators_detail', `hesprrs_edu_indicators`.`name` AS 'edu_name',
	// `hesprrs_edu_indicators_sub`.`name` AS 'sub_name',
	// `hesprrs_edu_indicators_detail`.`name` AS 'detail_name'
	// FROM  `hesprrs_option_strategies`
	// LEFT JOIN  `hesprrs_option_strategies_editor` ON `hesprrs_option_strategies_editor`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	// LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members`
	// LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
	// LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	// LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
	// LEFT JOIN `hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	// LEFT JOIN `hesprrs_option_sub_editor` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_sub_editor`.`sno_option_sub`
	// LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	// LEFT JOIN `hesprrs_option_main_editer` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_main_editer`.`sno_option_main`
	// WHERE  `hesprrs_option_strategies`.`activate_yyy`  =   '".$year."'  AND  
	// (`hesprrs_option_main_editer`.`sno_member` = '".$id."' OR `hesprrs_option_sub_editor`.`sno_member` = '".$id."' OR `hesprrs_option_strategies_editor`.`sno_members` = '".$id."' 
	// OR ((SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1' )) ";
	$sql = "SELECT DISTINCT
	`hesprrs_option_main`.`option_main_order`,`hesprrs_option_sub`.`option_sub_order`,`hesprrs_option_strategies`.`option_strategies_order`,
	`hesprrs_option_strategies`.`sno_option_strategies` ,
	concat(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`) as 'order',
	`hesprrs_option_strategies`.`name` AS 'strategies_name',
	`hesprrs_members`.`name` AS 'supervior_name'
	FROM `hesprrs_option_strategies`
	LEFT JOIN `hesprrs_option_strategies_editor` ON `hesprrs_option_strategies_editor`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members`
	LEFT JOIN	`hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN	`hesprrs_option_sub_editor` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_sub_editor`.`sno_option_sub`
	LEFT JOIN	`hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	LEFT JOIN	`hesprrs_option_main_editer` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_main_editer`.`sno_option_main`
	WHERE	`hesprrs_option_strategies`.`activate_yyy` = '".$year."' 
	AND (`hesprrs_option_main_editer`.`sno_member` = '".$id."' OR `hesprrs_option_sub_editor`.`sno_member` = '".$id."' OR `hesprrs_option_strategies_editor`.`sno_members` = '".$id."' 
		OR ((SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1' ))
	ORDER BY `hesprrs_option_main`.`option_main_order`,`hesprrs_option_sub`.`option_sub_order`,`hesprrs_option_strategies`.`option_strategies_order` ASC";

	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_option_strategies']=$row['sno_option_strategies'];
		$out1['order']=$row['order'];
		$out1['strategies_name']=$row['strategies_name'];
		$out1['supervior_name']=$row['supervior_name'];
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>