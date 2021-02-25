<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$year = $_POST["year"];
require_once 'fig.php';

if ($token_time == 0) {
    $sql = "SELECT DISTINCT `hesprrs_option_main`.`sno_option_main` as 'main_pk' ,CONCAT(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_main`.`name`) as 'main_name'
	FROM `hesprrs_option_main`WHERE `hesprrs_option_main`.`activate_yyy` =" . $year;
    $result = $conn->query($sql);
    $out = array();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $out1['main_pk'] = $row['main_pk'];
        $out1['main_name'] = $row['main_name'];
        array_push($out, $out1);
        unset($out1);
    }
    echo json_encode(array(
        'msg' => '200',
        'out' => $out,
    ));
}
