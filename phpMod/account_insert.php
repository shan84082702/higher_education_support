<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_roles = $_POST["sno_roles"];
@$account = $_POST["account"];
@$passwd = $_POST["passwd"];
@$name = $_POST["name"];
@$email = $_POST["email"];
include('fig.php');
if($token_time==0){
	$flag=0;//是否重複的指標
	//判斷帳號是否有重複
	$sql ="SELECT `account` FROM `hesprrs_members`";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$acc=$row['account'];
		if($account==$acc)
			$flag=1;
	}
	
	if($flag==0){//帳號沒有重複
		$passwd=hash('sha256',$passwd );//加密
		$sql ="INSERT INTO `hesprrs_members`(`sno_roles`, `account`, `passwd`, `name`, `email` ) VALUES ('".$sno_roles."', '".$account."','".$passwd."', '".$name."', '".$email."')";
		$result = $conn->query($sql) ;
		
		echo json_encode(array(
			'msg' =>'200'
		));
	}
	else{//帳號有重複
		echo json_encode(array(
			'msg' =>'207'
		));
	}
}
?>