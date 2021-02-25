<?php
header('Content-Type: application/json; charset=UTF-8');
@$token = $_POST["token"];
include ('fig.php');
if ($token_time == 0) {
    $str = "";
    foreach ($_FILES as &$file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $name = array_search($file, $_FILES); //傳送的參數名稱
            $tempfile = $file['tmp_name'];//初始上傳位置
			setlocale(LC_ALL, 'zh_CN');
			//2019/1/11 改上傳名字
			$t=time();
			$t1=rand(0,10000);

			$last=explode('.',$file['name']);//XXX.csv
			$file['name']=$t.$t1.".".$last[1];//重組名稱
			$dest = $dest_path . $file['name'];

            move_uploaded_file($tempfile, $dest); //將檔案移至指定位置
            if (!$fp = fopen($dest, "r")) { //假如開啟錯誤
                //顯示錯誤
                echo json_encode(array(
                    'msg' => '209',
                ));
                exit;
            } else { //開啟成功

                $size = filesize($dest) + 1; //取得筆數
                $i = 0; //從0筆開始讀取
                $out = "";

                $roles = [];
                $sql = "SELECT `sno_roles`, `name` FROM `hesprrs_roles`";
                $result = $conn->query($sql);
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $sno = $row['sno_roles'];
                    $roles[$sno] = $row['name'];
                }

                while ($temp[$i] = fgetcsv($fp, $size, ",")) { //讀取csv資料給temp陣列

                    /*if ($i == 0) {
                        if (count($temp[$i]) != 5) {
                            echo json_encode(array(
                                'msg' => '401'
                            ));
                            exit;
                        }
                    }*/
                    $flag = 0; //是否重複的指標
                    //判斷帳號是否有重複
                    $sql = "SELECT `account` FROM `hesprrs_members`";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        $acc = $row['account'];
                        if ($temp[$i][0] == $acc) {
                            $flag = 1;
                        }
                    }

                    if ($flag == 0) { //帳號沒有重複
                        $passwd = hash('sha256', $temp[$i][1]); //加密
                        $sno_roles = array_search($temp[$i][3], $roles);

                        $sql = "INSERT INTO `hesprrs_members`(`sno_roles`, `account`, `passwd`, `name`, `email` ) VALUES ('" . $sno_roles . "', '" . $temp[$i][0] . "','" . $passwd . "', '" . $temp[$i][2] . "', '" . $temp[$i][4] . "')";
                        $conn->query($sql);
                    } else { //帳號重複
                        $out = $out . $temp[$i][0] . ",";
                    }
                    $i++;
                }
                fclose($fp); //關閉檔案
            }
            $out = empty($out) ? "" : substr($out, 0, -1); //去除字串最後一個字元
            echo json_encode(array(
                'msg' => '200',
                'out' => $out
            ));
        } else {
            echo json_encode(array(
                'msg' => '209',
            ));
        }
    }
}
