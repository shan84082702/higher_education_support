<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$type = $_POST["type"];
@$sno_strategies_summary = $_POST["sno_strategies_summary"];//策略總結流水號
include('fig.php');
if($token_time==0){
	$num=count($sno_strategies_summary);
	for($i=0;$i<$num;$i++){
		$type[$i]=addslashes($type[$i]);
		$sql ="UPDATE `hesprrs_strategies_summary` SET `type` = '".$type[$i]."' WHERE `sno_strategies_summary` = '".$sno_strategies_summary[$i]."';";
		$conn->query($sql) ;
	}
	echo json_encode(array(
		'msg' =>'200'
	));
	/*$sql ="DELETE FROM `hesprrs_strategies_summary` WHERE `sno_option_strategies` ='".$sno_option_strategies."'";
	$conn->query($sql);
	$sql ="DELETE FROM `hesprrs_projects_summary` WHERE `sno_option_strategies` ='".$sno_option_strategies."'";
	$conn->query($sql);
	$num=count($sno_edu_management);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_strategies_summary`(`sno_edu_management`,`question`, `sno_option_strategies`, `sno_members`, `type`) 
		VALUES ('".$sno_edu_management[$i]."','".$question[$i]."', '".$sno_option_strategies."', '".$sno_members[$i]."', '".$type[$i]."');
		SELECT LAST_INSERT_ID() as 'strate_pk'";
		$conn->multi_query($sql);
		$result=$conn->store_result();
		$conn->next_result();
		$result=$conn->store_result();
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$strate_pk=$row['strate_pk'];
		$sqll ="INSERT INTO `hesprrs_projects_summary`(`sno_project_summary`,`sno_edu_management`,`question`, `sno_option_strategies`, `sno_members`) 
		VALUES ('".$strate_pk."','".$sno_edu_management[$i]."','".$question[$i]."' ,'".$sno_option_strategies."', '".$sno_members[$i]."')";
		$conn->query($sqll);
	}*/
}
?>