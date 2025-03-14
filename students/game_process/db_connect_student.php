<?php
$connStudent = new mysqli("localhost", "root", "", "quizfi");
if ($connStudent->connect_error) {
    die("Connection failed: " . $connStudent->connect_error);
}
?>

