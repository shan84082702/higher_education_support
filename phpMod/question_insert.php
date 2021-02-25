<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_questionnarie_group = $_POST["sno_questionnarie_group"];
@$q_category = $_POST["q_category"];
@$question_type = $_POST["question_type"];
@$q_orders = $_POST["q_orders"];
@$titile = $_POST["titile"];
@$list = $_POST["list"];
include('fig.php');
if($token_time==0){
	
	$sql ="INSERT INTO `hesprrs_questionorder`(`sno_questionnarie_group`, `q_category`, `question_type`, `q_orders`, `titile`) 
	VALUES ('".$sno_questionnarie_group."','".$q_category."','".$question_type."','".$q_orders."','".$titile."');
	SELECT LAST_INSERT_ID() as 'q_pk';";
	
	$conn->multi_query($sql);
	$conn->store_result();
	$conn->next_result();
	$result=$conn->store_result();
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$q_pk=$row['q_pk'];
	
	if($question_type==0){
		$sql ="INSERT INTO `hesprrs_subquestionorder`( `sno_questionorder`, `q_orders`, `titile`) 
		VALUES ('".$q_pk."','1','非常同意')";
		$result = $conn->query($sql) ;
		$sql ="INSERT INTO `hesprrs_subquestionorder`( `sno_questionorder`, `q_orders`, `titile`) 
		VALUES ('".$q_pk."','2','同意')";
		$result = $conn->query($sql) ;
		$sql ="INSERT INTO `hesprrs_subquestionorder`( `sno_questionorder`, `q_orders`, `titile`) 
		VALUES ('".$q_pk."','3','普通')";
		$result = $conn->query($sql) ;
		$sql ="INSERT INTO `hesprrs_subquestionorder`( `sno_questionorder`, `q_orders`, `titile`) 
		VALUES ('".$q_pk."','4','不同意')";
		$result = $conn->query($sql) ;
		$sql ="INSERT INTO `hesprrs_subquestionorder`( `sno_questionorder`, `q_orders`, `titile`) 
		VALUES ('".$q_pk."','5','非常不同意')";
		$result = $conn->query($sql) ;
	}
	else{
		$newlist=explode(",",$list);
		$num=count($newlist);
		$newlist[$i-1]=addslashes($newlist[$i-1]);
		for($i=1;$i<$num+1;$i++){
			$sql ="INSERT INTO `hesprrs_subquestionorder`( `sno_questionorder`, `q_orders`, `titile`) 
			VALUES ('".$q_pk."','".$i."','".$newlist[$i-1]."')";
			$result = $conn->query($sql) ;
		}
	}
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>