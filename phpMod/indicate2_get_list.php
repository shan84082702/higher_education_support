<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_edu_indicators_sub`, `activate_yyy`, `name`, `updated_at` FROM `hesprrs_edu_indicators_sub` WHERE `hesprrs_edu_indicators_sub`.`activate_yyy` ='".$year."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_edu_indicators_sub']=$row['sno_edu_indicators_sub'];
		$out1['activate_yyy']=$row['activate_yyy'];
		$out1['name']=$row['name'];
		$out1['updated_at']=$row['updated_at'];

		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>