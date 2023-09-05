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

$today = date("d");
//$today=1;
if ($today > 5) {
  $chekError=1;
  $arr['chekError']=$chekError;
}

if ($arr['dbCredit'] < 2 and $today<6 and $chekStatus==4) {
    $line="UPDATE ".$table." SET `credit` = '".$_GET['dbCredit']."' WHERE `users`.`account_id` = ".$_GET['dbAccountID'];
    $result = mysql_query($line);

    $arr['dbIP']=inet_ntoa($arr['dbIP']);
    $today = date("Y-m-d H:i:s");
    $todayEnd = date("Y-m-6");

    $line="INSERT INTO `express_credit`(`user_id`, `access_ip`, `balance`, `credit`, `date_start`, `date_end`, `is_active`) VALUES (".$arr['dbID'].",'".$arr['dbIP']."',".$arr['dbBalance'].",".$_GET['dbCredit'].",'".$today."','".$todayEnd."',1)";
    $result = mysql_query($line);

    $chekError=0;
    $arr['chekError']=$chekError;
  }

if ($arr['dbCredit'] > 1)
  {
    $chekError=2;
    $arr['chekError']=$chekError;
  }

echo json_encode($arr);
?>
