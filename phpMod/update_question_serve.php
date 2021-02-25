<?php
require_once 'fig.php';
header('Content-Type: application/json; charset=UTF-8');
@$pid = $_POST["pid"];
@$answer = $_POST["answer"];
$deanswer = json_decode($answer, true);
$num = count($deanswer["subqid"]);

$sql = "SELECT `sno_subquestion` FROM `hesprrs_subquestionorder`";
$result = $conn->query($sql);
$val = "";
$tagfindsqid = 0;

while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $tagfindsqid = 0;
    for ($i = 0; $i < $num; $i++) {
        if ($row['sno_subquestion'] == $deanswer["subqid"][$i]) {
            $deanswer["subqid"][$i]=addslashes($deanswer["subqid"][$i]);
            $deanswer["result"][$i]=addslashes($deanswer["result"][$i]);
            $val = $val . "('" . $deanswer["subqid"][$i] . "','" . $deanswer["result"][$i] . "','" . $pid . "')";
            $tagfindsqid = 1;
            break;
        }
    }
    if ($tagfindsqid == 0) {
        $val = $val . "('" . $row['sno_subquestion'] . "','','" . $pid . "')";
    }
    $val = $val . ",";
}
$val = substr_replace($val, '', -1);
/*
if($num>=1)
$val="('".$deanswer["subqid"][0]."','".$deanswer["result"][0]."','".$pid."')";
if($num>1){
for($i=1;$i<$num;$i++)
$val=$val.",('".$deanswer["subqid"][$i]."','".$deanswer["result"][$i]."','".$pid."')";
}
 */
$sql = "INSERT INTO `hesprrs_questionnaires_results`( `sno_subquestion`, `result`, `sno_projects`) VALUES " . $val;
$result = $conn->query($sql);
$sql = "UPDATE `hesprrs_projects_data` SET `isfilledresult`=1 WHERE `sno_option_projects` ='" . $pid . "'";
$result = $conn->query($sql);
echo json_encode(array(
    'msg' => '200',
    'qid' => $val
));
