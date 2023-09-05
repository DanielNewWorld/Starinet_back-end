<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД cards
  $db='st_cn';
  $table='cards';
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
$line="SELECT * FROM `".$table."` WHERE `id` LIKE '".$_GET['dbNomer']."' AND `pin` LIKE '".$_GET['dbPin']."'";
$result = mysql_query($line);

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {

  $arr = array(
               'dbID' => $row[0],
               'dbPIN' => $row[1],
               'dbNominal' => $row[2],
               'dbDateExp' => $row[6],
               'dbDateUse' => $row[7],
               'dbIP' => $row[8],
               'dbAccountID' => $row[9],
               'dbLogin' => $row[10],
               'dbUsed' => $row[11],
               'dbISP' => $row[12],
	       'chekError' => '4'
              );
}

$today = date("Y-m-d H:i:s");

  if ($arr['dbID']==$_GET['dbNomer'] and $arr['dbPIN']==$_GET['dbPin'] and $arr['dbDateExp']<$today) {
    $arr['chekError']=2;}

  if ($arr['dbID']==$_GET['dbNomer'] and $arr['dbPIN']==$_GET['dbPin'] and $arr['dbDateExp']>$today and $arr['dbUsed']==0) {
    $dbIP=inet_aton($_GET['dbIP']);
    $line="UPDATE `".$db."`.`".$table."` SET `account_id` = '".$_GET['dbAccountID'].
                                         "', `ip` = '".$dbIP.
                                         "', `login` = '".$_GET['dbLogin'].
                                         "', `date_use` = '".$today.
                                         "', `used` = '1'".
                                         " WHERE `".$table."`.`id` = ".$_GET['dbNomer'];
    $result = mysql_query($line);
    
    $host = 'localhost';
    $user = "root";
    $password = "manager";

    //БД users
    $db='st_cn';
    $table='users';

    // Производим попытку подключения к серверу MySQL:
    $dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());
    
    // Выбираем базу данных:
    mysql_select_db($db) or die("Не могу подключиться к базе.");
    mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    mysql_query("SET CHARACTER SET 'utf8'");

    $balance=$_GET['dbBalance']+$arr['dbNominal'];
    $line="UPDATE `".$db."`.`".$table."` SET `balance` = '".$balance."' WHERE `".$table."`.`account_id` = ".$_GET['dbAccountID'];
    $result = mysql_query($line);

    $arr['chekError']=0;}

  if ($arr['dbID']=='' and $arr['dbPIN']=='') {
    $arr['chekError']=1;}

  if ($arr['dbID']!=$_GET['dbNomer'] or $arr['dbPIN']!=$_GET['dbPin']) {
    $arr['dbID']=$_GET['dbNomer'];
    $arr['dbPIN']=$_GET['dbPin'];
    $arr['chekError']=1;}

  if ($arr['dbID']==$_GET['dbNomer'] and $arr['dbPIN']==$_GET['dbPin'] and $arr['dbDateExp']>$today and $arr['dbUsed']==1) {
    $arr['chekError']=3;}

echo json_encode($arr);
?>
