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

//new = Новый
//close = Закрыто (Решено)
//unswered = Ожидание ответа от абонента
//onhold = Ожидание ответа от оператора

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8'");

$chekError=2;

// Запись текста тикета
  $today = date("Y-m-d H:i:s");
  $line="INSERT INTO `tickets_data`(`ticket_id`, `user_id`, `admin_id`, `created`, `text`, `files`, `ip`) VALUES (".$_GET['ticketsID'].",'".$_GET['dbID']."',0,'".$today."','".$_GET['dbText']."','','')";
  $result = mysql_query($line);
  $arr['chekError'] = 0;

echo json_encode($arr);
?>

