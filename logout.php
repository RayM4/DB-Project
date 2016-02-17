<?php   
session_start(); //checks for same session
session_destroy(); 
header("location:index.php?LoggedOut"); //redirects back to "index.php" after logging out
exit();
?>