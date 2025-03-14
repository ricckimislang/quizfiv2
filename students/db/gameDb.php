<?php
$servername = "localhost";
$username = "root"; // Default WAMP username
$password = ""; // Default WAMP password
$dbname = "game";

// Create connection
$connGame = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($connGame->connect_error) {
    die("Connection failed: " . $connGame->connect_error);
}?>