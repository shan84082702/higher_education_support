<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_questionnarie_category = $_POST["sno_questionnarie_category"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_questionnarie_category`, `name`, `updated_at` FROM `hesprrs_questionnarie_category` WHERE `active` = 1 and `sno_questionnarie_category` = '".$sno_questionnarie_category."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_questionnarie_category']=$row['sno_questionnarie_category'];
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