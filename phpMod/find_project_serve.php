<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	if ($token != null&&$year!=null) {
		$sql ="SELECT DISTINCT 
		`hesprrs_option_main`.`option_main_order`,`hesprrs_option_sub`.`option_sub_order`,`hesprrs_option_strategies`.`option_strategies_order`,
		concat(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`) as 'order',
		`hesprrs_option_projects`.`sno_option_projects` as  'pid',`hesprrs_option_projects`.`name` as  'project_name',`hesprrs_projects_data`.`updated_at` as  'update_time'  ,
		`hesprrs_option_projects`.`activate_date` as  'estimated_date',  `hesprrs_projects_data`.`isfilledresult` as  'is_filled_result',(CASE
            WHEN  `hesprrs_projects_data`.`review_state` IS null THEN '未審核'
            WHEN  `hesprrs_projects_data`.`review_state` =0 THEN '未審核'
            WHEN  `hesprrs_projects_data`.`review_state` =1 THEN '審核中'
						WHEN  `hesprrs_projects_data`.`review_state` =2 THEN '審核完成'
						WHEN  `hesprrs_projects_data`.`review_state` =3 THEN '審核未通過'
            ELSE `hesprrs_projects_data`.`review_state` END) as  'review_state'
		FROM    `hesprrs_option_projects`
		LEFT JOIN `hesprrs_projects_editor` ON
			`hesprrs_projects_editor`.`sno_option_projects`  =  `hesprrs_option_projects`.`sno_option_projects`
        LEFT JOIN  `hesprrs_projects_data`  ON
			`hesprrs_option_projects`.`sno_option_projects`  = `hesprrs_projects_data`.`sno_option_projects`
		LEFT JOIN `hesprrs_option_strategies` ON
			`hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
		LEFT JOIN `hesprrs_option_strategies_editor` ON
			`hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_strategies_editor`.`sno_option_strategies`
		LEFT JOIN `hesprrs_option_sub` ON
			`hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
		LEFT JOIN `hesprrs_option_sub_editor` ON
			`hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_sub_editor`.`sno_option_sub`
		LEFT JOIN `hesprrs_option_main` ON
			`hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main`
		LEFT JOIN `hesprrs_option_main_editer` ON
			`hesprrs_option_main`.`sno_option_main` = `hesprrs_option_main_editer`.`sno_option_main`
		LEFT JOIN `hesprrs_flow` ON
        	`hesprrs_flow`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
		WHERE  `hesprrs_option_projects`.`activate_yyy`  =   '".$year."'  AND
		(`hesprrs_projects_editor`.`sno_members` =  '".$id."' OR 
			`hesprrs_option_main_editer`.`sno_member` = '".$id."' OR 
			`hesprrs_option_sub_editor`.`sno_member` = '".$id."' OR 
			`hesprrs_option_strategies_editor`.`sno_members` = '".$id."' OR
			((SELECT `hesprrs_roles`.`name` FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles` WHERE `sno_members` = '".$id."') = '管理員') OR
			`hesprrs_flow`.`sno_members` = '".$id."')
		ORDER BY `hesprrs_option_main`.`option_main_order`, `hesprrs_option_sub`.`option_sub_order`, `hesprrs_option_strategies`.`option_strategies_order` ASC ";


		$result = $conn->query($sql) ;
		$a=array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$out['order']=$row['order'];
			$out['pid']=$row['pid'];
			$out['project_name']=$row['project_name'];
            $out['update_time']=$row['update_time'];
            if($out['update_time']==null)
                $out['update_time']=0;
			$out['estimated_date']=$row['estimated_date'];
			$out['is_filled_result']=$row['is_filled_result'];
			if($out['is_filled_result']==null)
				$out['is_filled_result']=0;
            $out['review_state']=$row['review_state'];
            if($out['review_state']==null)
                $out['review_state']=0;

			$sqll = "SELECT 
			((CASE (SELECT `hesprrs_projects_data`.`review_state` FROM `hesprrs_projects_data` WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$row['pid']."') 
				WHEN '2' THEN '0'
				WHEN '1' THEN (
					IF(EXISTS(SELECT `hesprrs_flow`.`sno_flow` FROM `hesprrs_flow` WHERE `hesprrs_flow`.`sno_option_projects` = '".$row['pid']."' AND `hesprrs_flow`.`sno_members` = '".$id."' AND `hesprrs_flow`.`flow_status` = '2')
						,1 ,0)
					)
				WHEN '0' THEN (
					IF (EXISTS(SELECT `hesprrs_projects_editor`.`sno_hesprrs_projects_editor` FROM `hesprrs_projects_editor` WHERE `hesprrs_projects_editor`.`sno_option_projects` = '".$row['pid']."' AND `hesprrs_projects_editor`.`sno_members` = '".$id."')
						, 1, 0)
					)
				ELSE '0'
				END) OR
			('管理員' = (SELECT `hesprrs_roles`.`name` FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles` WHERE `sno_members` = '".$id."'))
			) AS 'edit'";
			$resultt = $conn->query($sqll) ;
			$roww = $resultt->fetch_array(MYSQLI_ASSOC);
			$out['edit']=$roww['edit'];
			$out=json_encode($out);
			array_push($a,$out);
			unset($out);//清空
		}
		echo json_encode(array(
			'msg' => '200',
			'outt' => $a
		));
	}
	else {
		echo json_encode(array(
			'msg' => '404'
		));
	}
}
?>
