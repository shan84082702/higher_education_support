<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$type = $_POST["type"];
include('fig.php');
if($token_time==0){
	
	$out=array();
	$sql ="SELECT `sno_questionnarie_group`, `name` as 'groupname' FROM `hesprrs_questionnarie_group_category`";
	$result = $conn->query($sql);
	$out1=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sno_questionnarie_group']=$row['sno_questionnarie_group'];
		$out1['groupname']=$row['groupname'];
		$b=array();
		$sqll ="SELECT `sno_questionorder` ,`q_category`, `q_orders`, `titile`, `active`, `created_at`, `updated_at` FROM `hesprrs_questionorder` WHERE `active`=True AND `q_category` ='".$type."' and `sno_questionnarie_group` = '".$row['sno_questionnarie_group']."' ORDER BY `q_orders`";
		$resultt = $conn->query($sqll) ;
		$out2=array();
		while($rowww = $resultt->fetch_array(MYSQLI_ASSOC)){
			$out2['sno_questionorder']=$rowww['sno_questionorder'];
			$out2['question']=$rowww['q_orders'].".".$rowww['titile'];
			$sqlll ="SELECT `sno_subquestion`, `sno_questionorder`, `q_orders` as 'subqorder', `titile` as 'subqtitle', `active`, `created_at`, `updated_at` 
			FROM `hesprrs_subquestionorder` WHERE `sno_questionorder` = '".$out2['sno_questionorder']."' AND  `active` = true ORDER BY `subqorder`";
			$resulttt = $conn->query($sqlll) ;
			$a=array();
			$out3=array();
			while($roww = $resulttt->fetch_array(MYSQLI_ASSOC)){
				$out3['subquestion']=$roww['subqorder'].".".$roww['subqtitle'];
				$out3['subqid']=$roww['sno_subquestion'];
				array_push($a,$out3);
				unset($out3);
			}
			$out2['sub']=$a;
			array_push($b,$out2);
			unset($out2);
		}
		$out1['topic']=$b;
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'outt' => $out
	)); 
}
?>