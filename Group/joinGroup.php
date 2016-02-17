<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../index.html?InvalidLogin");
}

$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$groupId = $_POST['group_id'];
$username = $_SESSION['username'];
$authorized = 0;
$query4 = $link->prepare("INSERT INTO belongs_to(group_id, username, authorized)
VALUES (?,?,?)");
$query4->bind_param('ssi', $groupId, $username, $authorized);
$query4->execute();
$query4->close();
/*if (strcmp($_SESSION['lastLoc'],"group") == 0) {
    header("location:group.php");
}*/
header("location:group.php");
//header("location:../homepage.php");
?>