<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$sno_option_strategies = $_POST["sno_option_strategies"];
include('fig.php');
if($token_time==0){

	$sql ="SELECT DISTINCT
	`hesprrs_option_projects`.`sno_option_projects` AS 'p_id',
	`hesprrs_option_projects`.`name` AS 'p_name',
	`hesprrs_option_projects`.`updated_at` AS 'update',
	( CASE WHEN `hesprrs_projects_data`.`review_state` IS NULL THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 0 THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 1 THEN '審核中' WHEN `hesprrs_projects_data`.`review_state` = 2 THEN '審核完成' WHEN `hesprrs_projects_data`.`review_state` = 3 THEN '審核未通過' ELSE `hesprrs_projects_data`.`review_state` END) AS 'type',
	( CASE `hesprrs_projects_data`.`isfilledresult` WHEN TRUE THEN '是' ELSE '否' END ) AS 'isfilled',
	`hesprrs_option_projects`.`activate_date` AS 'limted_date'
  	FROM `hesprrs_projects_summary`
  	LEFT JOIN `hesprrs_option_projects` ON `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_projects_summary`.`sno_project`
  	LEFT JOIN `hesprrs_projects_data` ON `hesprrs_projects_data`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
  	WHERE	`hesprrs_projects_summary`.`sno_option_strategies` = '".$sno_option_strategies."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['p_id']=$row['p_id'];
		$out1['p_name']=$row['p_name'];
		$out1['update']=$row['update'];
		$out1['type']=$row['type'];
		$out1['isfilled']=$row['isfilled'];
		$out1['limted_date']=$row['limted_date'];

		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>
