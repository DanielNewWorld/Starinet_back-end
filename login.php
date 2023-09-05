<?php // серверная часть вывода json

$login = $_POST['login'];
$pass = $_POST['pass'];
if($login == "user" & $pass == "pass") {
?>      {
"data":[
{
"firstName":"John",
"lastName":"Doe"
},
{
"firstName":"Anna",
"lastName":"Smith"
},
{
"firstName":"Peter",
"lastName":"Jones"
}
]
}<?php }
?> 
