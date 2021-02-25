<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$need_reply = $_POST["need_reply"];//2:審查完成,1:已審查待回復,  #0:已審查不用回復
@$message = $_POST["message"];
// 審查人 活動 回覆狀態 文字 
include('fig.php');
if($token_time==0){
	
	$sql ="SELECT   
	`hesprrs_reviewed`.`sno_reviewed`,
	`hesprrs_reviewed`.`sno_option_projects`,
	`hesprrs_option_projects`.`name`,
	CONCAT(`hesprrs_option_main`.`option_main_order`, '-', `hesprrs_option_sub`.`option_sub_order`, '-', `hesprrs_option_strategies`.`option_strategies_order`) AS 'order'
	FROM `hesprrs_reviewed`
	LEFT JOIN `hesprrs_option_projects` ON `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_reviewed`.`sno_option_projects`
	LEFT JOIN	`hesprrs_option_strategies` ON `hesprrs_option_projects`.`sno_option_strategies` = `hesprrs_option_strategies`.`sno_option_strategies`
	LEFT JOIN	`hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
	LEFT JOIN	`hesprrs_option_main` ON `hesprrs_option_sub`.`sno_option_main` = `hesprrs_option_main`.`sno_option_main` 
	WHERE `sno_members` = '".$id."' AND `hesprrs_reviewed`.`sno_option_projects` = '".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$name=$row['name'];
	$order=$row['order'];
	if (count($row['sno_reviewed'])>0 ){
		//update
		$sno_reviewed=$row['sno_reviewed'];
		$sql ="UPDATE `hesprrs_reviewed` SET `need_reply` = '".$need_reply."' WHERE `sno_reviewed` ='".$sno_reviewed."'";
		$result = $conn->query($sql) ;
	}
	else {
		//insert
		$sql ="INSERT INTO `hesprrs_reviewed` (`sno_members`, `sno_option_projects`, `status`, `need_reply`) VALUES ('".$id."', '".$sno_option_projects."', '1', '".$need_reply."');
		SELECT LAST_INSERT_ID() as 'sno_reviewed';";
		$conn->multi_query($sql);
		$result=$conn->store_result();
		$conn->next_result();
		$result=$conn->store_result();
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$sno_reviewed=$row['sno_reviewed'];
	}
	//更新報告書狀態:已審查待回復
	if($need_reply==1){
		$sql ="UPDATE `hesprrs_projects_data` SET `review_state` = '0' WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."'";
		$result = $conn->query($sql) ;
		$sql ="UPDATE `hesprrs_flow` SET `flow_status` = '0' WHERE `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$result = $conn->query($sql) ;
	}
	// new message
	$sql ="INSERT INTO `hesprrs_reviewed_message` (`sno_reviewed`, `sno_members`, `message`) VALUES ('".$sno_reviewed."', '".$id."', '".$message."')";
    $result = $conn->query($sql) ;
    //寄信
	$url =$loginFilePath;
    $sql ="SELECT `hesprrs_members`.`email` FROM hesprrs_projects_editor LEFT JOIN hesprrs_members ON `hesprrs_members`.`sno_members` = `hesprrs_projects_editor`.`sno_members` 
    WHERE `hesprrs_projects_editor`.`sno_option_projects` = '".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
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
        $mail->Subject = "審查通知"; //郵件標題
		if($need_reply==2)
			$need_reply_message="審查完成";
		else if($need_reply==1)
			$need_reply_message="診斷小組委員已審查，對成果報告書有意見，請您修正報告書後回覆。";
		else if($need_reply==0)
			$need_reply_message="已審查不用回復";
		if($message==""||$message==null)
			$long_message="";
		else
			$long_message="<br>審查人員意見:".$message;
		$name_message="高教深耕計畫成果報告書 ".$order." 活動名稱:".$name;
        $mail->Body = $name_message."<br>".$need_reply_message.$long_message."<br>請登入系統查看回覆: ".$url ; //郵件內容
        $mail->IsHTML(true); //郵件內容為html
        $mail->AddAddress($row['email']); //收件者郵件及名稱
    
		$mail->Send();
        /*if (!$mail->Send()) {
            echo json_encode(array(
                'msg' => '403',
                'out' => "Error: " . $mail->ErrorInfo,
            ));
            exit;
        } */
    }


	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>