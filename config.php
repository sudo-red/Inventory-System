<?php

$conn = mysqli_connect('localhost','root','','user_db');


$host_name = "localhost";
$database = "user_db"; // Change your database nae
$username = "root";          // Your database user id 
$password = "";          // Your password
try {
$dbo = new PDO('mysql:host='.$host_name.';dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}
?>