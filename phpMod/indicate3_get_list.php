<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_edu_indicators_detail`, `hesprrs_edu_indicators_detail`.`activate_yyy`,`hesprrs_edu_indicators`.`name` as 'edu_name',`hesprrs_edu_indicators_sub`.`name` as 'edu_sub_name', `hesprrs_edu_indicators_detail`.`name` as 'edu_detail'
	FROM `hesprrs_edu_indicators_detail` 
	LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` 
    where `hesprrs_edu_indicators_detail`.`activate_yyy` ='".$year."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_edu_indicators_detail']=$row['sno_edu_indicators_detail'];
		$out1['activate_yyy']=$row['activate_yyy'];
		$out1['edu_name']=$row['edu_name'];
		$out1['edu_sub_name']=$row['edu_sub_name'];
		$out1['edu_detail']=$row['edu_detail'];
		$out1['str']="";
		$out1['edu_indicators_detail_rule']="";
		$sqll ="SELECT `sno_edu_management`, `name`,`edu_indicators_detail_rule` FROM `hesprrs_edu_management` WHERE `sno_edu_indicator_detail` ='".$out1['sno_edu_indicators_detail']."'";
		$resultt = $conn->query($sqll) ;
		while($roww = $resultt->fetch_array(MYSQLI_ASSOC)){
			$out1['str']=$out1['str'].$roww['name'].",";//管考欄位
			$out1['edu_indicators_detail_rule']=$out1['edu_indicators_detail_rule'].$roww['edu_indicators_detail_rule'].",";//衡量基準
		}
		$out1['str']=substr($out1['str'],0,-1);
		if($out1['str']==false)
			$out1['str']="";
		$out1['edu_indicators_detail_rule']=substr($out1['edu_indicators_detail_rule'],0,-1);
		if($out1['edu_indicators_detail_rule']==false)
			$out1['edu_indicators_detail_rule']="";
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>