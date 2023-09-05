<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД cards
  $db='st_cn';
  $table='payments';
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
$result = mysql_query($line);

//if ($result != 0) {$chekError = 0;}
//if ($result == 0) {$chekError = $result;} //не работает
$chekError = 0;

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

   if ($row[11]==1 or $row[11]==2 or $row[11]==3 or $row[11]==4) {$row[11]='Наличные';}
   if ($row[11]==20 or $row[11]==21 or $row[11]==22 or $row[11]==24 or $row[11]==25 or $row[11]==26) {$row[11]='Безналичный платеж';}
   if ($row[11]==30 or $row[11]==50) {$row[11]='Visa / WebMoney';}
   if ($row[11]==31 or $row[11]==32) {$row[11]='Пополнение через терминал самообслуживания';}
   if ($row[11]==33) {$row[11]='Платеж Администратора';}
   if ($row[11]==60 or $row[11]==61) {$row[11]='Пополнение картой';}
   if ($row[11]==70) {$row[11]='Услуга "Пополни другу"';}
   if ($row[11]==71) {$row[11]='Услуга антивирус Dr.Web';}
   if ($row[11]==80) {$row[11]='Система';}

   if ($row[11]==23 or $row[11]==35) {$row[11]='Система';} //ne snau nasnachenie

   $arr[] = array('chekError' => $chekError,
               'dbBalanceBefore' => $row[4],               
               'dbBalanceAfter' => $row[5],               
               'dbInternet' => $row[6],
               'dbSumma' => $row[7],
               'dbDateTime' => $row[8],
               'dbComments' => $row[10],
               'dbType' => $row[11]
              );
}

echo json_encode($arr);
?>
