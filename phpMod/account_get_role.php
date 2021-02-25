<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_roles` as  'id', `name` FROM `hesprrs_roles` where `sno_roles` != 1";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['id']=$row['id'];
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