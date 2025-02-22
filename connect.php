<?php
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password for local setup
$dbname = "myshop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
