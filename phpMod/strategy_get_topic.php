<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_strategies = $_POST["sno_option_strategies"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_strategies_summary`, `name`, `type` FROM `hesprrs_strategies_summary` LEFT JOIN `hesprrs_edu_management` 
	ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_strategies_summary`.`sno_edu_management` 
	WHERE `sno_option_strategies` = '".$sno_option_strategies."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['manage_id']=$row['sno_strategies_summary'];
		$out1['manage_name']=$row['name'];
		$out1['type']=$row['type'];

		array_push($out,$out1);
		unset($out1);
	}

	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>