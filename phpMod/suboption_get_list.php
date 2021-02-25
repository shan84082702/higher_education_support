<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `hesprrs_option_sub`.`sno_option_sub` as 'sub_pk' ,`hesprrs_option_main`.`option_main_order` as 'main_id',`hesprrs_option_sub`.`option_sub_order` as 'sub_id',
	`hesprrs_option_sub`.`name` as 'sub_name', `hesprrs_members`.`name` as 'supervisor',`hesprrs_members`.`sno_members` as 'supervisor_id', `hesprrs_members`.`sno_roles` as 'role_id'
	FROM `hesprrs_option_sub` LEFT JOIN `hesprrs_members` ON `hesprrs_option_sub`.`supervisor_sub` = `hesprrs_members`.`sno_members`
	LEFT JOIN `hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
	WHERE `hesprrs_option_main`.`activate_yyy` =".$year;
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['sub_pk']=$row['sub_pk'];
		$out1['main_id']=$row['main_id'];
		$out1['sub_id']=$row['sub_id'];
		$out1['sub_name']=$row['sub_name'];
		$out1['supervisor']=$row['supervisor'];
		$out1['supervisor_id']=$row['supervisor_id'];
		$out1['role_id']=$row['role_id'];
		
		$sqll ="SELECT `sno_option_sub`,`sno_member`as 'member_id' ,`hesprrs_members`.`name` ,`hesprrs_members`.`sno_roles` as 'role_id'
		FROM `hesprrs_option_sub_editor` LEFT JOIN `hesprrs_members` ON `hesprrs_option_sub_editor`.`sno_member` = `hesprrs_members`.`sno_members`
		WHERE `sno_option_sub` =".$out1['sub_pk']." and `hesprrs_members`.`sno_members` !='".$out1['supervisor_id']."' and `hesprrs_members`.`sno_roles` != 1";
		$resultt = $conn->query($sqll) ;
		$sec_out=array();
		while($row = $resultt->fetch_array(MYSQLI_ASSOC)){
			$sec_out1['sno_option_sub']=$row['sno_option_sub'];
			$sec_out1['member_id']=$row['member_id'];
			$sec_out1['name']=$row['name'];
			$sec_out1['role_id']=$row['role_id'];
			array_push($sec_out,$sec_out1);
			unset($sec_out1);
		}
		$out1['edit_name']=$sec_out;
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>