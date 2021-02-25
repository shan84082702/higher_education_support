<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT DISTINCT `activate_yyy` FROM `hesprrs_option_strategies`";
	$result = $conn->query($sql) ;
	$year=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$a=$row['activate_yyy'];
		array_push($year,$a);
		unset($a);//清空
	}
	echo json_encode(array(
		'msg' => '200',
		'out' => $year
	));
}
?> 