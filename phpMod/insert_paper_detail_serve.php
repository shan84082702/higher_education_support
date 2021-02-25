<?php
@$token = $_POST["token"];
@$pid = $_POST["pid"];
include('fig.php');
@$activate_date= $_POST["activate_date"];
@$activate_location= addslashes($_POST["activate_location"]);
@$paricipant_number= addslashes($_POST["paricipant_number"]);
@$cost= addslashes($_POST["cost"]);
@$description= addslashes($_POST["description"]);
@$isongante= addslashes($_POST["isongante"]);
@$isinresulf=addslashes($_POST["isinresulf"]);
@$q_result= $_POST["q_result"];
$results=json_decode($q_result,true);
@$reason= addslashes($_POST["reason"]);
@$cooperation_project= addslashes($_POST["setcooperation_project"]);
@$target= addslashes($_POST["target"]);
@$recommendations= addslashes($_POST["recommendations"]);
@$delimg=$_POST["delimg"];



if($token_time==0){
	$s1="";
	$s2="";
	
	foreach ($_FILES as &$file) {
		if ($file['error'] === UPLOAD_ERR_OK){
			$key = array_search($file, $_FILES);//在$_FILES尋找$file
			$tempfile = $file['tmp_name'];
			
			//2019/1/11 改上傳名字
			$t=time();
			$t1=rand(0,100000);
			$last=explode('.',$file['name']);
			$file['name']=$t.$t1.".".$last[1];
			$dest = $dest_path. $file['name'];
			$dbdest = $dbdest_path.$file['name'];
			
			move_uploaded_file($tempfile, $dest);//將檔案移至指定位置
			
			$str_sec = str_split($key);//字串轉陣列
			$a_number=substr($key,1);//去除第一個數
			if($key=='sig')
				$s1=$dbdest;//",`sign`='".$dbdest."'"
			else if($key=='annex')
				$s2=$dbdest;//",`annex`='".$dbdest."'"
			else if($str_sec[0]=='q'){//管考指標執行內容(照片)
				$sql ="UPDATE `hesprrs_projects_summary` SET `results`='".$dbdest."' WHERE sno_project_summary ='".$a_number."'";
				$result = $conn->query($sql) ;
			}
			else{//活動照片
				$sql ="INSERT INTO `hesprrs_projects_images`(`sno_option_projects`, `path`) VALUES ('".$pid."','".$dbdest."')";
				$result = $conn->query($sql) ;
			}
		} 
		else {
			echo json_encode(array(
				'msg' => '205',
				'out' => $file
			));
			exit;
		}
	}
	
	$num=count($results);//管考指標執行內容(文字)
	for($i=0;$i<$num;$i++){
		$results["result"][$i]=addslashes($results["result"][$i]);
		$sql ="UPDATE `hesprrs_projects_summary` SET `results`='".$results["result"][$i]."' WHERE sno_project_summary ='".$results["qid"][$i]."'";
		$result = $conn->query($sql) ;
	}
	$sql ="DELETE FROM `hesprrs_projects_images` WHERE `sno_images` in (".$delimg.")";
	$result = $conn->query($sql) ;

	
	$sql ="INSERT INTO `hesprrs_projects_data` (`date_range`, `location`,`paricipant_number`,`cost`,`description`,`isongante`
	,`isinresulf`,`unable_execution_reason`, `cooperation_project`,`target`,`recommendations`,`sign`,`annex`,`sno_option_projects`) 
	VALUES ('".$activate_date."','".$activate_location."','".$paricipant_number."','".$cost."','".$description."','".$isongante."','".$isinresulf
	."','".$reason."','".$cooperation_project."','".$target."','".$recommendations."','".$s1."','".$s2."','".$pid."')";
	$result = $conn->query($sql) ;
	exit;
}

?>