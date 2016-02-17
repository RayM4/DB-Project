<?php
//This file is for registeration
//$link = mysqli_connect("127.0.0.1", "raym4", "", "meetup");
$link = new mysqli("localhost", "raym4", "", "meetup");
    
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$zipcode = $_POST['zipcode'];
$username = $_POST['newUsername'];
$password = md5($_POST['newPassword']);

$query = mysqli_prepare($link, "SELECT username, password FROM member WHERE username = ? ");
mysqli_stmt_bind_param($query, 's', $username);
mysqli_stmt_execute($query);
mysqli_stmt_store_result($query);
$rows = mysqli_stmt_num_rows($query);
mysqli_stmt_bind_result($query, $user);
mysqli_stmt_fetch($query);

if ($rows == 0) {
    $query = $link->prepare("INSERT INTO member (username, password, firstname, lastname, zipcode)
    VALUES (?,?,?,?,?)");
    
    $query->bind_param('sssss', $username, $password, $firstname, $lastname, $zipcode);
    
    $query->execute();
    
    $query->close();
    //$link()->close();
    header("location:index.php"); //direct to homepage
} else {
    header("location:index.php?invalidUser");
}


?>