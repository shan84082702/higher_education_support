<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_strategies = $_POST["sno_option_strategies"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_option_strategies` WHERE `sno_option_strategies` in (".$sno_option_strategies.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_option_strategies_editor` WHERE `sno_option_strategies` in (".$sno_option_strategies.")";
	$conn->query($sql) ;
	// 2019/08/01 kai
	$sql ="DELETE FROM `hesprrs_option_strategies_edu` WHERE `sno_option_strategies` in (".$sno_option_strategies.")";
	$conn->query($sql) ;
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>