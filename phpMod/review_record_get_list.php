<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_reviewed = $_POST["sno_reviewed"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `hesprrs_reviewed_message`.`updated_at`, `hesprrs_members`.`name`, `hesprrs_reviewed_message`.`message` FROM `hesprrs_reviewed_message` 
	LEFT JOIN `hesprrs_reviewed` ON `hesprrs_reviewed`.`sno_reviewed` = `hesprrs_reviewed_message`.`sno_reviewed` LEFT JOIN `hesprrs_members` on `hesprrs_members`.`sno_members` = `hesprrs_reviewed_message`.`sno_members` 
	WHERE `hesprrs_reviewed_message`.`sno_reviewed` = '".$sno_reviewed."'  ORDER BY `hesprrs_reviewed_message`.`updated_at` DESC";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['updated_at']=$row['updated_at'];
		$out1['name']=$row['name'];
		$out1['message']=$row['message'];
		array_push($out,$out1);
		unset($out1);
    }
	
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>