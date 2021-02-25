<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$userid = $_POST["userid"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_members` as 'mem_id', `account` as 'acc', `hesprrs_roles`.`sno_roles` as 'rol_id',`hesprrs_roles`.`name` as 'rol_name', `hesprrs_members`.`name` as 'mem_name', `email`, `hesprrs_members`.`created_at` 
	FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles`
	WHERE `sno_members` ='".$userid."'";
	$result = $conn->query($sql) ;
	
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out['mem_id']=$row['mem_id'];
		$out['acc']=$row['acc'];
		$out['rol_id']=$row['rol_id'];
		$out['rol_name']=$row['rol_name'];
		$out['mem_name']=$row['mem_name'];
		$out['email']=$row['email'];
		$out['created_at']=$row['created_at'];
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>