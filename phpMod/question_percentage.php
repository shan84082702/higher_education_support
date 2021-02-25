
<?php

header('Content-Type: application/json; charset=UTF-8'); 
$sno_projects = $_COOKIE["sno_projects"];
include('fig.php');
		//$domain = "http://spark5.widelab.org/~csie062452/cgust/";
		$domain = "http://service.cgust.edu.tw/";
		$project_sql = "SELECT `hesprrs_option_projects`.`name` as 'TITLE_NAME',
		concat('主軸',`hesprrs_option_main`.`option_main_order`,' ',`hesprrs_option_main`.`name`) as 'opt_main',
		concat('分項',`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,' : ',`hesprrs_option_sub`.`name`) as 'opt_sub',
		concat(`hesprrs_option_main`.`option_main_order`,'-',`hesprrs_option_sub`.`option_sub_order`,'-',`hesprrs_option_strategies`.`option_strategies_order`,' ',`hesprrs_option_strategies`.`name`) as 'opt_strage',
		`hesprrs_option_projects`.`name` as 'opt_activate',
		`hesprrs_projects_data`.`date_range`,
        substring_index(substring_index(`hesprrs_projects_data`.`date_range`, ',',1),'-',1) as 'activate_y',
        substring_index(substring_index(substring_index(`hesprrs_projects_data`.`date_range`, ',',1),'-',2),'-',-1) as 'activate_m',
        substring_index(substring_index(`hesprrs_projects_data`.`date_range`, ',',1),'-',-1) as 'activate_d',
		`hesprrs_projects_data`.`paricipant_number` as 'people_num',
		`hesprrs_projects_data`.`location` as 'location',
		`hesprrs_projects_data`.`cost` as 'cost',
		(CASE `hesprrs_projects_data`.`isongante` WHEN 1 THEN '是' ELSE '否' END) as 'isontime',
		(CASE WHEN `hesprrs_projects_data`.`unable_execution_reason` IS null THEN '無' WHEN '' THEN '無' ELSE `hesprrs_projects_data`.`unable_execution_reason` END) as 'reason',
		`hesprrs_projects_data`.`description` as 'description',
		(CASE `hesprrs_projects_data`.`isinresulf` WHEN 1 THEN '是' ELSE '否' END) as 'isonrule',
		(CASE `hesprrs_projects_data`.`cooperation_project` WHEN '' THEN '否' ELSE `hesprrs_projects_data`.`cooperation_project` END) as 'iscooper',
		(CASE `hesprrs_projects_data`.`target` WHEN 0 THEN '教師面' WHEN 1 THEN '學生面' WHEN 2 THEN '課程面' WHEN 3 THEN '整體面(特色、資源)' END) as 'face',
		act_mem.`name` as 'activate_supervisor',
		`hesprrs_projects_data`.`recommendations`,
		strage_mem.`name` as 'activate_manager',
		main_mem.`name` as 'main_supervisor',
		`hesprrs_projects_data`.`sign` as 'sign_url',
		`hesprrs_projects_data`.`annex` as 'annex_url'
		FROM `hesprrs_projects_data` 
		LEFT JOIN
		`hesprrs_option_projects` ON `hesprrs_option_projects`.`sno_option_projects` = `hesprrs_projects_data`.`sno_option_projects`
		LEFT JOIN
		`hesprrs_option_strategies` ON `hesprrs_option_strategies`.`sno_option_strategies` = `hesprrs_option_projects`.`sno_option_strategies`
		LEFT JOIN
		`hesprrs_option_sub` ON `hesprrs_option_strategies`.`sno_option_sub` = `hesprrs_option_sub`.`sno_option_sub`
		LEFT JOIN 
		`hesprrs_option_main` ON `hesprrs_option_main`.`sno_option_main` = `hesprrs_option_sub`.`sno_option_main`
		LEFT JOIN
		`hesprrs_members` act_mem ON act_mem.`sno_members` = `hesprrs_option_projects`.`supervisor_project`
		LEFT JOIN
		`hesprrs_members` strage_mem ON strage_mem.`sno_members` =  `hesprrs_option_strategies`.`supervisor_strategies`
		LEFT JOIN
		`hesprrs_members` main_mem ON main_mem.`sno_members` =  `hesprrs_option_main`.`supervisor_main`
        WHERE `hesprrs_option_projects`.`sno_option_projects` ='".$sno_projects."'";
		$project_result = $conn->query($project_sql);
		$reproject_result=array();
		while($row = $project_result->fetch_array(MYSQLI_ASSOC)){
			$reproject_result['TITLE_NAME'] = nf_to_wf($row['TITLE_NAME'],1);
			$reproject_result['opt_main'] = nf_to_wf($row['opt_main'],1);
			$reproject_result['opt_sub'] = nf_to_wf($row['opt_sub'],1);
			$reproject_result['opt_strage'] = nf_to_wf($row['opt_strage'],1);
			$reproject_result['opt_activate'] = nf_to_wf($row['opt_activate'],1);
			$reproject_result['OCR_activate_date'] = nf_to_wf('中華民國'.($row['activate_y']-1911).'年'.$row['activate_m'].'月'.$row['activate_d'].'日',1);
			// $reproject_result['activate_date'] = nf_to_wf($row['activate_m'].'/'.$row['activate_d'],1);
			$reproject_result['activate_date'] = str_replace(",",",</p><p>",str_replace("-","/",$row['date_range']));
			$reproject_result['people_num'] = nf_to_wf($row['people_num'],1);
			$reproject_result['location'] = nf_to_wf($row['location'],1);
			$reproject_result['cost'] = nf_to_wf($row['cost'],1);
			$reproject_result['isontime'] = nf_to_wf($row['isontime'],1);
			$reproject_result['reason'] = nf_to_wf($row['reason'],1);
			$reproject_result['description'] = nf_to_wf($row['description'],1);
			$reproject_result['isonrule'] = nf_to_wf($row['isonrule'],1);
			$reproject_result['iscooper'] = nf_to_wf($row['iscooper'],1);
			$reproject_result['face'] = nf_to_wf($row['face'],1);
			$reproject_result['recommendations'] = nf_to_wf($row['recommendations'],1);
			$reproject_result['activate_supervisor'] = nf_to_wf($row['activate_supervisor'],1);
			$reproject_result['activate_manager'] = nf_to_wf($row['activate_manager'],1);
			$reproject_result['main_supervisor'] = nf_to_wf($row['main_supervisor'],1);
			$reproject_result['sign_url'] = $domain.$row['sign_url'];
			$reproject_result['annex_url'] = $domain.$row['annex_url'];
		}
		$img_sql = "SELECT  `path`,`img_describe` FROM `hesprrs_projects_images` WHERE `sno_option_projects` = '".$sno_projects."'";
		$img_result = $conn->query($img_sql);
		$reimg_result=array();
		while($row = $img_result->fetch_array(MYSQLI_ASSOC)){
			$data['path']=$domain.$row['path'];
			$data['img_describe']=$row['img_describe'];
			array_push($reimg_result,$data);
			unset($data);
		}
		
		$mange_sql = "SELECT
			(CASE WHEN `hesprrs_edu_management`.`name` is null THEN '' else`hesprrs_edu_management`.`name` END) as  'opt_mange',
			`question`,
			(CASE WHEN`hesprrs_projects_summary`.`results` is null THEN '' else`hesprrs_projects_summary`.`results` END) as 'mange_result',
			`type`
			FROM `hesprrs_projects_summary`
			LEFT JOIN `hesprrs_edu_management` ON `hesprrs_edu_management`.`sno_edu_management` = `hesprrs_projects_summary`.`sno_edu_management`
			WHERE `hesprrs_projects_summary`.`sno_project` =  '".$sno_projects."'";
		$mange_result = $conn->query($mange_sql);
		$remange_result=array();
		while($row = $mange_result->fetch_array(MYSQLI_ASSOC)){
			$data['opt_mange']=$row['opt_mange'];
			$data['question']=$row['question'];//20190704
			$data['mange_result']=$row['mange_result'];
			$data['type']=$row['type'];//20190704
			array_push($remange_result,$data);
			unset($data);
		}
		
	
		$sql ="SELECT `sno_questionnarie_category`, `name` FROM `hesprrs_questionnarie_category` WHERE `active` = 1";
		$result = $conn->query($sql);
		$out=array();
		
		while($row = $result->fetch_array(MYSQLI_ASSOC)){
			$c=nf_to_wf($row['sno_questionnarie_category'],1);
			$out1['q_type']=nf_to_wf($row['name'],1);

			$sql2 ="SELECT `sno_questionnarie_group`, `name` as 'groupname' FROM `hesprrs_questionnarie_group_category`";
			$result2 = $conn->query($sql2) ;
			$group1=array();
			$group2=array();
			$group3=array();
			while($row2 = $result2->fetch_array(MYSQLI_ASSOC)){//題組
				$a=nf_to_wf($row2['sno_questionnarie_group'],1);
				$out21['title']=nf_to_wf($row2['groupname'],1);
				if($a == 1){
					$sql3 ="SELECT `sno_questionorder` ,`q_category`, `q_orders`, `titile`, `active`, `created_at`, `updated_at` 
					FROM `hesprrs_questionorder` WHERE `active`=True AND `q_category` = '".$c."' 
					AND `sno_questionnarie_group`='".$a."'";
					$result3 = $conn->query($sql3) ;
					$out3=array();
					while($row3 = $result3->fetch_array(MYSQLI_ASSOC)){//大標題
						$b=nf_to_wf($row3['sno_questionorder'],1);
						$out31['subtitle']=nf_to_wf($row3['titile'],1);
						$sql4 ="SELECT `hesprrs_subquestionorder`.`q_orders` ,
						`hesprrs_subquestionorder`.`titile`,`sno_questionnaires_results`,`result` 
						FROM `hesprrs_questionnaires_results` 
						LEFT JOIN `hesprrs_subquestionorder` ON `hesprrs_questionnaires_results`.`sno_subquestion` = `hesprrs_subquestionorder`.`sno_subquestion`
						WHERE `hesprrs_questionnaires_results`.`sno_projects` ='".$sno_projects."' 
						AND `hesprrs_subquestionorder`.`sno_questionorder` ='".$b."'";
						$result4 = $conn->query($sql4) ;
						$out4=array();
						$x=1;//計數
						$totle=0;//總數
						while($row4 = $result4->fetch_array(MYSQLI_ASSOC)){//子題目
							
							$s="q".$x;
							$out31[$s]=nf_to_wf((string)$row4['titile'],1);
							$s1="r".$x;
							$out31[$s1]=nf_to_wf((string)$row4['result'],1);
							$num[$s1]=$out31[$s1];//記住值
							$totle=$totle+$out31[$s1];//加總
							$x++;//記住有幾個
						}
						for($i=$x;$i<=5;$i++){//小於五的要加
							$s="q".$i;
							$out31[$s]="<br/>";
							$s1="r".$i;
							$out31[$s1]="";
						}
						for($i=1;$i<$x;$i++){//算分配多少
							$s1="r".$i;
							$s2="rp".$i;
							if($totle !=0){
								$out31[$s2]=(string)round((($num[$s1])*100/$totle),2)."％";
							}
							else{
								$out31[$s2]="";
							}
						}
						for($i=$x;$i<=5;$i++){//0%
							$s2="rp".$i;
							$out31[$s2]='';
						}
						//$out31['sub']=$out4;
						array_push($out3,$out31);
						unset($out31);
					}
					$out21['subtitle_group']=$out3;
					array_push($group1,$out21);
					unset($out21);
				}
				if($a == 2 || $a ==3){
					$sql3 ="SELECT `sno_questionorder` ,`q_category`, `q_orders`, `titile`, `active`, `created_at`, `updated_at` 
					FROM `hesprrs_questionorder` WHERE `active`=True AND `q_category` = '".$c."' 
					AND `sno_questionnarie_group`='".$a."'";
					$result3 = $conn->query($sql3) ;
					$out3=array();
					$arvg = 0;
					while($row3 = $result3->fetch_array(MYSQLI_ASSOC)){//大標題
						$b=nf_to_wf($row3['sno_questionorder'],1);
						$out31['subtitle']=nf_to_wf($row3['titile'],1);
						$sql4 ="SELECT `hesprrs_subquestionorder`.`q_orders` ,
						`hesprrs_subquestionorder`.`titile`,`sno_questionnaires_results`,`result` 
						FROM `hesprrs_questionnaires_results` 
						LEFT JOIN `hesprrs_subquestionorder` ON `hesprrs_questionnaires_results`.`sno_subquestion` = `hesprrs_subquestionorder`.`sno_subquestion`
						WHERE `hesprrs_questionnaires_results`.`sno_projects` ='".$sno_projects."' 
						AND `hesprrs_subquestionorder`.`sno_questionorder` ='".$b."'";
						$result4 = $conn->query($sql4) ;
						$out4=array();
						$x=1;//計數
						$totle=0;//總數
						while($row4 = $result4->fetch_array(MYSQLI_ASSOC)){//子題目
							
							$s="q".$x;
							$out31[$s]=nf_to_wf((string)$row4['titile'],1);
							$s1="r".$x;
							$out31[$s1]=nf_to_wf((string)$row4['result'],1);
							$num[$s1]=$out31[$s1];//記住值
							$totle=$totle+$out31[$s1];//加總
							$x++;//記住有幾個
						}
						for($i=$x;$i<=5;$i++){//小於五的要加
							$s="q".$i;
							$out31[$s]="";
							$s1="r".$i;
							$out31[$s1]="";
						}
						for($i=1;$i<$x;$i++){//算分配多少
							$s1="r".$i;
							$s2="rp".$i;
							if($totle != 0){
								$arvg = $arvg + ($num[$s1]/$totle *(6-$i));
								$out31[$s2]=(string)round((($num[$s1])*100/$totle),2)."％";
							}
							else{
								$arvg = "";
								$out31[$s2] = "";
							}
						}
						for($i=$x;$i<=5;$i++){//0%
							$s2="rp".$i;
							$out31[$s2]='';
						}
						$out31['avg']=(string)round($arvg,2);
						//$out31['sub']=$out4;
						array_push($out3,$out31);
						$arvg = 0;
						unset($out31);
					}
					$out21['subtitle_group']=$out3;
					array_push($group2,$out21);
					unset($out21);
				}
				if($a == 4){
					$sql3 ="SELECT `sno_questionorder` ,`q_category`, `q_orders`, `titile`, `active`, `created_at`, `updated_at` 
					FROM `hesprrs_questionorder` WHERE `active`=True AND `q_category` = '".$c."' 
					AND `sno_questionnarie_group`='".$a."'";
					$result3 = $conn->query($sql3) ;
					$out3=array();
					$x=1;//計數
					$totle=0;//總數
					while($row3 = $result3->fetch_array(MYSQLI_ASSOC)){//大標題
						$b=nf_to_wf($row3['sno_questionorder'],1);
						$out31['subtitle']=nf_to_wf($row3['titile'],1);
						$sql4 ="SELECT `hesprrs_subquestionorder`.`q_orders` ,
						`hesprrs_subquestionorder`.`titile`,`sno_questionnaires_results`,`result` 
						FROM `hesprrs_questionnaires_results` 
						LEFT JOIN `hesprrs_subquestionorder` ON `hesprrs_questionnaires_results`.`sno_subquestion` = `hesprrs_subquestionorder`.`sno_subquestion`
						WHERE `hesprrs_questionnaires_results`.`sno_projects` ='".$sno_projects."' 
						AND `hesprrs_subquestionorder`.`sno_questionorder` ='".$b."'";
						$result4 = $conn->query($sql4) ;
						$out4=array();
						
						while($row4 = $result4->fetch_array(MYSQLI_ASSOC)){//子題目
							$s="q".$x;
							$out31[$s]=nf_to_wf((string)$row4['titile'],1);
							$s1="r".$x;
							$out31[$s1]=nf_to_wf((string)$row4['result'],1);
						}
						//$out31['sub']=$out4;
						array_push($out3,$out31);
						unset($out31);
					}
					$out21['subtitle_group']=$out3;
					array_push($group3,$out21);
					unset($out21);
				}
			}
			if(empty($group1[0]["subtitle_group"][0]["r1"]))
				continue;
			$out1['title_group1']=$group1;
			$out1['title_group2']=$group2;
			$out1['title_group3']=$group3;
			/*
			if(empty(out1[0]['title_group1'][0]['subtitle_group'][0]['r1']))
				continue;
			*/
			array_push($out,$out1);
			unset($out1);
			
		}
/*
	echo json_encode(array(
		//'mesg' =>$mesg,
		'TITLE_NAME'=>$reproject_result['TITLE_NAME'],
		'opt_main'=>$reproject_result['opt_main'],
		'opt_sub'=>$reproject_result['opt_sub'],
		'opt_strage'=>$reproject_result['opt_strage'],
		'opt_activate'=>$reproject_result['opt_activate'],
		'OCR_activate_date'=>$reproject_result['OCR_activate_date'],
		'activate_date'=>$reproject_result['activate_date'],
		'people_num'=>$reproject_result['people_num'],
		'location'=>$reproject_result['location'],
		'cost'=>$reproject_result['cost'],
		'isontime'=>$reproject_result['isontime'],
		'reason'=>$reproject_result['reason'],
		'description'=>$reproject_result['description'],
		'isonrule'=>$reproject_result['isonrule'],
		'iscooper'=>$reproject_result['iscooper'],
		'face'=>$reproject_result['face'],
		'activate_supervisor'=>$reproject_result['activate_supervisor'],
		'activate_manager'=>$reproject_result['activate_manager'],
		'main_supervisor'=>$reproject_result['main_supervisor'],
		'sign_url'=>$reproject_result['sign_url'],
		'activate_url'=>$reimg_result,
		'mange'=>$remange_result,
		'qusetion' => $out
	));
*/
function nf_to_wf($strs, $types){  //全形半形轉換
    $nft = array(
        "(", ")", "[", "]", "{", "}", ".", ",", ";", ":",
        "?", "!", "@", "#", "$", "%", "&", "|", "\\",
        "+", "=", "*", "~", "`", "'", "\"", "<", ">",
        "^", "_",
    );
    $wft = array(
        "（", "）", "〔", "〕", "｛", "｝", "﹒", "，", "；", "：",
        "？", "！", "＠", "＃", "＄", "％", "＆", "｜", "＼",
        "＋", "＝", "＊", "～", "、", "、", "＂", "＜", "＞",
        "︿", "＿",
    );
 
    if ($types == '1'){
        // 轉全形
        $strtmp = str_replace($nft, $wft, $strs);
    }else{
        // 轉半形
        $strtmp = str_replace($wft, $nft, $strs);
    }
    return $strtmp;
}

