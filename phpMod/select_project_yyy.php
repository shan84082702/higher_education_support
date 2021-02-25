<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	if ($token != null) { 
		$sql ="SELECT `activate_yyy` FROM `hesprrs_option_projects`  GROUP BY  `activate_yyy` desc";
		$result = $conn->query($sql) ;
		$a=array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$out=$row['activate_yyy'];
			array_push($a,$out);
			unset($out);//清空
		}
		echo json_encode(array(
			'msg' => '200',
			'out' => $a
		));
	} 
	else {
		echo json_encode(array(
			'msg' => '404'
		));
	}
}
?> 