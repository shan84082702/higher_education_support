<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `hesprrs_members`.`sno_members`, `hesprrs_members`.`name`, `hesprrs_reviewed`.`sno_reviewed`,
	( CASE WHEN `hesprrs_reviewed`.`need_reply` = '0' THEN '已審查不用回復' WHEN `hesprrs_reviewed`.`need_reply` = '1' THEN '已審查待回復' WHEN `hesprrs_reviewed`.`need_reply` = '2' THEN '審查完成' END ) AS 'need_reply' 
	FROM `hesprrs_reviewed` LEFT JOIN `hesprrs_members` ON `hesprrs_members`.`sno_members` = `hesprrs_reviewed`.`sno_members` WHERE `sno_option_projects` = '".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		
		
		$sqll ="SELECT `hesprrs_reviewed_message`.`updated_at`,`hesprrs_members`.`name`, `hesprrs_reviewed_message`.`message` FROM `hesprrs_reviewed_message` 
		LEFT JOIN `hesprrs_reviewed` ON `hesprrs_reviewed`.`sno_reviewed` = `hesprrs_reviewed_message`.`sno_reviewed` 
		LEFT JOIN `hesprrs_members` ON `hesprrs_members`.`sno_members` = `hesprrs_reviewed_message`.`sno_members` 
		WHERE `hesprrs_reviewed`.`sno_members` = '".$row['sno_members']."' AND `hesprrs_reviewed`.`sno_option_projects` = '".$sno_option_projects."' ORDER BY `hesprrs_reviewed_message`.`updated_at` DESC"; 
		$resultt = $conn->query($sqll) ;
		while($roww = $resultt->fetch_array(MYSQLI_ASSOC)){
			////前一個sql
			$out1['sno_reviewed']=$row['sno_reviewed'];
			$out1['need_reply']=$row['need_reply'];
			$out1['review_name']=$row['name'];//審查人員
			////
			$out1['updated_at']=$roww['updated_at'];
			$out1['send_name']=$roww['name'];//寄件者
			$out1['message']=$roww['message'];
			
			array_push($out,$out1);
			unset($out1);
		}
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>