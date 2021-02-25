<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_main = $_POST["sno_option_main"];
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT
	  `hesprrs_option_projects`.`sno_option_projects`,
	  concat(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`) as 'order',
	  `hesprrs_option_projects`.`name`,
	  `hesprrs_projects_data`.`updated_at`,
	  `hesprrs_option_projects`.`activate_date`,
	(
	CASE WHEN `hesprrs_projects_data`.`isfilledresult` = '0' THEN '否' WHEN `hesprrs_projects_data`.`isfilledresult` = '1' THEN '是'
	END
	) AS 'isfilledresult',
	(
	  CASE WHEN `hesprrs_projects_data`.`review_state` IS NULL THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 0 THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 1 THEN '審核中' WHEN `hesprrs_projects_data`.`review_state` = 2 THEN '審核完成' WHEN `hesprrs_projects_data`.`review_state` = 3 THEN '審核未通過' ELSE `hesprrs_projects_data`.`review_state`
	END
	) AS 'review_state'
	FROM
	  `hesprrs_option_projects`
	LEFT JOIN
	  `hesprrs_projects_data` ON `hesprrs_projects_data`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
	LEFT JOIN
	  `hesprrs_option_strategies` ON `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_projects`.`sno_option_strategies`
	LEFT JOIN
	  `hesprrs_option_sub` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_strategies`.`sno_option_sub`
	LEFT JOIN
	  `hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main`
	WHERE
	`hesprrs_projects_data`.`review_state` = '2' AND
	  `hesprrs_option_main`.`sno_option_main` = '".$sno_option_main."' AND
	  `hesprrs_option_projects`.`sno_option_projects` not IN(
	  SELECT
		`hesprrs_reviewed`.`sno_option_projects`
	  FROM
		`hesprrs_reviewed`
	  WHERE
		(`hesprrs_reviewed`.`status` != '0' AND `hesprrs_reviewed`.`sno_members` = '".$id."') OR
		(`hesprrs_reviewed`.`status` != '0' AND (SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1')
		) ";
	$result = $conn->query($sql) ;
	$out1=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $out['sno_option_projects']=$row['sno_option_projects'];
        $out['order']=$row['order'];
		$out['name']=$row['name'];
		$out['updated_at']=$row['updated_at'];
		$out['activate_date']=$row['activate_date'];
		$out['isfilledresult']=$row['isfilledresult'];
		$out['review_state']=$row['review_state'];
		array_push($out1,$out);
		unset($out);
    }
    /*
	$sql ="SELECT
	  `hesprrs_option_projects`.`sno_option_projects`,
	  `hesprrs_option_projects`.`name`,
	  `hesprrs_projects_data`.`updated_at`,
	  `hesprrs_option_projects`.`activate_date`,
	(
	CASE WHEN `hesprrs_projects_data`.`isfilledresult` = '0' THEN '否' WHEN `hesprrs_projects_data`.`isfilledresult` = '1' THEN '是'
	END
	) AS 'isfilledresult',
	(
	  CASE WHEN `hesprrs_projects_data`.`review_state` IS NULL THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 0 THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 1 THEN '審核中' WHEN `hesprrs_projects_data`.`review_state` = 2 THEN '審核完成' WHEN `hesprrs_projects_data`.`review_state` = 3 THEN '審核未通過' ELSE `hesprrs_projects_data`.`review_state`
	END
	) AS 'review_state',
    (
        CASE WHEN `hesprrs_reviewed`.`need_reply`='0' THEN '否' WHEN `hesprrs_reviewed`.`need_reply`='1' THEN '是'
    END
    ) AS 'need_reply'
	FROM
      `hesprrs_reviewed`,
	  `hesprrs_option_projects`
	LEFT JOIN
	  `hesprrs_projects_data` ON `hesprrs_projects_data`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
	LEFT JOIN
	  `hesprrs_option_strategies` ON `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_projects`.`sno_option_strategies`
	LEFT JOIN
	  `hesprrs_option_sub` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_strategies`.`sno_option_sub`
	LEFT JOIN
	  `hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main`
	WHERE
      `hesprrs_option_main`.`sno_option_main` = '".$sno_option_main."' AND `hesprrs_option_projects`.`sno_option_projects`=`hesprrs_reviewed`.`sno_option_projects`";
    */
    
    $sql="SELECT
	  `hesprrs_reviewed`.`sno_reviewed`,
	  concat(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`) as 'order',
	  `hesprrs_option_projects`.`sno_option_projects`,
	  `hesprrs_option_projects`.`name`,
	  `hesprrs_projects_data`.`updated_at`,
	  `hesprrs_option_projects`.`activate_date`,
	(
	CASE WHEN `hesprrs_projects_data`.`isfilledresult` = '0' THEN '否' WHEN `hesprrs_projects_data`.`isfilledresult` = '1' THEN '是'
	END
	) AS 'isfilledresult',
	(
	  CASE WHEN `hesprrs_projects_data`.`review_state` IS NULL THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 0 THEN '未審核' WHEN `hesprrs_projects_data`.`review_state` = 1 THEN '審核中' WHEN `hesprrs_projects_data`.`review_state` = 2 THEN '審核完成' WHEN `hesprrs_projects_data`.`review_state` = 3 THEN '審核未通過' ELSE `hesprrs_projects_data`.`review_state`
	END
	) AS 'review_state',
    (
        CASE WHEN `hesprrs_reviewed`.`need_reply`='0' THEN '已審查不用回復' WHEN `hesprrs_reviewed`.`need_reply`='1' THEN '已審查待回復' WHEN `hesprrs_reviewed`.`need_reply`='2' THEN '審查完成'
    END
    ) AS 'need_reply'
	FROM
      `hesprrs_reviewed`
	LEFT JOIN
      `hesprrs_option_projects` ON `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_reviewed`.`sno_option_projects`
	LEFT  JOIN
	  `hesprrs_projects_data` ON `hesprrs_projects_data`.`sno_option_projects` = `hesprrs_option_projects`.`sno_option_projects`
	LEFT JOIN
	  `hesprrs_option_strategies` ON `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_projects`.`sno_option_strategies`
	LEFT JOIN
	  `hesprrs_option_sub` ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_strategies`.`sno_option_sub`
	LEFT JOIN
	  `hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main`
	WHERE
	  `hesprrs_option_main`.`sno_option_main` = '".$sno_option_main."' AND `hesprrs_option_projects`.`sno_option_projects` IN(
			SELECT
			`hesprrs_reviewed`.`sno_option_projects`
			FROM `hesprrs_reviewed`
			WHERE (`hesprrs_reviewed`.`status` != '0' AND `hesprrs_reviewed`.`sno_members` = '".$id."') OR
			(`hesprrs_reviewed`.`status` != '0' AND (SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1')) AND
	(`hesprrs_reviewed`.`sno_members` = '".$id."' OR (SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1')";

	$result = $conn->query($sql) ;
	$out2=array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$out['sno_reviewed']=$row['sno_reviewed'];
        $out['sno_option_projects']=$row['sno_option_projects'];
        $out['order']=$row['order'];
		$out['name']=$row['name'];
		$out['updated_at']=$row['updated_at'];
		$out['activate_date']=$row['activate_date'];
		$out['isfilledresult']=$row['isfilledresult'];
		$out['review_state']=$row['review_state'];
		$out['need_reply']=$row['need_reply'];
		array_push($out2,$out);
		unset($out);
	}
	
	echo json_encode(array(
		'msg' =>'200',
		'out1' => $out1,
		'out2' => $out2
	));
}
?>