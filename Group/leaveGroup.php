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

$query = $link->prepare("DELETE FROM belongs_to WHERE group_id = ? AND username = ?");
$query->bind_param('ss',$groupId, $username);
$query->execute();
$query->close();
if ($_SESSION['lastLoc'] == "group") {
    header("location:group.php");
}
header("location:../homepage.php");
?>