<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
@$sno_edu_indicators = $_POST["sno_edu_indicators"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT DISTINCT `edu_indicators_sub`, `hesprrs_edu_indicators_sub`.`name` FROM `hesprrs_edu_indicators_detail` LEFT JOIN `hesprrs_edu_indicators_sub`
	ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` 
	WHERE `sno_edu_indicators` = '".$sno_edu_indicators."' AND `hesprrs_edu_indicators_detail`.`activate_yyy` = '".$year."'";
	$result = $conn->query($sql);
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_edu_indicators_sub']=$row['edu_indicators_sub'];
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