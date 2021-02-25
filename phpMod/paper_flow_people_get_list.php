<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
include('fig.php');
if($token_time==0){
	$out=array();
	
	$sqll="SELECT `sno_flow`, `sno_option_projects`, `flow_number`, `hesprrs_flow`.`sno_members` as sno_members, `flow_status`,`hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id' FROM `hesprrs_flow` LEFT JOIN `hesprrs_members` ON
	`hesprrs_flow`.`sno_members` = `hesprrs_members`.`sno_members` WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$resultt = $conn->query($sqll);
	while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_flow']=$row['sno_flow'];
		$out1['flow_number']=$row['flow_number'];
		$out1['role_id']=$row['role_id'];
		$out1['sno_members']=$row['sno_members'];
		$out1['name']=$row['name'];
		$out1['flow_status']=$row['flow_status'];
		array_push($out,$out1);
		unset($out1);
	}
		
	
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>