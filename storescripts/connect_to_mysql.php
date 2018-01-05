<?php

$db_host = "83.226.17.35";
$db_username = "Beldrama";
$db_pass = "password";
$db_name = "login";

$connect = mysql_connect("$db_host", "$db_username", "$db_pass") or die("Couldn't connect");
mysql_select_db("$db_name") or die("No database found");
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=login', 'Beldrama', 'password');
?>