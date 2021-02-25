<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
require_once 'fig.php';
if ($token_time == 0) {
	$sql1 ="set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'";
    $conn->query($sql1);
	$sql = "SELECT `hesprrs_option_projects`.`sno_option_projects`,`hesprrs_option_projects`.`name` as 'project_name',`sno_source`,`hesprrs_members`.`name`,`hesprrs_messages`.`created_at`,(CASE
            WHEN  `hesprrs_projects_data`.`review_state` IS null THEN '未審核'
            WHEN  `hesprrs_projects_data`.`review_state` =0 THEN '未審核'
						WHEN  `hesprrs_projects_data`.`review_state` =1 THEN '審核中'
						WHEN  `hesprrs_projects_data`.`review_state` =2 THEN '審核完成'
            WHEN  `hesprrs_projects_data`.`review_state` =3 THEN '審核未通過'
            ELSE `hesprrs_projects_data`.`review_state` END) as  'type'
		FROM `hesprrs_messages`
		LEFT JOIN `hesprrs_option_projects` ON `hesprrs_messages`.`sno_projects` = `hesprrs_option_projects`.`sno_option_projects`
		LEFT JOIN `hesprrs_projects_data`   ON `hesprrs_projects_data`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
		LEFT JOIN `hesprrs_members` ON `hesprrs_messages`.`sno_source` = `hesprrs_members`.`sno_members`
		WHERE `hesprrs_messages`.`sno_target` ='" . $id . "'
		GROUP BY `hesprrs_option_projects`.`name`
		ORDER BY `hesprrs_messages`.`created_at` DESC";
    $result = $conn->query($sql);
    $out = array();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $out1['sno_option_projects'] = $row['sno_option_projects'];
        $out1['project_name'] = $row['project_name'];
        $out1['sno_source'] = $row['sno_source'];
        $out1['name'] = $row['name'];
        $out1['created_at'] = $row['created_at'];
        $out1['type'] = $row['type'];
		$sqll ="SELECT   
		`hesprrs_option_projects`.`sno_option_projects`,
		CONCAT(`hesprrs_option_main`.`option_main_order`, '-', `hesprrs_option_sub`.`option_sub_order`, '-', `hesprrs_option_strategies`.`option_strategies_order`) AS 'order'
		FROM `hesprrs_option_projects`
		LEFT JOIN	`hesprrs_option_strategies` ON `hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
		LEFT JOIN	`hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
		LEFT JOIN	`hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main` 
		WHERE `hesprrs_option_projects`.`sno_option_projects` = '".$out1['sno_option_projects']."'";
		$resultt = $conn->query($sqll) ;
		$roww = $resultt->fetch_array(MYSQLI_ASSOC);
		$out1['order']=$roww['order'];

        array_push($out, $out1);
        unset($out1);
    }

    echo json_encode(array(
        'msg' => '200',
        'out' => $out,
    ));
}
