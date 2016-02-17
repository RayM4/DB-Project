<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location:../index.html?InvalidLogin");
}

$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$groupName = $_POST['groupName'];
$description = $_POST['description'];
$leader = $_SESSION['username'];

$groupInterest = $_POST['groupInterest'];

$query = $link->prepare("INSERT INTO groups(group_name, description, username)
VALUES (?,?,?)");
$query->bind_param('sss', $groupName, $description, $leader);
$query->execute();

//$query->close();


$query2 = $link->prepare("SELECT group_id from groups WHERE group_name = ?");
$query2->bind_param('s', $groupName);
$query2->execute();
$query2->store_result();
$query2->bind_result($gID);
$query2->fetch();
echo $gID;
echo ", ";
echo $groupInterest;

$query3 = $link->prepare("INSERT INTO about(interest_name, group_id)
VALUES (?,?)");
$query3->bind_param('ss', $groupInterest, $gID);
$query3->execute();
$query3->close();

$authorized = 1;
$query4 = $link->prepare("INSERT INTO belongs_to(group_id, username, authorized)
VALUES (?,?,?)");
$query4->bind_param('ssi', $gID, $leader, $authorized);
$query4->execute();
$query4->close();


$_SESSION['groupCreated'] = true;
header("location:../homepage.php");

?>