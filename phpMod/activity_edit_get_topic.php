<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
//@$sno_option_strategies = $_POST["sno_option_strategies"];
@$sno_edu_indicators_detail = $_POST["sno_edu_indicators_detail"];
include('fig.php');
if($token_time==0){
	
	/*$sql ="SELECT `hesprrs_edu_management`.`sno_edu_management`, `hesprrs_edu_management`.`name` FROM `hesprrs_strategies_summary` 
	LEFT JOIN `hesprrs_edu_management` ON `hesprrs_edu_management`.`sno_edu_management` =`hesprrs_strategies_summary`.`sno_edu_management` 
	WHERE `hesprrs_strategies_summary`.`sno_option_strategies` = '".$sno_option_strategies."'";
	*/
	$sql ="SELECT `sno_edu_management`, `name` 
	FROM `hesprrs_edu_management` 
	WHERE `sno_edu_indicator_detail` = '".$sno_edu_indicators_detail."'";
	
	
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_strategies_summary']=$row['sno_edu_management'];
		$out1['name']=$row['name'];
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>