<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table='akcii';
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

$chekError = 2;
$status = 0; //неактивная акция
$today = date("Y-m-d H:i:s");
//$today = "2013-05-01 00:01:47";

// Получаем инф-ию
$line="SELECT * FROM `".$table."` WHERE `user_id` LIKE '".$_GET['dbID']."'";
$result = mysql_query($line);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  $chekError = 0;

  if ($row[3] < $today and $today < $row[4]) {
    $status = 1;
  }
  else $status = 0;

  $arr[] = array('chekError' => $chekError,
                 'sharesName' => $row[2],
                 'sharesdataStart' => $row[3],
                 'sharesdataEnd' => $row[4],
                 'sharesStatus' => $status);
}

echo json_encode($arr);
?>

