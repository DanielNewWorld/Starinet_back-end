<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table="users";
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

$chekError=4;
$chekStatus=0;

$line="SELECT * FROM `".$table."` WHERE `users`.`account_id` = ".$_GET['dbAccountID'];
$result = mysql_query($line);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  
  if ($row[21]=="Заблокирован") {
    $chekStatus=1;
    $chekError=3;}
  if ($row[21]=='Заморожен') {
    $chekStatus=2;
    $chekError=3;}
  if ($row[21]=='Инфицирован') {
    $chekStatus=3;
    $chekError=3;}
  if ($row[21]=="Активен") {
    $chekStatus=4;
    $chekError=3;}

  $arr = array('dbID' => $row[0],
               'dbBalance' => $row[2],
               'dbCredit' => $row[3],
               'dbIP' => $row[7],
               'dbStatus' => $row[21],
               'dbISP' => $row[28],
               'chekStatus' => $chekStatus,
	       'chekError' => $chekError);
}

$line="SELECT * FROM `freeze` WHERE `user_id` = ".$arr['dbID'];
$result = mysql_query($line);

$today = date("Y-m-");
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
//$row[1]="2015-12-23 17:27:57";
//$row[1]>$today
  if ($row[0]==$arr['dbID'] and $row[1]>$today."0" and $row[1]<$today."31 23:59:59") {
    $chekError=2;
    $arr['chekError']=$chekError;}
}

if ($_GET['chekStatusUPDATE'] == 2 and $chekError != 2) {
    $line="UPDATE `".$table."` SET `balance` = ".$_GET['dbBalanceUPDATE'].", `status`='Заморожен' WHERE `account_id` = ".$_GET['dbAccountID'];
    $result = mysql_query($line);

    $today = date("Y-m-d H:i:s");
    $arr['dbIP']=inet_ntoa($arr['dbIP']);
    $line="INSERT INTO `freeze`(`user_id`, `dates`, `ip`) VALUES ('".$arr['dbID']."','".$today."','".$arr['dbIP']."')";
    $result = mysql_query($line);

    $chekError=0;
    $arr['chekError']=$chekError;
  }

if ($_GET['chekStatusUPDATE'] == 4) {
    $line="UPDATE `".$table."` SET `balance` = ".$_GET['dbBalanceUPDATE'].", `status`='Активен' WHERE `account_id` = ".$_GET['dbAccountID'];
    $result = mysql_query($line);

    $chekError=1;
    $arr['chekError']=$chekError;
  }

echo json_encode($arr);
?>
