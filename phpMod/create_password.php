<?php
include('sqll.php');
header('Content-Type: application/form-data; charset=UTF-8'); //設定資料類型為 json，編碼 utf-8
@$passwd = $_POST["passwd"]; //取得 account POST 值

	
function encrypt($input, $key)
{
	$data=openssl_encrypt($input,'AES-128-ECB',$key,OPENSSL_RAW_DATA);
	$data=base64_encode($data);
	return $data;
}
$passwd=hash('sha256',$passwd );//加密


echo json_encode(array(
	'msg' =>$passwd
));

?>