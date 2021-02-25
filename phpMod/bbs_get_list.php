<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$spindle = $_POST["spindle"];
include('fig.php');
if($token_time==0){
	// 給主軸列活動
	$sql ="SELECT
	`hesprrs_option_projects`.`sno_option_projects`,
	`hesprrs_option_projects`.`name`,
	`hesprrs_option_projects`.`updated_at`,
	`hesprrs_option_projects`.`activate_date`,
	`hesprrs_projects_data`.`isfilledresult`,
    `hesprrs_projects_data`.`pushed`
  FROM
	`hesprrs_option_projects`
  LEFT JOIN
	`hesprrs_projects_data` ON `hesprrs_projects_data`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
  LEFT JOIN
	`hesprrs_option_strategies` ON `hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
  LEFT JOIN
	`hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
  LEFT JOIN
	`hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
  WHERE
    `hesprrs_projects_data`.`review_state` = '2' AND
	`hesprrs_option_main`.`sno_option_main` = '".$spindle."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_option_projects']=$row['sno_option_projects'];
		$out1['name']=$row['name'];
		$out1['updated_at']=$row['updated_at'];
		$out1['activate_date']=$row['activate_date'];
		$out1['isfilledresult']=$row['isfilledresult'];
		$out1['pushed']=$row['pushed'];
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>
