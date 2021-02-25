<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$activate_yyy = $_POST["activate_yyy"];
@$name = addslashes($_POST["name"]);//部訂指標項目名稱
@$sno_edu_indicators = $_POST["sno_edu_indicators"];//教育部指標流水號
@$edu_indicators_sub = $_POST["edu_indicators_sub"];//教育部指標說明流水號
@$names = $_POST["names"];//管考欄位名稱
@$edu_indicators_detail_rule = $_POST["edu_indicators_detail_rule"];//衡量基準
include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_edu_indicators_detail`( `activate_yyy`, `name`, `sno_edu_indicators`, `edu_indicators_sub`) VALUES
	('".$activate_yyy."', '".$name."', '".$sno_edu_indicators."', '".$edu_indicators_sub."');
	SELECT LAST_INSERT_ID() as 'sno_edu_indicator_detail';";
	$conn->multi_query($sql);
	$result=$conn->store_result();
	$conn->next_result();
	$result=$conn->store_result();
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$sub_pk=$row['sno_edu_indicator_detail'];
	
	$num=count($names);
	for($i=0;$i<$num;$i++){
		$names[$i]=addslashes($names[$i]);
		$edu_indicators_detail_rule[$i]=addslashes($edu_indicators_detail_rule[$i]);
		$sql ="INSERT INTO `hesprrs_edu_management`( `sno_edu_indicator_detail`, `name`, `edu_indicators_detail_rule`) VALUES
		('".$sub_pk."', '".$names[$i]."','".$edu_indicators_detail_rule[$i]."')";
		$conn->query($sql);
	}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>