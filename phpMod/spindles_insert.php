<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$option_main_order = $_POST["option_main_order"];
@$name = addslashes($_POST["name"]);
@$supervisor_main = addslashes($_POST["supervisor_main"]);
@$activate_yyy = $_POST["activate_yyy"];
@$sno_member = $_POST["sno_member"];
@$sno_review = $_POST["sno_review"];
include('fig.php');
if($token_time==0){	
	//把管理員抓出來
	$sql ="SELECT `sno_members` FROM `hesprrs_members` where `sno_roles`=1";
	$result = $conn->query($sql);
	$sup=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$sno_members=$row['sno_members'];
		array_push($sup,$sno_members);
		unset($sno_members);
	}
	
	
	$sql ="INSERT INTO `hesprrs_option_main` (`option_main_order`, `name`, `supervisor_main`, `activate_yyy`) 
	VALUES ('".$option_main_order."', '".$name."', '".$supervisor_main."', '".$activate_yyy."');
	SELECT LAST_INSERT_ID() as 'main_pk';";
	
	$conn->multi_query($sql);
	$result=$conn->store_result();
	$conn->next_result();
	$result=$conn->store_result();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$main_pk=$row['main_pk'];
	}
	
	//將管理員放進可編輯人員
	$num=count($sup);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_main_editer` (`sno_option_main`, `sno_member`) VALUES 
		('".$main_pk."', '".$sup[$i]."')";
		$result = $conn->query($sql);
	}
	
	
	$sql ="INSERT INTO `hesprrs_option_main_editer` (`sno_option_main`, `sno_member`) VALUES 
	('".$main_pk."', '".$supervisor_main."')";
	$result = $conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_main_editer` (`sno_option_main`, `sno_member`) VALUES 
		('".$main_pk."', '".$sno_member[$i]."')";
		$result = $conn->query($sql);
	}
///{
	$num=count($sno_review);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_review` (`sno_option_main`, `sno_members`) VALUES 
		('".$main_pk."', '".$sno_review[$i]."')";
		$result = $conn->query($sql);
	}
///}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>