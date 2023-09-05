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

//Poluchatel
$line="SELECT * FROM `".$table."` WHERE `users`.`account_id` = ".$_GET['accountIDOplata'];
$result = mysql_query($line);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  
  $arr = array('dbIDOplata' => $row[0],
               'dbAccountIDOplata' => $row[1],
               'dbBalanceOplata' => $row[2],
               'dbAccountID' => '0',
               'dbBalance' => '0',
               'dbUserID' => '0',
	       'dbIP' => '0',
	       'chekError' => $chekError);
}

//Tot kto platit
$line="SELECT * FROM `".$table."` WHERE `account_id` = ".$_GET['dbAccountID'];
$result = mysql_query($line);
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  $arr['dbUserID'] = $row[0];
  $arr['dbAccountID'] = $row[1];
  $arr['dbBalance'] = $row[2];
  $arr['dbIP'] = $row[7];
}

if ($_GET['accountIDOplata'] != $arr['dbAccountIDOplata']) {
    $chekError = 1;
    $arr['chekError']=$chekError;
  }

if ($_GET['accountIDOplata'] == $arr['dbAccountIDOplata']) {
    $balance = $arr['dbBalanceOplata'] + $_GET['oplata'];
    $line="UPDATE `".$table."` SET `balance` = ".$balance." WHERE `account_id` = ".$_GET['accountIDOplata'];
    $result = mysql_query($line);

    $today = date("Y-m-d H:i:s");
    $arr['dbIP']=inet_ntoa($arr['dbIP']);
//Arhiv plategey
    $line="INSERT INTO `st_cn`.`payments` (`id`, `user_id`, `account_id`, `fio`, `balance_before`, `balance_after`, `internet`, `summa`, `dates`, `microtime`, `comments`, `payment_type`, `deleted`, `ip`, `who`, `who_money`) VALUES (NULL, '".$arr['dbIDOplata']."', '".$arr['dbAccountIDOplata']."', 'NULL', '".$arr['dbBalanceOplata']."', '".$balance."', '".$_GET['oplata']."', '".$_GET['oplata']."', '".$today."', '0', '".$_GET['comment']."', '70', '0', '".$arr['dbIP']."', 'Transfere', 'SP');";
    $result = mysql_query($line);

    $balance = $arr['dbBalance'] - $_GET['oplata'];
    $line="UPDATE `".$table."` SET `balance` = ".$balance." WHERE `account_id` = ".$_GET['dbAccountID'];
    $result = mysql_query($line);

//Arhiv spisanij
    $line="INSERT INTO `st_cn`.`debit` (`id`, `user_id`, `account_id`, `fio`, `what`, `summa`, `balance_before`, `balance_after`, `dates`, `microtime`, `type`, `ip`, `who`, `is_deleted`) VALUES (NULL, '".$arr['dbUserID']."', '".$arr['dbAccountID']."', '', '".$_GET['comment']."', '-".$_GET['oplata']."', '".$arr['dbBalance']."', '".$balance."', '".$today."', '0', '70', '".$arr['dbIP']."', 'android', '0')";
    $result = mysql_query($line);

    $chekError = 0;
    $arr['chekError']=$chekError;
  }

echo json_encode($arr);
?>
