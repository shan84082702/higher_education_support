<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$option_sub_order = $_POST["option_sub_order"];
@$name = addslashes($_POST["name"]);
@$supervisor_sub = $_POST["supervisor_sub"];
@$sno_option_main = $_POST["sno_option_main"];
@$sno_member = $_POST["sno_member"];
include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_option_sub` (`option_sub_order`, `name`, `supervisor_sub`, `sno_option_main`) 
	VALUES ('".$option_sub_order."', '".$name."', '".$supervisor_sub."', '".$sno_option_main."');
	SELECT LAST_INSERT_ID() as 'sub_pk';";
	
	$conn->multi_query($sql);
	$result=$conn->store_result();
	$conn->next_result();
	$result=$conn->store_result();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$sub_pk=$row['sub_pk'];
	}
	$sql ="INSERT INTO `hesprrs_option_sub_editor`( `sno_option_sub`, `sno_member`) VALUES 
	('".$sub_pk."', '".$supervisor_sub."')";
	$conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_sub_editor` (`sno_option_sub`, `sno_member`) VALUES 
		('".$sub_pk."', '".$sno_member[$i]."')";
		$conn->query($sql);
	}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>