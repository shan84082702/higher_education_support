<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sub_pk = $_POST["sub_pk"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT
    `hesprrs_option_strategies`.`sno_option_strategies`,
    `hesprrs_option_strategies`.`sno_edu_indicators_detail`,
    CONCAT(
        `hesprrs_option_strategies`.`option_strategies_order`,
        '-',
        `hesprrs_option_strategies`.`name`
    ) AS 'name'
	FROM `hesprrs_option_strategies` LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail` = `hesprrs_option_strategies`.`sno_edu_indicators_detail`
	LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators`.`sno_edu_indicators` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators`
    LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` = `hesprrs_edu_indicators_detail`.`edu_indicators_sub`
	WHERE `hesprrs_option_strategies`.`sno_option_sub` = '".$sub_pk."' AND `hesprrs_option_strategies`.`activate_yyy` = '".$year."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_option_strategies']=$row['sno_option_strategies'];
		$out1['sno_edu_indicators_detail']=$row['sno_edu_indicators_detail'];
		$out1['name']=$row['name'];
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>