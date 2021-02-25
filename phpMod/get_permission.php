<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT `sno_roles` as 'id',DATE_FORMAT(`created_at`, '%Y/%m/%d') as 'creat_time',`name` as 'role_name' , `description` , 
	CONCAT((CASE WHEN `project_edit` = 1 THEN '成果報告管理' ELSE '' END),
	(CASE WHEN `project_edit`= 1 AND `strategiest_summary`= 1 THEN '、' ELSE '' END),
	(CASE WHEN `strategiest_summary`= 1 THEN '報告總結管理' ELSE '' END),
	(CASE WHEN (`project_edit` = 1 OR `strategiest_summary`= 1) AND `data_manage`= 1 THEN '、' ELSE '' END),
	(CASE WHEN `data_manage`= 1 THEN '資料管理' ELSE '' END),
	(CASE WHEN (`project_edit` = 1 OR `strategiest_summary`= 1 OR `data_manage`= 1) AND `member_manage` = 1 THEN '、' ELSE '' END),
	(CASE WHEN `member_manage` = 1 THEN '帳號管理' ELSE '' END),
    (CASE WHEN (`project_edit` = 1 OR `strategiest_summary`= 1 OR `data_manage`= 1 OR `member_manage` = 1) AND `view_data` = 1 THEN '、' ELSE '' END),
    (CASE WHEN `view_data` = 1 THEN '檢視資料' ELSE '' END),
	(CASE WHEN (`project_edit` = 1 OR `strategiest_summary`= 1 OR `data_manage`= 1 OR `member_manage` = 1 OR `view_data` = 1) AND `review` = 1 THEN '、' ELSE '' END),
	(CASE WHEN `review` = 1 THEN '診斷小組指標審查' ELSE '' END)
	) as 'power'
	FROM hesprrs_roles";
	$result = $conn->query($sql) ;
	$out=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out1['id']=$row['id'];
		$out1['creat_time']=$row['creat_time'];
		$out1['role_name']=$row['role_name'];
		$out1['description']=$row['description'];
		$out1['power']=$row['power'];
		array_push($out,$out1);
		unset($out1);
	}
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
	
}
?>