<?php
  $host = 'localhost';
  $user = "root";
  $password = "manager";
  $db='wallpaper';
  $arr = array();

$json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

//var_dump(json_decode($json));
//var_dump(json_decode($json, true));

$arr=json_decode($json);
print $arr->{"a"};

$json = json_encode(
    array(
        1 => array(
            'English' => array(
                'One',
                'January'
            ),
            'French' => array(
                'Une',
                'Janvier'
            )
        )
    )
);

print $json;

?>
