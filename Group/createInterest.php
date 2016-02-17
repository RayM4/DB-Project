<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../index.html?InvalidLogin");
}

$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$iname = $_POST['interestName'];
$query = $link->prepare("INSERT INTO interest (interest_name) VALUEs (?)") or die("Interest already exist");
$query->bind_param('s', $iname);
$query->execute();
$query->close();
$_SESSION['interestCreated'] = true;
header("location:group.php");
?>