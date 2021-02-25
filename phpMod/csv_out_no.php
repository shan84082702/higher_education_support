<?php 
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$year = $_POST["year"];
include('fig.php');
if($token_time==0){
	$name="ReportSummary(No-teacher)";
	$url = 'phpMod/download/'.$year.$name.'.csv';
	$write_url='download/'.$year.$name.'.csv';
	$fp = fopen($write_url, 'w+');//寫檔
	$lists=array("指標類型","指標項目","項目","衡量基準/計算公式(量化)檢核方式(質化)","教育部管考欄位(A)","執行內容與管考指標", "繳交類型(文字描述或附檔)");
	fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));
	fputcsv($fp, $lists);
	$sql ="SELECT
  `hesprrs_edu_indicators`.`name` AS 'edu_class',
  `hesprrs_edu_indicators_sub`.`name` AS 'edu_opt',
  `hesprrs_edu_indicators_detail`.`name` AS 'opt_detail',
  `hesprrs_edu_management`.`edu_indicators_detail_rule` AS 'edu_rule',
  `hesprrs_edu_management`.`name` AS 'manage',
  `hesprrs_strategies_summary`.`results` AS 'question',
  (CASE `hesprrs_strategies_summary`.`type` WHEN 1 THEN '附檔' ELSE '文字描述' END) as 'type'
	FROM
	  `hesprrs_option_strategies`
	LEFT JOIN
	  `hesprrs_edu_indicators_detail` ON `hesprrs_option_strategies`.`sno_edu_indicators_detail` = `hesprrs_edu_indicators_detail`.`sno_edu_indicators_detail`
	LEFT JOIN
	  `hesprrs_edu_indicators` ON `hesprrs_edu_indicators_detail`.`sno_edu_indicators` = `hesprrs_edu_indicators`.`sno_edu_indicators`
	LEFT JOIN
	  `hesprrs_edu_indicators_sub` ON `hesprrs_edu_indicators_detail`.`edu_indicators_sub` = `hesprrs_edu_indicators_sub`.`sno_edu_indicators_sub`
	LEFT JOIN
	  `hesprrs_strategies_summary` ON `hesprrs_strategies_summary`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	LEFT JOIN
	  `hesprrs_edu_management` ON `hesprrs_strategies_summary`.`sno_edu_management` = `hesprrs_edu_management`.`sno_edu_management`
	WHERE
	  `hesprrs_option_strategies`.`activate_yyy` ='".$year."'";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		
		$lists=array($row['edu_class'],$row['edu_opt'],$row['opt_detail'],$row['edu_rule'],$row['manage'],$row['question'],$row['type']);

		fputcsv($fp, $lists);
		
		
	}
	
	echo json_encode(array(
		'msg' =>'200',
		'url' => $url
	));


	fclose($fp);
}
?>