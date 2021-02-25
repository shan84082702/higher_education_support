<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$q_category = $_POST["q_category"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_questionorder` as 'q_pk',`q_orders` as 'order',
	(CASE WHEN `hesprrs_questionorder`.`question_type` = '0' THEN '量表' ELSE '自訂' END) as 'a_type',
	`hesprrs_questionnarie_group_category`.`name` as 'q_group',
	`hesprrs_questionorder`.`titile` as 'q_title'
	FROM `hesprrs_questionorder` 
	LEFT JOIN `hesprrs_questionnarie_category` ON
	`hesprrs_questionorder`.`q_category` = `hesprrs_questionnarie_category`.`sno_questionnarie_category`
	LEFT JOIN `hesprrs_questionnarie_group_category` ON
	`hesprrs_questionorder`.`sno_questionnarie_group` = `hesprrs_questionnarie_group_category`.`sno_questionnarie_group`
	WHERE  `hesprrs_questionorder`.`active` = true  and  `q_category` = '".$q_category."'";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['q_pk']=$row['q_pk'];
		$out1['order']=$row['order'];
		$out1['a_type']=$row['a_type'];
		$out1['q_group']=$row['q_group'];
		$out1['q_title']=$row['q_title'];
		
		$sqll ="SELECT `titile` as 'name' FROM `hesprrs_subquestionorder` WHERE `active`=true AND  `active` = true  and `sno_questionorder`= '".$out1['q_pk']."'";
		$resultt = $conn->query($sqll) ;
		$sec_out="";
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$sec_out=$sec_out.",".$row['name'];
		}
		$sec_out=substr($sec_out,1);
		$out1['topic']=$sec_out;
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>