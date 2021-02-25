<?php
require_once 'fig.php';
header('Content-Type: application/json; charset=UTF-8');
@$passwd = $_POST["passwd"];
@$repasswd = $_POST["repasswd"];
@$psdkey = $_POST["psdkey"];

if ($passwd == $repasswd) {
    $key = 'benson';
    $sql = "SELECT `sno_members` FROM `hesprrs_members` WHERE  `psdkey` ='" . $psdkey . "'";
    $result = $conn->query($sql);
    $out = 2;

    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['sno_members'] != null) {
        $sno_members = decryptWithURLdecode($psdkey, $key); //解密
        $passwd = hash('sha256', $passwd); //加密
        $sql = "UPDATE `hesprrs_members` SET `passwd`='" . $passwd . "',`psdkey`='' WHERE `sno_members` ='" . $sno_members . "'";
        $conn->query($sql);
        $out = 1;

    } else {
        $out = 0;
    }

    echo json_encode(array(
        'msg' => '200',
        'out' => $out,
    ));
} else {
    echo json_encode(array(
        'msg' => '200',
        'out' => 0,
    ));
}
