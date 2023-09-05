<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table="email_inform";
  $arr=array();

function inet_aton($ip){
  $ip = ip2long($ip);
  ($ip < 0) ? $ip+=4294967296 : true;
  return $ip;
}

function inet_ntoa($int){
  // long2ip принимает на вход также беззнаковые
  // INT, т.е. полностью идентичен inet_ntoa
  return long2ip($int);
}

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8'");

$chekError = 5;
$arr = array('chekError' => $chekError);
$arr['dbActive'] = 3;

if ($_GET['status'] == 2) {
  $line="SELECT * FROM `".$table."` WHERE `user_id` = ".$_GET['dbUserID'];
  $result = mysql_query($line);
  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    $arr['dbID'] = $row[0];    
    $arr['dbEMail'] = $row[1];
    $arr['dbActive'] = $row[4];
  }

  if ($arr['dbActive'] == 1) {
    $chekError = 0;
    $arr['chekError']=$chekError;
  }

  if ($arr['dbActive'] == 0) {
    $chekError = 1;
    $arr['chekError']=$chekError;
  }

  if ($_GET['dbUserID'] != $arr['dbID']) {
    $chekError = 4;
    $arr['chekError']=$chekError;
  }

}

if ($_GET['status'] == 1) {
  $line="DELETE FROM `st_cn`.`email_inform` WHERE `email_inform`.`user_id` = ".$_GET['dbUserID'];
  $result = mysql_query($line);
  $chekError = 2;
  $arr['chekError']=$chekError;
}

if ($_GET['status'] == 0) {
  $today = date("Y-m-d H:i:s");
  $line="INSERT INTO `email_inform`(`user_id`, `email`, `add_date`, `confirm`, `active`) VALUES ('".$_GET['dbUserID']."','".$_GET['email']."','".$today."','android','1')";
  $result = mysql_query($line);
  $chekError = 3;
  $arr['chekError']=$chekError;
}

echo json_encode($arr);
?>
