<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$sno_members = $_POST["sno_members"];
@$message = addslashes($_POST["message"]);//內容
@$sender_name = $_POST["sender_name"];
$message="退件內容 : ".$message;
$in_message=$message;//站內信訊息
include('fig.php');
if($token_time==0){
	//報告書資訊
	$sql ="SELECT
	CONCAT(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`) AS 'order',
	`hesprrs_option_projects`.`sno_option_projects`,
	`hesprrs_option_projects`.`name`
	FROM hesprrs_option_projects
	LEFT JOIN hesprrs_option_strategies ON `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_projects`.`sno_option_strategies`
	LEFT JOIN hesprrs_option_sub ON `hesprrs_option_sub`.`sno_option_sub` = `hesprrs_option_strategies`.`sno_option_sub`
	LEFT JOIN hesprrs_option_main ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main`
	WHERE `hesprrs_option_projects`.`sno_option_projects` = '".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$order=$row['order'];
	$name=$row['name'];
	$message='老師您好,有關高等教育深耕計畫 '.$order.' '.$name.' 活動成果報告書，已由'.$sender_name.'退件<br>'.$message.'，懇請進入平台後<br>'.$loginFilePath.'<br>請依下列附件pdf檔所列步驟逐項執行。';
	//判斷是否退到可編輯人員
	if($sno_members==0){//退給可編輯人員
		//報告書狀態為未審核
		$sql ="UPDATE hesprrs_projects_data SET review_state = '0'
		WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."'";
		$conn->query($sql) ;
		//其他人全部變成未審核狀態
		$sql ="UPDATE hesprrs_flow SET flow_status = '0' WHERE sno_option_projects ='".$sno_option_projects."'";
		$conn->query($sql) ;
		
		//所有可編輯人員都要被寄信//////////////////////////////////////////////////////////////////////////////////////
		//觀看可編輯人員email
		$sql ="SELECT DISTINCT
		`hesprrs_members`.`sno_members`,
		`hesprrs_members`.`name`,
		`hesprrs_members`.`email`
		FROM
		hesprrs_projects_editor
		LEFT JOIN
		hesprrs_members ON `hesprrs_members`.`sno_members` = `hesprrs_projects_editor`.`sno_members`
		WHERE
		`hesprrs_projects_editor`.`sno_option_projects` = '".$sno_option_projects."'
		UNION
		SELECT
		`hesprrs_members`.`sno_members`,
		`hesprrs_members`.`name`,
		`hesprrs_members`.`email`
		FROM
		hesprrs_option_projects
		LEFT JOIN
		hesprrs_members ON `hesprrs_members`.`sno_members` = `hesprrs_option_projects`.`supervisor_project`
		WHERE
		`hesprrs_option_projects`.`sno_option_projects` = '".$sno_option_projects."'";
		$result = $conn->query($sql) ;
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			//站外信
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
			$mail->Subject = "高教深耕計畫成果報告書審核通知"; //郵件標題
			$mail->Body = $message; //郵件內容
			$mail->addAttachment('flow/flow.pdf', 'flow.pdf'); //附件，改以新的檔名寄出
			$mail->IsHTML(true); //郵件內容為html
			$mail->AddAddress($row['email']); //收件者郵件及名稱
			$mail->Send();
			
			//站內信
			$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) VALUES ('審核中' ,'".$in_message."','".$id."','".$row['sno_members']."', '".$sno_option_projects."')";
			$conn->query($sqll) ;
		}
	}
	else{//如果不是可編輯人員
		//看退件者幾號
		$sql ="SELECT `hesprrs_flow`.`flow_number` FROM hesprrs_flow WHERE
		`hesprrs_flow`.`sno_members` = '".$id."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$flow_number_reject=$row['flow_number'];
		//看被退件者幾號
		$sql ="SELECT `hesprrs_flow`.`flow_number` FROM hesprrs_flow WHERE
		`hesprrs_flow`.`sno_members` = '".$sno_members."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$flow_number_be_reject=$row['flow_number'];
		//被退件者狀態變2
		$sql ="UPDATE hesprrs_flow SET flow_status = '2' WHERE
		`hesprrs_flow`.`sno_members` = '".$sno_members."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$conn->query($sql) ;
		//大於被退件者狀態都變0
		$sql ="UPDATE hesprrs_flow SET flow_status = '0'
		WHERE sno_option_projects = '".$sno_option_projects."' AND flow_number > '".$flow_number_be_reject."'";
		$conn->query($sql) ;
		//寄信給被退件者//////////////////////////////////////////////////////////////////////////////////////
		$sql ="SELECT email FROM `hesprrs_members` WHERE `sno_members` = '".$sno_members."'";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$email=$row['email'];
		
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
		$mail->Subject = "退件信件"; //郵件標題
		$mail->Body = $message; //郵件內容
		$mail->addAttachment('flow/flow.pdf', 'flow.pdf'); //附件，改以新的檔名寄出
		$mail->IsHTML(true); //郵件內容為html
		$mail->AddAddress($email); //收件者郵件及名稱
		$mail->Send();
		
		//站內信
		$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) VALUES ('審核中' ,'".$in_message."','".$id."','".$sno_members."', '".$sno_option_projects."')";
		$conn->query($sqll) ;
	}
	
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>