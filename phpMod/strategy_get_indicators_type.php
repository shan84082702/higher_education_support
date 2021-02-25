<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_edu_indicators`, `name` FROM `hesprrs_edu_indicators` where `activate_yyy` = '".$year."'";
	$result = $conn->query($sql);
	$out1=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out11['sno_edu_indicators']=$row['sno_edu_indicators'];
		$out11['name']=$row['name'];
		array_push($out1,$out11);
		unset($out11);
	}
	/*$sql ="SELECT `sno_edu_indicators_sub`, `name` FROM `hesprrs_edu_indicators_sub` where `activate_yyy` = '".$year."'";
	$result = $conn->query($sql);
	$out2=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out21['sno_edu_indicators_sub']=$row['sno_edu_indicators_sub'];
		$out21['name']=$row['name'];
		array_push($out2,$out21);
		unset($out21);
	}*/
	echo json_encode(array(
		'msg' =>'200',
		'out1' => $out1
	));
}
?>