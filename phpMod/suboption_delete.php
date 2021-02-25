<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sub_pk = $_POST["sub_pk"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_option_sub` WHERE `sno_option_sub` in (".$sub_pk.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_option_sub_editor` WHERE `sno_option_sub` in (".$sub_pk.")";
	$conn->query($sql) ;
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>