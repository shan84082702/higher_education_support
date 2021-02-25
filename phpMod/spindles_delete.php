<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$main = $_POST["main"];
include('fig.php');
if($token_time==0){
	$sql ="DELETE FROM `hesprrs_option_main` WHERE `sno_option_main` in (".$main.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_option_main_editer` WHERE `sno_option_main` in (".$main.")";
	$conn->query($sql) ;
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>