header("Content-type: text/html; charset=utf8"); //頁面編碼
header("Content-Type:application/msword");   //將此html頁面轉成word
header("Content-Disposition:attachment;filename=".$reproject_result['TITLE_NAME'].".doc");   //設定word檔名
header("Pragma:no-cache");
header("Expires:0");
?>
<html>
	<head>
	</head>
	<body style="font-style: DFKai-sb;">
		<div class="container" id="headpage" style ="text-align:center;">
			<p style="font-size:32pt;"><?php echo $reproject_result['TITLE_NAME']; ?></p>
			<p style="font-size:28pt;">&nbsp</p>
			<p style="font-size:28pt;">&nbsp</p>
			<p style="color:rgb(51,51,153);font-size:28pt;"><?php echo '主軸計畫成果報告書'; ?></p>
			<p style="font-size:18pt;"><?php echo $reproject_result['opt_main']; ?></p>
			<p style="font-size:18pt;"> <?php echo $reproject_result['opt_sub']; ?></p>
		</div>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<div class="container" id="headsign">			
			<p style="font-size:14pt;"><?php echo '執行策略：'.$reproject_result['opt_strage']; ?></p>
			<p style="font-size:14pt;"><?php echo '活動名稱：'.$reproject_result['opt_activate']; ?></p>
			<p style="font-size:14pt;"><?php echo '指導單位:教育部技職司'; ?></p>
			<p style="font-size:14pt;"><?php echo '主辦單位:長庚科技大學'; ?></p>
			<p style="font-size:14pt;"><?php echo '承辦單位: 長庚科技大學'; ?></p>
			<p style="font-size:14pt;"><?php echo '活動日期：'.$reproject_result['OCR_activate_date']; ?></p>
		</div>
		<div class="container" style ="text-align:center;">
			<p style="font-size:28pt;"><?php echo '目錄'; ?></p>
			<hr/>
			<p style="font-size:18pt;"><?php echo '壹、活動檢核................................................................P.'; ?></p>
			<p style="font-size:18pt;"><?php echo '貳、活動經費................................................................P.'; ?></p>
			<p style="font-size:18pt;"><?php echo '參、活動簽到................................................................P.'; ?></p>
			<p style="font-size:18pt;"><?php echo '肆、活動照片................................................................P.'; ?></p>
			<p style="font-size:18pt;"><?php echo '伍、附件.......................................................................P.'; ?></p>
			<p style="font-size:14pt;"><?php echo '(如活動講義、問卷調查、活動海報…等，請自行斟酌)'; ?></p>
		</div>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<p style="font-size:16pt;">&nbsp</p>
		<div class="container">
			<p style="font-size:14pt;">壹、活動檢核</p>
			<table style="border:solid black 1px; border-collapse: collapse; width:100%;">
				
				<tr><td style="border:solid black 1px;  background-color:rgb(224,224,224);" colspan="8"><p style="font-size:14pt; text-align:center;"><?php echo $reproject_result['opt_strage']; ?></p></td></tr>
				<tr><td style="border:solid black 1px;"  colspan="2"><p>活動名稱</p></td><td style="border:solid black 1px;" colspan="2"><p><?php echo $reproject_result['opt_activate']; ?></p></td><td style="border:solid black 1px;" colspan="2"><p>活動日期</p></td><td style="border:solid black 1px;" colspan="2"><p><?php echo $reproject_result['activate_date'];?></p></td></tr>
				<tr><td style="border:solid black 1px;" colspan="2"><p>活動時間</p></td><td style="border:solid black 1px;" colspan="2"><p><?php echo ''; ?></td></p><td style="border:solid black 1px;" colspan="2"><p>活動地點</p></td><td style="border:solid black 1px;" colspan="2"><p><?php echo $reproject_result['location']; ?></p></td></tr>
				<tr><td style="border:solid black 1px;" colspan="2"><p>參與人數</p></td><td style="border:solid black 1px;" colspan="2"><p><?php echo $reproject_result['people_num'].'人'; ?></p></td><td style="border:solid black 1px;" colspan="2"><p>活動經費</p></td><td style="border:solid black 1px;" colspan="2"><p><?php echo nf_to_wf($reproject_result['cost'],0); ?></p></td></tr>
				<tr><td style="border:solid black 1px;" colspan="8"><p style="font-size:13pt;">是否依據報部計畫書中甘特圖預定月份執行？<?php echo $reproject_result['isontime'];?></p><p style="font-size:13pt;">若無依預定月份執行，請說明原因：<?php echo $reproject_result['reason'];?></p></td></tr>
				<tr><td style="border:solid black 1px; background-color:rgb(255,255,153);"  colspan="1">活動簡述</td><td colspan="7"><p><?php echo $reproject_result['description']?></p></td></tr>
				<tr><td style="border:solid black 1px;" colspan="8"><p> 是否依據報部計畫書中撰寫之質量化指標執行？ <?php echo $reproject_result['isonrule'];?></p></td></tr>
				<?php 
					foreach($remange_result as $mange){
						echo '<tr><td style="border:solid black 1px; background-color:rgb(255,255,153);"><p>管考指標</p></td><td style="border:solid black 1px;" colspan="1"><p>'.$mange['opt_mange'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);"><p>衡量基準</p></td><td style="border:solid black 1px;" colspan="2"><p>'.$mange['question'].'</p></td><td style="border:solid black 1px;background-color:rgb(255,255,153)"><p>執行成效</p></td><td style="border:solid black 1px;" colspan="2"><p>';
						if ($mange['type'] == '1')
							echo '<a href="'.$domain.$mange['mange_result'].'">執行成效</a>';
						else if ($mange['type'] == '0')
							echo $mange['mange_result'];
						echo '</p></td></tr>';		
					//20190704 新欄位 $mange['question'], $mange['type']
					}
				?>
				<tr><td style="border:solid black 1px;" colspan="8"><p style="font-size:14pt;"><?php echo '是否與其他主軸活動進行成效倍增？'.$reproject_result['iscooper']; ?></p></td></tr>
				<tr><td style="border:solid black 1px;" colspan="8"><p style="font-size:14pt;"><?php echo '辦理此活動較著重之面向？'.$reproject_result['face']; ?></p></td></tr>		
				<tr><td style="border:solid black 1px; background-color:rgb(255,255,153);">檢討與建議</td><td colspan="7"><p><?php echo $reproject_result['recommendations']; ?></p></td></tr>
			</table>
			<p><span style="text-decoration: underline;">承辦人</span>  ： <?php echo $reproject_result['activate_supervisor'];?>  <span style="text-decoration: underline;">承辦單位主管</span>  ：  <?php echo $reproject_result['activate_manager'];?>  <span style="text-decoration: underline;">  主軸主持人 </span> ：  <?php echo $reproject_result['main_supervisor'];?>  </p>
			<p>教學發展與資源中心查核日期：   年   月   日    單位簽章：</p>
			<p>查核人簽章：</p>
		</div>
		<div class="container">
			<p style="font-size:14pt;">貳、活動經費預算與實際支出明細表</p>
			<p style="font-size:14pt; text-align:right;">單位：新台幣/元</p>
			<table style="border:solid black 1px; border-collapse: collapse; width:100%;">
				
				<tr><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="2"><p style="font-size:14pt; text-align:center;">編序</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="2"><p style="font-size:14pt; text-align:center;">預算項目</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="1" colspan="3"><p style="font-size:14pt; text-align:center;">預算支出</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="2"><p style="font-size:14pt; text-align:center;">實際支出</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="2"><p style="font-size:14pt; text-align:center;">差異說明</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="2"><p style="font-size:14pt; text-align:center;">款項別</p></td></tr>
				<tr><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="1" colspan="1"><p style="font-size:14pt; text-align:center;">單價</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="1" colspan="1"><p style="font-size:14pt; text-align:center;">數量</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153);" rowspan="1" colspan="1"><p style="font-size:14pt; text-align:center;">總額</p></td></tr>
				<tr><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td></tr>
				<tr><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;">總計</p></td><td style="border:solid black 1px;" colspan="3"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"><?php echo nf_to_wf($reproject_result['cost'],0);?></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td><td style="border:solid black 1px;"><p style="font-size:14pt; text-align:center;"></p></td></tr>
				
			</table>
			<p><span style="text-decoration: underline;">承辦人</span>  ： <?php echo $reproject_result['activate_supervisor'];?>  <span style="text-decoration: underline;">承辦單位主管</span>  ：  <?php echo $reproject_result['activate_manager'];?>  <span style="text-decoration: underline;">  主軸主持人</span> ：  <?php echo $reproject_result['main_supervisor'];?>  </p>
			<p style="font-size:12pt;"> </p>
			<p style="font-size:12pt;">說明： </p>
			<p style="font-size:12pt;">1.	預算項目請就原修正申請書之主軸計畫支出預算明細資料填寫。</p>
			<p style="font-size:12pt;">2.	實際支出欄位，請就實際執行的支出金額填寫，並說明差異原因。</p>
			<p style="font-size:12pt;">3.	若有學校配合款支付務必填寫清楚。</p>
			<p style="font-size:12pt;">4.	如本表不敷使用，請自行增列。</p>
		</div>
		<div class="container">
			<p style="font-size:14pt;">參、活動簽到表</p>
			<?php 
				//echo '<img src="'.$reproject_result['sign_url'].'" width=100%/>';	
				echo '<a href="'.$reproject_result['sign_url'].'">活動簽到</a>';				
			?>
		</div>
		<div class="container">
			<p style="font-size:14pt;">肆、活動照片</p>
			<table style="border:solid black 1px; border-collapse: collapse; width:100%; text-align:center;">
			<?php 
			$countimg = 0;
			for($i=0 ; $i<count($reimg_result);$i++){
				if(((count($reimg_result)-$i)/2)>=1){
					echo '<tr><td style="border:solid black 1px; width:30pt;"><img src="'.$reimg_result[$i]['path'].'" width="150" height="100"/></td><td style="border:solid black 1px; width:30pt;"><img src="'.$reimg_result[$i+1]['path'].'" width="150" height="100"/></td></tr>';
					echo '<tr><td style="border:solid black 1px; width:30pt;"><p>'.$reimg_result[$i]['img_describe'].'</p></td><td style="border:solid black 1px; width:50pt;"><p>'.$reimg_result[$i+1]['img_describe'].'</p></td></tr>';
					$i=$i+1;
				}
				else{
					echo '<tr><td style="border:solid black 1px; width:30pt;"><img src="'.$reimg_result[$i]['path'].'" width="150" height="100"/></td><td style="border:solid black 1px; width:30pt;"></td></tr>';
					echo '<tr><td style="border:solid black 1px; width:30pt;"><p>'.$reimg_result[$i]['img_describe'].'</p></td><td style="border:solid black 1px; width:50pt;"><p></p></td></tr>';
					$i=$i+1;
				}
									
			}
			?>
			</table>
		</div>
		<div class="container">
			<p style="font-size:14pt;">伍、附件(如活動講義、問卷調查、活動海報…等)</p>
			<p style="font-size:12pt;">(一)問卷調查</p>
			<?php
				foreach($out as $qusetion){
					echo '<p>'.$qusetion['q_type'].'</p>';
					foreach($qusetion['title_group1'] as $qusetiongroup){
						echo '<p>※'.$qusetiongroup['title'].'</p>';
						foreach($qusetiongroup['subtitle_group'] as $qusetionsubgroup){
							echo '<p>'.$qusetionsubgroup['subtitle'].'<p>';
							echo '<table style="border:solid black 1px; border-collapse: collapse; width:100%;  text-align:center;"> 
							<tr><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p></p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q1'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q2'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q3'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q4'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q5'].'</p></td></tr>' ;
							echo '<tr><td style="border:solid black 1px;"><p>人數</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r1'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r2'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r3'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r4'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r5'].'</p></td></tr>' ;
							echo '<tr><td style="border:solid black 1px;"><p>百分比</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp1'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp2'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp3'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp4'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp5'].'</p></td></tr>' ;
							echo '</table>' ;
						}
					}
					foreach($qusetion['title_group2'] as $qusetiongroup){
						echo '<p>※'.$qusetiongroup['title'].'</p>';
						foreach($qusetiongroup['subtitle_group'] as $qusetionsubgroup){
									echo '<p>'.$qusetionsubgroup['subtitle'].'<p>';
									echo '<table style="border:solid black 1px; border-collapse: collapse; width:100%;  text-align:center;"> 
									<tr><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p></p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q1'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q2'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q3'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q4'].'</p></td><td style="border:solid black 1px; background-color:rgb(255,255,153)"><p>'.$qusetionsubgroup['q5'].'</p></td></tr>' ;
									echo '<tr><td style="border:solid black 1px;"><p>人數</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r1'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r2'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r3'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r4'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['r5'].'</p></td></tr>' ;
									echo '<tr><td style="border:solid black 1px;"><p>百分比</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp1'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp2'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp3'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp4'].'</p></td><td style="border:solid black 1px;"><p>'.$qusetionsubgroup['rp5'].'</p></td></tr>' ;
									echo '<tr><td style="border:solid black 1px;" colspan="6"><p>5分量表評分為<span style="color:red">'.$qusetionsubgroup['avg'].'</span>分</p></td></tr>' ;
									echo '</table>' ;
								}
					}
					foreach($qusetion['title_group3'] as $qusetiongroup){
					echo '<p>※'.$qusetiongroup['title'].'</p>';
							foreach($qusetiongroup['subtitle_group'] as $qusetionsubgroup){
							echo '<p>'.$qusetionsubgroup['r1'].'</p>';
						}
					}
				}
			?>
			<p style="font-size:12pt;">(二)活動議程</p>
			<?php
				echo '<a href="'.$reproject_result['annex_url'].'">活動議程</a>';	
			?>
		</div>
	</body>
</html>