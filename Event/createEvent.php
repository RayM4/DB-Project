<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../index.html?InvalidLogin");
}

$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

//LOOK IN THE [] FOR THE NAMES MIKE
$title = $_POST['title'];
$description = $_POST['description'];
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'];
$startTime = $_POST['start_time'];
$endTime = $_POST['end_time'];
$groupId = $_POST['groupId']; //convert this to id before inserting into events
$loc = $_POST['locationName']; //drop down of locations
$zip = $_POST['zip'];

$start = $start = date("Y-m-d H:i:s", mktime(substr($startTime, 0,2),substr($startTime, 3, 2), substr($startTime, 6, 2), 
substr($startDate, 5, 2), substr($startDate, 8, 2), substr($startDate, 0, 4)));
$end = date("Y-m-d H:i:s", mktime(substr($endTime, 0,2),substr($endTime, 3, 2), substr($endTime, 6, 2), 
substr($endDate, 5, 2), substr($endDate, 8, 2), substr($endDate, 0, 4)));
//var_dump($start);
//var_dump($end);

$query = $link->prepare("INSERT INTO events (title, description, start_time, end_time, group_id, lname, zip)
VALUES (?,?,?,?,?,?,?)");

$query->bind_param('ssssisi', $title, $description, $start, $end, $groupId, $loc, $zip);

$query->execute();

if (!$query) {
    $_SESSION['eventCreated'] = false;
} else {
    $_SESSION['eventCreated'] = true;
}

$query->close();



header("location:../homepage.php");

?>