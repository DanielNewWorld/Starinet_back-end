<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table='tickets';
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
$arr = array('chekError' => $chekError,
               'dbStatus' => '',
               'dbPriority' => 0);

//вызывается когда ответное сообщение
//new = Новый
//close = Закрыто (Решено)
//unswered = Ожидание ответа от абонента
//onhold = Ожидание ответа от оператора
  if ($_GET['ticketsStatus'] == 0) $status = "new";
  if ($_GET['ticketsStatus'] == 1) $status = "close";
  if ($_GET['ticketsStatus'] == 2) $status = "unswered";
  if ($_GET['ticketsStatus'] == 3) $status = "onhold";

  $line="UPDATE `".$db."`.`".$table."` SET `status` = '".$status."', `priority` = ".$_GET['ticketsPriority']." WHERE `".$table."`.`id` = ".$_GET['ticketsID'];
  $result = mysql_query($line);

// Получаем инф-ию о тикете
$line="SELECT * FROM `".$table."` WHERE `id` LIKE '".$_GET['ticketsID']."'";
$result = mysql_query($line);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  $chekError = 0;
  $arr['chekError'] = $chekError;
  $arr['dbStatus'] = $row[7];
  $arr['dbPriority'] = $row[8];
}

echo json_encode($arr);
?>

