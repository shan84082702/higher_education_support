<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT DISTINCT `hesprrs_review`.`sno_option_main`, CONCAT( `hesprrs_option_main`.`option_main_order`, '-', `hesprrs_option_main`.`name` ) AS 'name' 
	FROM `hesprrs_review` LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_review`.`sno_option_main` 
	WHERE (`hesprrs_review`.`sno_members` = '".$id."' OR (SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1' ) AND `hesprrs_option_main`.`activate_yyy` = '".$year."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_option_main']=$row['sno_option_main'];
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