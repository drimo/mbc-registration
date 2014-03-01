<?php //logout.php - logs out and destrys session
//James Hayes
//12-12/02

//destroy all session variables
session_start();
session_destroy();

//redirect browser back to administration login
header("Location: ./homepage.php");
?>
