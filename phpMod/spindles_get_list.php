<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT hesprrs_option_main.`sno_option_main` as 'son_id',`activate_yyy` as 'year', `option_main_order` as 'main_oder',  `hesprrs_option_main`.`name` as 'main_name', `hesprrs_members`.`sno_members` as 'supervisor_id' ,
	`hesprrs_members`.`name` as 'supervisor' ,`hesprrs_members`.`sno_roles` as 'role_id'
	FROM `hesprrs_option_main` 	LEFT JOIN `hesprrs_members` ON `hesprrs_option_main`.`supervisor_main` = `hesprrs_members`.`sno_members`
	WHERE `activate_yyy` =".$year;
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['son_id']=$row['son_id'];
		$out1['year']=$row['year'];
		$out1['main_oder']=$row['main_oder'];
		$out1['main_name']=$row['main_name'];
		$out1['supervisor_id']=$row['supervisor_id'];
		$out1['supervisor']=$row['supervisor'];
		$out1['role_id']=$row['role_id'];
		$sqll ="SELECT `sno_option_main`, `sno_member`as 'member_id', `hesprrs_members`.`name`,`hesprrs_members`.`sno_roles` as 'role_id'  FROM `hesprrs_option_main_editer` 
		LEFT JOIN `hesprrs_members` ON `hesprrs_option_main_editer`.`sno_member` = `hesprrs_members`.`sno_members`
		WHERE `sno_option_main` ='".$out1['son_id']."' and  `sno_member` != '".$out1['supervisor_id']."' and `hesprrs_members`.`sno_roles` != 1";
		$resultt = $conn->query($sqll) ;
		$sec_out=array();
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$sec_out1['sno_option_main']=$row['sno_option_main'];
			$sec_out1['member_id']=$row['member_id'];
			$sec_out1['name']=$row['name'];
			$sec_out1['role_id']=$row['role_id'];
			array_push($sec_out,$sec_out1);
			unset($sec_out1);
		}
		$out1['edit_name']=$sec_out;
////
		$sqll ="SELECT `hesprrs_members`.`sno_members`, `hesprrs_members`.`name` FROM `hesprrs_review` 
		LEFT JOIN `hesprrs_members` ON `hesprrs_members`.`sno_members` = `hesprrs_review`.`sno_members` 
		WHERE `sno_option_main` = '".$out1['son_id']."'";
		$resultt = $conn->query($sqll) ;
		$third_out=array();
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$third_out1['sno_members']=$row['sno_members'];
			$third_out1['name']=$row['name'];
			array_push($third_out,$third_out1);
			unset($third_out1);
		}
		$out1['review']=$third_out;
////
		array_push($out,$out1);
		unset($out1);
	}

	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>