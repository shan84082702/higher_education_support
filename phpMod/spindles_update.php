<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$option_main_order = $_POST["option_main_order"];
@$name = addslashes($_POST["name"]);
@$supervisor_main = addslashes($_POST["supervisor_main"]);
@$sno_option_main = $_POST["sno_option_main"];
@$sno_member = $_POST["sno_member"];
@$sno_review = $_POST["sno_review"];
include('fig.php');
if($token_time==0){	
	
	$sql ="UPDATE `hesprrs_option_main` SET 
	`sno_option_main`='".$sno_option_main."',`option_main_order`='".$option_main_order."',`name`='".$name."',`supervisor_main`=  '".$supervisor_main."'
	WHERE `sno_option_main` = '".$sno_option_main."'";
	$conn->query($sql);
	
	$sql ="DELETE FROM `hesprrs_option_main_editer` WHERE `sno_option_main` =".$sno_option_main;
	$conn->query($sql);
	$sql ="INSERT INTO `hesprrs_option_main_editer` (`sno_option_main`, `sno_member`) VALUES 
	('".$sno_option_main."', '".$supervisor_main."')";
	$conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_main_editer` (`sno_option_main`, `sno_member`) VALUES 
	('".$sno_option_main."', '".$sno_member[$i]."')";
		$result = $conn->query($sql);
	}
	///{
	$sql ="DELETE FROM `hesprrs_review` WHERE `sno_option_main` =".$sno_option_main;
	$conn->query($sql);

	$num=count($sno_review);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_review` (`sno_option_main`, `sno_members`) VALUES 
		('".$sno_option_main."', '".$sno_review[$i]."')";
		$result = $conn->query($sql);
	}
	///}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>