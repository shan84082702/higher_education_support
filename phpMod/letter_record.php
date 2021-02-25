<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$message = addslashes($_POST["message"]);
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `hesprrs_option_strategies`.`supervisor_strategies` FROM `hesprrs_option_projects` 
	LEFT JOIN `hesprrs_option_strategies` ON
	`hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	WHERE `sno_option_projects` ='".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if($row['supervisor_strategies']==$id){
		//取得活動主持人id
		$sqll ="SELECT  `supervisor_project` FROM `hesprrs_option_projects` WHERE `sno_option_projects` ='".$sno_option_projects."'";
		$resultt = $conn->query($sqll) ;
		$roww = $resultt->fetch_array(MYSQLI_ASSOC);
		
		//新增站內信
		$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) 
		VALUES ('審核中' , '".$message."','".$id."', '".$roww['supervisor_project']."','".$sno_option_projects."')";
		$resultt = $conn->query($sqll) ;
	}
	else{
		//新增站內信
		$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) 
		VALUES ('審核中' , '".$message."','".$id."', '".$row['supervisor_strategies']."','".$sno_option_projects."')";
		$resultt = $conn->query($sqll) ;
	}
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>