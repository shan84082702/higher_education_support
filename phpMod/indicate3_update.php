<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$activate_yyy = $_POST["activate_yyy"];
@$name = addslashes($_POST["name"]);
@$sno_edu_indicators = $_POST["sno_edu_indicators"];
@$edu_indicators_sub = $_POST["edu_indicators_sub"];
@$sno_edu_indicators_detail = $_POST["sno_edu_indicators_detail"];
@$insert_names = $_POST["insert_names"];
@$insert_edu_indicators_detail_rule = $_POST["insert_edu_indicators_detail_rule"];
@$edit_id = $_POST["edit_id"];
@$edit_names = $_POST["edit_names"];
@$edit_edu_indicators_detail_rule = $_POST["edit_edu_indicators_detail_rule"];
@$delete_id = $_POST["delete_id"];
include('fig.php');
if($token_time==0){
	
	$sql ="UPDATE `hesprrs_edu_indicators_detail` SET `activate_yyy`='".$activate_yyy."',`name`='".$name."',`sno_edu_indicators`='".$sno_edu_indicators."',`edu_indicators_sub`='".$edu_indicators_sub."' 
	WHERE `sno_edu_indicators_detail` ='".$sno_edu_indicators_detail."'";
	$conn->query($sql) ;
	//管考欄位
	//新增
	$num=count($insert_names);
	for($i=0;$i<$num;$i++){
		$insert_names[$i]=addslashes($insert_names[$i]);
		$insert_edu_indicators_detail_rule[$i]=addslashes($insert_edu_indicators_detail_rule[$i]);
		$sql ="INSERT INTO `hesprrs_edu_management`( `sno_edu_indicator_detail`, `name`,`edu_indicators_detail_rule`) VALUES ('".$sno_edu_indicators_detail."', '".$insert_names[$i]."', '".$insert_edu_indicators_detail_rule[$i]."');
		SELECT LAST_INSERT_ID() as 'management_id';";
		$conn->multi_query($sql);
		$result=$conn->store_result();
		$conn->next_result();
		$result=$conn->store_result();
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$sqll="SELECT DISTINCT `hesprrs_strategies_summary`.`sno_option_strategies`
	  	FROM `hesprrs_strategies_summary` LEFT JOIN `hesprrs_edu_management` ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_strategies_summary`.`sno_edu_management`
		WHERE `hesprrs_edu_management`.`sno_edu_indicator_detail` = '".$sno_edu_indicators_detail."'";
		$resultt=$conn->query($sqll) ;
		while($roww= $resultt->fetch_array(MYSQLI_ASSOC)){
			$sqlll ="INSERT INTO `hesprrs_strategies_summary`( `sno_edu_management`, `sno_option_strategies` ) VALUES('".$row['management_id']."', '".$roww['sno_option_strategies']."')";
			$conn->query($sqlll) ;
		}
	}
	//修改
	$num=count($edit_id);
	for($i=0;$i<$num;$i++){
		$edit_names[$i]=addslashes($edit_names[$i]);
		$edit_edu_indicators_detail_rule[$i]=addslashes($edit_edu_indicators_detail_rule[$i]);
		$sql ="UPDATE `hesprrs_edu_management` SET `name` = '".$edit_names[$i]."',`edu_indicators_detail_rule`='".$edit_edu_indicators_detail_rule[$i]."' WHERE `sno_edu_management` = '".$edit_id[$i]."';";
		$conn->query($sql) ;
	}
	//刪除
	$num=count($delete_id);
	for($i=0;$i<$num;$i++){
		$sql ="DELETE FROM `hesprrs_edu_management` WHERE `sno_edu_management` = '".$delete_id[$i]."';";
		$conn->query($sql) ;
		$sql ="DELETE FROM `hesprrs_strategies_summary` WHERE `sno_edu_management` = '".$delete_id[$i]."';";
		$conn->query($sql) ;
	}
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>