<?php

@$token = $_POST["token"];
require_once 'fig.php';
header('Content-Type: application/json; charset=UTF-8');

if ($token != null) {

    $oldtoken = $token;
    $token = decrypt($token, $key); //token解密
    $deco = json_decode($token, true);
    $id = $deco['sno_members'];
    $f = 0;
    $sql = "SELECT * FROM `hesprrs_members`";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        if ($row['token'] == $oldtoken) {
            $f = 1;
        }

    }
    if ($f == 1) {
        $sql = "update `hesprrs_members` set `token`=null where `sno_members`=" . $id;
        $resultt = $conn->query($sql);
        echo json_encode(array(
            'msg' => '200',
        ));
    } else {
        echo json_encode(array(
            'msg' => '203',
        ));
    }
} else {
    echo json_encode(array(
        'msg' => '203',
    ));
}
