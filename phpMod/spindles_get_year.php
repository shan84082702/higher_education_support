<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT DISTINCT `activate_yyy` as 'year' FROM `hesprrs_option_main` order by `activate_yyy`";
	$result = $conn->query($sql) ;
	$out=array();
	$new_year=0;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['year']=$row['year'];
		$new_year=$row['year'];
		array_push($out,$out1);
		unset($out1);
	}
	$new_year = $new_year +1;
	$out1['year']=$new_year;
	array_push($out,$out1);
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
	
}
?>