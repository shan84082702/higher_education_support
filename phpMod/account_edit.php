<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_roles = $_POST["sno_roles"];
@$account = $_POST["account"];
@$passwd = $_POST["passwd"];
@$name = $_POST["name"];
@$email = $_POST["email"];
@$userid = $_POST["userid"];
include('fig.php');
$admin=0;
//判斷是否可以改密碼，管理員才可以改，看token的id
$sql ="SELECT `sno_members` FROM `hesprrs_members` where `sno_members` ='".$id."' and `sno_roles`=1";
$result = $conn->query($sql) ;
while($row = $result->fetch_array(MYSQLI_ASSOC)){
	$sno_members=$row['sno_members'];
	if($id==$sno_members)
		$admin=1;
}
if($token_time==0&&$admin==1){//有權限可以更改密碼
	$flag=0;//是否重複的指標
	//判斷帳號是否有重複
	$sql ="SELECT `account` FROM `hesprrs_members` where `sno_members` !='".$userid."'";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$acc=$row['account'];
		if($account==$acc)
			$flag=1;
	}
	
	if($flag==0){//帳號沒有重複
		$passwd=hash('sha256',$passwd );//加密
		$sql ="UPDATE `hesprrs_members` SET `account`='".$account."',`passwd`='".$passwd."',`sno_roles`='".$sno_roles."',
		`name`='".$name."',`email`='".$email."',`updated_at`= NOW()
		WHERE `sno_members` = '".$userid."'";
		$result = $conn->query($sql);
		
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
else if($admin==0){//沒權限更改密碼
	echo json_encode(array(
		'msg' =>'208'
	));
}
?>