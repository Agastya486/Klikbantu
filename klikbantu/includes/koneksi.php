<?php
$username = "root";
$password = "";
$host = "localhost";
$dbname = "klikbantu_db";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>