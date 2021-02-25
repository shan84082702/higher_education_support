<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
//@$sno_edu_indicators_detail = $_POST["sno_edu_indicators_detail"];//應該不用了
@$option_strategies_order = $_POST["option_strategies_order"];
@$name = addslashes($_POST["name"]);
@$aims = addslashes($_POST["aims"]);
@$supervisor_strategies = $_POST["supervisor_strategies"];
@$sno_option_sub = $_POST["sno_option_sub"];
@$sno_option_strategies = $_POST["sno_option_strategies"];
@$sno_member = $_POST["sno_member"];
@$sno_edu_management = $_POST["sno_edu_management"];
include('fig.php');
if($token_time==0){
	//看部訂指標有沒有更改(沒有一樣才要更改)，刪除所有下面的問題
	/*$sql ="SELECT `sno_option_strategies`, `sno_edu_indicators_detail` FROM `hesprrs_option_strategies` WHERE `sno_option_strategies` = '".$sno_option_strategies."';";
	$result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if($row['sno_edu_indicators_detail']!=$sno_edu_indicators_detail){
		$sqll ="DELETE FROM `hesprrs_strategies_summary` WHERE `sno_option_strategies` = '".$sno_option_strategies."';";
		$conn->query($sqll);
		$sqll ="DELETE FROM `hesprrs_projects_summary` WHERE `sno_option_strategies` ='".$sno_option_strategies."';";
		$conn->query($sqll);
		//換題目後新增新題目下的東西
		$sqll ="SELECT `sno_edu_management` FROM `hesprrs_edu_management` WHERE `sno_edu_indicator_detail` = '".$sno_edu_indicators_detail."';";
		$resultt = $conn->query($sqll) ;
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$sno_edu_management=$row['sno_edu_management'];
			$sqlll ="INSERT INTO `hesprrs_strategies_summary` (`sno_edu_management`, `sno_option_strategies`) VALUES ('".$sno_edu_management."', '".$sno_option_strategies."');";
			$conn->query($sqlll) ;
		}
	}*/
	
	
	
	
	
	$sql ="UPDATE `hesprrs_option_strategies` SET 
	`option_strategies_order`='".$option_strategies_order."',`name`='".$name."',`aims`='".$aims."',`supervisor_strategies`='".$supervisor_strategies."',`sno_option_sub`='".$sno_option_sub."'
	WHERE `sno_option_strategies` = '".$sno_option_strategies."'";
	$conn->query($sql);
	
	$sql ="DELETE FROM `hesprrs_option_strategies_editor` WHERE `sno_option_strategies`=".$sno_option_strategies;
	$conn->query($sql);
	
	$sql ="INSERT INTO `hesprrs_option_strategies_editor` (`sno_option_strategies`, `sno_members`) VALUES 
	('".$sno_option_strategies."', '".$supervisor_strategies."')";
	$conn->query($sql);
	
	$num=count($sno_member);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_strategies_editor` (`sno_option_strategies`, `sno_members`) VALUES 
		('".$sno_option_strategies."', '".$sno_member[$i]."')";
		$result = $conn->query($sql);
	}

    // 2019/08/01 kai
    //$num=count($sno_member);
	//$sql ="DELETE FROM `hesprrs_option_strategies_edu` WHERE `sno_option_strategies_edu` = '".$sno_option_strategies_edu."'";
    $sql ="DELETE FROM `hesprrs_option_strategies_edu` WHERE `sno_option_strategies` = '".$sno_option_strategies."'";
    $result = $conn->query($sql);
    $num=count($sno_edu_management);
	for($i=0;$i<$num;$i++){
		$sql ="INSERT INTO `hesprrs_option_strategies_edu`( `sno_option_strategies`, `sno_edu_management`) VALUES ('".$sno_option_strategies."', '".$sno_edu_management[$i]."')";
		$result = $conn->query($sql);
	}
    

	echo json_encode(array(
		'msg' =>'200'
	));
}
?>