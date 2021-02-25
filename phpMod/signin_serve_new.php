<?php
require_once 'fig.php';
header('Content-Type: application/form-data; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
@$account = $_POST["account"]; //取得 account POST 值
@$passwd = $_POST["passwd"]; //取得 passwd POST 值

if ($account != null && $passwd != null) { //如果 account 和 passwd 都有填寫

    $key = 'benson';
    $sql = "select * from `hesprrs_members`";
    $resultt = $conn->query($sql);
    $tag = 0;
    $passwd = hash('sha256', $passwd); //加密

    while ($row = $resultt->fetch_array(MYSQLI_ASSOC)) {

        if ($row['account'] == $account && $row['passwd'] == $passwd) {
            $tag = 1;
        }

    }

    if ($tag == 1) {
        $sql = "SELECT `sno_members`,hesprrs_members.`name` as 'name',hesprrs_members.sno_roles, hesprrs_roles.name as 'role_name',`project_edit`,`strategiest_summary`,`member_manage`,`data_manage`
		FROM `hesprrs_members` LEFT JOIN hesprrs_roles ON hesprrs_members.sno_roles = hesprrs_roles.sno_roles WHERE `account` = '" . $account . "' AND `passwd` = '" . $passwd . "'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $encode_token['sno_members'] = $row['sno_members'];
            $encode_token['now_time'] = date("ymdHis");
            $req["name"] = $row["name"];
            $req["role_name"] = $row["role_name"];

            $req["token"] = json_encode($encode_token); //token用json包起來
            $req["project_edit"] = $row["project_edit"];
            $req["strategiest_summary"] = $row["strategiest_summary"];
            $req["member_manage"] = $row["member_manage"];
            $req["data_manage"] = $row["data_manage"];

            if ($row["sno_roles"] == 1) {
                $sql2 = "SELECT `day`,`isactivate` FROM `hesprrs_alert_func` WHERE `name` = '催繳緩衝天數'";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_array(MYSQLI_ASSOC);
                if ($row2['isactivate'] == 1) {
                    $sql3 = "SELECT `hesprrs_projects_data`.`sno_option_projects`,`name`,`activate_yyy`,`activate_date` ,`supervisor_project` , (CASE
					WHEN  `hesprrs_projects_data`.`review_state` IS null THEN '未審核'
					WHEN  `hesprrs_projects_data`.`review_state` =0 THEN '未審核'
					WHEN  `hesprrs_projects_data`.`review_state` =1 THEN '審核中'
					ELSE '審核完成' END) as  'type' FROM `hesprrs_option_projects`
					LEFT JOIN `hesprrs_projects_data` ON `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_projects_data`.`sno_option_projects`";
                    $result3 = $conn->query($sql3);
                    $da = array();
                    while ($row3 = $result3->fetch_array(MYSQLI_ASSOC)) {
                        $activate_date = $row3['activate_date'];

                        $activate_date_array = explode("-", $activate_date); //月份分割
                        $num = count($activate_date_array); //數有幾個月份(1或2)
                        $num--;
                        $activate_date_array[$num]++; //月份+1(取後面的月份)
                        $now_times = strtotime("now"); //現在時間
                        $year = $row3['activate_yyy'] + 1911;
                        $need_time = strtotime($year . "-" . $activate_date_array[$num] . "-1 1:00:00");

                        $tt = $now_times - $need_time; //時間相減
                        $s = $tt / 24 / 60 / 60; //天數
                        if ($s > 0 && ($s % $row2['day'] == 0)) { //需不需要寄信
                            $sql4 = "INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`)
							VALUES ('" . $row3['type'] . "' ,'請盡速將成果報告書審核完畢','" . $encode_token['sno_members'] . "','" . $row3['supervisor_project'] . "', '" . $row3['sno_option_projects'] . "')";
                            $conn->query($sql4);
                        }
                    }

                }
            }

            $req["token"] = encrypt($req["token"], $key); //token加密
            $outt = $req;
        }
        $sql = "UPDATE `hesprrs_members` SET  `token`= '" . $req["token"] . "' WHERE `sno_members`= " . $encode_token['sno_members'];
        $result = $conn->query($sql);
        $msg = '200';
    } else {
        $msg = '202';
    }

    echo json_encode(array(
        'msg' => $msg,
        'outt' => $outt,
    ), JSON_UNESCAPED_UNICODE);
} else {
    //回傳 errorMsg json 資料
    echo json_encode(array(
        'msg' => '202',
    ), JSON_UNESCAPED_UNICODE);
}
