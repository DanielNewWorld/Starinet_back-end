<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД cards
  $db='st_cn';
  $table='debit';
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
$result = mysql_query($line);

//if ($result != 0) {$chekError = 0;}
//if ($result == 0) {$chekError = $result;} //не работает
$chekError = 0;

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

   if ($row[10]==50) {$row[10]="Платеж Администратора";}
   if ($row[10]==70) {$row[10]="Услуга 'Пополни другу'";}
   if ($row[10]==71) {$row[10]="Услуга антивирус Dr.Web";}
   if ($row[10]==80) {$row[10]="Система";}

   $arr[] = array('chekError' => $chekError,
               'dbComments' => $row[4],
               'dbSumma' => $row[5],
               'dbBalanceBefore' => $row[6],               
               'dbBalanceAfter' => $row[7],               
               'dbDateTime' => $row[8],
               'dbType' => $row[10]
              );
}

echo json_encode($arr);
?>
