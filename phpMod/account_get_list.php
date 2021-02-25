<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_members` as 'mem_id', `account` as 'acc', `hesprrs_roles`.`sno_roles` as 'rol_id',`hesprrs_roles`.`name` as 'rol_name', `hesprrs_members`.`name` as 'mem_name', `email`, `hesprrs_members`.`created_at` 
	FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles` where `hesprrs_roles`.`sno_roles` != 1";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['mem_id']=$row['mem_id'];
		$out1['acc']=$row['acc'];
		$out1['rol_id']=$row['rol_id'];
		$out1['rol_name']=$row['rol_name'];
		$out1['mem_name']=$row['mem_name'];
		$out1['email']=$row['email'];
		$out1['created_at']=$row['created_at'];
		
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>