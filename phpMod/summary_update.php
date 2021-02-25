<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
@$results = $_POST["results"];
require_once 'fig.php';
if ($token_time == 0) {
    $result = json_decode($results, true);
    $num = count($result['sno_option_strategies_summary']);

    if ($num >= 1) {
        $result['result'][0]=addslashes($result['result'][0]);
        $sql = "UPDATE `hesprrs_strategies_summary` SET `results`='" . $result['result'][0] . "'  WHERE `sno_strategies_summary` = '" . $result['sno_option_strategies_summary'][0] . "'";
        $conn->query($sql);
    }
    if ($num > 1) {
        for ($i = 1; $i < $num; $i++) {
            $result['result'][$i]=addslashes($result['result'][$i]);
            $sql = "UPDATE `hesprrs_strategies_summary` SET `results`='" . $result['result'][$i] . "'  WHERE `sno_strategies_summary` = '" . $result['sno_option_strategies_summary'][$i] . "'";
            $conn->query($sql);
        }
    }
    foreach ($_FILES as &$file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $tempfile = $file['tmp_name'];
            $t = time();
            $t1 = rand(0, 10000);
            $last = explode('.', $file['name']);
            $file['name'] = $t . $t1 . "." . $last[1];
            $dest = $dest_path . $file['name'];
            $dbdest = $dbdest_path . $file['name'];
            $name = array_search($file, $_FILES); //傳送的參數名稱
            move_uploaded_file($tempfile, $dest); //將檔案移至指定位置
			
			$a_number=substr($name,1);//去除第一個數
			
            $sql = "UPDATE `hesprrs_strategies_summary` SET `results`='" . $dbdest . "'  WHERE `sno_strategies_summary` ='" . $a_number . "'";
            $conn->query($sql);
			
        } else {
            echo json_encode(array(
                'msg' => '205',
                'out' => $file,
            ));
            exit;
        }
    }

    echo json_encode(array(
        'msg' => '200'
    ));
}
