<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$day = $_POST["day"];
@$isactivate = $_POST["isactivate"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_roles` FROM `hesprrs_members` where `sno_members` = '".$id."'";
	$result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$role=$row['sno_roles'];
	
	if($role==1){
		$sql ="UPDATE `hesprrs_alert_func` SET `day`='".$day."', `isactivate`='".$isactivate."' WHERE `name` =  '催繳緩衝天數'";
		$result = $conn->query($sql) ;
		$out=1;
	}
	else
		$out=0;
	
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>