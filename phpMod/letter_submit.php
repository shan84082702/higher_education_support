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
	
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$supervisor_strategies=$row['supervisor_strategies'];
	
	$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) VALUES ('審核中' ,'','".$id."','".$supervisor_strategies."', '".$sno_option_projects."')";
	$resultt = $conn->query($sqll) ;
	$sqll ="UPDATE `hesprrs_projects_data` SET `review_state`= '1' WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sqll) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>