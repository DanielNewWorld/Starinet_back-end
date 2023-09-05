<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table='users';
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
$line="SELECT * FROM `".$table."` WHERE `id` LIKE '".$_GET['dbID']."'";
$result = mysql_query($line);

$chekError=2;
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  $arr = array('dbID' => $row[0],
               'dbPassword' => $row[13],
               'dbISP' => $row[28],
	       'chekError' => $chekError);
}

  if ( $arr['dbID']==$_GET['dbID']) {
    $line="UPDATE `st_cn`.`users` SET `passwd` = '".$_GET['dbNewPass']."' WHERE `users`.`id` = ".$_GET['dbID'];
    $result = mysql_query($line);
    $arr['chekError']=0;}

  if ($arr['dbID']!=$_GET['dbID']) {
    $arr['chekError']=1;}

echo json_encode($arr);
?>

