<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators_detail = 0;//指標項目流水號
@$option_strategies_order = $_POST["option_strategies_order"];
@$name = addslashes($_POST["name"]);
@$aims = addslashes($_POST["aims"]);
@$supervisor_strategies = $_POST["supervisor_strategies"];
@$activate_yyy = $_POST["activate_yyy"];
@$sno_option_sub = $_POST["sno_option_sub"];
@$sno_member = $_POST["sno_member"];
@$sno_edu_management = $_POST["sno_edu_management"];
include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_option_strategies`(`sno_edu_indicators_detail`, `option_strategies_order`, `name`, `aims`, `supervisor_strategies`,`activate_yyy`,`sno_option_sub`) 
	VALUES ('".$sno_edu_indicators_detail."', '".$option_strategies_order."', '".$name."', '".$aims."', '".$supervisor_strategies."', '".$activate_yyy."', '".$sno_option_sub."');
	SELECT LAST_INSERT_ID() as 'strategies_pk';";
	
	$conn->multi_query($sql);
	$conn->store_result();
	$conn->next_result();
	$result=$conn->store_result();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$strategies_pk=$row['strategies_pk'];
	}
	$sql ="INSERT INTO `hesprrs_option_strategies_editor`( `sno_option_strategies`, `sno_members`) VALUES 
	('".$strategies_pk."', '".$supervisor_strategies."')";
	$conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_strategies_editor`( `sno_option_strategies`, `sno_members`) VALUES 
		('".$strategies_pk."', '".$sno_member[$i]."')";
		$conn->query($sql);
    }
    
	// 2019/08/01 kai
	$num=count($sno_edu_management);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_strategies_edu`( `sno_option_strategies`, `sno_edu_management`) VALUES ('".$strategies_pk."', '".$sno_edu_management[$i]."')";
		$conn->query($sql);
	}
	
	//管考欄位和策略關係欄位新增
	/*$sql ="SELECT `sno_edu_management` FROM `hesprrs_edu_management` WHERE `sno_edu_indicator_detail` = '".$sno_edu_indicators_detail."';";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$sno_edu_management=$row['sno_edu_management'];
		$sqll ="INSERT INTO `hesprrs_strategies_summary` (`sno_edu_management`, `sno_option_strategies`) VALUES ('".$sno_edu_management."', '".$strategies_pk."');";
		$conn->query($sqll) ;
	}*/
	
	
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>