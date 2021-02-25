<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `hesprrs_option_strategies`.`supervisor_strategies` FROM `hesprrs_option_projects` 
	LEFT JOIN `hesprrs_option_strategies` ON
	`hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	WHERE `sno_option_projects` ='".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	$out=array();
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if($row['supervisor_strategies']==$id)
		$issupervisor=1;
	else
		$issupervisor=0;
		
		
	$sqll ="SELECT `hesprrs_messages`.`created_at`,sou.`name` as 'source' ,   tar.`name` as 'target',`hesprrs_messages`.`message` as 'exmsg',`hesprrs_option_projects`.`name` as 'projects_name',(CASE  WHEN `hesprrs_messages`.`type` IS NULL THEN '未審核' ELSE `hesprrs_messages`.`type` END) as 'type'
	FROM `hesprrs_messages` 
	LEFT JOIN `hesprrs_members` as sou ON `hesprrs_messages`.`sno_source` = sou.`sno_members`
	LEFT JOIN `hesprrs_members` as tar ON `hesprrs_messages`.`sno_target` = tar.`sno_members`
    LEFT JOIN `hesprrs_option_projects` ON `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_messages`.`sno_projects`
	WHERE `hesprrs_messages`.`sno_projects` = '".$sno_option_projects."' order by `hesprrs_messages`.`created_at` desc";
	$resultt = $conn->query($sqll) ;
	$out=array();
	while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
		$out1['created_at']=$row['created_at'];
		$out1['source']=$row['source'];
		$out1['target']=$row['target'];
		$out1['projects_name']=$row['projects_name'];
		$out1['exmsg']=$row['exmsg'];
		$out1['type']=$row['type'];
		array_push($out,$out1);
		unset($out1);
	}
	
	
	echo json_encode(array(
		'msg' =>'200',
		'issupervisor'=>$issupervisor,
		'out' => $out
	));
}
?>