<?php

// server name...note that the php code and the database are going to be on the same server...therefore, a server name of "localhost" is used
$servername="localhost"; 

// mysql username
$username="group3";

// mysql password
$password="jan29m";

// mysql database name
$db_name="group3";

// character set
$charset = "utf8";

// pdo connection...note that "dsn" is acronym for "data source name" and includes info to connect to a database and could optionally identify the character set to be used
$dsn = "mysql:host=$servername; dbname=$db_name; charset=$charset";

// create an associative array of connection options...note syntax of "[index/key => value]" 
$opt = [PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];


// establish a connection as a PDO object
$connection = new PDO($dsn, $username, $password, $opt);


?>

 
