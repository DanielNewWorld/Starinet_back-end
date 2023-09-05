<?php

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

$ip=array();
$ip[]='178.0.0.0';
$ip[]='178.255.255.255';
$ip[]='0.0.0.0';
$ip[]='127.0.0.1';
$ip[]='251.56.5.210';
$ip[]='255.56.5.210';
$ip[]='10.0.0.1';
$ip[]='192.168.0.1';
$ip[]='191.168.0.1';
$ip[]='193.168.0.1';

foreach ($ip as $v){
  echo $v." -> ".inet_aton($v)." | ".inet_aton($v)." -> ".inet_ntoa(inet_aton($v))."<br>\n\r";
}

?> 
