<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location:index.html?InvalidLogin");
}

$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$eventId = $_POST['event_id'];
$username = $_SESSION['username'];
$rsvp = 1;
$rating = 10;

$query = $link->prepare("INSERT INTO attend(event_id, username, rsvp, rating) VALUEs (?,?,?,?)") or die("Interest already exist");
$query->bind_param('ssss', $eventId, $username, $rsvp, $rating);
$query->execute();
$query->close();
//$_SESSION['rsvp'] = true;
header("location:homepage.php");

?>