<?php
// Azure exposes MySQL connection strings with the prefix MYSQLCONNSTR_
$servername = getenv('MYSQLCONNSTR_DB_HOST');
$username   = getenv('MYSQLCONNSTR_DB_USER');
$password   = getenv('MYSQLCONNSTR_DB_PASS');
$dbname     = getenv('MYSQLCONNSTR_DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
