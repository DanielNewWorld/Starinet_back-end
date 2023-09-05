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

// Выводим заголовок таблицы:
//$line="SELECT * FROM `".$table."` WHERE `name` LIKE '".$_GET['dbLogin']."' AND `passwd` LIKE '".$_GET['dbPass']."'";
$line="UPDATE ".$table." SET `next_tariff` = '".$_GET['dbChangeTP']."' WHERE `users`.`account_id` = ".$_GET['dbAccountID'];
$result = mysql_query($line);

$chekError=0;
$arr = array('chekError' => $chekError);

echo json_encode($arr);
?>

