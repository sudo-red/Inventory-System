<?php

$conn = mysqli_connect('','','','');


$host_name = "";
$database = ""; // Change your database nae
$username = "";          // Your database user id 
$password = "";          // Your password
try {
$dbo = new PDO('mysql:host='.$host_name.';dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}
?>
