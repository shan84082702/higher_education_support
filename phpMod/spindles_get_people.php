<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$role_id = $_POST["role_id"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_members`, `sno_roles`,`name` FROM `hesprrs_members` WHERE `sno_roles` = '".$role_id ."' ORDER BY CAST(CONVERT(`name` using big5) AS BINARY) ASC";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_members']=$row['sno_members'];
		$out1['sno_roles']=$row['sno_roles'];
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