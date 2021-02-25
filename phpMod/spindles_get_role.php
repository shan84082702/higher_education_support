<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_roles` as 'role_id', `name` FROM `hesprrs_roles`";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		if($row['role_id']!=1){//不等於管理員才可以進來
			$out1['role_id']=$row['role_id'];
			$out1['name']=$row['name'];
			array_push($out,$out1);
			unset($out1);
		}
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
	
}
?>