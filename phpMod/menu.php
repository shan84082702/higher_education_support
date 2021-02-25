<?php
header('Content-Type: application/json; charset=UTF-8');
if($_POST["token"] == null) exit(); //20190502 kai modify
@$token=$_POST["token"];
$page = $_POST["page"];
$subpage = $_POST["subpage"];
include('fig.php');
///回傳列表
if($page == 1){
$remenu ='<li class="header"></li>
                        <li class="active">
                            <a href="index.html">
                                <i class="fa  fa-home"></i> <span>首頁</span>
                            </a>
                        </li>';
}
else{
	$remenu ='<li class="header"></li>
                        <li class="">
                            <a href="index.html">
                                <i class="fa  fa-home"></i> <span>首頁</span>
                            </a>
                        </li>';
}
#$id = 14;
#$conn = new mysqli($_servername,$_username,$_password,$_dbname);

if($token_time==0){//判斷token

	///參數預設
	$project_edit=False; //活動管理
	$strategiest_summary=False; //策略總結
	$member_manage=False; //帳號管理
    $data_manage=False; //資料管理
    $view_data=False;
    $review=False;
	///參數預設
	$sql="SELECT  `hesprrs_members`.`sno_roles`,`project_edit`, `strategiest_summary`, `member_manage`, `data_manage`, `view_data` FROM `hesprrs_members`
	LEFT JOIN `hesprrs_roles` ON `hesprrs_members`.`sno_roles`= `hesprrs_roles`.`sno_roles`
	WHERE `hesprrs_members`.`sno_members`= '".$id."'";
	$result= $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$project_edit= $row["project_edit"];
		$strategiest_summary= $row["strategiest_summary"];
		$member_manage= $row["member_manage"];
		$data_manage= $row["data_manage"];
        $view_data= $row["view_data"];
        $sno_roles= $row["sno_roles"];
	}
	$sql="SELECT (CASE WHEN (SELECT `sno_roles` FROM `hesprrs_members` WHERE `sno_members` = '".$id."' )='1' THEN '1' WHEN count(`sno_review`) = '0' THEN '0' ELSE '1' END) AS `review` FROM `hesprrs_review` WHERE `hesprrs_review`.`sno_members` = '".$id."'";
	$result= $conn->query($sql) ;
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$review= $row["review"];
	}
	if($member_manage){//帳號管理
		if($page == 2)
		{
			$remenu .='<li class="active">
								<a href="account.html">
									<i class="fa fa-users"></i>
									<span>帳號管理</span>
								</a>
							</li>';
		}
		else{
			$remenu .='<li>
                            <a href="account.html">
                                <i class="fa fa-users"></i>
                                <span>帳號管理</span>
                            </a>
                        </li>';
		}
	}

	if($project_edit){//活動管理
	if($page == 3)
		{
			$remenu .='<li class="treeview menu-open">
                            <a href="#">
                                <i class="fa  fa-table"></i>
                                <span>報告書管理</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>';
			 $remenu .='<ul class="treeview-menu" style="display: block;">';
	}
	else{
		$remenu .='<li class="treeview">
                            <a href="#">
                                <i class="fa  fa-table"></i>
                                <span>報告書管理</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>';
		 $remenu .='<ul class="treeview-menu" style="display: none;">';
	}




		if($strategiest_summary){//策略總結
		   if($page == 3 && $subpage == 1){
			   $remenu .= ' <li class="active"><a href="report.html"><i class="fa fa-circle-o"></i> 報告總結管理</a></li>';
		   }
		   else{
			   $remenu .= ' <li><a href="report.html"><i class="fa fa-circle-o"></i> 報告總結管理</a></li>';
		   }
		}
		if($page == 3 && $subpage == 2){
			  $remenu .='<li class="active"><a href="result.html"><i class="fa fa-circle-o"></i> 成果報告管理</a></li>';
		}
		else{
			  $remenu .='<li><a href="result.html"><i class="fa fa-circle-o"></i> 成果報告管理</a></li>';
		}
		if($page == 3 && $subpage == 3){
			   $remenu .='<li class="active"><a href="activity_management.html"><i class="fa fa-circle-o"></i> 活動管理</a></li>';
		}
		else{
			   $remenu .='<li><a href="activity_management.html"><i class="fa fa-circle-o"></i> 活動管理</a></li>';
		}
		$remenu .='</ul></li>';

	}
	if($data_manage){
		if($page == 4)
		{
			$remenu .= '<li class="treeview menu-open">
                            <a href="#">
                                <i class="fa fa-database"></i> <span>資料管理</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>';
				 $remenu .='<ul class="treeview-menu" style="display: block;">';
		}
		else{
			$remenu .='<li class="treeview">
                            <a href="#">
                                <i class="fa fa-database"></i> <span>資料管理</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>';
			 $remenu .='<ul class="treeview-menu" style="display: none;">';
		}
		if($page == 4 && $subpage == 1){
			  $remenu .='<li class="active"><a href="account_role.html"><i class="fa fa-circle-o"></i>帳號角色管理</a></li>';
		}
		else{
			  $remenu .='<li><a href="account_role.html"><i class="fa fa-circle-o"></i>帳號角色管理</a></li>';
		}
		if($page == 4 && $subpage == 2){
			   $remenu .='<li class="active"><a href="strategy_update.html"><i class="fa fa-circle-o"></i> 主軸/分項/策略/活動<br>
                                          <i class="fa fa-circle-o" style="color:transparent;"></i>&nbsp;名稱管理</a></li>';
		}
		else{
			   $remenu .='<li><a href="strategy_update.html"><i class="fa fa-circle-o"></i> 主軸/分項/策略/活動<br>
                                           <i class="fa fa-circle-o" style="color:transparent;"></i>&nbsp;名稱管理</a></li>';
		}
		if($page == 4 && $subpage == 3){
			  $remenu .='<li class="active"><a href="question_update.html"><i class="fa fa-circle-o"></i> 問卷題目管理</a></li>';
		}
		else{
			  $remenu .='<li><a href="question_update.html"><i class="fa fa-circle-o"></i> 問卷題目管理</a></li>';
		}
		if($page == 4 && $subpage == 4){
			   $remenu .='<li class="active"><a href="education_index.html"><i class="fa fa-circle-o"></i> 教育部指標管理</a></li>';
		}
		else{
			   $remenu .='<li><a href="education_index.html"><i class="fa fa-circle-o"></i> 教育部指標管理</a></li>';
		}
		if($page == 4 && $subpage == 5){
			  $remenu .='<li class="active"><a href="other_index.html"><i class="fa fa-circle-o"></i> 其他名稱管理</a></li>';
		}
		else{
			  $remenu .='<li><a href="other_index.html"><i class="fa fa-circle-o"></i> 其他名稱管理</a></li>';
		}

		$remenu .='</ul></li>';
		if($page == 5)
		{
			$remenu .= '<li class="active">
                            <a href="report_edit.html">
                                <i class="fa fa-table"></i>
                                <span>報表管理</span>
                            </a>
                        </li>';
		}
		else{
			$remenu .= '<li>
                            <a href="report_edit.html">
                                <i class="fa fa-table"></i>
                                <span>報表管理</span>
                            </a>
                        </li>';
		}

	}
	if($view_data){//可查看資料
		if($page == 6)
			{
				$remenu .='<li class="treeview menu-open">
								<a href="#">
									<i class="fa  fa-table"></i>
									<span>報告書</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>';
				 $remenu .='<ul class="treeview-menu" style="display: block;">';
		}
		else{
			$remenu .='<li class="treeview">
								<a href="#">
									<i class="fa  fa-table"></i>
									<span>報告書</span>
									<span class="pull-right-container">
										<i class="fa fa-angle-left pull-right"></i>
									</span>
								</a>';
			 $remenu .='<ul class="treeview-menu" style="display: none;">';
		}
		   if($page == 6 && $subpage == 1){
			   $remenu .= ' <li class="active"><a href="view_report.html"><i class="fa fa-circle-o"></i> 報告總結</a></li>';
		   }
		   else{
			   $remenu .= ' <li><a href="view_report.html"><i class="fa fa-circle-o"></i>報告總結</a></li>';
		   }

		if($page == 6 && $subpage == 2){
			  $remenu .='<li class="active"><a href="view_result.html"><i class="fa fa-circle-o"></i> 成果報告</a></li>';
		}
		else{
			  $remenu .='<li><a href="view_result.html"><i class="fa fa-circle-o"></i> 成果報告</a></li>';
		}
		$remenu .='</ul></li>';
    }
    if($review){//診斷小組指標審查
		if($page == 7)
		{
			$remenu .='<li class="active">
								<a href="index_review.html">
									<i class="fa fa-users"></i>
									<span>診斷小組指標審查</span>
								</a>
							</li>';
		}
		else{
			$remenu .='<li>
                            <a href="index_review.html">
                                <i class="fa fa-users"></i>
                                <span>診斷小組指標審查</span>
                            </a>
                        </li>';
		}
    }
    if($sno_roles==1){//電子報管理
		if($page == 8)
		{
			$remenu .='<li class="active">
								<a href="newspaper.html">
									<i class="fa fa-table"></i>
									<span>電子報管理</span>
								</a>
							</li>';
		}
		else{
			$remenu .='<li>
                            <a href="newspaper.html">
                                <i class="fa fa-table"></i>
                                <span>電子報管理</span>
                            </a>
                        </li>';
		}
	}
	$arr['msg'] = '200';
	$arr['remenu'] = $remenu;
	echo json_encode($arr,JSON_UNESCAPED_UNICODE);
}
?>
