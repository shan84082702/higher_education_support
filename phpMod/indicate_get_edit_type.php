<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators = $_POST["sno_edu_indicators"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_edu_indicators`, `activate_yyy`, `name`, `created_at`, `updated_at` FROM `hesprrs_edu_indicators` WHERE `sno_edu_indicators` ='".$sno_edu_indicators."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_edu_indicators']=$row['sno_edu_indicators'];
		$out1['activate_yyy']=$row['activate_yyy'];
		$out1['name']=$row['name'];
		$out1['created_at']=$row['created_at'];
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