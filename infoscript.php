<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД cards
  $db='st_cn';
  $table='info';
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

// Выводим заголовок таблицы:
$line="SELECT * FROM `".$table."` WHERE `account_id`=".$_GET['dbAccountID']." AND `dates`>'".$_GET['dbDataHome']."' AND `dates`<'".$_GET['dbDataEnd']."'";
//$line="SELECT * FROM `".$table."` WHERE `account_id`=0127277 AND `dates`>'2014.1.1' AND `dates`<'2015.4.18 23:59:59'";
//$line="SELECT * FROM `".$table."` WHERE `account_id`=".$_GET['dbAccountID'];
$result = mysql_query($line);

//if ($result != 0) {$chekError = 0;}
//if ($result == 0) {$chekError = $result;} //не работает
$chekError = 0;

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

   $arr[] = array('chekError' => $chekError,
               'dbComments' => $row[4],
               'dbDateTime' => $row[5]
              );
}

echo json_encode($arr);
?>
