<?php
date_default_timezone_set("Asia/Taipei");
//資料庫
$_servername = 'localhost';
$_username = 'cubecube';
$_password = 'pupuyueyue';
$_dbname = 'their_hesp_rrs';
$conn = new mysqli($_servername, $_username, $_password, $_dbname);

// email account
$uacc = "widelab@widelab.org"; ////寄件者帳號
$upasswd = "widelab35795709"; //寄件者密碼
$domain = "spark5.widelab.org";
$keyCheckFilePath = "http://spark5.widelab.org/~csie062452/cgust/forget_pwd.html?psdkey=";
$loginFilePath = "http://spark5.widelab.org/~csie062452/cgust/login.html";

// path info
$dest_path = 'upload/';
$dbdest_path = 'phpMod/upload/';

/////////
//@$token = $_POST["token"];
////////


if (isset($token) && !empty($token)) {

	
    $key = 'benson';
    $de_token = decrypt($token, $key); //token解密
    $deco = json_decode($de_token, true);
    $id = $deco['sno_members'];
    $oldtime = $deco['now_time'];
    $now_time = date("ymdHis");
    $time = $now_time - $oldtime;


    //判斷token是否存在
    $num_rows = 0;
    $sql = "SELECT `token` FROM `hesprrs_members` where `token` = '" . $token . "'";
    $result = $conn->query($sql);
	$row = $result->fetch_array(MYSQLI_ASSOC);

    if (count($row) > 0) { //token存在
        if ($time > 1000000) //token 過期
        {
            $sql = "update `hesprrs_members` set `token`=null where `sno_members`='" . $id . "'";
            $result = $conn->query($sql);
			//要可以重新登入???

            $token_time = 1;
            echo json_encode(array(
                'msg' => '204',
                'de_token' => $de_token,
                'time' => $time,
                'oldtime' => $oldtime,
            ));
            exit;
        } else {
            $token_time = 0;
        }
    } else {
        $token_time = 1;
        echo json_encode(array(
            'msg' => '206',
        ));
        exit;
    }
}

function decrypt($input, $key)
{
    $output = openssl_decrypt(base64_decode($input), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return $output;
}
function decryptWithURLdecode($input, $key)
{
    $temp = urldecode($input);
    $output = openssl_decrypt(base64_decode($temp), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return $output;
}
function encrypt($input, $key)
{
    $data = openssl_encrypt($input, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    $data = base64_encode($data);
    return $data;
}
function encryptWithURLdecode($input, $key)
{
    $data = openssl_encrypt($input, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    $data = base64_encode($data);
    return urlencode($data);
}