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
$line="SELECT * FROM `".$table."` WHERE `name` LIKE '".$_GET['dbLogin']."' AND `passwd` LIKE '".$_GET['dbPass']."'";
//$line="SELECT * FROM `".$table."`";
$result = mysql_query($line);

$chekStatus=0;
$chekError=2;
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
  if ($row[21]=="Заблокирован") {$chekStatus=1;}
  if ($row[21]=='Заморожен')    {$chekStatus=2;}
  if ($row[21]=='Инфицирован')  {$chekStatus=3;}
  if ($row[21]=="Активен")      {$chekStatus=4;}

  $arr = array('dbID' => $row[0],
               'dbAccountID' => $row[1],
               'dbBalance' => $row[2],
               'dbCredit' => $row[3],
               'dbInetStatus' => $row[4],
               'dbTariff' => $row[5],
               'dbIP' => $row[7],
               'dbMASK' => $row[8],
               'dbIPReal' => $row[11],
               'dbLogin' => $row[12],
               'dbPassword' => $row[13],
               'dbFIO' => $row[14],
	       'dbDom' => $row[16],
	       'dbLetter' => $row[17],
	       'dbFlat' => $row[18],
	       'dbTel' => $row[19],
	       'dbTelMob' => $row[20],
               'dbDates' => $row[22],
               'dbISP' => $row[28],
               'dbStreet_id' => $row[29],

               'chekStatus' => $chekStatus,
	       'chekError' => $chekError,
	       'dbDataNews' => '',
	       'dbNews' => 'Новых уведомлений нет',
	       'dbCost' => '',
	       'dbIPRealStatus' => '0',
	       'dbCity_id' => '?');
}

  if ($arr['dbLogin']==$_GET['dbLogin'] and $arr['dbPassword']==$_GET['dbPass']) {
    $arr['chekError']=0;}

  if ($arr['dbLogin']=='' and $arr['dbPassword']=='') {
    $arr['chekError']=1;}

  if ($arr['dbLogin']!=$_GET['dbLogin'] or $arr['dbPassword']!=$_GET['dbPass']) {
    $arr['chekError']=1;}

if ($arr['dbIP']>2986344448 and $arr['dbIP']<3003121663) {$arr['dbIPRealStatus']=2;}
$arr['dbIP']=inet_ntoa($arr['dbIP']);
$arr['dbMASK']=inet_ntoa($arr['dbMASK']);

if ($arr['dbIPReal']!=0) {$arr['dbIPRealStatus']=1;}
$arr['dbIPReal']=inet_ntoa($arr['dbIPReal']);

//БД tariff
  $db='st_cn';
  $table='tariff';

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8'");

// Выводим заголовок таблицы:
$line="SELECT * FROM `".$table."` WHERE `id` LIKE '".$arr['dbTariff']."'";
$result = mysql_query($line);

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$arr['dbTariff']=$row[1];
$arr['dbCost']=$row[4];
}

//БД streets
  $db='st_cn';
  $table='streets';

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8'");

// Выводим заголовок таблицы:
$line="SELECT * FROM `".$table."` WHERE `id` LIKE '".$arr['dbStreet_id']."'";
//$line="SELECT * FROM `streets` WHERE `id` LIKE '7'";
$result = mysql_query($line);

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$arr['dbCity_id']=$row[1];
$arr['dbStreet_id']="ул. ".$row[2]." ".$arr['dbDom'].$arr['dbLetter']." кв. ".$arr['dbFlat'];
}

//БД city
  $db='st_cn';
  $table='city';

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8'");

// Выводим заголовок таблицы:
$line="SELECT * FROM `".$table."` WHERE `id` LIKE '".$arr['dbCity_id']."'";
$result = mysql_query($line);

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$arr['dbCity_id']=$row[1];
}

//БД news
  $db='st_cn';
  $table='news';

// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8'");

// Выводим заголовок таблицы:
$line="SELECT * FROM `".$table."`";
$result = mysql_query($line);

while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
$arr['dbDataNews']=$row[1];
$arr['dbNews']=$row[2];
}

echo json_encode($arr);
?>

