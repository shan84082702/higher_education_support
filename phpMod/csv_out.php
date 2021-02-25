<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	$name="ReportSummary";
	$url = 'phpMod/download/'.$year.$name.'.csv';
	$write_url='download/'.$year.$name.'.csv';
	$fp = fopen($write_url, 'w+');//寫檔
	$lists=array("指標類型","指標項目","項目","衡量基準/計算公式(量化)檢核方式(質化)","教育部管考欄位(A)","本項目執行策略名稱","負責教師","本項目目標","執行內容與管考指標(提出對應教育部指標應請老師繳交之內容，每點一列)", 
	"繳交類型(文字描述或附檔)","對應活動或課程等內容","報告書繳交","繳交通知期限");
	fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));
	fputcsv($fp, $lists);
	$sql ="SELECT GROUP_CONCAT(`hesprrs_option_projects`.`sno_option_projects`) as 'projects_id',
	`hesprrs_option_projects`.`name` as 'project_name',
	COUNT(`hesprrs_option_projects`.`name`) as 'paper_num',
	GROUP_CONCAT(`hesprrs_option_projects`.`activate_date`) as 'act_mm'
	FROM `hesprrs_option_projects`
	WHERE `hesprrs_option_projects`.`activate_yyy` =".$year."
	GROUP BY `hesprrs_option_projects`.`name`";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		

		$sqll ="SELECT `hesprrs_edu_indicators`.`name` as 'edu_class',
		`hesprrs_edu_indicators_sub`.`name` as 'edu_opt',
		`hesprrs_edu_indicators_detail`.`name` as 'opt_detail',
        `hesprrs_edu_indicators_detail`.`edu_indicators_detail_rule` as 'detail_rule',
		`hesprrs_edu_management`.`name` as 'manage',
		`hesprrs_option_strategies`.`name` as 'strate_name',
		`hesprrs_members`.`name` as 'surperviosr',
		`hesprrs_option_strategies`.`aims` as 'strate_aim',
		`hesprrs_projects_summary`.`question` as 'question',
		(CASE `hesprrs_strategies_summary`.`type` WHEN 1 THEN '附檔' ELSE '文字描述' END) as 'type'
		FROM `hesprrs_projects_summary`
		LEFT JOIN `hesprrs_strategies_summary` ON `hesprrs_projects_summary`.`sno_project_summary` = `hesprrs_strategies_summary`.`sno_strategies_summary`
		LEFT JOIN `hesprrs_edu_management` ON `hesprrs_strategies_summary`.`sno_edu_management` = `hesprrs_edu_management`.`sno_edu_management`
		LEFT JOIN `hesprrs_option_strategies` ON `hesprrs_projects_summary`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
		LEFT JOIN `hesprrs_edu_indicators_detail` ON `hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
		LEFT JOIN `hesprrs_edu_indicators` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
		LEFT JOIN `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
		LEFT JOIN `hesprrs_members` ON `hesprrs_option_strategies`.`supervisor_strategies` = `hesprrs_members`.`sno_members`
		WHERE `hesprrs_projects_summary`.`sno_project` in ('".$row['projects_id']."')";
		$resultt = $conn->query($sqll) ;
		$sec_out=array();
		$f=0;
		while($roww = $resultt->fetch_array(MYSQLI_ASSOC)){
			$f=1;
			$lists=array($roww['edu_class'],$roww['edu_opt'],$roww['opt_detail'],$roww['detail_rule'],$roww['manage'],$roww['strate_name'],$roww['surperviosr'],$roww['strate_aim'],$roww['question'],
			$roww['type'],$row['project_name'], $row['paper_num'], $row['act_mm'],);
			fputcsv($fp, $lists);
		}
		if($roww==null&&$f==0){
			$lists=array("","","","","","","","","","", $row['project_name'], $row['paper_num'], $row['act_mm']);
			fputcsv($fp, $lists);
		}
	}
	
	echo json_encode(array(
		'msg' =>'200',
		'url' => $url
	));


	fclose($fp);
}
?>