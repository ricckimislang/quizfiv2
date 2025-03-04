<?php
session_start();
session_unset();
session_destroy();
header("location: ../students/index.php");
exit();
