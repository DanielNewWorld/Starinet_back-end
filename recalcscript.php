<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";

//БД users
  $db='st_cn';
  $table="recalc";
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

$chekError = 6;

if ($_GET['status'] == 2)
 {
  $line="SELECT * FROM `".$table."` WHERE `user_id` = ".$_GET['dbID'];
  //$line="SELECT * FROM `".$table."` WHERE `user_id` = 5307";
  $result = mysql_query($line);
  while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    if ($row[12] == -1) {$row[12] = 'В перерасчете отказано';}
    if ($row[12] == 0) {$row[12] = 'Ожидание ответа';}
    if ($row[12] == 1) {$row[12] = 'Перерасчет выполнен';}

    if ($_GET['dbID'] != $row[1]) {
      $chekError = 1;}

    if ($_GET['dbID'] == $row[1]) {
      $chekError = 0;}

    $arr[] = array('chekError' => $chekError,
               'dbID' => $row[1],               
               'dbTel' => $row[4]."\n"."\n",
               'dbData' => $row[5]."\n"."\n",
               'dbPeriod' => $row[7]." - \n".$row[8]."\n",
               'dbMessage' => "вы писали: ".$row[9]."\nответ: ".$row[10]."\n",
               'dbStatus' => $row[12]."\n"."\n"
              );
  }
}

if ($_GET['status'] == 0) {
  $today = date("Y-m-d H:i:s");
  $line="INSERT INTO `recalc`(`id`, `user_id`, `account_id`, `fio`, `tel_mob`, `date_queue`, `date_answer`, `period_start`, `period_end`, `message`, `adm_answer`, `admin_id`, `status`) VALUES (NULL,'".$_GET['dbID']."','".$_GET['dbAccountID']."','".$_GET['dbFIOReCalc']."','".$_GET['dbTelReCalc']."','".$today."','0','".$_GET['edtHomeString']."','".$_GET['edtEndString']."','".$_GET['edtComment']."','','0','0')";
  $result = mysql_query($line);

  $chekError = 3;
  $arr[] = array('chekError' => $chekError);
}

echo json_encode($arr);
?>
