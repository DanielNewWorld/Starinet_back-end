<!DOCTYPE html>
  <!--
  ver 16.07.2015
  -->
<html>

<head>
  <title>Работа с БД</title>
  <meta charset="utf-8">
</head>

  <body>

  <h1>Скрипт работает с БД</h1>

<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";
  $db='test';

  echo "Название БД: $db";
// Производим попытку подключения к серверу MySQL:
$dbh = mysql_connect($host, $user, $password) or die("Не могу соединиться с MySQL:".mysql_error());

// Выбираем базу данных:
mysql_select_db($db) or die("Не могу подключиться к базе.");

// Выводим заголовок таблицы:
echo "<table border=\"1\" width=\"100%\" bgcolor=\"#FFFFE1\">";
echo "<tr><td>Логин</td><td>ФИО</td><td>Лицевой счет</td><td>Пароль</td><td>Баланс</td><td>Сумма к списанию</td><td>Тарифный план</td></tr>";

//$result = mysql_query("SELECT * FROM `users` ");
while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
    //printf("ID: %s  Имя: %s", $row[0], $row[1]);
  echo "<td>$row[1]</td><td>$row[21]</td><td>$row[4]</td><td>$row[5]</td><td>$row[15]</td><td>?</td><td>?</td>";
  echo "</tr>";
  $dbLogin = $row[1];
  $dbFIO = $row[21];
  $dbNDogovor = $row[4];
  $dbPassword=$row[5];
  $dbBalans = $row[15];
  $dbSpisanie = "?";
  $dbTariff = "?";
}

echo "</table>";
$today = date("Y-m-")."0";
echo $today;
?>
  <h2>№ договора:</h2><?php echo $dbNDogovor?>
  <h2>Пароль:</h2><?php echo $dbPassword?>
  <h2>ФИО:</h2><?php echo $dbFIO?>
  <h2>Баланс:</h2><?php echo $dbBalans?>
  <h2>Сумма к списанию:</h2><?php echo $dbSpisanie?>
  <h2>Тарифный план:</h2><?php echo $dbTariff?>

<form action="phpinfo.php" method="GET">
  <input type="text" name="hello">
</form>

  </body>

</html>

