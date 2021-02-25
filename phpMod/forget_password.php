<?php
use PHPMailer\PHPMailer\PHPMailer;
//Load composer's autoloader
require 'vendor/autoload.php';
require_once 'fig.php';
header('Content-Type: application/json; charset=UTF-8');

@$account = $_POST["account"];
@$email = $_POST["email"];

$sql = "SELECT `sno_members` FROM `hesprrs_members` WHERE  `account` = '" . $account . "' AND `email` = '" . $email . "'";
$result = $conn->query($sql);
$row = $result->fetch_array(MYSQLI_ASSOC);

if ($row['sno_members'] == null) { //帳號或email錯誤
    $out = 0;
    echo json_encode(array(
        'msg' => '201'
    ));
    exit;
} else { //正確
    $out = 1;
    $key = 'benson';
    $sno_members = encryptWithURLdecode($row['sno_members'], $key); //加密
    $sql = "UPDATE `hesprrs_members` SET `psdkey`= '" . $sno_members . "' WHERE `account` ='" . $account . "' AND `email` = '" . $email . "'";
    $conn->query($sql);

    $url = $keyCheckFilePath . $sno_members;

    //寄信
    $mail = new PHPMailer(); //建立新物件
    $mail->SMTPDebug = 2;
    $mail->IsSMTP(); //設定使用SMTP方式寄信
    $mail->SMTPAuth = true; //設定SMTP需要驗證
    $mail->SMTPSecure = "ssl"; // Gmail的SMTP主機需要使用SSL連線
    $mail->Host = "smtp.gmail.com"; //Gamil的SMTP主機
    $mail->Port = 465; //Gamil的SMTP主機的埠號(Gmail為465)。
    $mail->CharSet = "utf-8"; //郵件編碼
    $mail->Username = $uacc; //Gamil帳號
    $mail->Password = $upasswd; //Gmail密碼
    $mail->From = $uacc; //寄件者信箱
    $mail->FromName = "長庚科技大學"; //寄件者姓名
    $mail->Subject = "忘記密碼"; //郵件標題
    $mail->Body = "請點選下列網址來變更密碼: " . $url; //郵件內容(忘記密碼的網頁)
    //$mail->addAttachment('../uploadfile/file/dirname.png', 'new.jpg'); //附件，改以新的檔名寄出
    $mail->IsHTML(true); //郵件內容為html
    $mail->AddAddress($email); //收件者郵件及名稱

    if (!$mail->Send()) {
        echo json_encode(array(
            'msg' => '403',
            'out' => "Error: " . $mail->ErrorInfo,
        ));
    } else {
        echo json_encode(array(
            'msg' => '200',
            'out' => "",
        ));
    }
}
