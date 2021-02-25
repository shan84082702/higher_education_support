<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_option_projects = $_POST["sno_option_projects"];
@$message = addslashes($_POST["message"]);//內容
@$sender_name = $_POST["sender_name"];
$in_message=$message;//站內信訊息
include('fig.php');
if($token_time==0){
	//看該份文件是否審核，問審核代表是可編輯人員送審
	$sql ="SELECT `hesprrs_projects_data`.`review_state` FROM hesprrs_projects_data WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."'";
	$result = $conn->query($sql) ;
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$review_state=$row['review_state'];
	//全部先弄成審核中
	$sql ="UPDATE hesprrs_projects_data SET review_state = '1'
	WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."'";
	$conn->query($sql) ;
	if($review_state==1){
		//看持有文件者是流程幾號
		$sql ="SELECT `hesprrs_flow`.`flow_number` FROM hesprrs_flow WHERE `hesprrs_flow`.`sno_members` = '".$id."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$result = $conn->query($sql) ;
		$str=$sql;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$flow_number=$row['flow_number'];
		//把持有文件者狀態改成1
		$sql ="UPDATE hesprrs_flow SET flow_status = '1' WHERE
		`hesprrs_flow`.`sno_members` = '".$id."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$conn->query($sql) ;
		//看流程下一位是否有人
		$flow_number=$flow_number+1;
		$sql ="SELECT IF( EXISTS( SELECT `hesprrs_flow`.`sno_flow` FROM hesprrs_flow WHERE `hesprrs_flow`.`flow_number` = '".$flow_number."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."' ), 1, 0 ) AS 'exist' ";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$exist=$row['exist'];
		//如果exist是代表有人
		if($exist==1){
			//把下一位狀態更新成2
			$sql ="UPDATE hesprrs_flow SET flow_status = '2'
			WHERE `hesprrs_flow`.`flow_number` = '".$flow_number."' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
			$conn->query($sql) ;
			//寄信給下一位
			//看下一位流水號
			$sql ="SELECT sno_members FROM `hesprrs_flow` WHERE `sno_option_projects` = '".$sno_option_projects."' AND `flow_number` = '".$flow_number."'";
			$result = $conn->query($sql) ;
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$sno_members=$row['sno_members'];
			//觀看email
			$sql ="SELECT email FROM `hesprrs_members` WHERE `sno_members` = '".$sno_members."'";
			$result = $conn->query($sql) ;
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$email=$row['email'];
			
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
			if($message=="")
				$message='老師您好,有關高等教育深耕計畫 '.$order.' '.$name.' 活動成果報告書，已由'.$sender_name.'填寫完畢，懇請進入平台後<br>'.$loginFilePath.'<br>請依下列附件pdf檔所列步驟逐項執行。';
			else
				$message='老師您好,有關高等教育深耕計畫 '.$order.' '.$name.' 活動成果報告書，已由'.$sender_name.'填寫完畢<br>意見:'.$message.'<br>懇請進入平台後<br>'.$loginFilePath.'<br>請依下列附件pdf檔所列步驟逐項執行。';
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
			$mail->AddAddress($email); //收件者郵件及名稱
			$mail->Send();
			
			//站內信
			$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) VALUES ('審核中' ,'".$in_message."','".$id."','".$sno_members."', '".$sno_option_projects."')";
			$resultt = $conn->query($sqll) ;
		}
		else{//否則報告書改成審核完成(因為沒有下一位了)
			$sql ="UPDATE hesprrs_projects_data SET review_state = '2'
			WHERE `hesprrs_projects_data`.`sno_option_projects` = '".$sno_option_projects."'";
			$conn->query($sql) ;
			//
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
			//審核完成要寄信給所有可編輯人員
			//所有可編輯人員都要被寄信
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
			$message='老師您好,有關高等教育深耕計畫 '.$order.' '.$name.' 活動成果報告書，已審核完成。如有疑問，請進入高教深耕成果平台檢視。如下範例。';
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
				$mail->addAttachment('flow/finish.png', 'finish.png'); //附件，改以新的檔名寄出
				$mail->IsHTML(true); //郵件內容為html
				$mail->AddAddress($row['email']); //收件者郵件及名稱
				$mail->Send();
				
				//站內信
				$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) VALUES ('審核完成' ,'".$in_message."','".$id."','".$row['sno_members']."', '".$sno_option_projects."')";
				$conn->query($sqll) ;
			}
		}
	}
	else{//可編輯人員送審
		//把第一位狀態變2
		$sql ="UPDATE hesprrs_flow SET flow_status = '2'
		WHERE `hesprrs_flow`.`flow_number` = '1' AND `hesprrs_flow`.`sno_option_projects` = '".$sno_option_projects."'";
		$conn->query($sql) ;
		
		$sql ="SELECT sno_members FROM `hesprrs_flow` WHERE `sno_option_projects` = '".$sno_option_projects."' AND `flow_number` = '1'";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$sno_members=$row['sno_members'];
		//觀看email
		$sql ="SELECT email FROM `hesprrs_members` WHERE `sno_members` = '".$sno_members."'";
		$result = $conn->query($sql) ;
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$email=$row['email'];
		
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
		if($message=="")
			$message='老師您好,有關高等教育深耕計畫 '.$order.' '.$name.' 活動成果報告書，已由'.$sender_name.'填寫完畢，懇請進入平台後<br>'.$loginFilePath.'<br>請依下列附件pdf檔所列步驟逐項執行。';
		else
			$message='老師您好,有關高等教育深耕計畫 '.$order.' '.$name.' 活動成果報告書，已由'.$sender_name.'填寫完畢<br>意見:'.$message.'<br>懇請進入平台後<br>'.$loginFilePath.'<br>請依下列附件pdf檔所列步驟逐項執行。';
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
		$mail->AddAddress($email); //收件者郵件及名稱
		$mail->Send();
		
		//站內信
		$sqll ="INSERT INTO `hesprrs_messages`(`type`, `message`, `sno_source`, `sno_target`, `sno_projects`) VALUES ('審核中' ,'".$in_message."','".$id."','".$sno_members."', '".$sno_option_projects."')";
		$resultt = $conn->query($sqll) ;
	}
	echo json_encode(array(
		'msg' =>'200'
	));
}
?>