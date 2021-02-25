<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
include('fig.php');
if($token_time==0){
	
	$out=array();
	$sql ="SELECT `flow_number`, `hesprrs_flow`.`sno_members`, `hesprrs_members`.`name` FROM hesprrs_flow LEFT JOIN hesprrs_members ON `hesprrs_members`.`sno_members` = `hesprrs_flow`.`sno_members`
	WHERE `hesprrs_flow`.`flow_status` = '1' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	//加入可編輯人員
	$out1['flow_number']='0';
	$out1['sno_members']='0';
	$out1['name']='可編輯人員';
	array_push($out,$out1);
	unset($out1);
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['flow_number']=$row['flow_number'];
		$out1['sno_members']=$row['sno_members'];
		$out1['name']=$row['name'];
		array_push($out,$out1);
		unset($out1);
		
	}
	
	echo json_encode(array(
		'msg' =>'200',
		'out' =>$out
	));
}
?>