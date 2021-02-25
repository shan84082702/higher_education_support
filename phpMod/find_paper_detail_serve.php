<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$pid = $_POST["pid"];
include('fig.php');
if($token_time==0){
	if ($token != null&&$pid!=null){
		$sql ="SELECT `hesprrs_option_projects`.`name` as  'pname',
			`hesprrs_option_projects`.`activate_date` as  'estimated_date',
			`hesprrs_option_main`.`option_main_order` as 'main_order',
			`hesprrs_option_sub`.`option_sub_order` as 'sub_order',
			`hesprrs_option_strategies`.`option_strategies_order` as 'strategies_order',
			`hesprrs_option_main`.`name` as  'opt_name',
			`hesprrs_option_sub`.`name` as  'subopt_name',
			`hesprrs_option_strategies`.`name` as  'strategiseopt_name',
			`hesprrs_edu_indicators`.`name` as  'edu_name',
			`hesprrs_edu_indicators_detail`.`name` as 'edu_detail',
			`hesprrs_edu_indicators_sub`.`name` as  'edusub_name',
			(CASE WHEN `hesprrs_projects_data`.`date_range` IS NULL THEN '' ELSE `hesprrs_projects_data`.`date_range` END) as  'activate_date',
			`hesprrs_projects_data`.`location`  as 'activate_location',
			`hesprrs_projects_data`.`cost` as 'cost',
			`hesprrs_projects_data`.`description` as  'description',
			`hesprrs_projects_data`.`paricipant_number` as  'paricipant_number',
			`hesprrs_projects_data`.`isongante` as  'isongante',
			`hesprrs_projects_data`.`isinresulf`  as  'isinresulf',
			`hesprrs_projects_data`.`unable_execution_reason` as  'reason',
			`hesprrs_projects_data`.`cooperation_project` as  'cooperation_project'  ,
			`hesprrs_projects_data`.`target` as  'target',
			`hesprrs_projects_data`.`recommendations`  as  'recommendations',
			`hesprrs_projects_data`.`sign` as  'sign_path',
			(CASE
            WHEN  `hesprrs_projects_data`.`review_state` IS null THEN '未審核'
            WHEN  `hesprrs_projects_data`.`review_state` =0 THEN '未審核'
            WHEN  `hesprrs_projects_data`.`review_state` =1 THEN '審核中'
						WHEN  `hesprrs_projects_data`.`review_state` =2 THEN '審核完成'
						WHEN  `hesprrs_projects_data`.`review_state` =3 THEN '審核未通過'
            ELSE `hesprrs_projects_data`.`review_state` END) as  'review_state',
            `hesprrs_projects_data`.`annex` as 'annex_path'
			FROM  `hesprrs_option_projects`
			LEFT  JOIN  `hesprrs_option_strategies`
			ON  `hesprrs_option_strategies`.`sno_option_strategies` =  `hesprrs_option_projects`.`sno_option_strategies`
			LEFT  JOIN  `hesprrs_option_sub`
			ON `hesprrs_option_strategies`.`sno_option_sub`  =  `hesprrs_option_sub`.`sno_option_sub`
			LEFT  JOIN  `hesprrs_option_main`
			ON `hesprrs_option_sub`.`sno_option_main`  =  `hesprrs_option_main`.`sno_option_main`
			LEFT  JOIN  `hesprrs_edu_indicators_detail`
			ON  `hesprrs_option_strategies`.`sno_edu_indicators_detail` =  `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
			LEFT  JOIN  `hesprrs_edu_indicators`
			ON  `hesprrs_edu_indicators_detail`.`sno_edu_indicators` =  `hesprrs_edu_indicators`.`sno_edu_indicators`
			LEFT  JOIN  `hesprrs_edu_indicators_sub`
			ON  `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub` = `hesprrs_edu_indicators_detail`.`edu_indicators_sub`
			LEFT  JOIN  `hesprrs_projects_data`
			ON `hesprrs_projects_data`.`sno_option_projects`  =  `hesprrs_option_projects`.`sno_option_projects`
			where  `hesprrs_option_projects`.`sno_option_projects`='".$pid."'";
		$result = $conn->query($sql) ;
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$out['pname']=$row['pname'];
			$out['estimated_date']=$row['estimated_date'];
			$out['opt_name']=$row['main_order'].'-'.$row['sub_order'].'-'.$row['strategies_order']." ".$row['opt_name'].$row['subopt_name'].$row['strategiseopt_name'];
			$out['edu_name']=$row['edu_name'];
			$out['edu_detail']=$row['edu_detail'];
			$out['edusub_name']=$row['edusub_name'];
			$out['activate_date']=$row['activate_date'];
			$out['activate_location']=$row['activate_location'];
			$out['paricipant_number']=$row['paricipant_number'];
			$out['isongante']=$row['isongante'];
			$out['cost']=$row['cost'];
			$out['description']=$row['description'];
			$out['isinresulf']=$row['isinresulf'];
			$out['reason']=$row['reason'];
			$out['cooperation_project']=$row['cooperation_project'];
			$out['target']=json_decode("[".$row['target']."]", true);
			$out['recommendations']=$row['recommendations'];
			$out['sign_path']=$row['sign_path'];
			$out['review_state']=$row['review_state'];
			if($out['review_state']==NULL)
				$out['review_state']='未審核';
			$out['annex_path']=$row['annex_path'];
		}
		//是否有編輯權限
		$sql = "SELECT IF( EXISTS(
		 		SELECT
		 		`hesprrs_flow`.`sno_option_projects`,
		 		`hesprrs_flow`.`sno_members`,
		 		`hesprrs_flow`.`flow_status`
		 		FROM `hesprrs_flow`
		 		WHERE `hesprrs_flow`.`flow_status` = '2' AND 
		 		`hesprrs_flow`.`sno_option_projects` = '".$pid."' AND 
		 		`hesprrs_flow`.`sno_members` = '".$id."'
		 		) OR 
				 (
					((SELECT `hesprrs_projects_data`.`review_state` FROM `hesprrs_projects_data` WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$pid."') = '0')  AND
					IF (EXISTS(SELECT `hesprrs_projects_editor`.`sno_hesprrs_projects_editor` FROM `hesprrs_projects_editor` WHERE `hesprrs_projects_editor`.`sno_option_projects` = '".$pid."' AND `hesprrs_projects_editor`.`sno_members` = '".$id."')
						, 1, 0)
				 ) OR 
				('管理員' = (SELECT `hesprrs_roles`.`name` FROM `hesprrs_members` LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles` = `hesprrs_roles`.`sno_roles` WHERE `sno_members` = '".$id."')) 
				, 1, 0) AS 'on_this_man'";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$on_this_man=$row['on_this_man'];
		
		$sql ="SELECT `sno_project_summary` as 'qid' ,`hesprrs_edu_management`.`name` as 'edu_name' , `question`, `results` as 'result',`type`
			FROM `hesprrs_projects_summary`
			LEFT JOIN `hesprrs_edu_management` ON
			`hesprrs_projects_summary`.`sno_edu_management` = `hesprrs_edu_management`.`sno_edu_management`
			WHERE `sno_project` ='".$pid."'";
		$result = $conn->query($sql) ;
		$results=array();
		$countq = 0;
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$q['qid'] = $row['qid'];
			$q['result'] = $row['result'];
			$q['edu_name2'] = $row['edu_name'];
			$q['question'] = $row['question'];
			$q['type'] = $row['type'];//如果是1就是檔案
			$countq ++;
			array_push($results,$q);
			unset($q);
		}
		if($countq ==0){
			$q['qid'] = "";
			$q['result'] = "";
			$q['question'] = "";
			array_push($results,$q);
		}
		$out['results']=json_encode($results);


		$sql ="SELECT hesprrs_projects_images.sno_images as 'mid',hesprrs_projects_images.path as  'img_path',hesprrs_projects_images.img_describe as  'img_des'
		FROM  `hesprrs_option_projects`  LEFT  JOIN  `hesprrs_projects_data`  ON  hesprrs_projects_data.sno_option_projects =  hesprrs_option_projects.sno_option_projects
		LEFT  JOIN  `hesprrs_projects_images`  ON hesprrs_projects_data.sno_option_projects = hesprrs_projects_images.sno_option_projects
		WHERE hesprrs_option_projects.sno_option_projects = '".$pid."'";
		$result = $conn->query($sql) ;
		$imgs=array();
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$s['mid']=$row['mid'];
			$s['img_path']=$row['img_path'];
			$s['img_des']=$row['img_des'];
			array_push($imgs,$s);
			unset($s);
		}
		echo json_encode(array(
			'msg' =>'200',
			'isedit'=>$on_this_man,//是否有編輯權限
			'outt' => $out,//json_encode($out)這樣才看得到
			'img' => $imgs
		));
	}
	else {
		echo json_encode(array(
			'msg' => '404'
		));
	}
}
?>
