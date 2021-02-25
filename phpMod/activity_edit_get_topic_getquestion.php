<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$sno_strategies_summary = $_POST["sno_strategies_summary"];
require_once 'fig.php';
if ($token_time == 0) {

    $sql = "SELECT `question` FROM `hesprrs_strategies_summary` WHERE `sno_strategies_summary` = '" . $sno_strategies_summary . "'";
    $result = $conn->query($sql);
    $out = array();
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $out1['question'] = $row['question'];
        array_push($out, $out1);
        unset($out1);
    }
    echo json_encode(array(
        'msg' => '200',
        'out' => $out,
    ));
}
