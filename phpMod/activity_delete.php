<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
include('fig.php');
if($token_time==0){
	
	$sql ="DELETE FROM `hesprrs_option_projects` WHERE `sno_option_projects` in (".$sno_option_projects.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_projects_data` WHERE `sno_option_projects` in (".$sno_option_projects.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_projects_editor` WHERE `sno_option_projects` in (".$sno_option_projects.")";
	$conn->query($sql) ;
	$sql ="DELETE FROM `hesprrs_flow` WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sql) ;
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>