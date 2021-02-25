<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
@$sno_option_main = $_POST["sno_option_main"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT DISTINCT `hesprrs_option_sub`.`sno_option_sub` as 'sub_pk' ,CONCAT(`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_sub`.`name`) as 'sub_name'
	FROM `hesprrs_option_sub` LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	WHERE `hesprrs_option_main`.`activate_yyy` =".$year." and `hesprrs_option_main`.`sno_option_main` ='".$sno_option_main."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sub_pk']=$row['sub_pk'];
		$out1['sub_name']=$row['sub_name'];
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>