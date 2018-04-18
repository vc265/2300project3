<?php
// the following code was taken from Lab #8

// hash passwords for database
$password = $_GET["password"];
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>
