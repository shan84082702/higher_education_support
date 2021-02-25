<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_reviewed = $_POST["sno_reviewed"];
@$message = $_POST["message"];
include('fig.php');
if($token_time==0){
	$sql ="INSERT INTO `hesprrs_reviewed_message` (`sno_reviewed`, `sno_members`, `message`) VALUES ('".$sno_reviewed."', '".$id."', '".$message."')";
	$result = $conn->query($sql);
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>