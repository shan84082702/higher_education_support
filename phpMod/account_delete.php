<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$userid = $_POST["userid"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_members` WHERE `sno_members` in (".$userid.")";
	$result = $conn->query($sql) ;
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>