<?php
header('Content-Type: application/json; charset=UTF-8'); 
@$token = $_POST["token"];
@$sno_projects = $_POST["sno_projects"];
@$q_category = $_POST["category"];
include('fig.php');
if($token_time==0){
	
		$out['q_category']=$q_category;
		
		$sql2 ="SELECT `sno_questionnarie_group`, `name` as 'groupname' FROM `hesprrs_questionnarie_group_category`";
		$result2 = $conn->query($sql2) ;
		$out2=array();
		while($row2 = $result2->fetch_array(MYSQLI_ASSOC)){//題組
			$out21['sno_questionnarie_group']=$row2['sno_questionnarie_group'];
			$out21['groupname']=$row2['groupname'];

			$sql3 ="SELECT `sno_questionorder` ,`q_category`, `q_orders`, `titile`, `active`, `created_at`, `updated_at` 
			FROM `hesprrs_questionorder` WHERE `active`=True AND `q_category` = '".$q_category."' 
			AND `sno_questionnarie_group`='".$out21['sno_questionnarie_group']."'";
			$result3 = $conn->query($sql3) ;
			$out3=array();
			while($row3 = $result3->fetch_array(MYSQLI_ASSOC)){//大標題
				$out31['sno_questionorder']=$row3['sno_questionorder'];
				$out31['q_category']=$row3['q_category'];
				$out31['q_orders']=$row3['q_orders'];
				$out31['question']=$row3['titile'];
				$out31['active']=$row3['active'];
				$out31['created_at']=$row3['created_at'];
				$out31['updated_at']=$row3['updated_at'];
	
				$sql4 ="SELECT `hesprrs_subquestionorder`.`q_orders` ,
				`hesprrs_subquestionorder`.`titile`,`sno_questionnaires_results`,`result` 
				FROM `hesprrs_questionnaires_results` 
				LEFT JOIN `hesprrs_subquestionorder` ON `hesprrs_questionnaires_results`.`sno_subquestion` = `hesprrs_subquestionorder`.`sno_subquestion`
				WHERE `hesprrs_questionnaires_results`.`sno_projects` ='".$sno_projects."' 
				AND `hesprrs_subquestionorder`.`sno_questionorder` ='".$out31['sno_questionorder']."'";
				$result4 = $conn->query($sql4) ;
				$out4=array();
				while($row4 = $result4->fetch_array(MYSQLI_ASSOC)){//子題目
					$out41['q_orders']=$row4['q_orders'];
					$out41['subquestion']=$row4['titile'];
					$out41['sno_questionnaires_results']=$row4['sno_questionnaires_results'];
					$out41['result']=$row4['result'];
					
					array_push($out4,$out41);
					unset($out41);
				}
				$out31['sub']=$out4;
				array_push($out3,$out31);
				unset($out31);
			}
			$out21['topic']=$out3;
			array_push($out2,$out21);
			unset($out21);
		}
		$out['sub']=$out2;
	
	echo json_encode(array(
		'msg' =>'200',
		'out' => $out
	));
}
?>