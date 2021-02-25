<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators = $_POST["sno_edu_indicators"];
@$edu_indicators_sub = $_POST["edu_indicators_sub"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_edu_indicators_detail`, `name`  FROM `hesprrs_edu_indicators_detail` Where 
	`sno_edu_indicators` ='".$sno_edu_indicators."' and `edu_indicators_sub` ='".$edu_indicators_sub."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_edu_indicators_detail']=$row['sno_edu_indicators_detail'];
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