<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_edu_indicators_detail = $_POST["sno_edu_indicators_detail"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT  `hesprrs_edu_indicators_detail`.`activate_yyy`,`hesprrs_edu_indicators`.`sno_edu_indicators`as 'edu_id' ,`hesprrs_edu_indicators`.`name` as 'edu_name',`hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` as 'edu_sub_id',`hesprrs_edu_indicators_sub`.`name` as 'edu_sub_name', `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` as 'edu_detail_id', `hesprrs_edu_indicators_detail`.`name` as 'edu_detail'
	FROM `hesprrs_edu_indicators_detail` 
	LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
	 WHERE `sno_edu_indicators_detail` ='".$sno_edu_indicators_detail."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_edu_indicators_detail']=$row['edu_detail_id'];
		$out1['activate_yyy']=$row['activate_yyy'];
		$out1['edu_name']=$row['edu_name'];
		$out1['edu_sub_name']=$row['edu_sub_name'];
		$out1['edu_id']=$row['edu_id'];
		$out1['edu_sub_id']=$row['edu_sub_id'];
		$out1['edu_detail']=$row['edu_detail'];
		
		$sqll ="SELECT `sno_edu_management`, `name`,`edu_indicators_detail_rule` FROM `hesprrs_edu_management` WHERE `sno_edu_indicator_detail` ='".$out1['sno_edu_indicators_detail']."'";
		$resultt = $conn->query($sqll) ;
		$out1['name']=array();
		while($roww = $resultt->fetch_array(MYSQLI_ASSOC)){
			$a['sno_edu_management']=$roww['sno_edu_management'];
			$a['name']=$roww['name'];
			$a['edu_indicators_detail_rule']=$roww['edu_indicators_detail_rule'];
			array_push($out1['name'],$a);
			unset($a);
		}
		

		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>