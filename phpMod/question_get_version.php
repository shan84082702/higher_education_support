<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_questionnarie_category`, `name`  FROM `hesprrs_questionnarie_category`";
	$result = $conn->query($sql) ;
	$version=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_questionnarie_category']=$row['sno_questionnarie_category'];
		$out1['name']=$row['name'];
		array_push($version,$out1);
		unset($out1);
	}
	$sql ="SELECT `sno_questionnarie_group`, `name` FROM `hesprrs_questionnarie_group_category`";
	$result = $conn->query($sql) ;
	$category=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_questionnarie_group']=$row['sno_questionnarie_group'];
		$out1['name']=$row['name'];
		array_push($category,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'version' => $version,
		'category' => $category
	));
}
?>