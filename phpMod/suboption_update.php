<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_main = $_POST["sno_option_main"];
@$option_sub_order = $_POST["option_sub_order"];
@$name = addslashes($_POST["name"]);
@$supervisor_sub = $_POST["supervisor_sub"];
@$sub_pk = $_POST["sub_pk"];
@$sno_member = $_POST["sno_member"];
include('fig.php');
if($token_time==0){
	
	$sql ="UPDATE `hesprrs_option_sub` SET 
	`option_sub_order`='".$option_sub_order."',`supervisor_sub`='".$supervisor_sub."',`name`='".$name."',`sno_option_main`='".$sno_option_main."'
	WHERE `sno_option_sub` = '".$sub_pk."'";
	$conn->query($sql);
	
	$sql ="DELETE FROM `hesprrs_option_sub_editor` WHERE `sno_option_sub`=".$sub_pk;
	$conn->query($sql);
	
	$sql ="INSERT INTO `hesprrs_option_sub_editor` (`sno_option_sub`, `sno_member`) VALUES 
	('".$sub_pk."', '".$supervisor_sub."')";
	$conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_sub_editor` (`sno_option_sub`, `sno_member`) VALUES ('".$sub_pk."', '".$sno_member[$i]."')";
		$result = $conn->query($sql);
	}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>