<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table='users';
  $arr=array();

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

// Получаем номер тикета
$line = "SELECT * FROM `".$table."`";
$result = mysql_query($line);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  if ($row[0] > 6000 ) {
    $line = "DELETE FROM `st_cn`.`users` WHERE `users`.`id` = ".$row[0];
    mysql_query($line);
    echo "Удаление: ".$row[0];
  }

}

?>
