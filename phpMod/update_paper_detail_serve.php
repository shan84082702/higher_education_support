<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$pid = $_POST["pid"];
require_once 'fig.php';
@$activate_date = $_POST["activate_date"];
@$activate_location =addslashes($_POST["activate_location"]) ;
@$paricipant_number = addslashes($_POST["paricipant_number"]);
@$cost = addslashes($_POST["cost"]);
@$description = addslashes($_POST["description"]);
@$isongante = addslashes($_POST["isongante"]);
@$isinresulf = addslashes($_POST["isinresulf"]);
@$results = $_POST["q_result"];
$results = json_decode($results, true);
@$reason = addslashes($_POST["reason"]);
@$cooperation_project = addslashes($_POST["setcooperation_project"]);
@$target = addslashes($_POST["target"]);
@$recommendations = addslashes($_POST["recommendations"]);
@$delimg = $_POST["delimg"];
@$img_description = $_POST["img_description"];
$img_description = json_decode($img_description, true);
@$change_img = $_POST["change_img"];
$change_img = json_decode($change_img, true);


if ($token_time == 0) {
    $s1 = "";
    $s2 = "";
	
	$arraycount = 0;
    foreach ($_FILES as &$file) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $key = array_search($file, $_FILES); //在$_FILES尋找$file
            $tempfile = $file['tmp_name'];
            $t = time();
            $t1 = rand(0, 10000);
            $last = array_pop(explode('.', $file['name']));//副檔名
            $file['name'] = $t . $t1 . "." . $last;
            $dest = $dest_path . $file['name'];
            $dbdest = $dbdest_path . $file['name'];

            move_uploaded_file($tempfile, $dest); //將檔案移至指定位置

			$str_sec = str_split($key);//字串轉陣列
			$a_number=substr($key,1);//去除第一個數
            if ($key == 'sig')
                $s1 =",`sign`='".$dbdest."'";
			else if ($key == 'annex')
                $s2 = ",`annex`='".$dbdest."'";
			else if($str_sec[0]=='q'){//管考指標執行內容(照片)
				$sql ="UPDATE `hesprrs_projects_summary` SET `results`='".$dbdest."' WHERE sno_project_summary ='".$a_number."'";
				$result = $conn->query($sql) ;
			}
            else {
				//新增圖片和敘述
				$sql ="INSERT INTO `hesprrs_projects_images`(`sno_option_projects`, `path`,`img_describe`) VALUES ('".$pid."','".$dbdest."','".$img_description[$arraycount]."')";
                //$sql = "INSERT INTO `hesprrs_projects_images`(`sno_option_projects`, `path`,`img_describe`) VALUES ('" . $pid . "','" . $dbdest . "','".$key."')";
                $result = $conn->query($sql);
				$arraycount = $arraycount +1;
            }
        } else {
            echo json_encode(array(
                'msg' => '205',
                'out' => $file
            ));
        }
    }

    $num = count($results["result"]);
    for ($i = 0; $i < $num; $i++) {
        $results["result"][$i]=addslashes($results["result"][$i]);
        $sql = "UPDATE `hesprrs_projects_summary` SET `results`='".$results["result"][$i]."' WHERE sno_project_summary ='".$results["qid"][$i]."'";
        $result = $conn->query($sql);
    }
	//刪除圖片跟敘述
    $sql = "DELETE FROM `hesprrs_projects_images` WHERE `sno_images` in (" . $delimg . ")";
    $result = $conn->query($sql);
	//更改圖片敘述
	$num = count($change_img);
    for ($i = 0; $i < $num; $i++) {
        $change_img[$i]["img_des"]=addslashes($change_img[$i]["img_des"]);
        $sql = "UPDATE `hesprrs_projects_images` SET `img_describe`='" . $change_img[$i]["img_des"] ."' WHERE sno_images ='" . $change_img[$i]["mid"] . "'";
        $result = $conn->query($sql);
    } 
    $sql = "UPDATE `hesprrs_projects_data` SET `date_range`='" . $activate_date . "',`location`='" . $activate_location . "',`paricipant_number`='"
        . $paricipant_number . "',`cost`='" . $cost . "',`description`='" . $description . "',`isongante`='" . "$isongante" . "',`isinresulf`='" . $isinresulf . "',`unable_execution_reason`='"
        . $reason . "', `cooperation_project`='" . $cooperation_project . "',`target`='" . $target . "',`recommendations`='" . $recommendations . "' " . $s1 . $s2 . " WHERE `sno_option_projects` = '" . $pid . "'";
    $result = $conn->query($sql);
    exit;
}
